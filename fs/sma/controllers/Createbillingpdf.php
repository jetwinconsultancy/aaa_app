<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class Createbillingpdf extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	

	public function index()
	{
		$this->load->helper('pdf_helper');

	    // create new PDF document
	    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$title = "Receipt";
		$obj_pdf->SetTitle($title);
		//$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$obj_pdf->SetFont('helvetica', '', 9);
		$obj_pdf->setFontSubsetting(false);
		//$obj_pdf->setPrintFooter(false);
		
		$obj_pdf->AddPage();
		/*$content = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'dot/themes/default/views/index.php' );*/
		//ob_start();
		    // we can have any view part here like HTML, PHP etc
		    
		    //echo $content;
			//$obj_pdf->WriteHTML( ob_get_contents() );
		//ob_end_clean();
		$content = '<div>
					<div style="height: 15px; width: 100%; margin: 20px 0; text-align: center; color: black; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 10px; padding: 8px 0px;font-size: 14px; font-family: Georgia, Serif; overflow: hidden; resize: none; text-decoration: none; font-weight: bold;">INVOICE
					</div>
					<div>
						<table style="padding-bottom: 20px;padding-right: 10px;font-size: 14px; font-family: Georgia, Serif;">
				            <tr>

			                    <td style="" width="90">Client Name:</td>
			                    <td width="190"></td>
			                    <td style="" width="90">Invoice No:</td>
			                    <td>000123</td>
			                </tr>
				            <tr>
				            	<td style="" width="90">Address:</td>
						        <td width="190">December 15, 2009</td>
						        <td style="" width="90">Date:</td>
						        <td>December 15, 2009</td>
				            </tr>
				            <tr>
				            	<td style="" width="90">Attention:</td>
							    <td width="190">$875.00</td>
							    <td style="" width="90">Currency:</td>
							    <td>$875.00</td>
				            </tr>


					    </table>
					</div>
					
					<table cellspacing="0" cellpadding="2" class="table" style="font-size: 14px; font-family: Georgia, Serif;">
					
					  
					  <tr style="background-color: #eee;">
					      <td style="border: 1px solid black; padding: 5px;width: 430px;">Description</td>
				           <td style="border: 1px solid black; padding: 5px;text-align: center;width: 80px;">Amount</td>
				      </tr>
					  <tr class="item-row">
					      <td style="border: 1px solid black; padding: 5px;">Web Updates</td>
					      <td style="border: 1px solid black; padding: 5px;text-align: center;">$650.00</td>
					  </tr>  
					  <tr>
					      <td style="border: 1px solid black; padding: 5px;background-color: #eee;">Sub-Total</td>
					      <td  style="border: 1px solid black; padding: 5px;text-align: center;">$875.00</td>
					  </tr>
					  <tr>
					      <td style="border: 1px solid black; padding: 5px;background-color: #eee;">GST</td>
					      <td  style="border: 1px solid black; padding: 5px;text-align: center;">$875.00</td>
					  </tr>
					  <tr>
					      <td style="border: 1px solid black; padding: 5px;background-color: #eee;">Total</td>
					      <td  style="border: 1px solid black; padding: 5px;text-align: center;">$875.00</td>
					  </tr>
					  
					
					</table>
				</div>';
		$obj_pdf->writeHTML($content, true, false, false, false, '');
		// $div = '<div id="footer">wow this is a nice footer</div>';
		// $obj_pdf->SetY(-5);
		// $obj_pdf->Cell(0, 10, $div, 0, false, 'C', 0, '', 0, false, 'T', 'M');
		//$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'iasia/pdf/output.pdf', 'F');
		$obj_pdf->Output('output.pdf', 'D');
	}

	public function delete_invoice()
	{
		//$path = $_POST["path"];

		// Remove file 
		$this->load->helper("file");
		delete_files('./pdf/invoice/');

		echo json_encode(array("status" => true));
	}

	public function delete_receipt()
	{
		//$path = $_POST["path"];

		// Remove file 
		$this->load->helper("file");
		delete_files('./pdf/receipt/');

		echo json_encode(array("status" => true));
	}

	public function delete_credit_note()
	{
		$this->load->helper("file");
		delete_files('./pdf/credit_note/');

		echo json_encode(array("status" => true));
	}

	public function create_statement_pdf()
	{
		$type = $_POST["type"];
		$keyword = $_POST["search"];
		$start = $_POST["start"];
		$end = $_POST["end"];

		if($keyword != "" && ($start != "" || $end != ""))
		{
			$this->db->select('billing.*, receipt.*, client.company_name, client.registration_no, client.former_name, currency.currency as currency_name');
	        $this->db->from('billing');
	        $this->db->join('billing_receipt_record', 'billing.id = billing_receipt_record.billing_id', 'left');
	        $this->db->join('receipt', 'receipt.id = billing_receipt_record.receipt_id', 'left');
	        $this->db->join('client', 'client.company_code = billing.company_code', 'left');
	        $this->db->join('currency', 'currency.id = billing.currency_id', 'left');

	        if ($type != NULL)
	        {
	            if ($type != 'all')
	            {
	                $this->db->like($type, $keyword);
	            } 
	            else 
	            {
	                $this->db->group_start();
                    $this->db->or_like('client.registration_no', $keyword);
                    $this->db->or_like('client.company_name', $keyword);
	                $this->db->group_end();
	            }
	        }
	        if ($start != NULL)
	        {
	            //$this->db->where("invoice_date BETWEEN ".$start." AND ".$end."");
	            if ($end != NULL)
	            {

	                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") BETWEEN STR_TO_DATE("'. $start. '","%d/%m/%Y") and STR_TO_DATE("'. $end.'","%d/%m/%Y")');
	            }
	            else
	            {
	                $this->db->where('billing.invoice_date >= "'. $start.'"');
	            }
	        }
	        else if ($start == NULL)
	        {
	        	if ($end != NULL)
	            {

	                $this->db->where('STR_TO_DATE(billing.invoice_date,"%d/%m/%Y") <= STR_TO_DATE("'. $end.'","%d/%m/%Y")');
	            }
	        }

	        $this->db->order_by('billing.id', 'asc');
	        $this->db->where('billing.firm_id', $this->session->userdata("firm_id"));
	        //$this->db->where('billing.outstanding !=', 0);
	        $this->db->where('billing.status !=', 1);
	        $q = $this->db->get();

	        $statement_result = $q->result_array();

	        // echo json_encode($statement_result);

	        $currency = $this->db->query("select * from currency");

	        $currency_result = $currency->result_array();

	        // echo json_encode($statement_result);

	        $distinct_currency = array_unique(array_column($statement_result, 'currency_name'));
	        // echo json_encode($distinct_currency);


	        $firm = $this->db->query("SELECT firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
										JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
										JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
										JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
										where firm.id = '".$this->session->userdata("firm_id")."'");
	        $firm = $firm->result_array();

	        $client = $this->db->query("SELECT * FROM client WHERE company_code='". $statement_result[0]["company_code"] ."'");
	        $client = $client->result_array();

	        $array_link = [];
	       	$this->load->helper('pdf_helper');

			// create new PDF document
		    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
			$obj_pdf->SetCreator(PDF_CREATOR);
			$title = "Statement";
			$obj_pdf->SetTitle($title);

			$unit_building_name = "";

			if(!empty($client[0]["unit_no1"]) && !empty($client[0]["unit_no2"])){
				$unit_building_name.= "#" . $client[0]["unit_no1"] . "-" . $client[0]["unit_no2"] . ",";
			}

			if(!empty($client[0]["building_name"]) && !empty($client[0]["unit_no1"]) && !empty($client[0]["unit_no2"])){
				$unit_building_name.= ' ' . $client[0]["building_name"] . ",";
			}else{
				$unit_building_name.= $client[0]["building_name"] . ",";
			}

			$bank = $this->db->query("SELECT * FROM bank_info WHERE firm_id =". $this->session->userdata("firm_id") . " AND in_use = 1");
			$bank = $bank->result_array();

			// $own_header = isset($statement_result[0]["own_letterhead_checkbox"])?$statement_result[0]["own_letterhead_checkbox"]:false;
			$own_header = false;

			if(!$own_header){
				$header_company_info = 
				'<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
						<tr style="height: 80px;"><td style="width: 24.275%; text-align: left; height: 80px;" align="center"><img src="uploads/logo/'. $firm[0]["file_name"] .'" height="60" /></td>
							<td style="width: 75.725%; height: 80px;"><span style="font-size: 14pt;"><strong>'.$firm[0]["name"].'</strong></span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $firm[0]["registration_no"] .'<br />Address: '. $firm[0]["street_name"] .', #'. $firm[0]["unit_no1"] .'-'.$firm[0]["unit_no2"].' '. $firm[0]["building_name"] .', Singapore '. $firm[0]["postal_code"] .'<br />Tel: '. $firm[0]["telephone"] .' &nbsp; Fax: '. $firm[0]["fax"] .'<br />Email: <span style="font-size: 7pt;">'. $firm[0]["email"] .'</span>&nbsp;</span></td>
						</tr>
					</tbody>
				</table>';
			}
			else
			{
				$header_company_info = 
				'<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
						<tr style="height: 80px;"><td style="height: 80px;"></td></tr>
					</tbody>
				</table>';
			}

			foreach($distinct_currency as $currency){
				$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
				$header_company_info. 
				'<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
					<tr>
					<td style="width: 50.2458%; font-weight: bold; font-size: 12px;">
						<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
								<tr>
								<td style="width: 25.0624%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">Name:</span></strong></td>
								<td style="width: 74.9376%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$client[0]["company_name"].'</span></strong></td>
								</tr>
								<tr>
								<td style="width: 25.0624%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">Address:</span></strong></td>
								<td style="width: 74.9376%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;"><strong><span style="font-family: arial, helvetica, sans-serif;"><span style="font-size: 10pt;">'.$client[0]["street_name"].',<br /></span><span style="font-size: 10pt;">'. $unit_building_name .'<br /></span><span style="font-size: 10pt;">SINGAPORE '.$client[0]["postal_code"].'</span></span></strong></span></strong></td>
								</tr>
								<tr>
								<td style="width: 25.0624%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">Attention:</span></strong></td>
								<td style="width: 74.9376%; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">DIRECTOR / FINANCE DEPARTMENT</span></strong></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td style="width: 14.1678%;">&nbsp;</td>
					<td style="width: 36.9762%;">
						<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
								<tr>
								<td style="width: 93.077%;"><span style="text-align: left; font-size: 22pt; font-family: arial, helvetica, sans-serif;">STATEMENT</span></td>
								<td style="width: 6.92303%; text-align: right;"><span style="text-align: right; font-weight: bold; font-size: 20px;">&nbsp;</span></td>
								</tr>
							</tbody>
						</table>
						<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
								<tr>
								<td style="width: 40%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								<td style="width: 60%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								</tr>
								<tr>
								<td style="width: 40%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								<td style="width: 60%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								</tr>
								<tr>
								<td style="width: 40%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								<td style="width: 60%; text-align: left;"><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;"><strong></strong></span></td>
								</tr>
							</tbody>
						</table>
						<p style="text-align: right;"></p>
					</td>
					</tr>
					</tbody>
				</table>
				<hr style="height: 2px; border: none; color: #333; background-color: #333;" />
				
				<table style="width: 99.9996%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
					<tr style="height: 17px;">
					<td style="width: 11.7278%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="width: 23.4551%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="width: 11.6018%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="width: 12.7364%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="width: 15.8891%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="width: 10.5925%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					<td style="text-align: center; width: 13.9975%; height: 17px;"><span style="font-size: 10pt;"><strong>&nbsp;</strong></span></td>
					</tr>
					<tr style="height: 17px;">
					<td style="height: 17px; width: 11.7278%;" align="left"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Date</span></strong></span></td>
					<td style="width: 23.4551%; height: 17px;" align="left"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Activity</span></strong></span></td>
					<td style="width: 11.6018%; height: 17px;" align="left"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Reference</span></strong></span></td>
					<td style="width: 12.7364%; height: 17px;" align="left"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Due Date</span></strong></span></td>
					<td style="width: 15.8891%; height: 17px; text-align: right;"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Invoice Amount</span></strong></span></td>
					<td style="width: 10.5925%; height: 17px; text-align: right;"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Payments</span></strong></span></td>
					<td style="width: 13.9975%; height: 17px; text-align: right;"><span style="font-family: trebuchet ms, geneva, sans-serif;"><strong><span style="font-size: 10pt;">Balance '.$currency.'</span></strong></span></td>
					</tr>
					<tr>
					<td style="width: 100%;" colspan="7"><hr style="height: 1px; border: none; color: #333; background-color: #333;" /></td>
					</tr>
					</tbody>
				</table>
				', 
				$tc=array(0,0,0), $lc=array(0,0,0));

				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+68, PDF_MARGIN_RIGHT+3);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);

				$obj_pdf->AddPage();
				$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
				$table_content_end = '</tbody></table>';

				$content = '';
				$current_content = '';
				$balance_due = 0;
				// $gst_rate = $p[0]['gst_rate'];

				// $content .= $table_content_start;

				foreach($statement_result as $row){
					if($row["currency_name"] == $currency){
						$reference = !is_null($row["reference_no"])?$row["reference_no"]: '';
						$payments  = $row["amount"] - $row["outstanding"];

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 11.7278%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. $row["invoice_date"] .'</span></td>
												<td style="width: 23.4551%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">Invoice # '. $row["invoice_no"] .'</span></td>
												<td style="width: 11.6018%; height: 17px;" align="left">'. $reference .'</td>
												<td style="width: 12.7364%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;"></span></td>
												<td style="width: 15.8891%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($row["amount"],2) .'</span></td>
												<td style="width: 10.5925%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($payments,2) .'</span></td>
												<td style="width: 13.9975%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($row["outstanding"],2) .'</span></td>
												</tr>';

						$content .= $table_part_content;

						// $sub_total += (float)$p[$j]['amount'];

						// $gst += round((($p[$j]['gst_rate'] / 100) * (float)$p[$j]['amount']), 2);

						// $content = $content . $table_content_end;	// add in end of table

						// $total = $sub_total + $gst;
					}
				}

				$table_content_height = $obj_pdf->getStringHeight(1000, $table_content_start . $content . $table_content_end);

				// to display content
				foreach($statement_result as $row){
					if($row["currency_name"] == $currency){
						$reference 	 = !is_null($row["reference_no"])?$row["reference_no"]: '';
						$payments  	 = $row["amount"] - $row["outstanding"];
						$balance_due += $row["outstanding"];

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 11.7278%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. $row["invoice_date"] .'</span></td>
												<td style="width: 23.4551%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">Invoice # '. $row["invoice_no"] .'</span></td>
												<td style="width: 11.6018%; height: 17px;" align="left">'. $reference .'</td>
												<td style="width: 12.7364%; height: 17px;" align="left"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;"></span></td>
												<td style="width: 15.8891%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($row["amount"],2) .'</span></td>
												<td style="width: 10.5925%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($payments,2) .'</span></td>
												<td style="width: 13.9975%; text-align: right; height: 17px;"><span style="font-size: 9pt; font-family: trebuchet ms, geneva, sans-serif;">'. number_format($row["outstanding"],2) .'</span></td>
												</tr>';

						$current_content .= $table_part_content;	// content without table tag

						$current_content_height = $obj_pdf->getStringHeight(1000, $table_content_start . $current_content . $table_content_end);
						$obj_pdf->writeHTML($table_content_start . $table_part_content . $table_content_end, true, false, false, false, '');

						if($obj_pdf->getY() >= 210  && $current_content_height == $table_content_height){ // for one page only
							
							$obj_pdf->AddPage();
						}
					}
				}

				// content 2 (payment method and total)
				// $obj_pdf->SetY(-50);
		        
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->SetXY(17, 210);

				$content2 = '<table style="border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 100%;"><hr /></td>
							</tr>
							</tbody>
							</table>
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 55%;">&nbsp;</td>
							<td style="width: 25%;">&nbsp;</td>
							<td style="width: 20%; text-align: right;"><strong>&nbsp;</strong></td>
							</tr>
							</tbody>
							</table>
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 55%;"><span style="font-size: 8pt;">'. $currency .': '. $this->spell_number_dollar($balance_due) .'</span></td>
							<td style="width: 25%;"><strong style="text-align:left">BALANCE DUE '.$currency.'</strong></td>
							<td style="width: 20%; text-align: right;">
							<div style="text-align: right;"><strong>'. number_format($balance_due,2) .'</strong></div>
							</td>
							</tr>
							<tr>
							<td style="width: 55%;">&nbsp;</td>
							<td style="width: 25%;">&nbsp;</td>
							<td style="width: 20%; text-align: right;"><strong>&nbsp;</strong></td>
							</tr>
							<tr>
							<td style="width: 55%;">&nbsp;</td>
							<td style="width: 25%;">&nbsp;</td>
							<td style="width: 20%; text-align: right;"><strong>&nbsp;</strong></td>
							</tr>
							</tbody>
							</table>
							<p>&nbsp;</p>
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 100%;"><span style="font-size: 8pt;">&nbsp; &nbsp;Please make a cheque payable to <strong>'.$firm[0]["name"].'</strong></span></td>
							</tr>
							<tr>
							<td style="width: 100%;">
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 12.4472%;"><span style="font-size: 8pt;">&nbsp; or remit to:</span></td>
							<td style="width: 74.0929%;"><span style="font-size: 8pt;">Banker: <strong>'.$bank[0]["banker"].'</strong></span></td>
							<td style="width: 13.4598%;">&nbsp;</td>
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
							<td style="width: 86.5823%;">
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 14.4376%;">&nbsp;</td>
							<td style="width: 85.5624%;"><span style="font-size: 8pt;">Account Number:&nbsp;<strong>'.$bank[0]["account_number"].'</strong></span></td>
							</tr>
							<tr>
							<td style="width: 14.4376%;">&nbsp;</td>
							<td style="width: 85.5624%;"><span style="font-size: 8pt;">Bank code: '.$bank[0]["bank_code"].'</span></td>
							</tr>
							<tr>
							<td style="width: 14.4376%;">&nbsp;</td>
							<td style="width: 85.5624%;"><span style="font-size: 8pt;">Swift code: '.$bank[0]["swift_code"].'</span></td>
							</tr>
							</tbody>
							</table>
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 100%;"><span style="font-size: 8pt;">This tax invoice is <strong>payable in full upon presentation</strong> and withholding tax, if any, will be on your account</span></td>
							</tr>
							<tr>
							<td style="width: 100%;"><span style="font-size: 8pt;">Payment by cheque only by cheque issued by a commercial bank in Singapore is acceptable</span></td>
							</tr>
							</tbody>
							</table>
							</td>
							<td style="width: 13.4177%;">'.
							// '<p style="text-align: center;"><span style="font-size: 8pt;"><em>for quick transfer</em><br /></span><span style="font-size: 8pt;"><img style="width: 49px; height: 49px;" src="img/invoice_qr_code.png" /></span></p>'.
							'</td>
							</tr>
							</tbody>
							</table>
							</td>
							</tr>
							</tbody>
							</table>';

				$obj_pdf->SetAutoPageBreak(TRUE, 10);
				$obj_pdf->writeHTML($content2, true, false, false, false, '');
				// $obj_pdf->AddPage();
			}

			// $content = '';
			
			// $sub_total = 0;
			// $gst = 0;
			// $total = 0;
			// // $gst_rate = $p[0]['gst_rate'];

			// $content .= $table_content_start;

			// $obj_pdf->writeHTML($table_content_start . $table_content_end, true, false, false, false, '');

			$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/statement.pdf', 'F');

				// output: http://
			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

			array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/invoice/statement.pdf');
			//echo ("123");
			echo json_encode(array("status" => 1, "link" => $array_link)); 

	        // echo json_encode(array("status" => 1));
	    }
	    else
	    {
	    	echo json_encode(array("status" => 2));
	    }
	}

	public function create_billing_pdf()
	{
		if($_POST["tab"] == "billing")
		{
			$billing_id = $_POST["billing_id"];
			$pre_printed = $_POST["pre-printed"];

			$array_link = [];
			if(count($billing_id) != 0)
			{
				for($i = 0; $i < count($billing_id); $i++)
				{
					$q = $this->db->query("select billing.firm_id, billing.id, billing.company_code, billing.invoice_date, billing.invoice_no, billing.currency_id, billing.amount, billing.rate, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency from billing left join client on client.company_code = billing.company_code left join currency on currency.id = billing.currency_id where billing.id =".$billing_id[$i]."");

			       	$q = $q->result_array();

			       	$p = $this->db->query("select billing.id, billing.company_code, billing_service.billing_id as billing_service_billing_id, billing_service.invoice_description, billing_service.amount, billing_service.gst_rate, billing_service.period_start_date, billing_service.period_end_date from billing left join billing_service on billing_service.billing_id = billing.id where billing.id =".$billing_id[$i]." ORDER BY billing_service.id");

			       	$p = $p->result_array();

			       	$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
												JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
												JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
												JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
												where firm.id = '".$q[0]["firm_id"]."'");

					$query = $query->result_array();

			       	$this->load->helper('pdf_helper');

		    		// create new PDF document
				    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Invoice";
					$obj_pdf->SetTitle($title);

					$unit_building_name = "";

					if(!empty($q[0]["unit_no1"]) && !empty($q[0]["unit_no2"])){
						$unit_building_name.= "#" . $q[0]["unit_no1"] . "-" . $q[0]["unit_no2"] . ",";
					}

					if(!empty($q[0]["building_name"]) && !empty($q[0]["unit_no1"]) && !empty($q[0]["unit_no2"])){
						$unit_building_name.= ' ' . $q[0]["building_name"] . ",";
					}else{
						$unit_building_name.= $q[0]["building_name"] . ",";
					}

					$own_header = isset($pre_printed)?($pre_printed === 'true')? true: false: true;

					$header_company_info = $this->write_header($q[0]["firm_id"], $own_header);

					// gst number display
					if(!(empty($p[0]['gst_rate']) || ($p[0]['gst_rate'] == 0)))
					{
						$gst_number_display = '<td style="width: 25%; text-align:left; font-size: 8pt; font-weight:normal;">GST Reg. No. : '. $query[0]['registration_no'] .'</td>';
					}
					else
					{
						$gst_number_display = '';
					}

					$receiver_info = $this->receiver_info("invoice", $gst_number_display, $q);

					$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
					$header_company_info.
					$receiver_info,
					$tc=array(0,0,0), $lc=array(0,0,0));

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

						$period_start_date 	= !(empty($p[$j]["period_start_date"]))? ' from ' . date('d F Y', str_replace('/', '-', strtotime($p[$j]["period_start_date"]))) : '';
						$period_end_date 	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', str_replace('/', '-', strtotime($p[$j]["period_end_date"]))) : '';

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 86.9273%;%; text-align: left;">
												<p style="text-align: justify;">'. nl2br($p[$j]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
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
						$period_end_date   	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) : '';

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 86.9273%;%; text-align: left;">
												<p style="text-align: justify;">'. nl2br($p[$j]['invoice_description']). '<strong>' . $period_start_date . $period_end_date .'</strong></p>
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

					$bank = $this->db->query("SELECT * FROM bank_info WHERE firm_id =". $q[0]["firm_id"] . " AND in_use = 1");
					$bank = $bank->result_array();

					$content2 = '<table style="border-collapse: collapse;" border="0">
					<tbody>
					<tr>
					<td style="width: 100%;"><hr /></td>
					</tr>
					</tbody>
					</table>';

					if($q[0]['rate'] != 1)
					{
						$converted_total = $total * $q[0]['rate'];
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
								<td style="width: 63%;"><span style="font-size: 8pt;">'. $q[0]["currency"] .': '. $this->spell_number_dollar($total) .'</span></td>
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
					$this->db->update("billing",$billing_bank_info,array("id" => $q[0]["id"]));

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

					// $obj_pdf->SetY(-75);
					$obj_pdf->writeHTML($display_bank_info, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/invoice/'.$q[0]["invoice_no"].'.pdf', 'F');

					// output: http://
	    			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

					array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/invoice/'.$q[0]["invoice_no"].'.pdf');
					//echo ("123");
				}
				echo json_encode(array("link" => $array_link)); 
				
			}
		}
		else if($_POST["tab"] == "receipt")
		{
			$receipt_id = $_POST["receipt_id"];
			$pre_printed = $_POST["pre-printed"];
			$own_header = isset($pre_printed)?($pre_printed === 'true')? true: false: true;

			//echo json_encode(count($billing_id));
			$array_link = [];
			if(count($receipt_id) != 0)
			{
				for($i = 0; $i < count($receipt_id); $i++)
				{
					$q = $this->db->query("select receipt.receipt_no, receipt.receipt_date, billing_receipt_record.received, billing.firm_id, billing.id, billing.company_code, billing.invoice_date, billing.invoice_no, billing.currency_id, billing.amount, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency from billing_receipt_record left join billing on billing.id = billing_receipt_record.billing_id left join client on client.company_code = billing.company_code left join currency on currency.id = billing.currency_id left join receipt on receipt.id = receipt_id where receipt_id ='".$receipt_id[$i]."'");

			       	$q = $q->result_array();

			       	$p = $this->db->query("select receipt.*, payment_mode.payment_mode as payment_mode_name from receipt left join payment_mode on payment_mode.id = receipt.payment_mode where receipt.id ='".$receipt_id[$i]."'");

			       	$p = $p->result_array();

			       	$query = $this->db->query("select * from firm where id = '".$q[0]["firm_id"]."'");

					$query = $query->result_array();

					/*<img id="image" src="uploads/logo/'.$query[0]["file_name"].'" alt="logo" height="50" width="50" style="float:left"/>*/

			       	//echo json_encode($query);

			       	$header_company_info = $this->write_header($q[0]["firm_id"], $own_header);
			       	$receiver_info = $this->receiver_info('receipt', '', $q);

			       	$this->load->helper('pdf_helper');

		    		// create new PDF document
				    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Receipt";
					$obj_pdf->SetTitle($title);
					//$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
					$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs=$header_company_info . $receiver_info, $tc=array(0,0,0), $lc=array(0,0,0));

					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+53, PDF_MARGIN_RIGHT+3);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();

					$table_content = "";
					$total = 0;
					//$gst = 0;

					// for($j = 0; $j < count($q); $j++)
					// {
					// 	$table_part_content = '<tr class="item-row">
					// 		<td style="padding: 5px;">'.$q[$j]['invoice_no'].'</td>
					// 		<td style="padding: 5px;text-align: right;">'.number_format($q[$j]['received'],2).'</td>
					// 	</tr><tr></tr>';
					// 	$table_content = $table_content.$table_part_content;

					// 	$total += (float)$q[$j]['received'];

					// 	//$gst += round((($p[$j]['gst_rate'] / 100) * (float)$p[$j]['amount']), 2);
					// }

					// if($p[0]["reference_no"] != "")
		   //          {
			  //           $reference_no_struc = '<tr>
		   //                  <td style="" width="90">Reference No.:</td>
		   //                  <td width="150">'.$p[0]["reference_no"].'</td>
		   //              </tr>';
	    //         	}
	    //         	else
	    //         	{
	    //         		$reference_no_struc = '';
	    //         	}
					
	            	$total_content_info = $this->calculation_content($obj_pdf, 'receipt', $q);
	            	$total = $total_content_info['total'];
	            	$table_content_height = $total_content_info['table_content_height'];

	            	$this->display_item($obj_pdf, 'receipt', $q, $table_content_height);

					$obj_pdf->SetY(-50);
			        
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->SetXY(17, 190);

					$content2 = '<table style="border-collapse: collapse;" border="0">
					<tbody>
					<tr>
					<td style="width: 100%;"><hr /></td>
					</tr>
					</tbody>
					</table>';

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
								<td style="width: 63%;"><span style="font-size: 8pt;">'. $q[0]["currency"] .': '. $this->spell_number_dollar($total) .'</span></td>
								<td style="width: 17%;">Total</td>
								<td style="width: 20%; text-align: right;">
								<div style="text-align: right;"><strong>'. number_format($total,2) .'</strong></div>
								<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
								</td>
								</tr>
								</tbody>
								</table>
								<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td>
								<p style="font-size: 8pt;">&nbsp;</p>
								<p style="font-size: 8pt;"><span style="font-size: 8pt;">Received by '. $p[0]["payment_mode_name"] .'<br />Reference No. '. $p[0]["reference_no"] .'</span></p>
								<p style="font-size: 8pt;"><span style="font-size: 8pt;">E. &amp; O.E</span></p>
								</td>
								</tr>
								</tbody>
								</table>';

					if($gst_rate == 0){
						$content2 .= $content3;
					}else{
						$content2 .= $content_gst . $content3;
					}
					$obj_pdf->SetAutoPageBreak(TRUE, 10);
					$obj_pdf->writeHTML($content2, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/receipt/'.$q[0]["invoice_no"].'.pdf', 'F');

					// output: http://
	    			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

					array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/receipt/'.$q[0]["invoice_no"].'.pdf');
					//echo ("123");
				}
				echo json_encode(array("link" => $array_link));
				
			}
		}
		else if($_POST["tab"] == "credit_note")
		{
			$credit_note_id = $_POST["credit_note_id"];
			$pre_printed = $_POST["pre-printed"];
			$own_header = isset($pre_printed)?($pre_printed === 'true')? true: false: true;

			//echo json_encode(count($billing_id));
			$array_link = [];
			if(count($credit_note_id) != 0)
			{
				for($i = 0; $i < count($credit_note_id); $i++)
				{
					$q = $this->db->query("select credit_note.*, billing_credit_note_record.received, billing.firm_id, billing.id, billing.company_code, billing.invoice_date, billing.invoice_no, billing.currency_id, billing.amount, client.company_name, client.postal_code, client.street_name, client.building_name, client.unit_no1, client.unit_no2, currency.currency from billing_credit_note_record left join billing on billing.id = billing_credit_note_record.billing_id left join client on client.company_code = billing.company_code left join currency on currency.id = billing.currency_id left join credit_note on credit_note.id = credit_note_id where credit_note_id ='".$credit_note_id[$i]."'");

			       	$q = $q->result_array();

			       	$p = $this->db->query("select credit_note.* from credit_note where credit_note.id ='".$credit_note_id[$i]."'");

			       	$p = $p->result_array();

			       	$query = $this->db->query("select * from firm where id = '".$q[0]["firm_id"]."'");

					$query = $query->result_array();

					/*<img id="image" src="uploads/logo/'.$query[0]["file_name"].'" alt="logo" height="50" width="50" style="float:left"/>*/

			       	//echo json_encode($query);

			       	$header_company_info = $this->write_header($q[0]["firm_id"], $own_header);
			       	$receiver_info		 = $this->receiver_info('credit note', '', $q);

			       	$this->load->helper('pdf_helper');

		    		// create new PDF document
				    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Receipt";
					$obj_pdf->SetTitle($title);
					//$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
					$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs=$header_company_info . $receiver_info, $tc=array(0,0,0), $lc=array(0,0,0));

					//$obj_pdf->setFooterData(array(0,0,0), array(0,0,0));
					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+10);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+53, PDF_MARGIN_RIGHT+3);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();
					//$content = file_get_contents( $_SERVER['DOCUMENT_ROOT'].'dot/themes/default/views/index.php' );
					//ob_start();
					    // we can have any view part here like HTML, PHP etc
					    
					    //echo $content;
						//$obj_pdf->WriteHTML( ob_get_contents() );
					//ob_end_clean();
					$table_content = "";
					$total = 0;
					//$gst = 0;

					for($j = 0; $j < count($q); $j++)
					{
						$table_part_content = '<tr class="item-row">
							<td style="padding: 5px;">'.$q[$j]['invoice_no'].'</td>
							<td style="padding: 5px;text-align: right;">'.number_format($q[$j]['received'],2).'</td>
						</tr><tr></tr>';
						$table_content = $table_content.$table_part_content;

						$total += (float)$q[$j]['received'];

						//$gst += round((($p[$j]['gst_rate'] / 100) * (float)$p[$j]['amount']), 2);
					}

					if($p[0]["reference_no"] != "")
		            {
			            $reference_no_struc = '<tr>
		                    <td style="" width="90">Reference No.:</td>
		                    <td width="150">'.$p[0]["reference_no"].'</td>
		                </tr>';
	            	}
	            	else
	            	{
	            		$reference_no_struc = '';
	            	}

	            	$total_content_info = $this->calculation_content($obj_pdf, 'receipt', $q);
	            	$total = $total_content_info['total'];
	            	$table_content_height = $total_content_info['table_content_height'];

	            	$this->display_item($obj_pdf, 'receipt', $q, $table_content_height);
	            	
					$obj_pdf->SetY(-50);
			        
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->SetXY(17, 190);

					$content2 = '<table style="border-collapse: collapse;" border="0">
					<tbody>
					<tr>
					<td style="width: 100%;"><hr /></td>
					</tr>
					</tbody>
					</table>';

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
								<td style="width: 63%;"><span style="font-size: 8pt;">'. $q[0]["currency"] .': '. $this->spell_number_dollar($total) .'</span></td>
								<td style="width: 17%;">Total</td>
								<td style="width: 20%; text-align: right;">
								<div style="text-align: right;"><strong>'. number_format($total,2) .'</strong></div>
								<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
								</td>
								</tr>
								</tbody>
								</table>
								<table style="width: 100%; border-collapse: collapse;" border="0">
								<tbody>
								<tr>
								<td>
								<p style="font-size: 8pt;"><br/><br/><span style="font-size: 8pt;">E. &amp; O.E</span></p>
								</td>
								</tr>
								</tbody>
								</table>';

					if($gst_rate == 0){
						$content2 .= $content3;
					}else{
						$content2 .= $content_gst . $content3;
					}
					$obj_pdf->SetAutoPageBreak(TRUE, 10);
					$obj_pdf->writeHTML($content2, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/credit_note/'.$q[0]["invoice_no"].'.pdf', 'F');

					// output: http://
	    			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

					array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/credit_note/'.$q[0]["invoice_no"].'.pdf');
					//echo ("123");
				}
				echo json_encode(array("link" => $array_link));
				
			}
		}
		
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

	public function receiver_info($document_type, $gst_number_display, $q)
	{
		$address = $this->write_address(ucwords(strtolower($q[0]["street_name"])), $q[0]["unit_no1"], $q[0]["unit_no2"], ucwords(strtolower($q[0]['building_name'])), $q[0]["postal_code"], 'letter with comma');
		$add_info = '';
		$description_title = '';

		if($document_type == "invoice")
		{
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["invoice_date"])));
			$document_num  = $q[0]["invoice_no"];

			$description_title = "Description";
			$title_font_size = '22pt';

			$add_info = '<tr><td style="width: 73%; text-align:left;"><strong style="font-size: 10pt;">Bill To:</strong></td>' . $gst_number_display . '</tr>';
		}
		elseif($document_type == "receipt")
		{
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["receipt_date"])));
			$document_num  = $q[0]["receipt_no"];

			$title_font_size = '22pt';
		}
		elseif($document_type == "credit note")
		{
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["credit_note_date"])));
			$document_num  = $q[0]["credit_note_no"];

			$title_font_size = '17pt';
		}

		$receiver_info = '<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
						<tbody>'
						.$add_info.
						'<tr>
						<td style="width: 64.7477%; font-weight: normal; font-size: 12px;">
						<table style="width: 107.191%; border-collapse: collapse; height: 81px;" border="0">
						<tbody>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$q[0]["company_name"].'</span></strong></td>
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
						<td style="width: 15.5%; height: 17px;"><strong><span style="text-decoration: underline;">'. $q[0]["currency"] .'</span></strong></td>
						</tr>
						</tbody>
					</table>
					';

		return $receiver_info;
	}

	public function calculation_content($obj_pdf, $document_type, $q)	// calculate height, gst subtotal and so on.
	{
		$content = '';
		$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
		$table_content_end = '</tbody></table>';
		$sub_total = 0;

		$content .= $table_content_start;

		for($j = 0; $j < count($q); $j++)
		{	
			if($document_type == 'receipt')
			{
				$record_no = $q[$j]['invoice_no'];
				$current_amount = $q[$j]['received'];
			}

			$amount = $current_amount;

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
									<p style="text-align: justify;">'. nl2br($record_no). '</p>
									</td>
									<td style="width: 15.5%; text-align: right;">'.$amount.'</td>
								  </tr>';

			$content = $content.$table_part_content;

			$total += (float)$current_amount;
		}

		$content = $content . $table_content_end;	// add in end of table

		// $total = $sub_total + $gst;

		$table_content_height = $obj_pdf->getStringHeight(1000, $content);

		return array(
					'table_content_height' => $table_content_height, 
					'total'			   	   => $total
				);
	}

	public function display_item($obj_pdf, $document_type, $q, $table_content_height)
	{
		$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
		$table_content_end = '</tbody></table>';

		// to display content
		for($j = 0; $j < count($q); $j++)
		{
			if($document_type == 'receipt')
			{
				$record_no = $q[$j]['invoice_no'];
				$current_amount = $q[$j]['received'];
			}

			$amount = $current_amount;

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
									<p style="text-align: justify;">'. nl2br($record_no). '</p>
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
