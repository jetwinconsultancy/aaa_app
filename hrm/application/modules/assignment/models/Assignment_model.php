<?php defined('BASEPATH') OR exit('No direct script access allowed');
include 'application/js/random_alphanumeric_generator.php';

class Assignment_model extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function get_firm_dropdown_list(){
        $q = $this->db->query("SELECT firm.* FROM firm left join user_firm on user_firm.firm_id = firm.id AND user_firm.user_id = '".$this->session->userdata('user_id')."' WHERE user_firm.user_id = '".$this->session->userdata('user_id')."' GROUP BY firm.name ORDER BY firm.name ASC");

        $firms = array();
        $firms[''] = '-- Please Select the Firm --';

        foreach($q->result() as $firm){
            $firms[$firm->id] = $firm->name; 
        }

        return $firms;
    }

    public function get_client_dropdown_list(){
        $q = $this->db->query("SELECT * FROM client WHERE deleted = 0 ORDER BY company_name ASC");

        $clients = array();
        $clients[''] = '-- Please Select the Client --';

        foreach($q->result() as $client){
            $clients[$client->company_code] = $this->encryption->decrypt($client->company_name); 
        }

        return $clients;
    }

    public function get_status_dropdown_list(){
        $q = $this->db->query("SELECT * FROM payroll_assignment_status ORDER BY list_order");

        $status = array();
        $status[''] = "-- Status --";

        foreach($q->result() as $client){
            $status[$client->id] = $client->assignment_status; 
        }

        return $status;
    }

    public function get_status_dropdown_list2(){
        $q = $this->db->query("SELECT * FROM payroll_assignment_status WHERE id IN(7,8,9,10,11) ORDER BY list_order");

        $status = array();
        $status[''] = "-- Status --";

        foreach($q->result() as $client){
            $status[$client->id] = $client->assignment_status; 
        }

        return $status;
    }

    public function get_completed_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status = '10' 
                                AND payroll_assignment.deleted = '0' 
                                AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)");

        return $q->result();
    }

    public function get_user_completed_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status = '10' 
                                AND payroll_assignment.deleted = '0'
                                AND payroll_assignment.PIC like '%".$userName."%'
                                AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)");

        return $q->result();
    }

    public function get_planning_completed_list(){

        // $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
        //                         FROM payroll_assignment 
        //                         LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
        //                         LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
        //                         LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
        //                         LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
        //                         WHERE payroll_assignment.status IN (15,17)
        //                         AND payroll_assignment.type_of_job  = '13'
        //                         AND payroll_assignment.deleted = '0'
        //                         AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)");

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status IN (15,17)
                                AND payroll_assignment.type_of_job  = '13'
                                AND payroll_assignment.deleted = '0'
        ");

        return $q->result();
    }

    public function get_user_planning_completed_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                WHERE payroll_assignment.status IN (15,17)
                                AND payroll_assignment.type_of_job  = '13'
                                AND payroll_assignment.deleted = '0'
                                AND payroll_assignment.PIC like '%".$userName."%'
                                AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)");

        return $q->result();
    }

    public function get_signed_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                LEFT JOIN payroll_assignment_completed ON payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                WHERE payroll_assignment.signed = '1' 
                                AND payroll_assignment.deleted = '0'
                                AND YEAR(payroll_assignment_completed.report_date) = YEAR(CURRENT_DATE)");

        return $q->result();
    }

    public function get_user_signed_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id
                                LEFT JOIN payroll_assignment_completed ON payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                WHERE payroll_assignment.signed = '1' 
                                AND payroll_assignment.deleted = '0'
                                AND payroll_assignment.PIC like '%".$userName."%'
                                AND YEAR(payroll_assignment_completed.report_date) = YEAR(CURRENT_DATE)");

        return $q->result();
    }

    public function get_assignment_list(){

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                WHERE payroll_assignment.status != '10' AND payroll_assignment.deleted = '0'");
        
        return $q->result();
        // print_r($q->result_array());
    }

    public function get_user_assignment_list($id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query("SELECT payroll_assignment.*,client.company_name,firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job 
                                FROM payroll_assignment 
                                LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
                                LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
                                LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
                                LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                WHERE payroll_assignment.status != '10' AND payroll_assignment.deleted = '0' AND payroll_assignment.PIC like '%".$userName."%'");
        return $q->result();
    }

    public function get_completed_assignment_list(){

        $q = $this->db->query("SELECT payroll_assignment_completed.*,firm.name,client.company_name FROM payroll_assignment_completed INNER JOIN firm ON payroll_assignment_completed.firm_id = firm.id INNER JOIN client ON payroll_assignment_completed.client_id = client.company_code");
        
        return $q->result();
    }

    public function submit_assignment($data,$id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_assignment', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_assignment', $data); 
        }

        return $result;
    }

    public function submit_recurring($client_name,$fye,$job,$recurring)
    {
        $data = array(
            'client_name' => $client_name,
            'fye'         => $fye,
            'type_of_job' => $job,
            'recurring'   => $recurring
        );

        $q = $this->db->query('SELECT * FROM payroll_assignment_recurring WHERE client_name = "'.$client_name.'" AND type_of_job = "'.$job.'"');

        if ($q->num_rows() > 0)
        {
            $query_result = $q->result();

            $id = $query_result[0]->id;
            
            $this->db->where('id', $id);
            $result = $this->db->update('payroll_assignment_recurring', $data);
        }
        else
        {
            if($recurring != 'non' && $fye != NULL)
            {
                $result = $this->db->insert('payroll_assignment_recurring', $data);
            } 
        }
    }

    public function save_completed_assignment($data,$id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_assignment_completed', $data); 
        }
        else
        {
            $result = $this->db->insert('payroll_assignment_completed', $data); 
        }

        return $result;
    }

    public function get_dispensed_final_year_end($client_id){

        if($client_id != '0'){
            $q = $this->db->query("SELECT * FROM filing WHERE company_code = '".$client_id."' ORDER BY year_end DESC LIMIT 1");

            foreach($q->result() as $item){

                $this->db->where('id', $item->id);
                $this->db->update('filing', array('agm' => 'dispensed'));

                $fye = $item->year_end; 
                return $fye;
            }
        }
        else{

            $fye = '0';
            return $fye;
        }
    }

    public function update_final_year_end($company_code,$year_end){

        //---------check 28 or 29 February---------------------
        $original_fye_date = $year_end;

        $dm = date('d F', strtotime($original_fye_date));

        if($dm == "28 February")
        {
            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

            $dt = new DateTime($fye_date);

            $dt->modify( 'first day of next month' );
            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

            $fye_dfy = $dt->format('d F Y');
            $fye_ymd = $dt->format('Y-m-d');
        }
        else if($dm == "29 February")
        {
            $fye_date = date('d F Y', strtotime('+1 year', strtotime($original_fye_date)));

            $dt = new DateTime($fye_date);

            //$dt->modify( 'first day of next month' );
            $dt->modify('+' . (min($day, $dt->format('t')) - 1) . ' days');

            $fye_dfy = $dt->format('d F Y');
            $fye_ymd = $dt->format('Y-m-d');
        }
        else
        {
            $fye_dfy = date('d F Y', strtotime('+1 year', strtotime($year_end)));
            $fye_ymd = date('Y-m-d', strtotime('+1 year', strtotime($year_end)));
        }

        $new_filing['company_code'] = $company_code;
        $new_filing['year_end'] = $fye_dfy;
        $new_filing['ar_filing_date'] = "";
        $new_filing['financial_year_period_id'] = 1;
        $new_filing['financial_year_period1'] = date('d F Y', strtotime('+1 day', strtotime($year_end)));
        $new_filing['financial_year_period2'] = $fye_dfy;
        $new_filing['175_extended_to'] = 0;
        $new_filing["201_extended_to"] = 0;
        $new_filing["197_extended_to"] = 0;
        $new_filing['due_date_175'] = "Not Applicable";

        $latest_year_end = date('Y-m-d', strtotime($new_filing['year_end']));

        $year_end_date = new DateTime($latest_year_end);
        if(date('Y-m-d', strtotime("8/31/2018")) > $fye_ymd) 
        {
            $new_filing['agm'] = "";

            $latest_due_date_201 = date('Y-m-d', strtotime($new_filing['year_end']));

            $date1 = new DateTime($latest_due_date_201);
            // We extract the day of the month as $start_day
            $date1 = $this->MonthShifter($date1,6)->format(('Y-m-d'));

            $new_filing['due_date_201'] =  date("t F Y", strtotime($date1));

            if($new_filing['due_date_175'] == "Not Applicable")
            {
                $new_filing['due_date_197'] = "Not Applicable";
            }
            //$new_filing['due_date_197'] = date('d F Y', strtotime('+30 days', strtotime($new_filing['year_end'])));
        }
        else
        {
            $new_filing['agm'] = "";

            $date_175 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

            //$new_filing['due_date_175'] = date('d F Y', strtotime($date_175));
            $new_filing['due_date_175'] = "";

            $date_201 = $this->MonthShifter($year_end_date,6)->format(('Y-m-d'));

            $new_filing['due_date_201'] = date('t F Y', strtotime($date_201));

            $date_197 = $this->MonthShifter($year_end_date,7)->format(('Y-m-d'));

            $new_filing['due_date_197'] =  date("t F Y", strtotime($date_197));
        }
        
        $this->db->insert("filing",$new_filing);
    }

    public function CA_filter($office,$department,$partner,$from,$to){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <= '".$to."' 
                                            AND payroll_assignment_completed.report_date >= '".$from."' 
                                            AND payroll_assignment.status = 10
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }

        }
        else
        {
            return NULL;
        }

    }

    public function CA_filter2($office,$department,$partner,$from,$to,$id){
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        if($office == '0' || $office == 'undefined'){
            $office = '%%';
        }

        if($department == '0' || $department == 'undefined'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <= '".$to."' 
                                            AND payroll_assignment_completed.report_date >= '".$from."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status = 10
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <='".$to."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.status = 10 
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.status = 10 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
        }
        else
        {
            return NULL;
        }

    }


    public function PC_filter($office,$department,$partner,$from,$to){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '%".$partner."%'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '%".$partner."%'
                                            AND payroll_assignment.complete_date <='".$to."' 
                                            AND payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date <= '".$to."' 
                                            AND payroll_assignment.complete_date >= '".$from."' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date <='".$to."' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '%".$partner."%'
                                            AND payroll_assignment.complete_date <='".$to."' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '%".$partner."%'
                                            AND payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)
                                            ".$office_department."");

                return $list->result();
            }

        }
        else
        {
            return NULL;
        }

    }

    public function PC_filter2($office,$department,$partner,$from,$to,$id){
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        if($office == '0' || $office == 'undefined'){
            $office = '%%';
        }

        if($department == '0' || $department == 'undefined'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.complete_date <='".$to."' 
                                            AND payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date <= '".$to."' 
                                            AND payroll_assignment.complete_date >= '".$from."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.complete_date <='".$to."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.complete_date <='".$to."' 
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC LIKE '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.complete_date >='".$from."'
                                            AND payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.status IN (15,17) 
                                            AND payroll_assignment.type_of_job = '13'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.deleted = '0'
                                            AND YEAR(payroll_assignment.complete_date) = YEAR(CURRENT_DATE)
                                            ".$office_department."");

                return $list->result();
            }
        }
        else
        {
            return NULL;
        }

    }


    public function SA_filter($office,$department,$partner,$from,$to){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";

            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <= '".$to."' 
                                            AND payroll_assignment_completed.report_date >= '".$from."' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
        }
        else
        {
            return NULL;
        }
    }

    public function SA_filter2($office,$department,$partner,$from,$to,$id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";

            // IF ONLY FILTER BY PARTNER
            if($partner != "0" && ($from == "" && $to == "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY PARTNER AND FROM&TO DATE
            else if($partner != "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM&TO DATE
            else if ($partner == "0" && ($from != "" && $to != "")){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <= '".$to."' 
                                            AND payroll_assignment_completed.report_date >= '".$from."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY FROM DATE
            else if ($partner == "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF ONLY FILTER BY TO DATE
            else if ($partner == "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.report_date <='".$to."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY TO DATE & PARTNER
            else if ($partner != "0" && $from == "" && $to != ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."' 
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            AND payroll_assignment_completed.report_date <='".$to."' 
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            // IF FILTER BY FROM DATE & PARTNER
            else if ($partner != "0" && $from != "" && $to == ""){
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment_completed.partner = '".$partner."'
                                            AND payroll_assignment.PIC like '%".$userName."%' 
                                            AND payroll_assignment_completed.report_date >='".$from."'
                                            AND payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            ".$office_department."");

                return $list->result();
            }
            else{
                $list = $this->db->query(" SELECT payroll_assignment_completed.*,payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment_completed 
                                            LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id
                                            LEFT JOIN payroll_assignment on payroll_assignment.id = payroll_assignment_completed.payroll_assignment_id
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.signed = 1
                                            AND payroll_assignment.deleted = '0'
                                            AND payroll_assignment.PIC like '%".$userName."%'
                                            ".$office_department."");

                return $list->result();
            }
        }
        else
        {
            return NULL;
        }

    }

    public function A_filter($office,$department,$partner,$staff){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            if($partner != "0" && $staff == "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC like '%".$partner."%' 
                                            ".$office_department."
                                            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0' ");

                return $list->result();
            }
            else if($partner == "0" && $staff != "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE (".$staff.")
                                            ".$office_department."
                                            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }
            else if($partner != "0" && $staff != "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC like '%".$partner."%'
                                            AND (".$staff.")
                                            ".$office_department."
                                           AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }
            else{
                 $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            ".$office_department."
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }

        }
        else
        {
            return NULL;
        }

    }

    public function A_filter2($partner,$id){

        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);

        if($partner != "0"){
            $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE payroll_assignment.PIC like '%".$partner."%' 
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }
        else{
             $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                        LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                        LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                        WHERE (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                        AND payroll_assignment.PIC like '%".$userName."%'
                                        AND payroll_assignment.deleted = '0'");

            return $list->result();
        }

    }

    public function A_filter3($office,$department,$partner,$id,$staff){

        if($office == '0'){
            $office = '%%';
        }

        if($department == '0'){
            $department = '%%';
        }

        $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."'");

        $office_department = array();

        foreach($query->result() as $key => $row){
            $office_department[$key] = $row->name;
        }

        array_push($office_department, "TZE KARN KONG");

        if(json_encode($office_department) != '[]')
        {
            $office_department = json_encode($office_department);
            $office_department = str_replace(str_split('["]'), "" , $office_department);

            $office_department = "AND (payroll_assignment.PIC LIKE '%".$office_department;
            $office_department = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $office_department);
            $office_department = $office_department."%')";


            $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

            $userName = $q1->result();
            // $userName = json_encode($userName[0]->name);
            if(json_encode($userName[0]->name) == '"YEE TING GWEE"' || json_encode($userName[0]->name) == '"YI LING CHOO"') {
                $userName = "AND (payroll_assignment.PIC like '%YEE TING GWEE%' OR payroll_assignment.PIC like '%YI LING CHOO%') ";
            } else {
                $userName = "AND payroll_assignment.PIC like '%".json_encode($userName[0]->name)."%' ";
            }

            if($partner != "0" && $staff == "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC like '%".$partner."%'
                                            ".$userName." 
                                            ".$office_department."
                                            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }
            else if($partner == "0" && $staff != "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE (".$staff.")
                                            ".$userName."
                                            ".$office_department."
                                            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }
            else if($partner != "0" && $staff != "0"){
                $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE payroll_assignment.PIC like '%".$partner."%'
                                            AND (".$staff.")
                                            ".$userName."
                                            ".$office_department."
                                            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'");

                return $list->result();
            }
            else{
                 $list = $this->db->query(" SELECT payroll_assignment.*, firm.name,payroll_assignment_status.assignment_status,payroll_assignment_jobs.type_of_job AS job FROM payroll_assignment 
                                            LEFT JOIN firm on payroll_assignment.firm_id = firm.id 
                                            LEFT JOIN payroll_assignment_status on payroll_assignment.status = payroll_assignment_status.id 
                                            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
                                            WHERE (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10))
                                            AND payroll_assignment.deleted = '0'
                                            ".$userName."
                                            ".$office_department."");

                return $list->result();
            }

        }
        else
        {
            return NULL;
        }

    }
    
    public function get_final_year_end($client_id){

    	if($client_id != '0'){
    		$q = $this->db->query("SELECT * FROM filing WHERE company_code = '".$client_id."' ORDER BY year_end DESC LIMIT 1");

    		foreach($q->result() as $item){
            	$fye = $item->year_end; 
            	return $fye;
        	}
        }
        else{

        	$fye = '0';
        	return $fye;
        }
    }

    public function delete_assignment($assignment_id){

        $this->db->where('id', $assignment_id);

        $result = $this->db->update('payroll_assignment', array('deleted' => 1));

        return $result;
    }

    public function updt_status($status, $signed, $assignment_id, $type_of_job){

        $this->db->where('id', $assignment_id);

        if($signed != '0' && $signed == '1'){
            if($status == '10')
            {
                $result = $this->db->update('payroll_assignment', array('status' => $status, 'signed' => $signed, 'complete_date' => date("Y-m-d")));
            }
            else
            {
                $result = $this->db->update('payroll_assignment', array('status' => $status, 'signed' => $signed));
            }

        }
        else if($type_of_job == 'ROLL-OVER' && ($status == '15' || $status == '17') )
        {
            $result = $this->db->update('payroll_assignment', array('status' => $status, 'signed' => $signed, 'complete_date' => date("Y-m-d")));
        }
        else
        {
            $result = $this->db->update('payroll_assignment', array('status' => $status));
        }

        return $result;
    }

    public function check_signed_assignment($assignment_id){

        $q = $this->db->query("SELECT payroll_assignment_completed.*,payroll_assignment.type_of_job,firm.name FROM payroll_assignment_completed
                                LEFT JOIN firm on payroll_assignment_completed.firm_id = firm.id 
                                LEFT JOIN payroll_assignment ON payroll_assignment_completed.payroll_assignment_id = payroll_assignment.id
                                WHERE payroll_assignment_id = '".$assignment_id."'");
        
        // print_r($q);
        return $q->result();
    }

    public function check_expected_completion_date($assignment_id){

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE id = '".$assignment_id."' ");
        
        // print_r($q);
        return $q->result();
    }

    public function submit_expected_completion_date($assignment_id,$expected_completion_date){

        if($assignment_id != null)
        {
            $this->db->where('id', $assignment_id);

            $q = $this->db->update('payroll_assignment', array('expected_completion_date' => $expected_completion_date)); 
        }
        return $q;
    }

    public function submit_log($assignment_id,$data){

        $q = $this->db->insert('payroll_assignment_log', $data); 
        return $q;
    }

    public function check_assignment_deadline($date,$id){

        $query = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $query->result();
        $userName = json_encode($userName[0]->name);

        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.deleted = '0' AND payroll_assignment.expected_completion_date = '".$date."' AND payroll_assignment.PIC like '%".$userName."%' AND (payroll_assignment.status NOT IN (10,13,15,17) OR payroll_assignment.complete_date != null)");
        
        // print_r($q);
        return $q->result();
    }

    // public function check_assignment_remain_day($date){

    //     $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE expected_completion_date = '".$date."' AND deleted = '0'");
        
    //     return $q->result();
    // }

    // public function check_assignment_remain_day_email($id,$date){

    //     $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' AND payroll_assignment_log.date LIKE '".$date."%' AND payroll_assignment_log.assignment_log LIKE '%Email Notification Sent: Expected Completion Date is less than 3 days%' ");
    //     // print_r($q);
    //     return $q->result();
    // }

    public function check_missed_reason($id,$date){

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' AND payroll_assignment_log.date LIKE '".$date."%' AND payroll_assignment_log.assignment_log LIKE '%Missed Expected Completion Date%' ");
        // print_r($q);
        return $q->result();
    }

    public function show_log($id){

        $q = $this->db->query(" SELECT * FROM payroll_assignment_log WHERE payroll_assignment_log.assignment_id = '".$id."' ORDER BY payroll_assignment_log.date ");
        // print_r($q);
        return $q->result();
    }

    public function get_selected_assignment($id){

        $q = $this->db->query("SELECT * FROM payroll_assignment WHERE id = '".$id."'");
        
        return $q->result();
    }

    public function get_yes_no_list(){
        $list = $this->db->query("SELECT * FROM payroll_choose_carry_forward");

        $choose_carry_forward_list = array();
        $choose_carry_forward_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $choose_carry_forward_list[$item->id] = $item->choose_carry_forward_name;
        }

        return $choose_carry_forward_list;
    }

    public function get_partner_list(){
        $list = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();
        $partner_list['0'] = 'All';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->partner_name)] = strtoupper($item->partner_name);
        }

        return $partner_list;
    }

    public function get_partner_list2(){
        $list = $this->db->query("SELECT * FROM payroll_partner WHERE deleted = '0'");

        $partner_list = array();
        // $partner_list['0'] = 'Please Select';
        $partner_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $partner_list[strtoupper($item->partner_name)] = strtoupper($item->partner_name);
        }

        return $partner_list;
    }

    public function get_users_list($user_id){
        $list = $this->db->query(" SELECT *,CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users LEFT JOIN user_firm ON user_firm.user_id = users.id WHERE firm_id in (SELECT firm_id FROM user_firm WHERE user_id = '".$user_id."') AND users.user_deleted = '0' ORDER BY Name ASC");

        $users_list = array();
        $users_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $users_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_users_list2($user_id){
        // SHOW UOA STAFF ONLY
        if($user_id == 147){
            $list = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users 
            INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
            LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id
            WHERE users.user_deleted = '0' 
            AND payroll_employee.office IN (4)
            AND payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) 
            AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)) 
            ORDER BY Name ASC ");
        }
        else // SHOW ALL STAFF
        {
            $list = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id WHERE users.user_deleted = '0' AND payroll_employee.employee_status_id IN (1,2) OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE)) ORDER BY Name ASC ");
        }

        $users_list = array();

        foreach($list->result()as $item){
            $users_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_users_list3($user_id){
        $list = $this->db->query(" SELECT *,CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users LEFT JOIN user_firm ON user_firm.user_id = users.id WHERE firm_id in (SELECT firm_id FROM user_firm WHERE user_id = '".$user_id."') AND users.user_deleted = '0' ORDER BY Name ASC ");

        $users_list = array();
        $users_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $users_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function get_manager_list(){
        // QUERY WHICH INCULDE FELICIA AND RAY
        $list = $this->db->query("SELECT *,CONCAT(first_name , ' ' , last_name) as Name FROM users WHERE (group_id = '5' OR id = '107' OR id = '65') AND user_deleted = '0' ORDER BY Name ASC");

        $manager_list = array();
        $manager_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $manager_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $manager_list;
    }

    public function get_jobs_list(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_jobs");

        $jobs_list = array();
        // $jobs_list['0'] = 'Please Select';
        $jobs_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $jobs_list[$item->id] = $item->type_of_job;
        }

        return $jobs_list;
    }

    public function get_jobs_list2(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_jobs");

        $jobs_list = array();

        foreach($list->result()as $item){
            $jobs_list[$item->id] = $item->type_of_job;
        }

        return $jobs_list;
    }

    public function get_calender_leaveList(){
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE deleted = 0 ");

        return $q->result_array();
    }

    public function get_calender_leaveList2($id){
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");

        $userName = $q1->result();
        $userName = json_encode($userName[0]->name);
        
        $q = $this->db->query(" SELECT * FROM payroll_assignment WHERE payroll_assignment.PIC like '%".$userName."%' AND deleted = 0 ");

        return $q->result_array();
    }

    public function new_assignment_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code){    

        $to_list = array();
        $cc_list = array();

        $user_email="";

        for($i = 0; $i < sizeof($user_id); $i++){
            $user_email .= $user_id[$i][0]->email .",";

            if($i + 1 == sizeof($user_id)){
                $user_email .= $user_id[$i][0]->email;
            }else{ 
                $user_email .= $user_id[$i][0]->email .",";
            }
        }
        $to_email = $leader_info[0]->email .",". $user_email;
        $to_email = implode(',',array_unique(explode(',', $to_email)));
        $temp = explode(',', $to_email);
        for($d = 0 ; $d < sizeof($temp); $d++){
            array_push($to_list, array("email"=> $temp[$d]));
        }

        $q1 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager_id."' ");
        $query1 = $q1->result();

        $manager_email = array("email"=> $query1[0]->email);
        array_push($cc_list, $manager_email);

        if(json_encode($manager_email['email']) != '"penny@aaa-global.com"'){
            $manager_email = array("email"=> 'penny@aaa-global.com');
            array_push($cc_list, $manager_email);
        }

        $this->load->library('parser');
        $parse_data = array(
            'assignment_code'  => $assigment_code,
            'client_name'      => $client_name,
        );
        $msg        = file_get_contents('./application/modules/assignment/email_templates/new_assignment.html');
        $subject    = 'New Assignment - '.$client_name.'';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode($to_list);
        $cc         = json_encode($cc_list);
        $message    = $this->parser->parse_string($msg, $parse_data, true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }

    public function change_pic_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code){ 

        $to_list = array();
        $cc_list = array();

        if($user_id != "" && $leader_info == ""){

            $user_email="";

            for($i = 0 ; $i < sizeof($user_id); $i++){
                if($i + 1 == sizeof($user_id)){
                    $user_email .= $user_id[$i][0]->email;
                }else{ 
                    $user_email .= $user_id[$i][0]->email .",";
                }
            }

            $to_email = $user_email;

        }else if($user_id == "" && $leader_info != ""){

            $to_email = $leader_info[0]->email;

        }else if($user_id != "" && $leader_info != ""){

            $user_email="";

            for($i = 0; $i < sizeof($user_id); $i++){
                if($i + 1 == sizeof($user_id)){
                    $user_email .= $user_id[$i][0]->email;
                }else{ 
                    $user_email .= $user_id[$i][0]->email .",";
                }
            }

            $to_email = $leader_info[0]->email .",". $user_email;

        }

        $to_email = implode(',',array_unique(explode(',', $to_email)));
        $temp = explode(',', $to_email);
        for($d = 0 ; $d < sizeof($temp); $d++){
            array_push($to_list, array("email"=> $temp[$d]));
        }

        $q1 = $this->db->query(" SELECT * FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager_id."' ");
        $query1 = $q1->result();
        $manager_email = array("email"=> $query1[0]->email);
        array_push($cc_list, $manager_email);

        if(json_encode($manager_email['email']) != '"penny@aaa-global.com"'){
            $manager_email = array("email"=> 'penny@aaa-global.com');
            array_push($cc_list, $manager_email);
        }

        $this->load->library('parser');
        $parse_data = array(
            'assignment_code'  => $assigment_code,
            'client_name'      => $client_name,
        );
        $msg        = file_get_contents('./application/modules/assignment/email_templates/new_assignment.html');
        $subject    = 'New Assignment - '.$client_name.'';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode($to_list);
        $cc         = json_encode($cc_list);
        $message    = $this->parser->parse_string($msg, $parse_data,true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }

    // public function assignment_deadline_email($manager_id,$leader_info,$user_id,$client_name,$assigment_code)
    // {    
    //     $user_email="";
    //     $manager_email = $manager_id[0]->email;

    //     for($i = 0 ; $i < sizeof($user_id); $i++){
    //         $user_email .= $user_id[$i][0]->email .",";
    //     }

    //     $to_email = $leader_info[0]->email .",". $user_email;

    //     $to_email = implode(',',array_unique(explode(',', $to_email)));

    //     $this->load->library('parser');
    //     $parse_data = array(
    //         'assignment_code'  => $assigment_code,
    //         'client_name'      => $client_name,
    //     );

    //     if(json_encode($manager_email) != '"penny@aaa-global.com"'){
    //         $manager_email .= ',penny@aaa-global.com';
    //     }

    //     $msg = file_get_contents('./application/modules/assignment/email_templates/completion_date_notification.html');
    //     $message = $this->parser->parse_string($msg, $parse_data);

    //     $subject = 'Assignment Completion Date Notification - '.$client_name.'';
    //     $this->sma->send_email($to_email, $subject, $message,"" ,"" ,"" ,$manager_email);
    //     // send_email($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    // }

    public function ECD_log($assignment_id,$data){

        $q = $this->db->insert('payroll_assignment_ecd', $data); 
        return $q;
    }

    public function previous_status_log($data){

        $q = $this->db->insert('payroll_assignment_status_log', $data); 
        return $q;
    }

    public function previous_remark_log($data){

        $q = $this->db->insert('payroll_assignment_remark_log', $data); 
        return $q;
    }

    public function get_office(){
        $q = $this->db->query("SELECT * FROM payroll_offices WHERE id NOT IN (1) AND office_deleted = 0");

        $office['0'] = 'All Offices';

        foreach($q->result() as $row){
            $office[$row->id] = $row->office_name;
        }

        return $office;
    }

    public function get_department(){
        $q = $this->db->query("SELECT * FROM department WHERE id NOT IN (7) ORDER BY list_order");

        $department['0'] = 'All Departments';

        foreach($q->result() as $row){
            $department[$row->id] = $row->department_name;
        }

        return $department;
    }

    // public function calendar_office_department_filter($data){

    //     $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$data['office']."' AND payroll_employee.department LIKE '".$data['department']."'");

    //     $staff = array();

    //     foreach($query->result() as $key => $row){
    //         $staff[$key] = $row->name;
    //     }

    //     if(json_encode($staff) != '[]')
    //     {
    //         $staff = json_encode($staff);
    //         $staff = str_replace(str_split('["]'), "" , $staff);

    //         $staff = "(payroll_assignment.PIC LIKE '%".$staff;
    //         $staff = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $staff);
    //         $staff = $staff."%')";

    //         $query2 = $this->db->query("SELECT * FROM payroll_assignment WHERE deleted = 0 AND ".$staff."");

    //         return $query2->result_array();
    //     }
    //     else
    //     {
    //         return [];
    //     }

    // }

    // public function calendar_office_department_filter2($user_id, $data){

    //     $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$user_id."'");

    //     $userName = $q1->result();
    //     $userName = json_encode($userName[0]->name);

    //     $query = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_employee INNER JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id LEFT JOIN users ON users.id = payroll_user_employee.user_id WHERE payroll_employee.office LIKE '".$data['office']."' AND payroll_employee.department LIKE '".$data['department']."'");

    //     $staff = array();

    //     foreach($query->result() as $key => $row){
    //         $staff[$key] = $row->name;
    //     }

    //     if(json_encode($staff) != '[]')
    //     {
    //         $staff = json_encode($staff);
    //         $staff = str_replace(str_split('["]'), "" , $staff);

    //         $staff = "(payroll_assignment.PIC LIKE '%".$staff;
    //         $staff = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $staff);
    //         $staff = $staff."%')";

    //         $query2 = $this->db->query("SELECT * FROM payroll_assignment WHERE deleted = 0 AND payroll_assignment.PIC like '%".$userName."%' AND ".$staff."");

    //         return $query2->result_array();
    //     }
    //     else
    //     {
    //         return [];
    //     }
    // }

    // public function get_all_billings_invoice_no($company_code,$job_id)
    public function get_all_billings_invoice_no($company_code)
    {
        $this->db->select('billing.invoice_no, currency.currency as currency_name, billing_service.service, our_service_info.service_name, billing_service.id');
        $this->db->from('billing');
        $this->db->join('client', 'client.company_code = billing.company_code AND client.deleted = 0', 'left');
        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');
        $this->db->join('billing_service', 'billing_service.billing_id = billing.id', 'left');
        $this->db->join('client_billing_info', 'client_billing_info.id = billing_service.service', 'left');
        $this->db->join('our_service_info', 'our_service_info.id = client_billing_info.service', 'left');

        $this->db->where('billing.company_code', $company_code);
        $this->db->where('billing.status != 1');
        $this->db->order_by('billing.id', 'asc');

        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        else
        {
            return FALSE;
        }
    }

    public function get_assingment_billings_invoice_no($assignment_id)
    {
        $q = $this->db->query(" SELECT * FROM payroll_assignment_invoices WHERE assignment_id = '".$assignment_id."' ");

        return $q->result();
    }

    public function save_assignment_bill($assignment_id,$invoice_no){

        $delete_query = $this->db->query(" DELETE FROM payroll_assignment_invoices WHERE payroll_assignment_invoices.assignment_id = '".$assignment_id."' ");

        for($a=0; $a<count($invoice_no); $a++)
        {
            if($invoice_no[$a] != "")
            {
                $result = $this->db->insert('payroll_assignment_invoices', array('assignment_id' => $assignment_id, 'billing_service_id' => $invoice_no[$a]));
            } 
        }

        $bill_flag = $this->get_assingment_billings_invoice_no($assignment_id);

        if(count($bill_flag) > 0)
        {
            $this->db->where('assignment_id', $assignment_id);
            $this->db->update('payroll_assignment', array('invoice_flag' => 1));
        }
        else
        {
            $this->db->where('assignment_id', $assignment_id);
            $this->db->update('payroll_assignment', array('invoice_flag' => 0));
        }

        return $result;
    }

    public function show_remarkLog($id){

        $q = $this->db->query(" SELECT payroll_assignment_remark_log2.*, CONCAT(users.first_name , ' ' , users.last_name) AS name FROM payroll_assignment_remark_log2 LEFT JOIN users ON users.id = payroll_assignment_remark_log2.user_id WHERE payroll_assignment_remark_log2.assignment_id = '".$id."' ORDER BY payroll_assignment_remark_log2.date ");
        // print_r($q);
        return $q->result();
    }

    public function submit_remark_log($data){

        $q = $this->db->insert('payroll_assignment_remark_log2', $data); 
        return $q;
    }

    public function get_portfolio_partner_n_reviewer($assignment_client,$type_of_job){

        $query = $this->db->query('SELECT payroll_partner.partner_name AS partner, CONCAT(users.first_name , " " , users.last_name) AS reviewer FROM payroll_portfolio LEFT JOIN payroll_partner ON payroll_partner.id = payroll_portfolio.the_partner LEFT JOIN users ON users.id = payroll_portfolio.the_reviewer WHERE company_code = "'.$assignment_client.'" AND type_of_job = "'.$type_of_job.'"');

        return $query->result();
    }

    public function calendar_filter($staff,$jobStatus){

        if($staff != "")
        {
            $subQ_staff = json_encode($staff);
            $subQ_staff = str_replace(str_split('["]'), "" , $subQ_staff);

            $subQ_staff = "(payroll_assignment.PIC LIKE '%".$subQ_staff;
            $subQ_staff = str_replace("," , "%' OR payroll_assignment.PIC LIKE '%" , $subQ_staff);
            $subQ_staff = $subQ_staff."%') AND ";
        }
        else
        {
            $subQ_staff = "";
        }

        if($jobStatus != "")
        {
            $subQ_jobStatus = "payroll_assignment.status IN (";

            for($a=0; $a<count($jobStatus); $a++)
            {
                if($a == count($jobStatus)-1)
                {
                    $subQ_jobStatus .= $jobStatus[$a];
                }
                else
                {
                    $subQ_jobStatus .= $jobStatus[$a].",";
                }
            }

            $subQ_jobStatus .= ") AND ";
        }
        else
        {
            $subQ_jobStatus = "";
        }

        $query = $this->db->query(" SELECT * FROM payroll_assignment WHERE ".$subQ_staff."".$subQ_jobStatus." deleted = 0 ");

        return $query->result_array();
    }

    public function get_multi_jobStatus_list(){
        $list = $this->db->query("SELECT * FROM payroll_assignment_status ORDER BY list_order");

        $jobs_list = array();

        foreach($list->result()as $item){
            $jobs_list[$item->id] = $item->assignment_status;
        }

        return $jobs_list;
    }

    public function get_calendar_staff_filter($user_id = null){


        
        if($user_id) {
            $user = $this->db->query(" SELECT payroll_employee.office FROM users 
                                        INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
                                        LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                                        WHERE users.id = '".$user_id."' ");

            if($user->result()[0]->office == 4) {
                $list = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users 
                                            INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
                                            LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                                            WHERE payroll_employee.office IN (4) 
                                            AND payroll_employee.employee_status_id IN (1,2) 
                                            OR (payroll_employee.employee_status_id IN (3,4) 
                                            AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE))
                                            AND users.user_deleted = '0' ORDER BY Name ASC");
            } else {
                $list = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users 
                                            INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
                                            LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                                            WHERE users.id = '".$user_id."' ORDER BY Name ASC");
            }
        } else {
            $list = $this->db->query(" SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name FROM users 
                                        INNER JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id 
                                        LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id 
                                        WHERE payroll_employee.employee_status_id IN (1,2) 
                                        OR (payroll_employee.employee_status_id IN (3,4) AND DATE(payroll_employee.date_cessation) >= DATE(CURRENT_DATE))
                                        AND users.user_deleted = '0' ORDER BY Name ASC ");
        }

        $users_list = array();

        foreach($list->result()as $item){
            $users_list[strtoupper($item->Name)] = strtoupper($item->Name);
        }

        return $users_list;
    }

    public function submit_portfolio($client_id,$client_name,$job,$partner,$manager)
    {
        $partner_id = $this->db->query(" SELECT id FROM payroll_partner WHERE partner_name LIKE '".$partner."' ");
        $partner_id = $partner_id->result();

        $manager_id = $this->db->query(" SELECT id FROM users WHERE concat(first_name, ' ', last_name) LIKE '".$manager."' ");
        $manager_id = $manager_id->result();

        $query = $this->db->query('SELECT * FROM payroll_portfolio WHERE payroll_portfolio.company_code = "'.$client_id.'" AND payroll_portfolio.company_name = "'.$client_name.'" AND payroll_portfolio.type_of_job = "'.$job.'"');

        if($query->num_rows() > 0)
        {
            $query = $query->result();
               
            $this->db->where('id', $query[0]->id);
            $result = $this->db->update('payroll_portfolio', array('the_partner' => $partner_id[0]->id,'the_reviewer' => $manager_id[0]->id));
        }
    }

    public function MonthShifter (DateTime $aDate,$months){
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){ 
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }

    public function get_invoice_list(){
        $q = $this->db->query("
            SELECT 
            payroll_assignment.assignment_id, 
            payroll_assignment.client_id, 
            payroll_assignment.client_name, 
            firm.name, 
            payroll_assignment_jobs.type_of_job AS job,
            payroll_assignment_jobs.service_id AS job_service_id,
            payroll_assignment.PIC, 
            payroll_assignment.FYE
            FROM payroll_assignment 
            LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
            LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
            LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
            WHERE payroll_assignment.deleted = '0' 
            AND (payroll_assignment.type_of_job = '13' AND payroll_assignment.status NOT IN (10,15,17) OR payroll_assignment.type_of_job != '13' AND payroll_assignment.status NOT IN (10)) 
            AND payroll_assignment.invoice_closed = 0
            ORDER BY payroll_assignment.client_name ASC
        ");

        $first_query = $q->result();

        foreach ($first_query as $key => $value)
        {
            // GET PROPOSAL VALUE
            $value->proposal_value = 0;

            $each_job_service_id = explode(",", $value->job_service_id);

            if(count($each_job_service_id) == 1)
            {
                $q2 = $this->db->query("
                    SELECT * FROM client_billing_info WHERE company_code = '".$value->client_id."' AND service = '".$each_job_service_id[0]."'
                ");

                foreach ($q2->result() as $key2 => $value2)
                {
                    $value->proposal_value = $value2->amount;
                }
            }
            else
            {
                $value->proposal_value = 0;
            }

            $value->proposal_value = number_format(floatval($value->proposal_value),2,'.','');
            // END GET PROPOSAL VALUE

            // GET INVOICE VALUE
            $value->invoice_list = '';
            $value->invoice_value = 0;

            $q3 = $this->db->query("
                SELECT billing.invoice_no,our_service_info.service_name,billing_service.amount FROM payroll_assignment_invoices 
                LEFT JOIN billing_service ON billing_service.id = payroll_assignment_invoices.billing_service_id 
                LEFT JOIN billing ON billing.id = billing_service.billing_id
                LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                WHERE payroll_assignment_invoices.assignment_id = '".$value->assignment_id."'
            ");

            foreach ($q3->result() as $key3 => $value3)
            {
                $value->invoice_value += $value3->amount;

                if(($key3 + 1) == count($q3->result()))
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.')';
                }
                else
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.') , ';
                }
            }

            $value->invoice_value = number_format(floatval($value->invoice_value),2,'.','');
            // END GET INVOICE VALUE

            // REMOVE VALUE WHEN PROPOSAL < INVOICE
            if(floatval($value->proposal_value) < floatval($value->invoice_value))
            {
                array_splice($first_query,$key);
            }
        }
        // print_r($first_query);
        return $first_query;
    }

    public function sendEmailApproval($data = null)
    {
        $q = $this->db->query("
            SELECT 
            payroll_assignment.assignment_id, 
            payroll_assignment.client_id, 
            payroll_assignment.client_name, 
            firm.name, 
            payroll_assignment_jobs.type_of_job AS job,
            payroll_assignment_jobs.service_id AS job_service_id,
            payroll_assignment.PIC, 
            payroll_assignment.FYE
            FROM payroll_assignment 
            LEFT JOIN client ON payroll_assignment.client_id = client.company_code 
            LEFT JOIN firm ON payroll_assignment.firm_id = firm.id 
            LEFT JOIN payroll_assignment_status ON payroll_assignment.status = payroll_assignment_status.id 
            LEFT JOIN payroll_assignment_jobs ON payroll_assignment.type_of_job = payroll_assignment_jobs.id 
            WHERE payroll_assignment.assignment_id = '".$data['assignment_id']."'
        ");

        $result_query = $q->result();

        foreach ($result_query as $key => $value)
        {
            // GET PROPOSAL VALUE
            $value->proposal_value = 0;

            $each_job_service_id = explode(",", $value->job_service_id);

            if(count($each_job_service_id) == 1)
            {
                $q2 = $this->db->query("
                    SELECT * FROM client_billing_info WHERE company_code = '".$value->client_id."' AND service = '".$each_job_service_id[0]."'
                ");

                foreach ($q2->result() as $key2 => $value2)
                {
                    $value->proposal_value = $value2->amount;
                }
            }
            else
            {
                $value->proposal_value = 0;
            }

            $value->proposal_value = number_format(floatval($value->proposal_value),2,'.','');
            // END GET PROPOSAL VALUE

            // GET INVOICE VALUE
            $value->invoice_list = '';
            $value->invoice_value = 0;

            $q3 = $this->db->query("
                SELECT billing.invoice_no,our_service_info.service_name,billing_service.amount FROM payroll_assignment_invoices 
                LEFT JOIN billing_service ON billing_service.id = payroll_assignment_invoices.billing_service_id 
                LEFT JOIN billing ON billing.id = billing_service.billing_id
                LEFT JOIN client_billing_info ON client_billing_info.id = billing_service.service
                LEFT JOIN our_service_info ON our_service_info.id = client_billing_info.service
                WHERE payroll_assignment_invoices.assignment_id = '".$value->assignment_id."'
            ");

            foreach ($q3->result() as $key3 => $value3)
            {
                $value->invoice_value += $value3->amount;

                if(($key3 + 1) == count($q3->result()))
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.')';
                }
                else
                {
                    $value->invoice_list .= $value3->invoice_no.'('.$value3->service_name.') , ';
                }
            }

            $value->invoice_value = number_format(floatval($value->invoice_value),2,'.','');
            // END GET INVOICE VALUE

            // REMOVE VALUE WHEN PROPOSAL < INVOICE
            if(floatval($value->proposal_value) < floatval($value->invoice_value))
            {
                array_splice($result_query,$key);
            }
        }

        $assistant_html = '';
        foreach (json_decode($result_query[0]->PIC)->assistant as $key => $assistant) {
            $assistant_html .='
                <tr style="border: 1px solid black;">
                <th style="border: 1px solid black; height: 20px;">Assistant</th>
                <td style="border: 1px solid black; height: 20px;">'.strtoupper($assistant).'</td>
                </tr>
            ';
        }

        $pic = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Partner</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->partner).'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Manager</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->manager).'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Leader</th>
            <td style="border: 1px solid black; height: 20px;">'.strtoupper(json_decode($result_query[0]->PIC)->leader).'</td>
            </tr>
            '.$assistant_html.'
            </table>
        ';

        $invoice_html = '';
        $each_invoice_list = explode(",", $result_query[0]->invoice_list);
        foreach ($each_invoice_list as $key => $value) {
            if($value == '')
            {
                $invoice_html .= 'N/A';
            }
            else
            {
                $invoice_html .= '
                    <li>'.$value.'</li><br>
                ';
            }
        }

        $invoice = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Total Amount</th>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->invoice_value.'</td>
            </tr>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">Invoice Linked</th>
            <td style="border: 1px solid black; height: 20px;">'.$invoice_html.'</td>
            </tr>
            </table>
        ';

        $unbilled_invoice = floatval($result_query[0]->proposal_value) - floatval($result_query[0]->invoice_value);
        $unbilled_invoice = number_format(floatval($unbilled_invoice),2,'.','');

        $close_invoice_detail = '
            <table style="width: 100%; border: 1px solid black; border-collapse: collapse;">
            <thead>
            <tr style="border: 1px solid black;">
            <th style="border: 1px solid black; height: 20px;">No.</th>
            <th style="border: 1px solid black; height: 20px;">Clients</th>
            <th style="border: 1px solid black; height: 20px;">Firm</th>
            <th style="border: 1px solid black; height: 20px;">Job Type</th>
            <th style="border: 1px solid black; height: 20px;">PIC</th>
            <th style="border: 1px solid black; height: 20px;">FYE</th>
            <th style="border: 1px solid black; height: 20px;">Proposal Value</th>
            <th style="border: 1px solid black; height: 20px;">Invoices Value</th>
            <th style="border: 1px solid black; height: 20px;">Unbilled Invoices Value</th>
            </tr>
            </thead>
            <tbody>
            <tr style="border: 1px solid black;">
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->assignment_id.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->client_name.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->name.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->job.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$pic.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->FYE.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$result_query[0]->proposal_value.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$invoice.'</td>
            <td style="border: 1px solid black; height: 20px;">'.$unbilled_invoice.'</td>
            </tr>
            </tbody>
            </table>
        ';

        $q4 = $this->db->query("
            SELECT CONCAT(users.first_name , ' ' , users.last_name) as Name, users.email FROM users WHERE users.id = '".$data['user_id']."'
        ");
        $q4 = $q4->result();

        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
        // SENDINBLUE EMAIL
        $this->load->library('parser');
        $parse_data = array(
            '$close_invoice_detail' => $close_invoice_detail,
            '$user_name'            => $q4[0]->Name,
            '$approval_link'        => $protocol . $_SERVER['SERVER_NAME'] ."/hrm/ClosingInvoiceApproval/approval/".$data['assignment_id']."/".$data['user_id']."",
            '$reject_link'          => $protocol . $_SERVER['SERVER_NAME'] ."/hrm/ClosingInvoiceApproval/reject/".$data['assignment_id']."/".$data['user_id']."",
            '$approve_here_pic'     => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/img/approve_here.png",
            '$reject_here_pic'      => $protocol . $_SERVER['SERVER_NAME'] ."/secretary/img/reject_here.png"
        );
        $msg        = file_get_contents('./application/modules/assignment/email_templates/close_invoice_notification.html');
        $subject    = 'Close Invoicing Request';
        $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
        $to_email   = json_encode(array(array("email"=> "woellywilliam@aaa-global.com")));
        // $to_email   = json_encode(array(array("email"=> "jiawei@aaa-global.com")));
        $cc         = null;
        $message    = $this->parser->parse_string($msg, $parse_data,true);
        $this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null);
    }
}

?>