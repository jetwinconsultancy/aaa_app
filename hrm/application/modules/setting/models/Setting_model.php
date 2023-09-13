<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
//include 'application/js/random_alphanumeric_generator.php';

class Setting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
        
    }

    public function get_choose_carry_forward_list(){
        $list = $this->db->query("SELECT * FROM payroll_choose_carry_forward");

        $choose_carry_forward_list = array();
        $choose_carry_forward_list[''] = 'Please Select';

        foreach($list->result()as $item){
            $choose_carry_forward_list[$item->id] = $item->choose_carry_forward_name;
        }

        return $choose_carry_forward_list;
    }

    public function get_approval_cap_list()
    {
        $list = $this->db->query('SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap LEFT join department on payroll_approval_cap.department_id = department.id LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id WHERE deleted = 0');

        return $list->result();
    }

    public function get_holiday_list(){

        $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE deleted = 0 AND year(holiday_date)='.date("Y").' ORDER BY holiday_date');

        return $list->result();
    }

    public function get_partner_list(){

        $list = $this->db->query(' SELECT * FROM payroll_partner WHERE deleted = 0 ');

        return $list->result();
    }

    public function get_type_of_leave_list(){

        $list = $this->db->query('SELECT payroll_type_of_leave.*, payroll_choose_carry_forward.choose_carry_forward_name FROM payroll_type_of_leave LEFT JOIN payroll_choose_carry_forward ON payroll_choose_carry_forward.id = payroll_type_of_leave.choose_carry_forward_id WHERE deleted = 0 ORDER BY leave_name');

        return $list->result();
    }

    public function get_leave_cycle_list(){
        $list = $this->db->query('SELECT * FROM payroll_leave_cycle');

        return $list->result();
    }

    public function get_carry_forward_period_list(){
        $list = $this->db->query('SELECT * FROM payroll_carry_forward_period');

        return $list->result();
    }

    public function submit_type_of_leave($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_type_of_leave', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_type_of_leave', $data); 
        }
        return $result;
    }

    // public function submit_offices($data, $id){

    //     if($id != null)
    //     {
    //         $query = $this->db->query("SELECT * FROM payroll_offices WHERE office_name = '".$data['office_name']."' AND office_country = '".$data['office_country']."' AND office_deleted = 0");

    //         if($query->num_rows()){

    //             return false;
    //         }
    //         else{

    //             $this->db->where('id', $id);
    //             $result = $this->db->update('payroll_offices', $data);
    //             return true;
    //         }
    //     }
    //     else
    //     {
    //         $query = $this->db->query("SELECT * FROM payroll_offices WHERE office_name = '".$data['office_name']."' AND office_country = '".$data['office_country']."' AND office_deleted = 0");

    //         if($query->num_rows()){

    //             return false;
    //         }
    //         else{

    //             $result = $this->db->insert('payroll_offices', $data); 
    //             return true;
    //         }
    //     }
    // }

    // public function submit_department($data, $id){

    //     if($id != null)
    //     {
    //         $query = $this->db->query("SELECT * FROM department WHERE department_name = '".$data['department_name']."' AND department_deleted = 0");

    //         if($query->num_rows()){

    //             return false;
    //         }
    //         else{

    //             $this->db->where('id', $id);
    //             $result = $this->db->update('department', $data);
    //             return true;
    //         }
    //     }
    //     else
    //     {
    //         $query = $this->db->query("SELECT * FROM department WHERE department_name = '".$data['department_name']."' AND department_deleted = 0");

    //         if($query->num_rows()){

    //             return false;
    //         }
    //         else{

    //             $result = $this->db->insert('department', $data); 
    //             return true;
    //         }
    //     }
    // }

    public function delete_type_of_leave($type_of_leave_id){

        $this->db->where('id', $type_of_leave_id);

        $result = $this->db->update('payroll_type_of_leave', array('deleted' => 1));

        return $result;
    }

    public function submit_holiday($data, $id){

        $office = $this->db->query("SELECT id FROM payroll_offices WHERE office_deleted = 0");
        $offices_list = '';

        foreach ($office->result() as $result) {

            if($offices_list =='')
            {
                $offices_list .= $result->id;
            }
            else
            {
                $offices_list .= ','.$result->id;
            }
        }

        $department = $this->db->query("SELECT id FROM department");
        $department_list = '';
        foreach ($department->result() as $result) {

            if($department_list =='')
            {
                $department_list .= $result->id;
            }
            else
            {
                $department_list .= ','.$result->id;
            }
        }

        if($id != null)
        {
            $query = $this->db->query("SELECT * FROM payroll_block_holiday WHERE department_id = '".$data['department_id']."' AND holiday_date = '".$data['holiday_date']."' AND deleted = 0");

            if($query->num_rows()){

                return false;
            }
            else{

                $this->db->where('id', $id);
                $result = $this->db->update('payroll_block_holiday', $data);
                return true;
            }

        }
        else
        {
            if($data['offices_id'] == '1')
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_holiday WHERE offices_id in (".$offices_list.") AND department_id in (".$department_list.") AND holiday_date = '".$data['holiday_date']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($office->result() as $result) {
                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;

                                foreach ($department->result() as $result2) {

                                    if($result2->id != '7')
                                    {
                                        $data['department_id'] = $result2->id;
                                        $this->db->insert('payroll_block_holiday', $data);
                                    }
                                }
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_holiday WHERE offices_id in (".$offices_list.") AND department_id = '".$data['department_id']."' AND holiday_date = '".$data['holiday_date']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        foreach ($office->result() as $result) {

                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;
                                $this->db->insert('payroll_block_holiday', $data);
                            }
                        }
                        return true;
                    }
                }

            }
            else
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_holiday WHERE offices_id = '".$data['offices_id']."' AND department_id in (".$department_list.") AND holiday_date = '".$data['holiday_date']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($department->result() as $result2) {

                            if($result2->id != '7')
                            {
                                $data['department_id'] = $result2->id;
                                $this->db->insert('payroll_block_holiday', $data);
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_holiday WHERE offices_id = '".$data['offices_id']."' AND department_id = '".$data['department_id']."' AND holiday_date = '".$data['holiday_date']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        $result = $this->db->insert('payroll_block_holiday', $data);
                        return true;
                    }
                }
            }
        }

    }

    public function submit_partner($data, $search, $id){

        if($data['user_id'] != 0)
        {
            $checkLinked = $this->db->query("SELECT * FROM payroll_partner WHERE user_id = ".$data['user_id']."");

            if($checkLinked->num_rows())
            {
                return false;
            }
            else
            {
                if($id != null)
                {
                    $query = $this->db->query("SELECT * FROM payroll_partner WHERE partner_name LIKE '".$search."' AND user_id = ".$data['user_id']." AND deleted = 0");

                    if($query->num_rows()){

                        return false;
                    }
                    else{

                        $this->db->where('id', $id);

                        $result = $this->db->update('payroll_partner', $data);
                    }
                }
                else
                {
                    $query = $this->db->query("SELECT * FROM payroll_partner WHERE partner_name LIKE '".$search."' AND user_id = ".$data['user_id']." AND deleted = 0");

                    if($query->num_rows()){

                        return false;
                    }
                    else{

                        $result = $this->db->insert('payroll_partner', $data);
                    }
                }
            }

        }
        else
        {
            if($id != null)
            {
                $query = $this->db->query("SELECT * FROM payroll_partner WHERE partner_name LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $this->db->where('id', $id);

                    $result = $this->db->update('payroll_partner', $data);
                }
            }
            else
            {
                $query = $this->db->query("SELECT * FROM payroll_partner WHERE partner_name LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $result = $this->db->insert('payroll_partner', $data);
                }
            }
        }

        return $result;
    }

    public function delete_holiday($holiday_id){

        $this->db->where('id', $holiday_id);

        $result = $this->db->update('payroll_block_holiday', array('deleted' => 1));

        return $result;
    }

    public function delete_partner($partner_id){

        $this->db->where('id', $partner_id);

        $result = $this->db->update('payroll_partner', array('deleted' => 1));

        return $result;
    }

    public function delete_event($event_id){

        $this->db->where('id', $event_id);

        $result = $this->db->update('payroll_event_type', array('deleted' => 1));

        return $result;
    }

    public function delete_job($job_id){

        $this->db->where('id', $job_id);

        $result = $this->db->update('payroll_assignment_jobs', array('deleted' => 1));

        return $result;
    }

    public function delete_office($id){

        $this->db->where('id', $id);

        $result = $this->db->update('payroll_offices', array('office_deleted' => 1));

        return $result;
    }

    // public function delete_department($id){

    //     $this->db->where('id', $id);

    //     $result = $this->db->update('department', array('department_deleted' => 1));

    //     return $result;
    // }

    public function delete_institution($institution_id){

        $this->db->where('id', $institution_id);

        $result = $this->db->update('payroll_institution', array('deleted' => 1));

        return $result;
    }

    public function submit_approval_cap($data, $id){

        $office = $this->db->query("SELECT id FROM payroll_offices WHERE office_deleted = 0");
        $offices_list = '';

        foreach ($office->result() as $result) {

            if($offices_list =='')
            {
                $offices_list .= $result->id;
            }
            else
            {
                $offices_list .= ','.$result->id;
            }
        }

        $department = $this->db->query("SELECT id FROM department");
        $department_list = '';
        foreach ($department->result() as $result) {

            if($department_list =='')
            {
                $department_list .= $result->id;
            }
            else
            {
                $department_list .= ','.$result->id;
            }
        }

        if($id != null)
        {
            $query = $this->db->query("SELECT * FROM payroll_approval_cap WHERE offices_id = '".$data['offices_id']."' AND department_id = '".$data['department_id']."' AND (( approval_cap_date_from BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_to BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_from <= '".$data['approval_cap_date_from']."' AND approval_cap_date_to >= '".$data['approval_cap_date_to']."')) AND number_of_employee = '".$data['number_of_employee']."' AND deleted = 0");

            if($query->num_rows()){

                return false;
            }
            else{

                $this->db->where('id', $id);
                $result = $this->db->update('payroll_approval_cap', $data);
                return true;
            }

        }
        else
        {
            if($data['offices_id'] == '1')
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_approval_cap WHERE offices_id in (".$offices_list.") AND department_id in (".$department_list.") AND (( approval_cap_date_from BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_to BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_from <= '".$data['approval_cap_date_from']."' AND approval_cap_date_to >= '".$data['approval_cap_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($office->result() as $result) {
                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;

                                foreach ($department->result() as $result2) {

                                    if($result2->id != '7')
                                    {
                                        $data['department_id'] = $result2->id;
                                        $this->db->insert('payroll_approval_cap', $data);
                                    }
                                }
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_approval_cap WHERE offices_id in (".$offices_list.") AND department_id = '".$data['department_id']."' AND (( approval_cap_date_from BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_to BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_from <= '".$data['approval_cap_date_from']."' AND approval_cap_date_to >= '".$data['approval_cap_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        foreach ($office->result() as $result) {

                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;
                                $this->db->insert('payroll_approval_cap', $data);
                            }
                        }
                        return true;
                    }
                }

            }
            else
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_approval_cap WHERE offices_id = '".$data['offices_id']."' AND department_id in (".$department_list.") AND (( approval_cap_date_from BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_to BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_from <= '".$data['approval_cap_date_from']."' AND approval_cap_date_to >= '".$data['approval_cap_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($department->result() as $result2) {

                            if($result2->id != '7')
                            {
                                $data['department_id'] = $result2->id;
                                $this->db->insert('payroll_approval_cap', $data);
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_approval_cap WHERE offices_id = '".$data['offices_id']."' AND department_id = '".$data['department_id']."' AND (( approval_cap_date_from BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_to BETWEEN '".$data['approval_cap_date_from']."'AND '".$data['approval_cap_date_to']."') OR (approval_cap_date_from <= '".$data['approval_cap_date_from']."' AND approval_cap_date_to >= '".$data['approval_cap_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        $result = $this->db->insert('payroll_approval_cap', $data);
                        return true;
                    }
                }
            }
        }
    }

    public function submit_leave_cycle($data, $id){

        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_leave_cycle', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_leave_cycle', $data); 
        }

        return $result;
    }

    public function submit_carry_forward_period($data, $id){
        if($id != null)
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_carry_forward_period', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_carry_forward_period', $data); 
        }

        return $result;
    }

    public function submit_block_leave($data, $id){

        $office = $this->db->query("SELECT id FROM payroll_offices WHERE office_deleted = 0");
        $offices_list = '';

        foreach ($office->result() as $result) {

            if($offices_list =='')
            {
                $offices_list .= $result->id;
            }
            else
            {
                $offices_list .= ','.$result->id;
            }
        }

        $department = $this->db->query("SELECT id FROM department");
        $department_list = '';
        foreach ($department->result() as $result) {

            if($department_list =='')
            {
                $department_list .= $result->id;
            }
            else
            {
                $department_list .= ','.$result->id;
            }
        }

        if($id != null)
        {
            $query = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id = '".$data['offices_id']."' AND department_id = '".$data['department_id']."' AND (( block_leave_date_from BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_to BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_from <= '".$data['block_leave_date_from']."' AND block_leave_date_to >= '".$data['block_leave_date_to']."')) AND deleted = 0");

            if($query->num_rows()){

                return false;
            }
            else{

                $this->db->where('id', $id);
                $result = $this->db->update('payroll_block_leave', $data);
                return true;
            }

        }
        else
        {
            if($data['offices_id'] == '1')
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id in (".$offices_list.") AND department_id in (".$department_list.") AND (( block_leave_date_from BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_to BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_from <= '".$data['block_leave_date_from']."' AND block_leave_date_to >= '".$data['block_leave_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($office->result() as $result) {
                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;

                                foreach ($department->result() as $result2) {

                                    if($result2->id != '7')
                                    {
                                        $data['department_id'] = $result2->id;
                                        $this->db->insert('payroll_block_leave', $data);
                                    }
                                }
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id in (".$offices_list.") AND department_id = '".$data['department_id']."' AND (( block_leave_date_from BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_to BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_from <= '".$data['block_leave_date_from']."' AND block_leave_date_to >= '".$data['block_leave_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        foreach ($office->result() as $result) {

                            if($result->id != '1')
                            {
                                $data['offices_id'] = $result->id;
                                $this->db->insert('payroll_block_leave', $data);
                            }
                        }
                        return true;
                    }
                }

            }
            else
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id = '".$data['offices_id']."' AND department_id in (".$department_list.") AND (( block_leave_date_from BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_to BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_from <= '".$data['block_leave_date_from']."' AND block_leave_date_to >= '".$data['block_leave_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($department->result() as $result2) {

                            if($result2->id != '7')
                            {
                                $data['department_id'] = $result2->id;
                                $this->db->insert('payroll_block_leave', $data);
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_block_leave WHERE offices_id = '".$data['offices_id']."' AND department_id = '".$data['department_id']."' AND (( block_leave_date_from BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_to BETWEEN '".$data['block_leave_date_from']."'AND '".$data['block_leave_date_to']."') OR (block_leave_date_from <= '".$data['block_leave_date_from']."' AND block_leave_date_to >= '".$data['block_leave_date_to']."')) AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        $result = $this->db->insert('payroll_block_leave', $data);
                        return true;
                    }
                }
            }
        }
        
    }//jw

    public function delete_block_leave($block_leave_id){

        $this->db->where('id', $block_leave_id);

        $result = $this->db->update('payroll_block_leave', array('deleted' => 1));

        return $result;
    }

    public function delete_approval_cap($approval_cap_id){

        $this->db->where('id', $approval_cap_id);

        $result = $this->db->update('payroll_approval_cap', array('deleted' => 1));

        return $result;
    }

    public function delete_charge_out_rate($charge_out_rate_id){

        $this->db->where('id', $charge_out_rate_id);

        $result = $this->db->update('payroll_charge_out_rate', array('deleted' => 1));

        return $result;
    }

    public function get_year_list(){

        $list = $this->db->query('SELECT Year(holiday_date) as holiday_date_year FROM payroll_block_holiday group by Year(holiday_date)');

        $year_list = array();
        $year_list[0] = 'Year Filter';

        foreach($list->result() as $item){
            $year_list[$item->holiday_date_year] = $item->holiday_date_year; 
        }

        return $year_list;

    }//JW

    // public function get_holiday_year_list($department,$year){

    //     if($year != 0 && $department == 0){
    //         $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE deleted = 0 AND year(holiday_date)='. $year . ' ORDER BY holiday_date');
    //     }else if($year != 0 && $department != 0){
    //          $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE payroll_block_holiday.department_id = '.$department.' AND year(holiday_date)='. $year . ' AND deleted = 0 ORDER BY holiday_date');
    //     }
    //     else{
    //         $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE deleted = 0 AND year(holiday_date)='. date("Y") . ' ORDER BY holiday_date');
    //     }

    //     return $list->result();
    // }//JW

    public function get_holiday_filter($office,$department,$year){

        if($office != 0 && $department == 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE payroll_block_holiday.offices_id = '.$office.' 
                                        AND year(payroll_block_holiday.holiday_date)='. date("Y").'
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else if($office != 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE payroll_block_holiday.offices_id = '.$office.' 
                                        AND payroll_block_holiday.department_id = '.$department.'
                                        AND year(payroll_block_holiday.holiday_date)='. date("Y").'
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else if($office == 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE payroll_block_holiday.department_id = '.$department.'
                                        AND year(payroll_block_holiday.holiday_date)='. date("Y").'
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else if($office == 0 && $department != 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE payroll_block_holiday.department_id = '.$department.'
                                        AND year(payroll_block_holiday.holiday_date)='. $year.'
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else if($office == 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE year(payroll_block_holiday.holiday_date)='. $year.'
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else if($office != 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE year(payroll_block_holiday.holiday_date)='. $year.'
                                        AND payroll_block_holiday.offices_id = '.$office.' 
                                        AND deleted = 0 ORDER BY holiday_date');
        }
        else
        {
            $list = $this->db->query('  SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday 
                                        LEFT JOIN department on payroll_block_holiday.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id 
                                        WHERE year(payroll_block_holiday.holiday_date)='. $year.'
                                        AND payroll_block_holiday.offices_id = '.$office.' 
                                        AND payroll_block_holiday.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY holiday_date');
        }

        return $list->result();

    }//JW

    public function get_cap_filter($office,$department,$year){

        if($office != 0 && $department == 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='.date("Y").' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='.date("Y").')
                                        AND payroll_approval_cap.offices_id = '.$office.' 
                                        AND deleted = 0 ORDER BY approval_cap_date_from');
        }
        else if($office != 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='.date("Y").' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='.date("Y").')
                                        AND payroll_approval_cap.offices_id = '.$office.' 
                                        AND payroll_approval_cap.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY approval_cap_date_from');
        }
        else if($office == 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='.date("Y").' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='.date("Y").')
                                        AND payroll_approval_cap.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY approval_cap_date_from');
        }
        else if($office == 0 && $department != 0 && $year != 0)
        {
            $list = $this->db->query('  SSELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='. $year.' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='. $year.')
                                        AND payroll_approval_cap.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY approval_cap_date_from');
        }
        else if($office == 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='. $year.' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='. $year.')
                                        AND deleted = 0 ORDER BY approval_cap_date_from');
        }
        else if($office != 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='. $year.' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='. $year.')
                                        AND payroll_approval_cap.offices_id = '.$office.' 
                                        AND deleted = 0 ORDER BY approval_cap_date_from ');
        }
        else
        {
            $list = $this->db->query('  SELECT payroll_approval_cap.*, department.department_name, payroll_offices.office_name FROM payroll_approval_cap 
                                        LEFT JOIN department on payroll_approval_cap.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_approval_cap.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_approval_cap.approval_cap_date_from)='. $year.' 
                                        OR year(payroll_approval_cap.approval_cap_date_to)='. $year.')
                                        AND payroll_approval_cap.offices_id = '.$office.' 
                                        AND payroll_approval_cap.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY approval_cap_date_from ');
        }

        return $list->result();

    }//JW


    public function get_block_leave_filter($office,$department,$year){

        if($office != 0 && $department == 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='.date("Y").' 
                                        OR year(payroll_block_leave.block_leave_date_to)='.date("Y").')
                                        AND payroll_block_leave.offices_id = '.$office.'
                                        AND deleted = 0 ORDER BY block_leave_date_from');
        }
        else if($office != 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='.date("Y").' 
                                        OR year(payroll_block_leave.block_leave_date_to)='.date("Y").')
                                        AND payroll_block_leave.offices_id = '.$office.' 
                                        AND payroll_block_leave.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY block_leave_date_from');
        }
        else if($office == 0 && $department != 0 && $year == 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='.date("Y").' 
                                        OR year(payroll_block_leave.block_leave_date_to)='.date("Y").')
                                        AND payroll_block_leave.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY block_leave_date_from');

        }
        else if($office == 0 && $department != 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='. $year.' 
                                        OR year(payroll_block_leave.block_leave_date_to)='. $year.') 
                                        AND payroll_block_leave.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY block_leave_date_from');
        }
        else if($office == 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='. $year.' 
                                        OR year(payroll_block_leave.block_leave_date_to)='. $year.')
                                        AND deleted = 0 ORDER BY block_leave_date_from');
        }
        else if($office != 0 && $department == 0 && $year != 0)
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='. $year.' 
                                        OR year(payroll_block_leave.block_leave_date_to)='. $year.')
                                        AND payroll_block_leave.offices_id = '.$office.'
                                        AND deleted = 0 ORDER BY block_leave_date_from ');
        }
        else
        {
            $list = $this->db->query('  SELECT payroll_block_leave.*, department.department_name, payroll_offices.office_name FROM payroll_block_leave 
                                        LEFT JOIN department on payroll_block_leave.department_id = department.id 
                                        LEFT JOIN payroll_offices on payroll_block_leave.offices_id = payroll_offices.id 
                                        WHERE (year(payroll_block_leave.block_leave_date_from)='. $year.' 
                                        OR year(payroll_block_leave.block_leave_date_to)='. $year.')
                                        AND payroll_block_leave.offices_id = '.$office.' 
                                        AND payroll_block_leave.department_id = '.$department.'
                                        AND deleted = 0 ORDER BY block_leave_date_from ');
        }

        return $list->result();

    }//JW


    // public function get_holiday_department_list($department,$year){

    //     if($department != 0 && $year == 0){
    //         $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE payroll_block_holiday.department_id = '.$department.' AND deleted = 0 ORDER BY holiday_date');
    //     }
    //     else if($department != 0 && $year != 0){
    //          $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE payroll_block_holiday.department_id = '.$department.' AND year(holiday_date)='. $year . ' AND deleted = 0 ORDER BY holiday_date');
    //     }
    //     else{
    //         $list = $this->db->query('SELECT payroll_block_holiday.*, department.department_name, payroll_offices.office_name FROM payroll_block_holiday LEFT JOIN department on payroll_block_holiday.department_id = department.id LEFT JOIN payroll_offices on payroll_block_holiday.offices_id = payroll_offices.id WHERE deleted = 0 AND year(holiday_date)='. date("Y") . ' ORDER BY holiday_date');
    //     }

    //     return $list->result();
    // }//JW

    public function get_department_list(){

        $list = $this->db->query('SELECT * FROM department ORDER BY list_order ASC');

        $department_list = array();
        $department_list[''] = 'Please Select Department';

        foreach($list->result() as $item){
            $department_list[$item->id] = $item->department_name; 
        }

        return $department_list;

    }//JW

    public function get_office_list(){

        $list = $this->db->query('SELECT * FROM payroll_offices WHERE office_deleted = 0');

        $office_list = array();
        $office_list[''] = 'Please Select Office';

        foreach($list->result() as $item){
            $office_list[$item->id] = $item->office_name; 
        }

        return $office_list;

    }//JW

    public function get_department_filter(){

        $list = $this->db->query('SELECT payroll_block_holiday.*, department.* FROM payroll_block_holiday INNER JOIN department ON payroll_block_holiday.department_id = department.id group by department.department_name ORDER BY list_order');

        $department_list = array();
        $department_list[0] = 'Department Filter';

        foreach($list->result() as $item)
        {
            $department_list[$item->id] = $item->department_name; 
        }

        return $department_list;

    }//JW

    public function get_offices_filter(){

        $list = $this->db->query('SELECT payroll_block_holiday.*, payroll_offices.* FROM payroll_block_holiday LEFT JOIN payroll_offices ON payroll_block_holiday.offices_id = payroll_offices.id group by payroll_offices.office_name');

        $offices_list = array();
        $offices_list[0] = 'Offices Filter';

        foreach($list->result() as $item)
        {
            $offices_list[$item->id] = $item->office_name; 
        }

        return $offices_list;

    }//JW

    public function get_event_list(){

        $list = $this->db->query(' SELECT * FROM payroll_event_type WHERE deleted = 0 ');

        return $list->result();
    }

    public function get_job_list(){

        $list = $this->db->query(' SELECT * FROM payroll_assignment_jobs WHERE deleted = 0 ');

        return $list->result();
    }

    public function get_institution_list(){

        $list = $this->db->query(' SELECT * FROM payroll_institution WHERE deleted = 0 ');

        return $list->result();
    }

    public function submit_event($data, $search, $id){

        if($id != null)
        {

            $query = $this->db->query("SELECT * FROM payroll_event_type WHERE event LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $this->db->where('id', $id);

                    $result = $this->db->update('payroll_event_type', $data);
                }
        }
        else
        {
            $query = $this->db->query("SELECT * FROM payroll_event_type WHERE event LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $result = $this->db->insert('payroll_event_type', $data);
                }
        }

        return $result;
    }

    public function submit_job($data, $search, $id){

        if($id != null)
        {

            $query = $this->db->query("SELECT * FROM payroll_assignment_jobs WHERE type_of_job LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $this->db->where('id', $id);

                    $result = $this->db->update('payroll_assignment_jobs', $data);
                }
        }
        else
        {
            $query = $this->db->query("SELECT * FROM payroll_assignment_jobs WHERE type_of_job LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $result = $this->db->insert('payroll_assignment_jobs', $data);
                }
        }

        return $result;
    }

    public function submit_institution($data, $search, $id){

        if($id != null)
        {

            $query = $this->db->query("SELECT * FROM payroll_institution WHERE institution_name LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $this->db->where('id', $id);

                    $result = $this->db->update('payroll_institution', $data);
                }
        }
        else
        {
            $query = $this->db->query("SELECT * FROM payroll_institution WHERE institution_name LIKE '".$search."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $result = $this->db->insert('payroll_institution', $data);
                }
        }

        return $result;
    }

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function get_departments(){

        $list = $this->db->query(' SELECT * FROM department ORDER BY list_order ASC ');

        return $list->result();
    }

    public function get_offices(){

        $list = $this->db->query(' SELECT * FROM payroll_offices WHERE office_deleted = 0');

        return $list->result();
    }
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function get_approvalCap_year_filter(){

        $list = $this->db->query('SELECT Year(approval_cap_date_from) as approval_cap_year FROM payroll_approval_cap group by Year(approval_cap_date_from)');

        $year_list = array();
        $year_list[0] = 'Year Filter';

        foreach($list->result() as $item){
            $year_list[$item->approval_cap_year] = $item->approval_cap_year; 
        }

        return $year_list;

    }//JW

    public function get_approvalCap_offices_filter(){

        $list = $this->db->query('SELECT payroll_approval_cap.*, payroll_offices.* FROM payroll_approval_cap INNER JOIN payroll_offices ON payroll_approval_cap.offices_id = payroll_offices.id group by payroll_offices.office_name');

        $offices_list = array();
        $offices_list[0] = 'Offices Filter';

        foreach($list->result() as $item)
        {
            $offices_list[$item->id] = $item->office_name; 
        }

        return $offices_list;

    }//JW

    public function get_approvalCap_department_filter(){

        $list = $this->db->query('SELECT payroll_approval_cap.*, department.* FROM payroll_approval_cap INNER JOIN department ON payroll_approval_cap.department_id = department.id group by department.department_name ORDER BY list_order');

        $department_list = array();
        $department_list[0] = 'Department Filter';

        foreach($list->result() as $item)
        {
            $department_list[$item->id] = $item->department_name; 
        }

        return $department_list;

    }//JW
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------

