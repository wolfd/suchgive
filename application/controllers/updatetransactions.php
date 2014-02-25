<?php
if( PHP_SAPI != 'cli') show_404('updatetransactions'); //nothing to see here folks.
set_time_limit(0); //infinite time!!!
//import settings hidden from git
require_once(APPPATH.'config/suchgive_config.php');
define('MINIMUM_CONFIRMATIONS_FINAL', 3);
define('MINIMUM_CONFIRMATIONS_GUESS', 0);
define('TRANSACTION_DELTA', 100);

class UpdateTransactions extends CI_Controller {


    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        require_once(APPPATH.'libraries/jsonRPCClient.php');
        $this->rpc = new jsonRPCClient(DOGECOIND_RPC_URL);
    }

    public function update()
    {
        $this->_update();
    }

    private function _update()
    {
        $charities = $this->_getCurrentCharity();

        if (!(empty($charities) || empty($charities['zero_account']) || empty($charities['one_account'])
                                || empty($charities['zero_id'])      || empty($charities['one_id'])))
        {
            $this->_updateSingle($charities['zero_account'], $charities['zero_id']);
            $this->_updateSingle($charities['one_account'], $charities['one_id']);
            $this->_checkForWinner($charities);
        }

        $this->_updateTransactionTable();
        $this->_recheckLastXTransactions(500);
        $this->_checkUnconfirmed();
        log_message(LOG_INFO, 'updated transactions');
    }

    //'prelaunch','active','contested','zerowin','onewin','archived'

    // If battle is active, determine if it should end and set the database accordingly.
    private function _checkForWinner($battle_info)
    {
        if (!(empty($battle_info) || empty($battle_info['zero_account']) || empty($battle_info['one_account'])
            || empty($battle_info['zero_id'])      || empty($battle_info['one_id'])
            || empty($battle_info['battle_id'])    || empty($battle_info['battle_status'])))
        {
            if ($battle_info['battle_status'] == 'active')
            {
                $zero_confirmed = $this->JSONtoAmount($this->rpc->getreceivedbyaccount($battle_info['zero_account'], MINIMUM_CONFIRMATIONS_FINAL)) / 2;
                $one_confirmed = $this->JSONtoAmount($this->rpc->getreceivedbyaccount($battle_info['one_account'], MINIMUM_CONFIRMATIONS_FINAL)) / 2;
                $funding_goal = $this->JSONtoAmount($battle_info['funding_goal']);
                //http://stackoverflow.com/a/2215360/831768
                $mysqltime = date ("Y-m-d H:i:s", time());

                // Check for confirmed victory
                if ($zero_confirmed >= $funding_goal || $one_confirmed >= $funding_goal)
                {
                    if ($zero_confirmed >= $funding_goal && $one_confirmed >= $funding_goal)
                    {
                        //Wat.
                        //Check the blockchain manually to see which charity reached it first.
                        $response = $this->db->query(  'UPDATE charity_battles
                                                        SET status = \'contested\', end_date = \'?\'
                                                        WHERE charity_battles.id =?;',
                                                        array($mysqltime,
                                                            $battle_info['battle_id']));
                    }
                    elseif ($zero_confirmed >= $funding_goal)
                    {
                        //Great. We have a winner.
                        $response = $this->db->query(  'UPDATE charity_battles
                                                        SET status = \'zerowin\', end_date = \'?\'
                                                        WHERE charity_battles.id =?;',
                                                        array($mysqltime,
                                                              $battle_info['battle_id']));
                    }
                    elseif ($one_confirmed >= $funding_goal)
                    {
                        //Great. We have a winner.
                        $response = $this->db->query(  'UPDATE charity_battles
                                                        SET status = \'onewin\', end_date = \'?\'
                                                        WHERE charity_battles.id =?;',
                                                        array($mysqltime,
                                                            $battle_info['battle_id']));
                    }
                }
            }
        }
    }

    private function _recheckLastXTransactions($x)
    {
        // get all dogecoin addresses ever made (in this server)
        $response = $this->db->query('SELECT id, account, transactions_recorded FROM charities');
        foreach ($response->result() as $charity)
        {
            $account = $charity->account;
            $from = max(intval($charity->transactions_recorded) - $x, 0);
            $transactions = $this->rpc->listtransactions($account, TRANSACTION_DELTA, $from);
            $did_at_least_one = false;
            $chunk_size = 0;
            while (count($transactions) > 0) {
                $chunk_size = count($transactions);
                foreach ($transactions as $tx) {
                    $this->db->query("INSERT INTO `give`.`transactions` (`charity_id`, `address_id`, `user_id`, `tx_id`, `shibetoshi`, `confirmed`, `time_received`)
                                      SELECT  a.related_charity,
                                              a.id,
                                              a.related_user,
                                              '?',
                                              ?,
                                              ?,
                                              ?
                                      FROM addresses a
                                      WHERE a.address = '?'
                                      LIMIT 1
                                      ON DUPLICATE KEY UPDATE
                                          `confirmed` = ?,
                                          `time_received` = ?;",
                                      array($tx['txid'],
                                            $this->JSONtoAmount($tx['amount']),
                                            ($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0),
                                            $tx['timereceived'],
                                            $tx['address'],
                                            ($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0),
                                            $tx['timereceived']));
                }
                $from = $from + TRANSACTION_DELTA;
                $did_at_least_one = true;
                $transactions = $this->rpc->listtransactions($account, TRANSACTION_DELTA, $from);
            }
            if ($did_at_least_one)
            {
                //update database with a new count of confirmed transactions (as to not exponentially overwhelm the server)
                //but first, subtract TRANSACTION_DELTA from $from to get the last successful range (will cause some overlap, but not much)
                $from = $from - TRANSACTION_DELTA + $chunk_size;
                $this->db->query("UPDATE `give`.`charities` SET `transactions_recorded` = '?' WHERE `charities`.`id` = ?;",
                    array(  $from,
                            $charity->id));
            }
        }
    }

    private function _updateSingle($charity_account, $charity_id)
    {
        //update global records
        //getreceivedbyaccount

        $shibetoshi_received = $this->JSONtoAmount($this->rpc->getreceivedbyaccount($charity_account, MINIMUM_CONFIRMATIONS_GUESS));
        $response = $this->db->query(  'UPDATE `give`.`charities`
                                        SET `shibetoshi_received` = ?
                                        WHERE `charities`.`id` = ?;',
            array(  $shibetoshi_received,
                    $charity_id));
    }

    private function _updateTransactionTable()
    {
        // get all dogecoin addresses ever made (in this server)
        $response = $this->db->query('SELECT id, account, transactions_recorded FROM charities');
        foreach ($response->result() as $charity)
        {
            $account = $charity->account;
            $count = 1;
            $from = intval($charity->transactions_recorded);
            $transactions = $this->rpc->listtransactions($account, $count, $from);
            $did_at_least_one = false;
            $chunk_size = 0;
            while (count($transactions) > 0) {
                $chunk_size = count($transactions);
                foreach ($transactions as $tx) {
                    $this->db->query("INSERT INTO `give`.`transactions` (`charity_id`, `address_id`, `user_id`, `tx_id`, `shibetoshi`, `confirmed`, `time_received`)
                                      SELECT  a.related_charity,
                                              a.id,
                                              a.related_user,
                                              '?',
                                              ?,
                                              ?,
                                              ?
                                      FROM addresses a
                                      WHERE a.address = '?'
                                      LIMIT 1
                                      ON DUPLICATE KEY UPDATE
                                          `confirmed` = ?,
                                          `time_received` = ?;",
                                  array($tx['txid'],
                                        $this->JSONtoAmount($tx['amount']),
                                        ($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0),
                                        $tx['timereceived'],
                                        $tx['address'],
                                        ($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0),
                                        $tx['timereceived']));
                }
                $from = $from + $count;
                $did_at_least_one = true;
                $transactions = $this->rpc->listtransactions($account, $count, $from);
            }
            if ($did_at_least_one)
            {
                //update database with a new count of confirmed transactions (as to not exponentially overwhelm the server)
                //but first, subtract $count from $from to get the last successful range (will cause some overlap, but not much)
                $from = $from - $count + $chunk_size;
                $this->db->query(  "UPDATE `give`.`charities`
                                    SET `transactions_recorded` = ?
                                    WHERE  `charities`.`id` = ?;",
                    array(  $from,
                            $charity->id));
            }


            //var_dump($transactions);
        }

    }

    private function _checkUnconfirmed()
    {
        // get all dogecoin addresses ever made (in this server)
        $response = $this->db->query('SELECT transactions.tx_id FROM transactions WHERE transactions.confirmed = 0');
        foreach ($response->result() as $transaction)
        {
            $tx = $this->rpc->gettransaction($transaction);

            $this->db->query("  UPDATE transactions
                                SET `confirmed` = ?
                                WHERE transactions.tx_id = ?;",
                array( ($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0),
                        $tx['txid']));
        }
    }

    //returns the currently active charity in array format.
    private function _getCurrentCharity()
    {
        $query = $this->db->query( 'SELECT cb.related_charity_zero,
                                    cb.related_charity_one,
                                    cb.funding_goal,
                                    cb.start_date,
                                    cb.end_date,
                                    cb.description AS battle_description,
                                    cb.id AS battle_id,
                                    cb.status AS battle_status,
                                    c0.id AS zero_id,
                                    c1.id AS one_id,
                                    c0.name AS zero_name,
                                    c1.name AS one_name,
                                    c0.url AS zero_url,
                                    c1.url AS one_url,
                                    c0.description AS zero_description,
                                    c1.description AS one_description,
                                    c0.account AS zero_account,
                                    c1.account AS one_account
                                    FROM  `charity_battles` cb
                                    JOIN charities c0
                                    ON cb.related_charity_zero = c0.id
                                    JOIN charities c1
                                    ON cb.related_charity_one = c1.id
                                    WHERE cb.active=1
                                    LIMIT 1');
        return $query->row_array();
    }

    private function JSONtoAmount($value) {
        return round($value * 1e8);
    }
}


?>