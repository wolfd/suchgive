<?php
class Top extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

    public function index()
    {
        $data['logged_in'] = $this->ion_auth->logged_in();
        $data['title'] = "suchgive!";
        $data['active_page'] = "top";

        $data['top_ten_table_current'] = "";
        $data['top_ten_table_all'] = $this->_getTopTenTableALL();


        $this->load->view('header', $data);
        $this->load->view('top', $data);
        $this->load->view('footer');
    }

    private function _getTopTenTableAll()
    {
        $user = $this->ion_auth->user()->row();

        $query_string = 'SELECT users.nickname AS `nickname`,
                                SUM (transactions.shibetoshi) / 100000000 AS `doge`
                         FROM users
                         JOIN transactions ON transactions.user_id = users.id
                         GROUP BY `nickname`
                         ORDER BY `doge`';

        $response = $this->db->query($query_string);

        $tmpl = array('table_open'  => '<table class="table table-bordered text-center">');

        $this->table->set_template($tmpl);

        return $this->table->generate($response);
    }
}