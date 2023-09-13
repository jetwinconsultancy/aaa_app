<!-- <?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class Cron_billing extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'World')
    {
        //echo "Hello {$to}!".PHP_EOL;

        $this->load->library('encrypt');
        // $pass = $this->encrypt->encode("Syapac12345678#");
        // echo json_encode($pass);


        //pop.gmail.com
        //contact@me.com
        //jEFTM4T63AiQ9dsidxhPKt9CIg4HQjCN58n/RW9vmdC/UDXCzRLR469ziZ0jjpFlbOg43LyoSmpJLBkcAHh0Yw==




  //       $from_billing_cycle = "01/09/2018";
  //       $to_billing_cycle = "31/10/2018";

  //       if($to_billing_cycle != null)
		// {
		// 	$date_to_billing_cycle = str_replace('/', '-', $to_billing_cycle);
		// 	$to_billing_cycle = strtotime($date_to_billing_cycle);
		// 	$new_to_billing_cycle = date('Y-m-d',$to_billing_cycle);
		// 	//echo ($new_to);

		// 	$next_to_billing_cycle = new DateTime($new_to_billing_cycle);
		// 	// We extract the day of the month as $start_day
		//     $next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,1)->format(('Y-m-d'));
		// }
		// else
		// {
		// 	$new_to_billing_cycle = null;
		// }

		// if($from_billing_cycle != null)
		// {
		// 	$date_from_billing_cycle = str_replace('/', '-', $from_billing_cycle);
		// 	$from_billing_cycle = strtotime($date_from_billing_cycle);
		// 	$new_from_billing_cycle = date('Y-m-d',$from_billing_cycle);
			
		// 	$next_from_billing_cycle = new DateTime($new_from_billing_cycle);
		// 	// We extract the day of the month as $start_day
		//     $next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,1)->format(('Y-m-d'));
		// }
		// else
		// {
		// 	$new_from_billing_cycle = null;
		// }

		// echo $next_from_billing_cycle;
		// echo $next_to_billing_cycle;
		
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

    public function check_recurring_bill()
   	{
   		$now = getDate();
		$current_date = DATE("Y-m-d",now());

   		$q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id");

		$q = $q->result_array();

		
		//echo json_encode($q);
		for($t= 0; $t < count($q); $t++)
		{
			if($q[$t]["recurring_status"] != 0 && $q[$t]["billing_period"] != 1)
			{
				$invoice_issue_date = str_replace('/', '-', $q[$t]["recu_invoice_issue_date"]);
				$time_invoice_issue_date = strtotime($invoice_issue_date);
				$new_invoice_issue_date = date('Y-m-d',$time_invoice_issue_date);

				$invoice_issue_date_time = new DateTime($new_invoice_issue_date);

				if($new_invoice_issue_date == $current_date)
				{
					$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $new_invoice_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				}
				else 
				{
					if($q[$t]["billing_period"] == 2)
					{
						$after_one_month_issue_date = $this->MonthShifter($invoice_issue_date_time,1)->format(('Y-m-d'));
						if($after_one_month_issue_date == $current_date)
						{
							$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_month_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
						}
					}
					else if($q[$t]["billing_period"] == 3)
					{
						$after_quarter_year_issue_date = $this->MonthShifter($invoice_issue_date_time,3)->format(('Y-m-d'));
						if($after_quarter_year_issue_date == $current_date)
						{
							$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_quarter_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
						}
					}
					else if($q[$t]["billing_period"] == 4)
					{
						$after_half_year_issue_date = $this->MonthShifter($invoice_issue_date_time,6)->format(('Y-m-d'));
						if($after_half_year_issue_date == $current_date)
						{
							$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_half_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
						}
					}
					else if($q[$t]["billing_period"] == 5)
					{
						$after_one_year_issue_date = $this->MonthShifter($invoice_issue_date_time,12)->format(('Y-m-d'));
						if($after_one_year_issue_date == $current_date)
						{
							$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
						}
					}
				}
			}
		}
   	}

   	public function send_recurring_bill($q, $recurring_billing_id, $firm_id, $issue_date, $company_code, $company_name, $currency, $billing_period)
   	{	
   		// echo json_encode($content);
   		// echo json_encode($company_code);
   		// echo json_encode($company_name);
        $this->load->library('parser');

        $p = $this->db->query("select recurring_billing.id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date from recurring_billing left join recurring_billing_service on recurring_billing_service.billing_id = recurring_billing.id where recurring_billing.id =".$recurring_billing_id."");

       	$p = $p->result_array();

       	$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
									JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
									JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
									JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
									where firm.id = '".$firm_id."'");

		$query = $query->result_array();

		// $query_invoice_no = $this->db->query("SELECT invoice_no FROM billing where id = (SELECT max(id) FROM billing where status = '0' and firm_id = '".$firm_id."')");
        //$id = $query->row()->id;
        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where status = '0' and firm_id = '".$firm_id."' GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

        if ($query_invoice_no->num_rows() > 0) 
        {
            $query_invoice_no = $query_invoice_no->result_array();

            // $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
            // $number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

            $last_section_invoice_no = (string)$query_invoice_no[0]["invoice_no"];

            //$invoice_number = substr_replace($last_section_invoice_no, "", -1).((int)($last_section_invoice_no[strlen($last_section_invoice_no)-1]) + 1);
            $invoice_number = substr_replace($last_section_invoice_no, "", -4).(str_pad((int)(substr($last_section_invoice_no, -4)) + 1, 4, '0', STR_PAD_LEFT));

        }
        else
        {
            $invoice_number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        }

        // $query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,9, length(invoice_no)-8) AS UNSIGNED)) as invoice_no from billing"); //invoice_number excluding the 9 first characters, converts to int, and selects max from it.

        // if ($query_invoice_no->num_rows() > 0) 
        // {
        //     $query_invoice_no = $query_invoice_no->result_array();

        //     $last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
        //     $invoice_number = "AB-".date("Y")."-".str_pad($last_section_invoice_no,4,"0",STR_PAD_LEFT);

        // }
        // else
        // {
        //     $invoice_number = "AB-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
        // }

        $this->load->helper('pdf_helper');

		// create new PDF document
	    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$title = "Invoice";
		$obj_pdf->SetTitle($title);

		$unit_building_name = "";

		if(!empty($q["unit_no1"]) && !empty($q["unit_no2"])){
			$unit_building_name.= "#" . $q["unit_no1"] . "-" . $q["unit_no2"] . ",";
		}

		if(!empty($q["building_name"]) && !empty($q["unit_no1"]) && !empty($q["unit_no2"])){
			$unit_building_name.= ' ' . $q["building_name"] . ",";
		}else{
			$unit_building_name.= $q["building_name"] . ",";
		}

		$own_header = ($p[0]["own_letterhead_checkbox"] == 1)?true: false;

		$header_company_info = $this->write_header($q["firm_id"], $own_header);

		// gst number display
		if(!(empty($p[0]['gst_rate']) || ($p[0]['gst_rate'] == 0)))
		{
			$gst_number_display = '<td style="width: 25%; text-align:left; font-size: 8pt; font-weight:normal;">GST Reg. No. : '. $query[0]['registration_no'] .'</td>';
		}
		else
		{
			$gst_number_display = '';
		}

		$receiver_info = $this->receiver_info("invoice", $gst_number_display, $q, $invoice_number);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
		$header_company_info.
		$receiver_info,
		$tc=array(0,0,0), $lc=array(0,0,0));

		// if($own_header){
		// 	if(!empty($query[0]["file_name"]))
		// 	{
		// 		$img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
		// 	}
		// 	else
		// 	{
		// 		$img = '';
		// 	}

		// 	$header_company_info = 
		// 	'<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
		// 		<tbody>
		// 			<tr style="height: 80px;"><td style="width: 24.275%; text-align: left; height: 80px;" align="center">'. $img .'</td>
		// 				<td style="width: 75.725%; height: 80px;"><span style="font-size: 14pt;"><strong>'.$query[0]["name"].'</strong></span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'<br />Email: <span style="font-size: 7pt;">'. $query[0]["email"] .'</span>&nbsp;</span></td>
		// 			</tr>
		// 		</tbody>
		// 	</table>';
		// }
		// else
		// {
		// 	$header_company_info = 
		// 	'<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
		// 		<tbody>
		// 			<tr style="height: 80px;"><td style="height: 80px;"></td></tr>
		// 		</tbody>
		// 	</table>';
		// }

		// $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
		// $header_company_info.
		// '<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
		// 	<tbody>
		// 	<tr>
		// 	<td style="width: 64.7477%; font-weight: normal; font-size: 12px;">
		// 	<table style="width: 107.191%; border-collapse: collapse; height: 81px;" border="0">
		// 	<tbody>
		// 	<tr style="height: 20px;">
		// 	<td style="width: 100%; height: 20px; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$q["company_name"].'</span></strong></td>
		// 	</tr>
		// 	<tr style="height: 116px;">
		// 	<td style="width: 100%; height: 41px; text-align: left;"><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;"><span style="font-family: arial, helvetica, sans-serif;"><span style="font-size: 10pt;">'. $q["street_name"] . $unit_building_name .'<br /></span><span style="font-size: 10pt;">SINGAPORE '.$q["postal_code"].'</span></span></span></td>
		// 	</tr>
		// 	<tr style="height: 20px;">
		// 	<td style="width: 100%; height: 20px; text-align: left;"><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">ATTN: DIRECTOR / FINANCE DEPARTMENT</span></td>
		// 	</tr>
		// 	</tbody>
		// 	</table>
		// 	</td>
		// 	<td style="width: 10.889%;">&nbsp;</td>
		// 	<td style="width: 25.753%;">
		// 	<table style="height: 34px; width: 100%; border-collapse: collapse;" border="0">
		// 	<tbody>
		// 	<tr style="height: 25px;">
		// 	<td style="width: 93.077%; height: 34px;"><span style="text-align: left; font-size: 22pt; font-family: arial, helvetica, sans-serif;">INVOICE</span></td>
		// 	<td style="width: 6.92303%; text-align: right; height: 34px;"><span style="text-align: right; font-weight: normal; font-size: 20px;">&nbsp;</span></td>
		// 	</tr>
		// 	</tbody>
		// 	</table>
		// 	<table style="height: 72px; width: 99.1372%; border-collapse: collapse;" border="0">
		// 	<tbody>
		// 	<tr style="height: 18px;">
		// 	<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">'.$invoice_number.'</span></td>
		// 	<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
		// 	</tr>
		// 	<tr style="height: 18px;">
		// 	<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">'. date('d F Y', strtotime($issue_date)) .'</span></td>
		// 	<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
		// 	</tr>
		// 	<tr style="height: 18px;">
		// 	<td style="width: 94.3111%; height: 18px; text-align: left;">&nbsp;</td>
		// 	<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
		// 	</tr>
		// 	</tbody>
		// 	</table>
		// 	</td>
		// 	</tr>
		// 	</tbody>
		// 	</table>
		// 	<hr style="height: 1px; border: none; color: #333; background-color: #333;" />
		// 	<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt; height: 34px;" border="0">
		// 	<tbody>
		// 	<tr style="height: 17px;">
		// 	<td style="width: 86.9273%; height: 17px;" colspan="2">&nbsp;</td>
		// 	<td style="width: 6.68348%; text-align: center; height: 17px;">&nbsp;</td>
		// 	</tr>
		// 	<tr style="height: 17px;">
		// 	<td style="width: 86.9273%; height: 17px;" colspan="2" align="left"><strong><span style="text-decoration: underline;">Description</span></strong></td>
		// 	<td style="width: 15.5%; height: 17px;"><strong><span style="text-decoration: underline;">$</span></strong></td>
		// 	</tr>
		// 	</tbody>
		// </table>
		// ', 
		// $tc=array(0,0,0), $lc=array(0,0,0));

		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+58, PDF_MARGIN_RIGHT+3);
		$obj_pdf->SetFont('helvetica', '', 10);
		$obj_pdf->setFontSubsetting(false);
		$obj_pdf->AddPage();
		$content = '';
		$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
		$table_content_end = '</tbody></table>';
		$sub_total = 0;
		$gst = 0;
		$gst_rate = $p[0]['gst_rate'];

		$content .= $table_content_start;

		// to calculate total amount of gst (not for display) DO NOT DELETE THIS
		for($j = 0; $j < count($p); $j++)
		{	
			
			if($billing_period == 2)
			{
				$how_many_month = 1;
			}
			else if($billing_period == 3)
			{
				$how_many_month = 3;
			}
			else if($billing_period == 4)
			{
				$how_many_month = 6;
			}
			else if($billing_period == 5)
			{
				$how_many_month = 12;
			}		
			else
			{
				$how_many_month = "";
			}

			if(!empty($how_many_month))
			{
				if(!empty($p[$j]["period_start_date"]))
				{
					$format_period_start_date = str_replace('/', '-', $p[$j]["period_start_date"]);
					$time_period_start_date = strtotime($format_period_start_date);
					$new_format_period_start_date = date('Y-m-d',$time_period_start_date);
					$period_start_date_time = new DateTime($new_format_period_start_date);
					$new_period_start_date = $this->MonthShifter($period_start_date_time,$how_many_month)->format(('m/d/Y'));
				}
				else
				{
					$new_period_start_date = "";
				}

				if(!empty($p[$j]["period_end_date"]))
				{
					$format_period_end_date = str_replace('/', '-', $p[$j]["period_end_date"]);
					$time_period_end_date = strtotime($format_period_end_date);
					$new_format_period_end_date = date('Y-m-d',$time_period_end_date);
					$period_end_date_time = new DateTime($new_format_period_end_date);
					$new_period_end_date = $this->MonthShifter($period_end_date_time,$how_many_month)->format(('m/d/Y'));

				}
				else
				{
					$new_period_end_date = "";
				}

				$format_issue_date = str_replace('/', '-', $issue_date);
				$time_issue_date = strtotime($format_issue_date);
				$new_format_issue_date = date('Y-m-d',$time_issue_date);
				$issue_date_time = new DateTime($new_format_issue_date);
				$new_issue_date = $this->MonthShifter($issue_date_time,$how_many_month)->format(('m/d/Y'));
			}	
			else
			{
				$new_period_start_date = $p[$j]["period_start_date"];
				$new_period_end_date = $p[$j]["period_end_date"];
				$new_issue_date = $issue_date;
			}
			//echo json_encode($how_many_month);
			$recurring_billing_data = array(
		        'recu_invoice_issue_date' => date('d/m/Y', str_replace('/', '-',strtotime($new_issue_date)))
			);

			$this->db->where('id', $recurring_billing_id);
			$this->db->update('recurring_billing', $recurring_billing_data);

			if(!empty($new_period_start_date))//!empty($p[$j]["period_start_date"]) || 
			{
				$new_format_period_start_date = date('d/m/Y', strtotime($new_period_start_date));
			}
			else
			{
				$new_format_period_start_date = "";
				//$period_start_date = "";
			}

			if(!empty($new_period_end_date))//!empty($p[$j]["period_start_date"]) || 
			{
				$new_format_period_end_date = date('d/m/Y', strtotime($new_period_end_date));
			}
			else
			{
				$new_format_period_end_date = "";
				//$period_end_date = "";
			}


			$recurring_billing_service_data = array(
		        'period_start_date' => $new_format_period_start_date,
		        'period_end_date' => $new_format_period_end_date
			);

			$this->db->where('id', $p[$j]["recurring_billing_service_id"]);
			$this->db->update('recurring_billing_service', $recurring_billing_service_data);

			$period_start_date = !(empty($p[$j]["period_start_date"]))? ' from ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_start_date"]))) : '';

			$period_end_date = !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) : '';

			$amount = $p[$j]['amount'];

			if($amount < 0)
			{
				$amount = $amount * -1;
				$amount = '(' . number_format($amount, 2) . ')';
			}
			else
			{
				$amount = number_format($amount, 2);
			}

			$table_part_content = '<tr style="height: 17px;">
									<td style="width: 86.9273%;%; text-align: left;">
									<p style="text-align: justify;">'. $p[$j]['invoice_description']. '<strong>' . $period_start_date . $period_end_date .'</strong></p>
									</td>
									<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
								  </tr>';

			$content = $content.$table_part_content;

			$sub_total += (float)$p[$j]['amount'];

			$gst += round((($p[$j]['gst_rate'] / 100) * (float)$p[$j]['amount']), 2);
		}

		$content = $content . $table_content_end;	// add in end of table

		$total = $sub_total + $gst;

		$table_content_height = $obj_pdf->getStringHeight(1000, $content);

		// to display content
		for($j = 0; $j < count($p); $j++)
		{
			$amount = $p[$j]['amount'];

			if($amount < 0)
			{
				$amount = $amount * -1;
				$amount = '(' . number_format($amount, 2) . ')';
			}
			else
			{
				$amount = number_format($amount, 2);
			}

			$period_start_date 	= !(empty($p[$j]["period_start_date"]))? ' from ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_start_date"]))) : '';

			$period_end_date 	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) : '';

			$table_part_content = '<tr style="height: 17px;">
									<td style="width: 86.9273%;%; text-align: left;">
									<p style="text-align: justify;">'. $p[$j]['invoice_description']. '<strong>' . $period_start_date . $period_end_date .'</strong></p>
									</td>
									<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
									</tr>';

			$current_content .= $table_part_content;	// content without table tag

			$test_content_height = $obj_pdf->getStringHeight(1000, $table_content_start . $current_content . $table_content_end);
			$obj_pdf->writeHTML($table_content_start . $table_part_content . $table_content_end, true, false, false, false, '');

			if($obj_pdf->getY() >= 190  && $test_content_height == $table_content_height){ // for one page only
				
				$obj_pdf->AddPage();
			}
		}

		
		
		$obj_pdf->SetY(-50);
			        
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->SetXY(17, 190);

					$bank = $this->db->query("SELECT * FROM bank_info WHERE firm_id =". $q["firm_id"] . " AND in_use = 1");
					$bank = $bank->result_array();

					$content2 = '<table style="border-collapse: collapse;" border="0">
					<tbody>
					<tr>
					<td style="width: 100%;"><hr /></td>
					</tr>
					</tbody>
					</table>';

					if($q['rate'] != 1)
					{
						$converted_total = $total * $q['rate'];
						$show_converted_total = '<tr>
								<td style="width: 63%;"><span style="font-size: 8pt;">SGD: '. $this->spell_number_dollar($converted_total) .'</span></td>
								<td style="width: 17%;">Equivalent To: </td>
								<td style="width: 20%; text-align: right;">
								<div style="text-align: right;"><strong>' . number_format($converted_total, 2) . '</strong></div>
								<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
								</td>
								</tr>';
					}
					

					$content_gst  = '<table style="width: 100%; border-collapse: collapse; height: 34px;" border="0">
								<tbody>
								<tr style="height: 17px;">
								<td style="width: 63%; height: 17px;">&nbsp;</td>
								<td style="width: 17%; height: 17px;">Subtotal</td>
								<td style="width: 20%; height: 17px; text-align: right;"><strong>'. number_format($sub_total,2) .'</strong></td>
								</tr>
								<tr style="height: 17px;">
								<td style="width: 63%; height: 17px;">&nbsp;</td>
								<td style="width: 17%; height: 17px;">GST '. $gst_rate .'%</td>
								<td style="width: 20%; height: 17px; text-align: right;">'. number_format($gst,2) .'</td>
								</tr>
								</tbody>
								</table>
								<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 63.9975%;">&nbsp;</td>
								<td style="width: 36.0025%;"><hr /></td>
								</tr>
								</tbody>
								</table>';
					
					$content3 = '<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 63%;"><span style="font-size: 8pt;">'. $q["currency"] .': '. $this->spell_number_dollar($total) .'</span></td>
								<td style="width: 17%;">Total</td>
								<td style="width: 20%; text-align: right;">
								<div style="text-align: right;"><strong>'. number_format($total,2) .'</strong></div>
								<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
								</td>
								</tr>'. 
								$show_converted_total
								.'</tbody>
								</table>';

					if($gst_rate == 0){
						$content2 .= $content3;
					}else{
						$content2 .= $content_gst . $content3;
					}

					$billing_bank_info["bank_info_id"] = $bank[0]["id"];
					$this->db->update("billing",$billing_bank_info,array("id" => $q["id"]));

					$obj_pdf->SetAutoPageBreak(TRUE, 10);
					$obj_pdf->writeHTML($content2, true, false, false, false, '');

					$display_bank_info = '<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 100%;"><br/><p style="font-size: 8pt;"> Please make a cheque payable to <strong>'.$query[0]["name"].'&nbsp;</strong>or remit to:</p></td>
								</tr>
								<tr>
								<td style="width: 100%;">
								<table style="width: 100.025%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 100%;">
								<p><span style="font-size: 8pt;">Banker: <strong>'.$bank[0]["banker"].'<br /></strong>Account Number:<strong>&nbsp;'.$bank[0]["account_number"].'&nbsp; &nbsp;</strong>Bank code:<strong> '.$bank[0]["bank_code"].'&nbsp; &nbsp;</strong>Swift code:<strong> '.$bank[0]["swift_code"].'</strong></span>&nbsp;</p>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td style="width: 100%;">
								<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td>
								<p style="font-size: 8pt;"><br/>* For wire transfer, payer shall bear all local and oversea bank charges.<br /><span style="font-size: 8pt;">* Please email the remittance advice to '. $query[0]["email"] .'.</span><br /><span style="font-size: 8pt;">* Please write the invoice reference number on the back of the cheque to facilitate processing.</span><br /><span style="font-size: 8pt;">* Interest accrued at 1% per month will be levied on outstanding balances that are more than 30 days from the invoice date.<br />* '. $query[0]["name"] .' reserve the rights to suspense your account until payment has been received.</span></p>
								<p style="font-size: 8pt;"><span style="font-size: 8pt;">NOTE:<br />Kindly advice us of any discrepancies within 30 days from invoice date, failing which this invoice will be deemed correct and disputes arising thereafter shall not be entertained.<br /><br /></span><span style="font-size: 8pt;">E. &amp; O.E</span></p>
								</td>'
								// '<td style="width: 12.5316%;"> 
								// <p style="text-align: center;"><span style="font-size: 8pt;"><em>for quick transfer</em><br /></span><span style="font-size: 8pt;"><img style="width: 49px; height: 49px;" src="img/invoice_qr_code.png" /></span></p>
								// </td>'.
								.'</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>';

		//$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->writeHTML($display_bank_info, true, false, false, false, '');
		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/'.$invoice_number.'.pdf', 'F');

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

		//$invoice_pdf_link = "";
		$invoice_pdf_link = $_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/'.$invoice_number.'.pdf';

		$select_all_director = $this->db->query("select officer.name, officer_email.email from client_officers left join officer_email on officer_email.officer_id = client_officers.officer_id and officer_email.primary_email = 1 left join officer on officer.id = client_officers.officer_id where client_officers.company_code = '".$company_code."' and client_officers.position = '1' and client_officers.retiring = '0' and client_officers.field_type = 'individual' and client_officers.date_of_cessation = ''");

        if ($select_all_director->num_rows() > 0) 
		{
			$select_all_director = $select_all_director->result_array();

			for($t = 0; $t < count($select_all_director); $t++)
			{	
                $parse_data = array(
                	'firm_name' => $query[0]["name"],
                	'firm_email' => $query[0]["email"],
                    'user_name' => $select_all_director[$t]["name"],
                    'email' => $select_all_director[$t]["email"],
                    'total_amount' => number_format($total, 2),
                    'issue_date' => $issue_date,
                    'currency_name' => $currency
                );
                $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
                $message = $this->parser->parse_string($msg, $parse_data);

                $subject =  '(TESTING) INVOICE FOR '.$company_name;

                $undersigned = base_url().'img/acumen_bizcorp_header.jpg';
                //echo json_encode($select_all_director[$t]["email"]);
                $check_email_send_to_contact_person = $this->sma->send_email($select_all_director[$t]["email"], $subject, $message.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'paul@aaa-global.com', 'ACT Secretary', $invoice_pdf_link, 'corpsec@aaa-global.com,justin@aaa-global.com,then.k.w@hotmail.com');
                
                // $check_email_send_director_person = $this->sma->send_email($select_all_director[$t]["email"], $subject, $message.'<p>Best regards,<br />Paul<br /><img src="'.$undersigned.'" alt="acumen" width="150" height="60" /><br />Address: 143 Cecil Street, #16-03 GB Building, Singapore 069542<br />Tel: (+65) 6220 1939</p>', 'paul@acumenbizcorp.com.sg', 'ACT Secretary', $_SERVER["DOCUMENT_ROOT"].'test_secretary/pdf/invoice/'.$q[0]["invoice_no"].'.pdf');
            }
        }

        $select_contact_person = $this->db->query("select client_contact_info.*, client_contact_info_email.email from client_contact_info left join client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id and primary_email = 1 where client_contact_info.company_code = '".$company_code."'");

        if ($select_contact_person->num_rows() > 0) 
		{
			$select_contact_person = $select_contact_person->result_array();

			for($t = 0; $t < count($select_contact_person); $t++)
			{
                $parse_data = array(
                	'firm_name' => $query[0]["name"],
                	'firm_email' => $query[0]["email"],
                    'user_name' => $select_contact_person[$t]["name"],
                    'email' => $select_contact_person[$t]["email"],
                    'total_amount' => number_format($total,2),
                    'issue_date' => $issue_date,
                    'currency_name' => $currency
                );
                $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
                $message = $this->parser->parse_string($msg, $parse_data);


                $subject =  '(TESTING) INVOICE FOR '.$company_name;

                $undersigned = base_url().'img/acumen_bizcorp_header.jpg';

                $check_email_send_to_contact_person = $this->sma->send_email($select_contact_person[$t]["email"], $subject, $message.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'paul@aaa-global.com', 'ACT Secretary', $invoice_pdf_link, 'corpsec@aaa-global.com,justin@aaa-global.com,then.k.w@hotmail.com');
                // $check_email_send_to_contact_person = $this->sma->send_email($select_contact_person[$t]["email"], $subject, $message.'<p>Best regards,<br />Paul<br /><img src="'.$undersigned.'" alt="acumen" width="150" height="60" /><br />Address: 143 Cecil Street, #16-03 GB Building, Singapore 069542<br />Tel: (+65) 6220 1939</p>', 'paul@acumenbizcorp.com.sg', 'ACT Secretary', $_SERVER["DOCUMENT_ROOT"].'test_secretary/pdf/invoice/'.$q[0]["invoice_no"].'.pdf');

                //($to, $subject, $message, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
            }
        }

        //print($check_email_send_director_person);

        if($check_email_send_director_person || $check_email_send_to_contact_person)
        {
        	$billing["firm_id"] = $q["firm_id"];
        	$billing["company_code"] = $q["company_code"];
        	$billing["currency_id"] = $q["currency_id"];
        	$billing["invoice_date"] = date('d/m/Y', strtotime($issue_date));
        	$billing["invoice_no"] = $invoice_number;
        	$billing["rate"] = $q["rate"];
        	$billing["amount"] = $q["amount"];
        	$billing["outstanding"] = $q["outstanding"];
        	$billing["status"] = $q["status"];
        	$billing["bank_info_id"] = $bank[0]["id"];

        	$this->db->insert("billing",$billing);
        	$insert_recurring_bill_id = $this->db->insert_id();

        	for($j = 0; $j < count($p); $j++)
			{
	        	$billing_service["billing_id"] = $insert_recurring_bill_id;
	        	$billing_service["service"] = $p[$j]["service"];
	        	$billing_service["invoice_date"] = date('d/m/Y', strtotime($issue_date));
	        	$billing_service["invoice_description"] = $p[$j]["invoice_description"];
	        	$billing_service["amount"] = $p[$j]["amount"];
	        	$billing_service["unit_pricing"] = $p[$j]["unit_pricing"];
	        	$billing_service["period_start_date"] = !(empty($p[$j]["period_start_date"]))? date('d/m/Y', str_replace('/', '-', strtotime($new_period_start_date))) : '';
	        	$billing_service["period_end_date"] = !(empty($p[$j]["period_end_date"]))? date('d/m/Y', str_replace('/', '-', strtotime($new_period_end_date))) : '';
	        	$billing_service["gst_rate"] = $p[$j]["gst_rate"];

	        	$this->db->insert("billing_service",$billing_service);
	        }

	        $this->load->helper("file");
			delete_files('./pdf/invoice/');
        }



   	}

   	public function write_header($firm_id, $use_own_header)
	{
		$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
												JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
												JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
												JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
												where firm.id = '".$firm_id."'");
		$query = $query->result_array();

		// Calling getimagesize() function 
		list($width, $height, $type, $attr) = getimagesize("uploads/logo/" . $query[0]["file_name"]); 

		$different_w_h = (float)$width - (float)$height;

		if((float)$width > (float)$height && $different_w_h > 100)
		{
			$td_width = 25;
			$td_height = 73.75;
		}
		else
		{
			$td_width = 15;
			$td_height = 83.75;
		}

		if(!$use_own_header){
			if(!empty($query[0]["file_name"]))
			{
				$img = '<img src="uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
			}
			else
			{
				$img = '';
			}
		}

		if(!$use_own_header)
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
		else
		{
			$header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
								<tbody>
								<tr style="height: 80px;"><td style="height: 60px;"></td></tr>
								</tbody>
							   </table>';
		}

		return $header_content;
	}

	public function receiver_info($document_type, $gst_number_display, $q, $invoice_number)
	{
		$address = $this->write_address(ucwords(strtolower($q["street_name"])), $q["unit_no1"], $q["unit_no2"], ucwords(strtolower($q['building_name'])), $q["postal_code"], 'letter with comma');
		$add_info = '';
		$description_title = '';

		// if($document_type == "invoice")
		// {
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q["recu_invoice_issue_date"])));
			$document_num  = $invoice_number;

			$description_title = "Description";
			$title_font_size = '22pt';

			$add_info = '<tr><td style="width: 73%; text-align:left;"><strong style="font-size: 10pt;">Bill To:</strong></td>' . $gst_number_display . '</tr>';
		// }
		// elseif($document_type == "receipt")
		// {
		// 	$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["receipt_date"])));
		// 	$document_num  = $q[0]["receipt_no"];

		// 	$title_font_size = '22pt';
		// }
		// elseif($document_type == "credit note")
		// {
		// 	$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["credit_note_date"])));
		// 	$document_num  = $q[0]["credit_note_no"];

		// 	$title_font_size = '17pt';
		// }

		$receiver_info = '<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
						<tbody>'
						.$add_info.
						'<tr>
						<td style="width: 64.7477%; font-weight: normal; font-size: 12px;">
						<table style="width: 107.191%; border-collapse: collapse; height: 81px;" border="0">
						<tbody>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$q["company_name"].'</span></strong></td>
						</tr>
						<tr style="height: 116px;">
						<td style="width: 100%; height: 41px; text-align: left;"><span style="font-size: 9pt; font-family: arial, helvetica, sans-serif;"><span style="font-family: arial, helvetica, sans-serif;">'. $address .'</span></span></td>
						</tr>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><span style="font-size: 9pt; font-family: arial, helvetica, sans-serif;">ATTN: Director / Finance Department</span></td>
						</tr>
						</tbody>
						</table>
						</td>
						<td style="width: 10.889%;">&nbsp;</td>
						<td style="width: 25.753%;">
						<table style="height: 34px; width: 100%; border-collapse: collapse;" border="0">
						<tbody>
						<tr style="height: 25px;">
						<td style="width: 93.077%; height: 34px;"><span style="text-align: left; font-size: '. $title_font_size .'; font-family: arial, helvetica, sans-serif;">'. strtoupper($document_type) .'</span></td>
						<td style="width: 6.92303%; text-align: right; height: 34px;"><span style="text-align: right; font-weight: normal; font-size: 20px;">&nbsp;</span></td>
						</tr>
						</tbody>
						</table>
						<table style="height: 72px; width: 99.1372%; border-collapse: collapse;" border="0">
						<tbody>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 9pt; font-weight: normal;">'. $document_num .'</span></td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 9pt; font-weight: normal;">'. $document_date .'</span></td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						<tr style="height: 18px;">
						<td style="width: 94.3111%; height: 18px; text-align: left;">&nbsp;</td>
						<td style="width: 27.8761%; text-align: left;">&nbsp;</td>
						</tr>
						</tbody>
						</table>
						</td>
						</tr>
						</tbody>
						</table>
						<hr style="height: 1px; border: none; color: #333; background-color: #333;" />
						<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt; height: 34px;" border="0">
						<tbody>
						<tr style="height: 17px;">
						<td style="width: 86.9273%; height: 17px;" colspan="2">&nbsp;</td>
						<td style="width: 6.68348%; text-align: center; height: 17px;">&nbsp;</td>
						</tr>
						<tr style="height: 17px;">
						<td style="width: 86.9273%; height: 17px;" colspan="2" align="left"><strong><span style="text-decoration: underline;">'.$description_title.'</span></strong></td>
						<td style="width: 15.5%; height: 17px;"><strong><span style="text-decoration: underline;">'. $q["currency"] .'</span></strong></td>
						</tr>
						</tbody>
					</table>
					';

		return $receiver_info;
	}

	public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
	{
		$unit = '';
		$unit_building_name = '';

		$comma = '';

		if($type == "normal")
		{
			$br = '';
		}
		elseif($type == "letter")
		{
			$br = ' <br/>';
		}
		elseif($type == "letter with comma")
		{
			$br = ' <br/>';
			$comma = ',';
		}
		elseif($type == "comma")
		{
			$br = ', ';
		}

		// Add unit
		if(!empty($unit_no1) && !empty($unit_no2))
		{
			$unit = '#' . $unit_no1 . '-' . $unit_no2 . $comma;
		}

		// Add building
		if(!empty($building_name) && !empty($unit))
		{
			$unit_building_name = $unit . ' ' . $building_name . $comma;
		}
		elseif(!empty($unit))
		{
			$unit_building_name = $unit;
		}
		elseif(!empty($building_name))
		{
			$unit_building_name = $building_name . $comma;
		}
		//print_r($street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code);
		return $street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code;
	}

   	public function spell_number_dollar($number){
    	$tempNum = explode( '.' , $number );
    	// $convertedNumber = "12345";
    	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

		$convertedNumber = ( isset( $tempNum[0] ) ? $f->format($tempNum[0]) . ' dollars' : '' );

		//  Use the below line if you don't want 'and' in the number before decimal point
		// $convertedNumber = str_replace( ' and ' ,' ' ,$convertedNumber );

		//  In the below line if you want you can replace ' and ' with ' , '
		$convertedNumber .= ( ( isset( $tempNum[0] ) and isset( $tempNum[1] ) )  ? ' and ' : '' );

		$convertedNumber .= ( isset( $tempNum[1] ) ? $f->format( $tempNum[1] ) .' cents' : '' );

		$convertedNumber .= " only";

		return ucfirst($convertedNumber);
	}

    //php index.php cron_billing check_recurring
