<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reimbursement extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library(array('session','parser'));
        $this->load->helper("file");
        $this->load->helper(array('form', 'url'));
        $this->load->model('reimbursement_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');

        if($this->user_group_name != 'admin'){
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'Reimbursement';
    }

    public function index()
    {   
        if($this->user_group_name != 'admin'){
            $this->data['reimbursement_list'] = $this->reimbursement_model->get_employee_reimbursement($this->employee_id);

            foreach($this->data['reimbursement_list'] as $item){
                $item->status_id = $this->employment_json_model->get_action_name($item->status_id);
            }
            $this->page_construct('index.php', $this->meta, $this->data);

        }else{
            $this->data['reimbursement_list'] = $this->reimbursement_model->get_all_employee_reimbursement();

            $this->page_construct('index_admin.php', $this->meta, $this->data);
        }
    }

    // public function index_admin()
    // {   
    //     $this->data['reimbursement_list'] = $this->reimbursement_model->get_all_employee_reimbursement();

    //     // // echo json_encode($this->data['reimbursement_list']);

    //     // foreach($this->data['reimbursement_list'] as $item){
    //     //     $item->status_id = $this->employment_json_model->get_action_name($item->status_id);
    //     // }

    //     $this->page_construct('index_admin.php', $this->meta, $this->data);
    // }

     // for admin side to approve or reject claim
    public function view_content($employee_id, $client_name, $firm_name){
        $this->data['reimbursement_list'] = $this->reimbursement_model->get_view_content($employee_id, $client_name, str_replace('%20', ' ', $firm_name));
        $this->data['history_list']       = $this->reimbursement_model->get_history($employee_id, $client_name, str_replace('%20', ' ', $firm_name));
        $this->data['employee_id'] = $employee_id;
        $this->data['client_name'] = $client_name;
        $this->data['firm_name']   = $firm_name;

        foreach($this->data['history_list'] as $row){
            $row->status_id = $this->employment_json_model->get_action_name($row->status_id);
        }

        $this->page_construct('view_content.php', $this->meta, $this->data);
    }

    public function history_admin(){
        $this->data['reimbursement_list'] = $this->reimbursement_model->get_all_employee_reimbursement_history();

        $this->page_construct('history_admin.php', $this->meta, $this->data);
    }

    public function history_content($employee_id, $client_name, $firm_name){
        $this->data['history_list']       = $this->reimbursement_model->get_history($employee_id, $client_name, str_replace('%20', ' ', $firm_name));
        $this->data['employee_id'] = $employee_id;
        $this->data['client_name'] = $client_name;
        $this->data['firm_name']   = $firm_name;

        foreach($this->data['history_list'] as $row){
            $row->status_id = $this->employment_json_model->get_action_name($row->status_id);
        }

        $this->page_construct('history_content.php', $this->meta, $this->data);
    }

    public function edit($reimbursement_id = NULL){
        $this->data['employee_id'] =  $this->employee_id;
        $this->data['reimbursement'] = $this->reimbursement_model->get_employee_reimbursement_details($reimbursement_id);

        // echo json_encode($this->data['reimbursement']);

        $this->page_construct('apply_reimbursement.php', $this->meta, $this->data);
    }

     public function apply_reimbursement()
    {  
        $this->data['employee_id'] =  $this->employee_id;
        $this->page_construct('apply_reimbursement.php', $this->meta, $this->data);
    }

    public function apply_reimbursement_tr_partial()
    {
        $form_data = $this->input->post();

        $this->data['count'] = $form_data['count'];

        $this->load->view('apply_reimbursement_tr_partial', $this->data);
    }

    public function submit_reimbursement(){
        $form_data = $this->input->post();
        // echo json_encode($form_data);

        $form_data['reimbursement_date']        = array_values($form_data['reimbursement_date']);
        $form_data['reimbursement_client_name'] = array_values($form_data['reimbursement_client_name']);
        $form_data['reimbursement_description'] = array_values($form_data['reimbursement_description']);
        $form_data['reimbursement_firm']        = array_values($form_data['reimbursement_firm']);
        $form_data['reimbursement_amount']      = array_values($form_data['reimbursement_amount']);
        $form_data['reimbursement_invoice_no']  = array_values($form_data['reimbursement_invoice_no']);

        $_FILES['reimbursement_receipt']['error']    = array_values($_FILES['reimbursement_receipt']['error']);
        $_FILES['reimbursement_receipt']['name']     = array_values($_FILES['reimbursement_receipt']['name']);
        $_FILES['reimbursement_receipt']['size']     = array_values($_FILES['reimbursement_receipt']['size']);
        $_FILES['reimbursement_receipt']['tmp_name'] = array_values($_FILES['reimbursement_receipt']['tmp_name']);
        $_FILES['reimbursement_receipt']['type']     = array_values($_FILES['reimbursement_receipt']['type']);

        // echo json_encode($_FILES['reimbursement_receipt']);

        // foreach($form_data as $row){
        //     if(is_array($row)){
        //         $item_Array = json_decode(json_encode($row), true); 

        //         array_push($temp_data, array_values($item_Array));
        //     }
        // }

        // echo json_encode($temp_data[0]);

        // $data = array();

        for($i = 0; $i < count($form_data['reimbursement_date']); $i++) {
            $receipt_img_filename = '';

            // echo $_FILES['reimbursement_receipt']['name'][$i];

            // Set preference
            $config['upload_path']   = 'uploads/reimbursement/'; 
            // $config['file_name']     = $_FILES['reimbursement_receipt']['name'];
            $config['upload_path']   = './uploads/reimbursement'; 
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; 
            // $config['max_size']      = 100; 
            // $config['max_width']     = 1024; 
            // $config['max_height']    = 768;  

            //Load upload library
            $this->load->library('upload',$config);
            $this->upload->initialize($config);

            if(!empty($_FILES['reimbursement_receipt']['name'][$i])){
                $_FILES['receipt_img'] = [
                    'name'     => $_FILES['reimbursement_receipt']['name'][$i],
                    'type'     => $_FILES['reimbursement_receipt']['type'][$i],
                    'tmp_name' => $_FILES['reimbursement_receipt']['tmp_name'][$i],
                    'error'    => $_FILES['reimbursement_receipt']['error'][$i],
                    'size'     => $_FILES['reimbursement_receipt']['size'][$i]
                ];
                    
                if (!$this->upload->do_upload('receipt_img')) {
                    // $error = array('error' => $this->upload->display_errors()); 
                    // $this->load->view('upload_form', $error); 
                    echo $this->upload->display_errors();
                }else{
                    $uploadData = $this->upload->data();
                    $receipt_img_filename = $uploadData['file_name'];

                    // echo $uploadData['full_path'];
                }
            }

            $temp = array(
                'employee_id' => $form_data['employee_id'],
                'date'        => date('Y-d-m', strtotime($form_data['reimbursement_date'][$i])),
                'client_name' => $form_data['reimbursement_client_name'][$i],
                'description' => $form_data['reimbursement_description'][$i],
                'firm_name'   => $form_data['reimbursement_firm'][$i],
                'amount'      => $form_data['reimbursement_amount'][$i],
                'receipt_img_filename' => $receipt_img_filename,
                'invoice_no'  => $form_data['reimbursement_invoice_no'][$i],
                'status_id'   => '1'
            );

            $result = $this->reimbursement_model->add_reimbursement($temp);

            echo $result;

            // $this->session->set_userdata(array('reimbursement_id' => $reimbursement_id));
        }
    }
    
    // public function uploadFile()
    // {
    //     $reimbursement_id = $this->session->userdata('reimbursement_id');
    //     echo $reimbursement_id;

    //     $_FILES['receipt']['name']     = $_FILES['receipt_img']['name'];
    //     $_FILES['receipt']['type']     = $_FILES['receipt_img']['type'];
    //     $_FILES['receipt']['tmp_name'] = $_FILES['receipt_img']['tmp_name'];
    //     $_FILES['receipt']['error']    = $_FILES['receipt_img']['error'];
    //     $_FILES['receipt']['size']     = $_FILES['receipt_img']['size'];

    //     $uploadPath                 = './uploads/reimbursement';
    //     $config['upload_path']      = $uploadPath;
    //     $config['allowed_types']    = 'gif|jpg|jpeg|png|ico|icon|image|image|ico';
        
    //     $this->load->library('upload', $config);
    //     $this->upload->initialize($config);

    //     //echo json_encode($_FILES['logo']);
    //     //echo json_encode($this->upload->do_upload('logo'));
    //     if($this->upload->do_upload('receipt'))
    //     {
    //         $fileData = $this->upload->data();
    //         //echo json_encode($fileData);
    //         // $firm_id = $this->session->userdata('submit_firm_id');
    //         $uploadData['receipt_img_filename'] = $fileData['file_name'];

    //         $files = $this->db->query("select * from reimbursement where id='".$reimbursement_id."'");
    //         $file_info = $files->result_array();

    //         if(!empty($file_info[0]["receipt_img_filename"]))
    //         {
    //             unlink("./uploads/reimbursement/".$file_info[0]["receipt_img_filename"]);
    //         }
    //         /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
    //         $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/

    //         echo "success";
    //     }

    //     //}
    //     //echo json_encode($uploadData);
    //     if(!empty($uploadData))
    //     {
    //         $this->db->update("reimbursement",$uploadData,array("id" => $reimbursement_id));
    //         //$this->db->insert_batch('officer_files',$uploadData);
            
    //     } 
    //     // else {
    //     //     $this->db->where('id', 6);
    //     //     $this->db->update("mc_claim",$uploadData,array("id" => $claim_id));
    //     // }
    //     //redirect("personprofile");
    //     /*$this->session->unset_userdata('officer_id');*/
    // //}
    // }

    // public function delete_receipt($reimbursement_id){
    //     // echo $claim_id;
    //     $this->session->set_userdata(array('reimbursement_id' => $reimbursement_id));

    //     echo true;
    // }

    public function change_status(){
        // echo "change";

        $form_data = $this->input->post();

        $data = array();

        if($form_data['is_approve']){
            array_push($data, array(
                'id' => $form_data['reimbursement_id'],
                'status_id' => 2,
                'status_updated_by' => date('Y-m-d H:i:s')
            ));
        }else {
            array_push($data, array(
                'id' => $form_data['reimbursement_id'],
                'status_id' => 3,
                'status_updated_by' => date('Y-m-d H:i:s')
            ));
        }

        $result = $this->reimbursement_model->update_status($data);

        echo $result;

    }
}