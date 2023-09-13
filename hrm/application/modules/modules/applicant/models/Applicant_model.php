<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    // public function getNationality(){

    // 	$query = $this->db->query("SELECT * from nationality");

    // 	$nationality = array();

    // 	$nationality[''] = '-- Select Nationality --';

    // 	foreach($query->result_array() as $item) {
    // 		$nationality[$item['id']] = $item['nationality']; 
    // 	}

   	// 	return $nationality;
    // }

    public function get_applicant($applicant_id){
        $query = $this->db->query("SELECT * from applicant WHERE id ='".$applicant_id."'");

        return $query->result()[0];
    }

    public function get_applicant_appendix($applicant_id){
        $query = $this->db->query("SELECT * from payroll_applicant_appendix WHERE id ='".$applicant_id."'");

        $appendix = (object)array (
            'q1' => '',
            'q2' => '',
            'q3' => '',
            'q4' => '',
            'q5' => '',
            'q6' => '',
            'q7' => '',
            'q8' => '',
            'q9' => '',
            'q10' => '',
            'q11' => '',
            'q12' => ''
        );

        if($query->num_rows() > 0)
        {
            // print_r($query->result()[0]);
            return $query->result()[0];
        }
        else
        {
            // print_r($appendix);
            return $appendix;
        }

    }

    public function get_applicant_language($applicant_id){
        $query = $this->db->query("SELECT * from language WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }


    public function get_applicant_education($applicant_id){
        $query = $this->db->query("SELECT * from education WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_experience($applicant_id){
        $query = $this->db->query("SELECT * from experience WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_professional($applicant_id){
        $query = $this->db->query("SELECT * from professional WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_family($applicant_id){
        $query = $this->db->query("SELECT * from family WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function get_applicant_referral($applicant_id){
        $query = $this->db->query("SELECT * from referral WHERE applicant_id ='".$applicant_id."'");

        return $query->result();
    }

    public function check_interview_no($interview_no){
        $query = $this->db->query('SELECT * from interview WHERE interview_no ="'. $interview_no.'"');

        if($query->num_rows()){

            $interview_id   = $query->result_array()[0]['id'];
            $interview_time = $query->result_array()[0]['expired_at'];

            if($interview_time > date('Y-m-d H:i:s')){
                $query1 = $this->db->query('SELECT * from applicant_interview WHERE interview_id ="' . $interview_id . '"');

                $applicant_id = $query1->result_array()[0]['applicant_id'];

                return json_encode(array('applicant_id' => $applicant_id, 'error' => ''));
            } else {
                return json_encode(array('applicant_id' => null, 'error' => 'Interview number is expired. Please contact our HR team to retrieve a new interview no.'));
            }

        }
        else {
            return json_encode(array('applicant_id' => null, 'error' => 'Interview no not found. Please make sure you have enter a correct interview no.'));
        }

    }

    public function save_applicant($id, $data){

        $this->db->where('id', $id);
        $result = $this->db->update('applicant', $data); 

        if($result){
            return true;
        }else{
            return $this->db->_error_message();
        }
    }

    public function save_appendix($id, $data){

        $q = $this->db->query("SELECT * FROM payroll_applicant_appendix WHERE id = '".$id."'");

        if ($q->num_rows() > 0)
        {
            $this->db->where('id', $id);
            $result = $this->db->update('payroll_applicant_appendix', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_applicant_appendix', $data);
        }
    }

    // public function insert_bundle_education($data){

    //     $result = $this->db->insert_batch('education', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_education($data){
        if(empty($data['id'])){
            $result = $this->db->insert('education', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('education', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_experience($data){

    //     $result = $this->db->insert_batch('experience', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_experience($data){
        if(empty($data['id'])){
            $result = $this->db->insert('experience', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('experience', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_language($data){

    //     $result = $this->db->insert_batch('language', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_language($data){
        if(empty($data['id'])){
            $result = $this->db->insert('language', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('language', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_professional($data){

    //     $result = $this->db->insert_batch('professional', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_professional($data){
        if(empty($data['id'])){
            $result = $this->db->insert('professional', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('professional', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    public function insert_family($data){
        if(empty($data['id'])){
            $result = $this->db->insert('family', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('family', $data);
        }
        return $result;
        // return !empty($data['id']);
    }

    // public function insert_bundle_referral($data){

    //     $result = $this->db->insert_batch('referral', $data); 

    //     if($result){
    //         return true;
    //     }else{
    //         return $this->db->_error_message();
    //     }
    // }

    public function insert_referral($data){
        if(empty($data['id'])){
            $result = $this->db->insert('referral', $data);
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('referral', $data);
        }
        return $result;
    }

    public function delete_batch($table_name, $ids){    // delete education, experience and so on by multiple rows
        $this->db->where_in('id', $ids);
        $result = $this->db->delete($table_name);

        return $result;
    }

    public function application_form_update_notification($applicant_id){

        $q1 = $this->db->query(" SELECT interview.interviewer, applicant.name, interview.interview_no FROM applicant
                                    LEFT JOIN applicant_interview ON applicant_interview.applicant_id = applicant.id
                                    LEFT JOIN interview ON interview.id = applicant_interview.interview_id
                                    WHERE applicant.id = '".$applicant_id."' ");
        $q1_result = $q1->result();
        $interviewer_user_id = $q1_result[0]->interviewer;

        $q2 = $this->db->query(" SELECT users.* ,  concat(users.first_name, ' ', users.last_name) as full_name FROM users WHERE id = '".$interviewer_user_id."' ");
        $q2_result = $q2->result();
        $interviewer_email = $q2_result[0]->email;

        $data = array(
            'interviewer_name'  => $q2_result[0]->full_name,
            'applicant_name'    => $q1_result[0]->name,
            'interview_no'      => $q1_result[0]->interview_no,
        );

        // SENDINBLUE EMAIL
        $this->load->library('parser');
        $msg        = file_get_contents('./application/modules/applicant/email_templates/ApplicationFormUpdateNotification.html');
        $subject    = 'Application Form Updated';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode(array(array("email"=> $interviewer_email)));
        $cc         = json_encode(array(array("email"=> 'woellywilliam@aaa-global.com')));
        $message    = $this->parser->parse_string($msg, $data,true);
        if($this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null)){
            return true;
        }
    }
}
?>