//    	public function check_recurring()
//    	{
//    		$billing_info = $this->db->query("select client_billing_info.client_billing_info_id, client_billing_info.	service, client_billing_info.invoice_description, client_billing_info.company_code, client_billing_info.amount, client_billing_info.from, client_billing_info.to, client_billing_info.frequency as frequency_id, client_billing_info.type_of_day, client_billing_info.days, client_billing_info.from_billing_cycle, client_billing_info.to_billing_cycle, record_billing_recurring.id as billing_recurring_id, record_billing_recurring.client_billing_info_id as record_client_id, record_billing_recurring.company_code as record_company_code, record_billing_recurring.last_recurring_date, billing_info_frequency.frequency, type_of_day.type_of_day as type_of_day_name from client_billing_info left join record_billing_recurring on client_billing_info.client_billing_info_id = record_billing_recurring.client_billing_info_id AND client_billing_info.company_code = record_billing_recurring.company_code left join billing_info_frequency on billing_info_frequency.id = client_billing_info.frequency left join type_of_day on type_of_day.id = client_billing_info.type_of_day where client_billing_info.frequency != 1");

// 		$billing_info = $billing_info->result_array();

// /*		$billing_recurring = $this->db->query("select * from record_billing_recurring");
// 		$billing_recurring = $billing_recurring->result_array();

