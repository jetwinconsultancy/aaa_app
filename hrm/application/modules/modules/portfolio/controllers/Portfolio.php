<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require_once('assets/vendor/tcpdf/tcpdf.php');

class Portfolio extends MX_Controller
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
        $this->load->model('portfolio/portfolio_model');
        $this->load->library(array('encryption'));
        
    }

    public function index()
    {   
        $this->data['User'] = $this->user_id;
        $this->meta['page_name'] = 'Portfolio';
        $bc   = array(array('link' => '#', 'page' => 'Portfolio'));
        $meta = array('page_title' => 'Portfolio', 'bc' => $bc, 'page_name' => 'Portfolio');

        $this->data['assignment_job_list'] = $this->portfolio_model->get_assignment_job_list();

        if(!$this->data['Admin'] && $this->user_id != '79' && $this->user_id != '62') 
        {
            $this->data['annually_list']       = $this->portfolio_model->get_PartnerReviwer_annually_list($this->user_id);
            $this->data['quarterly_list']      = $this->portfolio_model->get_PartnerReviwer_quarterly_list($this->user_id);
            $this->data['monthly_list']        = $this->portfolio_model->get_PartnerReviwer_monthly_list($this->user_id);
        }
        else
        {
            $this->data['annually_list']       = $this->portfolio_model->get_annually_list();
            $this->data['quarterly_list']      = $this->portfolio_model->get_quarterly_list();
            $this->data['monthly_list']        = $this->portfolio_model->get_monthly_list();
        }

        $this->data['portfolio_list']        = $this->portfolio_model->get_PartnerReviwer_portfolio_list($this->user_id,$this->Designation);
        $this->data['partner_reviewer_list'] = $this->portfolio_model->get_partner_reviewer_list();
        $this->data['partner_list']          = $this->portfolio_model->get_partner_list();
        $this->data['reviewer_list']         = $this->portfolio_model->get_reviewer_list();
        $this->data['jobs_list']             = $this->portfolio_model->get_jobs_list();
        $this->data['only_partner']          = $this->portfolio_model->get_partner();
        $this->data['only_reviewer']         = $this->portfolio_model->get_reviewer();

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function generateAnnuallyPortfolioExcel()
    {
        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Annually_portfolio.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;
        $tableData = $form_data['tableData'];

        foreach($tableData as $data)
        {
            $data = str_replace("&amp;",'&',$data);
            $data = str_replace("<br>","\n",$data);

            foreach( range('A', 'N') as $v )
            {
                switch( $v ) {
                    case 'A': {
                        $value = $data[0];
                        break;
                    }
                    case 'B': {
                        $value = $data[1];
                        break;
                    }
                    case 'C': {
                        $value = $data[2];
                        break;
                    }
                    case 'D': {
                        $value = $data[3];
                        break;
                    }
                    case 'E': {
                        $value = $data[4];
                        break;
                    }
                    case 'F': {
                        $value = $data[5];
                        break;
                    }
                    case 'G': {
                        $value = $data[6];
                        break;
                    }
                    case 'H': {
                        $value = $data[7];
                        break;
                    }
                    case 'I': {
                        $value = $data[8];
                        break;
                    }
                    case 'J': {
                        $value = $data[9];
                        break;
                    }
                    case 'K': {
                        $value = $data[10];
                        break;
                    }
                    case 'L': {
                        $value = $data[11];
                        break;
                    }
                    case 'M': {
                        $value = $data[12];
                        break;
                    }
                    case 'N': {
                        $value = $data[13];
                        break;
                    }
                }
                $spreadsheet->getActiveSheet()->setCellValue($v.$i, strtoupper($value));
                $spreadsheet->getActiveSheet()->getStyle($v.$i)->getAlignment()->setWrapText(true);
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/annually_portfolio/Annually Portfolio.xlsx';
        $response = $filename;

        $writer->save($filename);
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/annually_portfolio/Annually Portfolio.xlsx',0644);
        echo $response;
    }

    public function generateQuarterlyPortfolioExcel()
    {
       $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Annually_portfolio.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;
        $tableData = $form_data['tableData'];

        foreach($tableData as $data)
        {
            $data = str_replace("&amp;",'&',$data);
            $data = str_replace("<br>","\n",$data);

            foreach( range('A', 'N') as $v )
            {
                switch( $v ) {
                    case 'A': {
                        $value = $data[0];
                        break;
                    }
                    case 'B': {
                        $value = $data[1];
                        break;
                    }
                    case 'C': {
                        $value = $data[2];
                        break;
                    }
                    case 'D': {
                        $value = $data[3];
                        break;
                    }
                    case 'E': {
                        $value = $data[4];
                        break;
                    }
                    case 'F': {
                        $value = $data[5];
                        break;
                    }
                    case 'G': {
                        $value = $data[6];
                        break;
                    }
                    case 'H': {
                        $value = $data[7];
                        break;
                    }
                    case 'I': {
                        $value = $data[8];
                        break;
                    }
                    case 'J': {
                        $value = $data[9];
                        break;
                    }
                    case 'K': {
                        $value = $data[10];
                        break;
                    }
                    case 'L': {
                        $value = $data[11];
                        break;
                    }
                    case 'M': {
                        $value = $data[12];
                        break;
                    }
                    case 'N': {
                        $value = $data[13];
                        break;
                    }
                }
                $spreadsheet->getActiveSheet()->setCellValue($v.$i, strtoupper($value));
                $spreadsheet->getActiveSheet()->getStyle($v.$i)->getAlignment()->setWrapText(true);
            }
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/quarterly_portfolio/Quarterly Portfolio.xlsx';
        $response = $filename;

        $writer->save($filename);
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/quarterly_portfolio/Quarterly Portfolio.xlsx',0644);
        echo $response; 
    }

    public function generateMonthlyPortfolioExcel()
    {
        $spreadsheet = new Spreadsheet();
        $form_data = $this->input->post();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/Monthly_portfolio.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;
        $tableData = $form_data['tableData'];

        foreach($tableData as $data){
                foreach( range('A', 'E') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = strtoupper($data[0]);
                            break;
                        }
                        case 'B': {
                            $value = strtoupper($data[1]);
                            break;
                        }
                        case 'C': {
                            $value = strtoupper($data[2]);
                            break;
                        }
                        case 'D': {
                            $value = strtoupper($data[3]);
                            break;
                        }
                        case 'E': {
                            $value = strtoupper($data[4]);
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/monthly_portfolio/Monthly Portfolio.xlsx';
        $response = $filename;

        $writer->save($filename);
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/monthly_portfolio/Monthly Portfolio.xlsx',0644);
        echo $response;
    }

    public function get_partner()
    {
        $result = $this->portfolio_model->get_partner();
        echo json_encode($result);
    }

    public function get_reviewer()
    {
        $result = $this->portfolio_model->get_reviewer();
        echo json_encode($result);
    }

    public function get_client_list()
    {
        $result = $this->portfolio_model->get_client_list();
        echo json_encode($result);
    }

    // public function set_or_unset_client_list()
    // {
    //     $form_data = $this->input->post();

    //     $role      = $form_data['role'];
    //     $user_id   = $form_data['user_id'];
    //     $set_value = $form_data['set_value'];
    //     $job_id    = $form_data['job_id'];

    //     $result = $this->portfolio_model->set_or_unset_client_list($role,$user_id,$set_value,$job_id);
    //     echo json_encode($result);
    // }
    public function set_or_unset_client_list()
    {
        $form_data = $this->input->post();

        $partner   = $form_data['partner'];
        $reviewer  = $form_data['reviewer'];
        $job       = $form_data['job'];
        $set_value = $form_data['set_value'];
        $partner_filter  = isset($form_data['partner_filter'])?$form_data['partner_filter']:"";
        $reviewer_filter = isset($form_data['reviewer_filter'])?$form_data['reviewer_filter']:"";

        $result = $this->portfolio_model->set_or_unset_client_list($partner,$reviewer,$job,$set_value,$partner_filter,$reviewer_filter);
        echo json_encode($result);
    }

    public function update_client_to_portfolio()
    {
        $result = $this->portfolio_model->update_client_to_portfolio();
        echo json_encode($result);
    }

    public function get_all()
    {
        $result = $this->portfolio_model->get_portfolio_list();
        echo json_encode($result);
    }

    public function filter()
    {
        $form_data = $this->input->post();
        $partner  = isset($form_data['partner'])?$form_data['partner']:"";
        $reviewer = isset($form_data['reviewer'])?$form_data['reviewer']:"";
        $job      = isset($form_data['job'])?$form_data['job']:"";

        $result = $this->portfolio_model->filter($partner,$reviewer,$job);
        echo json_encode($result);
    }
}
