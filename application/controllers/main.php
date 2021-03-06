<?php
//import settings hidden from git
require_once(APPPATH.'config/suchgive_config.php');
class Main extends CI_Controller {
    private $rpc;

    public function __construct()
    {
        parent::__construct();

        $this->load->database();

        require_once(APPPATH.'libraries/jsonRPCClient.php');
        $this->rpc = new jsonRPCClient(DOGECOIND_RPC_URL);
    }

    public function index()
    {
        $data['logged_in'] = $this->ion_auth->logged_in();
        $data['title'] = "suchgive!";
        $data['active_page'] = "home";

        //get info related to the current battle and it's standings
        $data['battle'] = $this->_getCurrentCharity();
        if (!(empty($data['battle']) || empty($data['battle']['related_charity_zero'])))
        {
            $zero_amount = $data['battle']['zero_shibetoshi'] / 2;
            $one_amount = $data['battle']['one_shibetoshi'] / 2;
            $reward_pool = $zero_amount + $one_amount;
            $funding_goal = $data['battle']['funding_goal'] * 1e8;

            $zero_percentage = (($funding_goal > 0) ? ($zero_amount / $funding_goal) * 100 : 0);
            $one_percentage = (($funding_goal > 0) ? ($one_amount / $funding_goal) * 100 : 0);

            $data['battle_running'] = true;
            $data['battle']['reward_shibetoshi'] = $reward_pool;
            $data['battle']['zero_shibetoshi'] = $zero_amount;
            $data['battle']['one_shibetoshi'] = $one_amount;
            // for noscript tags, in lieu of js updates
            $data['realtime'] = array(  'charity_zero_raised' => $zero_amount / 1e8,
                'charity_one_raised' => $one_amount / 1e8,
                'charity_zero_percentage' => $zero_percentage,
                'charity_one_percentage' => $one_percentage,
                'reward_pool_raised' => $reward_pool / 1e8);

        }
        else
        {
            $data['battle_running'] = false;
            $data['battle']['related_charity_zero'] = "";
            $data['battle']['related_charity_one'] = "";
            $data['battle']['funding_goal'] = "";
            $data['battle']['start_date'] = "";
            $data['battle']['end_date'] = "";
            $data['battle']['battle_description'] = "There are no races being run right now.";
            $data['battle']['zero_id'] = "";
            $data['battle']['one_id'] = "";
            $data['battle']['zero_name'] = "";
            $data['battle']['one_name'] = "";
            $data['battle']['zero_url'] = "";
            $data['battle']['one_url'] = "";
            $data['battle']['zero_description'] = "";
            $data['battle']['one_description'] = "";
            $data['battle']['zero_tag_line'] = "";
            $data['battle']['one_tag_line'] = "";
            $data['battle']['zero_account'] = "";
            $data['battle']['one_account'] = "";
            $data['battle']['zero_shibetoshi'] = "0";
            $data['battle']['one_shibetoshi'] = "0";
            $data['battle']['reward_shibetoshi'] = "0";
            // for noscript tags, in lieu of js updates
            $data['realtime'] = array(  'charity_zero_raised' => 0,
                'charity_one_raised' => 0,
                'charity_zero_percentage' => 0,
                'charity_one_percentage' => 0,
                'reward_pool_raised' => 0);
        }
        //get the current user if logged in
        if ($data['logged_in'])
        {
            $data['user'] = $this->ion_auth->user()->row(); //returns the current user's object
        }
        else
        {
            $data['user'] = false;
        }


        //if logged in, do things.
        if ($data['logged_in']) {

            //PROCESS DOGECOIN ADDRESS GENERATION - MAY LATER CHANGE TO GETTING THE ADDRESSES ON BUTTON CLICK
            $addresses = $this->_getAddressesForUser($data['user'], $data['battle']);

            if (!empty($addresses))
            {
                $data['zero_address'] = $addresses['zero'];
                $data['one_address'] = $addresses['one'];
            }

        }

        $this->load->view('header', $data);
        $this->load->view('moon', $data);
        $this->load->view('moonfooter', $data);
        $this->load->view('footer');
    }