// 		$billing_frequency = $this->db->query("select * from billing_info_frequency");
// 		$billing_frequency = $billing_frequency->result_array();*/

// 		//echo json_encode($billing_info);
// 		for($i = 0; $i < count($billing_info); $i++)
// 		{
// 			$client = $this->db->query("select * from client where company_code='".$billing_info[$i]["company_code"]."' AND status = 1 AND deleted != 1");

// 			$client = $client->result_array();
// 			//echo json_encode($client[0]["auto_generate"]);
// 			// if($client[0]["auto_generate"] == 1)
// 			// {

// 				$firm = $this->db->query("select * from firm where id = '".$client[0]["firm_id"]."'");

// 				$firm = $firm->result_array();

// 				if($firm[0]["gst_checkbox"] == 1)
// 				{
// 					if($firm[0]["gst_date"] != null)
// 					{
// 						$array = explode('/', $firm[0]["gst_date"]);
// 						$tmp = $array[0];
// 						$array[0] = $array[1];
// 						$array[1] = $tmp;
// 						unset($tmp);
// 						$gst_date = implode('/', $array);
// 						$time = strtotime($gst_date);
// 						$gst_date = date('Y-m-d',$time);
// 		            	$gst_date = strtotime($gst_date);
// 					}

// 					if($firm[0]["previous_gst_date"] != null)
// 					{
// 						$array = explode('/', $firm[0]["previous_gst_date"]);
// 						$tmp = $array[0];
// 						$array[0] = $array[1];
// 						$array[1] = $tmp;
// 						unset($tmp);
// 						$previous_gst_date = implode('/', $array);
// 						$time = strtotime($previous_gst_date);
// 						$previous_gst_date = date('Y-m-d',$time);
// 		            	$previous_gst_date = strtotime($gst_date);
// 					}

