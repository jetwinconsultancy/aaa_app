<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');
class Createpvreceiptpdf extends MY_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	

	public function index()
	{
		
	}

	// public function delete_receipt_pdf()
 //    {
 //        $this->load->helper("file");
 //        delete_files('./pdf/pv_receipt');

 //        echo json_encode(array("status" => true));
 //    }

	public function create_pv_receipt_pdf()
	{
		if($_POST["tab"] == "pv_receipt")
		{
			$pv_id = $_POST["pv_id"];
			$pre_printed = $_POST["pre-printed"];

			$array_link = [];
			// if(count($pv_id) != 0)
			// {
				// for($i = 0; $i < count($billing_id); $i++)
				// {
			//payment_receipt.postal_code, payment_receipt.street_name, payment_receipt.building_name, payment_receipt.unit_no1, payment_receipt.unit_no2
					$q = $this->db->query("select payment_receipt.firm_id, payment_receipt.id, payment_receipt.receipt_date, payment_receipt.receipt_no, payment_receipt.currency_id, payment_receipt.amount, payment_receipt.rate, payment_receipt.client_name, payment_receipt.address, currency.currency from payment_receipt left join currency on currency.id = payment_receipt.currency_id where payment_receipt.id =".$pv_id."");

			       	$q = $q->result_array();

			       	$p = $this->db->query("select payment_receipt.id, payment_receipt.bank_acc_id, payment_receipt.cheque_number, payment_receipt_service.payment_receipt_id as payment_receipt_service_payment_receipt_id, payment_receipt_service.payment_receipt_description, payment_receipt_service.amount, bank_info.banker, bank_info.account_number, currency.currency, users.first_name, users.last_name from payment_receipt left join payment_receipt_service on payment_receipt_service.payment_receipt_id = payment_receipt.id left join bank_info on bank_info.id = payment_receipt.bank_acc_id left join currency on currency.id = bank_info.currency left join users on users.id = payment_receipt.approved_by where payment_receipt.id =".$pv_id." ORDER BY payment_receipt_service.id");

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
					$title = "Receipt";
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
					// if(!(empty($p[0]['gst_rate']) || ($p[0]['gst_rate'] == 0)))
					// {
					// 	$gst_number_display = '<td style="width: 25%; text-align:left; font-size: 8pt; font-weight:normal;">GST Reg. No. : '. $query[0]['registration_no'] .'</td>';
					// }
					// else
					// {
						$gst_number_display = '';
					//}

					$receiver_info = $this->receiver_info("Receipt", $gst_number_display, $q);

					$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
					$header_company_info.
					$receiver_info,
					$tc=array(0,0,0), $lc=array(0,0,0));

					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+13);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					if(40 > strlen($query[0]['name']))
					{
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+61, PDF_MARGIN_RIGHT+3);
					}
					else
					{
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+67, PDF_MARGIN_RIGHT+3);
					}
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();

					$content = '';
					$table_content_start = '<table style="width: 99.8318%; border-collapse: collapse; height: 73px;" border="0"><tbody>';
					$table_content_end = '</tbody></table>';
					$sub_total = 0;
					$gst = 0;
					$gst_rate = 0;

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

						// $period_start_date 	= !(empty($p[$j]["period_start_date"]))? ' from ' . date('d F Y', str_replace('/', '-', strtotime($p[$j]["period_start_date"]))) : '';
						// $period_end_date 	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', str_replace('/', '-', strtotime($p[$j]["period_end_date"]))) : '';

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 86.9273%;%; text-align: left;">
												<p style="text-align: justify;">'. nl2br($p[$j]['payment_receipt_description']). '<strong></strong></p>
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

						// $period_start_date 	= !(empty($p[$j]["period_start_date"]))? ' from ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_start_date"]))) : '';
						// $period_end_date   	= !(empty($p[$j]["period_end_date"]))? ' to ' . date('d F Y', strtotime(str_replace('/', '-', $p[$j]["period_end_date"]))) : '';

						$table_part_content = '<tr style="height: 17px;">
												<td style="width: 86.9273%;%; text-align: left;">
												<p style="text-align: justify;">'. nl2br($p[$j]['payment_receipt_description']). '<strong></strong></p>
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
					$obj_pdf->SetXY(17, 183);

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
								<td style="width: 63%;"><span style="font-size: 8pt;">SGD: '. $this->spell_number_dollar(sprintf('%0.2f', $converted_total)) .'</span></td>
								<td style="width: 17%;">Equivalent To: </td>
								<td style="width: 20%; text-align: right;">
								<div style="text-align: right;"><strong>' . number_format($converted_total, 2) . '</strong></div>
								<div class="divA" style="border-top: 1px solid #000; border-bottom: 1px solid #000; line-height: 1px;">&nbsp;</div>
								</td>
								</tr>';
					}
					
					// <table style="width: 100%; border-collapse: collapse; height: 34px;" border="0">
					// 			<tbody>
					// 			<tr style="height: 17px;">
					// 			<td style="width: 63%; height: 17px;">&nbsp;</td>
					// 			<td style="width: 17%; height: 17px;">Subtotal</td>
					// 			<td style="width: 20%; height: 17px; text-align: right;"><strong>'. number_format($sub_total,2) .'</strong></td>
					// 			</tr>
					// 			<tr style="height: 17px;">
					// 			<td style="width: 63%; height: 17px;">&nbsp;</td>
					// 			<td style="width: 17%; height: 17px;">GST '. $gst_rate .'%</td>
					// 			<td style="width: 20%; height: 17px; text-align: right;">'. number_format($gst,2) .'</td>
					// 			</tr>
					// 			</tbody>
					// 			</table>
					$content_gst  = '
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
								<td style="width: 63%;"><span style="font-size: 8pt;">'. $q[0]["currency"] .': '. $this->spell_number_dollar(sprintf('%0.2f', $total)) .'</span></td>
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
								<p style="font-size: 8pt;"><span style="font-size: 8pt;">Paid by Cheque<br />Reference No. '. $p[0]["cheque_number"] .'<br />Receiving Bank: '. (($p[0]["bank_acc_id"] != "0")?$p[0]["banker"] . ' (' .$p[0]["currency"]. ') - '. $p[0]["account_number"]:"") .'</span></p>

								</td>
								</tr>
								</tbody>
								</table>';

					$content_signing  = '
							<table style="width: 100%; border-collapse: collapse;" border="0">
							<tbody>
							<tr>
							<td style="width: 33.3333%;">
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />' . $p[0]["last_name"] .' '. $p[0]["first_name"] .'<hr />
								Approved By
							</td>
							<td style="width: 33.3333%;">
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><hr />
								Prepared By
							</td>
							<td style="width: 33.3333%;">
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><hr />
								Received By
							</td>
							</tr>
							</tbody>
							</table>';


					if($gst_rate == 0){
						$content2 .= $content3 . $content_signing;
					}else{
						$content2 .= $content_gst . $content3;
					}

					//$billing_bank_info["bank_info_id"] = $bank[0]["id"];
					//$this->db->update("billing",$billing_bank_info,array("id" => $q[0]["id"]));

					$obj_pdf->SetAutoPageBreak(TRUE, 10);
					$obj_pdf->writeHTML($content2, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/payment_voucher/'.$q[0]["payment_voucher_no"].'.pdf', 'F');

					chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/payment_voucher/'.$q[0]["payment_voucher_no"].'.pdf',0644);

					// output: http://
	    			//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
					$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
					array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/'.$this->systemName.'/pdf/payment_voucher/'.$q[0]["payment_voucher_no"].'.pdf');
					//echo ("123");
				//}
				echo json_encode(array("link" => $array_link)); 
				
			//}
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
			$br = '';
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

	public function write_header($firm_id, $use_own_header)
	{
		$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
												LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
												LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
												LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
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
			if($query[0]["branch_name"] != null)
			{
				$branch_name = 'Branch: '.$query[0]["branch_name"].'<br />';
			}
			else
			{
				$branch_name = '';
			}

			if($query[0]["address_type"] == "Local")
			{
				//$address = $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"];
				$address = $this->write_address($query[0]["street_name"], $query[0]["unit_no1"], $query[0]["unit_no2"], $query[0]["building_name"], $query[0]["postal_code"], "comma");
			}
			else
			{
				$address = $query[0]["foreign_address1"] . $query[0]["foreign_address2"] . $query[0]["foreign_address3"];
			}

			if(!empty($query[0]["fax"]))
			{
				$fax_text = 'Fax: '. $query[0]["fax"];
			}
			else
			{
				$fax_text = '';
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
						<td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />'.$branch_name.'Address: '. $address .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; '. $fax_text .'&nbsp;</span></td>
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
		// if($q[0]["use_foreign_add_as_billing_add"] == 1)
		// {
		// 	$address = $this->write_foreign_address(ucwords(strtolower($q[0]["foreign_add_1"])), ucwords(strtolower($q[0]["foreign_add_2"])), ucwords(strtolower($q[0]["foreign_add_3"])));
		// }
		// else
		// {
		// 	$address = $this->write_address(ucwords(strtolower($q[0]["street_name"])), $q[0]["unit_no1"], $q[0]["unit_no2"], ucwords(strtolower($q[0]['building_name'])), $q[0]["postal_code"], 'letter with comma');
		// }
		$add_info = '';
		$description_title = '';

		if($document_type == "Receipt")
		{
			$document_date = date('d F Y', strtotime(str_replace('/', '-', $q[0]["receipt_date"])));
			$document_num  = $q[0]["receipt_no"];

			$title_font_size = '22pt';
		}

		$receiver_info = '<table style="width: 100%; border-collapse: collapse; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
						<tbody>'
						.$add_info.
						'<tr>
						<td style="width: 64.7477%; font-weight: normal; font-size: 12px;">
						<table style="width: 107.191%; border-collapse: collapse; height: 81px;" border="0">
						<tbody>
						<tr style="height: 20px;">
						<td style="width: 100%; height: 20px; text-align: left;"><strong><span style="font-size: 10pt; font-family: arial, helvetica, sans-serif;">'.$q[0]["client_name"].'</span></strong></td>
						</tr>
						<tr style="height: 116px;">
						<td style="width: 100%; height: 41px; text-align: left;"><span style="font-size: 9pt; font-family: arial, helvetica, sans-serif;"><span style="font-family: arial, helvetica, sans-serif;">'. nl2br($q[0]["address"]) .'</span></span></td>
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
