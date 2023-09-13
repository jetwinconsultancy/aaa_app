<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');
class CreateListOfConfAuditor extends MY_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
		$this->load->library(array('encryption'));
		$this->load->model(array('db_model', 'master_model'));
	}

	public function create_pdf()
	{
		$company_code = $_POST["company_code"];
		$from = $_POST["letter_conf_auditor_date_from"];
		$to = $_POST["letter_conf_auditor_date_to"];
		//$recipient_name = $_POST["recipient_name"];
		//$pre_printed = $_POST["pre-printed"];
		$array_link = [];
		$generate = true;

		$list_of_confirmation_auditor = $this->master_model->get_all_list_of_confirmation_auditor($company_code, $_SESSION['group_id'], $from, $to);

		if($list_of_confirmation_auditor)
		{
			for($g = 0; $g < count($list_of_confirmation_auditor); $g++)
	        {
				if($list_of_confirmation_auditor[$g]->lodgement_date == "")
	           	{
	           		$generate = false;
	           		break;
				}
			}

			if($generate)
			{
				$this->load->helper('pdf_helper');

				// create new PDF document
			    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Letter of Confirmation";
				$obj_pdf->SetTitle($title);
				//$own_header = isset($pre_printed)?($pre_printed === 'true')? true: false: true;

				$header_company_info = $this->write_header($this->session->userdata('firm_id'), false);//$own_header

				$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=
				$header_company_info,
				$tc=array(0,0,0), $lc=array(0,0,0));

				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 13);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$obj_pdf->SetMargins(25, PDF_MARGIN_TOP + 25, 25);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);
				$obj_pdf->AddPage();
				$obj_pdf->SetTopMargin(27);

				$content = '<p style="text-align: center;"><strong>(Letterhead of Corporate Secretarial Firm or the Company)</strong></p>
					<p style="text-align: justify;">Date: {{from_range_date}}</p>
					<p style="text-align: justify;">{{Audit name}}<br />{{Audit Address}}</p>
					<p style="text-align: justify;">Dear Sirs</p>
					<p style="text-align: center;"><span style="text-decoration: underline;"><strong>COMPANY SECRETARY REPRESENTATION LETTER</strong></span></p>
					<p>&nbsp;</p>
					<p style="text-align: justify;">In connection with your audit of the financial statements of {{client name}} for the year/period ended {{year_end_date}}, we have submitted to your representative, minutes covering meetings of directors and shareholders and circular resolutions of directors and shareholders held on the dates stated below. These constitute a full and complete record of all meetings and circular resolutions of the period from {{to_range_date}} to the date of this letter.</p>
					<p style="text-align: justify;">In addition, other statutory and legal records required by the Companies Act (the &ldquo;Act&rdquo;) have been properly kept in accordance with the provisions of the Act and were made available to you in full.</p>
					<p>In chronological order,</p>
					<table style="height: 10px; width: 100.063%; border-collapse: collapse;" border="1">
					<tbody>
					<tr style="height: 10px;">
					<td style="width: 6.13988%;">
					<p>No.</p>
					</td>
					<td style="width: 21.379%; height: 10px; text-align: center;">
					<p>Date of meeting/resolution</p>
					</td>
					<td style="width: 71.5%; text-align: center;">
					<p>Minutes of meeting/Resolution</p>
					</td>
					</tr>
					<tr class="loop">
					<td style="width: 6.13988%;">
					<p>{{No}}</p>
					</td>
					<td style="width: 21.379%; text-align: center;">
					<p>{{Date}}</p>
					</td>
					<td style="width: 71.5%;">
					<p>{{Transaction}}</p>
					</td>
					</tr>
					</tbody>
					</table>
					<p style="text-align: justify;">&nbsp;</p>
					<p style="text-align: justify;">Yours sincerely,</p>
					<p style="text-align: justify;"><img src="img/Paul.JPG" width="100" height="50" /></p>
					<p style="text-align: justify;">Paul<br />Manager of Corporate Secretary<br />{{Firm Name}}</p>';

				// if(empty($recipient_name))
				// {
				// 	$content = str_replace('{{Recipient name}}', '<span style="text-align: justify;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>', $content);
				// }
				// else
				// {
				// 	$content = str_replace('{{Recipient name}}', $recipient_name, $content);
				// }

				if($from == "")
				{
					if($list_of_confirmation_auditor[0]->effective_date != "")
		           	{
		           		$latest_format_date = date('d F Y', strtotime($this->formatDate($list_of_confirmation_auditor[0]->effective_date)));

						$content = str_replace('{{from_range_date}}', $latest_format_date, $content);
					}
		    		else
		    		{
		    			$content = str_replace('{{from_range_date}}', "", $content);
		    		}
				}
				else
				{
					$latest_format_date = date('d F Y', strtotime($this->formatDate($from)));
					$content = str_replace('{{from_range_date}}', $latest_format_date, $content);
				}
				if($to == "")
				{
					$content = str_replace('{{to_range_date}}', date("d F Y"), $content);
				}
				else
				{
					$latest_format_date = date('d F Y', strtotime($this->formatDate($to)));
					$content = str_replace('{{to_range_date}}', $latest_format_date, $content);
				}
				
				$content = str_replace('{{client name}}', $list_of_confirmation_auditor[0]->company_name, $content);

				//filing
				$filing_info = $this->db->query("select company_code, year_end, agm, due_date_175, 175_extended_to, due_date_201, 201_extended_to from filing where company_code='".$company_code."' order by filing.id DESC LIMIT 2");
				                
		        if ($filing_info->num_rows() > 0) 
		        {
		        	$filing_info = $filing_info->result_array();
		        	$content = str_replace('{{year_end_date}}', $filing_info[0]["year_end"], $content);
		        }
		        else
		        {
		        	$content = str_replace('{{year_end_date}}', "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;", $content);
		        }

		        //Auditor
		        $get_auditor_name = $this->db->query("select officer_company.* from client_officers left join officer_company on client_officers.officer_id = officer_company.id AND client_officers.field_type = officer_company.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 5");

		        if($get_auditor_name->num_rows() > 0)
		        {
					$get_auditor_name = $get_auditor_name->result_array();
					$content = str_replace('{{Audit name}}', $this->encryption->decrypt($get_auditor_name[0]["company_name"]), $content);
					$address = array(
						'type' 			=> $get_auditor_name[0]['address_type'],
						'street_name1' 	=> strtoupper($get_auditor_name[0]["company_street_name"]),
						'unit_no1'		=> strtoupper($get_auditor_name[0]["company_unit_no1"]),
						'unit_no2'		=> strtoupper($get_auditor_name[0]["company_unit_no2"]),
						'building_name1'=> strtoupper($get_auditor_name[0]["company_building_name"]),
						'postal_code1'	=> strtoupper($get_auditor_name[0]["company_postal_code"]),
						'foreign_address1' => strtoupper($get_auditor_name[0]["company_foreign_address1"]),
						'foreign_address2' => strtoupper($get_auditor_name[0]["company_foreign_address2"]),
						'foreign_address3' => strtoupper($get_auditor_name[0]["company_foreign_address3"])
					);
					$individual_address = $this->write_address_local_foreign($address, "letter", "big_cap");
					$content = str_replace('{{Audit Address}}', $individual_address, $content);
		        }
		        else
		        {
		        	$content = str_replace('{{Audit name}}', "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;", $content);
		        	$content = str_replace('{{Audit Address}}', "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;", $content);
		        }

		        //firm
		        $firm_query = $this->db->query("select firm.* from firm 
														where firm.id = '".$this->session->userdata('firm_id')."'");
				$firm_query = $firm_query->result_array();
				$content = str_replace('{{Firm Name}}', $firm_query[0]["name"], $content);

				//list of document
				$latest_table = "";
				if(strpos($content, '<tr class="loop"') !== false)
		    	{
		    		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $content, $abstract_string_array);
		    	
		        	for($g = 0; $g < count($list_of_confirmation_auditor); $g++)
		        	{
		    			$html_string = $abstract_string_array[0][0];

		    			if(strpos($html_string, '<p>{{No}}</p>') !== false)
		            	{
		        			$html_string = str_replace('<p>{{No}}</p>', $g+1, $html_string);
		        		}

		        		if(strpos($html_string, '<p>{{Date}}</p>') !== false)
		            	{
		            		if($list_of_confirmation_auditor[$g]->effective_date != "")
		            		{
								$latest_format_date = $this->formatDate($list_of_confirmation_auditor[$g]->effective_date);

			        			$html_string = str_replace('<p>{{Date}}</p>', date('d F Y', strtotime($latest_format_date)), $html_string);
			        		}
			        		else
			        		{
			        			$html_string = str_replace('<p>{{Date}}</p>', "", $html_string);
			        		}
		        		}

		        		if(strpos($html_string, '<p>{{Transaction}}</p>') !== false)
		            	{
		        			$html_string = str_replace('<p>{{Transaction}}</p>', $list_of_confirmation_auditor[$g]->transaction_task, $html_string);
		        		}

						$latest_table = $latest_table.$html_string;
		        	}

		        	$new_contents = str_replace($abstract_string_array[0][0], $latest_table, $content);
		        }

				$obj_pdf->writeHTML($new_contents, true, false, false, false, '');

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/letter_of_confirmation/letter_of_confirmation - '.$list_of_confirmation_auditor[0]->company_name.'.pdf', 'F');

				chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/letter_of_confirmation/letter_of_confirmation - '.$list_of_confirmation_auditor[0]->company_name.'.pdf',0644);

				// output: http://
				//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
				$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
				array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/'.$this->systemName.'/pdf/letter_of_confirmation/letter_of_confirmation - '.$list_of_confirmation_auditor[0]->company_name.'.pdf');

				echo json_encode(array("link" => $array_link, "generate" => 1));
			}
			else
			{
				echo json_encode(array("generate" => 2));
			} 
		}
		else
		{
			echo json_encode(array("generate" => 3));
		}
	}

	public function formatDate($date)
	{
		$date = explode('/', $date);
		$day = $date[0];
		$month   = $date[1];
		$year  = $date[2];
		$latest_format_date = $month.'/'.$day.'/'.$year;

		return $latest_format_date;
	}

	// latest version include foreign address. 
	public function write_address_local_foreign($address, $type, $style)
	{
		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ', <br/>';
			$br2 = ', <br/>';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}

		if($address['type'] == "Local")
		{
			// Add unit
			if(!empty($address['unit_no1']) && !empty($address['unit_no2']))
			{
				$unit = '#' . $address['unit_no1'] . '-' . $address['unit_no2'];
			}

			// Add building
			if(!empty($address['building_name1']) && !empty($unit))
			{
				$unit_building_name = $unit . ' ' . $address['building_name1'] . $br2;
			}
			elseif(!empty($unit))
			{
				$unit_building_name = $unit . $br2;
			}
			elseif(!empty($address['building_name1']))
			{
				$unit_building_name = $address['building_name1'] . $br2;
			}

			if($style == "big_cap")
			{
				$sg_word = 'SINGAPORE ';
			}
			else
			{
				$sg_word = 'Singapore ';
			}

			return $address['street_name1'] . $br1 . $unit_building_name . $sg_word . $address['postal_code1'];
		}
		else if($address['type'] == "Foreign")
		{
			$foreign_address1 = !empty($address["foreign_address1"])? $address["foreign_address1"]: '';

			if(!empty($address["foreign_address1"]))
			{
				if(substr($address["foreign_address1"], -1) == ",")
				{
					$foreign_address1 = rtrim($address["foreign_address1"],',');	// remove , if there is any at last character
				}
				else
				{
					$foreign_address1 = $address["foreign_address1"];
				}
			}

			if(!empty($address["foreign_address2"]))
			{
				if(substr($address["foreign_address2"], -1) == ",")
				{
					$foreign_address2 = $br1 . rtrim($address["foreign_address2"],',');		// remove , if there is any at last character
				}
				else
				{
					$foreign_address2 = $br1 . $address["foreign_address2"];
				}
			}
			else
			{
				$foreign_address2 = '';
			}

			$foreign_address3 = !empty($address["foreign_address3"])? $br2 . $address["foreign_address3"]: '';

			return $foreign_address1.$foreign_address2.$foreign_address3;
		}
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