// 					//echo json_encode($previous_gst_date > $gst_date);
// 					$invoice_date = DATE("Y-m-d",now());
// 					$invoice_date = strtotime($invoice_date);

// 					if($previous_gst_date == null && $gst_date != null)
// 					{
// 						if($invoice_date >= $gst_date)
// 						{
// 							$billing_service['gst_rate'] = $firm[0]["gst"];
// 						}
// 						else
// 						{
// 							$billing_service['gst_rate'] = 0;
// 						}
// 					}
// 					else
// 					{
// 						if($previous_gst_date == $gst_date)
// 						{
// 							$billing_service['gst_rate'] = $firm[0]["gst"];
// 						}
// 						else if($previous_gst_date > $gst_date)
// 						{
// 							if($previous_gst_date > $invoice_date && $invoice_date >= $gst_date)
// 							{
// 								$billing_service['gst_rate'] = $firm[0]["gst"];
// 							}
// 							else if($invoice_date >= $previous_gst_date)
// 							{
// 								$billing_service['gst_rate'] = $firm[0]["previous_gst"];
// 							}
// 							else
// 							{
// 								$billing_service['gst_rate'] = 0;
// 							}
// 						}
// 						else if($gst_date > $previous_gst_date)
// 						{
// 							if($gst_date > $invoice_date && $invoice_date >= $previous_gst_date)
// 							{
// 								$billing_service['gst_rate'] = $firm[0]["previous_gst"];
// 							}
// 							else if($invoice_date >= $gst_date)
// 							{
// 								$billing_service['gst_rate'] = $firm[0]["gst"];
// 							}
// 							else
// 							{
// 								$billing_service['gst_rate'] = 0;
// 							}
// 						}
// 					}
					
