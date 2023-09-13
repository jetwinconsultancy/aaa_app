<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_setting extends MY_Controller
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
        $bc = array(array('link' => '#', 'page' => lang('Setting')));
        $meta = array('page_title' => lang('Setting'), 'bc' => $bc, 'page_name' => 'Setting');
		
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id = '.$this->session->userdata("user_id"));
        //$this->db->where('group_id = 2');
        $this->db->order_by('id');
        $q = $this->db->get();

        if ($q->num_rows() > 0) 
        {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            $this->data['user_info'] = $data;
        }

        $this->page_construct('admin_setting.php', $meta, $this->data);

    }
    public function sav_company()
    {
		$this->sma->prints_arrays($_POST,$_FILES);
        // $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
        // $bc = array(array('link' => '#', 'page' => lang('Setting')));
        // $meta = array('page_title' => lang('Setting'), 'bc' => $bc, 'page_name' => 'Setting');
        // $this->page_construct('setting.php', $meta, $this->data);

    }
	public function save()
	{
		$company['company_name'] = $_POST['company_name'];
		$company['company_uen'] = $_POST['company_uen'];
		$company['company_phone'] = $_POST['company_phone'];
		$company['company_email'] = $_POST['company_email'];
		$company['company_fax'] = $_POST['company_fax'];
		$company['company_postcode'] = $_POST['company_postcode'];
		$company['company_street'] = $_POST['company_street'];
		$company['company_building'] = $_POST['company_building'];
		$company['company_unit'] = $_POST['company_unit'];
        $this->db->where('id', 1);
        // if ($this->db->update('companies', $data)) {
		$this->db->update('company',$company);
		$this->sma->print_arrays($_POST);
	}
}