    //responds to ajax requests about the current standings in json format
    public function update()
    {
        $data['battle'] = $this->_getCurrentCharity();
        if (!(empty($data['battle']) || empty($data['battle']['related_charity_zero'])))
        {
            $zero_amount = $data['battle']['zero_shibetoshi'] / 2;
            $one_amount = $data['battle']['one_shibetoshi'] / 2;
            $reward_pool = $zero_amount + $one_amount;
            $funding_goal = $data['battle']['funding_goal'] * 1e8;


            $zero_percentage = (($funding_goal > 0) ? ($zero_amount / $funding_goal) * 100 : 0);
            $one_percentage = (($funding_goal > 0) ? ($one_amount / $funding_goal) * 100 : 0);

            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array( 'charity_zero_raised' => $zero_amount / 1e8,
                    'charity_one_raised' => $one_amount / 1e8,
                    'charity_zero_percentage' => $zero_percentage,
                    'charity_one_percentage' => $one_percentage,
                    'reward_pool_raised' => $reward_pool / 1e8)));
        }
        else
        {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array( 'charity_zero_raised' => 0,
                    'charity_one_raised' => 0,
                    'charity_zero_percentage' => 0,
                    'charity_one_percentage' => 0,
                    'reward_pool_raised' => 0)));
        }
    }

    //returns the currently active charity in array format.
    private function _getCurrentCharity()
    {
        $query = $this->db->query(
           'SELECT cb.related_charity_zero,
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
            c0.tag_line AS zero_tag_line,
            c1.tag_line AS one_tag_line,
            c0.account AS zero_account,
            c1.account AS one_account,
            c0.shibetoshi_received AS zero_shibetoshi,
            c1.shibetoshi_received AS one_shibetoshi
            FROM  `charity_battles` cb
            JOIN charities c0
            ON cb.related_charity_zero = c0.id
            JOIN charities c1
            ON cb.related_charity_one = c1.id
            WHERE cb.active=1
            LIMIT 1');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        else
        {
            return;
        }

    }

    private function _getAddressesForUser($user, $battle)
    {
        $addresses['zero'] = "";
        $addresses['one'] = "";
        if (!(empty($user->id) || empty($battle['related_charity_zero']) || empty($battle['related_charity_one']))) {
            // query for existing addresses
            $query_zero = $this->db->query('SELECT * FROM  `addresses` WHERE  `related_user` = ? AND  `related_charity` = ?;',
                array(  $user->id,
                        $battle['related_charity_zero']));
            $query_one = $this->db->query('SELECT * FROM  `addresses` WHERE  `related_user` = ? AND  `related_charity` = ?;',
                array(  $user->id,
                        $battle['related_charity_one']));

            // check to see if charity zero has an address for this user
            if ($query_zero->num_rows() > 0)
            {
                $zero = $query_zero->row_array();
                $addresses['zero'] = $zero['address'];
            }
            else // no address for zero exists, create one
            {
                try {
                    if (!empty($battle['zero_account'])) {
                        $addresses['zero'] = $this->rpc->getnewaddress($battle['zero_account']); //generate a new address for this account

                        $insert_address_zero = $this->db->query(   'INSERT INTO `give`.`addresses` (`related_user`, `related_charity`, `address`)
                                                                    VALUES (?, ?, \'?\');',
                        array($user->id,
                            $battle['related_charity_zero'],
                            $addresses['zero']
                        ));
                        //if $insert_address_zero is 1, the insert went through fine
                    }

                } catch (Exception $e) {
                    error_log("Could not create dogecoin address for user: ".$user->username." possibly for the reasons stated here: $e");
                }
            }

            // check to see if charity one has an address for this user
            if ($query_one->num_rows() > 0)
            {
                $one = $query_one->row_array();
                $addresses['one'] = $one['address'];
            }
            else
            {
                try {
                    if (!empty($battle['one_account'])) {
                        $addresses['one'] = $this->rpc->getnewaddress($battle['one_account']); //generate a new address for this account
                        $insert_address_zero = $this->db->query(   'INSERT INTO `give`.`addresses` (`related_user`, `related_charity`, `address`)
                                                                    VALUES (?, ?, \'?\');',
                        array($user->id,
                            $battle['related_charity_one'],
                            $addresses['one']
                        ));
                        //if $insert_address_one is 1, the insert went through fine
                    }

                } catch (Exception $e) {
                    error_log("Could not create dogecoin address for user: ".$user->username." possibly for the reasons stated here: $e");
                }
            }


        }

        return $addresses;
    }
}
?>