// 				}
// 				else
// 				{
// 					$billing_service['gst_rate'] = 0;
// 				}
				

			
// 				//echo json_encode($billing_info);
// 				//$frequency_name = "";

// 				$now = getDate();
// 				$current_date = DATE("Y-m-d",now());

// 				if($billing_info[$i]["from"] != null)
// 				{
// 					$date_from = str_replace('/', '-', $billing_info[$i]["from"]);
// 					$from = strtotime($date_from);
// 					$new_from = date('Y-m-d',$from);
// 					//echo ($new_to);
// 				}
// 				else
// 				{
// 					$new_from = null;
// 				}

// 				if($billing_info[$i]["to"] != null)
// 				{
// 					$date_to = str_replace('/', '-', $billing_info[$i]["to"]);
// 					$to = strtotime($date_to);
// 					$new_to = date('Y-m-d',$to);
// 					//echo ($new_to);
// 				}
// 				else
// 				{
// 					$new_to = null;
// 				}

// 				// if($billing_info[$i]["from_billing_cycle"] != null)
// 				// {
// 				// 	$date_from_billing_cycle = str_replace('/', '-', $billing_info[$i]["from_billing_cycle"]);
// 				// 	$from_billing_cycle = strtotime($date_from_billing_cycle);
// 				// 	//echo ($new_to);
// 				// 	if($billing_info[$i]["type_of_day"] == 1)
// 				// 	{
// 				// 		$new_from_billing_cycle = date('Y-m-d', strtotime('-'.$billing_info[$i]["days"].' days', $from_billing_cycle));
// 				// 	}
// 				// 	elseif($billing_info[$i]["type_of_day"] == 2)
// 				// 	{
// 				// 		$new_from_billing_cycle = date('Y-m-d', strtotime('+'.$billing_info[$i]["days"].' days', $from_billing_cycle));
// 				// 	}
// 				// 	else
// 				// 	{
// 				// 		$new_from_billing_cycle = date('Y-m-d',$from_billing_cycle);
// 				// 	}
					
