<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Applicant extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        // if (!$this->loggedIn) {
        //     $this->session->set_userdata('requested_page', $this->uri->uri_string());
        //     redirect('applicant/index');
        // }

        $this->load->library('form_validation');

        $this->load->model('Applicant_model');
        $this->load->model('Day_time_json_model');
        $this->load->model('Personal_json_model');
        $this->load->model('Country_json_model');
    }

    public function index()
    {
        $this->data['errorMsg'] = "";
        $this->data['page_title'] = "Interview";
        // $this->data['site_name'] = "PAYROLL";

        $this->load->view('index', $this->data);
    }

    // public function applicant_profile($applicant_id){

    //     $this->data['applicant_profile'] = $this->Applicant_model->get_applicant($applicant_id);
    //     // $this->data['education']         = $this->Applicant_model->get_applicant_education($applicant_id);
    //     // $this->data['experience']        = $this->Applicant_model->get_applicant_experience($applicant_id);
    //     // $this->data['professional']      = $this->Applicant_model->get_applicant_professional($applicant_id);
    //     // $this->data['referral']          = $this->Applicant_model->get_applicant_referral($applicant_id);
    //     // $this->data['language']          = $this->Applicant_model->get_applicant_language($applicant_id);

    //     // echo json_encode($this->data['education']);

    //     $this->load->view('applicant_profile/index', $this->data);
    //     // $this->load->view('applicant_profile', $this->data);
    // }

    public function submit_interview_no(){
        $form_data      = $this->input->post();
        $interview_no   = $form_data['interview_no'];

        $result = json_decode($this->Applicant_model->check_interview_no($interview_no));
        $applicant_id = $result->applicant_id;

        if(!is_null($applicant_id)){
            $this->session->set_flashdata('applicant_id', $applicant_id);

            redirect('applicant/form/'.$applicant_id, 'refresh');
        }else{
            // $this->form_validation->set_message('errorMsg', 'error');
            $this->data['errorMsg'] = $result->error;

            $this->load->view('index', $this->data);
        }

        // $this->load->view($this->theme . 'applicant/form', $this->data);
        // 
    }

    public function success_msg(){
        $this->load->view($this->theme . 'applicant/success_message', $this->data);
    }

    public function form($applicant_id = NULL)
    {   
        $this->data['page_title']       = "Application Form";
        // $this->meta['page_name'] = 'Application Form';
        $this->data['nationality_list'] = $this->Country_json_model->getNationality();
        $this->data['gender']           = $this->Personal_json_model->getGender_dropdown();
        // $this->data['applicant_id']     = $this->session->flashdata('applicant_id');
        $this->data['applicant_id']     = $applicant_id;

        // $this->session->keep_flashdata($applicant_id);

        $this->data['applicant']    = $this->Applicant_model->get_applicant($applicant_id);
        $this->data['education']    = $this->Applicant_model->get_applicant_education($applicant_id);
        $this->data['experience']   = $this->Applicant_model->get_applicant_experience($applicant_id);
        $this->data['professional'] = $this->Applicant_model->get_applicant_professional($applicant_id);
        $this->data['family']       = $this->Applicant_model->get_applicant_family($applicant_id);
        $this->data['referral']     = $this->Applicant_model->get_applicant_referral($applicant_id);
        $this->data['language']     = $this->Applicant_model->get_applicant_language($applicant_id);
        $this->data['appendix']     = $this->Applicant_model->get_applicant_appendix($applicant_id);

        $this->load->view('form', $this->data);
    }

    public function applicant_profile($applicant_id = NULL)
    {   
        $this->data['page_title']       = "Application Form";
        // $this->meta['page_name'] = 'Application Form';
        $this->data['nationality_list'] = $this->Country_json_model->getNationality();
        $this->data['gender']           = $this->Personal_json_model->getGender_dropdown();
        // $this->data['applicant_id']     = $this->session->flashdata('applicant_id');
        $this->data['applicant_id']     = $applicant_id;

        // $this->session->keep_flashdata($applicant_id);

        $this->data['applicant']    = $this->Applicant_model->get_applicant($applicant_id);
        $this->data['education']    = $this->Applicant_model->get_applicant_education($applicant_id);
        $this->data['experience']   = $this->Applicant_model->get_applicant_experience($applicant_id);
        $this->data['professional'] = $this->Applicant_model->get_applicant_professional($applicant_id);
        $this->data['family']       = $this->Applicant_model->get_applicant_family($applicant_id);
        $this->data['referral']     = $this->Applicant_model->get_applicant_referral($applicant_id);
        $this->data['language']     = $this->Applicant_model->get_applicant_language($applicant_id);
        $this->data['appendix']     = $this->Applicant_model->get_applicant_appendix($applicant_id);

        $this->load->view('applicant_profile', $this->data);
    }

    public function education_partial()
    {   
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);
            $this->data['content'] = $data['content'];
        }

        $this->data['count']            = $data['count'];
        $this->data['months']           = $this->Day_time_json_model->getMonth_dropdown();
        $this->data['qualification']    = $this->Personal_json_model->getQualification_dropdown();
        $this->data['grade']            = $this->Personal_json_model->getGrade_dropdown();
        $this->data['fieldOfStudy']     = $this->Personal_json_model->getFieldOfStudy_dropdown();
        $this->data['country']          = $this->Country_json_model->getCountry_dropdown();

        // echo $this->data['fieldOfStudy'];
        $this->load->view('education_partial', $this->data);
    }

    public function experience_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);
            $this->data['content'] = $data['content'];
        }

        $this->data['count']            = $data['count'];
        $this->data['months']           = $this->Day_time_json_model->getMonth_dropdown();
        $this->data['country']          = $this->Country_json_model->getCountry_dropdown();
        $this->data['currency']         = $this->Country_json_model->getCurrency_dropdown();
        $this->data['position_level']   = $this->Personal_json_model->getPosition_level_dropdown();
        
        // echo $this->data['position_level'][0];

        $this->load->view('experience_partial', $this->data);
    }

    public function add_language_tr_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('language_tr_partial', $this->data);
    }

    public function professional_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('professional_partial', $this->data);
    }

    public function family_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('family_partial', $this->data);
    }

    public function referral_partial(){
        $data = $this->input->post();

        if(!empty($data['content'])){
            // echo json_encode($data['content']);

            $this->data['content'] = $data['content'];
        }

        $this->data['count'] = $data['count'];
        $this->load->view('referral_partial', $this->data);
    }

    public function save_applicant(){
        $form_data  = $this->input->post();

        $applicant_id = $form_data['applicant_id'];

        if($form_data['applicant_DOB']==""){
            $form_data['applicant_DOB'] = Null;
        }
        else{
            $form_data['applicant_DOB'] = date('Y-m-d', strtotime($form_data['applicant_DOB']));
        }

        $applicant = array(
            'position'          => $form_data['applicant_position'],
            'name'              => $form_data['applicant_name'],
            'email'             => $form_data['applicant_email'],
            'phoneno'           => $form_data['applicant_phoneno'],
            'ic_passport_no'    => $form_data['applicant_ic_passport_no'],
            'nationality_id'    => $form_data['applicant_nationality'],
            'address'           => $form_data['applicant_address'],
            'dob'               => $form_data['applicant_DOB'],
            'gender'            => $form_data['applicant_gender'],
            'race'              => $form_data['applicant_race'],
            'marital_status'    => $form_data['applicant_marital_status'],
            'expected_salary'   => $form_data['applicant_expected_salary'],
            'last_drawn_salary' => $form_data['applicant_last_drawn_salary'],
            'about'             => $form_data['applicant_about'],
            'pic'               => $form_data['applicant_preview_pic']
        );

        $applicant_resultMsg = $this->Applicant_model->save_applicant($applicant_id, $applicant);

        $appendix = array(
            'id'  => $applicant_id,
            'q1'  => $form_data['q1'],
            'q2'  => $form_data['q2'],
            'q3'  => $form_data['q3'],
            'q4'  => $form_data['q4'],
            'q5'  => $form_data['q5'],
            'q6'  => $form_data['q6'],
            'q7'  => $form_data['q7'],
            'q8'  => $form_data['q8'],
            'q9'  => $form_data['q9'],
            'q10' => $form_data['q10'],
            'q11' => $form_data['q11'],
            'q12' => $form_data['q12']
        );

        $appendix_resultMsg = $this->Applicant_model->save_appendix($applicant_id, $appendix);

        // save bundle of education to database
        $education = array();
        $education_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        if(isset($form_data['edu_id']))
        {
            $form_data['edu_id']               = array_values($form_data['edu_id']);
            $form_data['edu_from']             = array_values($form_data['edu_from']);
            $form_data['edu_to']               = array_values($form_data['edu_to']);
            $form_data['edu_uni_name']         = array_values($form_data['edu_uni_name']);
            $form_data['edu_qualification']    = array_values($form_data['edu_qualification']);
            $form_data['edu_major']            = array_values($form_data['edu_major']);
            $form_data['edu_grade']            = array_values($form_data['edu_grade']);
            $form_data['edu_cgpa']             = array_values($form_data['edu_cgpa']);
            $form_data['edu_total_cgpa']       = array_values($form_data['edu_total_cgpa']);

            for($i = 0; $i < count($form_data['edu_uni_name']); $i++) {

                if($form_data['edu_from'][$i]==""){
                    $form_data['edu_from'][$i] = Null;
                }
                else{
                    $form_data['edu_from'][$i] = date('Y-m-d', strtotime($form_data['edu_from'][$i]));
                }

                if($form_data['edu_to'][$i]==""){
                    $form_data['edu_to'][$i] = Null;
                }
                else{
                    $form_data['edu_to'][$i] = date('Y-m-d', strtotime($form_data['edu_to'][$i]));
                }

                $temp = array(
                    'id'                => $form_data['edu_id'][$i],
                    'applicant_id'      => $applicant_id,
                    'edu_from'          => $form_data['edu_from'][$i],
                    'edu_to'            => $form_data['edu_to'][$i],
                    'uni_name'          => $form_data['edu_uni_name'][$i],
                    'qualification'     => $form_data['edu_qualification'][$i],
                    'major'             => $form_data['edu_major'][$i],
                    'grade'             => $form_data['edu_grade'][$i],
                    'score'             => $form_data['edu_cgpa'][$i],
                    'total_score'       => $form_data['edu_total_cgpa'][$i]
                );

                if(!$this->Applicant_model->insert_education($temp)){
                    $education_resultMsg = false;
                }
            }
        }

        // save bundle of experience to database
        $experience = array();  
        $experience_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.  

        if(isset($form_data['exp_id']))
        {
            $form_data['exp_id']             = array_values($form_data['exp_id']);
            $form_data['exp_from']           = array_values($form_data['exp_from']);
            $form_data['exp_to']             = array_values($form_data['exp_to']);
            $form_data['exp_company']        = array_values($form_data['exp_company']);
            $form_data['exp_position']       = array_values($form_data['exp_position']);
            $form_data['exp_duties']         = array_values($form_data['exp_duties']);
            $form_data['exp_leaving_reason'] = array_values($form_data['exp_leaving_reason']);

            for($i = 0; $i < count($form_data['exp_position']); $i++) {

                if($form_data['exp_from'][$i]==""){
                    $form_data['exp_from'][$i] = Null;
                }
                else{
                    $form_data['exp_from'][$i] = date('Y-m-d', strtotime($form_data['exp_from'][$i]));
                }

                if($form_data['exp_to'][$i]==""){
                    $form_data['exp_to'][$i] = Null;
                }
                else{
                    $form_data['exp_to'][$i] = date('Y-m-d', strtotime($form_data['exp_to'][$i]));
                }

                $temp = array(
                    'id'                 => $form_data['exp_id'][$i],
                    'applicant_id'       => $applicant_id,
                    'exp_from'           => $form_data['exp_from'][$i],
                    'exp_to'             => $form_data['exp_to'][$i],
                    'exp_company'        => $form_data['exp_company'][$i],
                    'exp_position'       => $form_data['exp_position'][$i],
                    'exp_duties'         => $form_data['exp_duties'][$i],
                    'exp_leaving_reason' => $form_data['exp_leaving_reason'][$i],
                );

                if(!$this->Applicant_model->insert_experience($temp)){
                    $experience_resultMsg = false;
                }
            }
        }

        // save bundle of language to database
        $language = array();
        $language_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        if(isset($form_data['lang_id']))
        {
            $form_data['lang_id']      = array_values($form_data['lang_id']);
            $form_data['lang_name']    = array_values($form_data['lang_name']);
            $form_data['lang_spoken']  = array_values($form_data['lang_spoken']);
            $form_data['lang_written'] = array_values($form_data['lang_written']);
            $form_data['lang_reading'] = array_values($form_data['lang_reading']);

            for($i = 0; $i < count($form_data['lang_name']); $i++) {

                $temp = array(
                    'id'            => $form_data['lang_id'][$i],
                    'applicant_id'  => $applicant_id,
                    'name'          => $form_data['lang_name'][$i],
                    'spoken'        => $form_data['lang_spoken'][$i],
                    'written'       => $form_data['lang_written'][$i],
                    'reading'       => $form_data['lang_reading'][$i]
                );

                if(!$this->Applicant_model->insert_language($temp)){
                    $language_resultMsg = false;
                }
            }
        }

        // save bundle of professional to database
        $professional = array();
        $professional_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        if(isset($form_data['pro_id']))
        {
            $form_data['pro_id']                = array_values($form_data['pro_id']);
            $form_data['pro_from']              = array_values($form_data['pro_from']);
            $form_data['pro_to']                = array_values($form_data['pro_to']);
            $form_data['qualifications_awards'] = array_values($form_data['qualifications_awards']);
            $form_data['institution']           = array_values($form_data['institution']);

            for($i = 0; $i < count($form_data['institution']); $i++) {

                if($form_data['pro_from'][$i]==""){
                    $form_data['pro_from'][$i] = Null;
                }
                else{
                    $form_data['pro_from'][$i] = date('Y-m-d', strtotime($form_data['pro_from'][$i]));
                }

                if($form_data['pro_to'][$i]==""){
                    $form_data['pro_to'][$i] = Null;
                }
                else{
                    $form_data['pro_to'][$i] = date('Y-m-d', strtotime($form_data['pro_to'][$i]));
                }

                $temp = array(
                    'id'                    => $form_data['pro_id'][$i],
                    'applicant_id'          => $applicant_id,
                    'pro_from'              => $form_data['pro_from'][$i],
                    'pro_to'                => $form_data['pro_to'][$i],
                    'qualifications_awards' => $form_data['qualifications_awards'][$i],
                    'institution'           => $form_data['institution'][$i],
                );

                if(!$this->Applicant_model->insert_professional($temp)){
                    $professional_resultMsg = false;
                }
            }
        }

        // save bundle of professional to database
        $family = array();
        $family_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        if(isset($form_data['family_id']))
        {
            $form_data['family_id']           = array_values($form_data['family_id']);
            $form_data['family_name']         = array_values($form_data['family_name']);
            $form_data['family_relationship'] = array_values($form_data['family_relationship']);
            $form_data['family_age']          = array_values($form_data['family_age']);
            $form_data['family_occupation']   = array_values($form_data['family_occupation']);

            for($i = 0; $i < count($form_data['family_name']); $i++) {

                $temp = array(
                    'id'                  => $form_data['family_id'][$i],
                    'applicant_id'        => $applicant_id,
                    'family_name'         => $form_data['family_name'][$i],
                    'family_relationship' => $form_data['family_relationship'][$i],
                    'family_age'          => $form_data['family_age'][$i],
                    'family_occupation'   => $form_data['family_occupation'][$i],
                );

                if(!$this->Applicant_model->insert_family($temp)){
                    $family_resultMsg = false;
                }
            }
        }

        // save bundle of referral to database
        $referral = array();
        $referral_resultMsg = true; // set as "true" so that the form can still be submited when there is no item added.

        if(isset($form_data['ref_id']))
        {
            $form_data['ref_id']          = array_values($form_data['ref_id']);
            $form_data['ref_name']        = array_values($form_data['ref_name']);
            $form_data['ref_phoneno']     = array_values($form_data['ref_phoneno']);
            $form_data['ref_email']       = array_values($form_data['ref_email']);
            $form_data['ref_address']     = array_values($form_data['ref_address']);
            $form_data['ref_profession']  = array_values($form_data['ref_profession']);
            $form_data['ref_years_known'] = array_values($form_data['ref_years_known']);

            for($i = 0; $i < count($form_data['ref_name']); $i++) {

                $temp = array(
                    'id'                => $form_data['ref_id'][$i],
                    'applicant_id'      => $applicant_id,
                    'name'              => $form_data['ref_name'][$i],
                    'phoneno'           => $form_data['ref_phoneno'][$i],
                    'email'             => $form_data['ref_email'][$i],
                    'address'           => $form_data['ref_address'][$i],
                    'profession'        => $form_data['ref_profession'][$i],
                    'no_of_years_known' => $form_data['ref_years_known'][$i],
                );

                if(!$this->Applicant_model->insert_referral($temp)){
                    $referral_resultMsg = false;
                }
            }
        }

        if($applicant_resultMsg && $education_resultMsg && $experience_resultMsg && $language_resultMsg && $professional_resultMsg && $referral_resultMsg)
        {
            $this->Applicant_model->application_form_update_notification($applicant_id);
            echo true;
        } 
        else 
        {
            echo $applicant_resultMsg;
        }
    }

    // public function uploadFile($applicant_id = NULL)
    public function uploadFile()
    {
        if(isset($_FILES['applicant_resume']))
        {
            $pdf_list = array();

            $filesCount = count((array)$_FILES['applicant_resume']['name']);

            $q = $this->db->query("select * from applicant where id='".$_POST['applicant_id']."'");
            $q = $q->result_array();

            if($q[0]['uploaded_resume'] != '')
            {
                $q_result = json_decode($q[0]['uploaded_resume']);
                $pdf_list = array_merge($pdf_list, $q_result); 
            }

            for($i = 0; $i < $filesCount; $i++)
            {
                $_FILES['uploaded_resume']['name']     = $_FILES['applicant_resume']['name'][$i];
                $_FILES['uploaded_resume']['type']     = $_FILES['applicant_resume']['type'][$i];
                $_FILES['uploaded_resume']['tmp_name'] = $_FILES['applicant_resume']['tmp_name'][$i];
                $_FILES['uploaded_resume']['error']    = $_FILES['applicant_resume']['error'][$i];
                $_FILES['uploaded_resume']['size']     = $_FILES['applicant_resume']['size'][$i];

                $uploadPath                 = './uploads/applicant_resume';
                $config['upload_path']      = $uploadPath;
                $config['allowed_types']    = 'pdf';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if($this->upload->do_upload('uploaded_resume'))
                {
                    $fileData = $this->upload->data();
                    $uploadData[$i] = $fileData['file_name'];
                    array_push($pdf_list , $fileData['file_name']);
                }
                else
                {
                    $error = $this->upload->display_errors();
                    echo json_encode($error);
                }
            }

            if(!empty($uploadData))
            {
                $this->db->update("applicant",array("uploaded_resume" => json_encode($pdf_list)),array("id" => $_POST['applicant_id']));
            }
        }

        if (count($this->session->userdata('filename')) != 0)
        {
            $file = $this->session->userdata('filename');

            $q = $this->db->query("select * from applicant where id='".$_POST['applicant_id']."'");
            $q = $q->result_array();

            $file_string = json_decode($q[0]['uploaded_resume']);
            $remove_file_string  = array();

            for($i = 0; $i < count($file); $i++)
            {
                array_push($remove_file_string, $file[$i]);

                if(file_exists("./uploads/applicant_resume/".$file[$i]))
                {
                    unlink("./uploads/applicant_resume/".$file[$i]);
                }
            }

            $new_file = array_diff($file_string, $remove_file_string);
            $new_file = json_encode(array_values($new_file));

            $this->db->where('id', $_POST['applicant_id']);
            $this->db->update('applicant', array('uploaded_resume' => $new_file));

            $this->session->unset_userdata('filename');
        }
    }

    public function delete_resume($filename){

        if($this->session->userdata('filename') != null)
        {
            $filename_list = $this->session->userdata('filename');
        }
        else
        {
            $filename_list = array();
        }
       
        array_push($filename_list, $filename);

        $this->session->set_userdata(array(
            'filename'  =>  $filename_list,
        ));

        // echo json_encode($filename_list);
        echo true;
    }

    public function uploadFile_education()
    {
        // echo "uploadFile";
        /*if(isset($insert_id))
        {*/
            //echo ($this->session->userdata('officer_id'));
           //$filesCount = count($_FILES['uploadimages']['name']);
            //echo json_encode(count($_FILES['uploadimages']['name']));
            //for($i = 0; $i < $filesCount; $i++)
            //{   
                $applicant_id = 6;

                $_FILES['receipt']['name']     = $_FILES['receipt_img']['name'];
                $_FILES['receipt']['type']     = $_FILES['receipt_img']['type'];
                $_FILES['receipt']['tmp_name'] = $_FILES['receipt_img']['tmp_name'];
                $_FILES['receipt']['error']    = $_FILES['receipt_img']['error'];
                $_FILES['receipt']['size']     = $_FILES['receipt_img']['size'];

                $uploadPath                 = './uploads/education';
                $config['upload_path']      = $uploadPath;
                $config['allowed_types']    = 'gif|jpg|jpeg|png|ico|icon|image|image|ico';
                
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                //echo json_encode($_FILES['logo']);
                //echo json_encode($this->upload->do_upload('logo'));
                if($this->upload->do_upload('receipt'))
                {
                    $fileData = $this->upload->data();
                    //echo json_encode($fileData);
                    // $firm_id = $this->session->userdata('submit_firm_id');
                    $uploadData['receipt_img'] = $fileData['file_name'];

                    $files = $this->db->query("select * from mc_claim where id='".$applicant_id."'");
                    $file_info = $files->result_array();

                    unlink("./uploads/education/".$file_info[0]["receipt_img"]);
                    /*$uploadData[$i]['created'] = date("Y-m-d H:i:s");
                    $uploadData[$i]['modified'] = date("Y-m-d H:i:s");*/

                    echo "success";
                }

            //}
            //echo json_encode($uploadData);
            if(!empty($uploadData))
            {
                $this->db->update("mc_claim",$uploadData,array("id" => $applicant_id));
                //$this->db->insert_batch('officer_files',$uploadData);
                
            } 
            // else {
            //     $this->db->where('id', 6);
            //     $this->db->update("mc_claim",$uploadData,array("id" => $claim_id));
            // }
            //redirect("personprofile");
            /*$this->session->unset_userdata('officer_id');*/
        //}
    }

    public function delete_data(){
        $form_data = $this->input->post();

        if(isset($form_data['edu'])){
            $this->Applicant_model->delete_batch('education', $form_data['edu']);
        }

        if(isset($form_data['exp'])){
            $this->Applicant_model->delete_batch('experience', $form_data['exp']);
        }

        if(isset($form_data['pro'])){
            $this->Applicant_model->delete_batch('professional', $form_data['pro']);
        }

        if(isset($form_data['ref'])){
            $this->Applicant_model->delete_batch('referral', $form_data['ref']);
        }

        if(isset($form_data['lang'])){
            $this->Applicant_model->delete_batch('language', $form_data['lang']);
        }

        if(isset($form_data['family'])){
            $this->Applicant_model->delete_batch('family', $form_data['family']);
        }

        // echo isset($form_data['edu']);
    }


    public function application_form(){

        $form_data = $this->input->post();
        $applicant_id = $form_data["applicant_id"];

        $query1 = $this->db->query('SELECT * FROM applicant WHERE applicant.id = "'.$applicant_id.'"');
        $applicant_details = $query1->result();

        if($applicant_details[0]->nationality_id != "")
        {
            $query2= $this->db->query('SELECT * FROM nationality WHERE nationality.id = "'.$applicant_details[0]->nationality_id.'"');
            $nationality = $query2->result();

            $national = $nationality[0]->nationality;
        }
        else
        {
            $national = '';
        }

        $query3 = $this->db->query('SELECT * FROM education WHERE education.applicant_id = "'.$applicant_id.'"');
        $education_details = $query3->result();

        $query4 = $this->db->query('SELECT * FROM professional WHERE professional.applicant_id = "'.$applicant_id.'"');
        $professional_details = $query4->result();

        $query5 = $this->db->query('SELECT * FROM experience WHERE experience.applicant_id = "'.$applicant_id.'"');
        $experience_details = $query5->result();

        $query6 = $this->db->query('SELECT * FROM family WHERE family.applicant_id = "'.$applicant_id.'"');
        $family_details = $query6->result();

        $query7 = $this->db->query('SELECT * FROM referral WHERE referral.applicant_id = "'.$applicant_id.'"');
        $referral_details = $query7->result();

        $query8 = $this->db->query('SELECT * FROM language WHERE language.applicant_id = "'.$applicant_id.'"');
        $language_details = $query8->result();

        $query9 = $this->db->query('SELECT * FROM payroll_applicant_appendix WHERE payroll_applicant_appendix.id = "'.$applicant_id.'"');

        if($query9->num_rows()>0)
        {
            $appendix_details = $query9->result();
        }
        else
        {
            $appendix_details[] = (object) array(
                'q1'    => '',
                'q2'    => '',
                'q3'    => '',
                'q4'    => '',
                'q5'    => '',
                'q6'    => '',
                'q7'    => '',
                'q8'    => '',
                'q9'    => '',
                'q10'   => '',
                'q11'   => '',
                'q12'   => ''
            );
        }

        if($applicant_details[0]->pic != "")
        {
            $img_base64_encoded = $applicant_details[0]->pic;
            // $imageContent = file_get_contents($img_base64_encoded);
            // $path = tempnam(sys_get_temp_dir(), 'prefix');
            // file_put_contents($path, $imageContent);
        }
        else
        {
            $img_base64_encoded = "";
            // $path = "";
        }

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Application Form";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        // $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetMargins(10, 10, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        // $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='',$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $personal_info = '<p style="text-align: center;"><span style="text-decoration: underline;"><strong>JOB APPLICATION FORM</strong></span></p>
        <p style="text-align: right;padding-top: 25px;">
        <img style="width:100px; height:100px" src="@' . preg_replace('#^data:image/[^;]+;base64,#', '', $img_base64_encoded) . '"/>
        </p>
        <p style="text-align: left;">POSITION APPLIED FOR : '.$applicant_details[0]->position.'</p>
        <p style="text-align: left;"><strong>PERSONAL INFORMATION</strong></p>
        <table style="border-collapse: collapse; width: 100%; height: 229px;" border="1">
        <tbody>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Full Name : </td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->name.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">NRIC / Passport No. :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->ic_passport_no.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Nationality :</td>
        <td style="width: 68.5139%; height: 17px;">'.$national.'</td>
        </tr>
        <tr style="height: 76px;">
        <td style="width: 31.4861%; height: 76px;">Residential Address :</td>
        <td style="width: 68.5139%; height: 76px;">'.$applicant_details[0]->address.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Contact :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->phoneno.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Email :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->email.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Date of Birth :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->dob.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Gender :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->gender.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Race :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->race.'</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 31.4861%; height: 17px;">Marital Status :</td>
        <td style="width: 68.5139%; height: 17px;">'.$applicant_details[0]->marital_status.'</td>
        </tr>
        </tbody>
        </table>';
        $content .= $personal_info;


        $academic_qua = '<p style="text-align: left;"><strong>ACADEMIC QUALIFICATION</strong></p>
        <table style="border-collapse: collapse; width: 100%; height: 17px;" border="1">
        <tbody>
        <tr style="height: 17px;">
        <td style="width: 25%; text-align: center; height: 17px;" colspan="2">Date</td>
        <td style="width: 25%; text-align: center; height: 17px;" rowspan="2">Schools / Institution Attended</td>
        <td style="width: 25%; text-align: center; height: 17px;" rowspan="2">Qualifications Obtained</td>
        <td style="width: 25%; text-align: center; height: 17px;" rowspan="2">Subjects / Grade</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 12.5%; text-align: center; height: 17px;">Form</td>
        <td style="width: 12.5%; text-align: center; height: 17px;">To</td>
        </tr>';
        $content .= $academic_qua;
        if(count($education_details) == 0)
        {
            $academic_qua = '<tr>
            <td style="width: 12.5%; text-align: center;">-</td>
            <td style="width: 12.5%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            </tr>';
            $content .= $academic_qua;
        }
        else
        {
            for($a=0;$a<count($education_details);$a++)
            {
                $academic_qua = '<tr nobr="true">
                <td style="width: 12.5%; text-align: center;">'.$education_details[$a]->edu_from.'</td>
                <td style="width: 12.5%; text-align: center;">'.$education_details[$a]->edu_to.'</td>
                <td style="width: 25%; text-align: center;">'.$education_details[$a]->uni_name.'</td>
                <td style="width: 25%; text-align: center;">'.$education_details[$a]->qualification.'</td>
                <td style="width: 25%; text-align: center;">'.$education_details[$a]->major.'</td>
                </tr>';
                $content .= $academic_qua;
            }
        }
        $academic_qua = '</tbody></table>';
        $content .= $academic_qua;

        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();

        $membership_professional = '<p style="text-align: left;"><strong>MEMBERSHIP OF SOCIAL OR PROFESSIONAL BODIES</strong></p>
        <table style="border-collapse: collapse; width: 100%; height: 17px;" border="1">
        <tbody>
        <tr style="height: 17px;">
        <td style="width: 33.3333%; height: 17px; text-align: center;" colspan="2">Date</td>
        <td style="width: 33.3333%; height: 17px; text-align: center;" rowspan="2">Qualifications / Awards&nbsp;Obtained</td>
        <td style="width: 33.3333%; height: 17px; text-align: center;" rowspan="2">Awarding Institution</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 16.66665%; text-align: center; height: 17px;">Form</td>
        <td style="width: 16.66665%; text-align: center; height: 17px;">To</td>
        </tr>';
        $content .= $membership_professional;
        if(count($professional_details)==0)
        {
            $membership_professional = '<tr>
            <td style="width: 16.66665%; text-align: center;">-</td>
            <td style="width: 16.66665%; text-align: center;">-</td>
            <td style="width: 33.3333%; text-align: center;">-</td>
            <td style="width: 33.3333%; text-align: center;">-</td>
            </tr>';
            $content .= $membership_professional;
        }
        else
        {
            for($a=0;$a<count($professional_details);$a++)
            {
                $membership_professional = '<tr nobr="true">
                <td style="width: 16.66665%; text-align: center;">'.$professional_details[$a]->pro_from.'</td>
                <td style="width: 16.66665%; text-align: center;">'.$professional_details[$a]->pro_to.'</td>
                <td style="width: 33.3333%; text-align: center;">'.$professional_details[$a]->qualifications_awards.'</td>
                <td style="width: 33.3333%; text-align: center;">'.$professional_details[$a]->institution.'</td>
                </tr>';
                $content .= $membership_professional;
            }
        }
        $membership_professional = '</tbody></table>';
        $content .= $membership_professional;


        $employment_his = '<p style="text-align: left;"><strong>EMPLOYMENT HISTORY</strong></p>
        <table style="border-collapse: collapse; width: 100%; height: 17px;" border="1">
        <tbody>
        <tr style="height: 17px;">
        <td style="width: 20%; text-align: center; height: 17px;" colspan="2">Date</td>
        <td style="width: 20%; text-align: center; height: 17px;" rowspan="2">Company Name</td>
        <td style="width: 20%; text-align: center; height: 17px;" rowspan="2">Position Held</td>
        <td style="width: 20%; text-align: center; height: 17px;" rowspan="2">Nature of Duties</td>
        <td style="width: 20%; text-align: center; height: 17px;" rowspan="2">Reason for Leaving</td>
        </tr>
        <tr style="height: 17px;">
        <td style="width: 10%; text-align: center; height: 17px;">Form</td>
        <td style="width: 10%; text-align: center; height: 17px;">To</td>
        </tr>';
        $content .= $employment_his;
        if(count($experience_details) == 0)
        {
            $employment_his = '<tr>
            <td style="width: 10%; text-align: center;">-</td>
            <td style="width: 10%; text-align: center;">-</td>
            <td style="width: 20%; text-align: center;">-</td>
            <td style="width: 20%; text-align: center;">-</td>
            <td style="width: 20%; text-align: center;">-</td>
            <td style="width: 20%; text-align: center;">-</td>
            </tr>';
            $content .= $employment_his;
        }
        else
        {
            for($a=0;$a<count($experience_details);$a++)
            {
                $employment_his = '<tr nobr="true">
                <td style="width: 10%; text-align: center;">'.$experience_details[$a]->exp_from.'</td>
                <td style="width: 10%; text-align: center;">'.$experience_details[$a]->exp_to.'</td>
                <td style="width: 20%; text-align: center;">'.$experience_details[$a]->exp_company.'</td>
                <td style="width: 20%; text-align: center;">'.$experience_details[$a]->exp_position.'</td>
                <td style="width: 20%; text-align: center;">'.$experience_details[$a]->exp_duties.'</td>
                <td style="width: 20%; text-align: center;">'.$experience_details[$a]->exp_leaving_reason.'</td>
                </tr>';
                $content .= $employment_his;
            }
        }
        $employment_his = '</tbody></table>';
        $content .= $employment_his;


        $family_member = '<p><strong>PARTICULAR OF IMMEDIATE FAMILY MEMBER</strong></p>
        <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
        <tr>
        <td style="width: 25%; text-align: center;">Name</td>
        <td style="width: 25%; text-align: center;">Relationship</td>
        <td style="width: 25%; text-align: center;">Age</td>
        <td style="width: 25%; text-align: center;">Occupation</td>
        </tr>';
        $content .= $family_member;
        if(count($family_details) == 0)
        {
            $family_member = '<tr>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            </tr>';
            $content .= $family_member;
        }
        else
        {
            for($a=0;$a<count($family_details);$a++)
            {
                $family_member = '<tr nobr="true">
                <td style="width: 25%; text-align: center;">'.$family_details[$a]->family_name.'</td>
                <td style="width: 25%; text-align: center;">'.$family_details[$a]->family_relationship.'</td>
                <td style="width: 25%; text-align: center;">'.$family_details[$a]->family_age.'</td>
                <td style="width: 25%; text-align: center;">'.$family_details[$a]->family_occupation.'</td>
                </tr>';
                $content .= $family_member;
            }
        }
        $family_member = '</tbody></table>';
        $content .= $family_member;

        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();

        $characters_referee = '<p><strong>CHARACTERS REFEREE</strong></p>
        <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
        <tr>
        <td style="width: 16.6667%; text-align: center;">Name</td>
        <td style="width: 16.6667%; text-align: center;">Address</td>
        <td style="width: 16.6667%; text-align: center;">Contact</td>
        <td style="width: 16.6667%; text-align: center;">Email</td>
        <td style="width: 16.6667%; text-align: center;">Profession</td>
        <td style="width: 16.6667%; text-align: center;">No of years known</td>
        </tr>';
        $content .= $characters_referee;
        if(count($referral_details) == 0)
        {
            $characters_referee = '<tr>
            <td style="width: 16.6667%; text-align: center;">-</td>
            <td style="width: 16.6667%; text-align: center;">-</td>
            <td style="width: 16.6667%; text-align: center;">-</td>
            <td style="width: 16.6667%; text-align: center;">-</td>
            <td style="width: 16.6667%; text-align: center;">-</td>
            <td style="width: 16.6667%; text-align: center;">-</td>
            </tr>';
            $content .= $characters_referee;
        }
        else
        {
            for($a=0;$a<count($referral_details);$a++)
            {
                $characters_referee = '<tr nobr="true">
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->name.'</td>
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->address.'</td>
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->phoneno.'</td>
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->email.'</td>
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->profession.'</td>
                <td style="width: 16.6667%; text-align: center;">'.$referral_details[$a]->no_of_years_known.'</td>
                </tr>';
                $content .= $characters_referee;
            }
        }
        $characters_referee = '</tbody></table>';
        $content .= $characters_referee;


        $language_pro = '<p><strong>LANGUAGE PROFICIENCY</strong></p>
        <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
        <tr>
        <td style="width: 25%; text-align: center;">Language</td>
        <td style="width: 25%; text-align: center;">Spoken</td>
        <td style="width: 25%; text-align: center;">Written</td>
        <td style="width: 25%; text-align: center;">Read</td>
        </tr>';
        $content .= $language_pro;
        if(count($language_details) == 0)
        {
            $language_pro = '<tr>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            <td style="width: 25%; text-align: center;">-</td>
            </tr>';
            $content .= $language_pro;
        }
        else
        {
            for($a=0;$a<count($language_details);$a++)
            {
                $language_pro = '<tr nobr="true">
                <td style="width: 25%; text-align: center;">'.$language_details[$a]->name.'</td>
                <td style="width: 25%; text-align: center;">'.$language_details[$a]->spoken.'</td>
                <td style="width: 25%; text-align: center;">'.$language_details[$a]->written.'</td>
                <td style="width: 25%; text-align: center;">'.$language_details[$a]->reading.'</td>
                </tr>';
                $content .= $language_pro;
            }
        }
        $language_pro = '</tbody></table>';
        $content .= $language_pro;


        $remuneration = '<p><strong>REMUNERATION</strong></p>
        <table style="border-collapse: collapse; width: 100%; height: 34px;" border="1">
        <tbody>
        <tr style="height: 17px;" nobr="true">
        <td style="width: 24.937%; height: 17px;">Current salary</td>
        <td style="width: 75.063%; height: 17px;">'.$applicant_details[0]->last_drawn_salary.'</td>
        </tr>
        <tr style="height: 17px;" nobr="true">
        <td style="width: 24.937%; height: 17px;">Expected salary</td>
        <td style="width: 75.063%; height: 17px;">'.$applicant_details[0]->expected_salary.'</td>
        </tr>
        </tbody>
        </table>';
        $content .= $remuneration;


        $others = '<p><strong>OTHERS</strong></p>
        <table style="border-collapse: collapse; width: 100.126%; height: 78px;" border="1">
        <tbody>
        <tr style="height: 78px;">
        <td style="width: 100%; height: 78px;">'.$applicant_details[0]->about.'</td>
        </tr>
        </tbody>
        </table>';
        $content .= $others;

        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content2 = '';
        $obj_pdf->AddPage();

        $appendix_p1 = '<p style="text-align: right;"><strong>APPENDIX A</strong></p>
        <p style="text-align: left;">1. Tell us a little bit about yourself?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q1.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">2. Why do you want this job?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q2.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">3. What are your greatest professional strengths which is NOT the standard answer found on any job portal, such as you are fast learner, hard worker, etc?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q3.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">4. What do you consider to be your weaknesses?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q4.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">5. What is your greatest professional or academic achievement?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q5.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">6. Tell us about a challenge or conflict you faced, and how you dealt with it?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q6.'</td>
        </tr>
        </tbody>
        </table>';
        $content2 .= $appendix_p1;

        $obj_pdf->writeHTML($content2, true, false, false, false, '');


        $content3 = '';
        $obj_pdf->AddPage();

        $appendix_p2 = '<p style="text-align: left;">7. Where do you see yourself in five years?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q7.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">8. Why are you leaving your current job? (if applicable)</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q8.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">9. What are you looking for in a new position?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q9.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">10. How would your boss and co-workers describe you? (if applicable)</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q10.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">11. How do you deal with pressure or stressful situations?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q11.'</td>
        </tr>
        </tbody>
        </table>

        <p style="text-align: left;">12. How do you get to know us?</p>
        <table style="border-collapse: collapse; width: 100%; height: 63px;" border="1">
        <tbody>
        <tr style="height: 63px;">
        <td style="width: 100%; height: 63px;">'.$appendix_details[0]->q12.'</td>
        </tr>
        </tbody>
        </table>';
        $content3 .= $appendix_p2;

        $obj_pdf->writeHTML($content3, true, false, false, false, '');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Application Form - ('.$applicant_details[0]->name.').pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Application Form - ('.$applicant_details[0]->name.').pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Application Form - ('.$applicant_details[0]->name.').pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Application Form - (".$applicant_details[0]->name.").pdf"));
    }
}

class MYPDF extends TCPDF {

    protected $last_page_flag = false;
    protected $total_page = 1;
    protected $one_page_only = false;

    public function Close() {
        $this->last_page_flag = true;

        if($this->total_page == 1){
            $this->one_page_only = true;
        }

        parent::Close();
    }

    public function Header() {
        $headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 23);
        // $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
        $this->writeHTML($headerData['string']);
    }

    public function Footer() {
        $this->SetY(-18);
        $this->Ln();
        
        // // Page number
        // if (empty($this->pagegroups)) {
        //     $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
        // } else {
        //     $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias();
        // }

        // if(!$this->one_page_only){
        //     $this->SetY(-18);
        //     $this->SetFont('helvetica', '', 8);
        //     $this->Cell(0, 10, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // }
        
        // if(!$this->last_page_flag){
        //    $this->SetY(-18);
        // }

        // $this->total_page++;

        // // FOOTER IMG
        // $logoX = 130;
        // // $logoFileName = '../secretary/uploads/logo/ISCA_CA.png';
        // // $logoFileName = base_url('../secretary/uploads/logo/ISCA_CA.png');
        // $logoFileName = base_url().'uploads/logo/ISCA_CA.PNG';
        // $logoWidth = 70;
        // $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
    }
}
