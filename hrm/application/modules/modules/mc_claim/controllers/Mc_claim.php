<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mc_claim extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->load->library(array('session','parser'));
        $this->load->helper("file");
        // $this->load->model('offer_letter_model');
        $this->load->model('mc_claim_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');

        if($this->user_group_name != 'admin'){
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'MC & Claim';
    }

    public function index()
    {   
        if($this->user_group_name != 'admin'){
            $this->data['mc_claim_list'] = $this->mc_claim_model->get_employee_mc_list($this->employee_id);

            $this->page_construct('index.php', $this->meta, $this->data);
        }else{ 
            /* Admin side */
            $this->data['mc_list'] = $this->mc_claim_model->get_all_employee_mc_list();

            foreach($this->data['mc_list'] as $row){
                $row->mc_status = $this->employment_json_model->get_action_name($row->mc_status);
            }

            $this->data['claim_list'] = $this->mc_claim_model->get_all_employee_claim_list();

            foreach($this->data['claim_list'] as $row){
                $row->mc_status    = $this->employment_json_model->get_action_name($row->mc_status);
                $row->claim_status = $this->employment_json_model->get_action_name($row->claim_status);
            }

            $this->page_construct('index_admin.php', $this->meta, $this->data);
        }
    }

    // public function index_admin()
    // {   
    //     $this->data['mc_list'] = $this->mc_claim_model->get_all_employee_mc_list();

    //     foreach($this->data['mc_list'] as $row){
    //         $row->mc_status = $this->employment_json_model->get_action_name($row->mc_status);
    //     }

    //     $this->data['claim_list'] = $this->mc_claim_model->get_all_employee_claim_list();

    //     foreach($this->data['claim_list'] as $row){
    //         $row->mc_status    = $this->employment_json_model->get_action_name($row->mc_status);
    //         $row->claim_status = $this->employment_json_model->get_action_name($row->claim_status);
    //     }

    //     // echo $this->data['mc_claim_list'];

    //     $this->page_construct('index_admin.php', $this->meta, $this->data);
    // }

    public function apply_mc()
    {   
        $this->data['employee_id'] = $this->employee_id;
        $this->page_construct('apply_mc.php', $this->meta, $this->data);
    }
    
    public function submit_mc()
    {   
        $this->form_validation->set_rules('mc_start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('mc_end_date', 'End Date', 'required');
        $this->form_validation->set_rules('mc_reason', 'Reason', 'required');

        if($this->form_validation->run() == true)
        {
            $form_data = $this->input->post();

            $data = array(
                'id'          => (int)$form_data['mc_id'],
                'mc_no'       => $form_data['mc_no'],
                'employee_id' => $form_data['mc_employee_id'],
                'start_date'  => date('Y-m-d H:i:s', strtotime($form_data['mc_start_date'])),
                'end_date'    => date('Y-m-d H:i:s', strtotime($form_data['mc_end_date'])),
                'reason'      => $form_data['mc_reason'],
                'mc_status'   => '1'    // 1 means pending (json/employment.json -> Action_status)
            );

            if($form_data['mc_status'] != '1' && $form_data['mc_status'] != ''){
                $data['mc_status'] = $form_data['mc_status'];
            }

            $return_data = $this->mc_claim_model->submit_mc($data);

            $error = array(
                'result'=> false
            );
            
            echo json_encode($error);
        }
        else
        {
            $error = array(
                'result'        => true,
                'mc_start_date' => strip_tags(form_error('mc_start_date')),
                'mc_end_date'   => strip_tags(form_error('mc_end_date')),
                'mc_reason'     => strip_tags(form_error('mc_reason'))
            );

            echo json_encode($error);
        }
    } 

    public function mc_edit($mc_no) {

        $this->data['mc_data'] = $this->mc_claim_model->edit_mc($mc_no);

        $this->page_construct('apply_mc.php', $this->meta, $this->data);
    }

    public function claim_apply($mc_id){
        // $this->data['mc_data'] = $this->mc_claim_model->edit_mc($mc_id);

        $this->data['mc_id'] = $mc_id;

        $this->page_construct('claim_apply.php', $this->meta, $this->data);
    } 

    public function submit_claim(){
        $this->form_validation->set_rules('claim_invoice_no', 'Invoice no.', 'required');
        $this->form_validation->set_rules('claim_amount', 'Amount', 'required');

        if($this->form_validation->run() == true)
        {
            $form_data = $this->input->post();

            $mc_id = $form_data['mc_id'];
            $delete_receipt_img_claim_id = $this->session->userdata('claim_id');

            if(!is_null($delete_receipt_img_claim_id)){
                $previous_receipt_img = $this->mc_claim_model->get_receipt_img_name($delete_receipt_img_claim_id);

                // unlink("./uploads/claim/".$previous_receipt_img[0]->receipt_img);

                $data = array(
                    'id'          => $form_data['claim_id'],
                    'claim_no'    => $form_data['claim_no'],  
                    'invoice_no'  => $form_data['claim_invoice_no'],
                    'amount'      => str_replace(',', '', $form_data['claim_amount']),
                    'status'      => '1'
                );
            }else {
                $data = array(
                    'id'          => $form_data['claim_id'],
                    'claim_no'    => $form_data['claim_no'],  
                    'invoice_no'  => $form_data['claim_invoice_no'],
                    'amount'      => str_replace(',', '', $form_data['claim_amount']),
                    'status'      => '1'
                );
            }

            if(is_null($form_data['claim_status'])){
                $data['status'] = $form_data['claim_status'];
            }

            $return_data = $this->mc_claim_model->submit_claim($data, $mc_id);

            $this->session->set_userdata(array('claim_id' => $return_data['id']));

            // echo json_encode($return_data);
            $error = array(
                'result'=> false
            );
            
            echo json_encode($error);
        }
        else
        {
            $error = array(
                'result'        => true,
                'claim_invoice_no' => strip_tags(form_error('claim_invoice_no')),
                'claim_amount'   => strip_tags(form_error('claim_amount'))
            );

            echo json_encode($error);
        }
    }

    public function edit_claim($claim_id){
        $this->data['claim_data'] = $this->mc_claim_model->get_claim_details($claim_id);

        $this->page_construct('claim_apply.php', $this->meta, $this->data);
    }

    public function change_mc_status(){
        $form_data = $this->input->post();
        $mc_id = $form_data['mc_id'];

        if($form_data['is_approve']){
            $data = array(
                'mc_status'         => 2,
                'status_updated_by' => date('Y-m-d H:i:s')
            );

            $result = $this->mc_claim_model->change_mc_status($mc_id, $data);
        }else{
            $data = array(
                'mc_status'         => 3,
                'status_updated_by' => date('Y-m-d H:i:s')
            );

            $result = $this->mc_claim_model->change_mc_status($mc_id, $data);
        }

        echo $result;
    }

    public function change_claim_status(){
        $form_data = $this->input->post();
        $claim_id = $form_data['claim_id'];

        if($form_data['is_approve']){
            $data = array(
                'status'            => 2,
                'status_updated_by' => date('Y-m-d H:i:s')
            );

            $result = $this->mc_claim_model->change_claim_status($claim_id, $data);
        }else{
            $data = array(
                'status'            => 3,
                'status_updated_by' => date('Y-m-d H:i:s')
            );

            $result = $this->mc_claim_model->change_claim_status($claim_id, $data);
        }

        echo $result;
    }

    // public function sendOL_NewEmployee(){
    //     $data = $this->input->post();
        
    //     $this->data['employee_data']    = $this->offer_letter_model->getApplicantData($data['id']);
    //     $this->data['salary']           = $this->data['employee_data'][0]->expected_salary;
    //     $this->data['employment_type']  = $this->employment_json_model->getEmployment_dropdown();

    //     $this->load->view('sendOfferLetter', $this->data);
    // }

    // public function sendOL_ExistingEmployee(){
    //     $data = $this->input->post();
    //     $this->data['employee_data']    = $this->offer_letter_model->getEmployeeData($data['id']);
    //     $this->data['salary']           = $this->data['employee_data'][0]->salary;
    //     $this->data['employment_type']  = $this->employment_json_model->getEmployment_dropdown();

    //     $this->load->view('sendOfferLetter', $this->data);
    // }

    // public function do_upload()
    // {
    //     $config['upload_path']          = './uploads/';
    //     $config['allowed_types']        = 'jpg|png';
    //     $config['max_size']             = 100;
    //     $config['max_width']            = 1024;
    //     $config['max_height']           = 768;

    //     echo $config['upload_path'];

    //     $this->load->library('upload', $config);
    //     $this->upload->initialize($config);


    //     if ( ! $this->upload->do_upload('userfile'))
    //     {
    //             $error = array('error' => $this->upload->display_errors());

    //             echo json_encode($error);
    //             // $this->load->view('upload_form', $error);
    //     }
    //     else
    //     {
    //             $data = array('upload_data' => $this->upload->data());

    //              echo json_encode($data);
    //             // $this->load->view('upload_success', $data);
    //     }
    // }

    public function uploadFile()
    { 
        $claim_id = $this->session->userdata('claim_id');

        $_FILES['receipt']['name']     = $_FILES['receipt_img']['name'];
        $_FILES['receipt']['type']     = $_FILES['receipt_img']['type'];
        $_FILES['receipt']['tmp_name'] = $_FILES['receipt_img']['tmp_name'];
        $_FILES['receipt']['error']    = $_FILES['receipt_img']['error'];
        $_FILES['receipt']['size']     = $_FILES['receipt_img']['size'];

        $uploadPath                 = './uploads/claim';
        $config['upload_path']      = $uploadPath;
        $config['allowed_types']    = 'gif|jpg|jpeg|png|ico|icon|image|image|ico';
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if($this->upload->do_upload('receipt'))
        {
            $fileData = $this->upload->data();
            $uploadData['receipt_img'] = $fileData['file_name'];

            $files = $this->db->query("select * from mc_claim where id='".$claim_id."'");
            $file_info = $files->result_array();

            unlink("./uploads/claim/".$file_info[0]["receipt_img"]);
        }

        if(!empty($uploadData))
        {
            $this->db->update("mc_claim",$uploadData,array("id" => $claim_id));

            // echo "success";
        }

        echo "{}";
    }

    public function delete_receipt($claim_id){
        // echo $claim_id;
        $this->session->set_userdata(array('claim_id2' => $claim_id));

        echo true;
    }
}