// 				// 	$next_from_billing_cycle = new DateTime($new_from_billing_cycle);
// 				// 	// We extract the day of the month as $start_day

// 				// 	if($billing_info[$i]['frequency'] == "Monthly")
// 				// 	{
// 				// 		//$last_recurring_date = date("Y-m-d", strtotime("+1 month", $new_from));
// 				// 		$next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,1)->format(('Y-m-d'));
// 				// 		$next_billing_cycle = new DateTime($next_from_billing_cycle);
// 				// 		$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,1)->format(('Y-m-d'));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Quarterly")
// 				// 	{
// 				// 		//$last_recurring_date = date("Y-m-d", strtotime("+3 months", $new_from));
// 				// 		$next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,3)->format(('Y-m-d'));
// 				// 		$next_billing_cycle = new DateTime($next_from_billing_cycle);
// 				// 		$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,3)->format(('Y-m-d'));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Bi-annually")
// 				// 	{
// 				// 		//$last_recurring_date = date("Y-m-d", strtotime("+6 months", $new_from));
// 				// 		$next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,6)->format(('Y-m-d'));
// 				// 		$next_billing_cycle = new DateTime($next_from_billing_cycle);
// 				// 		$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,6)->format(('Y-m-d'));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Annually")
// 				// 	{
// 				// 		//$last_recurring_date = date("Y-m-d", strtotime("+1 year", $new_from));
// 				// 		$next_from_billing_cycle = $this->MonthShifter($next_from_billing_cycle,12)->format(('Y-m-d'));
// 				// 		$next_billing_cycle = new DateTime($next_from_billing_cycle);
// 				// 		$next_to_billing_cycle = $this->MonthShifter($next_billing_cycle,12)->format(('Y-m-d'));
// 				// 	}

				    
// 				// }
// 				// else
// 				// {
// 				// 	$next_from_billing_cycle = null;
// 				// }

