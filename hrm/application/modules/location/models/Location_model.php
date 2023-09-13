<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_all_arrangement(){

        $all_arrangement = $this->db->query(" SELECT payroll_employee.name,payroll_location_arrangement.arrangement_location,payroll_location_arrangement.arrangement_start,payroll_location_arrangement.arrangement_end,payroll_location_arrangement.is_client_office FROM payroll_location_arrangement LEFT JOIN payroll_employee ON payroll_employee.id = payroll_location_arrangement.arrangement_employee_id WHERE payroll_location_arrangement.deleted = 0 ORDER BY payroll_location_arrangement.arrangement_start ");

        return $all_arrangement->result();
    }

    public function get_new_arrangement(){

        $new_arrangement = $this->db->query(" SELECT payroll_location_arrangement.id,payroll_employee.name,payroll_location_arrangement.arrangement_location,payroll_location_arrangement.arrangement_start,payroll_location_arrangement.arrangement_end,payroll_location_arrangement.is_client_office FROM payroll_location_arrangement LEFT JOIN payroll_employee ON payroll_employee.id = payroll_location_arrangement.arrangement_employee_id WHERE DATE(payroll_location_arrangement.arrangement_end) >= CURRENT_DATE AND payroll_location_arrangement.deleted = 0 ORDER BY payroll_location_arrangement.arrangement_start ");

        return $new_arrangement->result();
    }

    public function get_old_arrangement(){

        $old_arrangement = $this->db->query(" SELECT payroll_location_arrangement.id,payroll_employee.name,payroll_location_arrangement.arrangement_location,payroll_location_arrangement.arrangement_start,payroll_location_arrangement.arrangement_end,payroll_location_arrangement.is_client_office FROM payroll_location_arrangement LEFT JOIN payroll_employee ON payroll_employee.id = payroll_location_arrangement.arrangement_employee_id WHERE DATE(payroll_location_arrangement.arrangement_end) < CURRENT_DATE AND payroll_location_arrangement.deleted = 0 ORDER BY payroll_location_arrangement.arrangement_start ");

        return $old_arrangement->result();
    }

    public function get_arrangement_details($id){

        $arrangement_details = $this->db->query(" SELECT payroll_location_arrangement.id,payroll_employee.id AS emp_id,payroll_employee.name,payroll_location_arrangement.arrangement_location,payroll_location_arrangement.arrangement_start,payroll_location_arrangement.arrangement_end,payroll_location_arrangement.location_address,payroll_location_arrangement.is_client_office FROM payroll_location_arrangement LEFT JOIN payroll_employee ON payroll_employee.id = payroll_location_arrangement.arrangement_employee_id WHERE payroll_location_arrangement.id = '".$id."' ");

        return $arrangement_details->result();
    }

    public function get_employee_list(){

    	$employee_list = $this->db->query("SELECT e.*, ue.user_id AS `user_id`, u.email AS `user_email`,payroll_offices.office_name , department.department_name,payroll_employee_telephone.telephone FROM payroll_employee e LEFT JOIN payroll_user_employee ue ON ue.employee_id = e.id LEFT JOIN users u ON ue.user_id = u.id LEFT JOIN department ON department.id = e.department LEFT JOIN payroll_offices ON payroll_offices.id = e.office LEFT JOIN payroll_employee_telephone ON payroll_employee_telephone.employee_id = e.id AND payroll_employee_telephone.primary_telephone = 1 WHERE e.employee_status_id IN (1,2) OR (e.employee_status_id IN (3,4) AND DATE(date_cessation) >= DATE(CURRENT_DATE)) GROUP BY e.id ORDER BY e.name");

    	$result = array();
    	$result[''] = 'Select Employee';

        foreach($employee_list->result()as $employee){
            $result[$employee->id] = $employee->name; 
        }

        return $result;

    }

    public function get_our_office(){

        $location = array();
        $location[''] = 'Select Location';

        $q = $this->db->query("SELECT * FROM firm WHERE id IN (15,18,24) ORDER BY name ASC");

        foreach($q->result() as $office){

            if($office->id == '15')
            {
                $location[$office->id] = 'SBF Office';
            }
            else if($office->id == '18')
            {
                $location[$office->id] = 'Novelty Office';
            }
            else if($office->id == '24')
            {
                $location[$office->id] = 'UOA Office';
            } 
        }

        return $location;
    }

    public function get_client_office(){

        $location = array();
        $location[''] = 'Select Location';

        $q = $this->db->query("SELECT * FROM client WHERE deleted = 0 ORDER BY company_name ASC");

        foreach($q->result() as $client){
            $location[$client->company_code] = $this->encryption->decrypt($client->company_name); 
        }

        return $location;
    }

    public function submit_arrangement($form_data)
    {
        if($form_data['id'])
        {
            $arrangement_start_temp = explode('-', $form_data['arrangement_start']);
            $arrangement_start      = $arrangement_start_temp[0] . str_replace("", "", $arrangement_start_temp[1]);

            $arrangement_end_temp   = explode('-', $form_data['arrangement_end']);
            $arrangement_end        = $arrangement_end_temp[0] . str_replace("", "", $arrangement_end_temp[1]);

            $input = array(
                'arrangement_employee_id'  => $form_data['arrangement_employee_id'],
                'arrangement_start'        => date('Y-m-d H:i:s', strtotime($arrangement_start)),
                'arrangement_end'          => date('Y-m-d H:i:s', strtotime($arrangement_end)),
                'is_client_office'         => $form_data['is_client_office'],
                'arrangement_location'     => $form_data['arrangement_location'],
                'location_address'         => $form_data['location_address'],
            );
            $this->db->where('id', $form_data['id']);
            $result = $this->db->update('payroll_location_arrangement', $input);
        }
        else
        {
            $arrangement_start_temp = explode('-', $form_data['arrangement_start']);
            $arrangement_start      = $arrangement_start_temp[0] . str_replace("", "", $arrangement_start_temp[1]);

            $arrangement_end_temp   = explode('-', $form_data['arrangement_end']);
            $arrangement_end        = $arrangement_end_temp[0] . str_replace("", "", $arrangement_end_temp[1]);

            $input = array(
                'arrangement_employee_id'  => $form_data['arrangement_employee_id'],
                'arrangement_start'        => date('Y-m-d H:i:s', strtotime($arrangement_start)),
                'arrangement_end'          => date('Y-m-d H:i:s', strtotime($arrangement_end)),
                'is_client_office'         => $form_data['is_client_office'],
                'arrangement_location'     => $form_data['arrangement_location'],
                'location_address'         => $form_data['location_address'],
            );
            $result = $this->db->insert('payroll_location_arrangement', $input); 
        }

        return $result;
    }

    public function withdraw_arrangement($id){
    	$this->db->where('id', $id);
        $result = $this->db->update('payroll_location_arrangement', array('deleted'=>1));
    }

    public function get_firm_address($id){
        $q = $this->db->query("SELECT * FROM firm WHERE id = '".$id."'");

        return json_encode($q->result());
    }

    public function get_client_address($id){
        $q = $this->db->query("SELECT * FROM client WHERE company_code = '".$id."'");

        return json_encode($q->result());
    }

    public function get_client_name($id){
        $q = $this->db->query("SELECT * FROM client WHERE company_code = '".$id."'");

        foreach($q->result() as $client){
            $client_name = $this->encryption->decrypt($client->company_name); 
        }

        return $client_name;
    }

    public function get_employee_address($id){

        $q = $this->db->query("SELECT * FROM payroll_employee WHERE id = '".$id."'");

        return $q->result();
    }
}
?>