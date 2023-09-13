<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Block_holiday extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        if(!$this->data['Admin']){
            redirect('welcome');
        }

        $this->load->library(array('session','parser'));
        $this->load->helper("file");
        // $this->load->model('offer_letter_model');
        $this->load->model('block_holiday_model');
    }

    public function index()
    {   
        $this->meta['page_name'] = 'Block Holiday';
        $this->data['holiday_list'] = $this->block_holiday_model->get_holiday_list();

        $this->page_construct('add_holiday.php', $this->meta, $this->data);
    }

    public function submit_holiday(){
        $form_data = $this->input->post();

        $data = array(
            'holiday_date' => date('Y-m-d', strtotime($form_data['block_holiday'])),
            'description'  => $form_data['holiday_description']
        );

        $result = $this->block_holiday_model->submit_holiday($data);

        echo $result;
    }

    public function delete_holiday(){
        $form_data = $this->input->post();

        $result = $this->block_holiday_model->delete_holiday($form_data['holiday_id']);

        echo $result;
    }
    
}