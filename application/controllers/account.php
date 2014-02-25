<?php
class Account extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('table');
        $this->load->helper('url');
        require_once(APPPATH.'config/suchgive_config.php');

        $this->load->database();

        $this->form_validation->set_error_delimiters(
            $this->config->item('error_start_delimiter', 'ion_auth'),
            $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
    }

    //redirect if needed, otherwise display the user list
    public function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('/', 'refresh');
        }
        else
        {
            $data['logged_in'] = true;
            $data['title'] = "suchgive! account";
            $data['active_page'] = "account";

            $data['account_data'] = $this->_getAccountData();

            $this->load->view('header', $data);
            $this->load->view('account', $data);
            $this->load->view('footer');
        }
    }

    public function login()
    {
        $this->ion_auth->login($this->input->post('nickname'), $this->input->post('password'));

        redirect('/', 'refresh');
    }

    public function signup()
    {
        if (RECAPTCHA_ENABLED) require_once(APPPATH.'libraries/recaptchalib.php');

        $data['logged_in'] = $this->ion_auth->logged_in();
        $data['title'] = "suchgive!";
        $data['active_page'] = "account";
        $data['recaptcha'] = "";

        $this->form_validation->set_rules('nickname', 'Username', 'required|max_length[32]|xss_clean|is_unique[users.nickname]');
        $this->form_validation->set_rules('password', 'Password', 'required|alpha_dash|min_length[8]|xss_clean');
        $this->form_validation->set_rules('passwordconfirm', 'Password Confirmation', 'required|matches[password]|xss_clean');

        $this->load->view('header', $data);

        // run signup form validation
        if ($this->form_validation->run() == false)
        {
            $this->load->view('signup', $data);
        }
        else
        {
            // Validate reCAPTCHA if rest of form was valid
            if (RECAPTCHA_ENABLED)
            {
            $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
                $this->input->server('REMOTE_ADDR'),
                $this->input->post('recaptcha_challenge_field'),
                $this->input->post('recaptcha_response_field'));
            }

            if ($resp->is_valid || !RECAPTCHA_ENABLED)
            {
                $this->load->view('signupsuccess');

                $nickname = $this->input->post('nickname');
                $password = $this->input->post('password');

                $this->ion_auth->register($nickname, $password, $nickname);
            }
            else
            {
                $data['recaptcha'] = "The reCAPTCHA wasn't entered correctly. Go back and try it again." .
                    "(reCAPTCHA said: " . $resp->error . ")";
                $this->load->view('signup', $data);
            }

        }
        $this->load->view('footer');
    }

    public function logout()
    {
        $this->ion_auth->logout();
        redirect('/', 'refresh');
    }

    public function change()
    {
        if (RECAPTCHA_ENABLED) require_once(APPPATH.'libraries/recaptchalib.php');

        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('/', 'refresh');
        }
        else
        {
            $data['logged_in'] = true;
            $data['title'] = "suchgive! change account";
            $data['active_page'] = "account";

            $data['account_data'] = $this->_getAccountData();

            $this->load->view('header', $data);

            // If the user is changing the account, load the associated messages in views
            $this->form_validation->set_rules('nicknamechange', 'Username', 'max_length[32]|xss_clean|is_unique[users.nickname]');
            $nickname = $this->input->post('nickname');
            $password = $this->input->post('password');
            $this->form_validation->set_rules('passwordchange', 'Password', 'alpha_dash|min_length[8]|xss_clean');
            $this->form_validation->set_rules('passwordchangeconfirm', 'Password Confirmation', 'matches[password]|xss_clean');



            if ($this->form_validation->run() && $this->ion_auth->login($nickname, $password))
            {
                if (RECAPTCHA_ENABLED)
                {
                $resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
                    $this->input->server('REMOTE_ADDR'),
                    $this->input->post('recaptcha_challenge_field'),
                    $this->input->post('recaptcha_response_field'));
                }

                if ($resp->is_valid || !RECAPTCHA_ENABLED)
                {
                    $new_nickname = $this->input->post('nicknamechange');
                    $new_password = $this->input->post('passwordchange');
                    if (!empty($new_password))
                    {
                        $data_account['success'] = $this->_changePassword($new_password);
                        $data_account['reason'] = "";
                        $this->load->view('changeaccountsuccess', $data_account);
                    }

                    if (!empty($new_nickname))
                    {
                        $data_account['success'] = $this->_changeNickname($new_nickname);
                        $data_account['reason'] = "";
                        $this->load->view('changeaccountsuccess', $data_account);
                    }
                }
                else
                {
                    $data_account['success'] = false;
                    $data_account['reason'] = "Captcha not entered correctly";
                    $this->load->view('changeaccountsuccess', $data_account);
                }

            }

            $this->load->view('changeaccount', $data);
            $this->load->view('footer');
        }
    }

    private function _changePassword($new_pass)
    {
        $user = $this->ion_auth->user()->row();
        return $this->ion_auth->update($user->id, array(
            'password' => $new_pass
        ));
    }

    private function _changeNickname($new_nick)
    {
        $user = $this->ion_auth->user()->row();
        return $this->ion_auth->update($user->id, array(
            'nickname' => $new_nick,
            'email' => $new_nick,
            'username' => $new_nick
        ));
    }

    private function _getAccountData()
    {
        $account['donation_table'] = $this->_getDonationTable();
        $account['total_donated'] = $this->_updateTotal();
        $account['nickname'] = $this->ion_auth->user()->row()->username;
        return $account;
    }

    private function _getDonationTable()
    {
        $user = $this->ion_auth->user()->row();

        $response = $this->db->query(
            '	SELECT 	charities.name AS `charity`,
			        transactions.shibetoshi / 100000000 AS `doge`,
                    FROM_UNIXTIME(transactions.time_received) AS `time`,
                    CASE transactions.confirmed
                        WHEN 0 THEN  \'<span class="glyphicon glyphicon-time"></span>\'
                        WHEN 1 THEN  \'<span class="glyphicon glyphicon-ok"></span>\'
                        ELSE \'error\'
                    END AS `confirmed`
                FROM transactions
                JOIN charities ON transactions.charity_id = charities.id
                WHERE transactions.user_id = ?
                ORDER BY transactions.time_received DESC;',
            array($user->id));

        $template = array('table_open'  => '<table class="table table-bordered text-center">');

        $this->table->set_template($template);

        return $this->table->generate($response);
    }

    private function _updateTotal()
    {
        $user = $this->ion_auth->user()->row();
        $response = $this->db->query('SELECT SUM(`shibetoshi`) FROM `transactions` WHERE transactions.user_id = ?;',
                                     array($user->id));

        return $response->row_array()['SUM(`shibetoshi`)'];
    }
}
?>
