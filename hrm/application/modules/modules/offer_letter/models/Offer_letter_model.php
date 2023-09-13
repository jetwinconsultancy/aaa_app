<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Offer_letter_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getApplicantData($id){
        $query = $this->db->query("SELECT applicant.id AS `applicant_id`, applicant.name,applicant.ic_passport_no, applicant.expected_salary, applicant.nationality_id, firm.name AS `company_name`, payroll_offer_letter.* FROM applicant 
                                    LEFT JOIN applicant_interview ON applicant_interview.applicant_id = applicant.id 
                                    LEFT JOIN interview ON applicant_interview.interview_id = interview.id 
                                    LEFT JOIN payroll_offer_letter_applicant ON payroll_offer_letter_applicant.applicant_id = applicant.id 
                                    LEFT JOIN payroll_offer_letter ON payroll_offer_letter.id = payroll_offer_letter_applicant.offer_letter_id 
                                    LEFT JOIN firm ON firm.id = interview.firm
                                    WHERE applicant.id ='".$id."'");

        return $query->result();
    }

    public function getEmployeeData($id){
        // $query = $this->db->query("SELECT * from employee WHERE id ='".$id."'");

        $query = $this->db->query("SELECT e.id AS `employee_id`, e.name, e.workpass, e.salary, f.id AS `firm_id`, f.name AS `company_name`, ol.* FROM payroll_employee e
                                    LEFT JOIN firm f ON f.id = e.firm_id
                                    LEFT JOIN payroll_offer_letter_employee ole ON ole.employee_id = e.id
                                    LEFT JOIN payroll_offer_letter ol ON ole.offer_letter_id = ol.id AND YEAR(ol.effective_from) = YEAR(CURRENT_TIMESTAMP)
                                    WHERE e.id = '".$id."'");

        return $query->result();
    }

    public function create_save_offer_letter_new_employee($data, $applicant_id){

        $q = $this->db->get_where('payroll_offer_letter', array('id' => $data['id'])); 

        if(empty($data['id']))
        {
            $data['date_offer'] = date('Y-m-d', strtotime('today'));

            $this->db->insert('payroll_offer_letter', $data);

            $offer_letter_id = $this->db->insert_id();

            if(!empty($offer_letter_id)){
                $offer_letter_applicant_data = array(
                    'offer_letter_id' => $offer_letter_id,
                    'applicant_id'    => $applicant_id
                );

                $this->db->insert('payroll_offer_letter_applicant', $offer_letter_applicant_data);
            }

            return $offer_letter_id;
        }
        else
        {
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update('payroll_offer_letter', $data);

            return $q->result()[0]->id; // retrieve id
        }
    }

    public function create_save_offer_letter_employee($data, $employee_id){

        $q = $this->db->get_where('payroll_offer_letter', array('id' => $data['id'])); 

        if(empty($data['id'])){
            $this->db->insert('payroll_offer_letter', $data);

            $offer_letter_id = $this->db->insert_id();

            if(!empty($offer_letter_id)){
                $offer_letter_employee_data = array(
                    'offer_letter_id' => $offer_letter_id,
                    'employee_id'    => $employee_id
                );

                $this->db->insert('payroll_offer_letter_employee', $offer_letter_employee_data);
            }

            return $offer_letter_id;
        }
        else{
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update('payroll_offer_letter', $data);

            return $q->result()[0]->id; // retrieve id

            // return 'hello';
        }
        // return empty($data['id']);
    }

    public function getApplicant_OL($id){
        // $query = $this->db->query("SELECT i.firm AS `firm_id`, f.name AS `firm_name`, a.name, a.ic_passport_no, ol.effective_from, a.nationality_id, ol.probationary_period, ol.working_hour_time_start, ol.working_hour_time_end, ol.working_hour_day_start, ol.working_hour_day_end, ol.given_salary, ol.termination_notice, ol.employer FROM applicant a 
        //     LEFT JOIN applicant_interview ai ON ai.applicant_id = a.id 
        //     LEFT JOIN interview i ON ai.interview_id = i.id 
        //     JOIN firm f ON f.id = i.firm
        //     LEFT JOIN payroll_offer_letter_applicant ofa ON ofa.applicant_id = a.id 
        //     LEFT JOIN payroll_offer_letter ol ON ofa.offer_letter_id = ol.id WHERE a.id ='" . $id . "'");

        $query = $this->db->query("SELECT i.firm AS `firm_id`, f.name AS `firm_name`, a.name, a.ic_passport_no, ol.*, a.nationality_id, salaryCurrency.currency AS salary_currency_code, bondCurrency.currency AS bond_currency_code FROM applicant a 
            LEFT JOIN applicant_interview ai ON ai.applicant_id = a.id 
            LEFT JOIN interview i ON ai.interview_id = i.id 
            JOIN firm f ON f.id = i.firm
            LEFT JOIN payroll_offer_letter_applicant ofa ON ofa.applicant_id = a.id 
            LEFT JOIN payroll_offer_letter ol ON ofa.offer_letter_id = ol.id 
            LEFT JOIN currency salaryCurrency ON ol.salary_currency = salaryCurrency.id
            LEFT JOIN currency bondCurrency ON ol.bond_currency = bondCurrency.id 
            WHERE a.id ='" . $id . "'
        ");

        return $query->result();
    }

    public function getEmployee_OL($id){
        $q = $this->db->query("SELECT f.id AS `firm_id`, f.name AS `firm_name`, e.nationality_id, e.name, e.nric_fin_no AS `ic_passport_no`, ol.effective_from, ol.probationary_period, ol.working_hour_time_start, ol.working_hour_time_end, ol.working_hour_day_start, ol.working_hour_day_end, ol.given_salary, ol.termination_notice, ol.employer FROM payroll_employee e
            JOIN payroll_offer_letter_employee ole ON ole.employee_id = e.id
            JOIN payroll_offer_letter ol ON ol.id = ole.offer_letter_id AND ol.effective_from = (SELECT MAX(ol.effective_from) FROM payroll_offer_letter_employee ole2 
                                                                                            JOIN payroll_offer_letter ol ON ol.id = ole2.offer_letter_id 
                                                                                            WHERE ole2.employee_id = e.id)
            JOIN firm f ON f.id = e.firm_id
            WHERE e.id = '" . $id . "'");

        return $q->result();
    }

    public function add_offer_letter_employee($data){
        return $result = $this->db->insert('payroll_offer_letter_employee', $data);
    }

    public function remove_offer_letter_applicant($applicant_id){
        return $result = $this->db->delete('payroll_offer_letter_applicant', array('applicant_id' => $applicant_id));
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

    public function get_departmentName($id){
        $q = $this->db->query("SELECT * FROM department WHERE department.id = '".$id."'");

        return $q->result();
    }

    public function get_currency_dropdown(){
        $q = $this->db->query("SELECT * FROM currency ORDER BY id");

        $currencies     = array();
        $currencies[''] = "Select Currency";
        foreach($q->result() as $currency){
            $currencies[$currency->id] = $currency->currency; 
        }
        
        
        return $currencies;
    }

}
?>