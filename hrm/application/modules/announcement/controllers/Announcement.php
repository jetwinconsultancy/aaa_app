<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Announcement extends MX_Controller
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
        $this->load->model('announcement/announcement_model');
        
    }

    public function index()
    {   
        $this->meta['page_name'] = 'Announcement';
        $bc   = array(array('link' => '#', 'page' => 'Announcement'));
        $meta = array('page_title' => 'Announcement', 'bc' => $bc, 'page_name' => 'Announcement');

        $this->data['User'] = $this->user_id;
        $this->data['announcement_list'] = $this->announcement_model->get_announcement_list();
        $this->data['department_list']   = $this->announcement_model->get_employeeDepartment();

        $this->announcement_model->update_announcement_flag($this->user_id);

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function announcement_flag()
    {   
        $data = $this->input->post();

        $result = $this->announcement_model->check_announcement_flag($data['id']);

        echo $result;
    }

    public function new_announcement()
    {   
        $data = $this->input->post();

        $announcement_details = array(
            'date'          => date('Y-m-d'),
            'department'    => $data['department'],
            'title'         => $data['announce_title'],
            'announcement'  => $data['announce_info'],
        );

        $result = $this->announcement_model->new_announcement($announcement_details,$data['id'],$this->user_id);
        echo json_encode($result);
    }

    public function record_filter(){
        $form_data = $this->input->post();

        if($form_data['result'] == 'true')
        {
            $record = true;
        }
        else
        {
            $record = false;
        }
        
        $result = $this->announcement_model->record_filter($record);
        echo json_encode($result);
    }
}
