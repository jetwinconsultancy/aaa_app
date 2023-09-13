<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Budget_Hours extends MX_Controller {

	function __construct() {
        parent::__construct();

        $this->load->model('Budget_hours_model');

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
    }

	public function index()
	{
		$bc = array(array('link' => '#', 'page' => 'Budget_hours'));
        $meta = array('page_title' => 'Budget Hours', 'bc' => $bc, 'page_name' => 'Budget Hours');

        if($this->data['Admin'] || $this->data['Manager'])
        {
            $this->data['department']                = $this->Budget_hours_model->get_department();
            $this->data['assignment_status']         = $this->Budget_hours_model->get_assignment_status();
            $this->data['assignment_list']           = $this->Budget_hours_model->get_assignment_list();
            $this->data['completed_assignment_list'] = $this->Budget_hours_model->get_completed_assignment_list();
            $this->data['employee']                  = $this->Budget_hours_model->get_employee();

            $this->page_construct('index_AM.php', $meta, $this->data);
        }
        else
        {
            $this->data['assignment_list'] = $this->Budget_hours_model->get_employee_assignment_list($this->user_id);

            $this->page_construct('index.php', $meta, $this->data);
        }
	}

    public function edit($assignment_id)
    {
        $bc = array(array('link' => '#', 'page' => 'Budget_hours'));
        $meta = array('page_title' => 'Budget Hours', 'bc' => $bc, 'page_name' => 'Budget Hours');

        $this->data['assignment_details'] = $this->Budget_hours_model->get_assignment_details($assignment_id);
        $this->data['budget']             = $this->Budget_hours_model->get_budget($assignment_id);
        $this->data['actual_budget']      = $this->Budget_hours_model->get_actual_budget($assignment_id);
        $this->data['others']             = $this->Budget_hours_model->get_others($assignment_id);
        $this->data['report_type']        = $this->Budget_hours_model->get_report_type($assignment_id);

        $this->page_construct('edit.php', $meta, $this->data);
    }

    public function save_budget()
    {
        $form_data = $this->input->post();

        $data = array(
            'assignment_no'       => $form_data['assignment_id'],
            'client_id'           => $form_data['client_id'],
            'client_name'         => $form_data['client_name'],
            'fye'                 => $form_data['fye'],
            'type_of_job'         => $form_data['type_of_job'],
            'budget_hours_setted' => $form_data['budget_hour'],
            'budget'              => $form_data['budget_data'],
            'report_type'         => $form_data['report_type']
        );

        $result = $this->Budget_hours_model->save_budget($data);
        echo json_encode($result);

    }

    public function save_actual_budget()
    {
        $form_data = $this->input->post();

        $data = array(
            'assignment_no'       => $form_data['assignment_id'],
            'client_id'           => $form_data['client_id'],
            'client_name'         => $form_data['client_name'],
            'fye'                 => $form_data['fye'],
            'type_of_job'         => $form_data['type_of_job'],
            'budget_hours_setted' => $form_data['budget_hour'],
            'actual'              => $form_data['actual_data'],
            'report_type'         => $form_data['report_type']
        );

        $result = $this->Budget_hours_model->save_budget($data);
        echo json_encode($result);

    }

    public function save_others()
    {
        $form_data = $this->input->post();

        $data = array(
            'assignment_no'          => $form_data['assignment_id'],
            'client_id'              => $form_data['client_id'],
            'client_name'            => $form_data['client_name'],
            'fye'                    => $form_data['fye'],
            'type_of_job'            => $form_data['type_of_job'],
            'budget_hours_setted'    => $form_data['budget_hour'],
            'review_and_supervision' => $form_data['review_and_supervision'],
            'partner_review'         => $form_data['partner_review'],
            'fees_raised'            => $form_data['fees_raised'],
            'variance'               => $form_data['variance'],
            'report_type'            => $form_data['report_type']
        );

        $result = $this->Budget_hours_model->save_budget($data);
        echo json_encode($result);

    }

    public function save_PYA()
    {
        $form_data = $this->input->post();

        $data = array(
            'assignment_no'       => $form_data['assignment_id'],
            'client_id'           => $form_data['client_id'],
            'client_name'         => $form_data['client_name'],
            'fye'                 => $form_data['fye'],
            'type_of_job'         => $form_data['type_of_job'],
            'budget_hours_setted' => $form_data['budget_hour'],
            'prior_rns'           => $form_data['review_and_supervision'],
            'prior_pr'            => $form_data['partner_review'],
            'prior_fr'            => $form_data['fees_raised'],
            'prior_actual'        => $form_data['prior_actual_data']
        );

        $result = $this->Budget_hours_model->save_budget($data);
        echo json_encode($result);

    }

    public function save_PYA_RATE()
    {
        $form_data = $this->input->post();

        $data = array(
            'assignment_no'       => $form_data['assignment_id'],
            'client_id'           => $form_data['client_id'],
            'client_name'         => $form_data['client_name'],
            'fye'                 => $form_data['fye'],
            'type_of_job'         => $form_data['type_of_job'],
            'budget_hours_setted' => $form_data['budget_hour'],
            'prior_rate'          => $form_data['rate']
        );

        $result = $this->Budget_hours_model->save_budget($data);
        echo json_encode($result);

    }

    public function save_budget_log()
    {
        $form_data = $this->input->post();

        $id = $this->user_id;
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date'          => date("Y-m-d H:i:s"),
            'budget_log'    => "".$userName." saved the budget hours."
        );

        $result = $this->Budget_hours_model->submit_log($data);
        echo json_encode($result);

    }

    public function save_actual_budget_log()
    {
        $form_data = $this->input->post();

        $id = $this->user_id;
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date'          => date("Y-m-d H:i:s"),
            'budget_log'    => "".$userName." saved the actual budget hours."
        );

        $result = $this->Budget_hours_model->submit_log($data);
        echo json_encode($result);

    }

    public function save_others_log()
    {
        $form_data = $this->input->post();

        $id = $this->user_id;
        $q1 = $this->db->query("SELECT CONCAT(first_name , ' ' , last_name) AS name FROM users WHERE id = '".$id."'");
        $userName = $q1->result();
        $userName = $userName[0]->name;

        $data = array(
            'assignment_id' => $form_data['assignment_id'],
            'date'          => date("Y-m-d H:i:s"),
            'budget_log'    => "".$userName." saved others (Review and Supervision, Partner Review, Fees Raised and Explanations for Variance)."
        );

        $result = $this->Budget_hours_model->submit_log($data);
        echo json_encode($result);

    }

    public function show_log(){

        $form_data = $this->input->post();

        $result = $this->Budget_hours_model->get_log($form_data['assignment_id']);

        echo json_encode($result);

    }

    public function priorYear_Actual_Check(){

        $form_data = $this->input->post();

        $data = array(
            'client_id'   => $form_data['client_id'],
            'fye'         => $form_data['fye'],
            'type_of_job' => $form_data['type_of_job']
        );

        $result = $this->Budget_hours_model->priorYear_Actual_Check($data);
        echo json_encode($result);

    }

    public function priorYear_Rate_Check(){

        $form_data = $this->input->post();

        $data = array(
            'client_id'     => $form_data['client_id'],
            'assignment_id' => $form_data['assignment_id'],
            'fye'         => $form_data['fye'],
            'type_of_job' => $form_data['type_of_job']
        );

        $result = $this->Budget_hours_model->priorYear_Rate_Check($data);
        echo json_encode($result);

    }

    public function priorYear_Data_Check(){

        $form_data = $this->input->post();

        $data = array(
            'client_id'     => $form_data['client_id'],
            'assignment_id' => $form_data['assignment_id'],
            'fye'         => $form_data['fye'],
            'type_of_job' => $form_data['type_of_job']
        );

        $result = $this->Budget_hours_model->priorYear_Data_Check($data);
        echo json_encode($result);

    }

    public function actual_hours_check(){

        $form_data = $this->input->post();

        $people = $form_data['people'];
        $complete_date = $form_data['complete_date'];

        $result = $this->Budget_hours_model->actual_hours_check($people,$complete_date);
        echo json_encode($result);

    }

    public function get_type_of_job(){

        $form_data = $this->input->post();

        $type_of_job = $form_data['type_of_job'];

        $result = $this->Budget_hours_model->get_type_of_job($type_of_job);
        echo json_encode($result);

    }

    public function get_employee_bvsa_data(){

        $form_data = $this->input->post();

        if($form_data['employee_id'] != 0)
        {
            $result = $this->Budget_hours_model->get_employee_bvsa_data($form_data);
            echo json_encode($result);
        }
        else
        {
            $result = '';
            echo $result;
        }

    }

    public function get_prior_year_rate(){

        $form_data = $this->input->post();

        $data = array(
            'client_id'     => $form_data['client_id'],
            'assignment_id' => $form_data['assignment_id'],
            'fye'         => $form_data['fye'],
            'type_of_job' => $form_data['type_of_job']
        );

        $result = $this->Budget_hours_model->get_prior_year_rate($data);
        echo json_encode($result);

    }

    public function get_prior_year_actual(){

        $form_data = $this->input->post();

        $data = array(
            'client_id'     => $form_data['client_id'],
            'assignment_id' => $form_data['assignment_id'],
            'fye'         => $form_data['fye'],
            'type_of_job' => $form_data['type_of_job']
        );

        $result = $this->Budget_hours_model->get_prior_year_actual($data);
        echo json_encode($result);

    }

    public function get_stock_take_hours(){

        $form_data = $this->input->post();

        $client_id = $form_data['client_id'];
        $fye = $form_data['fye'];
        $people = $form_data['people'];

        $result = $this->Budget_hours_model->get_stock_take_hours($client_id,$fye,$people);
        echo json_encode($result);

    }

    public function generate_excel_bvsa(){

        $form_data = $this->input->post();

        if($form_data['employee_id'] != 0)
        {
            $result = $this->Budget_hours_model->get_employee_bvsa_data($form_data);

            $spreadsheet = new Spreadsheet();
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/BudgetVsActual_format.xlsx");
            $sheet = $spreadsheet->getActiveSheet();

            $i = 2;

            foreach($result as $data){
                foreach( range('A', 'E') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = $data['assignment_id'];
                            break;
                        }
                        case 'B': {
                            $value = $data['client_name'];
                            break;
                        }
                        case 'C': {
                            $value = $data['complete_date'];
                            break;
                        }
                        case 'D': {
                            $value = $data['actual_hour'];
                            break;
                        }
                        case 'E': {
                            $value = $data['budget_hour'];
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                    $i++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'excel/BudgetVsActual/Budget_vs_Actual('.$result[0]['employee_name'].').xlsx';
            $response = $filename;

            $writer->save($filename);
            chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/BudgetVsActual/Budget_vs_Actual('.$result[0]['employee_name'].').xlsx',0644);
            echo $response;
        }
        else
        {
            $result = '';
            echo $result;
        }
    }

    // public function generate_report(){

    //     $form_data = $this->input->post();
    //     $result = $this->Budget_hours_model->get_report_data($form_data);

    //     $staff = array();
    //     for($a=0;$a<count($result);$a++) {
    //         $actual = json_decode($result[$a]->actual);
    //         $total = 0;
    //         for($b=0;$b<(count((array)$actual) - 1);$b++) {
    //             $last_index = count($actual[$b]) - 1;
    //             array_push($staff,$actual[$b][0]);
    //             $total = $total + $actual[$b][$last_index];
    //         }
    //         $result[$a]->total = $total;
    //     }

    //     $staff = array_values(array_unique($staff));
    //     sort($staff);
    //     array_push($staff,'TOTAL');

    //     $apl = "G";
    //     for($a=0;$a<(count($staff));$a++) {
    //         if($a!==(count($staff)-1)) {
    //             $apl++;
    //         }
    //         // $apl++;
    //     }
    //     // $apl = chr(ord($apl) - 1);

    //     $spreadsheet = new Spreadsheet();
    //     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Budget_Report.xlsx");
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $staff_index = 0;
    //     $staff_position = array();

    //     foreach( $this->getcolumnrange('G', $apl) as $v ) {
    //         $row = 1;
    //         $spreadsheet->getActiveSheet()->setCellValue($v.$row, $staff[$staff_index]);
    //         $staff_position[$v] = $staff[$staff_index];
    //         $staff_index++;
    //     }

    //     for($a=0;$a<count($result);$a++) {
    //         $actual = json_decode($result[$a]->actual);
    //         for($b=0;$b<(count((array)$actual) - 1);$b++) {
    //             $last_index = count($actual[$b]) - 1;
    //             array_push($staff,$actual[$b][0]);
    //             $key = array_search ($actual[$b][0], $staff_position);
    //             $result[$a]->$key = $actual[$b][$last_index];
    //         }
    //     }

    //     $i = 2;
    //     foreach($result as $key => $data){
    //         if($data->fye) {
    //             $fye = date('d M Y', strtotime($data->fye));
    //         } else {
    //             $fye = "";
    //         }
    //         if($data->create_on) {
    //             $create_on = date('d M Y', strtotime($data->create_on));
    //         } else {
    //             $create_on = "";
    //         }
    //         if($data->complete_date) {
    //             $complete_date = date('d M Y', strtotime($data->complete_date));
    //         } else {
    //             $complete_date = "";
    //         }

    //         foreach( $this->getcolumnrange('A', $apl) as $v ) {
    //             switch( $v ) {
    //                 case 'A': {
    //                     $value = $data->client_name;
    //                     break;
    //                 }
    //                 case 'B': {
    //                     $value = $fye;
    //                     break;
    //                 }
    //                 case 'C': {
    //                     $value = $data->type_of_job;
    //                     break;
    //                 }
    //                 case 'D': {
    //                     $value = $create_on;
    //                     break;
    //                 }
    //                 case 'E': {
    //                     $value = $complete_date;
    //                     break;
    //                 }
    //                 case 'F': {
    //                     $value = $data->assignment_status;
    //                     break;
    //                 }
    //                 case $apl: {
    //                     $value = $data->total;
    //                     break;
    //                 }
    //                 default : {
    //                     $value = "";
    //                     break;
    //                 }
    //             }

    //             foreach ($data as $datakey => $datavalue) {
    //                switch( $v ) {
    //                     case $datakey: {
    //                         $value = $datavalue;
    //                         break;
    //                     }
    //                 } 
    //             }
    //             $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
    //         }
    //             $i++;
    //     }

    //     $writer = new Xlsx($spreadsheet);
    //     $filename = 'excel/budget_report/Budget_Report.xlsx';
    //     $response = $filename;
    //     $writer->save($filename);
    //     echo $response;
    // }
    public function generate_report(){
        $form_data = $this->input->post();

        if($form_data['status'] === '0') {
            $status = 'payroll_assignment.status LIKE "%%"';
        } else {
            $status = 'payroll_assignment.status = "'.$form_data['status'].'"';
        }
        if($form_data['report_dateFrom'] != '') {
            $report_dateFrom = "WHERE t.month >= '".date('Y-m-d', strtotime($form_data['report_dateFrom']))."'";
        } else {
            $report_dateFrom = "";
        }
        if($form_data['report_dateTo'] != '') {
            if($report_dateFrom == "") {
                $report_dateTo = "WHERE t.month <= '".date('Y-m-d', strtotime($form_data['report_dateTo']))."'";
            } else {
                $report_dateTo = "AND t.month <= '".date('Y-m-d', strtotime($form_data['report_dateTo']))."'";
            }
        } else {
            $report_dateTo = "";
        }

        if($form_data['report_department'] === '0') {
            $report_department = "";
        } else {
            $report_department = "AND e.department = '".$form_data['report_department']."'";
        }

        $query1 = $this->db->query("SELECT e.id AS id, e.name AS `employee_name`, t.content AS `content`, CONCAT(u.first_name , ' ' , u.last_name) AS `user_name` FROM timesheet t 
            INNER JOIN payroll_employee e ON e.id = t.employee_id 
            INNER JOIN  payroll_user_employee ue ON ue.employee_id = e.id
            INNER JOIN  users u ON u.id = ue.user_id
            ".$report_dateFrom."
            ".$report_dateTo."
            ".$report_department."
            ORDER BY employee_name, t.month ASC");

        $staff = array();
        $staff2 = array();
        $content = array();
        foreach($query1->result() as $row){
            $staff[$row->id] = $row->employee_name;
            $staff2[$row->id] = $row->user_name;
            // $content[$row->id] = $row->content;
            if(isset($content[$row->id]) && gettype($content[$row->id]) ==  'array') {
                array_push($content[$row->id],$row->content);
            } else {
                $content[$row->id] = array();
                array_push($content[$row->id],$row->content);
            }
        }
        $staff = array_values($staff);
        array_push($staff , "TOTAL");
        $staff2 = array_values($staff2);
        $content = array_values($content);
        $excel_list = array();

        for($a=0;$a<count($content);$a++) {
            foreach($content[$a] as $timesheet_content) {
                $timesheet = json_decode($timesheet_content);
                if(count((array)$timesheet) !== 0) {
                    for($b=0;$b<count($timesheet);$b++) {
                        $assignment = $timesheet[$b][0];
                        $job        = $timesheet[$b][1];
                        $fye        = date('Y-m-d', strtotime($timesheet[$b][2]));
                        $total      = $timesheet[$b][(count($timesheet[$b])-1)];
                        if($total == "") {
                            $total = 0.0;
                        }

                        if($assignment !== '') {
                            if($assignment[0] == '*') {
                                if($job !== 'STATUTORY AUDIT - CLEAR REVIEW' && $job !== 'STATUTORY AUDIT-STOCK TAKE') {

                                    $temp_assignment = str_replace("*","",$assignment);

                                    $query2 = $this->db->query(' SELECT payroll_assignment.client_name, payroll_assignment.fye, payroll_assignment_jobs.type_of_job, payroll_assignment_status.assignment_status, payroll_assignment.create_on, payroll_assignment.complete_date
                                        FROM payroll_assignment 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job
                                        LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status 
                                        WHERE '.$status.'
                                        AND payroll_assignment.client_name = "'.$temp_assignment.'"
                                        AND payroll_assignment_jobs.type_of_job = "'.$job.'"
                                        AND payroll_assignment.fye = "'.$fye.'"
                                        AND payroll_assignment.PIC LIKE "%'.$staff2[$a].'%"
                                        AND payroll_assignment.deleted = 0
                                    ');

                                    if($query2->num_rows() > 0) {
                                        foreach($query2->result() as $value) {
                                            $temp_assignment_list = array(
                                                "client" => $value->client_name,
                                                "fye"    => $value->fye,
                                                "job"    => $value->type_of_job,
                                                "from"   => $value->create_on,
                                                "to"     => $value->complete_date,
                                                "status" => $value->assignment_status,
                                            );

                                            $assignment_flag = false;
                                            if(count($excel_list)) {
                                                $result = $this->search_assignment_position($temp_assignment_list['client'],$temp_assignment_list['fye'],$temp_assignment_list['job'],$temp_assignment_list['from'],$temp_assignment_list['to'],$temp_assignment_list['status'],$excel_list);

                                                if($result !== 'null') {
                                                    $excel_list[$result][$a] = (float)$total;
                                                    // $excel_list[$result][(count($staff)-1)] = (float)$excel_list[$result][(count($staff)-1)] + (float)$total;
                                                    $assignment_flag = false;
                                                } else {
                                                    $assignment_flag = true;
                                                }
                                            } else {
                                                $assignment_flag = true;
                                            }

                                            if($assignment_flag) {
                                                for($c=0;$c<count($staff);$c++) {
                                                    if($c === $a) {
                                                        array_push($temp_assignment_list , $total);
                                                    } else if($c === (count($staff)-1)) {
                                                        array_push($temp_assignment_list , $total);
                                                    } else {
                                                        array_push($temp_assignment_list , "");
                                                    }
                                                }
                                                array_push($excel_list, $temp_assignment_list);
                                            }
                                        }
                                    }
                                } else {
                                    $temp_assignment = str_replace("*","",$assignment);

                                    $query2 = $this->db->query(' SELECT payroll_assignment.client_name, payroll_assignment.fye, payroll_assignment_jobs.type_of_job, payroll_assignment_status.assignment_status, payroll_assignment.create_on, payroll_assignment.complete_date
                                        FROM payroll_assignment 
                                        LEFT JOIN payroll_assignment_jobs ON payroll_assignment_jobs.id = payroll_assignment.type_of_job
                                        LEFT JOIN payroll_assignment_status ON payroll_assignment_status.id = payroll_assignment.status 
                                        WHERE '.$status.'
                                        AND payroll_assignment.client_name = "'.$temp_assignment.'"
                                        AND payroll_assignment_jobs.type_of_job = "STATUTORY AUDIT"
                                        AND payroll_assignment.fye = "'.$fye.'"
                                        AND payroll_assignment.PIC LIKE "%'.$staff2[$a].'%"
                                        AND payroll_assignment.deleted = 0
                                    ');

                                    if($query2->num_rows() > 0) {
                                        foreach($query2->result() as $value) {
                                            $temp_assignment_list = array(
                                                "client" => $value->client_name,
                                                "fye"    => $value->fye,
                                                "job"    => $job,
                                                "from"   => $value->create_on,
                                                "to"     => $value->complete_date,
                                                "status" => $value->assignment_status,
                                            );
                                            $assignment_flag = false;
                                            if(count($excel_list)) {
                                                $result = $this->search_assignment_position($temp_assignment_list['client'],$temp_assignment_list['fye'],$temp_assignment_list['job'],$temp_assignment_list['from'],$temp_assignment_list['to'],$temp_assignment_list['status'],$excel_list);

                                                if($result !== 'null') {
                                                    $excel_list[$result][$a] = (float)$total;
                                                    // $excel_list[$result][(count($staff)-1)] = (float)$excel_list[$result][(count($staff)-1)] + (float)$total;
                                                    $assignment_flag = false;
                                                } else {
                                                    $assignment_flag = true;
                                                }
                                            } else {
                                                $assignment_flag = true;
                                            }

                                            if($assignment_flag) {
                                                for($c=0;$c<count($staff);$c++) {
                                                    if($c === $a) {
                                                        array_push($temp_assignment_list , $total);
                                                    } else if($c === (count($staff)-1)) {
                                                        array_push($temp_assignment_list , $total);
                                                    } else {
                                                        array_push($temp_assignment_list , "");
                                                    }
                                                }
                                                array_push($excel_list, $temp_assignment_list);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $apl = "G";
        $apl2 = "G";
        for($a=0;$a<(count($staff));$a++) {
            if($a<(count($staff)-1)) {
                $apl++;
            }

            if($a<(count($staff)-2)) {
                $apl2++;
            }
        }

        $spreadsheet = new Spreadsheet();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Budget_Report.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $staff_index = 0;
        $staff_position = array();

        foreach( $this->getcolumnrange('G', $apl) as $v ) {
            $row = 1;
            $spreadsheet->getActiveSheet()->setCellValue($v.$row, $staff[$staff_index]);
            $staff_position[$v] = $staff[$staff_index];
            $staff_index++;
        }

        $i = 2;
        foreach($excel_list as $key => $data){

            if($data['fye']) {
                $fye = date('d M Y', strtotime($data['fye']));
            } else {
                $fye = "";
            }
            if($data['from']) {
                $create_on = date('d M Y', strtotime($data['from']));
            } else {
                $create_on = "";
            }
            if($data['to']) {
                $complete_date = date('d M Y', strtotime($data['to']));
            } else {
                $complete_date = "";
            }

            $index = 0;
            foreach( $this->getcolumnrange('A', $apl) as $v ) {
                switch( $v ) {
                    case 'A': {
                        $value = $data['client'];
                        break;
                    }
                    case 'B': {
                        $value = $fye;
                        break;
                    }
                    case 'C': {
                        $value = $data['job'];
                        break;
                    }
                    case 'D': {
                        $value = $create_on;
                        break;
                    }
                    case 'E': {
                        $value = $complete_date;
                        break;
                    }
                    case 'F': {
                        $value = $data['status'];
                        break;
                    }
                    case $apl: {
                        $value = '=SUM(G'.$i.':'.$apl2.$i.')';
                        break;
                    }
                    case $v: {
                        $value = $data[$index];
                        $index++;
                        break;
                    }
                    default : {
                        $value = "";
                        break;
                    }
                }
                $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
            }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/budget_report/Budget_Report.xlsx';
        $response = $filename;
        $writer->save($filename);
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/budget_report/Budget_Report.xlsx',0644);
        echo $response;
    }

    public function search_assignment_position($name,$fye,$job,$from,$to,$status, $array) {
       foreach ($array as $key => $val) {
            if ($val['client']===$name && $val['fye']===$fye && $val['job']===$job && $val['from']===$from && $val['to']===$to && $val['status']===$status) {
               return $key;
            }
       }
       return 'null';
    }
    public function getcolumnrange($min,$max){
        $pointer=strtoupper($min);
        $output=array();
        while($this->positionalcomparison($pointer,strtoupper($max))<=0){
            array_push($output,$pointer);
            $pointer++;
        }
        return $output;
    }
    public function positionalcomparison($a,$b){
       $a1=$this->stringtointvalue($a); $b1=$this->stringtointvalue($b);
       if($a1>$b1)return 1;
       else if($a1<$b1)return -1;
       else return 0;
    }
    public function stringtointvalue($str){
       $amount=0;
       $strarra=array_reverse(str_split($str));

       for($i=0;$i<strlen($str);$i++){
          $amount+=(ord($strarra[$i])-64)*pow(26,$i);
       }
       return $amount;
    }
}
