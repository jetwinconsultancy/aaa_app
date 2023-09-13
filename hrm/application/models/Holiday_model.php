<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getAllHolidays($employee_id){
        $query1 = $this->db->query("SELECT * FROM payroll_employee WHERE id = '".$employee_id."'");

        foreach($query1->result() as $employee){
            $department = $employee->department; 
            $office     = $employee->office; 
        }

        $query = $this->db->query(" SELECT * FROM payroll_block_holiday WHERE deleted = '0' AND offices_id = '".$office."' AND department_id = '".$department."' ");
        // $query = $this->db->query(" SELECT * FROM payroll_block_holiday WHERE deleted = '0' AND department_id = '".$department."' ");

        // $nationality_list      = array();

        // $nationality_list['']  = "-- Select a nationality --";

        // foreach($query->result() as $item){
        //     $nationality_list[$item->id] = $item->nationality; 
        // }
        
        if(count($query->result()) > 0){
            return $query->result();
        }else{
            return [];
        }
    }

    public function is_public_holiday($date,$id){
        $query1 = $this->db->query("SELECT * FROM payroll_employee WHERE id = '".$id."'");

        foreach($query1->result() as $employee){
            $department = $employee->department; 
            $office     = $employee->office; 
        }

        $q = $this->db->query("SELECT * FROM payroll_block_holiday WHERE holiday_date='" . $date . "' AND deleted = '0' AND offices_id = '".$office."' AND department_id = '".$department."'");

        if(count($q->result()) > 0){
            return true;
        }
        else{
            return false;
        }

        // $q = $this->db->query("SELECT * FROM payroll_block_holiday WHERE holiday_date='" . $date . "'");

        // if(count($q->result()) > 0){
        //     return true;
        // }
        // else{
        //     return false;
        // }
    }

    public function is_public_holiday_admin($date){

        $q = $this->db->query("SELECT * FROM payroll_block_holiday WHERE holiday_date='" . $date . "'");

        if(count($q->result()) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function get_Leave_StartTime($employee_id){
        // $q = $this->db->query("SELECT ol.*, payroll_employee.department FROM payroll_offer_letter_employee ole LEFT JOIN payroll_offer_letter ol ON ole.offer_letter_id = ol.id LEFT JOIN payroll_employee ON ole.employee_id = payroll_employee.id WHERE ole.employee_id ='". $employee_id ."'");

        // if ($q->num_rows() > 0) {
        //     $employee_data  = $q->result()[0];

        //     $start_time = array();

        //     $start_time[$employee_data->working_hour_time_start] = $employee_data->working_hour_time_start;
        //     $start_time['1:00 PM'] = '1:00 PM';

        //     return $start_time;
        // }
        // elseif ($q->department = "5") {
        //     $start_time = array();

        //     $start_time['10:00 AM'] = '10:00 AM';
        //     $start_time['1:00 PM'] = '1:00 PM';

        //     return $start_time;
        // }
        // else
        // {
        //     $start_time = array();

        //     $start_time['9:00 AM'] = '9:00 AM';
        //     $start_time['1:00 PM'] = '1:00 PM';

        //     return $start_time;
        // }
        //print_r($employee_id);


        $q = $this->db->query("SELECT * FROM payroll_employee WHERE id ='".$employee_id."'");

        if ($q->num_rows() > 0) {
            $t = $q->result_array();
            if ($t[0]['department'] == "5") {
                $start_time = array();

                $start_time['9:00 AM'] = '9:00 AM';
                $start_time['1:00 PM']  = '1:00 PM';

                return $start_time;
            }
            else{

                $start_time = array();

                $start_time['9:00 AM'] = '9:00 AM';
                $start_time['1:00 PM'] = '1:00 PM';

                return $start_time;
            }
        }
    }

    public function get_Leave_EndTime($employee_id){
        // $q = $this->db->query("SELECT ol.*, payroll_employee.department FROM payroll_offer_letter_employee ole LEFT JOIN payroll_offer_letter ol ON ole.offer_letter_id = ol.id LEFT JOIN payroll_employee ON ole.employee_id = payroll_employee.id WHERE ole.employee_id ='". $employee_id ."'");

        // if ($q->num_rows() > 0) {
        //     $employee_data  = $q->result()[0];

        //     $start_time = array();

        //     $start_time['1:00 PM'] = '1:00 PM';
        //     $start_time[$employee_data->working_hour_time_end] = $employee_data->working_hour_time_end;

        //     return $start_time;

        // }elseif ($q->department == "5") {
        //     $start_time = array();

        //     $start_time['1:00 PM'] = '1:00 PM';
        //     $start_time['6:00 PM'] = '6:00 PM';

        //     return $start_time;
        // }
        // else
        // {
        //     $start_time = array();

        //     $start_time['1:00 PM'] = '1:00 PM';
        //     $start_time['6:00 PM'] = '6:00 PM';

        //     return $start_time;
        // }

        $q = $this->db->query("SELECT * FROM payroll_employee WHERE id ='".$employee_id."'");

        if ($q->num_rows() > 0) {
            $t = $q->result_array();
            if ($t[0]['department'] == "5") {
                $start_time = array();

                $start_time['1:00 PM'] = '1:00 PM';
                $start_time['6:00 PM'] = '6:00 PM';

                return $start_time;
            }
            else{

                $start_time = array();

                $start_time['1:00 PM'] = '1:00 PM';
                $start_time['6:00 PM'] = '6:00 PM';

                return $start_time;
            }
        }
    }
}
?>