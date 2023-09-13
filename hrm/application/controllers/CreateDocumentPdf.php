<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once('assets/vendor/tcpdf/tcpdf.php');

class CreateDocumentPdf extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	public function message($to = 'World') {
        echo "Ola Ola {$to}!" . PHP_EOL;
    }

	public function index()
	{
		$this->load->helper('pdf_helper');
	}

	public function create_document_pdf()
	{
		// create new PDF document
	    $obj_pdf= new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$title  = "Document";
		$obj_pdf->SetTitle($title);
		$obj_pdf->setPrintHeader(false);
		$obj_pdf->setPrintFooter(false);
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetMargins(25, 15, 10);
		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$obj_pdf->SetFont('helvetica', '', 10);
		$obj_pdf->setFontSubsetting(false);
		$obj_pdf->AddPage();

		$audit = ["Final Completion","Audit Completion","Audit Planing","General Audit Procedures","Revenue","Cost of Sales","Other income and expense","Inventories","Trade And Other Receivables","Cash And Bank Balances","Propert, Plant And Equipment","Investment Properties","Intangible Assets","Equity Investments","Share Capital And Reserves","Borrowings And Finance Lease","Trade And Other Payables","Current And Deferred Tax","Goods And Services Tax","Construction Contracts","Provision And Contingent Liabilities","Leases And Capital Commitment","Related Parties Transactions","Group Audit","Interest Rate & Forex Risk","Cashflows","Search for unrecorded liabilities","Draft Report","Clear review points","Stock take","Subtotal"];

		$accounting = ['Book Keeping','Draft Management Accounts','Draft Compilation Report','XBRL Preparation','Subtotal'];

		$payroll = ['CPF Application','CPF Submission','Work Pass Application & Cancellation','Tax Clearance','Subtotal'];

		$tax = ['GST Application','GST Submission','Estimated Tax Computation','Final Tax Computation','Tax Submission','Tax Queries','Withholding Tax Submission','Subtotal'];

		$others = ['AUP Verification','Draft Report','Corppass Creation','Letter Collection','Other Administrative Matters','Subtotal'];


		$document_id = $_POST["document_id"];
		$array_link = [];

		$query  = $this->db->query("SELECT * FROM payroll_budget WHERE payroll_budget.assignment_no ='".$document_id."'");
       	$payroll_budget = $query->result_array();

       	$query2  = $this->db->query("SELECT * FROM payroll_assignment WHERE assignment_id ='".$payroll_budget[0]["assignment_no"]."'");
       	$payroll_assignment = $query2->result_array();

       	$budget = json_decode($payroll_budget[0]["budget"]);
       	$budget_data = [];
       	$total_total_col_budget = 0;

       	$actual = json_decode($payroll_budget[0]["actual"]);
       	$actual_data = [];
       	$total_total_col_actual = 0;

       	$pya = json_decode($payroll_budget[0]["prior_actual"]);
       	
       	if(count((array)$pya[0]) == 31) {
       		array_splice($pya[0], 30, 0, 0);
       	}

       	$review_and_supervision = json_decode($payroll_budget[0]["review_and_supervision"]);
       	$partner_review	        = json_decode($payroll_budget[0]["partner_review"]);
       	$fees_raised            = json_decode($payroll_budget[0]["fees_raised"]);

       	$prior_rns = json_decode($payroll_budget[0]["prior_rns"]);
       	$prior_pr  = json_decode($payroll_budget[0]["prior_pr"]);
       	$prior_fr  = json_decode($payroll_budget[0]["prior_fr"]);

       	$variance = $payroll_budget[0]["variance"];


       	$report_type = $payroll_budget[0]["report_type"];

       	if($report_type == 'audit')
		{
			$activities = $audit;
		}
		else if($report_type == 'accounting')
		{
			$activities = $accounting;
		}
		else if($report_type == 'payroll')
		{
			$activities = $payroll;
		}
		else if($report_type == 'tax')
		{
			$activities = $tax;
		}
		else if($report_type == 'others')
		{
			$activities = $others;
		}

		$length = count((array)$activities);

       	for($r=0 ; $r<count((array)$review_and_supervision) ; $r++){
       		if($review_and_supervision[$r] == '')
       		{
       			$review_and_supervision[$r] = 0;
       		}
       	}

       	for($p=0 ; $p<count((array)$partner_review) ; $p++){
       		if($partner_review[$p] == '')
       		{
       			$partner_review[$p] = 0;
       		}
       	}

       	for($f=0 ; $f<count((array)$fees_raised) ; $f++){
       		if($fees_raised[$f] == '')
       		{
       			$fees_raised[$f] = 0;
       		}
       	}

       	for($n=1 ; $n<$length ; $n++)
       	{
       		array_push($budget_data, $budget[count((array)$budget)-1][$n]);
       		$total_total_col_budget = $total_total_col_budget + $budget[count((array)$budget)-1][$n];

       		array_push($actual_data, $actual[count((array)$actual)-1][$n]);
       		$total_total_col_actual = $total_total_col_actual + $actual[count((array)$actual)-1][$n];
       	}

       	array_push($budget_data,$total_total_col_budget);
       	array_push($actual_data,$total_total_col_actual);

       	$rate = [];
       	$cost = [];
       	$cost_2 = [];

       	for($z=0 ; $z<count((array)$budget)-1 ; $z++)
       	{
       		$query3  = $this->db->query(" SELECT payroll_charge_out_rate.rate FROM payroll_employee 
										  LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
										  LEFT JOIN users ON users.id = payroll_user_employee.user_id
										  LEFT JOIN payroll_designation ON payroll_designation.designation = payroll_employee.designation
										  LEFT JOIN payroll_charge_out_rate ON payroll_charge_out_rate.office_id = payroll_employee.office AND payroll_charge_out_rate.department_id = payroll_employee.department AND payroll_charge_out_rate.designation_id = payroll_designation.id
										  WHERE payroll_charge_out_rate.deleted = '0' AND CONCAT(users.first_name , ' ' , users.last_name) = '".$budget[$z][0]."' ");

       		if ($query3->num_rows() > 0)
       		{
       			$query3_result = $query3->result_array();
       			$rate_holder   = $query3_result[0]['rate'];
       		}
       		else
       		{
       			$rate_holder = 0;
       		}

       		array_push($rate, $rate_holder);

			$cost_per_pic = [];
			$total = 0;

			$cost_per_pic_2 = [];
			$total_2 = 0;

       		for($n=1 ; $n<$length ; $n++)
	       	{
	       		array_push($cost_per_pic, (float)$budget[$z][$n] * (float)$rate_holder);
	       		$total = $total + ((float)$budget[$z][$n] * (float)$rate_holder);

	       		array_push($cost_per_pic_2, (float)$actual[$z][$n] * (float)$rate_holder);
	       		$total_2 = $total_2 + ((float)$actual[$z][$n] * (float)$rate_holder);
	       	}
	       	array_push($cost_per_pic, $total);
	       	array_push($cost, $cost_per_pic);

	       	array_push($cost_per_pic_2, $total_2);
	       	array_push($cost_2, $cost_per_pic_2);
       	}

       	sort($rate);

       	for($n=0 ; $n<$length ; $n++)
       	{
       		$total_cost[$n] = 0;
       	}

       	for($n=0 ; $n<$length ; $n++)
       	{
       		$total_cost_2[$n] = 0;
       	}

       	for($x=0 ; $x<count((array)$cost) ; $x++)
       	{
       		for($n=0 ; $n<count((array)$cost[$x]) ; $n++)
	       	{
	       		$total_cost[$n] += $cost[$x][$n];
	       	}
       	}

       	for($x=0 ; $x<count((array)$cost_2) ; $x++)
       	{
       		for($n=0 ; $n<count((array)$cost_2[$x]) ; $n++)
	       	{
	       		$total_cost_2[$n] += $cost_2[$x][$n];
	       	}
       	}

       	$total_TimeCost_hrs[0] = $total_total_col_budget + $review_and_supervision[0] + $partner_review[0];
       	$total_TimeCost_hrs[1] = $total_total_col_actual + $review_and_supervision[1] + $partner_review[1];

       	$total_TimeCost_cost[0] = $total_cost[count((array)$total_cost)-1] + ($review_and_supervision[0] * $review_and_supervision[2]) + ($partner_review[0] * $partner_review[2]);
       	$total_TimeCost_cost[1] = $total_cost_2[count((array)$total_cost_2)-1] + $review_and_supervision[1] * $review_and_supervision[2] + ($partner_review[1] * $partner_review[2]);

       	$writeOff_profit[0] = (float)$fees_raised[0] - (float)$total_TimeCost_cost[0];
       	$writeOff_profit[1] = (float)$fees_raised[1] - (float)$total_TimeCost_cost[1];

       	if(is_numeric($pya[0][0]))
       	{
       		$pya_hrs = [];
       		$col_total = 0;

       		$rate_2 = (float)$pya[0][0];

       		$pya_cost = [];

       		for($n=1 ; $n<($length+1) ; $n++)
	       	{
	       		array_push($pya_hrs, $pya[count((array)$pya)-1][$n]);
	       		array_push($pya_cost, (float)$pya[count((array)$pya)-1][$n] * $rate_2);
	       	}

	       	$total_TimeCost_hrs[2] = $pya_hrs[count((array)$pya_hrs)-1] + $prior_rns[1] + $prior_pr[1];
	       	$total_TimeCost_cost[2] = $pya_cost[count((array)$pya_cost)-1] + ($prior_rns[1]*$prior_rns[2]) + ((float)$prior_pr[1]*(float)$prior_pr[2]);
	       	$writeOff_profit[2] = (float)$prior_fr[1] - (float)$total_TimeCost_cost[2];

       	}
       	else
       	{
       		$pya_hrs = [];
       		$temp_rate_2 = [];
       		$cost_3 = [];
       		$col_total_pya_hrs = 0;

		    for($n=1 ; $n<$length ; $n++)
	       	{
	       		array_push($pya_hrs, $pya[count((array)$pya)-1][$n]);
	       		$col_total_pya_hrs = (float)$col_total_pya_hrs + (float)$pya[count((array)$pya)-1][$n];
	       	}

	       	array_push($pya_hrs,$col_total_pya_hrs);

       		for($z=0 ; $z<count((array)$pya)-1 ; $z++)
	       	{
	       		$query4  = $this->db->query(" SELECT payroll_charge_out_rate.rate FROM payroll_employee 
											  LEFT JOIN payroll_user_employee ON payroll_user_employee.employee_id = payroll_employee.id
											  LEFT JOIN users ON users.id = payroll_user_employee.user_id
											  LEFT JOIN payroll_designation ON payroll_designation.designation = payroll_employee.designation
											  LEFT JOIN payroll_charge_out_rate ON payroll_charge_out_rate.office_id = payroll_employee.office AND payroll_charge_out_rate.department_id = payroll_employee.department AND payroll_charge_out_rate.designation_id = payroll_designation.id
											  WHERE CONCAT(users.first_name , ' ' , users.last_name) = '".$pya[$z][0]."' ");

	       		$query4_result = $query4->result_array();

	       		if($payroll_budget[0]['prior_rate'] == null || $payroll_budget[0]['prior_rate'] == 0)
	       		{
	       			if($query4_result[0]['rate'] != '')
		       		{
		       			array_push($temp_rate_2, $query4_result[0]['rate']);
		       		}
	       		}
	       		else
	       		{
	       			$query4_result[0]['rate'] = $payroll_budget[0]['prior_rate'];
	       			array_push($temp_rate_2, $payroll_budget[0]['prior_rate']);
	       		}

	       		$cost_per_pic_3 = [];
				$total_3 = 0;

	       		for($n=1 ; $n<$length ; $n++)
		       	{
		       		array_push($cost_per_pic_3, (float)$pya[$z][$n] * (float)$query4_result[0]['rate']);
		       		$total_3 = $total_3 + ((float)$pya[$z][$n] * (float)$query4_result[0]['rate']);
		       	}
		       	array_push($cost_per_pic_3, $total_3);
		       	array_push($cost_3, $cost_per_pic_3);
	       	}

	       	sort($temp_rate_2);
			$rate_2 = '';
			if(count($temp_rate_2)) {
				$rate_2 = $temp_rate_2[0].'~'.$temp_rate_2[count((array)$temp_rate_2)-1];
			}

	       	for($n=0 ; $n<$length ; $n++)
	       	{
	       		$total_cost_3[$n] = 0;
	       	}

	       	for($x=0 ; $x<count((array)$cost_3) ; $x++)
	       	{
	       		for($n=0 ; $n<count((array)$cost_3[$x]) ; $n++)
		       	{
		       		$total_cost_3[$n] += $cost_3[$x][$n];
		       	}
	       	}

	       	$pya_cost = $total_cost_3;

	       	$total_TimeCost_hrs[2] = (float)$pya_hrs[count((array)$pya_hrs)-1] + (float)$prior_rns[1] + (float)$prior_pr[1];
	       	$total_TimeCost_cost[2] = $pya_cost[count((array)$pya_cost)-1] + ((float)$prior_rns[1]*(float)$prior_rns[2]) + ((float)$prior_pr[1]*(float)$prior_pr[2]);
	       	$writeOff_profit[2] = (float)$prior_fr[1] - (float)$total_TimeCost_cost[2];
       	}

		$content = '<table style="border-collapse: collapse; width: 100%; height: 90px;" border="1">
					<tbody>
					<tr style="height: 15px;">
					<td style="width: 74%; height: 15px;"><span style="font-size: 8pt;"><strong>ID:</strong>   '.$payroll_budget[0]["budget_id"].'</span></td>
					<td style="width: 26%; height: 45.2px; text-align: center;" rowspan="5">
					<p><span style="font-size: 8pt;">Ref:</span></p>
					<p><span style="font-size: 11pt;"><strong>B06</strong></span></p>
					</td>
					</tr>
					<tr style="height: 15px;">
					<td style="width: 74%; height: 15px;"><span style="font-size: 8pt;"><strong>Entity:</strong>   '.$payroll_budget[0]["client_name"].'</span></td>
					</tr>
					<tr style="height: 15px;">
					<td style="width: 74%; height: 15.2px;"><span style="font-size: 8pt;"><strong>Period End:</strong>  '.date('d F Y',strtotime($payroll_assignment[0]["FYE"])).'</span></td>
					</tr>
					<tr style="height: 15px;">
					<td style="width: 74%; height: 15px;"><span style="font-size: 8pt;"><strong>TIME COSTS BUDGET AND PERFROMANCE SUMMARY</strong></span></td>
					</tr>
					</tbody>
					</table>

					<p style="LINE-HEIGHT:1px;">&nbsp;</p>

					<table style="border-collapse: collapse; width: 100%; height: 18px;" border="1">
					<tbody>
					<tr>
					<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">&nbsp;</span></td>
					<td style="width: 45%; height: 15px; text-align: center;" colspan="6"><span style="font-size: 8pt;"><strong>Current year</strong></span></td>
					<td style="width: 25%; height: 15px; text-align: center;" colspan="3"><span style="font-size: 8pt;"><strong>Prior year</strong></span></td>
					</tr>
					<tr>
					<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">&nbsp;</span></td>
					<td style="width: 22.5%; height: 15px; text-align: center;" colspan="3"><span style="font-size: 8pt;"><strong>Budget</strong></span></td>
					<td style="width: 22.5%; height: 15px; text-align: center;" colspan="3"><span style="font-size: 8pt;"><strong>Actual</strong></span></td>
					<td style="width: 25%; height: 15px; text-align: center;" colspan="3"><span style="font-size: 8pt;"><strong>Actual</strong></span></td>
					</tr>
					<tr>
					<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">&nbsp;</span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Hrs</strong></span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Rate S$</strong></span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Cost S$</strong></span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Hrs</strong></span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Rate S$</strong></span></td>
					<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Cost S$</strong></span></td>
					<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Hrs</strong></span></td>
					<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Rate S$</strong></span></td>
					<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;"><strong>Cost S$</strong></span></td>
					</tr>
					<tr>
					<td style="width: 30%; height: 10px;"><span style="font-size: 8pt;">&nbsp;</span></td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
					<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
					</tr>';

		for($i=0 ; $i<count((array)$activities) ; $i++)
		{
			$hours_in_row = (float)$budget_data[$i] + (float)$actual_data[$i] + (float)$pya_hrs[$i];

			if($hours_in_row != 0)
			{
				$content .='<tr>
							<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">'.$activities[$i].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$budget_data[$i].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$rate[0].'~'.$rate[count((array)$rate)-1].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_cost[$i].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$actual_data[$i].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$rate[0].'~'.$rate[count((array)$rate)-1].'</span></td>
							<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_cost_2[$i].'</span></td>
							<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$pya_hrs[$i].'</span></td>
							<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$rate_2.'</span></td>
							<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$pya_cost[$i].'</span></td>
							</tr>';
			}
		}

		$content .= '	<tr>
						<td style="width: 30%; height: 10px;"><span style="font-size: 8pt;">&nbsp;</span></td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						</tr>
						<tr>
						<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">Review And Supervision</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$review_and_supervision[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$review_and_supervision[2].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">
						<span style="font-size: 8pt;">'.$review_and_supervision[0]*$review_and_supervision[2].'</span>
						</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$review_and_supervision[1].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$review_and_supervision[2].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">
						<span style="font-size: 8pt;">'.$review_and_supervision[1]*$review_and_supervision[2].'</span>
						</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$prior_rns[1].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$prior_rns[2].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.(float)$prior_rns[1]*(float)$prior_rns[2].'</span></td>
						</tr>
						<tr>
						<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;">Partner review</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$partner_review[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$partner_review[2].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">
						<span style="font-size: 8pt;">'.$partner_review[0]*$partner_review[2].'</span>
						</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$partner_review[1].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$partner_review[2].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">
						<span style="font-size: 8pt;">'.$partner_review[1]*$partner_review[2].'</span>
						</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$prior_pr[1].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$prior_pr[2].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">
						<span style="font-size: 8pt;">'.(float)$prior_pr[1]*(float)$prior_pr[2].'</span></td>
						</tr>
						<tr>
						<td style="width: 30%; height: 10px;"><span style="font-size: 8pt;">&nbsp;</span></td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 10px; text-align: center;">&nbsp;</td>
						</tr>
						<tr>
						<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;"><strong>Total Time / Cost</strong></span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_hrs[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_cost[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_hrs[1].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_cost[1].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_hrs[2].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$total_TimeCost_cost[2].'</span></td>
						</tr>
						<tr>
						<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;"><strong>Fee Raised</strong></span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$fees_raised[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$fees_raised[1].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$prior_fr[1].'</span></td>
						</tr>
						<tr>
						<td style="width: 30%; height: 15px;"><span style="font-size: 8pt;"><strong>Write-off / Profit</strong></span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$writeOff_profit[0].'</span></td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 7.5%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$writeOff_profit[1].'</span></td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;">&nbsp;</td>
						<td style="width: 8.3333%; height: 15px; text-align: center;"><span style="font-size: 8pt;">'.$writeOff_profit[2].'</span></td>
						</tr>
						</tbody>
						</table>
						<p style="LINE-HEIGHT:1px;">&nbsp;</p>
						<p><span style="font-size: 8pt;"><strong>Explanations for variance:&nbsp;</strong>'.$variance.'</span></p>';

		$obj_pdf->writeHTML($content, true, false, false, false, '');

		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/'.$payroll_budget[0]["assignment_no"].'.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/'.$payroll_budget[0]["assignment_no"].'.pdf',0644);

		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

		array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/'.$payroll_budget[0]["assignment_no"].'.pdf');

		echo json_encode(array("link" => $array_link));
	}
}

class MYPDF extends TCPDF {}