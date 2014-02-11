<?php
class Contact extends CI_Controller {
	
	public function index()
	{
		$data['logged_in'] = $this->ion_auth->logged_in();
		$data['title'] = "suchgive!";
		$data['active_page'] = "contact";
		$this->load->view('header', $data);
		$this->load->view('contact', $data);
		$this->load->view('footer');
	}
}