// -----------------------------------------------------------------------------------------------------------------------------------------------------------------
    public function get_block_leave_year_filter(){

        $list = $this->db->query('SELECT Year(block_leave_date_from) as block_leave_year FROM payroll_block_leave group by Year(block_leave_date_from)');

        $year_list = array();
        $year_list[0] = 'Year Filter';

        foreach($list->result() as $item){
            $year_list[$item->block_leave_year] = $item->block_leave_year; 
        }

        return $year_list;

    }//JW

    public function get_block_leave_offices_filter(){

        $list = $this->db->query('SELECT payroll_block_leave.*, payroll_offices.* FROM payroll_block_leave INNER JOIN payroll_offices ON payroll_block_leave.offices_id = payroll_offices.id group by payroll_offices.office_name');

        $offices_list = array();
        $offices_list[0] = 'Offices Filter';

        foreach($list->result() as $item)
        {
            $offices_list[$item->id] = $item->office_name; 
        }

        return $offices_list;

    }//JW

    public function get_block_leave_department_filter(){

        $list = $this->db->query('SELECT payroll_block_leave.*, department.* FROM payroll_block_leave INNER JOIN department ON payroll_block_leave.department_id = department.id group by department.department_name ORDER BY list_order');

        $department_list = array();
        $department_list[0] = 'Department Filter';

        foreach($list->result() as $item)
        {
            $department_list[$item->id] = $item->department_name; 
        }

        return $department_list;

    }//JW
