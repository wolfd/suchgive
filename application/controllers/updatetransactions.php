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
        $success_zero = $this->_updateSingle($charities['zero_account'], $charities['zero_id']);
        $success_one = $this->_updateSingle($charities['one_account'], $charities['one_id']);

        $this->_updateTransactionTable();
        $this->_recheckLastXTransactions(500);
    }

    private function _updateAll()
    {
        //for every charity


    }

    private function checkFrom($x)
    {
        // get all dogecoin addresses ever made (in this server)
        $response = $this->db->query('SELECT id, account, transactions_recorded FROM charities');
        foreach ($response->result() as $charity)
        {
            $account = $charity->account;
            $from = $x;
            $transactions = $this->rpc->listtransactions($account, TRANSACTION_DELTA, $from);
            $did_at_least_one = false;
            $chunk_size = 0;
            while (count($transactions) > 0) {
                $chunk_size = count($transactions);
                foreach ($transactions as $tx) {
                    $this->db->query("INSERT INTO `give`.`transactions` (`charity_id`, `address_id`, `user_id`, `tx_id`, `shibetoshi`, `confirmed`, `time_received`)
										SELECT 	a.related_charity,
												a.id,
												a.related_user,
												'".$tx['txid']."',
												".$this->JSONtoAmount($tx['amount']).",
												".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0).",
												".$tx['timereceived']."
											FROM addresses a
											WHERE a.address = '".$tx['address']."'
											LIMIT 1
										ON DUPLICATE KEY UPDATE
											`confirmed` = '".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0)."',
											`time_received` = ".$tx['timereceived'].";");
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
                $update_transactions_recorded_string = "UPDATE  `give`.`charities` SET  `transactions_recorded` =  '".$from."' WHERE  `charities`.`id` =".$charity->id.";";
                $this->db->query($update_transactions_recorded_string);
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
										SELECT 	a.related_charity,
												a.id,
												a.related_user,
												'".$tx['txid']."',
												".$this->JSONtoAmount($tx['amount']).",
												".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0).",
												".$tx['timereceived']."
											FROM addresses a
											WHERE a.address = '".$tx['address']."'
											LIMIT 1
										ON DUPLICATE KEY UPDATE
											`confirmed` = '".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0)."',
											`time_received` = ".$tx['timereceived'].";");
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
                $update_transactions_recorded_string = "UPDATE  `give`.`charities` SET  `transactions_recorded` =  '".$from."' WHERE  `charities`.`id` =".$charity->id.";";
                $this->db->query($update_transactions_recorded_string);
            }
        }
    }

    private function _updateSingle($charity_account, $charity_id)
    {
        //update global records
        //getreceivedbyaccount

        //0.00000000

        $shibetoshi_received = $this->JSONtoAmount($this->rpc->getreceivedbyaccount($charity_account, MINIMUM_CONFIRMATIONS_GUESS));
        $update_query_string = 'UPDATE  `give`.`charities` SET  `shibetoshi_received` =  \''.$shibetoshi_received.'\' WHERE  `charities`.`id` ='.$charity_id.';';
        $response = $this->db->query($update_query_string);


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
										SELECT 	a.related_charity,
												a.id,
												a.related_user,
												'".$tx['txid']."',
												".$this->JSONtoAmount($tx['amount']).",
												".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0).",
												".$tx['timereceived']."
											FROM addresses a
											WHERE a.address = '".$tx['address']."'
											LIMIT 1
										ON DUPLICATE KEY UPDATE
											`confirmed` = ".($tx['confirmations'] > MINIMUM_CONFIRMATIONS_FINAL ? 1 : 0).",
											`time_received` = ".$tx['timereceived'].";");
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
                $update_transactions_recorded_string = "UPDATE  `give`.`charities` SET  `transactions_recorded` =  '".$from."' WHERE  `charities`.`id` =".$charity->id.";";
                $this->db->query($update_transactions_recorded_string);
            }


            //var_dump($transactions);
        }

    }

    //returns the currently active charity in array format.
    private function _getCurrentCharity()
    {
        $querystring = 'SELECT cb.related_charity_zero,
								cb.related_charity_one,
								cb.funding_goal,
								cb.start_date,
								cb.end_date,
								cb.description AS battle_description,
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
								LIMIT 1';
        $query = $this->db->query($querystring);
        return $query->row_array();
    }

    private function JSONtoAmount($value) {
        return round($value * 1e8);
    }
}


?>
