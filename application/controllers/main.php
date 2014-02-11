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
        if (isset($data['battle']) && isset($data['battle']['related_charity_zero']))
        {
            $data['battle_running'] = true;
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
            $data['battle']['zero_account'] = "";
            $data['battle']['one_account'] = "";
            $data['battle']['zero_shibetoshi'] = "0";
            $data['battle']['one_shibetoshi'] = "0";
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

            if (isset($addresses))
            {
                $data['zero_address'] = $addresses['zero'];
                $data['one_address'] = $addresses['one'];
            }

        }

        $this->load->view('header', $data);
        $this->load->view('moon', $data);
        $this->load->view('mainview', $data);
        $this->load->view('footer');
    }

    //responds to ajax requests about the current standings in json format
    public function update()
    {

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
								c1.account AS one_account,
								c0.shibetoshi_received AS zero_shibetoshi,
								c1.shibetoshi_received AS one_shibetoshi
								FROM  `charity_battles` cb
								JOIN charities c0
								ON cb.related_charity_zero = c0.id
								JOIN charities c1
								ON cb.related_charity_one = c1.id
								WHERE cb.active=1
								LIMIT 1';
        $query = $this->db->query($querystring);
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
        if (isset($user->id) && isset($battle['related_charity_zero']) && isset($battle['related_charity_one'])) {
            // query for existing addresses
            $query_zero = $this->db->query('SELECT * FROM  `addresses` WHERE  `related_user` ='.$user->id.' AND  `related_charity` ='.$battle['related_charity_zero']);
            $query_one = $this->db->query('SELECT * FROM  `addresses` WHERE  `related_user` ='.$user->id.' AND  `related_charity` ='.$battle['related_charity_one']);

            // check to see if charity zero has an address for this user
            if ($query_zero->num_rows() > 0)
            {
                $zero = $query_zero->row_array();
                $addresses['zero'] = $zero['address'];
            }
            else // no address for zero exists, create one
            {
                try {
                    if (isset($battle['zero_account'])) {
                        $addresses['zero'] = $this->rpc->getnewaddress($battle['zero_account']); //generate a new address for this account
                        $insert_query_string = 'INSERT INTO `give`.`addresses` (`related_user`, `related_charity`, `address`) VALUES ('.$user->id.', '.$battle['related_charity_zero'].', \''.$addresses['zero'].'\');';

                        $insert_address_zero = $this->db->query($insert_query_string);
                        //if $insert_address_zero is 1, the insert went through fine
                    }

                } catch (Exception $e) {
                    error_log("Could not create dogecoin address for user: ".$user->email." possibly for the reasons stated here: $e the query string was $insert_query_string");
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
                    if (isset($battle['one_account'])) {
                        $addresses['one'] = $this->rpc->getnewaddress($battle['one_account']); //generate a new address for this account
                        $insert_query_string = 'INSERT INTO `give`.`addresses` (`related_user`, `related_charity`, `address`) VALUES ('.$user->id.', '.$battle['related_charity_one'].', \''.$addresses['one'].'\');';

                        $insert_address_one = $this->db->query($insert_query_string);
                        //if $insert_address_one is 1, the insert went through fine
                    }

                } catch (Exception $e) {
                    error_log("Could not create dogecoin address for user: ".$user->email." possibly for the reasons stated here: $e the query string was $insert_query_string");
                }
            }


        }

        return $addresses;
    }
}
?>
