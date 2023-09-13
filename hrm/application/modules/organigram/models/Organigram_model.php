<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Organigram_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_employeeDepartment()
    {
        $list = $this->db->query("SELECT * FROM department WHERE id != '7' ORDER BY list_order ASC");
        return $list->result();
    }

    public function get_designation($department)
    {
        $list = $this->db->query(' SELECT * FROM payroll_designation WHERE department_id LIKE "'.$department.'" GROUP BY designation ORDER BY sorting DESC');
        return $list->result();
    }

    public function get_position_staff($department,$designation)
    {
        $list = $this->db->query("SELECT payroll_employee.* , users.email , payroll_employee_telephone.telephone , payroll_offices.office_name , payroll_offices.office_country FROM payroll_employee 
                                    LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
                                    LEFT JOIN users ON payroll_user_employee.user_id = users.id 
                                    LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = payroll_employee.id
                                    LEFT JOIN payroll_offices ON payroll_offices.id = payroll_employee.office
                                    WHERE (payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE))) AND payroll_employee.department = '".$department."' AND payroll_employee.designation = '".$designation."' AND payroll_employee_telephone.primary_telephone = '1' ");
        
        return $list->result();
    }
    
}
?>