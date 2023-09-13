<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Action extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('action/action_model');
        $this->load->model('setting/setting_model');

        $this->load->model('offer_letter/offer_letter_model');
        $this->load->model('employment_json_model');
        $this->load->model('Day_time_json_model');
        
    }

    public function debug()
    {   
    	// print_r($this->data);
    	print_r($this->session->userdata());  
    }

    public function index()
    {   
        $bc   = array(array('link' => '#', 'page' => 'Action'));
        $meta = array('page_title' => 'Action', 'bc' => $bc, 'page_name' => 'Action');

        $this->meta['page_name'] = 'Action';

        // if($this->data['Admin'] || $this->data['Manager'] || $this->user_id == 79 || $this->user_id == 62 || $this->user_id == 91 || $this->user_id == 107)
        if($this->data['Admin'])
        {
            $this->data['staff_list'] = $this->action_model->get_employeeList();
        }
        else if($this->data['Manager'])
        {
            $this->data['staff_list'] = $this->action_model->get_employeeList($this->user_id,'true');
        }
        else
        {
            $this->data['staff_list'] = $this->action_model->get_employeeList($this->user_id);
        }

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function edit($staff_id = NULL)
    {   
        $this->meta['page_name'] = 'Action';
        $bc   = array(array('link' => '#', 'page' => 'Action'));
        $meta = array('page_title' => 'Action', 'bc' => $bc, 'page_name' => 'Action');

        $this->data['staff']           = $this->action_model->get_staff_info($staff_id);
        $this->data['event_info']      = $this->action_model->get_event_info($staff_id);
        $this->data['bank_info']       = $this->action_model->get_open_bank_info($staff_id);
        $this->data['firm_list']       = $this->action_model->get_firm_dropdown_list();
        $this->data['department_list'] = $this->action_model->get_employeeDepartment();

        $this->load->library('mybreadcrumb');
        $this->mybreadcrumb->add('Action', base_url('action'));
        $this->mybreadcrumb->add($this->data['staff'][0]->name, base_url());
        $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $this->page_construct('create.php', $meta, $this->data);
    }

    public function employmentContract(){
        $data = $this->input->post();

        $this->data['employee_data']    = $this->action_model->getEmployeeData($data['id']);
        $this->data['department_list']  = $this->action_model->get_employeeDepartment();

        $this->load->view('employmentContract', $this->data);
    }

    public function get_event_type(){
        $data = $this->action_model->getEventType();

        echo json_encode($data);
    }

    public function get_designation(){

        $form_data = $this->input->post();

        $department = $form_data['department'];

        if($department == '7'){

            $department = '%%';
        }

        $result = $this->setting_model->get_designation($department);
        echo json_encode($result);

    }

    public function add_event_info()
    {
        // Retrive Form Date
        $data['employee_id'] = $_POST['employee_id'][0];
        $data['date']=empty($_POST['eventDate'])? NULL:date('Y-m-d', strtotime($_POST['eventDate'][0]));
        $data['event']=$_POST['event'][0];
        $data['attachment']=$_POST['hidden_event_attachment'];

        if($data['event'] == 1)
        {
            $q = $this->db->query(" 
                SELECT * FROM payroll_employee_type_of_leave WHERE payroll_employee_type_of_leave.employee_id ='".$data['employee_id']."' AND payroll_employee_type_of_leave.type_of_leave_id = '1' 
            ");
            $this->db->where('id', $q->result()[0]->id);
            $this->db->update("payroll_employee_type_of_leave", array("days" => $_POST['AL']));

            $this->db->where('id', $data['employee_id']);
            $this->db->update("payroll_employee", array("department" => $_POST['department'],"designation" => $_POST['designation']));
        }

        if($data['event'] == 2 && $_POST['last_date'] != 'null')
        {
            $q = $this->db->query("SELECT * FROM payroll_employee WHERE id = ".$data['employee_id']);
                
            if($q->num_rows())
            {
                $q = $q->result_array();

                if($q[0]['employee_status_id'] == '1' && ($q[0]['date_of_letter'] == '' || $q[0]['status_date'] == ''))
                {
                    $q4 = $this->db->query("SELECT * FROM payroll_leave_cycle");
                    $q4 = $q4->result_array();

                    $q6 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE employee_id = ".$data['employee_id']);
                
                    if($q6->num_rows())
                    {
                        $q6 = $q6->result_array();

                        for($t = 0; $t < count($q6); $t++)
                        {
                            $q5 = $this->db->query("SELECT * FROM payroll_employee_type_of_leave WHERE type_of_leave_id = ".$q6[$t]['type_of_leave_id']." AND employee_id = ".$data['employee_id']);

                            $annual_leave_result = $q5->result_array();

                            $annual_leave_result_day = $annual_leave_result[0]['days'];

                            $date1 = $q[0]['date_joined'];
                            $date2 = date("Y").'-'.$q4[0]["leave_cycle_date_to"];

                            // $interval = abs(strtotime($date2) - strtotime($date1));
                            // $years = floor($interval / (365*60*60*24));
                            // $months = floor(($interval - $years * 365*60*60*24) / (30*60*60*24));

                            $ts1 = strtotime($date1);
                            $ts2 = strtotime($date2);
                            $year1 = date('Y', $ts1);
                            $year2 = date('Y', $ts2);
                            $month1 = date('m', $ts1);
                            $month2 = date('m', $ts2);
                            $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                            $months = $diff;

                            if(date('Y', strtotime($_POST['last_date'])) != date('Y', strtotime($q[0]['date_joined'])))
                            {
                                if($q6[$t]['type_of_leave_id'] == '1')
                                {
                                    // $balance_for_annual_leave_days = ($annual_leave_result_day * ($months/12))+$annual_leave_result_day;
                                    $balance_for_annual_leave_days = ($annual_leave_result_day * ($months/12));
                                }
                                else
                                {
                                    $balance_for_annual_leave_days = $annual_leave_result_day;
                                }
                            }
                            else
                            {
                                $balance_for_annual_leave_days = $annual_leave_result_day * ($months/12);
                            }

                            $q7 = $this->db->query("SELECT * FROM payroll_employee_annual_leave WHERE employee_id='". $data['employee_id'] ."' AND type_of_leave_id = '". $q6[$t]['type_of_leave_id'] ."' AND year(last_updated) = YEAR(CURDATE()) AND last_updated = (SELECT MAX(last_updated) FROM `payroll_employee_annual_leave` WHERE employee_id = ". $data['employee_id'] ." AND type_of_leave_id = ".$q6[$t]['type_of_leave_id'].")");

                            if(!$q7->num_rows())
                            {
                                // $total_annual_leave = floor($balance_for_annual_leave_days * 2) / 2;
                                $total_annual_leave = round($balance_for_annual_leave_days);
                            }
                            else
                            {
                                $q7_query = $q7->result_array();
                                $q7_query = $q7_query[0]['annual_leave_days'];
                                // $total_annual_leave = (floor($balance_for_annual_leave_days * 2) / 2) + $q7_query;
                                $total_annual_leave = round($balance_for_annual_leave_days) + $q7_query;
                            }

                            $final_data = array(
                                'employee_id' => $data['employee_id'],
                                'type_of_leave_id' => $q6[$t]['type_of_leave_id'],
                                'annual_leave_days' => $total_annual_leave
                            );
                            
                            $leave = $q7->result_array();
                            $this->db->insert('payroll_employee_annual_leave', $final_data);
                        }
                    }
                }
            }

            $this->db->where('id', $data['employee_id']);
            $this->db->update("payroll_employee", array("date_of_letter" => date('Y-m-d', strtotime($_POST['last_date'])), "status_date" => date('Y-m-d', strtotime($_POST['last_date'])), "employee_status_id" => '2' ));
        }

        if(($data['event'] == 4 || $data['event'] == 8) && $_POST['last_date'] != 'null')
        {
            $this->db->where('id', $data['employee_id']);
            $this->db->update("payroll_employee", array("date_cessation" => date('Y-m-d', strtotime($_POST['last_date'])), "employee_status_id" => '4' ));
        }

        if($data['event'] == 11)
        {
            $this->db->where('id', $data['employee_id']);
            $this->db->update("payroll_employee", array("designation" => $_POST['designation']));
        }

        if(($data['event'] == 6 || $data['event'] == 7 || $data['event'] == 12) && $_POST['wp_fin_no'] != 'null')
        {
            $this->db->where('id', $data['employee_id']);
            $this->db->update("payroll_employee", array("wp_fin_no" => $_POST['wp_fin_no'] ));
        }

        $q = $this->db->get_where("payroll_event_info", array("id" => $_POST['event_info_id'][0]));

        if (!$q->num_rows())
        {   
            $this->db->insert("payroll_event_info",$data);
            $insert_event_info_id = $this->db->insert_id();

            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated', "insert_event_info_id" => $insert_event_info_id));
        }
        else
        {
            $this->db->update("payroll_event_info",$data,array("id" => $_POST['event_info_id'][0]));
            echo json_encode(array("Status" => 1, 'message' => 'Information Updated', 'title' => 'Updated'));
        }
    }

    public function delete_event_info ()
    {
        $id = $_POST["event_info_id"];

        $data["deleted"] = 1;

        $this->db->update("payroll_event_info", $data, array('id'=>$id));

        echo json_encode(array("Status" => 1));
                
    }

    public function calculate_extend_date(){

        $form_data = $this->input->post();

        $join_date = $form_data['join_date'];
        $extension = $form_data['extension'];

        $result = date('d F Y', strtotime($join_date."+".$extension." month"));

        echo $result;

    }

    public function calculate_year_month(){

        $form_data = $this->input->post();

        // Declare and define two dates
        $date1 = strtotime($form_data['date1']);
        $date2 = strtotime(date('Y-m-d')); 
          
        // Formulate the Difference between two dates 
        $diff = abs($date2 - $date1);  
          
        // To get the year divide the resultant date into 
        // total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24));  
          
        // To get the month, subtract it with years and 
        // divide the resultant date into 
        // total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

        if($years == 0)
        {
            echo $months.' months';
        }
        else
        {
            echo $years.' years and '.$months.' months';
        }

    }

    public function calculate_year_month_InNumber(){

        $form_data = $this->input->post();

        // Declare and define two dates
        $date1 = strtotime($form_data['date1']);
        $date2 = strtotime(date('Y-m-d')); 
          
        // Formulate the Difference between two dates 
        $diff = abs($date2 - $date1);  
          
        // To get the year divide the resultant date into 
        // total seconds in a year (365*60*60*24) 
        $years = floor($diff / (365*60*60*24));  
          
        // To get the month, subtract it with years and 
        // divide the resultant date into 
        // total seconds in a month (30*60*60*24) 
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));

        echo  json_encode(array($years,$months));

    }

    // public function get_notice_period(){

    //     $form_data = $this->input->post();

    //     $last_month = date("d F Y", strtotime('today +'. $form_data['notice_period'].'days'));

    //     echo $last_month;

    // }

    public function get_firm_name(){

        $form_data = $this->input->post();

        $firm = $form_data['firm'];
        
        $query = $this->db->query("SELECT id,name FROM firm WHERE id = ".$firm."");
        $query = $query->result_array();

        echo json_encode($query);

    }

    public function save_open_bank_address(){

        $form_data = $this->input->post();

        $id = $form_data['id'];
        $address = $form_data['address'];

        if($id != null)
        {
            $this->db->where('id', $id);

            $q = $this->db->update('payroll_bank_details', array('address' => $address)); 
        }
        return $q;
    }

    public function get_wp_fin_no(){

        $form_data = $this->input->post();

        $emp_id = $form_data['emp_id'];
        
        $query = $this->db->query("SELECT wp_fin_no FROM payroll_employee WHERE id = ".$emp_id."");
        $query = $query->result_array();

        echo json_encode($query);

    }

    public function employment_contract_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $offer_letter_info = array(
            'firm_id'                 => $data[0],
            'name'                    => $data[1],
            'nric/passport'           => $data[2],
            'job_title'               => $data[3],
            'date_of_commencement'    => $data[4],
            'work_hour'               => $data[5],
            'vacation_leave'          => $data[6],
            'date_of_offer'           => $data[7],
        );
        $offer_letter_pdf = modules::load('offer_letter/CreateEmploymentContractPdf/');

        $return_data      = $offer_letter_pdf->create_employment_contract_pdf($offer_letter_info);
        echo $return_data;
    }

    public function probation_passed_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today    = $data[0];
        $emp_name = $data[1];
        $firm_id  = $data[2];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Pass Probation Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>Confirmation of Employment</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>
                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">As you are aware, your appointment with our firm was subject to a probationary period of 3 months. I am delighted to inform you that you have successfully completed this probationary period and your employment will now continue with our firm.</span></p></td></tr><p>&nbsp;</p>
                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Your probationary period counts towards your continuous service with our firm.</span></p></td></tr><p>&nbsp;</p>
                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Thank you for your commitment to excellence and professionalism over the past months and I look forward to working with you over the coming months and years.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Pass Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Pass Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);
        
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Pass Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Pass Probation Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function probation_extended_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today         = $data[0];
        $emp_name      = $data[1];
        $extend_period = $data[3];
        $extend_to     = $data[4];
        $reason        = $data[5];
        $firm_id       = $data[6];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        // $reason = json_decode(str_replace('[0~100][.]','[0~100][.]\t',json_encode($reason)));
        $reason = json_decode(str_replace('\n','<br>',json_encode($reason)));

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Extend Probation Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>Notice of Probationary Period Extension</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">This letter is to notify you of my intent to extend your probationary period for another '.$extend_period.' months.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">The reason for this extension is: <br><br>'.$reason.'</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">This extension will provide you additional time to perform the full range of responsibilities and demonstrate your ability to more fully and consistently meet outlined expectations for this position.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">With this extension, your probationary period is now scheduled to end on '.$extend_to.'. Please let me know if you would like to discuss this further or have any questions.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Extend Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Extend Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Extend Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Extend Probation Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function probation_failed_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today     = $data[0];
        $emp_name  = $data[1];
        $end_on    = $data[2];
        $reason    = $data[3];
        $firm_id   = $data[4];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        // $reason = json_decode(str_replace('-','\u2022\t',json_encode($reason)));
        $reason = json_decode(str_replace('\n','<br>',json_encode($reason)));

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Fail Probation Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>Notice of Unsuccessful Probationary</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Your appointment with our firm was subject to a probationary period of 3 months as set out in the letter of employment. I regret to notify you that you have not been successful through this probationary period and your employment will cease with our firm.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">The reasons for your failure during the probation are: <br><br>'.$reason.'</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">With this letter, your probationary period as well as your employment with this firm will end on '.$end_on.'. Please let me know if you would like to discuss this further or have any questions.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Fail Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Fail Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Fail Probation Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Fail Probation Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function employment_termination_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today     = $data[0];
        $emp_name  = $data[1];
        $end_on    = $data[2];
        $reason    = $data[3];
        $firm_id   = $data[4];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        // $reason = json_decode(str_replace('-','\u2022\t',json_encode($reason)));
        $reason = json_decode(str_replace('\n','<br>',json_encode($reason)));

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Employment Termination Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>Termination of Employment</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">We regret to inform you that your employment with the Company has to be terminated with effect from '.$end_on.'.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">The reasons for your termination are: <br><br>'.$reason.'</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Your final salary will be paid to you on your final day of work subject to applicable withholdings and deductions.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Your access to email and all systems shall cease on the day following your last day of work. You are required to return all company property and equipment upon your last day of work.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">We thank you for your contributions to the Company and wish you the best in your future endeavour.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Employment Termination Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Employment Termination Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Employment Termination Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Employment Termination Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function recommendation_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today       = $data[0];
        $emp_name    = $data[1];
        $designation = $data[2];
        $work_over   = $data[3];
        $firm_id     = $data[4];
        $gender      = $data[5];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        if($gender)
        {
            $He_She = 'He';
            $he_she = 'he';

            $his_her = 'his';
        }
        else
        {
            $He_She = 'She';
            $he_she = 'she';

            $his_her = 'her';
        }

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Recommendation Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">To Whom It May Concern: </span></p></td></tr><p>&nbsp;</p>';
        $content .= $name_content;

        $title_content = '<tr><td><p><strong>RE: <u>Letter of Recommendation</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">It is my pleasure to recommend '.$emp_name.' for employment with your organization. I have known '.$his_her.' for over '.$work_over.' during which time '.$he_she.' worked as '.$designation.' in my office. I have been consistently pleased with '.$his_her.' work ethic during the time that '.$he_she.' has worked in the office.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">'.$He_She.' is both dedicated and motivated. I am confident that '.$he_she.' will devote herself to a position with your organization with a high degree of diligence and independence. '.$He_She.' is quick to show '.$his_her.' ability to digest large volumes of information and complete '.$his_her.' tasks.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">I recommend '.$emp_name.' without reservation. I am confident that '.$he_she.' will establish productive relationships with your staff and organization.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Recommendation Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function PR_recommendation_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today     = $data[0];
        $emp_name  = $data[1];
        $Fin       = $data[2];
        $firm_name = $data[3];
        $work_over = $data[4];
        $firm_id   = $data[5];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        $firm_name = str_replace(".", "",$firm_name);

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "PR Recommendation Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;"><strong>Immigration & Checkpoints Authority of Singapore</strong><br>10 Kallang Road,<br>ICA Building,<br>Singapore 208718</span></p></td></tr><p>&nbsp;</p>';
        $content .= $name_content;

        $title_content = '<tr><td><p><strong>RE: <u>Letter of Recommendation</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Dear Sir/Mdm,</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">It is my pleasure to recommend '.$emp_name.' (FIN: '.$Fin.'). I have known '.$emp_name.' for '.$work_over.' in my capacity as director at '.$firm_name.'. '.$emp_name.' worked on various assignments, and based on the work, I would rank '.$emp_name.' as one of the best employees we have ever had.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Shall '.$emp_name.' performance in our company is a good indication of how '.$emp_name.' would perform for the country, '.$emp_name.' would be an extremely positive asset to this country.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">If I can be of any further assistance, or provide you with any additional information, please do not hesitate to contact me at (65) 6538 1993.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/PR Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/PR Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/PR Recommendation Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "PR Recommendation Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function reprimand_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today    = $data[0];
        $emp_name = $data[1];
        $reason   = $data[2];
        $firm_id  = $data[3];
        $emp_id   = $data[4];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        $query = $this->db->query("SELECT COUNT(*) as num_of_warning FROM payroll_event_info 
                                    WHERE employee_id = ".$emp_id." AND event = 5 AND deleted = 0");

        $query = $query->result();
        $num_of_warning = $query[0]->num_of_warning;

        $first_word = array('eth','First','Second','Third','Fouth','Fifth','Sixth','Seventh','Eighth','Ninth','Tenth','Elevents','Twelfth','Thirteenth','Fourteenth','Fifteenth','Sixteenth','Seventeenth','Eighteenth','Nineteenth','Twentieth');
        $second_word =array('','','Twenty','Thirty','Forty','Fifty');

        if($num_of_warning+1 <= 20)
        {
            $warning = $first_word[$num_of_warning+1];
        }
        else
        {
            $first_num = substr($num_of_warning+1,-1,1);
            $second_num = substr($num_of_warning+1,-2,1);
            $warning = $string = str_replace('y-eth','ieth',$second_word[$second_num].'-'.$first_word[$first_num]);
        }      

        $re = '/^[0-9]+[.](.*?)$\n/m';
        preg_match_all($re, $reason, $matches, PREG_SET_ORDER, 0);

        for($a=0;$a<count($matches);$a++)
        {
            $reason = str_replace($matches[$a][0],'<strong>'.$matches[$a][0].'</strong>',$reason);
        }

        $reason = json_decode(str_replace('\n','<br>',json_encode($reason)));

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Reprimand Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end   = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>'.$warning.' Written Warning</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        // $body_content ='<tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>
        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">This is an official written reprimand for your failure to perform the functions of your position appropriately. It has been a while since you joined the Company and you are expected to get yourself familiar with the duties and responsibilities expected to be discharged by you.</span></p></td></tr><p>&nbsp;</p>
        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">'.$reason.'</span></p></td></tr><p>&nbsp;</p>
        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">You have received verbal counseling for an earlier offense by your manager. With this letter of reprimand, I am reminding you of the critical importance of exercising care, managing your time and following clear instructions that your role requires.</span></p></td></tr><p>&nbsp;</p>
        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Immediate remedies are expected to rectify your follies. We shall observe your conduct within a month where immediate improvements are expected.</span></p></td></tr><p>&nbsp;</p>
        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Another failure to carry out any of your role will result in additional disciplinary action up to and including the possibility of demotion or employment termination.</span></p></td></tr><p>&nbsp;</p>

        //                 <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">A copy of this reprimand will be placed in your official personnel file.</span></p></td></tr>';
        // $content .= $body_content;
        // $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
        //                      <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
        //                      <p>&nbsp;</p>
        //                      <p>&nbsp;</p>
        //                      <p>&nbsp;</p>
        //                      <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">____________________________</span></p></td></tr><p>&nbsp;</p>
        //                      <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
        //                      <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        // $content .= $signature_content;
        // $content .= $table_content_end;

        $body_content ='<tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">This is an official written reprimand for your failure to perform the functions of your position appropriately. It has been a while since you joined the Company and you are expected to get yourself familiar with the duties and responsibilities expected to be discharged by you.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">'.$reason.'</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">You have received verbal counseling for an earlier offense by your manager. With this letter of reprimand, I am reminding you of the critical importance of exercising care, managing your time and following clear instructions that your role requires.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Immediate remedies are expected to rectify your follies. We shall observe your conduct within a month where immediate improvements are expected.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Another failure to carry out any of your role will result in additional disciplinary action up to and including the possibility of demotion or employment termination.</span></p></td></tr><p>&nbsp;</p>';
        $content .= $body_content;
        $content .= $table_content_end;

        $signature_content='<p>&nbsp;</p>
                            <table class="next" style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">

                            <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">A copy of this reprimand will be placed in your official personnel file.</span></p></td></tr>

                            <p>&nbsp;</p>
                            <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                            <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                            <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                            <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                            <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;

        $content .= $table_content_end;

        $content = str_replace('class="next"', 'nobr="true"', $content);

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Reprimand Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Reprimand Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Reprimand Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Reprimand Letter - (".$emp_name.") UTS".$uts.".pdf"));

    }

    public function open_bank_letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today            = $data[0];
        $emp_name         = $data[1];
        $FIN              = $data[2];
        $residing_address = $data[3];
        $firm             = $data[4];
        $date_join        = $data[5];
        $bank_info        = $data[6];
        $bank_address     = $data[7];
        $firm_id          = $data[8];
        $gender           = $data[9];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        if($gender)
        {
            $His_Her = 'His';
        }
        else
        {
            $His_Her = 'Her';
        }

        $query = $this->db->query("SELECT name FROM payroll_bank_details WHERE id = ".$bank_info."");
        $query = $query->result();

        $bank_info = $query[0]->name;

        $bank_address = json_decode(str_replace('\n','<br>',json_encode($bank_address)));

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Open Bank Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end   = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $bank_address_content = '<p>&nbsp;</p>
                                 <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;"><b>'.$bank_info.'</b></span></p></td></tr>
                                 <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$bank_address.'</span></p></td></tr>';
        $content .= $bank_address_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear Sir/ Madam,</span></p></td></tr><p>&nbsp;</p>';
        $content .= $name_content;

        $body_content ='<tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">This letter is to certify that '.$emp_name.' (NRIC/FIN: '.$FIN.') is an employee at '.$firm.' and has been working with the company since '.$date_join.'.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">'.$His_Her.' current residing address is '.$residing_address.' as per company’s employee records.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">If you require any additional information, please do not hesitate to contact me at (65) 6538 1993.</span></p></td></tr><p>&nbsp;</p>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;

        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Open Bank Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Open Bank Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Open Bank Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Open Bank Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function promotion_progression(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $today       = $data[0];
        $designation = $data[1];
        $effect_date = $data[2];
        $emp_name    = $data[3];
        $firm_id     = $data[4];

        $creator_name = $this->db->query("SELECT CONCAT(users.first_name , ' ' , users.last_name) AS name FROM users WHERE users.id = '".$this->user_id."'");
        $creator_name = $creator_name->result();
        $creator_name = $creator_name[0]->name;

        $creator_designation = '';
        if($this->user_id == 67)
        {
            $creator_designation = 'Director';
        }
        else
        {
            $creator_designation = 'Manager';
        }

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Promotion & Progression Letter";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 35, 10);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($firm_id);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        $obj_pdf->AddPage();

        $table_content_start = '<table style="border-collapse: collapse; width: 100%;padding-right:15px;" border="0">';
        $table_content_end = '</table>';

        $content .= $table_content_start;

        $date_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Date: '.$today.'</span></p></td></tr>';
        $content .= $date_content;

        $name_content = '<p>&nbsp;</p><tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$emp_name.'</span></p></td></tr>';
        $content .= $name_content;

        $on_hand = '<p style="padding-left: 30px; color:red"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;BY HAND</strong></p>';
        $content .= $on_hand;

        $title_content = '<tr><td><p><strong>RE: <u>Confirmation of Employment</u></strong></p></td></tr><p>&nbsp;</p>';
        $content .= $title_content;

        $body_content ='<tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Dear '.$emp_name.',</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">We have been monitoring your performance level lately.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Your accomplishment has been integral to our success and we deeply appreciate such contribution. Despite challenges ahead, we trust you will continue to accomplish better and for that, we would like to acknowledge your contribution.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">We are pleased to inform you that you have been promoted to '.$designation.' along with pay adjustment with effect from '.$effect_date.'.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">We thank you for your continuing commitment to excellence in the company and congratulate you on your dedication.</span></p></td></tr><p>&nbsp;</p>

                        <tr><td><p style="text-align: justify;"><span style="font-family: "Calibri"; font-size: 11pt;">Please be advised that matters relating to pay package are confidential in nature and should not be divulged to other employees.</span></p></td></tr>';
        $content .= $body_content;

        $signature_content ='<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">Sincerely,</span></p></td></tr>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">
                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                             [S I G N E D]</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">_____________________________</span></p></td></tr><p>&nbsp;</p>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_name.'</span></p></td></tr>
                             <tr><td><p><span style="font-family: "Calibri"; font-size: 11pt;">'.$creator_designation.'</span></p></td></tr>';
        $content .= $signature_content;


        $content .= $table_content_end;

        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Promotion & Progression Letter - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Promotion & Progression Letter - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Promotion & Progression Letter - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Promotion & Progression Letter - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function Annex_A_Letter(){

        $form_data = $this->input->post();
        $data = $form_data["data"];

        $emp_name  = $data[0];
        $Fin       = $data[1];
        $year      = $data[2];
        $month     = $data[3];

        $Fin_array = str_split($Fin);

        $array_link = [];
        $content = '';
        $obj_pdf= new MYPDF_for_annexA(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Annex A";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        $obj_pdf->setFontSubsetting(false);
        $obj_pdf->AddPage();

        // FIN NO
        $html = isset($Fin_array[0])?$Fin_array[0]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,146,45.5,$html);

        $html = isset($Fin_array[1])?$Fin_array[1]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,150.7,45.5,$html);

        $html = isset($Fin_array[2])?$Fin_array[2]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,154.9,45.5,$html);

        $html = isset($Fin_array[3])?$Fin_array[3]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,159.2,45.5,$html);

        $html = isset($Fin_array[4])?$Fin_array[4]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,163.5,45.5,$html);

        $html = isset($Fin_array[5])?$Fin_array[5]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,167.7,45.5,$html);

        $html = isset($Fin_array[6])?$Fin_array[6]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,172,45.5,$html);

        $html = isset($Fin_array[7])?$Fin_array[7]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,176.3,45.5,$html);

        $html = isset($Fin_array[8])?$Fin_array[8]:'';
        $obj_pdf->SetFont('helvetica', '', 9);
        $obj_pdf->writeHTMLCell(0,0,180.4,45.5,$html);

        // NAME OF APPLICANT
        $html = $emp_name;
        $obj_pdf->SetFont('helvetica', '', 11);
        $obj_pdf->writeHTMLCell(0,0,44,44,$html);

        // // YEAR 1
        // $html = '2020';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,21,183.5,$html);

        // // YEAR 1 VALUE
        // $html = '100,000,000';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,36,183.5,$html);

        // // YEAR 2
        // $html = '2019';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,77.5,183.5,$html);

        // // YEAR 2 VALUE
        // $html = '999,999,999';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,93.6,183.5,$html);

        // // YEAR 3
        // $html = '2018';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,135,183.5,$html);

        // // YEAR 3 VALUE
        // $html = '999,999,999';
        // $obj_pdf->SetFont('helvetica', '', 9);
        // $obj_pdf->writeHTMLCell(0,0,151,183.5,$html);

        // YEAR PERIOD OF EMPLOYMENT
        $html = $year;
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->writeHTMLCell(0,0,84.5,235,$html);

        // MONTH PERIOD OF EMPLOYMENT
        $html = $month;
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->writeHTMLCell(0,0,111.5,235,$html);

        $uts = time('Y.m.d H:i:s');

        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Annex A - ('.$emp_name.') UTS'.$uts.'.pdf', 'F');

        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Annex A - ('.$emp_name.') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Annex A - ('.$emp_name.') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Annex A - (".$emp_name.") UTS".$uts.".pdf"));
    }


    public function write_header($firm_id)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '".$firm_id."'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize(base_url('../secretary/uploads/logo/'.$query[0]["file_name"].'')); 

        $different_w_h = (float)$width - (float)$height;

        if((float)$width > (float)$height && $different_w_h > 100)
        {
            //before width is 25, height is 73.75
            $td_width = 25;
            $td_height = 73.75;
        }
        else
        {
            $td_width = 15;
            $td_height = 83.75;
        }

        if(!empty($query[0]["file_name"]))
        {
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $img = '<img src="'.$protocol . $_SERVER['SERVER_NAME'].'/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
        }
        else
        {
            $img = '';
        }

        if( $query[0]["address_type"] == 'Foreign')
        {
            $fax = $query[0]["fax"];

            if(empty($fax))
            {
                $fax = '-';
            }

            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="width: 1.25%; text-align: left;">&nbsp;</td>
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["foreign_address2"] .' '.$query[0]["foreign_address3"].'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $fax .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
        {
            $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
                    <tbody>
                    <tr style="height: 60px;">
                        <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
                            <table style="border-collapse: collapse; width: 100%;" border="0">
                            <tbody>
                            <tr>
                            <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
                            </tr>
                            </tbody>
                            </table>
                        </td>
                        <td style="width: 1.25%; text-align: left;">&nbsp;</td>
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }

        return $header_content;
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
        // $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
        $this->writeHTML($headerData['string']);
    }

    public function Footer() {
        $this->SetY(-18);
        $this->Ln();
        
        // Page number
        if (empty($this->pagegroups)) {
            $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
        } else {
            $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias();
        }

        if(!$this->one_page_only){
            $this->SetY(-18);
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 10, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
        
        if(!$this->last_page_flag){
           $this->SetY(-18);
        }

        $this->total_page++;

        // FOOTER IMG
        $logoX = 130;
        // $logoFileName = '../secretary/uploads/logo/ISCA_CA.png';
        // $logoFileName = base_url('../secretary/uploads/logo/ISCA_CA.png');
        $logoFileName = base_url().'uploads/logo/ISCA_CA.PNG';
        $logoWidth = 70;
        $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
    }
}


class MYPDF_for_annexA extends TCPDF {

   public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        // $img_file = 'C:/wamp64/www/hrm/assets/background/ANNEX_A.jpg';
        $img_file = base_url().'uploads/template/ANNEX_A.jpg';
        $this->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

