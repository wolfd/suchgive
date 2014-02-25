<?php
class About extends CI_Controller {
	
	public function index()
	{
		$data['logged_in'] = $this->ion_auth->logged_in();
		$data['title'] = "suchgive! about";
		$data['active_page'] = "about";
		$this->load->view('header', $data);
		$this->load->view('about', $data);
		$this->load->view('footer');
	}
}