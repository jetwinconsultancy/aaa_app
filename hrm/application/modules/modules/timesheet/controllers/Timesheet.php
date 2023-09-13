<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Timesheet extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library(array('session','parser'));
        $this->load->library(array('zip'));
        $this->load->helper("file");
        $this->load->helper(array('form', 'url'));
        $this->load->model('holiday_model');
        $this->load->model('timesheet_model');
        $this->load->model('employee/employee_model');
        $this->load->model('employment_json_model');
        $this->load->library(array('encryption'));

        if(!$this->data['Admin'] && !$this->data['Manager'])
        {
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }
        else if($this->user_id == 91 || $this->user_id == 84 || $this->user_id == 107 || $this->data['Manager'])
        {
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'Timesheet';
    }

    public function index()
    {   
        $bc   = array(array('link' => '#', 'page' => 'Timesheet'));
        $meta = array('page_title' => 'Timesheet', 'bc' => $bc, 'page_name' => 'Timesheet');

        if(!$this->data['Admin'] && !$this->data['Manager'])
        {
            $this->data['timesheet_list'] = $this->timesheet_model->get_employee_timesheet($this->employee_id);

            foreach($this->data['timesheet_list'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this_month_date_days = $this->set_arrayObj_date_days(date('Y-m-d', strtotime(date('Y-m')." -1 month")));
            $header_setting = $this->setup_header_setting($this_month_date_days,$this->employee_id);
            // set header and if column is readonly
            $this->data['header'] = $header_setting[0];
            $this->data['array_header_col_readonly'] = $header_setting[1];
            // $this->data['holiday'] = $this->timesheet_model->is_holiday();
            $this->data['third_working_date'] = $this->timesheet_model->get_third_working_date();
            $this->data['this_month_leave'] = $this->timesheet_model->get_this_month_leave();
            $this->data['emp_id'] = $this->employee_id;


            $this->page_construct('index.php', $meta, $this->data);
        }
        else if($this->user_id == 91 || $this->user_id == 84 || $this->user_id == 107 || $this->data['Manager'])
        {
            $this->data['timesheet_list1'] = $this->timesheet_model->get_employee_timesheet($this->employee_id);
            foreach($this->data['timesheet_list1'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this->data['timesheet_list2'] = $this->timesheet_model->get_all_timesheet();
            foreach($this->data['timesheet_list2'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this->data['years']    = $this->timesheet_model->get_years();
            $this->data['month']    = $this->timesheet_model->get_month();
            $this->data['status']   = $this->timesheet_model->get_status();
            $this->data['employee'] = $this->timesheet_model->get_employee();
            $this->data['office']     = $this->timesheet_model->get_office();
            $this->data['department'] = $this->timesheet_model->get_department();

            $this->page_construct('index_manager.php', $meta, $this->data);
        }
        else
        {

            $this->data['timesheet_list'] = $this->timesheet_model->get_all_timesheet();
            foreach($this->data['timesheet_list'] as $row){
                $row->status_id = $this->employment_json_model->get_timesheet_action_name($row->status_id);
            }

            $this->data['years']      = $this->timesheet_model->get_years();
            $this->data['month']      = $this->timesheet_model->get_month();
            $this->data['status']     = $this->timesheet_model->get_status();
            $this->data['employee']   = $this->timesheet_model->get_employee();
            $this->data['office']     = $this->timesheet_model->get_office();
            $this->data['department'] = $this->timesheet_model->get_department();

            $this_month_date_days = $this->set_arrayObj_date_days(date('Y-m-d', strtotime(date('Y-m')." -1 month")));
            $header_setting = $this->setup_header_setting_admin($this_month_date_days);
            // set header and if column is readonly
            $this->data['header'] = $header_setting[0];
            $this->data['array_header_col_readonly'] = $header_setting[1];
            // $this->data['holiday'] = $this->timesheet_model->is_holiday();
            $this->data['third_working_date'] = $this->timesheet_model->get_third_working_date();
            $this->data['this_month_leave'] = $this->timesheet_model->get_this_month_leave();


            $this->page_construct('index_admin.php', $meta, $this->data);
        }
    }

    public function timesheet_tr_partial(){
        $form_data = $this->input->post();
        $this->data['timesheet'] = $form_data['data'];
        
        $this->data['timesheet']['status_id'] = $this->employment_json_model->get_timesheet_action_name($this->data['timesheet']['status_id']);

        $this->load->view('timesheet_tr_partial', $this->data);
        // echo json_encode($this->data['timesheet']);
    }

    public function create(){
        $bc   = array(array('link' => '#', 'page' => 'Timesheet'));
        $meta = array('page_title' => 'Timesheet', 'bc' => $bc, 'page_name' => 'Timesheet');

        $this->data['employee_id']    = $this->employee_id;
        $this->data['timesheet_list'] = $this->timesheet_model->get_employee_timesheet2($this->employee_id);

        $this->page_construct('create.php', $meta, $this->data);
    }

    public function edit($timesheet_id){

        $bc   = array(array('link' => '#', 'page' => 'Timesheet'));
        $meta = array('page_title' => 'Timesheet', 'bc' => $bc, 'page_name' => 'Timesheet');
        
        $this->data['timesheet'] = $this->timesheet_model->get_timesheet($timesheet_id);

        $this->data['timesheet_status_name'] = $this->employment_json_model->get_timesheet_action_name($this->data['timesheet'][0]->status_id);


        if($this->session->has_userdata('got_assignment_selected') && $this->data['Manager'])
        {
            $this->data['assignment'] = $this->timesheet_model->get_assignment($timesheet_id);
            $this->data['user_selected_assignment'] = json_decode($this->session->userdata('assignment_selected'));
        }
        else if($this->data['Admin'])
        {
            $this->data['assignment'] = $this->timesheet_model->get_assignment($timesheet_id);
            $user_selected_assignment = $this->timesheet_model->get_assignment($timesheet_id);
            $timesheet = $this->timesheet_model->get_timesheet($timesheet_id);
            $timesheet_content = json_decode($timesheet[0]->content);
            $list_array = array();

            for($a=0 ; $a<count(array($timesheet_content)) ; $a++)
            {
                for($b=0 ; $b<count($user_selected_assignment) ; $b++)
                {
                    $date = date("d M Y", strtotime($user_selected_assignment[$b]->FYE));

                    if($timesheet_content[$a][0] == $user_selected_assignment[$b]->client_name && $timesheet_content[$a][1] == $user_selected_assignment[$b]->job  && $timesheet_content[$a][2] == strtoupper($date))
                    {
                        if(strpos($timesheet_content[$a][0], '*') !== false)
                        {
                           array_push($list_array,$user_selected_assignment[$b]->assignment_id);
                        }
                    }
                }
            }

            $this->data['user_selected_assignment'] = $list_array;

        }
        else
        {
            $this->data['assignment'] = $this->timesheet_model->get_assignment($timesheet_id);
            $user_selected_assignment = $this->timesheet_model->get_assignment($timesheet_id);
            $list_array = array();

            for($a=0 ; $a<count($user_selected_assignment) ; $a++)
            {
                array_push($list_array,$user_selected_assignment[$a]->assignment_id);
            }

            $this->data['user_selected_assignment'] = $list_array;
        }

        $date = $this->data['timesheet'][0]->month;
        $this_month_date_days = $this->set_arrayObj_date_days($date);
        $header_setting = $this->setup_header_setting($this_month_date_days,$this->data['timesheet'][0]->employee_id);
        // set header and if column is readonly
        $this->data['header'] = $header_setting[0];
        $this->data['array_header_col_readonly'] = $header_setting[1];

        $this->data['bf_timesheet'] = $this->timesheet_model->get_bf_timesheet($this->data['timesheet'][0]->employee_id,$date);
        $this->data['leave_details'] = $this->timesheet_model->get_leave_details($this->data['timesheet'][0]->employee_id,$date);

        $this->page_construct('edit.php', $meta, $this->data);
    }

    public function edit1()
    {   
        $newdata = array(
            'assignment_selected' => '',
            'got_assignment_selected' => 'false',
        );

        $this->session->unset_userdata($newdata);

        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];
        $assignment = $form_data['assignment'];

        $newdata = array(
                'assignment_selected' => $assignment,
                'got_assignment_selected' => 'true',
        );

        $this->session->set_userdata($newdata);

        echo true;
    }

    public function create_timesheet(){
        $form_data = $this->input->post();

        $data = array(
            'employee_id' => $form_data['employee_id'],
            'month'       => date('Y-m-d', strtotime('01 ' . $form_data['timesheet_month'])),
            'status_id'   => 1
        );

        $result = $this->timesheet_model->create_timesheet($data);

        echo $result;
    }

    public function get_month(){
        $form_data = $this->input->post();

        $year = $form_data['year'];

        $months = $this->timesheet_model->get_months_from_this_year($year);

        echo json_encode($months);
    }

    public function get_holiday(){
        $form_data = $this->input->post();

        $emp_id = $form_data['emp_id'];

        $result = $this->timesheet_model->get_holiday($emp_id);

        echo json_encode($result);
    }

    public function get_holiday2(){
        $form_data = $this->input->post();

        $emp_id = $form_data['emp_id'];
        $month  = $form_data['month'];

        $result = $this->timesheet_model->get_holiday2($emp_id,$month);

        echo json_encode($result);
    }

    public function get_list_from_year_month(){
        $form_data = $this->input->post();

        $year  = $form_data['year'];
        $month = $form_data['month'];

        $list = $this->timesheet_model->get_list_from_year_month($year, $month);

        echo json_encode($list);
    }

    // FUNCTION FOR SET 6 & 7 & PH TO READONLY
    public function setup_header_setting($this_month_date_days,$emp_id = null){
        // setup header
        $header = array("Activities");
        array_push($header,"Type of Job");
        array_push($header,"FYE");
        $array_header_col_readonly = array();
        array_push($array_header_col_readonly, 
            array("data" => "Activities", "className" => "act"),
            array("data" => "Type of Job", "className" => "act"),
            array("data" => "FYE" , "className" => "act")
        );

        $i = 1;
        foreach($this_month_date_days as $row){
            $is_public_holiday = $this->holiday_model->is_public_holiday($row['date'],$emp_id);

            array_push($header, (string)$i);

            if($row[$i] == 6 || $row[$i] == 7 || $is_public_holiday){
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i,
                    "className" => "WeekEnd htRight"

                ));
            }
            else{
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i,
                    "className" => "htRight"
                ));
            }

            $i++;
        }

        array_push($array_header_col_readonly, 
            array("data" => "current", "className" => "tt htRight", "readOnly" => true),
            array("data" => "b/f",     "className" => "tt htRight"),
            array("data" => "total",   "className" => "tt htRight", "readOnly" => true)
        );
        array_push($header, "current", "b/f", "total");

        return [$header, $array_header_col_readonly];
    }

    public function setup_header_setting_admin($this_month_date_days){
        // setup header
        $header = array("Activities");
        array_push($header,"Type of Job");
        array_push($header,"FYE");
        $array_header_col_readonly = array(array("data" => "Activities"));
        array_push($array_header_col_readonly, 
            array("data" => "Type of Job"),
            array("data" => "FYE")
        );

        $i = 1;
        foreach($this_month_date_days as $row){
            $is_public_holiday = $this->holiday_model->is_public_holiday_admin($row['date']);

            array_push($header, (string)$i);

            if($row[$i] == 6 || $row[$i] == 7 || $is_public_holiday){
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i,
                    "className" => "WeekEnd htRight"

                ));
            }
            else{
                array_push($array_header_col_readonly, array(
                    "data"      => (string)$i,
                    "className" => "htRight"
                ));
            }

            $i++;
        }

        array_push($array_header_col_readonly, 
            array("data" => "current", "className" => "tt htRight", "readOnly" => true),
            array("data" => "b/f",     "className" => "tt htRight", "readOnly" => true),
            array("data" => "total",   "className" => "tt htRight", "readOnly" => true)
        );
        array_push($header, "current", "b/f", "total");

        return [$header, $array_header_col_readonly];
    }

    public function set_arrayObj_date_days($date){
        $this_month_date_days  = array();
        $month = date('m', strtotime($date));
        $year  = date('Y', strtotime($date));

        $days_in_month = date("t", strtotime($date));  // get total no. of days in this month

        // get dates and days in this month
        for($d=1; $d<=$days_in_month; $d++)
        {
            $time = mktime(12, 0, 0, $month, $d, $year);

            if (date('m', $time) == $month){
                array_push($this_month_date_days, 
                    array(
                        (int)(date('d', $time)) => date('N', $time),
                        'date'                  => $year . "-" . $month . "-" . date('d', $time)
                    )
                );
            }
        }

        return $this_month_date_days;
    }

    public function save_timesheet(){
        $form_data = $this->input->post();

        $timesheet_id = $form_data['timesheet_id'];

        $data = array(
            'content' => json_encode($form_data['data'])
        );

        $result = $this->timesheet_model->edit_timesheet($data, $timesheet_id);

        echo $result;
    }

    public function submit_timesheet(){
        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];

        $data = array(
            "status_id" => 2
        );

        $result = $this->timesheet_model->edit_timesheet($data, $timesheet_id);

        echo $result;
    }

    public function approve_timesheet(){
        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];

        $data = array(
            "status_id" => 3
        );

        $result = $this->timesheet_model->edit_timesheet($data, $timesheet_id);

        echo $result;
    }

    public function timesheet_filter(){
        $form_data = $this->input->post();

        $office     = $form_data['office'];
        $department = $form_data['department'];
        $employee   = $form_data['employee'];
        $month      = $form_data['month'];
        $year       = $form_data['year'];
        $status     = $form_data['status'];

        if($office == 0){
            $office = '%%';
        }

        if($department == 0){
            $department = '%%';
        }

        if($employee == 0){
            $employee = "timesheet.employee_id LIKE '%%'";
        } else {
            $employee = "timesheet.employee_id LIKE '".$employee;
            $employee = str_replace("," , "' OR timesheet.employee_id LIKE '" , $employee);
            $employee = $employee."'";
        }

        if($status == 0){
            $status = '%%';
        }

        $result = $this->timesheet_model->timesheet_filter($office, $department, $employee , $month , $year , $status);
        echo json_encode($result);
    }
    
    public function record_filter(){
        $form_data = $this->input->post();

        if($form_data['result'] == 'true')
        {
            $record = true;
        }
        else
        {
            $record = false;
        }
        
        $result = $this->timesheet_model->record_filter($record, $this->employee_id);
        echo json_encode($result);
    }

    public function record_filter_admin(){
        $form_data = $this->input->post();

        if($form_data['result'] == 'true')
        {
            $record = true;
        }
        else
        {
            $record = false;
        }
        
        $result = $this->timesheet_model->record_filter_admin($record);
        echo json_encode($result);
    }

    // public function Submition_Notification(){
    //     $this->timesheet_model->Submition_Notification();
    // }

    public function timesheet_submition_check(){
        $result = $this->timesheet_model->timesheet_submition_check();
        echo json_encode($result);
    }

    public function Timesheet_Submition(){
        $form_data = $this->input->post();

        $value = $form_data['timesheet_list'];
        $id = $value['id'];
        $data = array(
            'status_id' => '2',
            'content'   =>  json_encode($value['content'])
        );

        $this->db->where('id', $id);
        $result = $this->db->update('timesheet', $data);

        echo $result;
    }

    public function Check_Timesheet(){
        $form_data = $this->input->post();

        $this_month_tiemsheet = $this->timesheet_model->Check_Timesheet($form_data['emp_id']);

        if($this_month_tiemsheet == true){
            $data = array(
                'employee_id' => $form_data['emp_id'],
                'month'       => date('Y-m-d', strtotime('01 ' . date('M Y'))),
                'status_id'   => 1
            );

            $result = $this->timesheet_model->create_timesheet($data);

            echo $result;
        }
    }

    public function select_assignment(){
        $form_data = $this->input->post();

        $timesheet_id = $form_data['timesheet_id'];

        $result = $this->timesheet_model->get_assignment($timesheet_id);

        echo json_encode($result);
    }

    public function get_timesheet(){
        $form_data = $this->input->post();

        $timesheet_id = $form_data['timesheet_id'];

        $result = $this->data['timesheet'] = $this->timesheet_model->get_timesheet($timesheet_id);;

        echo json_encode($result);
    }

    public function check_assignment_status()
    {
        $form_data = $this->input->post();

        $assignment_list = $form_data['assignment_list'];

        $result = $this->data['timesheet'] = $this->timesheet_model->check_assignment_status($assignment_list);;

        echo json_encode($result);
    }

    public function stocktake_assignment_list()
    {
        $form_data = $this->input->post();

        $timesheet_id = $form_data['timesheet_id'];

        $result = $this->timesheet_model->stocktake_assignment_list($timesheet_id);;

        echo json_encode($result);
    }

    public function generate_multiple_PDF(){
        $form_data = $this->input->post();
        $office     = $form_data['office'];
        $department = $form_data['department'];
        $employee   = $form_data['employee'];
        $month      = $form_data['month'];
        $year       = $form_data['year'];
        $status     = $form_data['status'];

        if($office == 0){
            $office = '%%';
        }
        if($department == 0){
            $department = '%%';
        }
        if($employee == 0){
            $employee = "timesheet.employee_id LIKE '%%'";
        } else {
            $employee = "timesheet.employee_id LIKE '".$employee;
            $employee = str_replace("," , "' OR timesheet.employee_id LIKE '" , $employee);
            $employee = $employee."'";
        }
        if($status == 0){
            $status = '%%';
        }

        $query1 = $this->db->query("SELECT timesheet.id AS id FROM timesheet LEFT JOIN payroll_employee ON timesheet.employee_id = payroll_employee.id WHERE payroll_employee.office LIKE '".$office."' AND payroll_employee.department LIKE '".$department."' AND (".$employee.") AND month(timesheet.month) = '".$month."' AND  year(timesheet.month) = '".$year."' AND timesheet.status_id LIKE '".$status."'");

        foreach($query1->result() as $row)
        {
            $query2 = $this->db->query('SELECT * FROM timesheet LEFT JOIN payroll_employee ON payroll_employee.id = timesheet.employee_id WHERE timesheet.id = "'.$row->id.'"');
            $timesheet = $query2->result();
            $timesheet_content = json_decode($timesheet[0]->content);
            $days_this_month = count($timesheet_content[0]) - 6;
            $timesheet_month = $timesheet[0]->month;
            $timesheet_employee = $timesheet[0]->name;
            $timesheet_status_name = $this->employment_json_model->get_timesheet_action_name($timesheet[0]->status_id);


            $array_link = [];
            $content = '';
            $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $obj_pdf->SetCreator(PDF_CREATOR);
            $title  = "Timesheet (".$timesheet_month.") - ".$timesheet_employee."";
            $obj_pdf->SetTitle($title);
            $obj_pdf->SetDefaultMonospacedFont('helvetica');
            $obj_pdf->SetFont('helvetica', '', 10);
            $obj_pdf->setFontSubsetting(false);
            $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='',$tc=array(0,0,0), $lc=array(0,0,0));

            $obj_pdf->AddPage('L', 'A4');
            $content = '<table style="border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td style="width:50px">Name</td>
                                    <td style="width:10px">:</td>
                                    <td style="width:150px">'.$timesheet_employee.'</td>
                                </tr>
                                <tr>
                                    <td>Month</td>
                                    <td>:</td>
                                    <td>'.date('M Y', strtotime($timesheet[0]->month)).'</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">Status</td>
                                    <td>:</td>
                                    <td>'.$timesheet_status_name.'</td>
                                </tr>
                            </tbody>
                        </table>
                        <p style="LINE-HEIGHT:1px;">&nbsp;</p>';

            $content .= '<table style="border-collapse: collapse;" border="1">
                            <tbody>
                                <tr>
                                    <td style="width:75px !important; text-align: center"><span style="font-size: 7pt;"><strong>Activities</strong></span></td>
                                    <td style="width:60px !important; text-align: center"><span style="font-size: 7pt;"><strong>Type of Job</strong></span></td>
                                    <td style="width:50px !important; text-align: center"><span style="font-size: 7pt;"><strong>FYE</strong></span></td>
                        ';

            for($a = 1; $a <= $days_this_month; $a++)
            {
                $content .= '<td style="width:17px !important; text-align: center"><span style="font-size: 6pt;">'.$a.'</span></td>';
            }

            $content .= '   <td style="width:30px !important; text-align: center"><span style="font-size: 7pt;"><strong>current</strong></span></td>
                            <td style="width:25px !important; text-align: center"><span style="font-size: 7pt;"><strong>b/f</strong></span></td>
                            <td style="width:25px !important; text-align: center"><span style="font-size: 7pt;"><strong>total</strong></span></td>
                            </tr>
                        ';

            for($a = 0; $a < count($timesheet_content); $a++)
            {
                $content .= '<tr>';
                for($b = 0; $b < count($timesheet_content[$a]); $b++)
                {
                    if($b === 0) {
                        $width = "75";
                        $align = "left";
                    } else if($b === 1) {
                        $width = "60";
                        $align = "left";
                    } else if($b === 2) {
                        $width = "50";
                        $align = "left";
                    }
                    else if($b === count($timesheet_content[$a])-1) {
                        $width = "25";
                        $align = "center";
                    }
                    else if($b === count($timesheet_content[$a])-2) {
                        $width = "25";
                        $align = "center";
                    }
                    else if($b === count($timesheet_content[$a])-3) {
                        $width = "30";
                        $align = "center";
                    } else {
                        $width = "17";
                        $align = "center";
                    }

                    $content .= '<td style="width:'.$width.'px !important; text-align: '.$align.'">
                                    <span style="font-size: 6pt;">'.$timesheet_content[$a][$b].'</span>
                                </td>';
                }
                $content .= '</tr>';
            }

            $content .= '</tbody></table>';

            $obj_pdf->writeHTML($content, true, false, false, false, '');

            $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf', 'F');

            chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf',0644);

            $this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'/hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf');
        }

        $this->zip->archive($_SERVER['DOCUMENT_ROOT'].'/hrm/pdf/document/Timesheet_PDF.zip');

        // $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'https://';
        $protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';

        $array_link = [];

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Timesheet_PDF.zip');

        echo json_encode(array("link" => $array_link, "filename" => "Timesheet_PDF.zip")); //
    }

    public function generate_PDF()
    {
        $form_data = $this->input->post();
        $timesheet_id = $form_data['timesheet_id'];

        $query = $this->db->query('SELECT * FROM timesheet LEFT JOIN payroll_employee ON payroll_employee.id = timesheet.employee_id WHERE timesheet.id = "'.$timesheet_id.'"');
        $timesheet = $query->result();
        $timesheet_content = json_decode($timesheet[0]->content);
        $days_this_month = count($timesheet_content[0]) - 6;
        $timesheet_month = $timesheet[0]->month;
        $timesheet_employee = $timesheet[0]->name;
        $timesheet_status_name = $this->employment_json_model->get_timesheet_action_name($timesheet[0]->status_id);


        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Timesheet (".$timesheet_month.") - ".$timesheet_employee."";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='',$tc=array(0,0,0), $lc=array(0,0,0));

        $obj_pdf->AddPage('L', 'A4');
        $content = '<table style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="width:50px">Name</td>
                                <td style="width:10px">:</td>
                                <td style="width:150px">'.$timesheet_employee.'</td>
                            </tr>
                            <tr>
                                <td>Month</td>
                                <td>:</td>
                                <td>'.date('M Y', strtotime($timesheet[0]->month)).'</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;">Status</td>
                                <td>:</td>
                                <td>'.$timesheet_status_name.'</td>
                            </tr>
                        </tbody>
                    </table>
                    <p style="LINE-HEIGHT:1px;">&nbsp;</p>';

        $content .= '<table style="border-collapse: collapse;" border="1">
                        <tbody>
                            <tr>
                                <td style="width:75px !important; text-align: center"><span style="font-size: 7pt;"><strong>Activities</strong></span></td>
                                <td style="width:60px !important; text-align: center"><span style="font-size: 7pt;"><strong>Type of Job</strong></span></td>
                                <td style="width:50px !important; text-align: center"><span style="font-size: 7pt;"><strong>FYE</strong></span></td>
                    ';

        for($a = 1; $a <= $days_this_month; $a++)
        {
            $content .= '<td style="width:17px !important; text-align: center"><span style="font-size: 6pt;">'.$a.'</span></td>';
        }

        $content .= '   <td style="width:30px !important; text-align: center"><span style="font-size: 7pt;"><strong>current</strong></span></td>
                        <td style="width:25px !important; text-align: center"><span style="font-size: 7pt;"><strong>b/f</strong></span></td>
                        <td style="width:25px !important; text-align: center"><span style="font-size: 7pt;"><strong>total</strong></span></td>
                        </tr>
                    ';

        for($a = 0; $a < count($timesheet_content); $a++)
        {
            $content .= '<tr>';
            for($b = 0; $b < count($timesheet_content[$a]); $b++)
            {
                if($b === 0) {
                    $width = "75";
                    $align = "left";
                } else if($b === 1) {
                    $width = "60";
                    $align = "left";
                } else if($b === 2) {
                    $width = "50";
                    $align = "left";
                }
                else if($b === count($timesheet_content[$a])-1) {
                    $width = "25";
                    $align = "center";
                }
                else if($b === count($timesheet_content[$a])-2) {
                    $width = "25";
                    $align = "center";
                }
                else if($b === count($timesheet_content[$a])-3) {
                    $width = "30";
                    $align = "center";
                } else {
                    $width = "17";
                    $align = "center";
                }

                $content .= '<td style="width:'.$width.'px !important; text-align: '.$align.'">
                                <span style="font-size: 6pt;">'.$timesheet_content[$a][$b].'</span>
                            </td>';
            }
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'https://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Timesheet ('.$timesheet_month.') - '.$timesheet_employee.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Timesheet (".$timesheet_month.") - ".$timesheet_employee.".pdf"));
    }
}

class MYPDF extends TCPDF {

    protected $last_page_flag = false;
    protected $total_page = 1;
    protected $one_page_only = false;

    public function Close() {
        $this->last_page_flag = true;

        if($this->total_page == 1){
            $this->one_page_only = true;
        }

        parent::Close();
    }

    public function Header() {
        $headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 23);
        $this->writeHTML($headerData['string']);
    }

    public function Footer() {
        $this->SetY(-18);
        $this->Ln();
    }
}
