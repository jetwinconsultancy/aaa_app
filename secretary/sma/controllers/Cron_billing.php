<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class Cron_billing extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'World')
    {
        //echo "Hello {$to}!".PHP_EOL; 

        $this->load->library('encryption');
        // $pass = $this->encrypt->encode("5XOUtGKpCiR_");
        // echo json_encode($pass);

        // echo json_encode($this->encrypt->decode($pass));
        //pop.gmail.com
        //contact@me.com
        //jEFTM4T63AiQ9dsidxhPKt9CIg4HQjCN58n/RW9vmdC/UDXCzRLR469ziZ0jjpFlbOg43LyoSmpJLBkcAHh0Yw==




        // $from_billing_cycle = "01/09/2018";
        // $to_billing_cycle = "31/10/2018";

        // if($to_billing_cycle != null)
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



    public function resendupdate() {
    	echo "updates1ssasasdasdsads asas";
    }

    public function resend() 
   	{
   		$this->load->library('encryption');
   		$now = getDate();
		$current_date = DATE("Y-m-d",now());
		$number_of_invoice = 0;
   		//$current_date = '2020-03-01'; //16

   		// $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id = 264");

   		// $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id IN('71','98','356','349','318','88','110','141','338','329','58','76','95','119','343','325','63','81','102','129','361','348','321','70','87','107','138','336','355','312','116','94','118','342','324','62','80','100','128','313','347','320','69','86','106','136','335','354','315','74','93','114','341','326','61','79','125','346','319','68','115','105','135','334','352','316','73','92','113','340','327','60','78','97','122','345','322','67','83','104','134','333','350','317','72','89','112','142','339','328','59','77','96','120','344','323','64','82','103','130')");
		   $q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00' and recurring_billing.id IN('".$_POST["id"]."')");

   		 //send on = 17/1 // AND recurring_billing.recu_invoice_issue_date = '01/02/2020' // AND recurring_billing.firm_id = '18' // AND recurring_billing.firm_id = 21//  recurring_billing.recu_invoice_issue_date = '22/07/2020' AND //  AND recurring_billing.recu_invoice_issue_date = '01/01/2021' LIMIT 10

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
				//print_r($new_invoice_issue_date);
				///print_r(' Current Date: '.strtotime($current_date));
				//print_r(' Issue Date: '.strtotime($new_invoice_issue_date));
				//echo($new_invoice_issue_date == "2020-01-01");


				if(strtotime($current_date) >= strtotime($new_invoice_issue_date) && 100 > $number_of_invoice)
				{
					$q[$t]["company_name"] = $this->encryption->decrypt($q[$t]["company_name"]);
					$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $new_invoice_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]); //$new_invoice_issue_date
					$number_of_invoice = $number_of_invoice + 1;
				}


				//print_r($q[$t]["company_name"]);
				// else 
				// {
				// 	if($q[$t]["billing_period"] == 2)
				// 	{
				// 		$after_one_month_issue_date = $this->MonthShifter($invoice_issue_date_time,1)->format(('Y-m-d'));
				// 		if($after_one_month_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_month_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 3)
				// 	{
				// 		$after_quarter_year_issue_date = $this->MonthShifter($invoice_issue_date_time,3)->format(('Y-m-d'));
				// 		if($after_quarter_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_quarter_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 4)
				// 	{
				// 		$after_half_year_issue_date = $this->MonthShifter($invoice_issue_date_time,6)->format(('Y-m-d'));
				// 		if($after_half_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_half_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 5)
				// 	{
				// 		$after_one_year_issue_date = $this->MonthShifter($invoice_issue_date_time,12)->format(('Y-m-d'));
				// 		if($after_one_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// }
			}
		}
		
   	}

    public function check_recurring_bill()
   	{
   		$this->load->library('encryption');
   		$now = getDate();
		$current_date = DATE("Y-m-d",now());
		$number_of_invoice = 0;
   		//$current_date = '2020-03-01'; //16

   		$q = $this->db->query("select recurring_billing.firm_id, recurring_billing.id, recurring_billing.company_code, recurring_billing.invoice_date, recurring_billing.invoice_no, recurring_billing.currency_id, recurring_billing.amount, recurring_billing.rate, recurring_billing.outstanding, recurring_billing.status, recurring_billing.recurring_status, recurring_billing.billing_period, recurring_billing.recu_invoice_issue_date, client.acquried_by, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, client.foreign_add_1, client.foreign_add_2, client.foreign_add_3, client.use_foreign_add_as_billing_add, currency.currency from recurring_billing left join client on client.company_code = recurring_billing.company_code left join currency on currency.id = recurring_billing.currency_id where client.acquried_by = '1' AND client.deleted != '1' AND client.status = '1' AND recurring_billing.status = '0' AND recurring_billing.amount != '0.00'"); //send on = 17/1 // AND recurring_billing.recu_invoice_issue_date = '01/02/2020' // AND recurring_billing.firm_id = '18' // AND recurring_billing.firm_id = 21//  recurring_billing.recu_invoice_issue_date = '22/07/2020' AND //  AND recurring_billing.recu_invoice_issue_date = '01/01/2021' LIMIT 10

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
				//print_r($new_invoice_issue_date);
				///print_r(' Current Date: '.strtotime($current_date));
				//print_r(' Issue Date: '.strtotime($new_invoice_issue_date));
				//echo($new_invoice_issue_date == "2020-01-01");


				if(strtotime($current_date) >= strtotime($new_invoice_issue_date) && 100 > $number_of_invoice)
				{
					$q[$t]["company_name"] = $this->encryption->decrypt($q[$t]["company_name"]);
					$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $new_invoice_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]); //$new_invoice_issue_date
					$number_of_invoice = $number_of_invoice + 1;
				}


				//print_r($q[$t]["company_name"]);
				// else 
				// {
				// 	if($q[$t]["billing_period"] == 2)
				// 	{
				// 		$after_one_month_issue_date = $this->MonthShifter($invoice_issue_date_time,1)->format(('Y-m-d'));
				// 		if($after_one_month_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_month_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 3)
				// 	{
				// 		$after_quarter_year_issue_date = $this->MonthShifter($invoice_issue_date_time,3)->format(('Y-m-d'));
				// 		if($after_quarter_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_quarter_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 4)
				// 	{
				// 		$after_half_year_issue_date = $this->MonthShifter($invoice_issue_date_time,6)->format(('Y-m-d'));
				// 		if($after_half_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_half_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// 	else if($q[$t]["billing_period"] == 5)
				// 	{
				// 		$after_one_year_issue_date = $this->MonthShifter($invoice_issue_date_time,12)->format(('Y-m-d'));
				// 		if($after_one_year_issue_date == $current_date)
				// 		{
				// 			$this->send_recurring_bill($q[$t], $q[$t]["id"], $q[$t]["firm_id"], $after_one_year_issue_date, $q[$t]["company_code"], $q[$t]["company_name"], $q[$t]["currency"], $q[$t]["billing_period"]);
				// 		}
				// 	}
				// }
			}
		}
		
   	}

   	public function send_recurring_bill($q, $recurring_billing_id, $firm_id, $issue_date, $company_code, $company_name, $currency, $billing_period)
   	{	
   		// echo json_encode($content);
   		// echo json_encode($company_code);
   		// echo json_encode($company_name);
        $this->load->library('parser');
        $got_gst = false;

        $check_gst_status_query = $this->db->query("SELECT gst_firm.*, firm.jurisdiction_id, firm.gst_checkbox FROM gst_firm LEFT JOIN firm ON firm.id = gst_firm.firm_id WHERE gst_firm.register_date <= CURRENT_DATE() AND (gst_firm.deregister_date >= CURRENT_DATE() OR gst_firm.deregister_date IS NULL) AND gst_firm.firm_id = '".$firm_id."'");

        if ($check_gst_status_query->num_rows() > 0) 
        {
        	$got_gst = true;
        	$check_gst_status_array = $check_gst_status_query->result_array();

        	$gst_attribute = ", gst_category_info.rate as gst_category_info_rate, gst_category_info.gst_category_id";
        	$gst_where = "LEFT JOIN our_service_gst ON our_service_gst.our_service_info_id = client_billing_info.service and our_service_gst.jurisdiction_id = '".$check_gst_status_array[0]["jurisdiction_id"]."' 
                LEFT JOIN gst_category_info ON gst_category_info.deleted = 0 AND gst_category_info.id = our_service_gst.category_id AND gst_category_info.start_date <= CURRENT_DATE() AND (gst_category_info.end_date >= CURRENT_DATE() OR gst_category_info.end_date IS NULL)";
        }
        else
        {
        	$got_gst = false;
        	$gst_attribute = "";
        	$gst_where = "";
        }

        $p = $this->db->query("select recurring_billing.id, recurring_billing.company_code, recurring_billing.own_letterhead_checkbox, recurring_billing_service.id as recurring_billing_service_id, recurring_billing_service.billing_id as billing_service_billing_id, recurring_billing_service.invoice_description, recurring_billing_service.amount, recurring_billing_service.service, recurring_billing_service.unit_pricing, recurring_billing_service.gst_rate, recurring_billing_service.period_start_date, recurring_billing_service.period_end_date, client_billing_info.service as client_billing_info_service, recurring_billing_service.gst_new_way".$gst_attribute."
        	FROM recurring_billing 
        	LEFT JOIN recurring_billing_service ON recurring_billing_service.billing_id = recurring_billing.id 
        	LEFT JOIN client_billing_info ON client_billing_info.id = recurring_billing_service.service
        	".$gst_where." 
        	where recurring_billing.id =".$recurring_billing_id." ORDER BY recurring_billing_service.id");

       	$p = $p->result_array();

       	$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax,
       								currency.currency as currency_name from firm 
									LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
									LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
									LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
									LEFT JOIN currency ON currency.id = firm.firm_currency
									where firm.id = '".$firm_id."'");

		$query = $query->result_array();

		// $query_invoice_no = $this->db->query("SELECT invoice_no FROM billing where id = (SELECT max(id) FROM billing where status = '0' and firm_id = '".$firm_id."')");
        //$id = $query->row()->id;
        if($firm_id == 18 || $firm_id == 26)
        {
            $where = '(firm_id = 18 or firm_id = 26)';
        }
        else
        {
            $where = "firm_id = '".$firm_id."'";
        }

        $current_year = date("Y");

        $query_invoice_no = $this->db->query("select id, invoice_no, MAX(CAST(SUBSTRING(invoice_no, -4) AS UNSIGNED)) as latest_invoice_no from billing where YEAR(STR_TO_DATE(invoice_date,'%d/%m/%Y')) = ".$current_year." and status = '0' and ".$where." GROUP BY invoice_no ORDER BY latest_invoice_no DESC LIMIT 1");

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
            if($firm_id == 6)
            {
                $invoice_number = "AB-".date("Y")."S-0001";
            }
            elseif($firm_id == 7)
            {
                $invoice_number = "AG-".date("Y")."0001";
            }
            elseif($firm_id == 8)
            {
                $invoice_number = "SC-".date("Y")."S-0001";
            }
            elseif($firm_id == 9)
            {
                $invoice_number = "VC-".date("Y")."-0001";
            }
            elseif($firm_id == 15)
            {
                $invoice_number = "SYA-".date("Y")."0001";
            }
            elseif($firm_id == 16)
            {
                $invoice_number = "AA-".date("Y")."0001";
            }
            elseif($firm_id == 17)
            {
                $invoice_number = "AALLP-".date("Y")."-0001";
            }
            elseif($firm_id == 18 || $firm_id == 26)
            {
                $invoice_number = "AAA-".date("Y")."-0001";
            }
            elseif($firm_id == 21)
            {
                $invoice_number = "AN-".date("Y")."-0001";
            }
            elseif($firm_id == 23)
            {
                $invoice_number = "ACT-".date("Y")."-0001";
            }
            elseif($firm_id == 27)
            {
                $invoice_number = "TUH-".date("Y")."-0001";
            }
            elseif($firm_id == 28)
            {
                $invoice_number = "H-".date("Y")."-0001";
            }
            elseif($firm_id == 29)
            {
                $invoice_number = "146-".date("Y")."-0001";
            }
            else
            {
                $invoice_number = "AAA-".date("Y")."-".str_pad(1,4,"0",STR_PAD_LEFT);
            }
        }

        $this->load->helper('pdf_helper');

        // create merge PDF document
		//$merge_pdf_link = $this->get_merge_doc($q, $p);
        // -------------------------

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

		// gst number display
		//if(!(empty($p[0]['gst_rate']) || ($p[0]['gst_rate'] == 0)))
		//$gst_new_way = $p[0]['gst_new_way'];
		if(!(empty($p[0]['gst_category_info_rate']) || ($p[0]['gst_category_info_rate'] == 0)) || $got_gst)
		{
			$gst_uen_display = 'UEN and GST Reg. No.';
			$gst_number_display = '<td style="width: 25%; text-align:left; font-size: 8pt; font-weight:normal;">GST Reg. No. : '. $query[0]['registration_no'] .'</td>';
		}
		else
		{
			$gst_uen_display = 'UEN';
			$gst_number_display = '';
		}

		$header_company_info = $this->write_header($q["firm_id"], $own_header, $gst_uen_display);

		$receiver_info = $this->receiver_info("invoice", $gst_number_display, $q, $invoice_number, $query[0]['gst_checkbox']);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
		$header_company_info.
		$receiver_info,
		$tc=array(0,0,0), $lc=array(0,0,0));

		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		if(40 > strlen($query[0]['name']))
		{
			$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+58, PDF_MARGIN_RIGHT+3);
		}
		else
		{
			$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+64, PDF_MARGIN_RIGHT+3);
		}
		
		$obj_pdf->SetFont('helvetica', '', 10);
		$obj_pdf->setFontSubsetting(false);
		$obj_pdf->AddPage();
		$content = '';
		$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
		$table_content_end = '</tbody></table>';
		$sub_total = 0;
		$gst = 0;
		$gst_rate = $p[0]['gst_rate'];
		$gst_checkbox = $query[0]['gst_checkbox'];
		$sumArray = array();

		$content .= $table_content_start;

		// to calculate total amount of gst (not for display) DO NOT DELETE THIS
		for($z = 0; $z < count($p); $z++)
		{	
			// if($p[$z]["client_billing_info_service"] == 3)
			// {
			// 	$agreement_pdf_link = $this->get_secretarial_doc($q, $p, $p[$z]['amount'], $billing_period);
			// }

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
				if(!empty($p[$z]["period_start_date"]))
				{
					$format_period_start_date = str_replace('/', '-', $p[$z]["period_start_date"]);
					$format_period_end_date = str_replace('/', '-', $p[$z]["period_end_date"]);
					$time_period_start_date = strtotime($format_period_start_date);
					$new_format_period_start_date = date('Y-m-d',$time_period_start_date);
					$period_start_date_time = new DateTime($new_format_period_start_date);
					//$new_period_start_date = $this->MonthShifter($period_start_date_time,$how_many_month)->format(('m/d/Y'));
					$new_period_start_date = date('m/d/Y', strtotime('+ 1 days', strtotime($format_period_end_date)));
				}
				else
				{
					$new_period_start_date = "";
				}

				if(!empty($p[$z]["period_end_date"]))
				{
					$format_period_end_date = str_replace('/', '-', $p[$z]["period_end_date"]);
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
				$new_period_start_date = $p[$z]["period_start_date"];
				$new_period_end_date = $p[$z]["period_end_date"];
				$new_issue_date = $issue_date;
			}
			//echo json_encode($how_many_month);
			

			if(!empty($new_period_start_date))//!empty($p[$j]["period_start_date"]) || 
			{
				$new_format_period_start_date = date('d/m/Y', str_replace('/', '-',strtotime($new_period_start_date)));
			}
			else
			{
				$new_format_period_start_date = "";
				//$period_start_date = "";
			}

			if(!empty($new_period_end_date))//!empty($p[$j]["period_start_date"]) || 
			{
				$new_format_period_end_date = date('d/m/Y', str_replace('/', '-',strtotime($new_period_end_date)));
			}
			else
			{
				$new_format_period_end_date = "";
				//$period_end_date = "";
			}

			//update recurring billing service date
			$recurring_billing_service_data = array(
		        'period_start_date' => $new_format_period_start_date,
		        'period_end_date' => $new_format_period_end_date
			);

			$this->db->where('id', $p[$z]["recurring_billing_service_id"]);
			$this->db->update('recurring_billing_service', $recurring_billing_service_data);
			//-------------------

			$period_start_date = !(empty($p[$z]["period_start_date"]))? ' from ' . date('d F Y', strtotime(str_replace('/', '-', $p[$z]["period_start_date"]))) : '';

			$period_end_date = !(empty($p[$z]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$z]["period_end_date"]))) . '.' : '';

			$amount = $p[$z]['amount'];

			if($amount < 0)
			{
				$amount = $amount * -1;
				$amount = '(' . number_format($amount, 2) . ')';
			}
			else
			{
				$amount = number_format($amount, 2);
			}

			if(strpos($p[$z]['invoice_description'], '{big_cap_month}') !== false)
			{
				$p[$z]['invoice_description'] = str_replace('{big_cap_month}', strtoupper(date("F")), $p[$z]['invoice_description']);
			}
			if(strpos($p[$z]['invoice_description'], '{first_letter_big_cap_month}') !== false)
			{
				$p[$z]['invoice_description'] = str_replace('{first_letter_big_cap_month}', date("F"), $p[$z]['invoice_description']);
			}
			if(strpos($p[$z]['invoice_description'], '{first_letter_big_cap_previous_month}') !== false)
			{
				$previous_month = str_replace('{first_letter_big_cap_previous_month}', date("F", strtotime("-1 months")), $p[$z]['invoice_description']);

				$p[$z]['invoice_description'] = $previous_month;

				if($previous_month == "December")
				{
					$p[$z]['invoice_description'] = str_replace('{year}', date("Y", strtotime("-1 years")), $p[$z]['invoice_description']);
				}
			}
			if(strpos($p[$z]['invoice_description'], '{year}') !== false)
			{
				$p[$z]['invoice_description'] = str_replace('{year}', date("Y"), $p[$z]['invoice_description']);
			}

			// if($gst_checkbox == 0)
			// {
				$gst_content = '<td style="width: 86.9273%; text-align: left;">
									<p style="text-align: justify;">'. nl2br($p[$z]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
									</td>';
			// }
			// else
			// {
			// 	$gst_content = '<td style="width: 78.9273%;%; text-align: left;">
			// 						<p style="text-align: justify;">'. nl2br($p[$z]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
			// 						</td>
			// 						<td style="width: 8%; text-align: right;">'.$p[$z]['gst_rate'].'</td>';
			// }

			$table_part_content = '<tr style="height: 17px;">'.
									$gst_content.
									'<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
								  </tr>';

			// $table_part_content = '<tr style="height: 17px;">
			// 						<td style="width: 86.9273%;%; text-align: left;">
			// 						<p style="text-align: justify;">'. nl2br($p[$z]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
			// 						</td>
			// 						<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
			// 					  </tr>';

			$content = $content.$table_part_content;

			$sub_total += (float)$p[$z]['amount'];

			$gst += round((($p[$z]['gst_rate'] / 100) * (float)$p[$z]['amount']), 2);

			if($gst_checkbox != 0)
			{
				$sumArray[$p[$z]['gst_rate']] = (isset($sumArray[$p[$z]['gst_rate']]) ? $sumArray[$p[$z]['gst_rate']] + (float)$p[$z]['amount'] : (float)$p[$z]['amount']);
			}
		}
		//print_r($sumArray);
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

			$period_end_date 	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) . '.' : '';

			// if($gst_checkbox == 0)
			// {
				$gst_content = '<td style="width: 86.9273%;%; text-align: left;">
									<p style="text-align: justify;">'. nl2br($p[$j]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
									</td>';
			// }
			// else
			// {
			// 	$gst_content = '<td style="width: 78.9273%;%; text-align: left;">
			// 						<p style="text-align: justify;">'. nl2br($p[$j]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
			// 						</td>
			// 						<td style="width: 8%; text-align: right;">'.$p[$j]['gst_rate'].'</td>';
			// }

			$table_part_content = '<tr style="height: 17px;">'.
									$gst_content.
									'<td style="width: 13.5%; text-align: right;">'.$amount.'</td>
									</tr>';		
			// $table_part_content = '<tr style="height: 17px;">
			// 						<td style="width: 86.9273%;%; text-align: left;">
			// 						<p style="text-align: justify;">'. nl2br($p[$j]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
			// 						</td>
			// 						<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
			// 						</tr>';

			$current_content .= $table_part_content;	// content without table tag

			$test_content_height = $obj_pdf->getStringHeight(1000, $table_content_start . $current_content . $table_content_end);
			$obj_pdf->writeHTML($table_content_start . $table_part_content . $table_content_end, true, false, false, false, '');

			if($obj_pdf->getY() >= 190  && $test_content_height == $table_content_height){ // for one page only
				
				$obj_pdf->AddPage();
			}
		}
		
		$obj_pdf->SetY(-50);
			        
		$obj_pdf->SetFont('helvetica', '', 10);
		$obj_pdf->SetXY(17, 183);

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
					<td style="width: 67%;"><span style="font-size: 8pt;">'. $query[0]["currency_name"] .': '. $this->spell_number_dollar(sprintf('%0.2f', $converted_total)) .'</span></td>
					<td style="width: 23%;">Equivalent To: </td>
					<td style="width: 20%; text-align: right;">
					<div style="text-align: right;"><strong>' . number_format($converted_total, 2) . '</strong></div>
					<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
					</td>
					</tr>';
		}
		else
		{
			$show_converted_total = "";
		}

		$total_gst = "";
		$total_not_gst = "";
		$notSubjectToCharges = "";
		$total_gst_line = 0;
		$sub_gst = 0;
		$forPaynow_Y_value = -90;

		if(!(empty($p[0]['gst_category_info_rate']) || ($p[0]['gst_category_info_rate'] == 0)) || $got_gst)
		{
			if(count($sumArray) > 0)
			{
				foreach($sumArray as $x => $val){
					if($x != 0)
					{
						$sub_gst = round((($x / 100) * (float)$val), 2);
						if($total_gst_line == 0)
						{
							if($q['rate'] != 1)
							{
								$converted_gst = $gst * $q['rate'];
								$show_converted_gst = "GST ".$x."% (".$query[0]["currency_name"]."): ".number_format($converted_gst,2);
							}
							else
							{
								$show_converted_gst = "&nbsp;";
							}

							$total_gst = '<tr style="height: 17px;">
								<td style="width: 57%; height: 17px;">'.$show_converted_gst.'</td><td style="width: 23%; height: 17px;">GST: '.number_format($val,2).' @ '.$x.'%</td>'
								.'<td style="width: 20%; height: 17px; text-align: right;">'. number_format($gst,2) .'</td></tr>';
						}
						else
						{
							if($q['rate'] != 1)
							{
								$converted_gst = $gst * $q['rate'];
								$show_converted_gst = "GST ".$x."% (".$query[0]["currency_name"]."): ".number_format($converted_gst,2);
							}
							else
							{
								$show_converted_gst = "&nbsp;";
							}
							
							$total_gst = $total_gst.'<tr style="height: 17px;">
								<td style="width: 62.4%; height: 17px;">'.$show_converted_gst.'</td><td style="width: 17.6%; height: 17px;">'.number_format($val,2).' @ '.$x.'%</td>'
								.'<td style="width: 20%; height: 17px; text-align: right;">'. number_format($gst,2) .'</td></tr>';
							
							// $total_gst = $total_gst.'<tr style="height: 17px;">
							// 	<td style="width: 63%; height: 17px;">&nbsp;</td><td style="width: 17%; height: 17px;">Charges Not Subjected to GST '.number_format($val,2).'</td>'
							// 	.'<td style="width: 20%; height: 17px; text-align: right;"></td></tr>';
						}
						$total_gst_line++;
					}
					else
					{
						$notSubjectToCharges = "Charges Not Subjected to GST: ".number_format($val,2);
						$total_not_gst = '<tr style="height: 17px;">
								<td style="width: 57%; height: 17px;">&nbsp;</td><td style="width: 23%; height: 17px;">GST: </td>'
								.'<td style="width: 20%; height: 17px; text-align: right;">0.00</td></tr>';
					}
				}
			}
		}
		else
		{
			$notSubjectToCharges = "";
		}

		if($q['rate'] != 1)
		{
			$forPaynow_Y_value = -85;
		}
		else if($total_gst_line == 2)
		{
			$forPaynow_Y_value = -83;
		}

		if($total_gst != "")
		{
			$gst_sentences = $total_gst;
		}
		else if($total_not_gst != "")
		{
			$gst_sentences = $total_not_gst;
		}
		
		$content_gst  = '<table style="width: 100%; border-collapse: collapse; height: 34px;" border="0">
						<tbody>
						<tr style="height: 17px;">
						<td style="width: 57%; height: 17px;">'.$notSubjectToCharges.'&nbsp;</td>
						<td style="width: 23%; height: 17px;">Subtotal</td>
						<td style="width: 20%; height: 17px; text-align: right;"><strong>'. number_format($sub_total,2) .'</strong></td>
						</tr>'
						//.'<td style="width: 17%; height: 17px;">GST '. $gst_rate .'%</td>'
						. $gst_sentences .'
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
					<td style="width: 57%;"><span style="font-size: 8pt;">'. $q["currency"] .': '. $this->spell_number_dollar(sprintf('%0.2f', $total)) .'</span></td>
					<td style="width: 23%;">Total</td>
					<td style="width: 20%; text-align: right;">
					<div style="text-align: right;"><strong>'. number_format($total,2) .'</strong></div>
					<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
					</td>
					</tr>'. 
					$show_converted_total
					.'</tbody>
					</table>';

		if($gst_checkbox == 0){
			$content2 .= $content3;
		}else{
			$content2 .= $content_gst . $content3;
		}

		$billing_bank_info["bank_info_id"] = $bank[0]["id"];
		$this->db->update("billing",$billing_bank_info,array("id" => $q["id"]));

		$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->writeHTML($content2, true, false, false, false, '');

		if($bank[0]["qr_code"] != "")
		{
			if(count(json_decode($bank[0]["qr_code"])) > 0)
			{
				$qr_code_info = json_decode($bank[0]["qr_code"]);
				// $qr_code = '<td style="width: 12.5316%;" rowspan="3"> 
				// 				<p style="text-align: center;"><span style="font-size: 8pt;"><em>for quick transfer</em><br /></span><span style="font-size: 8pt;"><img style="width: 59px; height: 59px;" src="uploads/billing_qr_code/'.$qr_code_info[0].'" /></span></p>
				// 			</td>';
				// $qr_code = 	'<td style="width: 12.5316%; text-align: center; vertical-align: bottom;">
				// 				<br /><br /><span style="font-size: 8pt;"><em>for quick transfer</em><br /></span><span style="font-size: 8pt;"><img style="width: 69px; height: 69px;" src="uploads/billing_qr_code/'.$qr_code_info[0].'" /></span>
				// 			</td>';
				$qr_code = 	'<td style="width: 19.5316%; text-align: right; vertical-align: top;" rowspan="3">
											<br /><br /><img style="width: 60px; height: 60px;" src="uploads/billing_qr_code/'.$qr_code_info[0].'" />
										</td>';
			}
			else
			{
				$qr_code = '';
			}
		}
		else
		{
			$qr_code = '';
		}

		$display_bank_info = '<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 80.4684%;"><br/><p style="font-size: 8pt;"> Please make a cheque payable to <strong>'.$query[0]["name"].'&nbsp;</strong>or remit to:</p></td>'.
									$qr_code.
								'</tr>
								<tr>
								<td style="width: 80.4684%;">
								<table style="width: 100.025%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 100%;">
								<p><span style="font-size: 8pt;">Banker: <strong>'.$bank[0]["banker"].'<br /></strong>Account Number:<strong>&nbsp;'.$bank[0]["account_number"].'&nbsp; &nbsp;</strong>Bank code:<strong> '.$bank[0]["bank_code"].'&nbsp; &nbsp;</strong>Swift code:<strong> '.$bank[0]["swift_code"].'</strong></span>&nbsp;</p>
								</td>'.
								//$qr_code.
								'</tr>
								</tbody>
								</table>
								</td>'.
								//$qr_code.
								'</tr>
								<tr>
								<td style="width: 100%;">
								<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td style="width: 90.4684%;">
								<p style="font-size: 8pt;"><br/>* For wire transfer, payer shall bear all local and oversea bank charges.<br /><span style="font-size: 8pt;">* Please email the remittance advice to '. $query[0]["email"] .'.</span><br /><span style="font-size: 8pt;">* Please write the invoice reference number on the back of the cheque to facilitate processing.</span><br /><span style="font-size: 8pt;">* Interest accrued at 1% per month will be levied on outstanding balances that are more than 30 days from the invoice date.<br />* '. $query[0]["name"] .' reserve the rights to suspense your account until payment has been received.</span></p>
								</td>
								</tr>
								<tr>
								<td style="width: 90.4684%;">
								<p style="font-size: 8pt;"><span style="font-size: 8pt;"><br />NOTE:<br />Kindly advice us of any discrepancies within 30 days from invoice date, failing which this invoice will be deemed correct and disputes arising thereafter shall not be entertained.<br /><br /></span><span style="font-size: 8pt;">E. &amp; O.E</span></p>
								<p style="font-size: 8pt;"><em>This is a computer generated document. No signature is required.</em></p>
								</td>'.
								//$qr_code.
								'</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>';

		// $display_bank_info = '<table style="width: 100%; border-collapse: collapse;" border="0">
		// 			<tbody>
		// 			<tr>
		// 			<td style="width: 100%;"><br/><p style="font-size: 8pt;"> Please make a cheque payable to <strong>'.$query[0]["name"].'&nbsp;</strong>or remit to:</p></td>
		// 			</tr>
		// 			<tr>
		// 			<td style="width: 100%;">
		// 			<table style="width: 100.025%; border-collapse: collapse;" border="0">
		// 			<tbody>
		// 			<tr>
		// 			<td style="width: 100%;">
		// 			<p><span style="font-size: 8pt;">Banker: <strong>'.$bank[0]["banker"].'<br /></strong>Account Number:<strong>&nbsp;'.$bank[0]["account_number"].'&nbsp; &nbsp;</strong>Bank code:<strong> '.$bank[0]["bank_code"].'&nbsp; &nbsp;</strong>Swift code:<strong> '.$bank[0]["swift_code"].'</strong></span>&nbsp;</p>
		// 			</td>
		// 			</tr>
		// 			</tbody>
		// 			</table>
		// 			</td>
		// 			</tr>
		// 			<tr>
		// 			<td style="width: 100%;">
		// 			<table style="width: 100%; border-collapse: collapse;" border="0">
		// 			<tbody>
		// 			<tr>
		// 			<td style="width: 87.4684%;">
		// 			<p style="font-size: 8pt;"><br/>* For wire transfer, payer shall bear all local and oversea bank charges.<br /><span style="font-size: 8pt;">* Please email the remittance advice to '. $query[0]["email"] .'.</span><br /><span style="font-size: 8pt;">* Please write the invoice reference number on the back of the cheque to facilitate processing.</span><br /><span style="font-size: 8pt;">* Interest accrued at 1% per month will be levied on outstanding balances that are more than 30 days from the invoice date.<br />* '. $query[0]["name"] .' reserve the rights to suspense your account until payment has been received.</span></p>
		// 			</td>
		// 			</tr>
		// 			<tr>
		// 			<td style="width: 87.4684%;">
		// 			<p style="font-size: 8pt;"><span style="font-size: 8pt;"><br />NOTE:<br />Kindly advice us of any discrepancies within 30 days from invoice date, failing which this invoice will be deemed correct and disputes arising thereafter shall not be entertained.<br /><br /></span><span style="font-size: 8pt;">E. &amp; O.E</span></p>
		// 			<p style="font-size: 8pt;"><em>This is a computer generated document. No signature is required.</em></p>
		// 			</td>'.
		// 			$qr_code.
		// 			'</tr>
		// 			</tbody>
		// 			</table>
		// 			</td>
		// 			</tr>
		// 			</tbody>
		// 			</table>';

		//$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->SetY($forPaynow_Y_value);
		$obj_pdf->writeHTML($display_bank_info, true, false, false, false, '');
		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/'.$invoice_number.'.pdf', 'F');
		chmod($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/'.$invoice_number.'.pdf',0644);

		// output: http://
		//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
		$invoice_pdf_link = array();
		$invoice_pdf_link['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/'.$invoice_number.'.pdf'));
		$invoice_pdf_link['name'] = $invoice_number.'.pdf';

		$billing_email_list = array();
		
		//       if ($select_all_directors->num_rows() > 0) 
				// {
				// 	$select_all_director = $select_all_directors->result_array();
					
				// 	for($t = 0; $t < count($select_all_director); $t++)
				// 	{	
		//               $parse_data = array(
		//               	'firm_name' => $query[0]["name"],
		//               	'firm_email' => $query[0]["email"],
		//                   'user_name' => $select_all_director[$t]["name"],
		//                   'email' => $select_all_director[$t]["email"],
		//                   'total_amount' => number_format($total, 2),
		//                   'issue_date' => $issue_date,
		//                   'currency_name' => $currency
		//               );
		//               $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
		//               $message = $this->parser->parse_string($msg, $parse_data);

		//               $subject =  'INVOICE FOR '.$company_name;

		//               $undersigned = base_url().'img/acumen_bizcorp_header.jpg';
		//               array_push($billing_email_list, $select_all_director[$t]["email"]);
		//           }
					
		//       }

        $select_contact_persons = $this->db->query("select client_contact_info.*, client_contact_info_email.email from client_contact_info left join client_contact_info_email on client_contact_info_email.client_contact_info_id = client_contact_info.id and primary_email = 1 where client_contact_info.company_code = '".$company_code."'");

        if ($select_contact_persons->num_rows() > 0) 
		{
			$select_contact_person = $select_contact_persons->result_array();

			for($t = 0; $t < count($select_contact_person); $t++)
			{
				if($firm_id == '26')
				{
					$notify_email = "karnlee@aaa-global.com";
					$clarifi_email = "karnlee@aaa-global.com";
					$call_us = "(65) 6222 0028";
					$cc_email = 'karnlee@aaa-global.com';
				}
				else
				{
					$notify_email = "admin@aaa-global.com";
					$clarifi_email = "looi@aaa-global.com or admin@aaa-global.com";
					$call_us = "(65) 6246 8801";
					$cc_email = 'corpsec@aaa-global.com';
				}
                $parse_data = array(
                	'$notify_email' => $notify_email,
                	'$clarifi_email' => $clarifi_email,
                	'$call_us' => $call_us,
                	'firm_name' => $query[0]["name"],
                	'firm_email' => $query[0]["email"],
                    'user_name' => $select_contact_person[$t]["name"],
                    'email' => $select_contact_person[$t]["email"],
                    'total_amount' => number_format($total,2),
                    'issue_date' => $issue_date,
                    'currency_name' => $currency,
                    'company_name' => $company_name
                );
                $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
                $message = $this->parser->parse_string($msg, $parse_data);


                $subject =  'INVOICE FOR '.$company_name;

                $undersigned = base_url().'img/acumen_bizcorp_header.jpg';
                array_push($billing_email_list, $select_contact_person[$t]["email"]);
                // $check_email_send_to_contact_person = $this->sma->send_email($select_contact_person[$t]["email"], $subject, $message.'<p>Best regards,<br />Management on behalf of Acumen Alpha Advisory Group<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'admin@aaa-global.com', 'ACT Secretary', $invoice_pdf_link, 'corpsec@aaa-global.com,justin@aaa-global.com,then.k.w@hotmail.com');
            }
        }
        //$select_all_directors->num_rows() > 0 || 
        if ($select_contact_persons->num_rows() > 0) 
		{
			$recurring_billing_data = array(
		        'recu_invoice_issue_date' => date('d/m/Y', str_replace('/', '-',strtotime($new_issue_date)))
			);

			$this->db->where('id', $recurring_billing_id);
			$this->db->update('recurring_billing', $recurring_billing_data);

			$billing["firm_id"] = $q["firm_id"];
        	$billing["company_code"] = $q["company_code"];

        	$billing["company_name"] = $q["company_name"];
        	$billing["postal_code"] = $q["postal_code"];
        	$billing["street_name"] = $q["street_name"];
        	$billing["building_name"] = $q["building_name"];
        	$billing["unit_no1"] = $q["unit_no1"];
        	$billing["unit_no2"] = $q["unit_no2"];

        	$billing["currency_id"] = $q["currency_id"];
        	$billing["invoice_date"] = date('d/m/Y', now());
        	//$billing["invoice_date"] = date('d/m/Y', strtotime($issue_date)); //change invoice date
        	//$billing["invoice_date"] = date('d/m/Y', strtotime("05/04/2020")); //change invoice date(03/23/2020)
        	$billing["invoice_no"] = $invoice_number;
        	$billing["rate"] = $q["rate"];
        	$billing["amount"] = $total;
        	$billing["outstanding"] = $total;
        	$billing["status"] = $q["status"];
        	$billing["bank_info_id"] = $bank[0]["id"];

        	$this->db->insert("billing",$billing);
        	$insert_recurring_bill_id = $this->db->insert_id();

        	for($j = 0; $j < count($p); $j++)
			{
	        	$billing_service["billing_id"] = $insert_recurring_bill_id;
	        	$billing_service["service"] = $p[$j]["service"];
	        	$billing_service["invoice_date"] = date('d/m/Y', now());
	        	//$billing_service["invoice_date"] = date('d/m/Y', strtotime($issue_date)); //change invoice date
	        	//$billing_service["invoice_date"] = date('d/m/Y', strtotime("05/04/2020")); //change invoice date
	        	$billing_service["invoice_description"] = $p[$j]["invoice_description"];
	        	$billing_service["amount"] = $p[$j]["amount"];
	        	$billing_service["unit_pricing"] = $p[$j]["unit_pricing"];
	        	$billing_service["period_start_date"] = !(empty($p[$j]["period_start_date"]))? date('d/m/Y', strtotime(str_replace('/', '-', $p[$j]["period_start_date"]))) : '';
	        	$billing_service["period_end_date"] = !(empty($p[$j]["period_end_date"]))? date('d/m/Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) : '';
	        	if($p[$j]["gst_category_info_rate"] == NULL)
	        	{
	        		$billing_service["gst_rate"] = 0;
	        	}
	        	else
	        	{
	        		$billing_service["gst_rate"] = $p[$j]["gst_rate"];
	        	}

	        	if($p[$j]["gst_category_id"] == NULL)
	        	{
	        		$billing_service["gst_category_id"] = 0;
	        	}
	        	else
	        	{
	        		$billing_service["gst_category_id"] = $p[$j]["gst_category_id"];
	        	}

	        	if(!(empty($p[0]['gst_category_info_rate']) || ($p[0]['gst_category_info_rate'] == 0)) || $got_gst)
				{
					$billing_service["gst_new_way"] = 1;
				}
				else
				{
					$billing_service["gst_new_way"] = 0;
				}

	        	$this->db->insert("billing_service",$billing_service);
	        }
	        //check multiple email by special character
	        $unique_email = array_unique($billing_email_list);
	        $email_to = explode(';', $unique_email[0]); // your email address 
	        if(count($email_to) > 0)
	        {
	        	$email_arr = array();
	        	for($t = 0; $t < count($email_to); $t++)
	        	{
	        		$arr_email = array("email"=> trim($email_to[$t]));
	        		array_push($email_arr, $arr_email);
	        	}

	        	$email_detail['email'] = json_encode($email_arr);
	        }
	        else
	        {
	        	$email_detail['email'] = json_encode(array(array("email"=> trim($unique_email[0]))));
	        }
			//check multiple email by special character

            $email_detail['subject'] = $subject;
            if($firm_id == '26')
			{
				$email_detail['message'] = $message.'<p>Best regards,<br />Karn Lee<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 160 Robinson Road, #26-10 Singapore Business Federation Center (SBF Center), Singapore 068914<br />Tel: (+65) 6222 0028</p>';
			}
			else
			{
            	$email_detail['message'] = $message.'<p>Best regards,<br />Management on behalf of Acumen Alpha Advisory Group<br />'.$query[0]['name'].'<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 6246 8801 / (+ 65) 6246 8802</p>';
            }

            //add on
            $attach = array();
            array_push($attach, $invoice_pdf_link);
            //-----------------------

            $email_detail['from_email'] = json_encode(array("name" => $query[0]['name'], "email" => "admin@aaa-global.com"));//'admin@bizfiles.com.sg';
            $email_detail['from_name'] = $query[0]['name'];
            $email_detail['attachment'] = json_encode($attach);
            $email_detail['cc'] = json_encode(array(array("email" => $cc_email)));
            //looi@aaa-global.com, corpsec@aaa-global.com
            $email_detail['bcc'] = null;
            $email_detail['sended'] = 0;
            $email_detail['type'] = 'billing';
			$this->db->insert("email_queue",$email_detail);
			//corpsec@aaa-global.com,justin@aaa-global.com
        }
   	}
   	public function get_secretarial_doc($q, $p, $amount, $billing_period)
   	{
   		$q1 = $this->db->query("select * from document_master where id = '1768'");

       	$q1 = $q1->result_array();
		$this->load->helper('pdf_helper');

		$pattern = "/{{[^}}]*}}/";
		$subject = $q1[0]["document_content"];
		preg_match_all($pattern, $subject, $matches);
		$new_contents_info = $q1[0]["document_content"];

		$obj_agree_pdf = new ENGAGEMENT_PDF_WITH_NORMAL_FOOTER(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$obj_agree_pdf->SetCreator(PDF_CREATOR);
		$title = "Engagement";
		$obj_agree_pdf->SetTitle($title);

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

		$header_company_info = $this->write_engagement_header($q["firm_id"], $own_header);

		$obj_agree_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
		$header_company_info,
		$tc=array(0,0,0), $lc=array(0,0,0));

		$obj_agree_pdf->setPrintHeader(true);
		$obj_agree_pdf->setPrintFooter(true);
		$obj_agree_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);

		$obj_agree_pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP + 18, PDF_MARGIN_RIGHT+3);
		$obj_agree_pdf->SetAutoPageBreak(TRUE, 25);
		$obj_agree_pdf->AddPage();
		$obj_agree_pdf->setListIndentWidth(4);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));
			
			$replace_string = $matches[0][$r];
			$temp_content = "______________";

			if($q["use_foreign_add_as_billing_add"] == 1)
			{
				$address = $this->write_foreign_address(ucwords(strtolower($q["foreign_add_1"])), ucwords(strtolower($q["foreign_add_2"])), ucwords(strtolower($q["foreign_add_3"])));
			}
			else
			{
				$address = $this->write_address(ucwords(strtolower($q["street_name"])), $q["unit_no1"], $q["unit_no2"], ucwords(strtolower($q['building_name'])), $q["postal_code"], 'letter with comma');
			}

			if($string2 == "client company name")
			{
				$temp_content = $q["company_name"];

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
			elseif($string2 == "client address")
			{
				$new_contents_info = str_replace($replace_string, $address, $new_contents_info);
			}
			elseif($string2 == "Engagement Letter Date")
			{
				$temp_content = "22 July 2020";

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
			elseif($string2 == "Secretarial Fee")
			{
				if($billing_period == 4)
				{
					$temp_content = "SGD" . number_format(($amount * 2), 2) . " per year";
				}
				else if($billing_period == 5)
				{
					$temp_content = "SGD" . number_format($amount, 2) . " per year";
				}

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
			elseif($string2 == "servicing firm name")
			{
				$temp_content = "ACUMEN ALPHA ADVISORY PTE. LTD.";

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
			elseif($string2 == "Director Name")
			{
				$temp_content = "";

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
			elseif($string2 == "Company Director Name")
			{
				$temp_content = "Woelly William";

				$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
			}
		}

		$tagvs = array('p' => array(1 => array('h' => 0.0001, 'n' => 1)), 'ul' => array(0 => array('h' => 0.0001, 'n' => 1)));
		$obj_agree_pdf->setHtmlVSpace($tagvs);

		$content = $new_contents_info;

		$content = str_replace('class="check_new_page"', 'nobr="true"', $content);	// replace static text paragraph to make sure text is displayed in block together.

		$obj_agree_pdf->writeHTML($content, true, 0, true, true);

		$img_tag = 'img/Woelly_AAA_Signature.png';

		$obj_agree_pdf->setY($obj_agree_pdf->getY() - 48);
						
		$obj_agree_pdf->Image($img_tag, '', '', 60, 38, '', '', 'T', false, 1000, '', false, false, 1, false, false, false);

		$string_client_name = $this->myUrlEncode($q["company_name"]);

		$obj_agree_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/Secretarial Services Agreement - '.$string_client_name.'.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/Secretarial Services Agreement - '.$string_client_name.'.pdf',0644);

		// output: http://
		//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
		//$agreement_pdf_link = $_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/Secretarial Services Agreement - '.$string_client_name.'.pdf';

		$agreement_pdf_link = array();
		$agreement_pdf_link['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/Secretarial Services Agreement - '.$string_client_name.'.pdf'));
		$agreement_pdf_link['name'] = 'Secretarial Services Agreement - '.$string_client_name.'.pdf';

		return $agreement_pdf_link;
   	}

   	public function get_merge_doc($q, $p)
   	{
   		$objs_pdf = new MYMERGERPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$objs_pdf->SetCreator(PDF_CREATOR);
		$title = "BUSINESS MERGER ANNOUCEMENT";
		$objs_pdf->SetTitle($title);

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

		$objs_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
		$header_company_info,
		$tc=array(0,0,0), $lc=array(0,0,0));

		$objs_pdf->SetDefaultMonospacedFont('helvetica');
		$objs_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$objs_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		if(40 > strlen($query[0]['name']))
		{
			$objs_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+6, PDF_MARGIN_RIGHT+3);
		}
		else
		{
			$objs_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+6, PDF_MARGIN_RIGHT+3);
		}
        $objs_pdf->SetFont('helvetica', '', 10);
		$objs_pdf->setFontSubsetting(false);
		$objs_pdf->AddPage();
		$merge_content = '<p style="text-align: justify;">Date: 22 July 2020</p>
		<p style="text-align: justify;">Dear Valued Customers,</p><p style="text-align: justify;"><strong><u>BUSINESS MERGER ANNOUCEMENT</u></strong></p>
		<p style="text-align: justify;">I am pleased to announce that Acumen Group and Alpha Group are now officially operating as one, Acumen Alpha Advisory Pte. Ltd. Over the past year, many of you have expressed your support for this combination. We appreciate your confidence. We pledge our continued commitment to serve with excellence and delivering the benefits we expect from this merger:</p>
		<p style="text-align: justify;"><strong>Expanded advisory services<br /></strong>We planned to offer additional advisory services such as business valuation, financial due diligence, risk management and other advisory. The foundation work has begun in acquisition of necessary resources and skills We believe these services will help you to take your Company to the new ground with new growth and its big plan.</p>
		<p style="text-align: justify;"><strong>Better experience<br /></strong>Our combination allows access to additional resources and technology which gives you better respond rate and service. From the application of new technology, we will deliver more impactful services.</p>
		<p style="text-align: justify;">As we work to deliver the value of the combined companies, we are equally as focussed on the work of our existing portfolio. We plan to incorporate the best features of our services to our customers. We are all about our customers and our goal is always about 100% customer satisfaction.</p>
		<p style="text-align: justify;">All procedures and contact person remain unchanged, so please continue to communicate and liaise with the person you have been in touch with for support via the channel that you have been using all these years.</p>
		<p style="text-align: justify;">A new contract is attached in the appendix to formalize the professional relationship between our newly combined company with your company. The terms of engagement including our fee largely remains the same. Please read the new contract, sign and return to us.</p>
		<p style="text-align: justify;">Shall you require clarification on our new arrangement, please let us know. You may contact the same person you have been liaising with in the past.</p>
		<p style="text-align: justify;">Thank you for your continued support</p>
		<p style="text-align: justify;">Your faithfully,</p>
		<p style="text-align: justify;">&nbsp;</p><p style="text-align: justify;">&nbsp;</p>
		<p style="text-align: justify;">Ray Kong<br />CEO<br /><span style="font-size: 11pt;">Acumen Alpha Advisory Pte. Ltd.</span></p>';
		$objs_pdf->writeHTML($merge_content, true, false, false, false, '');

		$img_tag = 'img/ray_secretary.png';

		$objs_pdf->setY($objs_pdf->getY() - 35);
						
		$objs_pdf->Image($img_tag, '', '', 35, 18, '', '', 'T', false, 1000, '', false, false, 0, false, false, false);

		$objs_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/BUSINESS MERGER ANNOUCEMENT.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/BUSINESS MERGER ANNOUCEMENT.pdf',0644);

		// output: http://
		//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
		//$merge_pdf_link = $_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/BUSINESS MERGER ANNOUCEMENT.pdf';

		$merge_pdf_link = array();
		$merge_pdf_link['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/BUSINESS MERGER ANNOUCEMENT.pdf'));
		$merge_pdf_link['name'] = 'BUSINESS MERGER ANNOUCEMENT.pdf';

		return $merge_pdf_link;
   	}

   	public function write_engagement_header($firm_id, $use_own_header)
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
			//before width is 25, height is 73.75
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
			if($query[0]["branch_name"] != null)
			{
				$branch_name = 'Branch: '.$query[0]["branch_name"].'<br />';
			}
			else
			{
				$branch_name = '';
			}

			$header_content = '<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
						<tr style="height: 80px;"><td style="width: 24.275%; text-align: left; height: 80px;" align="center">'.$img.'</td><td style="width:5px;"></td>
							<td style="width: 75.725%; height: 80px;"><span style="font-size: 14pt;"><strong>'.$query[0]["name"].'</strong></span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />'.$branch_name.'Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
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

   	public function write_header($firm_id, $use_own_header, $gst_uen_display = NULL)
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
			//before width is 25, height is 73.75
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
			if($query[0]["branch_name"] != null)
			{
				$branch_name = 'Branch: '.$query[0]["branch_name"].'<br />';
			}
			else
			{
				$branch_name = '';
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
						<td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">'.$gst_uen_display.': '. $query[0]["registration_no"] .'<br />'.$branch_name.'Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
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

	public function receiver_info($document_type, $gst_number_display, $q, $invoice_number, $gst_checkbox = null)
	{
		if($q["use_foreign_add_as_billing_add"] == 1)
		{
			$address = $this->write_foreign_address(ucwords(strtolower($q["foreign_add_1"])), ucwords(strtolower($q["foreign_add_2"])), ucwords(strtolower($q["foreign_add_3"])));
		}
		else
		{
			$address = $this->write_address(ucwords(strtolower($q["street_name"])), $q["unit_no1"], $q["unit_no2"], ucwords(strtolower($q['building_name'])), $q["postal_code"], 'letter with comma');
		}
		$add_info = '';
		$description_title = '';

		// if($document_type == "invoice")
		// {
			$document_date = date('d F Y', now());
			//$document_date = date('d F Y', strtotime(str_replace('/', '-', $q["recu_invoice_issue_date"]))); //change invoice date
			//$document_date = date('d F Y', strtotime(str_replace('/', '-', "04/05/2020"))); //change invoice date DD/MM/YYYY
			$document_num  = $invoice_number;

			$description_title = "Description";
			//$title_font_size = '22pt';

			// if($gst_checkbox == 1)
			// {
			// 	$gst_checkbox_value = '<td style="width: 8%; height: 17px;"><strong><span style="text-decoration: underline;">GST (%)</span></strong></td>';
			// 	$header_width = "78.9273%";
			// }
			// else
			// {
				$gst_checkbox_value = '';
				$header_width = "86.9273%";
			//}

			$add_info = '<tr><td style="width: 73%; text-align:left;"><strong style="font-size: 10pt;">Bill To:</strong></td></tr>';//' . $gst_number_display . '

			if($gst_number_display != '')
			{
				$document_type = "tax invoice";
				$title_font_size = '18pt';
			}
			else
			{
				$document_type = "invoice";
				$title_font_size = '22pt';
			}

			$content_header = '<td style="width: '.$header_width.'; height: 17px;" colspan="2" align="left"><strong><span style="text-decoration: underline;">'.$description_title.'</span></strong></td>'.
						$gst_checkbox_value.
						'<td style="width: 13.5%; height: 17px;"><strong><span style="text-decoration: underline;">'. $q["currency"] .'</span></strong></td>';
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
						<tr style="height: 17px;">'.
						$content_header.
						'</tr>
						</tbody>
					</table>
					';

		return $receiver_info;
	}

	public function write_foreign_address($foreign_add_1, $foreign_add_2, $foreign_add_3)
	{
		if(!empty($foreign_add_1))
        {
            $comma1 = $foreign_add_1 . '<br/>';
        }
        else
        {
            $comma1 = '';
        }

        if(!empty($foreign_add_2))
        {
            $comma2 = $comma1 . $foreign_add_2 .'<br/>';
        }
        else
        {
            $comma2 = $comma1 . '';
        }
        $address = $comma2.$foreign_add_3;

        return $address;
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
		if(!empty($unit))
		{
			$address = $street_name . $comma . $br . $unit_building_name . $br . 'Singapore ' . $postal_code;
		}
		elseif(!empty($building_name))
		{
			$address = $street_name . $comma . $br . $building_name . $comma . $br . 'Singapore ' . $postal_code;
		}
		else
		{
			$address = $street_name . $comma . $br . 'Singapore ' . $postal_code;
		}
		return $address;
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

	public function myUrlEncode($string) {
	    $replacements = array('');
	    $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
	    return str_replace($entities, $replacements, $string);
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
}

class MYMERGERPDF extends TCPDF {
	public function Header() {
		$headerData = $this->getHeaderData();
		$this->SetFont('helvetica', 'B', 23);
		//$this->SetY(0);
        //$this->SetFont('helvetica', '', 17);
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);

        // $document_name = '';
   }

   public function Footer() {

   }
}

class ENGAGEMENT_PDF_WITH_NORMAL_FOOTER extends TCPDF {
	public function Header() {
		$headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
   	}

	public function Footer() {
		// Position at 25 mm from bottom
        $this->SetY(-20);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', '', 8);
        
        // Page number
		$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		// $this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().' of '.$this->getAliasNbPages(), 0, 0, 'R');
		$this->Ln();
   }
}