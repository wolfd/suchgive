<?php
class Top extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->library('ion_auth');
        $this->load->library('table');
    }

    public function index()
    {
        $data['logged_in'] = $this->ion_auth->logged_in();
        $data['title'] = "suchgive!";
        $data['active_page'] = "top";

        $current = $this->_getCurrentCharity();
        $data['battle'] = $current;

        $data['top_ten_table_current_zero'] = $this->_getTopTenTableCharity($current['zero_id']);
        $data['top_ten_table_current_one'] = $this->_getTopTenTableCharity($current['one_id']);
        $data['top_ten_table_all'] = $this->_getTopTenTableAll();


        $this->load->view('header', $data);
        $this->load->view('top', $data);
        $this->load->view('footer');
    }

    private function _getTopTenTableAll()
    {
        $query_string = 'SELECT users.nickname AS `nickname`, SUM(transactions.shibetoshi) / 100000000 AS `doge`
                         FROM users
                         JOIN transactions ON transactions.shibetoshi
                         WHERE transactions.user_id = users.id
                         GROUP BY `nickname`
                         ORDER BY `doge` DESC
                         LIMIT 10';

        $response = $this->db->query($query_string);

        $tmpl = array('table_open' => '<table class="table table-bordered text-center" style="width: auto; padding: 10px;">');

        $this->table->set_template($tmpl);

        return $this->table->generate($response);
    }


    private function _getTopTenTableCharity($id)
    {
        $query_string = 'SELECT users.nickname AS `nickname`, SUM(transactions.shibetoshi) / 100000000 AS `doge`
                         FROM users
                         JOIN transactions ON transactions.shibetoshi
                         WHERE transactions.user_id = users.id AND transactions.charity_id = '.$id.'
                         GROUP BY `nickname`
                         ORDER BY `doge` DESC
                         LIMIT 10';

        $response = $this->db->query($query_string);

        $tmpl = array('table_open' => '<table class="table table-bordered text-center" style="width: auto; padding: 10px;">');

        $this->table->set_template($tmpl);

        return $this->table->generate($response);
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
}
