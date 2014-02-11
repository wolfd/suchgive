<?php
class Account extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('ion_auth');
		$this->load->library('form_validation');
		$this->load->library('table');
		$this->load->helper('url');

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
			$data['title'] = "suchgive!";
			$data['active_page'] = "account";

			$data['account_data'] = $this->_getAccountData();

			$this->load->view('header', $data);
			$this->load->view('account', $data);
			$this->load->view('footer');
		}
	}

	public function login() 
	{
		$this->ion_auth->login($this->input->post('email'), $this->input->post('password'));

		redirect('/', 'refresh');
	}

	public function signup() 
	{
		require_once(APPPATH.'libraries/recaptchalib.php');

		$data['logged_in'] = $this->ion_auth->logged_in();
		$data['title'] = "suchgive!";
		$data['active_page'] = "account";
		$data['recaptcha'] = "";


		$this->load->helper(array('form', 'url'));

		$this->load->library('form_validation');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|is_unique[users.email]');
		$this->form_validation->set_rules('nickname', 'Username', 'required|max_length[32]|xss_clean|is_unique[users.nickname]');
		$this->form_validation->set_rules('password', 'Password', 'required|alpha_dash|min_length[8]|xss_clean');
		$this->form_validation->set_rules('passwordconfirm', 'Password Confirmation', 'required|matches[password]|xss_clean');
		$this->form_validation->set_rules('anonymous', 'anonymous');

		$this->load->view('header', $data);

		// run signup form validation
		if ($this->form_validation->run() == false)
		{	
			$this->load->view('signup', $data);
		}
		else
		{
			// Validate reCAPTCHA if rest of form was valid
			$resp = recaptcha_check_answer (RECAPTCHA_PRIVATE_KEY,
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
			if ($resp->is_valid) 
			{
				$this->load->view('signupsuccess');

				$email    = strtolower($this->input->post('email'));
				$password = $this->input->post('password');

				$additional_data = array(
					'nickname' => $this->input->post('nickname'),
					'anonymous'  => $this->input->post('anonymous'),
				);

				$this->ion_auth->register($email, $password, $email, $additional_data);
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

	private function _getAccountData()
	{
		$account['donation_table'] = $this->_getDonationTable();
		$account['total_donated'] = $this->_updateTotal();
		return $account;
	}

	private function _getDonationTable()
	{
		$user = $this->ion_auth->user()->row();

		$query_string = '	SELECT 	charities.name AS `Charity Name`, 
									transactions.shibetoshi / 100000000 AS `Doge`, 
									FROM_UNIXTIME(transactions.time_received) AS `Time`, 
									CASE transactions.confirmed
										WHEN 0 THEN  \'<span class="glyphicon glyphicon-time"></span>\'
										WHEN 1 THEN  \'<span class="glyphicon glyphicon-ok"></span>\'
										ELSE \'error\'
									END AS `Confirmed`
							FROM transactions
							JOIN charities ON transactions.charity_id = charities.id
							WHERE transactions.user_id ='.$user->id.'
							ORDER BY transactions.time_received DESC;';
		$response = $this->db->query($query_string);

		$tmpl = array('table_open'  => '<table class="table table-bordered text-center">');

		$this->table->set_template($tmpl);

		return $this->table->generate($response);
	}

	private function _updateTotal()
	{
		$user = $this->ion_auth->user()->row();
		$response = $this->db->query('SELECT SUM(`shibetoshi`) FROM `transactions` WHERE transactions.user_id ='.$user->id.';');

		return $response->row_array()['SUM(`shibetoshi`)'];
	}





}
?>