// -----------------------------------------------------------------------------------------------------------------------------------------------------------------

    public function get_country_name(){

        $list = $this->db->query('SELECT * FROM fs_country');

        $department_list = array();
        $department_list[''] = 'Please Select the Country';

        foreach($list->result() as $item)
        {
            $department_list[$item->name] = $item->name; 
        }

        return $department_list;

    }//JW

    public function get_designation($department){

        $list = $this->db->query(' SELECT * FROM payroll_designation WHERE department_id LIKE "'.$department.'" GROUP BY designation ORDER BY sorting');
        return $list->result();

    }//JW

    public function submit_charge_out_rate($data,$id){

        $office = $this->db->query("SELECT id FROM payroll_offices WHERE office_deleted = 0");
        $offices_list = '';

        foreach ($office->result() as $result) {

            if($offices_list =='')
            {
                $offices_list .= $result->id;
            }
            else
            {
                $offices_list .= ','.$result->id;
            }
        }

        $department = $this->db->query("SELECT id FROM department");
        $department_list = '';

        foreach ($department->result() as $result) {

            if($department_list =='')
            {
                $department_list .= $result->id;
            }
            else
            {
                $department_list .= ','.$result->id;
            }
        }

        if($id != null)
        {
            $query = $this->db->query("SELECT * FROM payroll_charge_out_rate WHERE office_id = '".$data['office_id']."' AND department_id = '".$data['department_id']."' AND designation_id = '".$data['designation_id']."' AND rate = '".$data['rate']."' AND deleted = 0");

            if($query->num_rows()){

                return false;
            }
            else{

                $this->db->where('id', $id);
                $result = $this->db->update('payroll_charge_out_rate', $data);
                return true;
            }

        }
        else
        {
            if($data['office_id'] == '1')
            {
                if($data['department_id'] == '7')
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_charge_out_rate WHERE office_id in (".$offices_list.") AND department_id in (".$department_list.") AND designation_id = '".$data['designation_id']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {

                        foreach ($office->result() as $result) {
                            if($result->id != '1')
                            {
                                $data['office_id'] = $result->id;

                                foreach ($department->result() as $result2) {

                                    if($result2->id != '7')
                                    {
                                        $data['department_id'] = $result2->id;
                                        $this->db->insert('payroll_charge_out_rate', $data);
                                    }
                                }
                            }
                        }
                        return true;
                    }
                }
                else
                {
                    $query1 = $this->db->query("SELECT * FROM payroll_charge_out_rate WHERE office_id in (".$offices_list.") AND department_id = '".$data['department_id']."' AND designation_id = '".$data['designation_id']."' AND deleted = 0");

                    if($query1->num_rows())
                    {
                        return false;
                    }
                    else
                    {
                        foreach ($office->result() as $result) {

                            if($result->id != '1')
                            {
                                $data['office_id'] = $result->id;
                                $this->db->insert('payroll_charge_out_rate', $data);
                            }
                        }
                        return true;
                    }
                }

            }
            else
            {
                $query = $this->db->query("SELECT * FROM payroll_charge_out_rate WHERE office_id = '".$data['office_id']."' AND department_id = '".$data['department_id']."' AND designation_id = '".$data['designation_id']."' AND deleted = 0");

                if($query->num_rows()){

                    return false;
                }
                else{

                    $result = $this->db->insert('payroll_charge_out_rate', $data);
                    return true;
                }
            }
        }

    }//JW

    public function get_charge_out_rate(){

        $list = $this->db->query('SELECT payroll_charge_out_rate.*, payroll_offices.office_name, department.department_name, payroll_designation.designation FROM payroll_charge_out_rate LEFT JOIN payroll_offices ON payroll_offices.id = payroll_charge_out_rate.office_id LEFT JOIN department ON payroll_charge_out_rate.department_id = department.id LEFT JOIN payroll_designation ON payroll_charge_out_rate.designation_id = payroll_designation.id WHERE payroll_charge_out_rate.deleted = 0');

        return $list->result();

    }//JW

    public function get_block_leave_list(){

        $list = $this->db->query('SELECT payroll_block_leave.*, department.department_name FROM payroll_block_leave LEFT join department on payroll_block_leave.department_id = department.id WHERE deleted = 0 AND year(block_leave_date_from)='. date("Y") .' ORDER BY block_leave_date_from');

        return $list->result();
    }

    public function get_team(){

        $list = $this->db->query(' SELECT * FROM payroll_employee WHERE employee_status_id NOT IN (3,4) ');

        return $list->result();
    }

    public function get_team_filter($team){

        $list = $this->db->query(' SELECT * FROM payroll_employee WHERE employee_status_id NOT IN (3,4) AND team_shift LIKE "'.$team.'" ');

        return $list->result();
    }

    public function update_team($id,$data){

        $this->db->where('id', $id);
        $result = $this->db->update('payroll_employee', $data);
        return $result;
    }

    public function get_partner_email(){

        $list = $this->db->query('SELECT users.id, users.email FROM users
        LEFT JOIN payroll_user_employee ON payroll_user_employee.user_id = users.id
        LEFT JOIN payroll_employee ON payroll_employee.id = payroll_user_employee.employee_id
        WHERE (payroll_employee.designation = "PARTNER" OR users.group_id IN (2)) AND users.user_deleted = 0 AND users.active = 1');

        $partner = array();
        $partner[0] = 'None';

        foreach($list->result() as $item){
            $partner[$item->id] = $item->email; 
        }

        return $partner;

    }//JW

    public function get_jurisdiction_info($user_admin_code_id)
    {
        $q = $this->db->query("select gst_jurisdiction.* from gst_jurisdiction where user_admin_code_id = '".$user_admin_code_id."' AND deleted = 0 ORDER BY id");

        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $key => $row) {
                $data[$row->id] = $row->jurisdiction;
            }
            return $data;
        }
        return FALSE;
    } //CPF

    public function get_salary_cap(){
        $q = $this->db->query("SELECT * FROM payroll_cpf_salary_cap ORDER BY cap_start_date DESC");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    } //CPF

    public function update_salary_cap($id, $data){

        $this->db->where('id', $id);

        $result = $this->db->update('payroll_cpf_salary_cap', $data);

        return $result;
    } //CPF

    public function insert_salary_cap($data)
    {
        $result = $this->db->insert('payroll_cpf_salary_cap', $data); 

        return $result;
    } //CPF

    public function get_age_group_period(){
        $q = $this->db->query("SELECT * FROM payroll_cpf_age_group_period ORDER BY period_start_date DESC");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    } //CPF

    public function get_age_group($period_id){
        $q = $this->db->query("SELECT * FROM payroll_cpf_age_group WHERE age_group_period_id='".$period_id."' and deleted=0 ORDER BY age_years ASC");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            $no_record_flag = true;
            $q = $this->db->query("SELECT * from payroll_cpf_age_group_period where period_end_date < (select period_start_date from payroll_cpf_age_group_period where id = '".$period_id."') ORDER BY period_start_date DESC") ;
            if ($q->num_rows() > 0) {
                $prev_records = $q->result_array();
                foreach ($prev_records as $record)
                {
                    $q = $this->db->query("SELECT * FROM payroll_cpf_age_group WHERE age_group_period_id='".$record['id']."' and deleted=0 ORDER BY age_years ASC");
                    if ($q->num_rows() > 0) {
                        $temp_arr = $q->result_array();
                        foreach ($temp_arr as $x => $val)
                        {
                            unset($temp_arr[$x]["id"]);
                        }
                        return $temp_arr;
                        $no_record_flag = false;
                    }
                }
                if($no_record_flag)
                {
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
    } //CPF

    public function update_age_group_period($id, $data){

        $this->db->where('id', $id);

        $result = $this->db->update('payroll_cpf_age_group_period', $data);

        return $id;
    } //CPF

    public function insert_age_group_period($data)
    {
        $result = $this->db->insert('payroll_cpf_age_group_period', $data); 
        
        $id = $this->db->insert_id();
        return $id;
    } //CPF

    public function get_nationality_period(){
        $q = $this->db->query("SELECT * FROM payroll_cpf_nationality_period ORDER BY period_start_date DESC");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            return FALSE;
        }
    } //CPF

    public function insert_age_group($data, $id=null)
    {
        if($id != null && $id != "")
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_cpf_age_group', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_cpf_age_group', $data); 
        }
        return $result;
    }

    public function update_nationality_period($id, $data){

        $this->db->where('id', $id);

        $result = $this->db->update('payroll_cpf_nationality_period', $data);

        return $id;
    } //CPF

    public function insert_nationality_period($data)
    {
        $result = $this->db->insert('payroll_cpf_nationality_period', $data); 
        
        $id = $this->db->insert_id();
        return $id;
    } //CPF

    public function get_nationality($period_id){
        $q = $this->db->query("SELECT * FROM payroll_cpf_nationality_group WHERE nationality_period_id='".$period_id."' ORDER BY nationality_type DESC");

        if ($q->num_rows() > 0) {
            return $q->result_array();
        }else{
            $no_record_flag = true;
            $q = $this->db->query("SELECT * from payroll_cpf_nationality_period where period_end_date < (select period_start_date from payroll_cpf_nationality_period where id = '".$period_id."') ORDER BY period_start_date DESC") ;
            if ($q->num_rows() > 0) {
                $prev_records = $q->result_array();
                foreach ($prev_records as $record)
                {
                    $q = $this->db->query("SELECT * FROM payroll_cpf_nationality_group WHERE nationality_period_id='".$record['id']."' ORDER BY nationality_type DESC");
                    if ($q->num_rows() > 0) {
                        $temp_arr = $q->result_array();
                        foreach ($temp_arr as $x => $val)
                        {
                            unset($temp_arr[$x]["id"]);
                        }
                        return $temp_arr;
                        $no_record_flag = false;
                    }
                }
                if($no_record_flag)
                {
                    return FALSE;
                }
            }
            else
            {
                return FALSE;
            }
        }
    } //CPF

    public function get_nationality_type(){
        $q = $this->db->query("SELECT * FROM payroll_cpf_nationality_type");
        $types[''] = "Select Type";
        
        

        if ($q->num_rows() > 0) {
            foreach($q->result() as $type){
                $types[$type->id] = $type->nationality_type; 
            }
            return $types;
        }else{
            return FALSE;
        }
    } //CPF

    public function insert_nationality($data, $id=null)
    {
        if($id != null && $id != "")
        {
            $this->db->where('id', $id);

            $result = $this->db->update('payroll_cpf_nationality_group', $data);
        }
        else
        {
            $result = $this->db->insert('payroll_cpf_nationality_group', $data); 
        }
        return $result;
    }
        
}
?>