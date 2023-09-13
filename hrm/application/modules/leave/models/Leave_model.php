<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Leave_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        $this->load->model('Employment_json_model');
    }

    public function get_calander_block_leave_list($employee_id){

        $query = $this->db->query("SELECT * FROM payroll_employee WHERE id = '".$employee_id."'");

        foreach($query->result()as $item){
            $department_id = $item->department;
            $office_id     = $item->office; 
        }

        $list = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id = '".$office_id."' AND department_id = '".$department_id."' AND deleted = 0 AND year(block_leave_date_from)='". date("Y") ."' ORDER BY block_leave_date_from");

        return $list->result();
    }//jw

    public function get_department($employee_id){

        $query = $this->db->query("SELECT * FROM payroll_employee WHERE id = '".$employee_id."'");
        
        return $query->result();
    }

    public function get_leave_details($leave_id){
        $q = $this->db->query("SELECT payroll_leave.*, payroll_user_employee.user_id, payroll_employee.name, payroll_employee_others_leave.child_dob, payroll_employee_others_leave.child_is, payroll_employee_others_leave.expired_flag FROM `payroll_leave` LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_leave.employee_id LEFT JOIN payroll_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN payroll_employee_others_leave ON payroll_employee_others_leave.leave_no = payroll_leave.leave_no WHERE payroll_leave.id = '". $leave_id ."'");

        return $q->result();
    }

    public function get_employee_leaveList($employee_id){
        $q = $this->db->query("SELECT l.*, l.status as status_id, payroll_type_of_leave.leave_name FROM `payroll_leave` l LEFT JOIN payroll_type_of_leave ON l.type_of_leave_id = payroll_type_of_leave.id WHERE l.employee_id='". $employee_id ."' ORDER BY l.status ASC");

        if ($q->num_rows() > 0) {
            $action_list = $this->Employment_json_model->get_action_result();   // Get list and name

            foreach($q->result() as $item){
                $item->status = $action_list[$item->status];
            }

            return $q->result();
        }else {
            return [];
        }
    }

    public function get_active_type_of_leave_list($employee_id){
        $list = $this->db->query("SELECT * FROM payroll_employee_type_of_leave LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id WHERE employee_id='".$employee_id."'");

        $type_of_leave_list = array();
        $type_of_leave_list[''] = 'Select a Type of Leave';

        foreach($list->result()as $item){
            $type_of_leave_list[$item->id] = $item->leave_name; 
        }

        return $type_of_leave_list;
    }

    public function get_leaveList(){    // for admin side
        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated = 
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE (l.status=1 OR (l.status=2 AND l.end_date >= CURRENT_DATE))
            ORDER BY l.status");

        // $action_list = $this->Employment_json_model->get_action_result();   // Get list and name

        // foreach($q->result() as $item){
        //     $item->status = $action_list[$item->status];
        // }

        return $q->result();
    }

    public function get_leaveList2($id){    // for manager side
        // $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
        //     LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
        //     LEFT JOIN `firm` f ON e.firm_id = f.id 
        //     LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
        //     LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated =
        //     (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
        //     LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
        //     LEFT JOIN users ON ue.user_id = users.id
        //     WHERE (l.status=1 OR (l.status=2 AND l.end_date >= CURRENT_DATE)) AND users.manager_in_charge = '".$id."'
        //     ORDER BY l.status");

        if($id == 79)
        {
            $id = '('.$id.',91)';

            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated =
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE (l.status=1 OR (l.status=2 AND l.end_date >= CURRENT_DATE)) AND users.manager_in_charge IN ".$id."
            ORDER BY l.status");
        }
        else if($id == 62)
        {
            $id = '('.$id.')';

            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated =
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE (l.status=1 OR (l.status=2 AND l.end_date >= CURRENT_DATE)) AND (users.manager_in_charge IN ".$id." OR users.id = '63')
            ORDER BY l.status");
        }
        else
        {
            $id = '('.$id.')';

            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, eal.annual_leave_days AS `remaining_al`, ptl.leave_name, ue.user_id FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id 
            LEFT JOIN `payroll_user_employee` ue ON l.employee_id = ue.employee_id 
            LEFT JOIN payroll_employee_annual_leave eal ON eal.employee_id = e.id AND eal.type_of_leave_id = l.type_of_leave_id AND eal.last_updated =
            (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id = e.id AND eal_2.type_of_leave_id = l.type_of_leave_id)
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN users ON ue.user_id = users.id
            WHERE (l.status=1 OR (l.status=2 AND l.end_date >= CURRENT_DATE)) AND users.manager_in_charge IN ".$id."
            ORDER BY l.status");
        }

        return $q->result();
    }

    public function get_history_leaveList(){    // for admin side
        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status NOT IN(1) ORDER BY e.name ASC");

        return $q->result();
    }

    public function get_history_leaveList2($id){    // for manager side

        if($id == '79')
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status NOT IN(1) ORDER BY e.name ASC");

            return $q->result();
        }
        else if($id == '62') // 62=YEETING
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN payroll_user_employee ON e.id = payroll_user_employee.employee_id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            WHERE l.status NOT IN(1) AND (users.manager_in_charge = '".$id."' OR users.id = '63')
            ORDER BY e.name ASC");

            return $q->result();
        }
        else
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN payroll_user_employee ON e.id = payroll_user_employee.employee_id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            WHERE l.status NOT IN(1) AND users.manager_in_charge = '".$id."' ORDER BY e.name ASC");

            return $q->result();
        }
    }

    public function get_latest_leave_list(){    // for admin side
        $q = $this->db->query("SELECT payroll_employee_annual_leave.employee_id,payroll_employee.name,firm.name AS firm_name,AL.annual_leave_days AS AL, SL.annual_leave_days AS SL, HL.annual_leave_days AS HL FROM payroll_employee_annual_leave

            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=1 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) AL ON AL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=2 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) SL ON SL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=3 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) HL ON HL.employee_id = payroll_employee_annual_leave.employee_id

            LEFT JOIN payroll_employee ON payroll_employee_annual_leave.employee_id = payroll_employee.id 
            LEFT JOIN firm ON firm.id = payroll_employee.firm_id

            WHERE payroll_employee.employee_status_id IN (1,2) 
            OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE))

            GROUP BY payroll_employee_annual_leave.employee_id");


        return $q->result();
    }

    public function get_latest_leave_list2($id){    // for admin side

        if($id == '79')
        {
            $q = $this->db->query("SELECT payroll_employee_annual_leave.employee_id,payroll_employee.name,firm.name AS firm_name,AL.annual_leave_days AS AL, SL.annual_leave_days AS SL, HL.annual_leave_days AS HL FROM payroll_employee_annual_leave

            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=1 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) AL ON AL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=2 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) SL ON SL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=3 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) HL ON HL.employee_id = payroll_employee_annual_leave.employee_id

            LEFT JOIN payroll_employee ON payroll_employee_annual_leave.employee_id = payroll_employee.id 
            LEFT JOIN firm ON firm.id = payroll_employee.firm_id

            WHERE payroll_employee.employee_status_id IN (1,2) 
            OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE))

            GROUP BY payroll_employee_annual_leave.employee_id");


            return $q->result();
        }
        else if($id == '62') // 62=YEETING
        {
            $q = $this->db->query("SELECT payroll_employee_annual_leave.employee_id,payroll_employee.name,firm.name AS firm_name,AL.annual_leave_days AS AL, SL.annual_leave_days AS SL, HL.annual_leave_days AS HL FROM payroll_employee_annual_leave

            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=1 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) AL ON AL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=2 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) SL ON SL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=3 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) HL ON HL.employee_id = payroll_employee_annual_leave.employee_id

            LEFT JOIN payroll_employee ON payroll_employee_annual_leave.employee_id = payroll_employee.id 
            LEFT JOIN firm ON firm.id = payroll_employee.firm_id
            LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            
            WHERE (payroll_employee.employee_status_id IN (1,2) 
            OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))
            AND (users.manager_in_charge = '".$id."' OR users.id = '63')

            GROUP BY payroll_employee_annual_leave.employee_id");


            return $q->result();
        }
        else
        {
            $q = $this->db->query("SELECT payroll_employee_annual_leave.employee_id,payroll_employee.name,firm.name AS firm_name,AL.annual_leave_days AS AL, SL.annual_leave_days AS SL, HL.annual_leave_days AS HL FROM payroll_employee_annual_leave

            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=1 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) AL ON AL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=2 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) SL ON SL.employee_id = payroll_employee_annual_leave.employee_id
            LEFT JOIN (
                SELECT employee_id,type_of_leave_id,annual_leave_days FROM payroll_employee_annual_leave 
                WHERE type_of_leave_id=3 AND payroll_employee_annual_leave.id in (
                    SELECT max(id) FROM payroll_employee_annual_leave GROUP BY employee_id, type_of_leave_id)
            ) HL ON HL.employee_id = payroll_employee_annual_leave.employee_id

            LEFT JOIN payroll_employee ON payroll_employee_annual_leave.employee_id = payroll_employee.id 
            LEFT JOIN firm ON firm.id = payroll_employee.firm_id
            LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            
            WHERE (payroll_employee.employee_status_id IN (1,2) 
            OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)))
            AND users.manager_in_charge = '".$id."'

            GROUP BY payroll_employee_annual_leave.employee_id");


            return $q->result();
        }
    }

    public function get_calender_leaveList(){

        // BEFORE SELECT OFFICE AND DEPARTMENT
        // $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
        //     LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
        //     LEFT JOIN `firm` f ON e.firm_id = f.id
        //     LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
        //     WHERE l.status IN(2)");

        $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, e.office AS `office`, e.department AS `department`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status IN(2)");

        return $q->result_array();
    }

    public function get_calender_leaveList2($id){

        // ALLOW KARNLEE TO SEE LEAVE WHICH HWEEXIN CAN SEE
        if($id==82){
            $id = 91;

            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN payroll_user_employee ON e.id = payroll_user_employee.employee_id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            WHERE users.id = '".$id."' and l.status IN(2) OR l.status IN(2) AND users.manager_in_charge = '".$id."'");
        }
        else if($id==79)
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            WHERE l.status IN(2)");
        }
        else if($id==62)
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN payroll_user_employee ON e.id = payroll_user_employee.employee_id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            WHERE l.status IN(2) AND (users.manager_in_charge = '".$id."' OR users.id = '63')");
        }
        else
        {
            $q = $this->db->query("SELECT l.*, e.id AS `employee_id`, e.name AS `employee_name`, f.id AS `firm_id`, f.name AS `firm_name`, ptl.leave_name FROM `payroll_leave` l 
            LEFT JOIN `payroll_employee` e ON l.employee_id = e.id 
            LEFT JOIN `firm` f ON e.firm_id = f.id
            LEFT JOIN `payroll_type_of_leave` ptl ON ptl.id = l.type_of_leave_id
            LEFT JOIN payroll_user_employee ON e.id = payroll_user_employee.employee_id
            LEFT JOIN users ON users.id = payroll_user_employee.user_id
            WHERE l.status IN(2) AND users.manager_in_charge = '".$id."'");
        }

        return $q->result_array();
    }

    public function get_calender_holidayList(){
        $q = $this->db->query("SELECT * FROM payroll_block_holiday WHERE deleted = 0 GROUP BY holiday_date");

        return $q->result_array();
    }

    public function apply_leave($data){

        if(isset($data['child_dob'])?TRUE:FALSE)
        {
            $child_dob = $data['child_dob'];
            unset($data['child_dob']);
        }

        if(isset($data['child_is'])?TRUE:FALSE)
        {
            $child_is = $data['child_is'];
            unset($data['child_is']);
        }

        if(isset($data['perious_child_dob'])?TRUE:FALSE)
        {
            $perious_child_dob = $data['perious_child_dob'];
            unset($data['perious_child_dob']);
        }
        else
        {
            $perious_child_dob = "";
        }

        $q = $this->db->query("SELECT * FROM `payroll_leave` WHERE id ='". $data['id'] ."'");
        $result_array = array();

        if(!$q->num_rows())
        {
            $data['leave_no'] = random_code(8);
            $result = $this->db->insert('payroll_leave', $data); // insert new payslip to database
            $data['id'] = $this->db->insert_id();

            if(!($result > 0)) {
                array_push($result_array, array('result' => false, 'data' => array()));

                // return $result_array;
            }else{
                array_push($result_array, array('result' => true, 'data' => $data));
                
                // return $result_array;
            }
        } 
        else
        {
            $data['id'] = $q->result()[0]->id;
            $data['leave_no'] = $q->result()[0]->leave_no;

            $q2 = $this->db->where('id', $q->result()[0]->id);
            $result = $q2->update('payroll_leave', $data);

            if(!($result > 0)) {
                array_push($result_array, array('result' => false, 'data' => array()));
                
                // return $result_array;
            }else{
                array_push($result_array, array('result' => true, 'data' => $data));
                
                // return $result_array;
            }
        }

        if($data['type_of_leave_id'] == '5')
        {
            $others_leave_data = array(
                'leave_no'          => $data['leave_no'],
                'employee_id'       => $data['employee_id'],
                'type_of_leave_id'  => $data['type_of_leave_id'],
                'days'              => $data['total_days'],
                'child_dob'         => $child_dob,
                'child_is'          => $child_is,
                'perious_child_dob' => $perious_child_dob,
            );

            $abc = $this->leave_model->store_others_leave_data($others_leave_data);
        }

        return $result_array;
    }


    public function store_others_leave_data($data){

        if($data['perious_child_dob'] == "")
        {
            unset($data['perious_child_dob']);
        }

        $q = $this->db->query("SELECT * FROM `payroll_employee_others_leave` WHERE leave_no ='". $data['leave_no'] ."'");

        $result_array = array();

        if(!$q->num_rows())
        {
            $result = $this->db->insert('payroll_employee_others_leave', $data);
        } 
        else
        {
            $q2 = $this->db->query("SELECT * FROM `payroll_employee_others_leave` WHERE employee_id ='". $data['employee_id'] ."' AND child_dob ='". $data['perious_child_dob'] ."'");

            foreach($q2->result() as $item)
            {
                $q3 = $this->db->where('id', $item->id);
                $result = $q3->update('payroll_employee_others_leave', array('child_dob' => $data['child_dob']));
            }
        }

        return true;
    }

    public function reset_number_of_leave(){
        $q = $this->db->query("SELECT payroll_employee.*, payroll_type_of_leave.choose_carry_forward_id, payroll_type_of_leave.id as type_of_leave_id, payroll_employee_type_of_leave.days FROM payroll_employee LEFT JOIN payroll_employee_type_of_leave ON payroll_employee.id = payroll_employee_type_of_leave.employee_id LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id");

        foreach($q->result() as $employee){
            // YEAR(CURDATE())
            $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."' AND year(last_updated) = YEAR(CURDATE())");

            if(!$q2->num_rows())
            {
                if($employee->employee_status_id == 2)
                {
                    $q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
                    $q4 = $q4->result_array();

                    if($employee->choose_carry_forward_id == 1)
                    {   // (YEAR(CURDATE()))-1
                        $q3 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = '". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."') AND employee_id='". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."' AND year(last_updated) = (YEAR(CURDATE()))-1");

                        if($q3->num_rows())
                        {
                            $total_annual_leave = $employee->days + $q3->result()[0]->annual_leave_days;
                        }
                        else
                        {
                            $date1 = new DateTime($employee->date_joined);
                            $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                            $interval = $date1->diff($date2);

                            $years = $interval->y;
                            $months = $interval->m;
                            $days = $interval->d;

                            $balance_for_annual_leave_days = $employee->days * ($months/12);
                            // $balance_for_annual_leave_days = $employee->days * (12/12);

                            $total_annual_leave = round($balance_for_annual_leave_days);
                        }
                    }
                    else
                    {
                        $date1 = new DateTime($employee->date_joined);
                        $date2 = new DateTime(date("Y").'-'.$q4[0]["leave_cycle_date_to"]);

                        $interval = $date1->diff($date2);

                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;

                        $balance_for_annual_leave_days = $employee->days * (12/12);

                        $total_annual_leave = round($balance_for_annual_leave_days);

                        // $total_annual_leave = $employee->days;
                    }
                }
                else
                {
                    // (YEAR(CURDATE()))-1
                    $q5 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = '". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."') AND employee_id='". $employee->id ."' AND type_of_leave_id = '". $employee->type_of_leave_id ."' AND year(last_updated) = (YEAR(CURDATE()))-1");

                    if($q5->num_rows())
                    {
                        $total_annual_leave = $q5->result()[0]->annual_leave_days;
                    }
                    else
                    {
                        $total_annual_leave = 0;
                    }

                }

                $data = array(
                    'employee_id' => $employee->id,
                    'type_of_leave_id' => $employee->type_of_leave_id,
                    'annual_leave_days' => $total_annual_leave
                );

                $result = $this->db->insert('payroll_employee_annual_leave', $data);
            }
        }
    }

    public function update_status($data, $is_approve, $employee_id, $type_of_leave_id){  // for admin to approve or reject the leave
        $this->db->where('id', $data[0]['id']);
        $result = $this->db->update('payroll_leave', $data[0]);

        if($is_approve){    // Update employee remaining number of annual leave
            $q = $this->db->query("SELECT * FROM `payroll_leave` WHERE id =". $data[0]['id']);

            // create new record for updating employee remaining annual leave.
            $q2 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = ".$type_of_leave_id.")AND employee_id = ". $employee_id ."  AND type_of_leave_id = ".$type_of_leave_id."");

            $numOfLeave_Applied = $q->result()[0]->total_days;
            $annual_leave_days  = $q2->result()[0]->annual_leave_days;

            $data_2 = array(
                'employee_id'       => $employee_id,
                'type_of_leave_id'  => $type_of_leave_id,
                'annual_leave_days' => $annual_leave_days - $numOfLeave_Applied
            );

            // UPDATE payroll_employee_annual_leave TABLE & al_left_before , al_left_after ONLY IF AL/SL/HL
            if($type_of_leave_id == 1 || $type_of_leave_id == 2 || $type_of_leave_id == 3)
            {
                $result_2 = $this->db->insert('payroll_employee_annual_leave', $data_2);

                // IF APPROVE SICK LEAVE, - HOSPITALIZATION LEAVE ALSO
                if($type_of_leave_id == 2)
                {
                    $q3 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $employee_id ." AND type_of_leave_id = 3)AND employee_id = ". $employee_id ." AND type_of_leave_id = 3");

                    if($q3->num_rows())
                    {
                        $data_3 = array(
                            'employee_id'       => $employee_id,
                            'type_of_leave_id'  => 3,
                            'annual_leave_days' => $q3->result()[0]->annual_leave_days - $numOfLeave_Applied
                        );

                        $result_3 = $this->db->insert('payroll_employee_annual_leave', $data_3);
                    }
                }

                // update al_left_after
                $data_4 = array(
                    'al_left_before' => $annual_leave_days,
                    'al_left_after' => $annual_leave_days - $numOfLeave_Applied
                );
                $this->db->where('id', $data[0]['id']);
                $result_4 = $this->db->update('payroll_leave', $data_4);
            }
            else
            {
                $result_2 = true;
                $result_4 = true;
            }

            if($result && $result_2 && $result_4)
            {
                if($type_of_leave_id == 1)
                {   
                    $employee_query = $this->db->query("SELECT * FROM payroll_employee WHERE payroll_employee.id = '".$employee_id."'");
                                            
                    $employee_query = $employee_query->result_array();

                    $approve_query2 = $this->db->query(" SELECT payroll_leave.* from payroll_leave
                                                         LEFT JOIN payroll_employee ON payroll_employee.id = payroll_leave.employee_id
                                                         WHERE ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) 
                                                         AND payroll_leave.status = 2 
                                                         AND payroll_leave.type_of_leave_id = 1 
                                                         AND payroll_employee.department = '".$employee_query[0]['department']."' 
                                                         AND payroll_employee.office = '".$employee_query[0]['office']."' ");

                    if($approve_query2->num_rows())
                    {
                        $approve_query2 = $approve_query2->result_array();

                        $payroll_approval_cap_query2 = $this->db->query(" SELECT * FROM payroll_approval_cap 
                                                                          WHERE deleted = 0 
                                                                          AND ((approval_cap_date_from BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (approval_cap_date_to BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (approval_cap_date_from <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND approval_cap_date_to >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) 
                                                                          AND department_id = '".$employee_query[0]['department']."'
                                                                          AND offices_id = '".$employee_query[0]['office']."' ");

                        if($payroll_approval_cap_query2->num_rows())
                        {
                            $payroll_approval_cap_query2 = $payroll_approval_cap_query2->result_array();

                            if($payroll_approval_cap_query2[0]["number_of_employee"] <= count($approve_query2))
                            {
                                $query = $this->db->query("SELECT payroll_leave.* from payroll_leave
                                                           LEFT JOIN payroll_employee ON payroll_employee.id = payroll_leave.employee_id
                                                           WHERE ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) 
                                                           AND payroll_leave.status = 1 
                                                           AND payroll_leave.type_of_leave_id = 1 
                                                           AND payroll_employee.department = '".$employee_query[0]['department']."' 
                                                           AND payroll_employee.office = '".$employee_query[0]['office']."'");
                                
                                if($query->num_rows())
                                {
                                    $query = $query->result_array();

                                    for($t = 0; $t < count($query); $t++)
                                    {
                                        // To get the last remaining annual leave left
                                        $annual_leave_left_q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']." AND eal_1.employee_id=" . $query[$t]['employee_id'] . "");

                                        $annual_leave_left_q = $annual_leave_left_q->result_array();

                                        $payroll_leave_data['status'] = 3;
                                        $payroll_leave_data['status_updated_by'] = date('Y-m-d H:i:s');
                                        $payroll_leave_data['al_left_before'] = $annual_leave_left_q[0]['annual_leave_days'];
                                        $payroll_leave_data['al_left_after'] = $annual_leave_left_q[0]['annual_leave_days'];

                                        $update_payroll_leave = $this->db->where('id', $query[$t]['id']);
                                        $update_payroll_leave->update('payroll_leave', $payroll_leave_data);
                                    }
                                }
                            }

                        }
                    } 


                    // $approve_query = $this->db->query(" SELECT payroll_leave.* , users.department_id from payroll_leave
                    //                                     LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_leave.employee_id
                    //                                     LEFT JOIN users ON users.id = payroll_user_employee.user_id
                    //                                     WHERE ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 2 AND type_of_leave_id = 1 ");
                    
                    
                    // if($approve_query->num_rows())
                    // {
                    //     $approve_query = $approve_query->result_array();

                    //     $payroll_approval_cap_query = $this->db->query("SELECT * FROM payroll_approval_cap WHERE deleted = 0 AND ((approval_cap_date_from BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                    //     (approval_cap_date_to BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                    //     (approval_cap_date_from <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND approval_cap_date_to >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."'))");

                    //     if($payroll_approval_cap_query->num_rows())
                    //     {
                    //         $payroll_approval_cap_query = $payroll_approval_cap_query->result_array();

                    //         // FOR ALL DEPARTMENT APPROVAL CAP
                    //         if($payroll_approval_cap_query[0]['department_id'] == '7')
                    //         {
                    //             if($payroll_approval_cap_query[0]["number_of_employee"] <= count($approve_query))
                    //             {
                    //                 $query = $this->db->query("SELECT * from payroll_leave WHERE
                    //                             ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                    //                             (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR 
                    //                             (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 1 AND type_of_leave_id = 1");
                                    
                    //                 if($query->num_rows())
                    //                 {
                    //                     $query = $query->result_array();

                    //                     for($t = 0; $t < count($query); $t++)
                    //                     {
                    //                         // To get the last remaining annual leave left
                    //                         $annual_leave_left_q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.employee_id=" . $query[$t]['employee_id'] . " AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']."");
                    //                         //echo json_encode($annual_leave_left_q->result_array());
                    //                         $annual_leave_left_q = $annual_leave_left_q->result_array();

                    //                         $payroll_leave_data['status'] = 3;
                    //                         $payroll_leave_data['status_updated_by'] = date('Y-m-d H:i:s');
                    //                         $payroll_leave_data['al_left_before'] = $annual_leave_left_q[0]['annual_leave_days'];
                    //                         $payroll_leave_data['al_left_after'] = $annual_leave_left_q[0]['annual_leave_days'];

                    //                         $update_payroll_leave = $this->db->where('id', $query[$t]['id']);
                    //                         // $update_payroll_leave->update('payroll_leave', $payroll_leave_data);
                    //                     }
                    //                 }
                    //             }
                    //         }
                    //         else
                    //         {
                    //             $employee_query = $this->db->query("SELECT * FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$employee_id."'");
                                            
                    //             $employee_query = $employee_query->result_array();

                    //             $approve_query2 = $this->db->query(" SELECT payroll_leave.* , users.department_id from payroll_leave
                    //                                         LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_leave.employee_id
                    //                                         LEFT JOIN users ON users.id = payroll_user_employee.user_id
                    //                                         WHERE ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 2 AND type_of_leave_id = 1 AND users.department_id = '".$employee_query[0]['department_id']."'");

                    //             if($approve_query2->num_rows())
                    //             {
                    //                 $approve_query2 = $approve_query2->result_array();

                    //                 $payroll_approval_cap_query2 = $this->db->query("SELECT * FROM payroll_approval_cap 
                    //                     WHERE deleted = 0 AND ((approval_cap_date_from BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (approval_cap_date_to BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (approval_cap_date_from <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND approval_cap_date_to >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND department_id = '".$employee_query[0]['department_id']."'");

                    //                 if($payroll_approval_cap_query2->num_rows())
                    //                 {
                    //                     $payroll_approval_cap_query2 = $payroll_approval_cap_query2->result_array();

                    //                     if($payroll_approval_cap_query2[0]["number_of_employee"] <= count($approve_query2))
                    //                     {
                    //                         $query = $this->db->query("SELECT payroll_leave.* , users.department_id from payroll_leave
                    //                                         LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_leave.employee_id
                    //                                         LEFT JOIN users ON users.id = payroll_user_employee.user_id
                    //                                         WHERE ((start_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (end_date BETWEEN '".date('Y-m-d', strtotime($q->result()[0]->start_date))."'AND '".date('Y-m-d', strtotime($q->result()[0]->end_date))."') OR (start_date <= '".date('Y-m-d', strtotime($q->result()[0]->start_date))."' AND end_date >= '".date('Y-m-d', strtotime($q->result()[0]->end_date))."')) AND status = 1 AND type_of_leave_id = 1 AND users.department_id = '".$employee_query[0]['department_id']."'");
                                            
                    //                         if($query->num_rows())
                    //                         {
                    //                             $query = $query->result_array();

                    //                             for($t = 0; $t < count($query); $t++)
                    //                             {
                    //                                 // To get the last remaining annual leave left
                    //                                 $annual_leave_left_q = $this->db->query("SELECT * FROM payroll_employee_annual_leave eal_1 WHERE eal_1.last_updated = (SELECT MAX(eal_2.last_updated) FROM payroll_employee_annual_leave eal_2 WHERE eal_2.employee_id=" . $query[$t]['employee_id'] . " AND eal_2.type_of_leave_id = ".$query[$t]['type_of_leave_id'].") AND eal_1.employee_id=" . $query[$t]['employee_id'] . " AND eal_1.type_of_leave_id = ".$query[$t]['type_of_leave_id']."");
                    //                                 //echo json_encode($annual_leave_left_q->result_array());
                    //                                 $annual_leave_left_q = $annual_leave_left_q->result_array();

                    //                                 $payroll_leave_data['status'] = 3;
                    //                                 $payroll_leave_data['status_updated_by'] = date('Y-m-d H:i:s');
                    //                                 $payroll_leave_data['al_left_before'] = $annual_leave_left_q[0]['annual_leave_days'];
                    //                                 $payroll_leave_data['al_left_after'] = $annual_leave_left_q[0]['annual_leave_days'];

                    //                                 $update_payroll_leave = $this->db->where('id', $query[$t]['id']);
                    //                                 // $update_payroll_leave->update('payroll_leave', $payroll_leave_data);
                    //                             }
                    //                         }
                    //                     }

                    //                 }
                    //             } 
                    //         }


                    //     }
                    // }
                }

                return true;
            }else{
                return false;
            }
        }

        return json_encode($result);
    }

    public function leave_application_email($user_id,$leave_data){

        $to_list = array();

        $q1 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$user_id."' ");

        $query1 = $q1->result();
        $user_email = $query1[0]->email;

        if($query1[0]->manager_in_charge != '0')
        {
            $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$query1[0]->manager_in_charge."' ");
            $query2 = $q2->result();

            $manager_name = $query2[0]->fullname;
            $manager_email = array("email"=> $query2[0]->email);
            array_push($to_list, $manager_email);

            if($query1[0]->manager_in_charge == 91 || $query1[0]->manager_in_charge == 107)
            {
                $manager_email = array("email"=> 'hr@aaa-global.com');
                array_push($to_list, $manager_email);
            }
        }
        else
        {
            if($user_id == 63)
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '62' ");
                $query2 = $q2->result();

                $manager_name = $query2[0]->fullname;
                $manager_email = array("email"=> $query2[0]->email);
                array_push($to_list, $manager_email);
            }
            else
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '67' ");
                $query2 = $q2->result();

                $manager_name = $query2[0]->fullname;
                $manager_email = array("email"=> $query2[0]->email);
                array_push($to_list, $manager_email);
            }
        }

        $this->load->library('parser');
        $parse_data = array(
            'user_name'         => $query1[0]->fullname,
            'manager_name'      => $manager_name,
            'leave_day'         => $leave_data['total_days'],
            'start_date'        => $leave_data['start_date'],
            'end_date'          => $leave_data['end_date']
        );
        $subject    = ' Leave Application ';
        $msg        = file_get_contents('./application/modules/leave/email_templates/leave_application_email.html');
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode($to_list);
        $cc         = json_encode(array(array("email"=> $user_email)));
        $message    = $this->parser->parse_string($msg, $parse_data, true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }

    public function leave_application_email_admin($emp_id,$boss_id,$leave_data){

        $to_list = array();
        $cc_list = array();

        $q = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$boss_id."' ");
        $query = $q->result();
        $boss_email = $query[0]->email;
        array_push($cc_list, $boss_email);

        $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee on payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$emp_id."' ");

        $query1 = $q1->result();

        if($query1[0]->manager_in_charge != '0')
        {
            $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$query1[0]->manager_in_charge."' ");
            $query2 = $q2->result();

            array_push($to_list, $query1[0]->email);
            array_push($to_list, $query2[0]->email);

            $this->load->library('parser');
            $parse_data = array(
                'boss'              => $query[0]->fullname,
                'user_name'         => $query1[0]->fullname,
                'manager_name'      => $query2[0]->fullname,
                'leave_day'         => $leave_data['total_days'],
                'start_date'        => $leave_data['start_date'],
                'end_date'          => $leave_data['end_date']
            );

            $msg = file_get_contents('./application/modules/leave/email_templates/leave_application_email_admin.html');
            $subject = ' Leave Application ';
            $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
            $to_email   = json_encode($to_list);
            $cc         = json_encode($cc_list);
            $message    = $this->parser->parse_string($msg, $parse_data,true);
            $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
        }
        else
        {
            if($query1[0]->id == 63)
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '62' ");
                $query2 = $q2->result();

                array_push($to_list, $query1[0]->email);
                array_push($to_list, $query2[0]->email);

                $this->load->library('parser');
                $parse_data = array(
                    'boss'              => $query[0]->fullname,
                    'user_name'         => $query1[0]->fullname,
                    'manager_name'      => $query2[0]->fullname,
                    'leave_day'         => $leave_data['total_days'],
                    'start_date'        => $leave_data['start_date'],
                    'end_date'          => $leave_data['end_date']
                );

                $msg = file_get_contents('./application/modules/leave/email_templates/leave_application_email_admin.html');
                $subject = ' Leave Application ';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode($to_list);
                $cc         = json_encode($cc_list);
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
            else
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '67' ");
                $query2 = $q2->result();

                array_push($to_list, $query1[0]->email);
                array_push($to_list, $query2[0]->email);

                $this->load->library('parser');
                $parse_data = array(
                    'boss'              => $query[0]->fullname,
                    'user_name'         => $query1[0]->fullname,
                    'manager_name'      => $query2[0]->fullname,
                    'leave_day'         => $leave_data['total_days'],
                    'start_date'        => $leave_data['start_date'],
                    'end_date'          => $leave_data['end_date']
                );

                $msg = file_get_contents('./application/modules/leave/email_templates/leave_application_email_admin.html');
                $subject = ' Leave Application ';
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode($to_list);
                $cc         = null;
                $message    = $this->parser->parse_string($msg, $parse_data,true);
                $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
            }
        }
    }
  
    public function leave_withdraw_email($leave_id){

        $to_list = array();

        $q1 = $this->db->query(" SELECT payroll_leave.*, users.id AS user_id, users.manager_in_charge, users.email, CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM payroll_leave LEFT JOIN payroll_user_employee on payroll_user_employee.employee_id = payroll_leave.employee_id LEFT JOIN users on users.id = payroll_user_employee.user_id WHERE payroll_leave.id = '".$leave_id."' ");
        $query1 = $q1->result();

        $user_id    = $query1[0]->user_id;
        $user_email = $query1[0]->email;
        $leave_no   = $query1[0]->leave_no;
        $total_days = $query1[0]->total_days;
        $start_date = $query1[0]->start_date;
        $end_date   = $query1[0]->end_date;


        if($query1[0]->manager_in_charge != '0')
        {
            $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$query1[0]->manager_in_charge."' ");
            $query2 = $q2->result();

            $manager_name  = $query2[0]->fullname;
            $manager_email = array("email"=> $query2[0]->email);
            array_push($to_list, $manager_email);

            if($query1[0]->manager_in_charge == 91)
            {
                $manager_email = array("email"=> 'hr@aaa-global.com');
                array_push($to_list, $manager_email);
            }
        }
        else
        {
            if($user_id == 63)
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '62' ");
                $query2 = $q2->result();

                $manager_name = $query2[0]->fullname;
                $manager_email = array("email"=> $query2[0]->email);
                array_push($to_list, $manager_email);
            }
            else
            {
                $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '67' ");
                $query2 = $q2->result();

                $manager_name = $query2[0]->fullname;
                $manager_email = array("email"=> $query2[0]->email);
                array_push($to_list, $manager_email);
            }
        }


        $this->load->library('parser');
        $parse_data = array(
            'user_name'         => $query1[0]->fullname,
            'manager_name'      => $manager_name,
            'leave_no'          => $leave_no,
            'leave_day'         => $total_days,
            'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $start_date))),
            'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $end_date)))
        );

        $subject = ' Withdraw Leave - leave id: '.$leave_no.'';
        $msg = file_get_contents('./application/modules/leave/email_templates/leave_withdraw_email.html');


        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode($to_list);
        $cc         = json_encode(array(array("email"=> $user_email)));
        $message    = $this->parser->parse_string($msg, $parse_data, true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }

    public function leave_change_status_email($manager_id,$user_id,$leave){    

        $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
        $query1 = $q1->result();
        $user_email = $query1[0]->email;

        $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '".$manager_id."' ");
        $query2 = $q2->result();
        $manager_email = $query2[0]->email;

        $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave[0]['id']."' ");
        $query3 = $q3->result();

        if( json_encode($leave[0]['status']) == '2')
        {
            $status = "Approved";
            echo json_encode($leave[0]['status']);
        }
        else if( json_encode($leave[0]['status']) == '3')
        {
            $status = "Rejected (Reason: ".$leave[0]['reason'].")";
            echo json_encode($leave[0]['status']);
        }

        $this->load->library('parser');
        $parse_data = array(
            'user_name'         => $query1[0]->fullname,
            'manager_name'      => $query2[0]->fullname,
            'leave_no'          => $query3[0]->leave_no,
            'leave_day'         => $query3[0]->total_days,
            'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
            'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date))),
            'status'            => $status
        );

        $msg        = file_get_contents('./application/modules/leave/email_templates/leave_change_status_email.html');
        $subject    = ' Leave Application Status';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode(array(array("email"=> $user_email)));
        $cc         = json_encode(array(array("email"=> $manager_email)));
        $message    = $this->parser->parse_string($msg, $parse_data,true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }

    // TEAM MEMBER LEAVE NOTIFICATION 
    // public function team_leave_notification($user_id,$leave){

    //     // FOR JAMES
    //     if($user_id == 2 || $user_id == 7 || $user_id == 3)// YY & JOSEPH & BG
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '78' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave[0]['id']."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_notification.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    //     // FOR FELICIA
    //     else if($user_id == 6 || $user_id == 8 || $user_id == 9 || $user_id == 11)// VIC & DAMON & SH & SW
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '107' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave[0]['id']."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_notification.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    //     // FOR KARNLEE
    //     else if($user_id == 34 || $user_id == 35 || $user_id == 36 || $user_id == 37)// KY & YX & JACINTH & HX
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '82' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave[0]['id']."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_notification.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    // }
    // public function team_leave_withdraw_email($user_id,$leave){

    //     // FOR JAMES
    //     if($user_id == 2 || $user_id == 7 || $user_id == 3)// YY & JOSEPH & BG
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '78' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_withdraw_email.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    //     // FOR FELICIA
    //     else if($user_id == 6 || $user_id == 8 || $user_id == 9 || $user_id == 11)// VIC & DAMON & SH & SW
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '107' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_withdraw_email.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    //     // FOR KARNLEE
    //     else if($user_id == 34 || $user_id == 35 || $user_id == 36 || $user_id == 37)// KY & YX & JACINTH & HX
    //     {
    //         $q1 = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) AS fullname FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$user_id."' ");
    //         $query1 = $q1->result();
    //         // $user_email = $query1[0]->email;

    //         $q2 = $this->db->query(" SELECT *,CONCAT(first_name , ' ' , last_name) AS fullname FROM users WHERE id = '82' ");
    //         $query2 = $q2->result();
    //         $manager_email = $query2[0]->email;

    //         $q3 = $this->db->query(" SELECT * FROM payroll_leave WHERE id = '".$leave."' ");
    //         $query3 = $q3->result();

    //         $this->load->library('parser');
    //         $parse_data = array(
    //             'user_name'         => $query1[0]->fullname,
    //             'manager_name'      => $query2[0]->fullname,
    //             'start_date'        => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->start_date))),
    //             'end_date'          => date('d F Y', strtotime(str_replace('/', '-', $query3[0]->end_date)))
    //         );

    //         $msg = file_get_contents('./application/modules/leave/email_templates/team_leave_withdraw_email.html');
    //         $message = $this->parser->parse_string($msg, $parse_data,TRUE);

    //         $subject = ' Team Member Leave Notification';
    //         $this->sma->send_email($manager_email, $subject, $message,"" ,"" ,"");
    //     }
    // }

    public function get_employeeList(){
        $list = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) as Name,payroll_user_employee.employee_id as employee_id FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE group_id IN (3,5,6) AND payroll_employee.employee_status_id NOT IN (3,4) ORDER BY Name ASC ");

        $users_list = array();
        $users_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $users_list[$item->employee_id] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_employeeList2(){
        $list = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) as Name,payroll_user_employee.employee_id as employee_id FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE group_id IN (3,5,6) AND payroll_employee.employee_status_id NOT IN (3,4) ORDER BY Name ASC ");

        $users_list = array();
        foreach($list->result()as $item){
            $users_list[$item->employee_id] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_leave_balance($id,$leave){
        $list = $this->db->query(" SELECT * FROM payroll_employee_annual_leave WHERE last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = '".$id."' AND type_of_leave_id = '".$leave."') AND employee_id='".$id."' AND type_of_leave_id = '".$leave."' ");

        return $list->result();
    }

    public function day_off_email($id,$day)
    {    
        $q = $this->db->query(" SELECT users.*,CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id WHERE payroll_user_employee.employee_id = '".$id."' ");

        $query = $q->result();
        $to_email = $query[0]->email;

        // SENDINBLUE EMAIL
        $this->load->library('parser');
        $parse_data         = array('user_name' => $query[0]->Name,'day' => $day,);
        $msg                = file_get_contents('./application/modules/leave/email_templates/day_off_email.html');
        $subject            = 'Day Off Awarded';
        $from_email         = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $credential_email   = json_encode(array(array("email"=> $to_email)));
        $cc                 = json_encode(array(array("email"=> "penny@aaa-global.com")));
        $message            = $this->parser->parse_string($msg, $parse_data,true);
        $this->sma->send_by_sendinblue($subject, $from_email, $credential_email, $cc, $message, null);
        // send_by_sendinblue($subject, $from_email, $to_email, $cc = null, $message, $attachment = null)
    }

    public function get_Leave_info($id){
        $list = $this->db->query(" SELECT payroll_type_of_leave.id AS id , payroll_type_of_leave.leave_name AS leave_name FROM payroll_employee_type_of_leave LEFT JOIN payroll_type_of_leave ON payroll_type_of_leave.id = payroll_employee_type_of_leave.type_of_leave_id WHERE payroll_employee_type_of_leave.employee_id = '".$id."' ");

        return $list->result();
    }

    public function get_start_time_info($id){
        $list = $this->db->query(" SELECT * FROM payroll_employee WHERE id ='".$id."' ");

        return $list->result();
    }

    public function get_department_list(){
        $q = $this->db->query("SELECT * FROM department WHERE id NOT IN (7) ORDER BY list_order");

        $department['0'] = 'All Departments';

        foreach($q->result() as $row){
            $department[$row->id] = $row->department_name;
        }

        return $department;
    }

    public function get_office_list(){
        $q = $this->db->query("SELECT * FROM payroll_offices WHERE id NOT IN (1) AND office_deleted = 0");

        $office['0'] = 'All Offices';

        foreach($q->result() as $row){
            $office[$row->id] = $row->office_name;
        }

        return $office;
    }

    public function get_other_type_of_leave_list(){

        $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 AND payroll_type_of_leave.id NOT IN (1,2,3) ORDER BY leave_name');

        return json_encode($list->result());
    }

    public function get_staff_info($staff_id){
        $q = $this->db->query(" SELECT payroll_employee.*, GROUP_CONCAT(DISTINCT CONCAT(payroll_employee_telephone.id,',', payroll_employee_telephone.telephone, ',', payroll_employee_telephone.primary_telephone)SEPARATOR ';') AS 'employee_telephone', payroll_offices.office_country FROM payroll_employee LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = payroll_employee.id LEFT JOIN payroll_offices ON payroll_offices.id = payroll_employee.office WHERE payroll_employee.id = '".$staff_id."' ORDER BY payroll_employee_telephone.primary_telephone DESC ");

        if($q->result()[0]->employee_telephone != null)
        {
            $q->result()[0]->employee_telephone = explode(';', $q->result()[0]->employee_telephone);
        }

        if ($q->num_rows() > 0) {
            return $q->result();
        }else{
            return FALSE;
        }
    }

    // public function get_relationship($emp_id){
    //     $list = $this->db->query(" SELECT * FROM payroll_family_relationship ");

    //     $result = array();
    //     $result[''] = 'Select the Relationship';

    //     foreach($list->result()as $item){
    //         $result[$item->id] = $item->relationship_name; 
    //     }

    //     return $result;
    // }

    public function get_relationship($emp_id){

        $list = $this->db->query(" SELECT payroll_family_info.* , payroll_family_relationship.id AS relation_id , payroll_family_relationship.relationship_name , payroll_family_relationship.relatives FROM payroll_family_relationship 
            INNER JOIN payroll_family_info ON payroll_family_info.relationship = payroll_family_relationship.id 
            LEFT JOIN payroll_employee ON payroll_employee.id = payroll_family_info.employee_id 
            WHERE payroll_employee.id = '".$emp_id."'");

        return $list->result();
    }

    public function get_all_relationship(){

        $list = $this->db->query(" SELECT payroll_family_info.* , payroll_family_relationship.id AS relation_id , payroll_family_relationship.relationship_name , payroll_family_relationship.relatives FROM payroll_family_relationship 
            INNER JOIN payroll_family_info ON payroll_family_info.relationship = payroll_family_relationship.id 
            LEFT JOIN payroll_employee ON payroll_employee.id = payroll_family_info.employee_id ");

        return $list->result();
    }

    public function get_institution(){
        $list = $this->db->query(" SELECT * FROM payroll_institution ");

        $result = array();
        $result[''] = 'Select the Institution';

        foreach($list->result()as $item){
            $result[$item->id] = $item->institution_name; 
        }

        return $result;
    }

    public function get_leave_day($id){
        $list = $this->db->query(" SELECT * FROM payroll_type_of_leave WHERE id ='".$id."' ");

        return $list->result();
    }

    public function search_for_matenity($employee_id,$date)
    {
        $total_num = 0;
        $country   = '';

        $list = $this->db->query(" SELECT * FROM payroll_employee_others_leave WHERE expired_flag = 0 AND employee_id = '".$employee_id."' AND child_dob = '".$date."' ");

        foreach($list->result()as $item)
        {
            $total_num += floatval($item->days);
            $country    = $item->child_is;
        }

        return array($total_num,$country);
    }
}
?>