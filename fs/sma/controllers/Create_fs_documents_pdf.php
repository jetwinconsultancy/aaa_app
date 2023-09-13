<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');
class Create_fs_documents_pdf extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->load->helper('form');
		$this->load->model('fs_model');
		$this->load->model('fs_account_category_model');
		$this->load->model('fs_statements_model');
		$this->load->model('fs_replace_content_model');
	}

	public function index()
	{
		$array_link = [];

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
		// $obj_pdf->Output('output.pdf', 'D');

		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'financial_statement/pdf/document/front_page.pdf', 'F');

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

		array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/financial_statement/pdf/document/front_page.pdf');
		//echo ("123");
		echo json_encode(array("status" => 1, "link" => $array_link)); 
	}

	public function fs_report()
	{
		$firm_id = $this->session->userdata("firm_id");

		$array_link = [];
		$form_data = $this->input->post();
		// $fs_company_info_id = $this->session->userdata('fs_company_info_id');
		$fs_company_info_id = $form_data['fs_company_info_id'];
		$pre_printed = $form_data['pre_printed'];

		$this->load->helper('pdf_helper');

		// create new PDF document
	    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$obj_pdf->SetCreator(PDF_CREATOR);
		$title = "Financial Statement Report";
		$obj_pdf->SetTitle($title);

		$obj_pdf->SetFont("calibri", '', 11);

		// Add sections here
		$this->front_page($obj_pdf, $fs_company_info_id);

		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$this->Statement_by_directors($obj_pdf, $fs_company_info_id);
		$this->independent_auditors_report($obj_pdf, $fs_company_info_id, $firm_id, $pre_printed);

		// $this->statement_comprehensive_income($obj_pdf, $fs_company_info_id, $firm_id);
		// $this->statement_of_financial_position($obj_pdf, $fs_company_info_id, $firm_id);
		// $this->NTFS_report($obj_pdf, $fs_company_info_id, $firm_id);

		// $this->schedule_of_operating_expenses($obj_pdf, $fs_company_info_id);
		// $this->state_detailed_profit_loss($obj_pdf, $fs_company_info_id);

		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'fs/pdf/document/front_page.pdf', 'F');

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

		array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/fs/pdf/document/front_page.pdf');

		echo json_encode(array("status" => 1, "link" => $array_link));

	}

	public function front_page($obj_pdf, $fs_company_info_id)
	{
		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=1");
		$q = $q->result_array();

		$data = $this->fs_model->get_fs_company_info($fs_company_info_id);

		$firm_info = $this->db->query("SELECT * FROM firm WHERE id='" . $data[0]["firm_id"] . "'");
		$firm_info = $firm_info->result_array();

		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$obj_pdf->SetDefaultMonospacedFont('calibri');
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// $obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$obj_pdf->SetFont('calibri', '', 9);
		$obj_pdf->setFontSubsetting(false);
		$obj_pdf->setListIndentWidth(4);
		//$obj_pdf->setPrintFooter(false);
		
		$obj_pdf->AddPage();

		$pattern = "/{{[^}}]*}}/";
		$template = $q[0]["content"];
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			if($string2 == "set of accounts")
			{
				$first_set 		= $data[0]['first_set'];
				$last_fye_begin = $data[0]['current_fye_begin'];
				$final_year_end = $data[0]['current_fye_end'];

				if($first_set)
				{
					$content = '<strong>' . strtoupper($last_fye_begin) . '</strong><br><span style="font-style:normal;">(Date of incorporation)</span><strong><br/><br/> TO '. strtoupper($final_year_end) .'</strong>';
				}
				else 
				{
					$start_date_day = date('d', strtotime($data[0]['current_fye_begin']));

					$start_date = new DateTime(date('Y-m-d', strtotime($data[0]['current_fye_begin'])));
			        $start_date->modify('-1 day');
			        $start_date  = $start_date->format('Y-m-d');

			        $start_date = date_create($start_date);
					$end_date = date_create($data[0]['current_fye_end']);

					$interval 	= date_diff($start_date, $end_date);
					$interval_value_year = $interval->format('%y');

					if($interval_value_year == 1)
					{
						$content = '<strong>' . strtoupper($final_year_end) .'</strong>';
					}
					else
					{
						$content = '<strong>' . strtoupper($last_fye_begin) . '</strong><strong><br/><br/> TO '. strtoupper($final_year_end) .'</strong>';
					}
				}

				$template = str_replace($replace_string, $content, $template);
			}
		}

		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $template, "Front page", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');

		// show Company Info on left corner
		$obj_pdf->SetXY(30, 245);

		$company_info = '<strong><p style="font-size:11pt;">'. $firm_info[0]['name'] .'<br/>PUBLIC ACCOUNTANTS AND<br/>CHARTERED ACCOUNTANTS<br/>SINGAPORE</p></strong>';

		$obj_pdf->SetAutoPageBreak(TRUE, 10);
		$obj_pdf->writeHTML($company_info, true, false, false, false, '');
	}

	public function Statement_by_directors($obj_pdf, $fs_company_info_id)
	{
		$fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
		$fs_directors 	 = $this->fs_model->get_fs_appt_directors($fs_company_info_id);

		// print_r($fs_directors);

		$header_company_name = '<p style="font-size: 10pt; font-family: calibri; font-weight:normal; line-height: 1.5;"><strong>{{client name}}</strong><br/><i style="font-size:14pt;">Statement by director{_sing/plu s_}</i></p>';

		$pattern = "/{{[^}}]*}}/";
		preg_match_all($pattern, $header_company_name, $matches_header);

		$header_company_name = $this->fs_replace_content_model->replace_toggle($matches_header[0], $header_company_name, "Statement by Directors", $fs_company_info_id);
		$header_company_name = $this->fs_replace_content_model->replace_verbs_plural($header_company_name, "Statement by Directors", $fs_company_info_id);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_name, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP + 10, PDF_MARGIN_RIGHT);

		$obj_pdf->AddPage();

		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=2");
		$q = $q->result_array();

		$fs_company_info = $this->db->query("SELECT * FROM fs_company_info WHERE id=" . $fs_company_info_id);
		$fs_company_info = $fs_company_info->result_array();

		// $fs_company_director = $this->db->query("SELECT fs_company_type.company_type, fs_dir_statement_company.company_name, GROUP_CONCAT(fs_dir_statement_director.director_name) AS `directors_name`, fs_dir_statement_company.dir_begin_fy_no_of_share AS `direct_begin`, fs_dir_statement_company.dir_end_fy_no_of_share AS `direct_end`, fs_dir_statement_company.deem_begin_fy_no_of_share AS `deem_begin`, fs_dir_statement_company.deem_end_fy_no_of_share AS `deem_end`
		// 	FROM `fs_dir_statement_company` 
		// 	LEFT JOIN fs_company_type ON fs_company_type.id = fs_dir_statement_company.fs_company_type_id
		// 	LEFT JOIN fs_dir_statement_director ON fs_dir_statement_director.fs_dir_statement_company_id = fs_dir_statement_company.id 
		// 	WHERE fs_dir_statement_company.fs_company_info_id = ". $fs_company_info_id ." GROUP BY fs_dir_statement_company.fs_company_type_id, fs_dir_statement_company.company_name ORDER BY fs_dir_statement_director.id");

		$fs_company_director = $this->db->query("SELECT fs_company_type.company_type, fs_dir_statement_company.company_name, GROUP_CONCAT(fs_dir_statement_director.director_name) AS `directors_name`
													FROM `fs_dir_statement_company` 
													LEFT JOIN fs_company_type ON fs_company_type.id = fs_dir_statement_company.fs_company_type_id
													LEFT JOIN fs_dir_statement_director ON fs_dir_statement_director.fs_dir_statement_company_id = fs_dir_statement_company.id 
													WHERE fs_dir_statement_company.fs_company_info_id = ". $fs_company_info_id ." GROUP BY fs_dir_statement_company.fs_company_type_id, fs_dir_statement_company.company_name ORDER BY fs_dir_statement_director.id");

		// print_r($fs_company_director->result_array());

		$pattern = "/{{[^}}]*}}/";
		$template = $q[0]["content"];
		preg_match_all($pattern, $template, $matches);

		$latest_table = '';

		$total_begin_FY = 0;
  		$total_end_FY = 0;

  		if(!$fs_company_info[0]['has_director_interest'])
  		{
  			// echo strpos($template, '<table class="table_direct_interest" style="width: 100%; border-collapse: collapse;" border="0">');

  			if(strpos($template, '<table class="table_direct_interest" style="width: 100%; border-collapse: collapse;" border="0">') !== false)
	    	{
	    		$template = str_replace('<table class="table_direct_interest" style="width: 100%; border-collapse: collapse;" border="0">', '<table class="table_direct_interest" style="width: 100%; border-collapse: collapse; display:none;" border="0">', $template);
	    	}
  		}
  		else
  		{
  			if(strpos($template, '<tr class="director_and_shares"') !== false)
	    	{
	    		preg_match_all ('/<tr class="director_and_shares"(.+?)<\/tr>/s', $template, $abstract_string_array);

	    		// calculate total of begin no of share and end of no of share.
	    		for($x = 0; $x < count($fs_directors); $x++){
	    			$x_director = $this->fs_model->get_shares((int)$fs_directors[$x]['id'], $fs_company_info[0]['current_fye_end']);
	    			$total_begin_FY += $x_director[0]['begin_FY'];
	    			$total_end_FY 	+= $x_director[0]['end_FY'];
	    		}

	    		// display directors with more than 20% over total no. of share. 
	    		if($total_begin_FY > 0 || $total_end_FY > 0)
	    		{
	    			$percent_begin_FY = 0;
	    			$percent_end_FY = 0;

	    			for($g = 0; $g < count($fs_directors); $g++)
		        	{
	        			$director_html_string = $abstract_string_array[0][0];

		    			$director = $this->fs_model->get_shares((int)$fs_directors[$g]['id'], $fs_company_info[0]['current_fye_end']);

		    			$percent_begin_FY = (float)$director[0]['begin_FY'] / $total_begin_FY * 100;
	        			$percent_end_FY = (float)$director[0]['end_FY'] / $total_end_FY * 100;

	        			// echo json_encode($director[0]['end_FY']) . ", ";

	        			if($percent_begin_FY > 20 || $percent_end_FY > 20){

			    			// echo json_encode($director[0]) . "<br/>";

			        		if(strpos($director_html_string, '{{director name}}') !== false)
			            	{
			        			$director_html_string = str_replace('{{director name}}', $director[0]['name'], $director_html_string);
			        		}

			        		if(strpos($director_html_string, '{{Beginning of Direct Interest}}') !== false)
			            	{
			        			$director_html_string = str_replace('{{Beginning of Direct Interest}}', number_format($director[0]['begin_FY']), $director_html_string);
			        		}

			        		if(strpos($director_html_string, '{{End of Direct Interest}}') !== false)
			        		{
			        			$director_html_string = str_replace('{{End of Direct Interest}}', number_format($director[0]['end_FY']), $director_html_string);
			        		}

			        		if(strpos($director_html_string, '{{Beginning of Deemed Interest}}') !== false)
			        		{
			        			$director_html_string = str_replace('{{Beginning of Deemed Interest}}', '-', $director_html_string);
			        		}

			        		if(strpos($director_html_string, '{{End of Deemed Interest}}') !== false)
			            	{
			        			$director_html_string = str_replace('{{End of Deemed Interest}}', '-', $director_html_string);
			        		}

			        		$latest_table = $latest_table.$director_html_string;
		        		}
		        	}
	    		}

	        	if($fs_company_director->num_rows() > 0)
	        	{
	        		$fs_company_director = $fs_company_director->result_array();

	        		foreach($fs_company_director as $company_director)
		        	{
		        		$company_director_display = '<tr style="font-size:11pt;"><td></td></tr><tr style="font-size:11pt;"><td colspan="5"><strong>'. $company_director['company_type'] .'</strong></td></tr>';

		        		// $direct_begin = !($company_director['direct_begin'] == 0)? number_format($company_director['direct_begin']): '-';
		        		// $direct_end   = !($company_director['direct_end'] == 0)? number_format($company_director['direct_end']): '-';
		        		// $deem_begin   = !($company_director['deem_begin'] == 0)? number_format($company_director['deem_begin']): '-';
		        		// $deem_end 	  = !($company_director['deem_end'] == 0)? number_format($company_director['deem_end']): '-';

		        		// $company_director_display.= '<tr style="font-size:11pt;">
		        		// 								<td style="width: 33.367%;"><u>'. $company_director['company_name'] .'</u></td>
												// 		<td style="width: 16.7213%; text-align: center;">'. $direct_begin .'</td>
												// 		<td style="width: 16.4691%; text-align: center;">'. $direct_end .'</td>
												// 		<td style="width: 16.5952%; text-align: center;">'. $deem_begin .'</td>
												// 		<td style="width: 16.8474%; text-align: center;">'. $deem_end .'</td>
												// 	  </tr>';
		        		$company_director_display.= '<tr style="font-size:11pt;">
		        										<td style="width: 33.367%;"><u>'. $company_director['company_name'] .'</u></td>
														<td style="width: 16.7213%; text-align: center;"></td>
														<td style="width: 16.4691%; text-align: center;"></td>
														<td style="width: 16.5952%; text-align: center;"></td>
														<td style="width: 16.8474%; text-align: center;"></td>
													  </tr>';

		        		$directors = explode(",", $company_director["directors_name"]);
		        		// echo json_encode($company_director["directors_name"]);

		        		foreach($directors as $director)
		        		{
		        			// echo json_encode($director);
		        			$company_director_display .= '<tr style="font-size:11pt;">
		        											<td style="width: 33.367%;">'. $director .'</td>
		        											<td style="width: 16.7213%; text-align: center;"></td>
															<td style="width: 16.4691%; text-align: center;"></td>
															<td style="width: 16.5952%; text-align: center;"></td>
															<td style="width: 16.8474%; text-align: center;"></td>
														  </tr>';
		        		}

		        		$latest_table = $latest_table.$company_director_display;
		        	}
	        	}

	        	$template = str_replace($abstract_string_array[0][0], $latest_table, $template);
	        }
  		}

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$content = '';

			if($string2 == "first set report paragraph")
			{
				$replace_string = $matches[0][$r];

				$first_set_report_paragraph = $this->fs_model->get_fs_doc_template_master('2', 'first set report paragraph');

				if($fs_company_info[0]['first_set'] == "1")
				{
					// $content = "The directors present their report to the members together with the audited financial_statement of the company for the period from " . $fs_company_info[0]['this_fye_end'] . " (date of incorporation) to " . $fs_company_info[0]['current_fye_end'] . ".<br/>";
					$content = $first_set_report_paragraph[0]->content;
				}
				else
				{
					$content = $first_set_report_paragraph[1]->content;
				}

				$template = str_replace($replace_string, $content, $template);

				// echo json_encode($template);
			}
			elseif($string2 == "directors name and date of appointment")
			{
				$replace_string = $matches[0][$r];

				foreach($fs_directors as $key=>$director)
				{
					// echo json_encode($director);
					if($key != count($fs_directors) - 1)
					{
						$br = '<br/>';
					}
					else
					{
						$br = '';
					}

					if($director['show_appt_date'])
					{
						$content = $content . $director['name'] . ' ' . '(appointed on ' . date('d.m.Y', strtotime($director['date_of_appointment'])) . ')' . $br;
					}
					else
					{
						$content = $content . $director['name'] . ' ' . $br;
					}
				}

				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "director's shareholders")
			{
				$replace_string = $matches[0][$r];

				$temp_doc_template = $this->db->query("SELECT fs_doc_template_master.*, fs_opinion_type.name FROM fs_doc_template_master 
                            LEFT JOIN fs_opinion_type ON fs_opinion_type.id = fs_doc_template_master.fs_opinion_type_id
                            WHERE fs_document_master_id = 2 AND section = 'Directors interest in shares or debentures' ORDER BY order_by");
				$temp_doc_template = $temp_doc_template->result_array();

				// print_r($temp_doc_template);

				// echo $fs_company_info[0]['has_director_interest'];

				if(!(int)$fs_company_info[0]['has_director_interest'])
				{
					// echo "false";
					$content = $temp_doc_template[0]['content'];
				}
				else
				{
					$content = $temp_doc_template[1]['content'];
				}

				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "holding company that shares common directors")
			{
				$replace_string = $matches[0][$r];

				$template = str_replace($replace_string, $content, $template);
			}
		}

		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $template, "Statement by Directors", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Statement by Directors", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function independent_auditors_report($obj_pdf, $fs_company_info_id, $firm_id, $pre_printed)
	{
		if($pre_printed)
		{
			$header_company_name = "";
		}
		else
		{
			$document_info = array('type' => "Company Info Header");

			$header_company_name = $this->get_header_template($document_info, $firm_id);
		}
		
		// $obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		// $obj_pdf->SetMargins(PDF_MARGIN_LEFT + 6, PDF_MARGIN_TOP-5, PDF_MARGIN_RIGHT+4);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_name, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 6, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT + 4);
		// $obj_pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT+3);

		$obj_pdf->AddPage();

		// $q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=3");
		$q = $this->db->query("SELECT * FROM fs_document_master WHERE id=3");
		$q = $q->result_array();

		$data = $this->fs_model->get_this_independent_aud_report($fs_company_info_id);
		$fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

		$pattern = "/{{[^}}]*}}/";
		$template = $q[0]["content"];
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			// echo json_encode($data[0]);

			if($string2 == "opinion")
			{
				$content = $data[0]['opinion_fixed'] . '<br/><br/>' . $data[0]['opinion_fixed_2'];

				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "Opinion Type")
			{
				if($data[0]['fs_opinion_type_id'] == 1)
				{
					$content = "";
				}
				elseif($data[0]['fs_opinion_type_id'] == 4)
				{
					$content = $data[0]['opinion_name'] . ' of ';
				}
				else
				{
					$content = $data[0]['opinion_name'] . ' ';
				}
				
				$template = str_replace($replace_string, $content . "Opinion", $template);
			}
			elseif($string2 == "Basic for modified opinion")
			{
				if($data[0]['fs_opinion_type_id'] == 1)
				{
					$content = $data[0]['basic_for_opinion_fixed'];
				}
				elseif($data[0]['fs_opinion_type_id'] == 4)
				{
					$content = $data[0]['basic_for_opinion'] . ' ' . $data[0]['basic_for_opinion_fixed'];
				}
				else
				{
					$content = $data[0]['basic_for_opinion'] . '<br/><br/>' . $data[0]['basic_for_opinion_fixed'];
				}

				$template = str_replace($replace_string, $content, $template);
			}
			// elseif($string2 == "Emphasis of matter")
			// {
			// 	if(!empty($data[0]['emphasis_of_matter']))
			// 	{
			// 		$content = $data[0]['emphasis_of_matter'] . "<br/><br/>";
			// 	}
			// 	else
			// 	{
			// 		$content = "";
			// 	}
				
			// 	$template = str_replace($replace_string, $content, $template);
			// }
			// elseif($string2 == "Other matters")
			// {
			// 	if(!empty($data[0]['other_matters']))
			// 	{
			// 		$content = $data[0]['other_matters'] . "<br/><br/>";
			// 	}
			// 	else
			// 	{
			// 		$content = "";
			// 	}

			// 	$template = str_replace($replace_string, $content, $template);
			// }
			elseif($string2 == "Key audit matter")
			{
				if($data[0]['fs_opinion_type_id'] != 4)
				{
					if($data[0]['fs_opinion_type_id'] == 3)
					{
						$content = '<p><strong>Key Audit Matters</strong><br/>' . $data[0]['key_audit_matter'] . '</p>';
					}
					else
					{
						if(!empty($data[0]['key_audit_matter_input']))
						{
							$content = '<p><strong>Key Audit Matters</strong><br/>' . $data[0]['key_audit_matter'] . '<br/><br/>' . $data[0]['key_audit_matter_input'] . '</p>';
						}
						else
						{
							$content = '<p><strong>Key Audit Matters</strong><br/>' . $data[0]['key_audit_matter'] . '</p>';
						}
					}
				}
				else
				{
					$content = '';
				}
				
				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "Other information")
			{
				if($data[0]['fs_opinion_type_id'] == 1)
				{
					$temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Other information');

					$content = "<br/><br/>" . $temp_from_db[0]->content;

					if(!empty($data[0]['emphasis_of_matter']))
					{
						$content .= "<br/><br/>" . $data[0]['emphasis_of_matter'];
					}

					if(!empty($data[0]['other_matters']))
					{
						$content .= "<br/><br/>" . $data[0]['other_matters'];
					}
				}
				else
				{
					$content = '';
				}
				
				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "Responsibilities of Management and Directors for the Financial Statement - Fixed")
			{
				$temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Responsibilities of Management and Directors for the Financial Statement - Fixed');

				$template = str_replace($replace_string, $temp_from_db[0]->content, $template);
			}
			elseif($string2 == "Report on Other Legal and Regulatory Requirements")
			{
				$temp_from_db = $this->fs_model->get_fs_doc_template_master(3, 'Report on Other Legal and Regulatory Requirements');

				if($data[0]['fs_opinion_type_id'] == 4)
				{
					if($fs_company_info[0]['group_type'] == 1)
					{
						$content = $temp_from_db[2]->content;
					}
					else
					{
						$content = $temp_from_db[3]->content;
					}
				}
				else
				{
					if($fs_company_info[0]['group_type'] == 1)
					{
						$content = $temp_from_db[0]->content;
					}
					else
					{
						$content = $temp_from_db[1]->content;
					}
				}

				// if(!empty($data[0]['disclaimer_of_opinion']))
				// {
				// 	$content .= '<br/><br/>' . $data[0]['disclaimer_of_opinion'];
				// }
				
				$template = str_replace($replace_string, $content, $template);
			}
			// elseif($string2 == "disclaimer of opinion")
			// {
			// 	$content = $data[0]['disclaimer_of_opinion'];
			// 	$template = str_replace($replace_string, $content, $template);
			// }
		}

		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $template, "Independent Auditors' Report", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Independent Auditors' Report", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function statement_comprehensive_income($obj_pdf, $fs_company_info_id, $firm_id)
	{
		$document_info = array(
							'type'  => "statements",
							'title' => "Statement of comprehensive income"
						);

		$header_template = $this->get_header_template($document_info, $firm_id);

		$pattern = "/{{[^}}]*}}/";
		preg_match_all($pattern, $header_template, $matches_header);

		$header_template = $this->fs_replace_content_model->replace_toggle($matches_header[0], $header_template, "Statement of comprehensive income", $fs_company_info_id);
		$header_template = $this->fs_replace_content_model->replace_verbs_plural($header_template, "Statement of comprehensive income", $fs_company_info_id);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_template, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);

		$obj_pdf->AddPage();

		$fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);

		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=4");
		$q = $q->result_array();

		$str = $q[0]["content"];

		// if report does not have group, remove group columns
		if($fs_company_info[0]["group_type"] == '1')
        {
            $str = $this->remove_group_part_from_template($str, false);
        }

        $total_mc_company_ye 	= 0.00;
		$total_mc_company_lye 	= 0.00;
		$total_mc_group_ye 		= 0.00;
		$total_mc_group_lye 	= 0.00;

		// GET INCOME LIST (REVENUE, OTHER INCOME)
		$main_account_template = $this->fs_replace_content_model->get_part_of_template('<tr class="main account"', 'tr', $str);

		$income_list = $this->fs_account_category_model->get_account_with_sub($fs_company_info_id, array('M1003', 'M1005'));

		if(!empty($main_account_template))
		{
			$temp_store_template = '';

			foreach($income_list[0] as $key => $value)
			{
				$temp_template = $main_account_template[0][0];

				if(!empty($value['parent'][0]['company_end_prev_ye_value']))
				{
					$c_lye_value = $value['parent'][0]['company_end_prev_ye_value'];
				}
				else
				{
					$c_lye_value = $value['total']['total_c_lye'];
				}

				$total_mc_company_ye  += $value['total']['total_c'];
				$total_mc_company_lye += $c_lye_value;

				$total_mc_group_ye 	+= $value['parent_array'][0]['group_end_this_ye_value'];
				$total_mc_group_lye += $value['parent_array'][0]['group_end_prev_ye_value'];

				// For account name tr
	    		if(strpos($temp_template, '{{Main account description}}') !== false)
	        	{
	        		$temp_template = str_replace('{{Main account description}}', ucfirst(strtolower($value['parent_array'][0]['description'])), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{Note no}}') !== false)
	        	{
	        		$temp_template = str_replace('{{Note no}}', $value['parent_array'][0]['fs_note_details']['fs_note_no'], $temp_template);
	        	}
	        	
	        	if(strpos($temp_template, '{{value 1 - group}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 1 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_this_ye_value']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 2 - group}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 2 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_prev_ye_value']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 1 - company}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 1 - company}}', $this->fs_replace_content_model->negative_bracket($value['total']['total_c']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 2 - company}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 2 - company}}', $this->fs_replace_content_model->negative_bracket($c_lye_value), $temp_template);
	        	}

	        	$temp_store_template .= $temp_template;
			}
		}

		$new_contents = preg_replace('(' . $main_account_template[0][0] . ')', $temp_store_template, $str, 1);
		// END OF GET INCOME LIST (REVENUE, OTHER INCOME)

		// GET SUBTOTAL FOR INCOME LIST
		$subtotal_main_account_template = $this->fs_replace_content_model->get_part_of_template('<tr class="main account subtotal"', 'tr', $new_contents);

		if(!empty($subtotal_main_account_template))
		{
			$temp_store_template = '';
			$temp_template = $subtotal_main_account_template[0][0];

        	// REPLACE SUBTOTAL
        	if(strpos($temp_template, '{{subtotal 1 - group}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 1 - group}}', $this->fs_replace_content_model->negative_bracket($total_mc_company_ye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 2 - group}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 2 - group}}', $this->fs_replace_content_model->negative_bracket($total_mc_company_lye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 1 - company}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 1 - company}}', $this->fs_replace_content_model->negative_bracket($total_mc_group_ye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 2 - company}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 2 - company}}', $this->fs_replace_content_model->negative_bracket($total_mc_group_lye), $temp_template);
        	}

        	$temp_store_template .= $temp_template;
		}

		$new_contents = preg_replace('(' . $subtotal_main_account_template[0][0] . ')', $temp_store_template, $new_contents, 1);
		// END OF GET SUBTOTAL FOR INCOME LIST

		$changes_in_inventories_g_ye  = 0.00;
		$changes_in_inventories_g_lye = 0.00;
		$changes_in_inventories_c_ye  = 0.00;
		$changes_in_inventories_c_lye = 0.00;

		// GET GET EXPENSE LIST
		$expense_template = $this->fs_replace_content_model->get_part_of_template('<tr class="expense"', 'tr', $str);

		$expense_sub_list = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, 'M1006');
		$expense_list = $this->fs_account_category_model->get_account_with_sub($fs_company_info_id, $expense_sub_list);

		$total_expenses_group_ye 	= 0.00;
		$total_expenses_group_lye 	= 0.00;
		$total_expenses_company_ye 	= 0.00;
		$total_expenses_company_lye = 0.00;

		if(!empty($expense_template))
		{
			$temp_store_template = '';

			foreach($expense_list[0] as $key => $value)
			{
				$temp_template = $expense_template[0][0];

				$total_expenses_group_ye 	+= $value['parent_array'][0]['group_end_this_ye_value'];
				$total_expenses_group_lye 	+= $value['parent_array'][0]['group_end_prev_ye_value'];
				$total_expenses_company_ye 	+= $value['total']['total_c'];
				$total_expenses_company_lye += $value['total']['total_c_lye'];

				// For account name tr
	    		if(strpos($temp_template, '{{Expense account description}}') !== false)
	        	{
	        		$temp_template = str_replace('{{Expense account description}}', ucfirst(strtolower($value['parent_array'][0]['description'])), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{Note no}}') !== false)
	        	{
	        		$temp_template = str_replace('{{Note no}}', $value['parent_array'][0]['fs_note_details']['fs_note_no'], $temp_template);
	        	}
	        	
	        	if(strpos($temp_template, '{{value 1 - group}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 1 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_this_ye_value']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 2 - group}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 2 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_prev_ye_value']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 1 - company}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 1 - company}}', $this->fs_replace_content_model->negative_bracket($value['total']['total_c']), $temp_template);
	        	}

	        	if(strpos($temp_template, '{{value 2 - company}}') !== false)
	        	{
	        		$temp_template = str_replace('{{value 2 - company}}', $this->fs_replace_content_model->negative_bracket($c_lye_value), $temp_template);
	        	}

	        	$temp_store_template .= $temp_template;
			}
		}

		$new_contents = preg_replace('(' . $expense_template[0][0] . ')', $temp_store_template, $new_contents, 1);
		// END OF GET EXPENSE LIST

		// GET SUBTOTAL FOR EXPENSE LIST
		$subtotal_expense_template = $this->fs_replace_content_model->get_part_of_template('<tr class="expense subtotal"', 'tr', $new_contents);

		if(!empty($subtotal_expense_template))
		{
			$temp_store_template = '';
			$temp_template = $subtotal_expense_template[0][0];

        	// REPLACE SUBTOTAL
        	if(strpos($temp_template, '{{subtotal 1 - group}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 1 - group}}', $this->fs_replace_content_model->negative_bracket($total_expenses_group_ye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 2 - group}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 2 - group}}', $this->fs_replace_content_model->negative_bracket($total_expenses_group_lye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 1 - company}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 1 - company}}', $this->fs_replace_content_model->negative_bracket($total_expenses_company_ye), $temp_template);
        	}

        	if(strpos($temp_template, '{{subtotal 2 - company}}') !== false)
        	{
        		$temp_template = str_replace('{{subtotal 2 - company}}', $this->fs_replace_content_model->negative_bracket($total_expenses_company_lye), $temp_template);
        	}

        	$temp_store_template .= $temp_template;
		}

		$new_contents = preg_replace('(' . $subtotal_expense_template[0][0] . ')', $temp_store_template, $new_contents, 1);
		// END OF GET SUBTOTAL FOR EXPENSE LIST

		// GET OTHER COMPREHENSIVE LIST
		$other_comprehensive_template = $this->fs_replace_content_model->get_part_of_template('<tr class="other comprehensive"', 'tr', $str);

		// $other_comprehensive_sub_list = $this->fs_account_category_model->get_sub_categories($fs_company_info_id, 'M1006');
		$other_comprehensive_list = $this->fs_account_category_model->get_account_with_sub($fs_company_info_id, array('M1008')); // OTHER COMPREHENSIVE INCOME

		$total_other_g_ye  = 0.00;
		$total_other_g_lye = 0.00;
		$total_other_c_ye  = 0.00;
		$total_other_c_lye = 0.00;

		if(!empty($other_comprehensive_template))
		{
			$temp_store_template = '';

			foreach($other_comprehensive_list[0] as $key => $value)
			{
				$temp_template = $other_comprehensive_template[0][0];

				if(count($value['child_array']) == 0)
				{
					// For account name tr
		    		if(strpos($temp_template, '{{Other comprehensive account description}}') !== false)
		        	{
		        		$temp_template = str_replace('{{Other comprehensive account description}}', ucfirst(strtolower($value['parent_array'][0]['description'])), $temp_template);
		        	}

		        	if(strpos($temp_template, '{{Note no}}') !== false)
		        	{
		        		$temp_template = str_replace('{{Note no}}', $value['parent_array'][0]['fs_note_details']['fs_note_no'], $temp_template);
		        	}
		        	
		        	if(strpos($temp_template, '{{value 1 - group}}') !== false)
		        	{
		        		$temp_template = str_replace('{{value 1 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_this_ye_value']), $temp_template);
		        	}

		        	if(strpos($temp_template, '{{value 2 - group}}') !== false)
		        	{
		        		$temp_template = str_replace('{{value 2 - group}}', $this->fs_replace_content_model->negative_bracket($value['parent_array'][0]['group_end_prev_ye_value']), $temp_template);
		        	}

		        	if(strpos($temp_template, '{{value 1 - company}}') !== false)
		        	{
		        		$temp_template = str_replace('{{value 1 - company}}', $this->fs_replace_content_model->negative_bracket($value['total']['total_c']), $temp_template);
		        	}

		        	if(strpos($temp_template, '{{value 2 - company}}') !== false)
		        	{
		        		$temp_template = str_replace('{{value 2 - company}}', $this->fs_replace_content_model->negative_bracket($c_lye_value), $temp_template);
		        	}

		        	$temp_store_template .= $temp_template;

		        	$total_other_g_ye  += $value['parent_array'][0]['group_end_this_ye_value'];
					$total_other_g_lye += $value['parent_array'][0]['group_end_prev_ye_value'];
					$total_other_c_ye  += $value['total']['total_c'];
					$total_other_c_lye += $value['total']['total_c_lye'];
				}
				else
				{
					$temp_store_template .= 
						'<tr>' . 
							'<td>'. ucfirst(strtolower($value['parent_array'][0]['description'])) . '</td>' .
							'<td colspan="6"></td>' .
						'</tr>';

		        	foreach($value['child_array'] as $key => $sub_other_comprehensive)
					{
						// echo json_encode($sub_other_comprehensive);
						// echo '</br>';

						if(!is_null($sub_other_comprehensive['parent_array']))	// child that is a parent has child under it (got sub)
						{
							$description = $sub_other_comprehensive['parent_array']['description'];
							$note_no 	 = $sub_other_comprehensive['parent_array']['fs_note_details']['note_no'];
							$value_1_g = $sub_other_comprehensive['parent_array']['group_end_this_ye_value'];
							$value_2_g = $sub_other_comprehensive['parent_array']['group_end_prev_ye_value'];
							$value_1_c = $sub_other_comprehensive['parent_array']['total_c'];
							$value_2_c = $sub_other_comprehensive['parent_array']['total_c_lye'];
						}
						else
						{
							$description = $sub_other_comprehensive[0]['description'];
							$note_no 	 = $sub_other_comprehensive[0]['fs_note_details']['note_no'];
							$value_1_g = $sub_other_comprehensive[0]['group_end_this_ye_value'];
							$value_2_g = $sub_other_comprehensive[0]['group_end_prev_ye_value'];
							$value_1_c = $sub_other_comprehensive[0]['total_c'];
							$value_2_c = $sub_other_comprehensive[0]['total_c_lye'];
						}

						if(strpos($temp_template, '{{Other comprehensive account description}}') !== false)
			        	{
			        		$temp_template = str_replace('{{Other comprehensive account description}}', ucfirst(strtolower($description)), $temp_template);
			        	}

			        	if(strpos($temp_template, '{{Note no}}') !== false)
			        	{
			        		$temp_template = str_replace('{{Note no}}', $note_no, $temp_template);
			        	}
			        	
			        	if(strpos($temp_template, '{{value 1 - group}}') !== false)
			        	{
			        		$temp_template = str_replace('{{value 1 - group}}', $this->fs_replace_content_model->negative_bracket($value_1_g), $temp_template);
			        	}

			        	if(strpos($temp_template, '{{value 2 - group}}') !== false)
			        	{
			        		$temp_template = str_replace('{{value 2 - group}}', $this->fs_replace_content_model->negative_bracket($value_2_g), $temp_template);
			        	}

			        	if(strpos($temp_template, '{{value 1 - company}}') !== false)
			        	{
			        		$temp_template = str_replace('{{value 1 - company}}', $this->fs_replace_content_model->negative_bracket($value_1_c), $temp_template);
			        	}

			        	if(strpos($temp_template, '{{value 2 - company}}') !== false)
			        	{
			        		$temp_template = str_replace('{{value 2 - company}}', $this->fs_replace_content_model->negative_bracket($value_2_c), $temp_template);
			        	}

			        	$temp_store_template .= $temp_template;

			        	$total_other_g_ye  += $value_1_g;
						$total_other_g_lye += $value_2_g;
						$total_other_c_ye  += $value_1_c;
						$total_other_c_lye += $value_2_c;
					}
				}
			}
		}

		$new_contents = preg_replace('(' . $other_comprehensive_template[0][0] . ')', $temp_store_template, $new_contents, 1);
		// END OF GET OTHER COMPREHENSIVE LIST

		$pl_after_tax_g_ye	= 0.00;
		$pl_after_tax_g_lye	= 0.00;
		$pl_after_tax_c_ye	= 0.00;
		$pl_after_tax_c_lye	= 0.00;

		$pattern = "/{{[^}}]*}}/";
		$template = $new_contents;
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			// temporary like this first. (Will change in the future)
			if($string2 == "total 1 - group")
			{
				$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($pl_after_tax_g_ye + $total_other_g_ye), $template);
			}

			if($string2 == "total 2 - group")
			{
				$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($pl_after_tax_g_lye + $total_other_g_lye), $template);
			}

			if($string2 == "total 1 - company")
			{
				$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($pl_after_tax_c_ye + $total_other_c_ye), $template);
			}

			if($string2 == "total 2 - company")
			{
				$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($pl_after_tax_c_lye + $total_other_c_lye), $template);
			}

			// elseif($string2 == "width_td")
			// {
			// 	$template = str_replace($replace_string, '80', $template);
			// }
			// elseif($string2 == "Total operating expenses")
			// {
			// 	$total_operating_expenses = $this->fs_statements_model->get_total_operating_expenses($fs_company_info_id);

			// 	$content = $this->fs_replace_content_model->negative_bracket($total_operating_expenses[0]["total_operating_expenses"]);

			// 	$template = str_replace($replace_string, $content, $template);
			// }
		}

		// echo json_encode($template);

		// $template = str_replace("{{Display None}}", "display:none;", $template);

		$new_content = $this->fs_replace_content_model->replace_special_sign($matches[0], $template);
		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $new_content, "Statement of Comprehensive Income", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Statement of Comprehensive Income", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function statement_of_financial_position($obj_pdf, $fs_company_info_id, $firm_id)
	{
		$document_info = array(
							'type'  => "statements",
							'title' => "Statement of financial position"
						);

		$header_template = $this->get_header_template($document_info, $firm_id);
	}

	public function NTFS_report($obj_pdf, $fs_company_info_id, $firm_id)
	{
		$document_info = array(
							'type'  => "note",
							'title' => "Note to the financial statements"
						);

		$header_template = $this->get_header_template($document_info, $data[0]["firm_id"]);

		// $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
		// $fs_directors 	 = $this->fs_model->get_fs_appt_directors($fs_company_info_id);

		$pattern = "/{{[^}}]*}}/";
		preg_match_all($pattern, $header_template, $matches_header);

		$header_template = $this->fs_replace_content_model->replace_toggle($matches_header[0], $header_template, "Note to the financial statements", $fs_company_info_id);
		$header_template = $this->fs_replace_content_model->replace_verbs_plural($header_template, "Note to the financial statements", $fs_company_info_id);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_template, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP + 13, PDF_MARGIN_RIGHT);
		$obj_pdf->setListIndentWidth(4);

		$obj_pdf->AddPage();

		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=5");
		$q = $q->result_array();

		$pattern = "/{{[^}}]*}}/";
		$template = $q[0]["content"];
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			if($string2 == "ntfs content")
			{
				// $q2 = $this->db->query("SELECT * FROM fs_ntfs_section_template ORDER BY order_by");
				$q2 = $this->db->query('SELECT lyt.id, lytd.section_name, lytd.document_layout
										FROM fs_ntfs_layout_template lyt
										LEFT JOIN fs_ntfs_layout_template_default lytd ON lyt.fs_ntfs_layout_template_default_id = lytd.id
										WHERE lyt.fs_company_info_id=' . $fs_company_info_id . ' AND lyt.is_checked = 1 ORDER BY lyt.order_by');
				// $q2 = $this->db->query('SELECT lytd.section_name, lytd.document_layout
				// 						FROM fs_ntfs_layout_template lyt
				// 						LEFT JOIN fs_ntfs_layout_template_default lytd ON lyt.fs_ntfs_layout_template_default_id = lytd.id
				// 						WHERE lyt.fs_company_info_id=' . $fs_company_info_id . ' AND lyt.is_checked = 1 ORDER BY lyt.order_by');
				$q2 = $q2->result_array();

				$temp_template = '';

				foreach ($q2 as $key => $value) // sections
				{	
					// if($value['id'] == 14)
					// {
					// 	echo json_encode($value['document_layout']);
					// }

					$temp_template .= $this->fs_replace_content_model->ntfs_replace_toggle($value['document_layout'], $value['section_name'], $fs_company_info_id);

					// $pattern = "/{{[^}}]*}}/";
					// $content_template = $value['document_layout'];
					// preg_match_all($pattern, $content_template, $matches_content);

					// // SECTION: Domicile and activities
					// if($value['section_name'] == "DOMICILE AND ACTIVITIES")
					// {
					// 	for($r = 0; $r < count($matches_content[0]); $r++)
					// 	{
					// 		$string1 = (str_replace('{{', '',$matches_content[0][$r]));
					// 		$string2 = (str_replace('}}', '',$string1));

					// 		$replace_string_c = $matches_content[0][$r];

					// 		// ULTIMATE COMPANY PART
					// 		$ultimate_info = $this->db->query("SELECT fs_dir_statement_company.*, country.name, country.nicename
					// 											FROM fs_dir_statement_company 
					// 											LEFT JOIN country ON country.id = fs_dir_statement_company.country_id
					// 											WHERE fs_dir_statement_company.fs_company_info_id=" . $fs_company_info_id . " AND fs_dir_statement_company.fs_company_type_id=1");
					// 		$ultimate_info = $ultimate_info->result_array();

					// 		if(count($ultimate_info) > 0)
					// 		{
					// 			if($string2 == "ultimate company - name")
					// 			{
					// 				$content_template = str_replace($replace_string_c, $ultimate_info[0]['company_name'], $content_template);
					// 			}

					// 			if($string2 == "ultimate company - country")
					// 			{
					// 				$content_template = str_replace($replace_string_c, $ultimate_info[0]['nicename'], $content_template);
					// 			}
					// 		}
					// 		else // remove ultimate company part
					// 		{	
					// 			$ultimate_template = $this->fs_replace_content_model->get_part_of_template('<p class="ultimate_company"', 'p', $content_template);

					// 			$content_template = preg_replace('(' . $ultimate_template[0][0] . ')', "", $content_template, 1);
					// 		}
					// 		// END OF ULTIMATE COMPANY PART

					// 		// CHANGE COMPANY NAME PART
					// 		if(empty($fs_company_info[0]['old_company_name']))	// remove change company name part
					// 		{	
					// 			$change_name_template = $this->fs_replace_content_model->get_part_of_template('<p class="change_company_name"', 'p', $content_template);

					// 			$content_template = preg_replace('(' . $change_name_template[0][0] . ')', "", $content_template, 1);
					// 		}
					// 		// END OF CHANGE COMPANY NAME PART
					// 	}
						
					// 	$temp_template .= $content_template;
					// }

					// // SECTION: Basis of preparation
					// elseif($value['section_name'] == "Basis of preparation")
					// {	
					// 	if(!$fs_company_info[0]['company_liquidated'])
					// 	{
					// 		$company_liquidated_template = $this->fs_replace_content_model->get_part_of_template('<p class="company_liquidated"', 'p', $content_template);

					// 		$content_template = preg_replace('(' . $company_liquidated_template[0][0] . ')', "", $content_template, 1);
					// 	}
					// 	$temp_template .= $content_template;
					// }

					// // SECTION: Functional and presentation currency
					// elseif($value['section_name'] == "Functional and presentation currency")
					// {	
					// 	// GET FP CURRENCY INFO
					// 	$fp_currency_info = $this->fs_model->get_fs_fp_currency_details($fs_company_info_id);

					// 	// DISPLAY PART IF FC AND PC ARE SAME
					// 	if($fp_currency_info[0]['last_year_fc_currency_id'] == $fp_currency_info[0]['last_year_pc_currency_id'])
					// 	{
					// 		$fc_pc_same_template = $this->fs_replace_content_model->get_part_of_template('<div class="fc_pc_same"', 'div', $content_template);

					// 		// remove div tag
					// 		$removed_div_fc_pc_same_template = preg_replace('(<div class="fc_pc_same">)', "", $fc_pc_same_template[0][0], 1);
					// 		$removed_div_fc_pc_same_template = preg_replace('(</div>)', "", $removed_div_fc_pc_same_template, 1);

					// 		$content_template = preg_replace('(' . $fc_pc_same_template[0][0] . ')', $removed_div_fc_pc_same_template, $content_template, 1);

					// 		// remove fc_pc_different
					// 		$fc_pc_different_template = $this->fs_replace_content_model->get_part_of_template('<div class="fc_pc_different"', 'div', $content_template);
					// 		$content_template = preg_replace('(' . $fc_pc_different_template[0][0] . ')', "", $content_template, 1);
					// 	}
					// 	else // DISPLAY PART IF FC AND PC ARE DIFFERENT
					// 	{
					// 		$fc_pc_different_template = $this->fs_replace_content_model->get_part_of_template('<div class="fc_pc_different"', 'div', $content_template);

					// 		// remove div tag
					// 		$removed_div_fc_pc_different_template = preg_replace('(<div class="fc_pc_different">)', "", $fc_pc_different_template[0][0], 1);
					// 		$removed_div_fc_pc_different_template = preg_replace('(</div>)', "", $removed_div_fc_pc_different_template, 1);

					// 		$content_template = preg_replace('(' . $fc_pc_different_template[0][0] . ')', $removed_div_fc_pc_different_template, $content_template, 1);

					// 		// remove fc_pc_same
					// 		$fc_pc_same_template = $this->fs_replace_content_model->get_part_of_template('<div class="fc_pc_same"', 'div', $content_template);
					// 		$content_template 	 = preg_replace('(' . $fc_pc_same_template[0][0] . ')', "", $content_template, 1);
					// 	}

					// 	// if got subsidiary/ies
					// 	if($fs_company_info[0]['group_type'] == 1) 
					// 	{
					// 		$company_has_subsidiary_template = $this->fs_replace_content_model->get_part_of_template('<p class="company_has_subsidiary"', 'p', $content_template);

					// 		$content_template = preg_replace('(' . $company_has_subsidiary_template[0][0] . ')', "", $content_template, 1);
					// 	}

					// 	// REMOVE PART IF OLD FC AND CURRENT FC ARE SAME
					// 	if($fp_currency_info[0]['last_year_fc_currency_id'] == $fp_currency_info[0]['current_year_fc_currency_id'])
					// 	{
					// 		$company_change_fc_template = $this->fs_replace_content_model->get_part_of_template('<p class="company_change_fc"', 'p', $content_template);
					// 		$content_template = preg_replace('(' . $company_change_fc_template[0][0] . ')', "", $content_template, 1);
					// 	}

					// 	$pattern = "/{{[^}}]*}}/";
					// 	preg_match_all($pattern, $content_template, $matches_sub_part);

					// 	for($x = 0; $x < count($matches_sub_part[0]); $x++)
					// 	{
					// 		$string1 = (str_replace('{{', '',$matches_sub_part[0][$x]));
					// 		$string2 = (str_replace('}}', '',$string1));

					// 		$replace_string_sub_part = $matches_sub_part[0][$x];

					// 		if($string2 == "Functional Presentation Currency")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_pc_currency_id'])[0]['name'], $content_template);
					// 		}

					// 		if($string2 == "Functional Currency - Last Year")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_fc_currency_id'])[0]['name'], $content_template);
					// 		}

					// 		if($string2 == "Functional Currency - Current Year")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['name'], $content_template);
					// 		}

					// 		if($string2 == "Presentation Curreny - Last Year")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['last_year_pc_currency_id'])[0]['name'], $content_template);
					// 		}

					// 		if($string2 == "Functional Currency - Reason of changing")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $fp_currency_info[0]['reason_of_changing_fc'], $content_template);
					// 		}

					// 		if($string2 == "Presentation Curreny Country - Last Year")
					// 		{
					// 			$content_template = str_replace($replace_string_sub_part, $this->fs_model->get_currency_info($fp_currency_info[0]['current_year_fc_currency_id'])[0]['country_name'], $content_template);
					// 		}
					// 	}

					// 	$temp_template .= $content_template;
					// }
					// // SECTION: Foreign currency transactions and balances
					// elseif($value['section_name'] == "Foreign currency transactions and balances")
					// {
					// 	$temp_template .= $content_template;
					// }

					// elseif($value['section_name'] == "Investment in associate and joint ventures")
					// {
					// 	$show_content = $this->fs_model->show_investment_in_associate_and_join_ventures($fs_company_info_id);

					// 	if($show_content)
					// 	{
					// 		$temp_template .= $content_template;
					// 	}
					// }
					// else
					// {
					// 	$temp_template .= $content_template;
					// }
								
				}

				$template = str_replace($replace_string, $temp_template, $template);
			}
		}


		$pattern = "/{{[^}}]*}}/";
		// $template = $template;
		preg_match_all($pattern, $template, $matches_part);

		$new_content = $this->fs_replace_content_model->replace_special_sign($matches_part[0], $template);
		$new_content = $this->fs_replace_content_model->replace_toggle($matches_part[0], $new_content, "Note to the financial statements", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Note to the financial statements", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->write_list_number($new_content);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function schedule_of_operating_expenses($obj_pdf, $fs_company_info_id)
	{
		// $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
		// $data 			 = $this->fs_model->get_fs_company_info($fs_company_info_id);
		$document_info = array(
							'type'  => "statements",
							'title' => "Schedule of operating expenses"
						);

		$header_template = $this->get_header_template($document_info, $data[0]["firm_id"]);

		$pattern = "/{{[^}}]*}}/";
		preg_match_all($pattern, $header_template, $matches_header);

		$header_template = $this->fs_replace_content_model->replace_toggle($matches_header[0], $header_template, "Schedule of operating expenses", $fs_company_info_id);
		$header_template = $this->fs_replace_content_model->replace_verbs_plural($header_template, "Schedule of operating expenses", $fs_company_info_id);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_template, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);

		$obj_pdf->AddPage();

		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=6");
		$q = $q->result_array();

		// $fs_company_info = $this->db->query("SELECT * FROM fs_company_info WHERE id=" . $fs_company_info_id);
		// $fs_company_info = $fs_company_info->result_array();

		$operating_expenses = $this->fs_statements_model->get_account_category_item_list($fs_company_info_id, array('S0001', 'S0002', 'S0003'));

		$str = $q[0]["content"];

		// get category name tr template
		if(strpos($str, '<tr class="category_name"') !== false)
    	{
    		preg_match_all ('/<tr class="category_name"(.+?)<\/tr>/s', $str, $template_category_name);
    	}

    	// get account name tr template
		if(strpos($str, '<tr class="account_name"') !== false)
		{
			preg_match_all ('/<tr class="account_name"(.+?)<\/tr>/s', $str, $template_account_name);
		}

		// get total of account name tr template
		if(strpos($str, '<tr class="total_by_category"') !== false)
		{
			preg_match_all ('/<tr class="total_by_category"(.+?)<\/tr>/s', $str, $template_total_by_category);
		}

		$temp_content_trs = "";

    	foreach($operating_expenses as $key => $value)
    	{
    		$tr_category_name = $template_category_name[0][0];

    		// For Category Name tr
    		if(strpos($tr_category_name, '{{Category Name}}') !== false)
        	{
        		$tr_category_name = str_replace('{{Category Name}}', ucfirst(strtolower($value[0]['category_name'])), $tr_category_name);
        	}

        	$temp_content_trs .= $tr_category_name;

    		foreach($value[0]['data'] as $key => $value_1)
    		{
    			$tr_account_name = $template_account_name[0][0];

    			// For account name tr
	    		if(strpos($tr_account_name, '{{Account Name}}') !== false)
	        	{
	        		$tr_account_name = str_replace('{{Account Name}}', $value_1['description'], $tr_account_name);
	        	}
	        	
	        	if(strpos($tr_account_name, '{{Account Value}}') !== false)
	        	{
	        		$tr_account_name = str_replace('{{Account Value}}', $this->fs_replace_content_model->negative_bracket($value_1['value']), $tr_account_name);
	        	}

	        	$temp_content_trs .= $tr_account_name;
    		}

    		// For Total of account name (by this category)
    		$tr_total_by_category = $template_total_by_category[0][0];

    		if(strpos($tr_total_by_category, '{{Total by category}}') !== false)
        	{
        		$total_by_category = $this->fs_account_category_model->get_fs_total_by_account_category($value[0]['fs_categorized_account_id']);

        		// echo json_encode($total_by_category);

        		$tr_total_by_category = str_replace('{{Total by category}}', $this->fs_replace_content_model->negative_bracket($total_by_category[0]["total"]), $tr_total_by_category);
        	}

        	$temp_content_trs .= $tr_total_by_category;
    	}

    	// echo $temp_content_trs;

    	// $new_contents = str_replace($template_category_name[0][0] . $template_account_name[0][0] . $template_total_by_category[0][0], "", $q[0]["content"]);

    	$new_contents = str_replace($template_category_name[0][0], "", $q[0]["content"]);
    	$new_contents = str_replace($template_account_name[0][0], "", $new_contents);
    	$new_contents = str_replace($template_total_by_category[0][0], $temp_content_trs, $new_contents);

		$pattern = "/{{[^}}]*}}/";
		$template = $new_contents;
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			// temporary like this first. (Will change in the future)
			if($string2 == "Year End")
			{
				$current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        		$last_fye_end    = $current_last_year_end['last_fye_end'];
        		$current_fye_end = $current_last_year_end['current_fye_end'];

				$template = str_replace($replace_string, $current_fye_end, $template);
			}
			elseif($string2 == "width_td")
			{
				$template = str_replace($replace_string, '80', $template);
			}
			elseif($string2 == "Total operating expenses")
			{
				$total_operating_expenses = $this->fs_statements_model->get_total_operating_expenses($fs_company_info_id);

				$content = $this->fs_replace_content_model->negative_bracket($total_operating_expenses[0]["total_operating_expenses"]);

				$template = str_replace($replace_string, $content, $template);
			}
		}

		// echo json_encode($template);

		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $template, "Schedule of operating expenses", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Schedule of operating expenses", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function state_detailed_profit_loss($obj_pdf, $fs_company_info_id)
	{
		// $fs_company_info = $this->fs_model->get_fs_company_info($fs_company_info_id);
		// $data 			 = $this->fs_model->get_fs_company_info($fs_company_info_id);

		$state_detailed_pro_loss_main_total = $this->fs_statements_model->get_state_detailed_pro_loss_info($fs_company_info_id);

		// get header template
		$document_info = array(
							'type'  => "statements",
							'title' => "Statement of detailed profit or loss"
						);

		$header_template = $this->get_header_template($document_info, $data[0]["firm_id"]);

		$pattern = "/{{[^}}]*}}/";
		preg_match_all($pattern, $header_template, $matches_header);

		$header_template = $this->fs_replace_content_model->replace_toggle($matches_header[0], $header_template, "Statement of detailed profit or loss", $fs_company_info_id);
		$header_template = $this->fs_replace_content_model->replace_verbs_plural($header_template, "Statement of detailed profit or loss", $fs_company_info_id);

		$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_template, $tc=array(0,0,0), $lc=array(0,0,0));
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT + 5, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);

		$obj_pdf->AddPage();

		$q = $this->db->query("SELECT * FROM fs_document_master WHERE order_num=7");
		$q = $q->result_array();

		// $fs_company_info = $this->db->query("SELECT * FROM fs_company_info WHERE id=" . $fs_company_info_id);
		// $fs_company_info = $fs_company_info->result_array();

		$state_detailed_pro_loss = $this->fs_statements_model->get_account_category_item_list($fs_company_info_id, array('M1005', 'M1006', 'M1007'));

		$str = $q[0]["content"];

		// get category name tr template
		if(strpos($str, '<tr class="category_name"') !== false)
    	{
    		preg_match_all ('/<tr class="category_name"(.+?)<\/tr>/s', $str, $template_category_name);
    	}

    	// get account name tr template
		if(strpos($str, '<tr class="account_name"') !== false)
		{
			preg_match_all ('/<tr class="account_name"(.+?)<\/tr>/s', $str, $template_account_name);
		}

		// get total of account name tr template
		if(strpos($str, '<tr class="total_by_category"') !== false)
		{
			preg_match_all ('/<tr class="total_by_category"(.+?)<\/tr>/s', $str, $template_total_by_category);
		}

		// get gross profit tr template
		if(strpos($str, '<tr class="gross_profit"') !== false)
		{
			preg_match_all ('/<tr class="gross_profit"(.+?)<\/tr>/s', $str, $template_gross_profit);
		}

		$temp_content_trs = "";
		$temp_content_trs_other_income = "";
		$revenue_exist				= false;
		$cost_of_sales_exist		= false;

    	foreach($state_detailed_pro_loss as $key => $value)
    	{
    		$tr_category_name = $template_category_name[0][0];
    		$add_less_display = "";

    		// To display Less: / Add: for Category Name
    		if($value[0]['account_code'] == "M1005")
    		{
    			$revenue_exist = true;
    		}
    		elseif($value[0]['account_code'] == "M1006")
    		{
    			$add_less_display = "Less: ";
    			$cost_of_sales_exist = true;
    		}
    		elseif($value[0]['account_code'] == "M1007")	// M1007 - other income
			{
				$add_less_display = "Add: ";
			}

    		// Display category 
    		if(count($value[0]['data']) > 0)
    		{
    			// if got sub, use category name
    			// For Category Name tr
	    		if(strpos($tr_category_name, '{{Category Name}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{Category Name}}', $add_less_display . ucfirst(strtolower($value[0]['category_name'])), $tr_category_name);
	        	}

	        	if(strpos($tr_category_name, '{{Category Value}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{Category Value}}', "", $tr_category_name);
	        	}

	        	if(strpos($tr_category_name, '{{underline style}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{underline style}}', "", $tr_category_name);
	        	}

	        	// keep other income template for later use.
				if($value[0]['account_code'] == "M1007")	// other income
				{
					$temp_content_trs_other_income .= $tr_category_name;
				}
				else
				{
					$temp_content_trs .= $tr_category_name;
				}
    		}
    		else
    		{
    			// if no sub
    			// For Category Name tr
	    		if(strpos($tr_category_name, '{{Category Name}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{Category Name}}', $add_less_display . ucfirst(strtolower($value[0]['category_name'])), $tr_category_name);
	        	}

	        	if(strpos($tr_category_name, '{{Category Value}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{Category Value}}', "-", $tr_category_name);
	        	}

	        	if(strpos($tr_category_name, '{{underline style}}') !== false)
	        	{
	        		$tr_category_name = str_replace('{{underline style}}', "border-bottom: 1px solid #000;", $tr_category_name);
	        	}

	        	// keep other income template for later use.
				if($value[0]['account_code'] == "M1007")	// other income
				{
					$temp_content_trs_other_income .= $tr_category_name . '<tr><td>&nbsp;</td></tr>';
				}
				else
				{
					$temp_content_trs .= $tr_category_name . '<tr><td>&nbsp;</td></tr>';
				}
    		}
    		
			// For account under category
    		foreach($value[0]['data'] as $key => $value_1)
    		{
    			$tr_account_name = $template_account_name[0][0];

    			// For account name tr
	    		if(strpos($tr_account_name, '{{Account Name}}') !== false)
	        	{
	        		$tr_account_name = str_replace('{{Account Name}}', $value_1['description'], $tr_account_name);
	        	}
	        	
	        	if(strpos($tr_account_name, '{{Account Value}}') !== false)
	        	{
	        		$tr_account_name = str_replace('{{Account Value}}', $this->fs_replace_content_model->negative_bracket($value_1['value']), $tr_account_name);
	        	}

	        	// keep other income template for later use.
				if($value[0]['account_code'] == "M1007")	// other income
				{
					$temp_content_trs_other_income .= $tr_account_name;

					// echo $temp_content_trs_other_income;
				}
				else
				{
					$temp_content_trs .= $tr_account_name;
				}
    		}
        	
    		if(count($value[0]['data']) > 0)
    		{
	    		// For Total of account name (by this category)
	    		$tr_total_by_category = $template_total_by_category[0][0];

	    		if(strpos($tr_total_by_category, '{{Total by category}}') !== false)
	        	{
	        		$total_by_category = $this->fs_account_category_model->get_fs_total_by_account_category($value[0]['fs_categorized_account_id']);

	        		// echo json_encode($value[0]);

	        		$tr_total_by_category = str_replace('{{Total by category}}', $this->fs_replace_content_model->negative_bracket($total_by_category[0]["total"]), $tr_total_by_category);
	        	}

	        	// keep other income template for later use.
				if($value[0]['account_code'] == "M1007")	// other income
				{
					$temp_content_trs_other_income .= $tr_total_by_category;
				}
				else
				{
					$temp_content_trs .= $tr_total_by_category;
				}
	        }
    	}

    	/* for gross profit */
    	$tr_gross_profit = $template_gross_profit[0][0];
    	$gross_profit_final_template = "";

    	if(strpos($tr_gross_profit, '{{Gross Profit}}') !== false)
    	{
    		$tr_gross_profit = str_replace('{{Gross Profit}}', ucfirst(strtolower($this->fs_replace_content_model->negative_bracket($state_detailed_pro_loss_main_total[0]['gross_profit']))), $tr_gross_profit);

    		if($revenue_exist && $cost_of_sales_exist)
    		{
    			$gross_profit_final_template = $tr_gross_profit;
    		}
    	}
    	/* END OF for gross profit */

    	// replace all first part (revenue & cost of sale)
    	$new_contents = preg_replace('(' . $template_category_name[0][0] . ')', "", $q[0]["content"], 1);
    	$new_contents = preg_replace('(' . $template_account_name[0][0] . ')', "", $new_contents, 1);
    	$new_contents = preg_replace('(' . $template_total_by_category[0][0] . ')', $temp_content_trs, $new_contents, 1);

    	// replace for second part (other income)
    	$new_contents = preg_replace('(' . $template_category_name[0][0] . ')', "", $new_contents, 1);
    	$new_contents = preg_replace('(' . $template_account_name[0][0] . ')', "", $new_contents, 1);

    	// replace for gross profit
    	$new_contents = preg_replace('(' . $template_gross_profit[0][0] . ')', $gross_profit_final_template, $new_contents, 1);

    	$add_line = "";

    	if(!empty($temp_content_trs_other_income))
    	{
    		$add_line = "<tr><td>&nbsp;</td></tr>";
    	}

    	$new_contents = preg_replace('(' . $template_total_by_category[0][0] . ')', $temp_content_trs_other_income . $add_line, $new_contents, 1);

		$pattern = "/{{[^}}]*}}/";
		$template = $new_contents;
		preg_match_all($pattern, $template, $matches);

		for($r = 0; $r < count($matches[0]); $r++)
		{
			$string1 = (str_replace('{{', '',$matches[0][$r]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches[0][$r];

			// temporary like this first. (Will change in the future)
			if($string2 == "Year End")
			{
				$current_last_year_end = $this->fs_model->calculate_difference_dates($fs_company_info_id, "General");

        		$last_fye_end    = $current_last_year_end['last_fye_end'];
        		$current_fye_end = $current_last_year_end['current_fye_end'];

				$template = str_replace($replace_string, $current_fye_end, $template);
			}
			elseif($string2 == "width_td")
			{
				$template = str_replace($replace_string, '80', $template);
			}
			elseif($string2 == "Total operating expenses")
			{
				$total_operating_expenses = $this->fs_statements_model->get_total_operating_expenses($fs_company_info_id);

				$content = $this->fs_replace_content_model->negative_bracket($total_operating_expenses[0]["total_operating_expenses"]);

				$template = str_replace($replace_string, $content, $template);
			}
			elseif($string2 == "As per schedule")
			{
				$template = str_replace($replace_string, 'As per schedule', $template);
			}
			// elseif($string2 == "Gross Profit")
			// {
			// 	$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($state_detailed_pro_loss_main_total[0]['gross_profit']), $template);
			// }
			elseif($string2 == "Profit for the year")
			{
				$template = str_replace($replace_string, $this->fs_replace_content_model->negative_bracket($state_detailed_pro_loss_main_total[0]['profit_for_the_year']), $template);
			}
		}

		// echo json_encode($template);

		$new_content = $this->fs_replace_content_model->replace_toggle($matches[0], $template, "Statement of detailed profit or loss", $fs_company_info_id);
		$new_content = $this->fs_replace_content_model->replace_verbs_plural($new_content, "Statement of detailed profit or loss", $fs_company_info_id);

		$obj_pdf->writeHTML($new_content, true, false, false, false, '');
	}

	public function delete_document()
	{
		// Remove file 
		$this->load->helper("file");
		delete_files('./pdf/document/');

		echo json_encode(array("status" => true));
	}

	// public function replace_special_sign($match, $new_contents)
	// {
	// 	$pattern = "/{{[^}}]*}}/";
	// 	$template = $new_contents;
	// 	preg_match_all($pattern, $template, $string_to_replace);

	// 	if(count($string_to_replace[0]) != 0)
 //   		{
 //   			// echo json_encode($string_to_replace[0]);
	//    		for($r = 0; $r < count($string_to_replace[0]); $r++)
	// 		{
	// 			$string1 = (str_replace('{{', '',$string_to_replace[0][$r]));
	// 			$string2 = (str_replace('}}', '',$string1));

	// 			if($string2 == "dollar sign")
	// 			{
	// 				$replace_string = $string_to_replace[0][$r];
	// 				$temp_content = '$';
	// 			}

	// 			$new_contents = str_replace($replace_string, $temp_content, $new_contents);
	// 		}
	// 	}

	// 	return $new_contents;
	// }

	public function get_header_template($document_info, $firm_id = NULL)
	{
		if(!is_null($firm_id))
		{
			$firm = $this->db->query("SELECT firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
										JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
										JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
										JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
										where firm.id = ". $firm_id);
        	$firm = $firm->result_array();
        	$firm_logo = !empty($firm[0]["file_name"])?'<img src="uploads/logo/'. $firm[0]["file_name"] .'" height="60" />' : '';
		}	

		// if($document_type == "DRIW")
		// {
		// 	return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
		// 		<p style="text-align: center;"><span style="font-size: 10pt;">RESOLUTION IN WRITING PURSUANT TO REGULATION OF THE COMPANY&rsquo;S CONSTITUTION</span></p>
		// 		<hr />';
		// }
		// elseif($document_type == "Attendance")
		// {
		// 	return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
		// 		<p style="text-align: center;"><span style="font-size: 10pt;"><strong>ATTENDANCE LIST</strong></span></p>';
		// }
		// else
		if($document_info['type'] == "Company Info Header")
		{
			return '<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: calibri; font-size: 10pt;" border="0">
					<tbody>
						<tr style="height: 80px;"><td style="width: 24.275%; text-align: left; height: 80px;" align="center">'.$firm_logo.'</td><td style="width:5px;"></td>
							<td style="width: 75.725%; height: 80px;"><span style="font-size: 14pt;"><strong>'.$firm[0]["name"].'</strong></span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $firm[0]["registration_no"] .'<br />Address: '. $firm[0]["street_name"] .', #'. $firm[0]["unit_no1"] .'-'.$firm[0]["unit_no2"].' '. $firm[0]["building_name"] .', Singapore '. $firm[0]["postal_code"] .'<br />Tel: '. $firm[0]["telephone"] .' &nbsp; Fax: '. $firm[0]["fax"] .'<br />Email: <span style="font-size: 7pt;">'. $firm[0]["email"] .'</span>&nbsp;</span></td>
						</tr>
					</tbody>
				</table>';
		}
		elseif($document_info['type']  == "headerOnly")
		{
			return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>';
		}
		elseif($document_info['type'] == "statements")
		{
			return '<p style="font-size: 10pt; font-weight:normal; line-height: 1.5;"><strong>{{client name}}</strong><br/><i style="font-size:14pt;">' . $document_info['title'] . '</i><br/>for the year ended {{Current Year End - Ending}}</p>';
		}
		elseif($document_info['type'] == "note")
		{
			return '<p style="font-size: 10pt; font-weight:normal; line-height: 1.5;"><strong>{{client name}}</strong><br/><i style="font-size:14pt;">' . $document_info['title'] . '</i><br/></p>';
		}
	}

	public function remove_group_part_from_template($template, $is_group)
	{	
		if(!$is_group)	// if not group then remove group columns
		{
			$group_template = $this->fs_replace_content_model->get_part_of_template('<td class="group"', 'td', $template);

			foreach ($group_template[0] as $key => $value) {
				// echo json_encode($value);
				// echo '<br/><br/>';

				$template = preg_replace('(' . $value . ')', "", $template, 1);
			}

			return $template;	// new template
		}
		else
		{
			return $template;
		}
	}

	// public function get_part_of_template($related_part, $tagType, $template)
	// {
	// 	if(strpos($template, $related_part) !== false)
 //    	{
	// 		preg_match_all ('/' . $related_part . '(.+?)<\/' . $tagType . '>/s', $template, $taken_template);

	// 		return $taken_template;
	// 	}
	// 	else
	// 	{
	// 		return '';
	// 	}
	// }

	// public function negative_bracket($number)
	// {
	// 	if($number == 0)
	// 	{
	// 		return "-";
	// 	}
	// 	elseif($number < 0)
	// 	{
	// 		return "(" . number_format(abs($number), 2) . ")";
	// 	}
	// 	else
	// 	{
	// 		return number_format($number, 2);
	// 	}
	// }

	

	
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
        $this->SetFont('calibri', 'B', 23);
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
   }

   public function Footer() {
        $this->SetY(-18);
        $this->Ln();
        
        // Page number
        if (empty($this->pagegroups)) {
            // $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().'/'.$this->getAliasNbPages();
            $pagenumtxt = 'Page | '.$this->getAliasNumPage();
        } else {
            // $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias();
            $pagenumtxt = 'Page | '.$this->getPageNumGroupAlias();
        }

        if(!$this->one_page_only && $this->getPage() != 1){
        	$this->SetFont('calibri', '', 8);
        	$this->Cell(0, 10, $pagenumtxt, 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
        
        // if(!$this->last_page_flag){
	       //  $this->SetFont('helvetica', 'I', 8);
	       //  $this->Cell(0, 10, 'continue to the next page...', 0, false, 'R', 0, '', 0, false, 'T', 'M');
        // }

        $this->total_page++;
   }
}
