<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');
class SendRegisterControllerEmail extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library(array('encryption'));
		$this->load->model(array('db_model', 'master_model'));
	}

	public function send_email()
	{
		//$company_code = "company_1563939781";
		$firm_id = "26"; //need change
		$total_column = 300; //need change
		$number_need_to_send = 2; //need change

		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        
        $spreadsheet = $reader->load($_SERVER['DOCUMENT_ROOT']."/secretary/assets/uploads/file/novelty/aaa_sbf_others.xls");

        $new_sheet = $spreadsheet->getActiveSheet();

        for($t = 2; $t < $total_column; $t++)
        {
        	$contact_email = strtoupper($new_sheet->getCell('C'.$t)->getValue());
        	$company_code = $new_sheet->getCell('E'.$t)->getValue();
        	$check_status = $new_sheet->getCell('F'.$t)->getValue();

        	if($number_need_to_send > 0)
        	{
	        	if($check_status != "Sent")
	        	{
					$get_client_name = $this->db->query("select company_name from client where company_code='".$company_code."' AND client.deleted != 1");

					$get_client_name = $get_client_name->result_array();

					$client_name = $this->encryption->decrypt($get_client_name[0]["company_name"]);

					$this->load->helper('pdf_helper');

					// create new PDF document
				    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Letter of Confirmation";
					$obj_pdf->SetTitle($title);

					$header_company_info = '';

					$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
					$header_company_info,
					$tc=array(0,0,0), $lc=array(0,0,0));

					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
					$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();
					$obj_pdf->SetTopMargin(13);

					$contents = '<p style="text-align: center;"><strong><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong>(the &ldquo;<strong>Company</strong>&rdquo;)<br />(Company Registration No.: <span class="myclass mceNonEditable">{{UEN}}</span>)<br />(Incorporated in the Republic of Singapore)</p>
						<p style="text-align: justify;">RESOLUTIONS IN WRITING PASSED PURSUANT TO THE CONSTITUTION OF THE COMPANY</p><hr style="height: 1px; border: none; color: #333; background-color: #333;" />
						<table style="width: 100.063%; border-collapse: collapse; height: 40px;" border="0">
						<tbody>
						<tr style="height: 10px;">
						<td style="text-align: justify;height: 10px;"></td>
						</tr>
						<tr style="height: 10px;">
						<td style="text-align: justify;height: 10px; padding: 0px;" align="left"><strong>NEW FILING REQUIREMENT ON REGISTER OF REGISTRABLE CONTROLLERS (the &ldquo;<em>RORC</em>&rdquo;) WITH ACRA</strong></td>
						</tr>
						</tbody>
						</table>
						<p style="text-align: justify;">RESOLVED:</p>
						<table style="width: 100.063%; border-collapse: collapse; height: 10px;">
						<tbody>
						<tr style="height: 60px;">
						<td style="width: 7.11596%; height: 60px;">
						<p>1.</p>
						</td>
						<td style="width: 92.9471%; height: 60px; text-align: justify;">
						<p>The Company shall comply with the New Filing Requirement and that ACUMEN ALPHA ADVISORY PTE. LTD. (the &ldquo;<strong>RFA&rdquo;</strong>) be authorised to update the RORC information as affirmed by the Board and to lodge the same with the ACRA within the prescribed filing deadline as set by the ACRA from time to time.</p>
						</td>
						</tr>
						<tr style="height: 110px;">
						<td style="width: 7.11596%; height: 110px;">
						<p>2.</p>
						</td>
						<td style="width: 92.9471%; height: 110px; text-align: justify;">
						<p>In assisting the Company to comply with the current laws and the New Filing Requirement and going forward, the RFA be tasked to take the necessary actions including, sending notices to the relevant parties for purpose of updating the RORC in compliance with the Companies Act and the ACRA-issued guidance published on the ACRA website or any amendments to the Act as regards RORC from time to time; recording any response to amend the RORC from the controller or any relevant party including nil response, and forthwith updating and filing the updated RORC information with ACRA for and on behalf of the Company.</p>
						</td>
						</tr>
						<tr>
						<td style="width: 7.11596%;">
						<p>3.</p>
						</td>
						<td style="width: 92.9471%; text-align: justify;">
						<p>The RFA be authorised to complete, and lodge all necessary documents and/or forms accordingly with the ACRA pursuant to the resolutions passed herein.</p>
						</td>
						</tr>
						</tbody>
						</table>
						<p>Dated this</p>
						<p style="text-align: center;"><strong>D I R E C T O R (S)</strong></p>
						<p><span class="myclass mceNonEditable">{{Directors name - all}}</span></p>';

					$pattern = "/{{[^}}]*}}/";
					$subject = $contents;
					preg_match_all($pattern, $subject, $matches);
					
					$new_contents = $this->replaceToggle($matches[0], $company_code, null, $contents);

					$obj_pdf->writeHTML($new_contents, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/reso_reg_controller/DRIW-Reg Of Controller ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $this->myUrlEncode($client_name)).').pdf', 'F');
					
					// output: http://
					//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
					$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
					//array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/reso_reg_controller/DRIW-Reg Of Controller ('.$client_name.').pdf');

					//echo json_encode(array("link" => $array_link)); 

					$pdf_link = array();
					$pdf_link['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/reso_reg_controller/DRIW-Reg Of Controller ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $this->myUrlEncode($client_name)).').pdf'));
					$pdf_link['name'] = 'DRIW-Reg Of Controller ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $this->myUrlEncode($client_name)).').pdf';

					$pdf_link1 = array();
					$pdf_link1['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/assets/uploads/file/novelty/ACRA Letter (sample).pdf'));
					$pdf_link1['name'] = 'ACRA Letter (sample).pdf';

					$pdf_link2 = array();
					$pdf_link2['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/assets/uploads/file/novelty/DECLARATION FOR CONTROLLERS.pdf'));
					$pdf_link2['name'] = 'DECLARATION FOR CONTROLLERS.pdf';

					$pdf_link3 = array();
					$pdf_link3['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/assets/uploads/file/novelty/DECLARATION OF NOMINEE DIRECTOR.pdf'));
					$pdf_link3['name'] = 'DECLARATION OF NOMINEE DIRECTOR.pdf';

					if($firm_id == '26')
					{
						$pdf_link4 = array();
						$pdf_link4['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/assets/uploads/file/novelty/Notice Letter Reg of controller (SBF).pdf'));
						$pdf_link4['name'] = 'Notice Letter Reg of controller (SBF).pdf';
					}
					else
					{
						$pdf_link4 = array();
						$pdf_link4['content'] = base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'secretary/assets/uploads/file/novelty/Notice Letter Reg of controller (Novelty).pdf'));
						$pdf_link4['name'] = 'Notice Letter Reg of controller (Novelty).pdf';
					}
					
					$attach = array();
			        array_push($attach, $pdf_link);
			        array_push($attach, $pdf_link1);
			        array_push($attach, $pdf_link2);
			        array_push($attach, $pdf_link3);
			        array_push($attach, $pdf_link4);

			        $msg = file_get_contents('./themes/default/views/email_templates/register_of_controller.html');
			        //$msg = "";
			  //       if($firm_id == '26')
					// {
					// 	$email_detail['message'] = $msg.'<p>Best regards,<br />Karn Lee<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 160 Robinson Road, #26-10 Singapore Business Federation Center (SBF Center), Singapore 068914<br />Tel: (+65) 6222 0028</p>';
					// }
					// else
					// {
			  //       	$email_detail['message'] = $msg.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 6246 8801 / (+ 65) 6246 8802</p>';
			  //       }

			  //       $email_detail['email'] = json_encode(array(array("email"=> "JUSTIN@AAA-GLOBAL.COM")));
				 //        //json_encode(array_unique($billing_email_list));
			  //       $email_detail['subject'] = "Updating Registrable Controllers’ Information With ACRA";

			  //       $email_detail['from_email'] = json_encode(array("name" => "ACUMEN ALPHA ADVISORY PTE. LTD.", "email" => "admin@aaa-global.com"));//'admin@bizfiles.com.sg';
			  //       $email_detail['from_name'] = "ACUMEN ALPHA ADVISORY PTE. LTD.";
			  //       $email_detail['attachment'] = json_encode($attach);
			  //       $email_detail['cc'] = null; //json_encode(array(array("email" => $cc_email)));
			  //       //looi@aaa-global.com, corpsec@aaa-global.com
			  //       $email_detail['bcc'] = null;
			  //       $email_detail['sended'] = 0;
			  //       $email_detail['type'] = 'controller';
					// $this->db->insert("email_queue",$email_detail);


					$from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY SECRETARY', "email" => "admin@aaa-global.com"));
					if($firm_id == '26')
					{
						$email_message = $msg.'<p>Best regards,<br />Karn Lee<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 160 Robinson Road, #26-10 Singapore Business Federation Center (SBF Center), Singapore 068914<br />Tel: (+65) 6222 0028</p>';
					}
					else
					{
			        	$email_message = $msg.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMEN ALPHA ADVISORY PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 6246 8801 / (+ 65) 6246 8802</p>';
			        }
			        $email_subject = 'Updating Registrable Controllers’ Information With ACRA';
			        $email = json_encode(array(array("email"=> trim($contact_email))));
			        //$cc = json_encode(array(array("email" => "corpsec@aaa-global.com"))); //corpsec@aaa-global.com
			        $cc = null;
			        $this->sma->send_by_sendinblue($email_subject, $from_email, $email, $cc, $email_message,json_encode($attach));

			        $new_sheet->setCellValue('F'.$t, "Sent");
			        $number_need_to_send -= 1;
			    }
			}
			else
			{
				break;
			}
	    }

	    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
                 
        $filename = $_SERVER['DOCUMENT_ROOT'].'/secretary/assets/uploads/file/novelty/aaa_sbf_others.xls';

        $writer->save($filename);
	}

	public function myUrlEncode($string) {
	    $replacements = array('');
	    $entities = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
	    return str_replace($entities, $replacements, $string);
	}

	public function replaceToggle($match, $company_code, $firm_id, $new_contents, $id = null, $document_name = null, $value = null)
   	{
   		$content = "";

   		if(count($match) != 0)
   		{
	   		for($r = 0; $r < count($match); $r++)
			{
				$string1 = (str_replace('{{', '',$match[$r]));
				$string2 = (str_replace('}}', '',$string1));

				if($string2 == "Company current name")
				{
					$replace_string = $match[$r];

					$get_client_name = $this->db->query("select company_name from client where company_code='".$company_code."' AND client.deleted != 1");

					$get_client_name = $get_client_name->result_array();

					$content = $this->encryption->decrypt($get_client_name[0]["company_name"]);
				}
				elseif($string2 == "UEN")
				{
					$replace_string = $match[$r];

					$get_client_registration_no = $this->db->query("select registration_no from client where company_code='".$company_code."' AND client.deleted != 1");

					$get_client_registration_no = $get_client_registration_no->result_array();
				
					$content = $this->encryption->decrypt($get_client_registration_no[0]["registration_no"]);
					
				}
		   		elseif($string2 == "Directors name - all")
				{
					$replace_string = $match[$r];
					$content = '';

					$director = "";

					$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND (client_officers.position = 1 || client_officers.position = 8)");

					$get_directors = $get_directors->result_array();
					$number_of_director = count($get_directors);
					$director_array = array();
					for($i = 0; $i < count($get_directors); $i++)//0,2,4,6 and 8 are even numbers
					{	
						array_push($director_array, $this->encryption->decrypt($get_directors[$i]["name"]));
						$number_of_director -= 1;

						if(count($director_array) == 2)
						{
							// $director = $director."<p>&nbsp;</p><p>&nbsp;</p>________________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;________________________________<br/>".$director_array[0]."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$director_array[1];

							$director = $director.
							'<table style="width: 100%; border-collapse: collapse;"><tbody>
							<tr style="height: 89px;">
							<td style="width: 40%; height: 89px;">
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							_______________________________<br/>
							'.$director_array[0].'
							</td>
							<td style="width: 20%; height: 89px;">
							</td>
							<td style="width: 40%; height: 89px;">
							<p>&nbsp;</p>
							<p>&nbsp;</p>
							_______________________________<br/>
							'.$director_array[1].'
							</td>
							</tr>
							</tbody></table>';

							$director_array = array();
						}
						else if(count($director_array) == 1 && $number_of_director == 0)
						{
							$director = $director."<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$this->encryption->decrypt($get_directors[$i]["name"]);
						}
					}
					
					$content = $director;
				}

				$new_contents = str_replace($replace_string, $content, $new_contents);
			}
		}

		return $new_contents;
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

	public function write_header($firm_id, $use_own_header)
	{
		$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
												LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
												LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
												LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
												where firm.id = '".$firm_id."'");
		$query = $query->result_array();
		//echo json_encode($query);
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

			if($query[0]["address_type"] == "Local")
			{
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
}

class MYPDF extends TCPDF {
	public function Header() {
		$headerData = $this->getHeaderData();
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
   }

   public function Footer() {
   }
}