// 				if($billing_info[$i]["to_billing_cycle"] != null)
// 				{
// 					$date_to_billing_cycle = str_replace('/', '-', $billing_info[$i]["to_billing_cycle"]);
// 					$to_billing_cycle = strtotime($date_to_billing_cycle);
// 					if($billing_info[$i]["type_of_day"] == 1)
// 					{
// 						$new_to_billing_cycle = date('Y-m-d', strtotime('-'.$billing_info[$i]["days"].' days', $to_billing_cycle));
// 					}
// 					elseif($billing_info[$i]["type_of_day"] == 2)
// 					{
// 						$new_to_billing_cycle = date('Y-m-d', strtotime('+'.$billing_info[$i]["days"].' days', $to_billing_cycle));
// 					}
// 					else
// 					{
// 						$new_to_billing_cycle = date('Y-m-d',$to_billing_cycle);
// 					}
					
// 					//echo ($new_to);
// 					$next_to_billing_cycle = new DateTime(date('Y-m-d', strtotime($date_to_billing_cycle)));
// 					// We extract the day of the month as $start_day
// 				    //$next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,1)->format(('Y-m-d'));
// 				    if($billing_info[$i]['frequency'] == "Monthly")
// 					{
// 						//$last_recurring_date = date("Y-m-d", strtotime("+1 month", $new_from));
// 						$next_from_billing_cycle = $next_to_billing_cycle;
// 						$next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,1)->format(('Y-m-d'));
// 					}
// 					elseif($billing_info[$i]['frequency'] == "Quarterly")
// 					{
// 						//$last_recurring_date = date("Y-m-d", strtotime("+3 months", $new_from));
// 						$next_from_billing_cycle = $next_to_billing_cycle;
// 						$next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,3)->format(('Y-m-d'));
// 					}
// 					elseif($billing_info[$i]['frequency'] == "Bi-annually")
// 					{
// 						//$last_recurring_date = date("Y-m-d", strtotime("+6 months", $new_from));
// 						$next_from_billing_cycle = $next_to_billing_cycle;
// 						$next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,6)->format(('Y-m-d'));
// 					}
// 					elseif($billing_info[$i]['frequency'] == "Annually")
// 					{
// 						//$last_recurring_date = date("Y-m-d", strtotime("+1 year", $new_from));
// 						$next_from_billing_cycle = $next_to_billing_cycle;
// 						$next_to_billing_cycle = $this->MonthShifter($next_to_billing_cycle,12)->format(('Y-m-d'));
// 					}
// 				}
// 				else
// 				{
// 					$next_to_billing_cycle = null;
// 				}

				
// 				//echo json_encode($next_from_billing_cycle == $current_date && ($next_from_billing_cycle != null && $next_from_billing_cycle >= $new_from) && ($next_to_billing_cycle != null && $next_to_billing_cycle <= $new_to));
// 				//echo json_encode($next_from_billing_cycle);
// 				// echo $next_to_billing_cycle;
// 				//echo json_encode($from);
// 				/*for($p = 0; $p < count($billing_frequency); $p++)
// 				{
// 					if($billing_frequency[$p]["id"] == $billing_info[$i]["frequency"])
// 					{
// 						$frequency_name = $billing_frequency[$p]["frequency"];
// 					}
// 				}*/
// 				//echo ($frequency_name);


// 				// if($billing_info[$i]['billing_recurring_id'] == null)
// 				// {
					
// 					//echo json_encode($last_recurring_date);
// 					//if($last_recurring_date == $current_date && ($new_to == null || $current_date < $new_to))
// 					if($next_from_billing_cycle == $current_date && ($new_from == null || ($next_from_billing_cycle != null && $next_from_billing_cycle >= $new_from)) && ($new_to == null || ($next_to_billing_cycle != null && $next_to_billing_cycle <= $new_to)))
// 					{
// 						$billing_result = $this->db->query("select * from billing where date_format(created_at, '%Y-%m-%d') = '".$current_date."' AND company_code='".$billing_info[$i]["company_code"]."' AND status != 1");

// 						$billing_result = $billing_result->result_array();

// 						//echo json_encode($billing_result);

// 						if($billing_result)
// 						{
// 							$billing['amount'] = $billing_result[0]['amount'] + $billing_info[$i]['amount'];
// 							$billing['outstanding'] = $billing_result[0]['outstanding'] + $billing_info[$i]['amount'];

// 							$this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

// 							$billing_service['billing_id'] = $billing_result[0]['id'];
// 						}
// 						else
// 						{
// 							$query_invoice_no = $this->db->query("select MAX(CAST(SUBSTRING(invoice_no,10, length(invoice_no)-9) AS UNSIGNED)) as invoice_no from billing");

// 	                        //echo json_encode($query_test);

