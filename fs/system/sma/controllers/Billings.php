<?php defined('BASEPATH') OR exit('No direct script access allowed');

class billings extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('login');
        }
        $this->load->library('form_validation');
        $this->load->model('db_model');
    }

    public function index()
    {
        $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $this->data['sales'] = $this->db_model->getLatestSales();
        // $this->data['quotes'] = $this->db_model->getLastestQuotes();
        // $this->data['purchases'] = $this->db_model->getLatestPurchases();
        // $this->data['transfers'] = $this->db_model->getLatestTransfers();
        // $this->data['customers'] = $this->db_model->getLatestCustomers();
        // $this->data['suppliers'] = $this->db_model->getLatestSuppliers();
        // $this->data['chatData'] = $this->db_model->getChartData();
        // $this->data['stock'] = $this->db_model->getStockValue();
        // $this->data['bs'] = $this->db_model->getBestSeller();
        // $this->data['users'] = $this->db_model->getUserList();
        // $lmsdate = date('Y-m-d', strtotime('first day of last month')) . ' 00:00:00';
        // $lmedate = date('Y-m-d', strtotime('last day of last month')) . ' 23:59:59';
        // $this->data['lmbs'] = $this->db_model->getBestSeller($lmsdate, $lmedate);
        $bc = array(array('link' => '#', 'page' => lang('Billings')));
        $meta = array('page_title' => lang('Billings'), 'bc' => $bc, 'page_name' => 'Billings');
		// $this->data['page_name'] = 'Clients';
        $this->page_construct('billings.php', $meta, $this->data);

    }

}
