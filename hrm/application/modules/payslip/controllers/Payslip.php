<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payslip extends MX_Controller
{

    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }
        
        //$this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('employee/employee_model');
        $this->load->model('assignment/assignment_model');

        $this->load->model('payslip_model');
        // $this->load->module('payslip/create_document_pdf');
        // $this->load->library('controllers/createpayslippdf');

        if($this->data['Admin']){
            $this->employee_id  = $this->employee_model->get_employee_id_from_user_id($this->user_id);
        }

        $this->meta['page_name'] = 'Payslip';
    }

    public function index()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Employee', base_url('Employee'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();
        $this->meta['page_name'] = 'Payslip';
        $bc   = array(array('link' => '#', 'page' => 'Payslip'));
        $meta = array('page_title' => 'Payslip', 'bc' => $bc, 'page_name' => 'Payslip');

        if(!$this->data['Admin']){
            $this->data['payslip_list']   = $this->payslip_model->get_list($this->user_id);

            $available_month_list = array();

            $available_month_list[''] = "-- Select month --";

            foreach($this->data['payslip_list'] as $item){
                $available_month_list[$item->id] = date('F Y', strtotime($item->payslip_for));
            }

            $this->data['payslip_months'] = $available_month_list;

            $this->page_construct('index.php', $meta, $this->data);
        }else{

            
            $data = array(
                'date' => date('Y-m-d') // date of today
            );

            $this->data['payslip'] = $this->payslip_model->get_all_employee_list($data);
            $this->data['month_list'] = $this->payslip_model->get_all_months();
            $this->data['department'] = $this->assignment_model->get_department();


            $this->page_construct('index_admin.php', $meta, $this->data);
        }
    }

    //  merge with index after complete
    public function index_admin()
    {   
        // $this->load->library('mybreadcrumb');
        // $this->mybreadcrumb->add('Employee', base_url('Employee'));

        // $this->data['breadcrumbs'] = $this->mybreadcrumb->render();

        $data = array(
            'date' => date('Y-m-d') // date of today
        );

        $this->data['payslip'] = $this->payslip_model->get_all_employee_list($data);
        $this->data['month_list'] = $this->payslip_model->get_all_months();
        $this->data['department'] = $this->assignment_model->get_department();

        // print_r($this->data['department']);
        // echo "OOOOOOOOOOOOOOOOOOOOOOOOO";

        // echo json_encode($this->data['month_list']);
        // echo json_encode($this->data['payslip']);
        // echo date('F Y');   // November 2018

        $this->page_construct('index_admin.php', $this->meta, $this->data);
    }

    public function payslip_settings(){

        $this->data['payslip_settings'] = $this->payslip_model->get_payslip_settings();

        $this->page_construct('settings.php', $this->meta, $this->data);
    }

    public function set_bonus($month = NULL){

        $this->data['selected_month'] = $month;

        $selected_month = array(
            'date' => $month
        );

        $this->data['payslip_list'] = $this->payslip_model->get_all_bonus_list($selected_month);

        $this->page_construct('set_bonus.php', $this->meta, $this->data);
    }

    public function set_bonus_tr_partial()
    {
        $data = $this->input->post();

        $this->data['count']         = $data['count'];

        if($this->data['Manager'] && $this->user_id != 79) // user 79 is Penny
        {
            $this->data['employee_name'] = $this->employee_model->get_employeeList_dropdown($data['selected_month'],$this->user_id,'true');
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        }
        else
        {
            $this->data['employee_name'] = $this->employee_model->get_employeeList_dropdown($data['selected_month']);
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        }
        // $this->data['employee_name'] = $this->employee_model->get_employeeList_dropdown($data['selected_month']);

        if(!empty($data['bonus_details'])){
            $this->data['bonus_details'] = $data['bonus_details'];
        }

        $this->load->view('set_bonus_tr_partial', $this->data);
    }

    public function remove_bonus(){
        $data = $this->input->post();
        $payslip_id = $data['payslip_id'];

        $result = $this->payslip_model->remove_bonus($payslip_id);

        echo $result;
    }

    public function generate_payslip(){ 

        $data = $this->input->post();

        if($this->data['Manager'] && $this->user_id != 79) // user 79 is Penny
        {
            $result = $this->payslip_model->generate_all_payslip($data['selected_month'],$this->user_id,'true');
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        }
        else
        {
            $result = $this->payslip_model->generate_all_payslip($data['selected_month']);
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        }


        // $result = $this->payslip_model->generate_all_payslip($data['selected_month']);

        echo $result;
    }

    public function getThisMonthPayslipConfirmation(){
        $form_data = $this->input->post();
       

        if($this->data['Manager'] && $this->user_id != 79) // user 79 is Penny
        {
            $result = $this->payslip_model->check_all_payslip($form_data['selected_month'],NULL,$this->user_id,'true');
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        }
        else
        {
            $result = $this->payslip_model->check_all_payslip($form_data['selected_month'], $form_data['department']);
            // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        }

        echo json_encode($result);
    }

    public function getThisMonthPayslipList(){
        $form_data = $this->input->post();
       
        $this->data['payslip'] = $this->payslip_model->get_all_employee_list($form_data);

        echo json_encode($this->data);
    }

    public function view_payslip(){
        $form_data = $this->input->post();

        $payslip_id = $form_data['payslip_id'];

        $data = $this->payslip_model->view_payslip($payslip_id);


        $subtotal_salary     = $data->basic_salary + $data->aws + $data->bonus + $data->commission;
        $subtotal_less       = $data->cpf_employee + $data->cdac + $data->salary_advancement + $data->unpaid_leave;
        $subtotal_add        = $data->health_incentive + $data->bond_allowance;
        $total_net_remun_pay = $subtotal_salary - $subtotal_less + $subtotal_add;
        $total_cpf           = $data->cpf_employer + $data->cpf_employee;

        $payslip_info = array(
            'payslip_for'          => date('F Y', strtotime($data->payslip_for)),
            'employee_name'        => $data->name,
            'nric'                 => $data->nric_fin_no,
            'date'                 => $data->date,
            'designation'           => "(".$data->department_name.") ".$data->designation,
            'pv_no'                => $data->pv_no,
            'basic_salary'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->basic_salary,2,'.',',')),
            'aws'                  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->aws,2,'.',',')),
            'bonus'                => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->bonus,2,'.',',')),
            'commission'           => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->commission,2,'.',',')),
            'subtotal_salary'      => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_salary,2,'.',',')),    // subtotal salary
            'less_contribution'    => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employee,2,'.',',')),
            'less_cdac'            => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cdac,2,'.',',')),
            'less_salary_advance'  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->salary_advancement,2,'.',',')),
            'less_unpaid_leave'    => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->unpaid_leave,2,'.',',')),
            'subtotal_less'        => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_less * -1 ,2,'.',',')), // subtotal less
            'add_health_incentive' => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->health_incentive,2,'.',',')),
            'bond_allowance'       => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->bond_allowance,2,'.',',')),
            'subtotal_add'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($subtotal_add,2,'.',',')),        // subtotal add
            'total_net_remun_pay'  => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($total_net_remun_pay,2,'.',',')), // total net remuneration payable
            'cpf_employer'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employer,2,'.',',')),
            'cpf_employee'         => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->cpf_employee,2,'.',',')),
            'total_cpf'            => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($total_cpf,2,'.',',')),
            'sd_levy'              => preg_replace('/(-)([\d\.\,]+)/ui', '($2)', number_format($data->sd_levy,2,'.',',')),
            'remaining_days'       => $data->remaining_al,
            'payment_mode'         => $data->payment_mode,
            'firm'                 => $data->firm_id,
            'currency'             => $data->currency_shortform
        );

        $payslip_pdf = modules::load('payslip/CreatePayslipPdf/');
        $return_data = $payslip_pdf->create_document_pdf($payslip_info);

        echo $return_data;
    }

    public function submit_bonus(){
        $form_data = $this->input->post();

        $bonus_data = array();

        $payslip_setting = $this->db->query('SELECT * FROM payroll_payslip_setting')->result()[0];

        $form_data['payslip_id']                       = array_values($form_data['payslip_id']);
        $form_data['payslip_employee_name']            = array_values($form_data['payslip_employee_name']);
        $form_data['payslip_employee_bonus']           = array_values($form_data['payslip_employee_bonus']);
        $form_data['payslip_employee_commission']      = array_values($form_data['payslip_employee_commission']);
        $form_data['payslip_employee_other_allowance'] = array_values($form_data['payslip_employee_other_allowance']);

        for($i = 0; $i < count($form_data['payslip_employee_name']); $i++) {

            $employee_list   = $this->employee_model->get_staff_info($form_data['payslip_employee_name'][$i]);

            // $health_incentive = $form_data['hidden_payslip_employee_health_incentive'][$i] > 0 ? $payslip_setting->health_incentive: 0;
            // $aws              = $form_data['hidden_payslip_employee_aws'][$i] > 0 ? $employee_list[0]->salary: 0;

            $temp = array(
                'id'                => $form_data['payslip_id'][$i],
                'employee_id'       => $form_data['payslip_employee_name'][$i],
                'payslip_for'       => $form_data['selected_month'],
                'basic_salary'      => $employee_list[0]->salary,
                // 'aws'               => $aws,
                'bonus'             => $form_data['payslip_employee_bonus'][$i],
                'commission'        => $form_data['payslip_employee_commission'][$i],
                // 'health_incentive'  => $health_incentive,
                'other_allowance'   => $form_data['payslip_employee_other_allowance'][$i]
            );

            array_push($bonus_data, $temp);
        }

        $result = $this->payslip_model->set_bonus_payslip($bonus_data);

        echo json_encode($result);
    }

    public function submit_settings(){
        $form_data = $this->input->post();

        $payslip_setting = array(
            'id'            => $form_data['payslip_setting_id'],
            'cdac'          => $form_data['payslip_setting_cdac'],
            'sdl'           => $form_data['payslip_setting_sdl'],
            'last_updated'  => date('Y-m-d H:i:s')
        );

        $payslip_setting_id = $this->payslip_model->save_payslip_setting($payslip_setting);

        // echo json_encode($payslip_setting_id);
    }

    public function calculate_cpf(){ 

        $form_data = $this->input->post();

        // print_r($data);

        $employee_ids = $form_data['employee_id'];
        $payslip_salary = $form_data['payslip_salary'];
        $payslip_bond_allowance = $form_data['payslip_bond_allowance'];
        $payslip_bonus = $form_data['payslip_bonus'];
        $payslip_bundle = array();

        foreach ($employee_ids as $key => $id) 
        {
            $payslip_arr = array(
                'employee_id'            => $id,
                'payslip_for'            => $form_data['selected_month'],
                // 'date'                   => date("Y-m-d", strtotime("last day of this month")),date("Y-m-t", strtotime($a_date));
                'date'                   => date("Y-m-t", strtotime($form_data['selected_month'])),
                'basic_salary'           => $payslip_salary[$key],
                'bond_allowance'         => $payslip_bond_allowance[$key],
                'bonus'                  => $payslip_bonus[$key],
                'generate_by'            => $this->session->userdata("user_id"),
                'shown'                  => 0

            );
            // $payslip_arr

            array_push($payslip_bundle, $payslip_arr);

            // $result = $this->setting_model->insert_nationality($nationality_arr, $nationality_id);
            

        }

        $result = $this->payslip_model->save_and_calculate_cpf($form_data['selected_month'],$payslip_bundle);

        // if($this->data['Manager'] && $this->user_id != 79) // user 79 is Penny
        // {
        //     $result = $this->payslip_model->generate_all_payslip($data['selected_month'],$this->user_id,'true');
        //     // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        // }
        // else
        // {
        //     $result = $this->payslip_model->generate_all_payslip($data['selected_month']);
        //     // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        // }


        // $result = $this->payslip_model->generate_all_payslip($data['selected_month']);

        echo json_encode($result);
    }

    public function submit_payslip(){ 

        $form_data = $this->input->post();

        $employee_ids = $form_data['employee_id'];
        $payslip_ids = $form_data['payslip_id'];
        $cpf_employee = $form_data['cpf_employee'];
        $cpf_employer = $form_data['cpf_employer'];
        $salary_cpf_payable = $form_data['salary_cpf_payable'];
        $bonus_cpf_payable = $form_data['bonus_cpf_payable'];


        // $payslip_bonus = $form_data['payslip_bonus'];
        $payslip_bundle = array();

        foreach ($employee_ids as $key => $id) 
        {
            $payslip_arr = array(
                'id'                     => $payslip_ids[$key],
                'employee_id'            => $id,
                'cpf_employee'           => $cpf_employee[$key],
                'cpf_employer'           => $cpf_employer[$key],
                'salary_cpf_payable'     => $salary_cpf_payable[$key],
                'bonus_cpf_payable'      => $bonus_cpf_payable[$key]


            );
            // $payslip_arr

            array_push($payslip_bundle, $payslip_arr);

            $result = $this->payslip_model->update_payslip($payslip_bundle);
            

        }

        // $result = $this->payslip_model->save_and_calculate_cpf($form_data['selected_month'],$payslip_bundle);

        // // if($this->data['Manager'] && $this->user_id != 79) // user 79 is Penny
        // // {
        // //     $result = $this->payslip_model->generate_all_payslip($data['selected_month'],$this->user_id,'true');
        // //     // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList($this->user_id,'true');
        // // }
        // // else
        // // {
        // //     $result = $this->payslip_model->generate_all_payslip($data['selected_month']);
        // //     // $this->data['past_staff_list'] = $this->employee_model->get_past_employeeList();
        // // }


        // // $result = $this->payslip_model->generate_all_payslip($data['selected_month']);

        echo json_encode($result);
    }

    public function cancel_generate(){ 

        $form_data = $this->input->post();

        $payslip_ids = $form_data['payslip_id'];
        // $payslip_bonus = $form_data['payslip_bonus'];

        foreach ($payslip_ids as $id)
        {
            $this->db->where_in('id', $id);
            $result = $this->db->delete('payroll_payslip');
        }
      

        echo json_encode($result);
    }

    public function generateExcel() {

        $form_data = $this->input->post();
        $result = $this->payslip_model->get_payslip_data($form_data['selected_month']);

        $spreadsheet = new Spreadsheet();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/payslip_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;

        foreach($result as $data){
            for($v='A';$v!='AI';$v++){
                switch($v) {
                    case 'A': {
                        $value = date('M-y', strtotime($data['payslip_for']));
                        break;
                    }
                    case 'B': {
                        $value = $data['pr_issued_date']!=''?date('d-m-Y', strtotime($data['pr_issued_date'])):'';
                        break;
                    }
                    case 'C': {
                        $value = json_encode($data['nric_fin_no']);
                        break;
                    }
                    case 'D': {
                        $value = $data['name'];
                        break;
                    }
                    case 'E': {
                        $value = $data['date_joined']!=''?date('d-m-Y', strtotime($data['date_joined'])):'';
                        break;
                    }
                    case 'F': {
                        $value = $data['date_cessation']!=''?date('d-m-Y', strtotime($data['date_cessation'])):'N/A';
                        break;
                    }
                    case 'G': {
                        $value = $data['singapore_pr']?'SINGAPORE P.R.':$data['nationality'];
                        break;
                    }
                    case 'H': {
                        $value = $data['dob']!=''?date('d-m-Y', strtotime($data['dob'])):'';
                        break;
                    }
                    case 'I': {
                        $value = $data['firmName'];
                        break;
                    }
                    case 'J': {
                        $value = $data['office_name'];
                        break;
                    }
                    case 'K': {
                        $value = $data['department_name'];
                        break;
                    }
                    case 'L': {
                        $value = $data['designation'];
                        break;
                    }
                    case 'M': {
                        $value = $data['basic_salary'];
                        break;
                    }
                    case 'N': {
                        $value = '-';
                        break;
                    }
                    case 'O': {
                        $value = '-';
                        break;
                    }
                    case 'P': {
                        $value = '-';
                        break;
                    }
                    case 'Q': {
                        $value = '-';
                        break;
                    }
                    case 'R': {
                        $value = $data['bonus'];
                        break;
                    }
                    case 'S': {
                        $value = $data['basic_salary'] + $data['bonus'];
                        break;
                    }
                    case 'T': {
                        $value = $data['bond_allowance']?$data['bond_allowance']:0;
                        break;
                    }
                    case 'U': {
                        $value = $data['basic_salary'] + $data['bonus'] + $data['bond_allowance'];
                        break;
                    }
                    case 'V': {
                        $value = '-';
                        break;
                    }
                    case 'W': {
                        $value = '('.$data['cpf_employee'].')';
                        break;
                    }
                    case 'X': {
                        $value = '-';
                        break;
                    }
                    case 'Y': {
                        $value = '-';
                        break;
                    }
                    case 'Z': {
                        $value = $data['basic_salary'] + $data['bonus'] + $data['bond_allowance'] - $data['cpf_employee'];
                        break;
                    }
                    case 'AA': {
                        $value = 'GIRO';
                        break;
                    }
                    case 'AB': {
                        $value = $data['cpf_employee'];
                        break;
                    }
                    case 'AC': {
                        $value = $data['cpf_employer'];
                        break;
                    }
                    case 'AD': {
                        $value = $data['cpf_employee'] + $data['cpf_employer'];
                        break;
                    }
                    case 'AE': {
                        $value = '';
                        break;
                    }
                    case 'AF': {
                        $value = $data['sd_levy'];
                        break;
                    }
                    case 'AG': {
                        $value = $data['cpf_employee'] + $data['cpf_employer'] + $data['sd_levy'];
                        break;
                    }
                    case 'AH': {
                        $value = '';
                        break;
                    }
                }
                $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/payslip_format/payslip.xlsx';
        $response = $filename;

        $writer->save($filename);
        echo $response;
    }
}