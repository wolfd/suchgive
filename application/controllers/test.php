<?php
//import settings hidden from git
require_once(APPPATH.'config/suchgive_config.php');
class Test extends CI_Controller {

    public function index(){
        $data['logged_in'] = $this->ion_auth->logged_in();
        $data['title'] = "suchgive!";
        $data['active_page'] = "test";

        $this->load->view('header', $data);
        $this->load->view('moon');
        $this->load->view('footer');
    }
}