<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Welcome_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }


    // WORKPASS EXPIRE DATE CHECK
    public function workpass_expire_date_check($user_id){
        $query = $this->db->query("SELECT payroll_employee.*,payroll_user_employee.* FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id AND payroll_user_employee.user_id = '".$user_id."'");

        return $query->result();
    }

    public function get_leave_pending_list(){

        $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status=1");

        return $query->result();
    }

    public function get_leave_pending_list2($id){

        // $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
        // LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
        // LEFT JOIN `firm` f ON e.firm_id = f.id 
        // LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
        // LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
        // (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
        // LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
        // LEFT JOIN users ON ue.user_id = users.id
        // WHERE l.status=1 AND users.manager_in_charge = '".$id."'");

        if($id == 79)
        {
            $id = '('.$id.',91)';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE l.status=1 AND users.manager_in_charge IN ".$id."");
        }
        else if($id == 62)
        {
            $id = '('.$id.')';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE l.status=1 AND (users.manager_in_charge IN ".$id." OR users.id = '63')");
        }
        else
        {
            $id = '('.$id.')';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE l.status=1 AND users.manager_in_charge IN ".$id."");
        }

        return $query->result();
    }

    public function get_on_leave_list(){
        
        $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status=2 
            AND week(l.start_date) = week(CURDATE())
            AND year(l.start_date) = year(CURDATE())");

        return $query->result();
    }

    public function get_on_leave_list2($id){

        if($id == 79)
        {
            $id = '('.$id.',91)';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON users.id = ue.user_id
            WHERE l.status=2 AND week(l.start_date) = week(CURDATE()) AND year(l.start_date) = year(CURDATE()) AND users.manager_in_charge IN ".$id."");
        }
        else if($id == 62)
        {
            $id = '('.$id.')';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON users.id = ue.user_id
            WHERE l.status=2 AND week(l.start_date) = week(CURDATE()) AND year(l.start_date) = year(CURDATE()) AND (users.manager_in_charge IN ".$id." OR users.id = '63')");
        }
        else
        {
            $id = '('.$id.')';

            $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON users.id = ue.user_id
            WHERE l.status=2 AND week(l.start_date) = week(CURDATE()) AND year(l.start_date) = year(CURDATE()) AND users.manager_in_charge IN ".$id."");
        }

        return $query->result();
    }

    public function get_pass_expiry_list(){
        
        $query = $this->db->query("SELECT payroll_employee.name AS name , payroll_employee.workpass AS pass , payroll_employee.pass_expire AS expiry_date, datediff(payroll_employee.pass_expire,CURDATE()) AS remaining_days FROM payroll_employee WHERE DATE_ADD(CURDATE(), INTERVAL 6 month) >= payroll_employee.pass_expire AND (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))");

        return $query->result();
    }

    public function get_pass_expiry_list2($id){
        
        $query = $this->db->query("SELECT payroll_employee.name AS name , payroll_employee.workpass AS pass , payroll_employee.pass_expire AS expiry_date, datediff(payroll_employee.pass_expire,CURDATE()) AS remaining_days FROM payroll_employee LEFT JOIN payroll_user_employee ON payroll_employee.id = payroll_user_employee.employee_id LEFT JOIN users ON payroll_user_employee.user_id = users.id WHERE DATE_ADD(CURDATE(), INTERVAL 6 month) >= payroll_employee.pass_expire AND users.manager_in_charge = '".$id."' AND (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))");

        return $query->result();
    }

    public function jobs_due_list(){
        
        $query = $this->db->query("SELECT * FROM payroll_assignment WHERE payroll_assignment.expected_completion_date IS NOT null AND payroll_assignment.status NOT IN (10) AND payroll_assignment.deleted = 0 ORDER BY payroll_assignment.expected_completion_date ASC");

        return $query->result();
    }

    public function jobs_due_list2($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        
        $query = $this->db->query("SELECT * FROM payroll_assignment WHERE payroll_assignment.expected_completion_date IS NOT null AND payroll_assignment.status NOT IN (10) AND payroll_assignment.PIC LIKE '%".$userName."%' AND payroll_assignment.deleted = 0 ORDER BY payroll_assignment.expected_completion_date ASC");

        return $query->result();
    }

    public function J_member_on_leave_list(){
        
        $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status=2 AND ue.user_id IN (89,92,93) AND l.end_date >= CURDATE() ORDER BY l.start_date ASC");

        return $query->result();
    }

    public function F_member_on_leave_list(){
        
        // $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
        //     LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
        //     LEFT JOIN `firm` f ON e.firm_id = f.id 
        //     LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
        //     LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
        //     (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
        //     LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
        //     WHERE l.status=2 AND ue.user_id IN (88,99,100,101) AND l.end_date >= CURDATE() ORDER BY l.start_date ASC");

        $query = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status=2 AND e.department = 1 AND l.end_date >= CURDATE() ORDER BY l.start_date ASC");

        return $query->result();
    }

    public function check_acknowledgement()
    {
        $q = $this->db->get_where("acknowledgement", array('user_id' => $this->session->userdata('user_id')));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        else
        {
            $user_login_queue = $this->db->query("SELECT * FROM user_logins WHERE DATE(user_logins.time) >= '2020-04-01' AND user_id = '".$this->session->userdata('user_id')."' ");

            if($user_login_queue->num_rows() > 1)
            {
                return "warning";
            }
            else
            {
                return "normal";
            }

            
        }
    }

    public function ECD_list(){
        
        $query = $this->db->query("SELECT payroll_assignment_ecd.*, payroll_assignment.client_name FROM payroll_assignment_ecd LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_ecd.assignment_id WHERE month(payroll_assignment_ecd.date) = month(CURRENT_TIMESTAMP)");

        return $query->result();
    }

    public function ECD_list2($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        
        $query = $this->db->query("SELECT payroll_assignment_ecd.*, payroll_assignment.client_name FROM payroll_assignment_ecd LEFT JOIN payroll_assignment ON payroll_assignment.assignment_id = payroll_assignment_ecd.assignment_id WHERE month(payroll_assignment_ecd.date) = month(CURRENT_TIMESTAMP) AND payroll_assignment.PIC LIKE '%".$userName."%'");

        return $query->result();
    }
}
?>