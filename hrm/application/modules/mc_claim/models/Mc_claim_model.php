<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Mc_claim_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('Employment_json_model');
    }

    public function get_all_employee_mc_list(){
        $q = $this->db->query('
                SELECT mc.id AS `mc_id`, mc.mc_no AS `mc_no`, mc.start_date AS `mc_start_date`, mc.end_date AS `mc_end_date`, mc.mc_status AS `mc_status`, e.name AS `employee_name`, mac.id AS `mac_id`, claim.id AS `claim_id`, 
                (SELECT COUNT(*) FROM mc_apply WHERE employee_id = mc.employee_id AND mc_status = 2) AS `total_mc_approved`
                FROM `mc_apply` mc 
                LEFT JOIN employee e ON e.id = mc.employee_id
                LEFT JOIN mc_apply_claim mac ON mac.mc_apply_id = mc.id
                LEFT JOIN mc_claim claim ON claim.id = mac.claim_id
                WHERE mc.mc_status = 1
            ');

        return $q->result();
    }

    public function get_all_employee_claim_list(){
        $q = $this->db->query('
                SELECT mc.id AS `mc_id`, mc.mc_no AS `mc_no`, mc.start_date AS `mc_start_date`, mc.end_date AS `mc_end_date`, mc.mc_status AS `mc_status`, mc.status_updated_by, e.name AS `employee_name`, mac.id AS `mac_id`, claim.id AS `claim_id`, claim.status AS `claim_status`, claim.invoice_no AS `claim_invoice_no`, claim.receipt_img AS `receipt_img`
                FROM `mc_apply` mc 
                LEFT JOIN employee e ON e.id = mc.employee_id
                LEFT JOIN mc_apply_claim mac ON mac.mc_apply_id = mc.id
                LEFT JOIN mc_claim claim ON claim.id = mac.claim_id
                WHERE mc.mc_status = 2 AND claim.status = 1
            ');

        return $q->result();
    }

    public function get_employee_mc_list($employee_id) {

        $q = $this->db->query('SELECT a.id AS `apply_id`, a.mc_no, a.start_date, a.end_date, a.mc_status, c.id AS `claim_id`, c.claim_no, c.invoice_no, c.receipt_img, c.status AS `claim_status` FROM mc_apply a LEFT JOIN mc_apply_claim ac ON a.id = ac.mc_apply_id LEFT JOIN mc_claim c ON c.id = ac.claim_id WHERE a.employee_id = "'. $employee_id .'"');

        $action_list = $this->Employment_json_model->get_action_result();   // Get list and name


        foreach($q->result() as $item){
            $item->mc_status = $action_list[$item->mc_status];
        }

        foreach($q->result() as $item){
            if(!is_null($item->claim_status)){
                $item->claim_status = $action_list[$item->claim_status];
            }
        }

        return $q->result();

    }

    public function submit_mc($data) {
        // $q = $this->db->get_where('mc_apply', $data);    // check if interview existed before.
        $q = $this->db->query("SELECT * FROM mc_apply WHERE id = '".$data['id']."'");

        // echo json_encode($q->num_rows());

        if(!$q->num_rows()){
            // $now = getDate();
            // $data['mc_no'] = 'mc_no_'.$now[0];

            $data['mc_no'] =  random_code(8);

            $this->db->insert('mc_apply', $data);    // insert new mc to database.
            $mc_id = $this->db->insert_id();

            $data['id'] = $mc_id;

            return $data;
        }
        else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('mc_apply', $data); 

            return $data;
        }
    }

    public function edit_mc($mc_apply_id) {
        $q = $this->db->query("SELECT * FROM mc_apply WHERE id = '" . $mc_apply_id . "'");

        return $q->result();
    }

    public function submit_claim($data, $mc_id){
        // return json_encode($data);
        $q = $this->db->query("SELECT * FROM mc_claim WHERE id = '" . $data['id'] . "'");

        if(!$q->num_rows()){
            $data['claim_no'] =  random_code(8);

            $this->db->insert('mc_claim', $data);    // insert new claim to database.
            $claim_id = $this->db->insert_id();

            $mc_apply_claim_data = array(
                'mc_apply_id' => $mc_id,
                'claim_id'    => $claim_id
            );

            $this->db->insert('mc_apply_claim', $mc_apply_claim_data);

            $data['id'] = $claim_id;

            return $data;
        }else{
            $this->db->where('id', $data['id']);
            $result = $this->db->update('mc_claim', $data); 

            return $data;
        }

    }

    public function get_claim_details($claim_id){
        $q = $this->db->query("SELECT mc.*, mac.mc_apply_id AS `mc_id` FROM mc_claim mc LEFT JOIN mc_apply_claim mac ON mac.claim_id =". $claim_id ." WHERE mc.id = '" . $claim_id . "'");

        return $q->result();
    }

    public function get_receipt_img_name($claim_id) {
        $q = $this->db->query("SELECT receipt_img FROM mc_claim WHERE id = '" . $claim_id . "'");

        return $q->result();
    }

    public function change_mc_status($mc_id, $data){
        $this->db->where('id', $mc_id);
        $result = $this->db->update('mc_apply', $data);

        return $result;
    }

    public function change_claim_status($claim_id, $data){
        $this->db->where('id', $claim_id);
        $result = $this->db->update('mc_claim', $data);

        return $result;
    }

    // public function get_interviewList(){
    //     $q = $this->db->query("SELECT applicant_interview.id AS `interview_id`, interview.interview_no, applicant.id AS `applicant_id`, applicant.name, interview.interview_time, interview.status, interview.result FROM interview LEFT JOIN applicant_interview ON applicant_interview.interview_id = interview.id LEFT JOIN applicant ON applicant_interview.applicant_id = applicant.id");

    //     return $q->result();
    // }

    // public function create_applicant($data){
    //     $q = $this->db->get_where('applicant', $data);    // check if customer existed before.

    //     if(!$q->num_rows()){
    //         $this->db->insert('applicant', $data);    // insert new customer to database
    //         $applicant_id = $this->db->insert_id();

    //         return $applicant_id;
    //     }
    //     else{
    //         return $q->result()[0]->id; // retrieve id
    //     }
    // }

    // public function create_interview($data){
    //     $q = $this->db->get_where('interview', $data);    // check if interview existed before.

    //     if(!$q->num_rows()){
    //         $this->db->insert('interview', $data);    // insert new interview to database.
    //         $interview_id = $this->db->insert_id();

    //         return $interview_id;
    //     }
    //     else{
    //         return $q->result()[0]->id; // retrieve id
    //     }
    // }

    // public function create_applicant_interview($data){
    //     $q = $this->db->get_where('applicant_interview', $data);    // check if interview existed before.

    //     if(!$q->num_rows()){
    //         $this->db->insert('applicant_interview', $data);    // insert new interview to database.
    //         $interview_id = $this->db->insert_id();

    //         return $interview_id;
    //     }
    //     else{
    //         return $q->result()[0]->id; // retrieve id
    //     }
    // }

    // public function sendInvitationEmail($data, $applicant_email){
    //     // send email with interview code
    //     $msg = file_get_contents('./themes/default/views/email_templates/interview_invitation.html');
    //     $message = $this->parser->parse_string($msg, $data, TRUE);

    //     $subject = "Interview Invitation";
    //     if($this->sma->send_email($applicant_email, $subject, $message)){
    //         return true;
    //     }
    // }
}
?>