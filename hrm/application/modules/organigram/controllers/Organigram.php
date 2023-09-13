<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Organigram extends MX_Controller
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
        $this->load->model('organigram/organigram_model');
        
    }

    public function index()
    {   
        $this->meta['page_name'] = 'Org Chart';
        $bc   = array(array('link' => '#', 'page' => 'Org Chart'));
        $meta = array('page_title' => 'Org Chart', 'bc' => $bc, 'page_name' => 'Organization Chart');

        $this->data['department_list']   = $this->organigram_model->get_employeeDepartment();

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function get_designation(){

        $form_data = $this->input->post();

        $department = $form_data['department'];

        $result = $this->organigram_model->get_designation($department);
        echo json_encode($result);

    }

    public function get_position_staff(){

        $form_data = $this->input->post();

        $department  = $form_data['department'];
        $designation = $form_data['designation'];

        $result = $this->organigram_model->get_position_staff($department,$designation);
        echo json_encode($result);

    }
}
