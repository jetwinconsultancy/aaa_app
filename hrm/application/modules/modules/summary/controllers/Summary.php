<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Summary extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('summary/summary_model');
        $this->load->library(array('encryption'));
        
    }

    public function index()
    {   
        $this->data['User'] = $this->user_id;
        $this->meta['page_name'] = 'Weekly Summary';
        $bc   = array(array('link' => '#', 'page' => 'Weekly Summary'));
        $meta = array('page_title' => 'Weekly Summary', 'bc' => $bc, 'page_name' => 'Weekly Summary');

        $this->data['active_tab'] = $this->session->userdata('tab_active');
        $this->session->unset_userdata('tab_active');

        $this->data['updated_or_completed_list'] = $this->summary_model->get_updated_or_completed_list();
        $this->data['job_remain_list'] = $this->summary_model->get_job_remain_list();

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function show_details($request = NULL, $emp_id = NULL)
    {  
        $this->meta['page_name'] = 'Weekly Summary';
        $bc = array(array('link' => '#', 'page' => 'Weekly Summary'));
        $meta = array('page_title' => 'Weekly Summary', 'bc' => $bc, 'page_name' => 'Weekly Summary');

        $this->data['request'] = $request;
        $emp_name = $this->summary_model->get_emp_name($emp_id);
        $this->data['status_updated_list'] = $this->summary_model->get_status_updated_list($emp_id);
        $this->data['remark_updated_list'] = $this->summary_model->get_remark_updated_list($emp_id);
        $this->data['completed_list'] = $this->summary_model->get_completed_list($emp_id);
        $this->data['emp_job_remain_list'] = $this->summary_model->get_emp_job_remain_list($emp_id);

        $this->load->library('mybreadcrumb');
        if($request == 'JUC'){
            $this->session->set_userdata("tab_active", "no_job_completed_updated");
            $this->mybreadcrumb->add('Summary', base_url('summary'));
            $this->mybreadcrumb->add('Job Updated or Completed - '.$emp_name, base_url());
        }
        else if($request == 'JR'){
            $this->session->set_userdata("tab_active", "no_job_remain");
            $this->mybreadcrumb->add('Summary', base_url('summary'));
            $this->mybreadcrumb->add('Job Remain - '.$emp_name, base_url());
        }
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('summary/show_details.php', $meta, $this->data);
    }

}