// 	                        if ($query_invoice_no->num_rows() > 0) 
// 	                        {
// 	                            $query_invoice_no = $query_invoice_no->result_array();
// 	                            //$array_invoice_no = explode('-', $query_invoice_no[0]["invoice_no"]);
// 	           					$last_section_invoice_no = (int)$query_invoice_no[0]["invoice_no"] + 1;
// 	                            $number = date("Y")."-ABC-".$last_section_invoice_no;

// 	                        }
// 	                        else
// 	                        {
// 	                            $number = date("Y")."-ABC-1";
// 	                        }
// 							/*$number = sprintf('%02d', $now[0]);
// 							$number = 'INV - '.$number;*/
// 							$billing['invoice_no'] = $number;
// 							$billing['firm_id'] = $client[0]["firm_id"];
// 							$billing['company_code'] = $billing_info[$i]["company_code"];
// 							$billing['invoice_date'] = DATE("d/m/Y",now());
// 							$billing['amount'] = $billing_info[$i]['amount'];
// 							$billing['outstanding'] = $billing_info[$i]['amount'];
// 							$billing['rate'] = 1.0000;
// 							$billing['currency_id'] = 1;

// 							//$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];

// 							$this->db->insert("billing",$billing);
// 							$billing_service['billing_id'] = $this->db->insert_id();
// 						}

// 						$billing_service['service'] = $billing_info[$i]['service'];
// 						$billing_service['invoice_date'] = DATE("d/m/Y",now());
// 						//$billing_service['client_billing_info_id'] = $billing_info[$i]['client_billing_info_id'];
// 						$billing_service['invoice_description'] = $billing_info[$i]['invoice_description'];
// 						$billing_service['amount'] = $billing_info[$i]['amount'];

// 						$this->db->insert("billing_service",$billing_service);

// 						// $recurring["last_recurring_date"] = DATE("d-m-Y",strtotime($last_recurring_date));

// 						// $recurring["client_billing_info_id"] = $billing_info[$i]['client_billing_info_id'];
// 						// $recurring["company_code"] = $billing_info[$i]["company_code"];
// 						// $this->db->insert("record_billing_recurring",$recurring);
// 						$client_billing_info['from_billing_cycle'] = date("d/m/Y", strtotime($next_from_billing_cycle));
// 						$client_billing_info['to_billing_cycle'] = date("d/m/Y", strtotime($next_to_billing_cycle));

// 						$this->db->update("client_billing_info",$client_billing_info,array("client_billing_info_id" => $billing_info[$i]['client_billing_info_id']));
// 					}
// 				// }
// 				// else
// 				// {
// 				// 	if($billing_info[$i]['frequency'] == "Monthly")
// 				// 	{
// 				// 		$new_last_recurring_date = strtotime( $billing_info[$i]["last_recurring_date"]);
// 				// 		$new_last_recurring_date = date("Y-m-d", strtotime("+1 month", $new_last_recurring_date));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Quarterly")
// 				// 	{
// 				// 		$new_last_recurring_date = strtotime( $billing_info[$i]["last_recurring_date"]);
// 				// 		$new_last_recurring_date = date("Y-m-d", strtotime("+3 months", $new_last_recurring_date));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Bi-annually")
// 				// 	{
// 				// 		$new_last_recurring_date = strtotime( $billing_info[$i]["last_recurring_date"]);
// 				// 		$new_last_recurring_date = date("Y-m-d", strtotime("+6 months", $new_last_recurring_date));
// 				// 		//$new_last_recurring_date = date("Y-m-d", strtotime("+1 day", $new_last_recurring_date));
// 				// 	}
// 				// 	elseif($billing_info[$i]['frequency'] == "Annually")
// 				// 	{
// 				// 		$new_last_recurring_date = strtotime( $billing_info[$i]["last_recurring_date"]);
// 				// 		$new_last_recurring_date = date("Y-m-d", strtotime("+1 year", $new_last_recurring_date));
// 				// 		//$new_last_recurring_date = date("Y-m-d", strtotime("+0 day", $new_last_recurring_date));
// 				// 	}
// 				// 	//echo json_encode($new_last_recurring_date);
// 				// 	if($new_last_recurring_date == $current_date && ($new_to == null || $current_date < $new_to))
// 				// 	{
// 				// 		$billing_result = $this->db->query("select * from billing where date_format(created_at, '%Y-%m-%d') = '".$current_date."' AND company_code='".$billing_info[$i]["company_code"]."' AND status != 1");

// 				// 		$billing_result = $billing_result->result_array();

// 				// 		//echo json_encode($billing_result);

// 				// 		if($billing_result)
// 				// 		{
// 				// 			$billing['amount'] = $billing_result[0]['amount'] + $billing_info[$i]['amount'];
// 				// 			$billing['outstanding'] = $billing_result[0]['outstanding'] + $billing_info[$i]['amount'];

// 				// 			$this->db->update("billing",$billing,array("id" => $billing_result[0]['id']));

// 				// 			$billing_service['billing_id'] = $billing_result[0]['id'];
// 				// 		}
// 				// 		else
// 				// 		{
// 				// 			$number = sprintf('%02d', $now[0]);
// 				// 			$number = 'INV - '.$number;
// 				// 			$billing['invoice_no'] = $number;

// 				// 			$billing['company_code'] = $billing_info[$i]["company_code"];
// 				// 			$billing['invoice_date'] = DATE("d/m/Y",now());
// 				// 			$billing['amount'] = $billing_info[$i]['amount'];
// 				// 			$billing['outstanding'] = $billing_info[$i]['amount'];
// 				// 			$billing['rate'] = 1.0000;
// 				// 			$billing['currency_id'] = 1;

// 				// 			//$billing_service['client_billing_info_id'] = $result[0]['client_billing_info_id'];

// 				// 			$this->db->insert("billing",$billing);
// 				// 			$billing_service['billing_id'] = $this->db->insert_id();
// 				// 		}

// 				// 		$billing_service['invoice_date'] = DATE("d/m/Y",now());
// 				// 		$billing_service['client_billing_info_id'] = $billing_info[$i]['client_billing_info_id'];
// 				// 		$billing_service['invoice_description'] = $billing_info[$i]['invoice_description'];
// 				// 		$billing_service['amount'] = $billing_info[$i]['amount'];

// 				// 		$this->db->insert("billing_service",$billing_service);

// 				// 		$recurring["last_recurring_date"] = DATE("d-m-Y",strtotime($new_last_recurring_date));

// 				// 		$this->db->update("record_billing_recurring",$recurring,array("id" => $billing_info[$i]['billing_recurring_id']));
// 				// 	}
						
// 				// }
// 			//}
// 		}
//    	}

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

    //Page header
    public function Header() {
		$headerData = $this->getHeaderData();
        $this->SetFont('helvetica', 'B', 23);
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
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
        	$this->SetFont('helvetica', '', 8);
        	$this->Cell(0, 10, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
        
        if(!$this->last_page_flag){
	        $this->SetFont('helvetica', 'I', 8);
	        $this->Cell(0, 10, 'continue to the next page...', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }

        $this->total_page++;
   }
} -->