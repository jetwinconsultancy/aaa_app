<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Reimbursement_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_employee_reimbursement($employee_id){
        $q = $this->db->query("SELECT * FROM reimbursement WHERE employee_id='". $employee_id . "'");

        return $q->result();
    }

    public function get_all_employee_reimbursement(){
        $q = $this->db->query("SELECT e.id AS `employee_id`, e.name AS `employee_name`, r.client_name, r.firm_name, MAX(r.date_applied) AS `max_date`, COUNT(*) AS `status_count`, SUM(r.amount) AS `total_amount` FROM reimbursement r JOIN employee e ON r.employee_id = e.id WHERE r.status_id = 1 GROUP BY r.status_id, r.employee_id , r.client_name, r.firm_name");

        return $q->result();
    }

    public function get_all_employee_reimbursement_history(){
        $q = $this->db->query("SELECT e.id AS `employee_id`, e.name AS `employee_name`, r.client_name, r.firm_name, MAX(r.date_applied) AS `max_date`, COUNT(*) AS `status_count`, SUM(r.amount) AS `total_amount` FROM reimbursement r JOIN employee e ON r.employee_id = e.id WHERE r.status_id NOT IN (1) GROUP BY r.status_id, r.employee_id , r.client_name, r.firm_name");

        return $q->result();
    }


    public function get_employee_reimbursement_details($reimbursement_id){
        $q = $this->db->query("SELECT * FROM reimbursement WHERE id='". $reimbursement_id . "'");

        return $q->result()[0];
    }

    // get selected content (admin side)
    public function get_view_content($employee_id, $client_name, $firm_name){ // for admin side page view_content
        $q = $this->db->query("SELECT r.*, e.id AS `employee_id`, e.name AS `employee_name` FROM `reimbursement` r JOIN employee e ON e.id = r.employee_id  WHERE r.status_id = 1 AND r.employee_id =". $employee_id ." AND r.client_name = '" . $client_name . "' AND r.firm_name = '" . $firm_name . "'");

        return $q->result();
    }

    public function get_history($employee_id, $client_name, $firm_name){ // for admin side page view_content
        $q = $this->db->query("SELECT r.*, e.id AS `employee_id`, e.name AS `employee_name` FROM `reimbursement` r JOIN employee e ON e.id = r.employee_id  WHERE r.status_id NOT IN (1) AND r.employee_id =". $employee_id ." AND r.client_name = '" . $client_name . "' AND r.firm_name = '" . $firm_name . "'");

        return $q->result();
    }

    public function add_reimbursement($data){
        $data['reimbursement_no'] = random_code(8);

        $result = $this->db->insert('reimbursement', $data); 
        // $id     = $this->db->insert_id();

        return $result;
    }

    public function update_status($data){
        // echo $data[0]['id'];
        $this->db->where('id', $data[0]['id']);
        $result = $this->db->update('reimbursement', $data[0]);

        return $result;
    }
}
?>