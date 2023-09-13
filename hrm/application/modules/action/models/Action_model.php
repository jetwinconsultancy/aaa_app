<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Action_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_employeeList($user_id = NULL){

        if($user_id == NULL) // ADMIN
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name , department.department_name,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) > DATE(CURRENT_DATE)) GROUP BY e.id ORDER BY e.name");
        }
        else  // NORMAL USER
        {
            $q = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name, department.department_name ,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE ue.user_id ='". $user_id ."' GROUP BY e.id ORDER BY e.name");
        }

        return $q->result();
    }

    public function get_staff_info($staff_id){
        $q = $this->db->query(" SELECT payroll_employee.*, GROUP_CONCAT(DISTINCT CONCAT(payroll_employee_telephone.id,',', payroll_employee_telephone.telephone, ',', payroll_employee_telephone.primary_telephone)SEPARATOR ';') AS 'employee_telephone' FROM payroll_employee LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = payroll_employee.id WHERE payroll_employee.id = '".$staff_id."' ORDER BY payroll_employee_telephone.primary_telephone DESC ");

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

    public function get_event_info($staff_id){
        $q = $this->db->query("SELECT payroll_event_info.*, payroll_user_employee.user_id FROM payroll_event_info LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_event_info.employee_id WHERE payroll_event_info.employee_id='".$staff_id."' AND payroll_event_info.deleted=0");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    }

    public function get_open_bank_info(){
        $list = $this->db->query("SELECT * FROM payroll_bank_details");

        $open_bank_list2 = array();
        $open_bank_list = array();
        $result = array();
        $open_bank_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $open_bank_list[$item->id] = $item->name;
            $open_bank_list2[$item->id] = $item->address;
        }

        array_push($result, $open_bank_list);
        array_push($result, $open_bank_list2);

        return $result;
    }

    public function getEventType(){
        $query = $this->db->query("SELECT * FROM payroll_event_type");

        foreach($query->result() as $item){
            $event_list[$item->id] = $item->event; 
        }

        return $event_list;
    }

    public function get_employeeDepartment()
    {
        $list = $this->db->query("SELECT * FROM department ORDER BY list_order ASC");

        $employee_department_list = array();
        $employee_department_list[''] = 'Select a Employee Department';

        foreach($list->result()as $item){
            $employee_department_list[$item->id] = $item->department_name; 
        }

        return $employee_department_list;
    }

    public function getEmployeeData($id){
        $query = $this->db->query("
            SELECT e.*, f.id AS `firm_id`, f.name AS `company_name`,tf.days FROM payroll_employee e
            LEFT JOIN firm f ON f.id = e.firm_id
            LEFT JOIN payroll_employee_type_of_leave tf ON tf.employee_id = e.id AND tf.type_of_leave_id = 1
            WHERE e.id = '".$id."'
        ");

        return $query->result();
    }

    public function get_firm_dropdown_list(){
        $q = $this->db->query("SELECT firm.* FROM firm left join user_firm on user_firm.firm_id = firm.id AND user_firm.user_id = '".$this->session->userdata('user_id')."' WHERE user_firm.user_id = '".$this->session->userdata('user_id')."'");

        $firms = array();
        $firms[''] = 'Select a firm';

        foreach($q->result() as $firm){
            $firms[$firm->id] = $firm->name; 
        }

        return $firms;
    }
}
?>