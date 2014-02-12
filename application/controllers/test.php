<?php
//import settings hidden from git
require_once(APPPATH.'config/suchgive_config.php');
class Test extends CI_Controller {

    public function index(){
        $this->view->load('moon');
    }
}