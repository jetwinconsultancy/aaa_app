<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class Transaction_document extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'download', 'url'));
		$this->load->library(array('zip', 'Phpword', 'Excel'));
		$this->load->model(array('convert_number_to_word_model', 'transaction_model'));

	}

	public function index()
	{
		$this->load->helper('pdf_helper');
		$document_master_id = [169];

		//echo json_encode(count($billing_id));
		$array_link = [];
		if(count($document_master_id) != 0)
		{
			// $temp_file_name = $_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/my-pdf.zip';
			// $zip = new ZipArchive();
			// $file = $temp_file_name;
			// $zip->open($file, ZipArchive::CREATE);



			for($i = 0; $i < count($document_master_id); $i++)
			{
				//echo ($billing_id[$i]);
				$q = $this->db->query("select * from document_master where id = 326");

		       	$q = $q->result_array();

		       	/*$p = $this->db->query("select billing.id, billing.company_code, billing_service.billing_id as billing_service_billing_id, billing_service.client_billing_info_id as billing_service_client_billing_id, client_billing_info.client_billing_info_id, client_billing_info.company_code, billing_service.invoice_description, billing_service.amount, billing_service.gst_rate from billing left join billing_service on billing_service.billing_id = billing.id left join client_billing_info on client_billing_info.client_billing_info_id = billing_service.client_billing_info_id AND client_billing_info.company_code = billing.company_code where billing.id =".$billing_id[$i]."");

		       	$p = $p->result_array();

		       	$query = $this->db->query("select * from firm");

				$query = $query->result_array();*/

				$query = $this->db->query("select * from firm where id = '".$q[0]["firm_id"]."'");

				$query = $query->result_array();

		       	$this->load->helper('pdf_helper');

	    		// create new PDF document
			    $obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Delivery Order";
				$obj_pdf->SetTitle($title);
				$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width: 101.5%; height: 150px;"><tr><td style="width:101.5%; height: 150px;"><img src="'.base_url().'/img/do_header.jpeg" width="1200" height="150"></td></tr></table>', $tc=array(0,0,0), $lc=array(0,0,0));

				//$obj_pdf->setPrintFooter(false);
				$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);
				$obj_pdf->AddPage();
				$obj_pdf->setY(33);


				$content = $q[0]["document_content"];
				$obj_pdf->writeHTML($content, true, false, false, false, '');


				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].'.pdf', 'F');

    			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].'.pdf')){
			        echo "File Doesn't Exist...";exit;
			    }

				
				// $zip->addFile($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].'.pdf', $q[0]["document_name"].'.pdf');
				$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].'.pdf');
				

			}
			//$zip->close();
			$this->zip->archive($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/my_backup.zip');

			$this->zip->download('my_backup.zip');
			
		}
		
	    
	}

	public function delete_document()
	{
		//$path = $_POST["path"];

		// Remove file 
		$this->load->helper("file");
		delete_files('./pdf/document/');

		echo json_encode(array("status" => true));
	}

	public function generate_document()
	{
		$document_master_id = $_POST["document_master_id"];
		$company_code = $_POST["company_code"];
		$transaction_task_name = $_POST["transaction_task_name"];
		$transaction_master_id = $_POST["transaction_master_id"];

		$array_link = [];
		if(count($document_master_id) != 0)
		{
			if($transaction_task_name == "Incorporation of new company" || $transaction_task_name == "Take Over of Secretarial")
			{
				$client_query = $this->db->query("select * from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
			}
			else
			{
				$client_query = $this->db->query("select * from client where company_code='".$company_code."' AND client.deleted != 1");
			}
			

			$client_query = $client_query->result_array();

			$this->db->where('transaction_pending_documents.client_id', $client_query[0]["id"]);
			// $this->db->where('transaction_pending_documents.received_on', "");
			$this->db->where('transaction_pending_documents.firm_id', $this->session->userdata("firm_id"));
			$this->db->where('transaction_pending_documents.transaction_id', $transaction_master_id);
			$this->db->delete('transaction_pending_documents');
			
			$this->db->where('transaction_pending_documents_file.transaction_id', $transaction_master_id);
			$this->db->delete('transaction_pending_documents_file');

			for($i = 0; $i < count($document_master_id); $i++)
			{

				$q = $this->db->query("select * from document_master where id = '".$document_master_id[$i]."'");

		       	$q = $q->result_array();

				$query = $this->db->query("select * from firm where id = '".$q[0]["firm_id"]."'");

				$query = $query->result_array();

		       	$this->load->helper('pdf_helper');

				$pattern = "/{{[^}}]*}}/";
				$subject = $q[0]["document_content"];
				preg_match_all($pattern, $subject, $matches);

				//echo json_encode($matches[0]);
				//$num_of_activity = 1;
				
				$new_contents_info = $q[0]["document_content"];
				//print_r(count($matches[0]));

				if($q[0]["document_name"] == "Form 45")
				{
					$get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 1");
					//echo json_encode($get_directors_info->num_rows());
					if($get_directors_info->num_rows())
					{
						$get_directors_info = $get_directors_info->result_array();
						
						for($t = 0 ; $t < count($get_directors_info) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_directors_info[$t]["id"], $q[0]["document_name"]);

							$content = utf8_encode($new_contents);

							//echo json_encode($content);

							$obj_pdf->writeHTML($content, true, false, true, false, '');
							//ob_clean();
							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf');

// 							$phpWord = new \PhpOffice\PhpWord\PhpWord();
// 						    // $phpWord->getCompatibility()->setOoxmlVersion(14);
// 						    // $phpWord->getCompatibility()->setOoxmlVersion(15);


// 						    $filename = 'test.docx';
// 						    // add style settings for the title and paragraph

// 						    $section = $phpWord->addSection();
// 						    $html = "<h1>HELLO WORLD!</h1>";
// $html .= $content;
// $html .= "<table><tr><td>A table</td><td>Cell</td></tr></table>";
// 						//$html = $this->escape($html);
// 						    \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
// 						    // $section->addTextBreak(1);
// 						    // $section->addText("It's cold outside.");
// // 						    header('Content-Type: application/octet-stream');
// // header('Content-Disposition: attachment;filename="convert.docx"');
// 						    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

// 						    $objWriter->save($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_directors_info[$t]["name"].'.html');

// // 						    $file=$_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_directors_info[$t]["name"].'.html';
// // $test=$content;
// // header("Content-type: application/vnd.ms-word");
// // header("Content-Disposition: attachment; filename=$file");
// // echo $test;
// 						    $this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_directors_info[$t]["name"].'.html');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_directors_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}

						
					}
				}
				else if($q[0]["document_name"] == "Form 45B")
				{
					$get_secretary_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4");
					//echo json_encode($get_directors_info->num_rows());
					if($get_secretary_info->num_rows())
					{
						$get_secretary_info = $get_secretary_info->result_array();
						
						for($t = 0 ; $t < count($get_secretary_info) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();

							if($get_secretary_info[$t]["name"] == "LOOI YONG KEAN" || $get_secretary_info[$t]["name"] == "KONG TZE KARN")
							{
								if(strpos($new_contents_info, '<div class="hide_table"') !== false)
                				{
                					$new_contents_info = str_replace('<div class="hide_table">','<div class="hide_table" style="display: none">',$new_contents_info);
                				}

							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_secretary_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							//echo json_encode($get_directors_info[$t]["id"]);
							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_secretary_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}


					}
				}
				else if($q[0]["document_name"] == "Shares allotment form" || $q[0]["document_name"] == "Allotment-Share Cert" || $q[0]["document_name"] == "Allotment-Share Application Form" || $q[0]["document_name"] == "Transferee-Share Cert")
				{
					$get_member_info = $this->db->query("select transaction_member_shares.*, officer.name, officer_company.company_name, client.company_name as client_company_name, transaction_certificate.new_certificate_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 right join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."' AND transaction_member_shares.number_of_share > 0");
					
					if($get_member_info->num_rows())
					{
						$get_member_info = $get_member_info->result_array();

						// echo json_encode($get_member_info);
						
						for($t = 0 ; $t < count($get_member_info) ; $t++)
						{
							if($get_member_info[$t]["number_of_share"] > 0)
							{
								$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
								$obj_pdf->SetCreator(PDF_CREATOR);
								$title = "Document";
								$obj_pdf->SetTitle($title);
								// if($q[0]["document_name"] == "Shares allotment form" || $q[0]["document_name"] == "Allotment-Share Application Form")
								if($q[0]["document_name"] == "Shares allotment form")
								{
									$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs='<div style="font-family: Helvetica, Sans-Serif;text-align: center;position: fixed; margin: 20px; width: 100%; font-weight: bold; font-size: 17px;">'.$client_query[0]["company_name"].'</div><br/><hr>', $tc=array(0,0,0), $lc=array(0,0,0));
								}

								$obj_pdf->setPrintFooter(false);
								$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
								$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
								$obj_pdf->SetDefaultMonospacedFont('helvetica');
								$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
								$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
								$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
								$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
								$obj_pdf->SetFont('helvetica', '', 10);
								$obj_pdf->setFontSubsetting(false);
								
								if($q[0]["document_name"] == "Allotment-Share Cert" || $q[0]["document_name"] == "Transferee-Share Cert")
								{
									$obj_pdf->AddPage('L');
								}
								else
								{
									$obj_pdf->AddPage();
								}
								
								$obj_pdf->setY(25);
								

								$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_member_info[$t]["id"], $q[0]["document_name"]);

								$content = $new_contents;

								//echo json_encode($get_directors_info[$t]["id"]);
								$obj_pdf->writeHTML($content, true, false, false, false, '');

								if($get_member_info[$t]["name"] != null)
								{
									$member_name = $get_member_info[$t]["name"];
								}
								else if($get_member_info[$t]["company_name"] != null)
								{
									$member_name = $get_member_info[$t]["company_name"];
								}
								else if($get_member_info[$t]["client_company_name"] != null)
								{
									$member_name = $get_member_info[$t]["client_company_name"];
								}

								if($q[0]["document_name"] == "Shares allotment form" || $q[0]["document_name"] == "Allotment-Share Application Form")
								{
									$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $member_name).'.pdf', 'F');

									if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $member_name).'.pdf'))
					    			{
								        echo "File Doesn't Exist...";exit;
								    }

									$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $member_name).'.pdf');
								}
								else
								{
									$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/Share Cert '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_member_info[$t]["new_certificate_no"]).'.pdf', 'F');

									if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/Share Cert '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_member_info[$t]["new_certificate_no"]).'.pdf'))
					    			{
								        echo "File Doesn't Exist...";exit;
								    }

									$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/Share Cert '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_member_info[$t]["new_certificate_no"]).'.pdf');
								}

								$data['transaction_id'] = $transaction_master_id;
								$data['type'] = "trans"; 
								$data['client_id'] = $client_query[0]["id"];
								$data['firm_id'] = $q[0]["firm_id"];
								$data['document_name'] = $q[0]["document_name"].' - '.$member_name;
								$data['triggered_by'] = 1;
								$data['document_date_checkbox'] = 1;
								$data['transaction_date'] = DATE("d/m/Y",now());
								$data['content'] = $content;
		                		$data['created_by']=$this->session->userdata('user_id');

		                		$this->save_incorporate_pdf($data);
		                	}
						}
					}
				}
				else if($q[0]["document_name"] == "CSS Proposal")
				{
					$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Document";
					$obj_pdf->SetTitle($title);

					// $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width: 100%; height: 150px;"><tr><td style="width:160px; height: 50px;"><table style="width: 100%; padding-top: 10px;"><tr><td style="width:130px; height: 30px;"><img src="'.base_url().'/img/acumen_bizcorp_header.jpg" width="120" height="30"></td></tr></table></td><td style="text-align:justify;"><span style="font-weight: bold; font-size: 12px;">ACUMEN BIZCORP PTE. LTD.</span><br/><span style="font-size: 8px;">UEN: 201431547H</span><br/><span style="font-size: 8px;">Address: 143 Cecil Street, #16-03 GB Building, Singapore 069542</span><br/><span style="font-size: 8px;">Tel No.: (+65) 6220 1939, 6220 0288   Fax No.: (+65) 6532 0669</span><br/><span style="font-size: 8px; color: #007ac0;">Email: admin@acumenbizcorp.com.sg</span></td></td></tr></table><br/><hr cellpadding="5">', $tc=array(0,0,0), $lc=array(0,0,0));

					$query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
									JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
									JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
									JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
									where firm.id = '".$this->session->userdata("firm_id")."'");

					$query = $query->result_array();

					$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='<table style="width: 100%; border-collapse: collapse; height: 75px; font-family: arial, helvetica, sans-serif; font-size: 10pt" border="0"><tbody>
			<tr style="height: 95px;">
			<td style="width: 37.0114%; height: 75px;" align="center"><img src="uploads/logo/'. $query[0]["file_name"] .'" height="60" /></td>
			<td style="width: 62.9886%; height: 75px;">
				<span style="font-size: 14pt;"><strong>'.$query[0]["name"].'</strong></span><br />
				<span style="font-size: 8pt; text-align:left;">UEN: '. $query[0]["registration_no"] .'<br/>Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'<br /><span style="font-size: 8px; color: #007ac0;">Email: '. $query[0]["email"] .'</span>&nbsp;</span></td>
			</tr></tbody></table><hr cellpadding="2">', $tc=array(0,0,0), $lc=array(0,0,0));

					//$obj_pdf->setPrintFooter(false);
					$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
					$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();
					$obj_pdf->SetTopMargin(37);
					// $obj_pdf->setListIndentWidth(4);
					//$obj_pdf->setY(38);

					// $new_contents = $this->replaceToggle($matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, null, $q[0]["document_name"]);

					$str = $new_contents_info;
					$latest_billing_table = "";
		            $allotment_string = "";

		            $get_billing_info = $this->db->query("select transaction_client_billing_info.*, our_service_info.service_name, unit_pricing.unit_pricing_name as frequency_name from transaction_client_billing_info left join our_service_info on our_service_info.id = transaction_client_billing_info.service left join unit_pricing on unit_pricing.id = transaction_client_billing_info.unit_pricing where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

					$get_billing_info = $get_billing_info->result_array();
					//echo json_encode(count($get_billing_info));
					if(strpos($str, '<tr class="loop"') !== false)
                	{
	            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
	            	
	                	for($g = 0; $g < count($get_billing_info); $g++)
	                	{

	            			$billing_html_string = $abstract_string_array[0][0];
	            			//echo json_encode($abstract_string_array[0][0]);
	                		if(strpos($billing_html_string, '<span class="myclass mceNonEditable">{{Billing services}}</span>') !== false)
		                	{
	                				$billing_html_string = str_replace('<span class="myclass mceNonEditable">{{Billing services}}</span>', $get_billing_info[$g]["service_name"], $billing_html_string);
	                		}

	                		if(strpos($billing_html_string, '<span class="myclass mceNonEditable">{{Billing amount}}</span>') !== false)
	                		{
	                			$billing_html_string = str_replace('<span class="myclass mceNonEditable">{{Billing amount}}</span>', number_format($get_billing_info[$g]["amount"], 2), $billing_html_string);
	                		}

	                		if(strpos($billing_html_string, '<span class="myclass mceNonEditable">{{Billing period}}</span>') !== false)
	                		{
	                			$billing_html_string = str_replace('<span class="myclass mceNonEditable">{{Billing period}}</span>', $get_billing_info[$g]["frequency_name"], $billing_html_string);
	                		}

	                		$latest_billing_table = $latest_billing_table.$billing_html_string;
	                		
		            		
	                	}
	                	$new_contents = str_replace($abstract_string_array[0][0], $latest_billing_table, $str);
	                }

	                $director_signature_result = $this->db->query("select director_signature_1, director_signature_2 from transaction_client_signing_info where company_code='".$company_code."'");

					if ($director_signature_result->num_rows() > 0) 
					{
	                	$director_signature_result = $director_signature_result->result_array();

	                	if($director_signature_result[0]["director_signature_1"] != "0")
	                	{
		                	$client_officer = $this->db->query("select * from transaction_client_officers where id='".$director_signature_result[0]["director_signature_1"]."'");

	                		$client_officer = $client_officer->result_array();

	                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["name"];

		                	$new_contents = str_replace('<span class="myclass mceNonEditable">{{Director Signature 1}}</span>', $name, $new_contents);
		                	//echo json_encode($new_contents);
	                	}
	                	else
	                	{
	                		$new_contents = str_replace('<div class="director_signature_1">', '<div class="director_signature_1" style="display: none">', $new_contents);
	                	}

	                	if($director_signature_result[0]["director_signature_2"] != "0")
	                	{
		                	$client_officer = $this->db->query("select * from transaction_client_officers where id='".$director_signature_result[0]["director_signature_2"]."'");

	                		$client_officer = $client_officer->result_array();

	                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["name"];

		                	$new_contents = str_replace('<span class="myclass mceNonEditable">{{Director Signature 2}}</span>', $name, $new_contents);
		                	
	                	}
	                	else
	                	{
	                		$new_contents = str_replace('<div class="director_signature_2">', '<div class="director_signature_2" style="display: none">', $new_contents);
	                	}
	                }

					
					$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents, null, $q[0]["document_name"]);

					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
				else if($q[0]["document_name"] == "Ltrs Transfer of Shares")
				{
					$transferor_result = $this->db->query("select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."' AND 0 > transaction_member_shares.number_of_share");

					$transferor_result = $transferor_result->result_array();

					$member_transfer_info = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

					

					for($f = 0; $f < count($transferor_result); $f++)
		            {
		            	$str = $new_contents_info;

		            	$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setPrintHeader(false);
						$obj_pdf->setPrintFooter(false);
						$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						$obj_pdf->SetDefaultMonospacedFont('helvetica');
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$obj_pdf->SetFont('helvetica', '', 10);
						$obj_pdf->setFontSubsetting(false);
						$obj_pdf->AddPage();

						if(strpos($str, '<tr class="loop"') !== false)
	                	{
		            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
		            		$latest_transfer_table = "";
		            		$get_transferor_name = "";
	                		$multiple_transferee_name = "";
	                		$array_transferee_name = array();
		                	for($g = 0; $g < count($member_transfer_info); $g++)
		                	{
		                		
		                		$transfer_html_string = $abstract_string_array[0][0];

		                		if($member_transfer_info[$g]->from_transfer_member_id == $transferor_result[$f]["id"])
		                		{
		                			if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
				                	{
				                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format($member_transfer_info[$g]->to_number_of_share, 2), $transfer_html_string);
				                	}

				                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
				                	{
				                		if($member_transfer_info[$g]->from_officer_name != null)
				                		{
				                			$transferor_name = $member_transfer_info[$g]->from_officer_name;
				                		}
				                		elseif($member_transfer_info[$g]->from_officer_company_name != null)
				                		{
				                			$transferor_name = $member_transfer_info[$g]->from_officer_company_name;
				                		}
				                		elseif($member_transfer_info[$g]->from_client_company_name != null)
				                		{
				                			$transferor_name = $member_transfer_info[$g]->from_client_company_name;
				                		}

				                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transferor_name, $transfer_html_string);

				                		$get_transferor_name = $transferor_name;
				                	}


				                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
				                	{
				                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', $member_transfer_info[$g]->currency.$member_transfer_info[$g]->from_consideration, $transfer_html_string);
				                	}

				                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
				                	{
				                		if($member_transfer_info[$g]->to_officer_name != null)
				                		{
				                			$transferee_name = $member_transfer_info[$g]->to_officer_name;
				                		}
				                		elseif($member_transfer_info[$g]->to_officer_company_name != null)
				                		{
				                			$transferee_name = $member_transfer_info[$g]->to_officer_company_name;
				                		}
				                		elseif($member_transfer_info[$g]->to_client_company_name != null)
				                		{
				                			$transferee_name = $member_transfer_info[$g]->to_client_company_name;
				                		}

				                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transferee_name, $transfer_html_string);

				                		array_push($array_transferee_name, $transferee_name);
				                	}
				                	$latest_transfer_table = $latest_transfer_table.$transfer_html_string;
		                		}

		                	}

		                	$new_contents = str_replace($abstract_string_array[0][0], $latest_transfer_table, $str);

		                	$new_contents = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $get_transferor_name, $new_contents);

		                	//echo json_encode($new_contents);
	                		for($r = 0; $r < count($array_transferee_name); $r++)
	                		{
	                			if($r == 0)
	                			{
	                				$multiple_transferee_name = $array_transferee_name[$r];
	                			}
	                			else if($r == (count($array_transferee_name) - 1))
	                			{
	                				$multiple_transferee_name = $multiple_transferee_name.", and ".$array_transferee_name[$r];
	                			}
	                			else
	                			{
	                				$multiple_transferee_name = $multiple_transferee_name.", ".$array_transferee_name[$r];
	                			}
	                		}
	                		$new_contents = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $multiple_transferee_name, $new_contents);

	                		$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents, $transferor_result[$f]["id"], $q[0]["document_name"]);
		                }

		                $obj_pdf->writeHTML($content, true, false, false, false, '');

						$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferor_name).')'.'.pdf', 'F');

						if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferor_name).')'.'.pdf'))
		    			{
					        echo "File Doesn't Exist...";exit;
					    }

						$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferor_name).')'.'.pdf');

						$data['transaction_id'] = $transaction_master_id;
						$data['type'] = "trans"; 
						$data['client_id'] = $client_query[0]["id"];
						$data['firm_id'] = $q[0]["firm_id"];
						$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.$transferor_name.')';
						$data['triggered_by'] = 1;
						$data['document_date_checkbox'] = 1;
						$data['transaction_date'] = DATE("d/m/Y",now());
						$data['content'] = $content;
	            		$data['created_by']=$this->session->userdata('user_id');

	            		$this->save_incorporate_pdf($data);
		            }
				}
				else if($q[0]["document_name"] == "Transfer Form")
				{
					$member_transfer_info = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

					for($g = 0; $g < count($member_transfer_info); $g++)
		            {
		            	$str = $new_contents_info;

		            	$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setPrintHeader(false);
						$obj_pdf->setPrintFooter(false);
						$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						$obj_pdf->SetDefaultMonospacedFont('helvetica');
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$obj_pdf->SetFont('helvetica', '', 10);
						$obj_pdf->setFontSubsetting(false);
						$obj_pdf->AddPage();

		            	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share number}}</span>') !== false)
	                	{
	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share number}}</span>', strtoupper($this->convert_number_to_word_model->convert_number_to_words($member_transfer_info[$g]->to_number_of_share))." (".number_format($member_transfer_info[$g]->to_number_of_share).")", $str);
	                	}

	                	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - share type}}</span>') !== false)
	                	{	//print_r($member_transfer_info[$g]);
	                		if($member_transfer_info[$g]->class_id == 2)
							{
								$share_type = $member_transfer_info[$g]->other_class;
							}
							else
							{
								$share_type = $member_transfer_info[$g]->sharetype;
							}

	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - share type}}</span>', $share_type, $str);
	                	}
	                	
						if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
	                	{//print_r($member_transfer_info);
	                		if($member_transfer_info[$g]->from_officer_name != null)
	                		{
	                			$transferor_name = $member_transfer_info[$g]->from_officer_name;
	                			$doc_transferor_name = $member_transfer_info[$g]->from_officer_name;
	                		}
	                		elseif($member_transfer_info[$g]->from_officer_company_name != null)
	                		{
	                			$doc_transferor_name = $member_transfer_info[$g]->from_officer_company_name;

	                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_transfer_info[$g]->from_officer_company_register_no.'" and corporate_representative.subsidiary_name = "'.$member_transfer_info[$g]->client_company_name.'"');

	                			$get_corp_rep_info = $get_corp_rep_info->result_array();

	                			for($b = 0; $b < count($get_corp_rep_info); $b++)
                				{
                					// $directors_html_string_corp_rep = '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$member_transfer_info[$g]->client_company_name.')</span>';

                					$directors_html_string_corp_rep = '<span>'.$member_transfer_info[$g]->client_company_name.'</span><br/><span>Corp Rep Name: '.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>Corp Rep ID: '.$get_corp_rep_info[$b]["identity_number"].'</span>';
                					
                				}

                				$transferor_name = $directors_html_string_corp_rep;
	                		}
	                		elseif($member_transfer_info[$g]->from_client_company_name != null)
	                		{
	                			$doc_transferor_name = $member_transfer_info[$g]->from_client_company_name;
	                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_transfer_info[$g]->from_client_regis_no.'" and corporate_representative.subsidiary_name = "'.$member_transfer_info[$g]->client_company_name.'"');

	                			$get_corp_rep_info = $get_corp_rep_info->result_array();

	                			for($b = 0; $b < count($get_corp_rep_info); $b++)
                				{
                					// $directors_html_string_corp_rep = '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$member_transfer_info[$g]->client_company_name.')</span>';

                					$directors_html_string_corp_rep = '<span>'.$member_transfer_info[$g]->client_company_name.'</span><br/><span>Corp Rep Name: '.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>Corp Rep ID: '.$get_corp_rep_info[$b]["identity_number"].'</span>';
                				}

                				$transferor_name = $directors_html_string_corp_rep;
	                		}

	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name (FULL)}}</span>', $transferor_name, $str);
	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $doc_transferor_name, $str);
	                	}

	                	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>') !== false)
	                	{
	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferor - consideration}}</span>', $member_transfer_info[$g]->currency.number_format($member_transfer_info[$g]->from_consideration,2), $str);
	                	}

	                	if(strpos($str, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
	                	{
	                		if($member_transfer_info[$g]->to_officer_name != null)
	                		{
	                			$transferee_name = $member_transfer_info[$g]->to_officer_name;
	                			$doc_transferee_name = $member_transfer_info[$g]->to_officer_name;
	                		}
	                		elseif($member_transfer_info[$g]->to_officer_company_name != null)
	                		{
	                			$doc_transferee_name = $member_transfer_info[$g]->to_officer_company_name;

	                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_transfer_info[$g]->to_officer_company_register_no.'" and corporate_representative.subsidiary_name = "'.$member_transfer_info[$g]->client_company_name.'"');

	                			$get_corp_rep_info = $get_corp_rep_info->result_array();

	                			for($b = 0; $b < count($get_corp_rep_info); $b++)
                				{
                					// $directors_html_string_corp_rep = '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$member_transfer_info[$g]->client_company_name.')</span>';

                					$directors_html_string_corp_rep = '<span>'.$member_transfer_info[$g]->client_company_name.'</span><br/><span>Corp Rep Name: '.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>Corp Rep ID: '.$get_corp_rep_info[$b]["identity_number"].'</span>';
                				}

                				$transferee_name = $directors_html_string_corp_rep;
	                		}
	                		elseif($member_transfer_info[$g]->to_client_company_name != null)
	                		{
	                			$doc_transferee_name = $member_transfer_info[$g]->to_client_company_name;

	                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_transfer_info[$g]->to_client_regis_no.'" and corporate_representative.subsidiary_name = "'.$member_transfer_info[$g]->client_company_name.'"');

	                			$get_corp_rep_info = $get_corp_rep_info->result_array();

	                			for($b = 0; $b < count($get_corp_rep_info); $b++)
                				{
                					// $directors_html_string_corp_rep = '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$member_transfer_info[$g]->client_company_name.')</span>';

                					$directors_html_string_corp_rep = '<span>'.$member_transfer_info[$g]->client_company_name.'</span><br/><span>Corp Rep Name: '.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>Corp Rep ID: '.$get_corp_rep_info[$b]["identity_number"].'</span>';
                				}
								$transferee_name = $directors_html_string_corp_rep;
	                		}

	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name (FULL)}}</span>', $transferee_name, $str);
	                		$str = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $doc_transferee_name, $str);

	                	}

	                	$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $str, $member_transfer_info[$g]->to_transfer_member_id, $q[0]["document_name"]);

			            $obj_pdf->writeHTML($content, true, false, false, false, '');

						$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferee_name).')'.'.pdf', 'F');

						if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferee_name).')'.'.pdf'))
		    			{
					        echo "File Doesn't Exist...";exit;
					    }

						$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_query[0]["company_name"].' ('.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $transferee_name).')'.'.pdf');

						$data['transaction_id'] = $transaction_master_id;
						$data['type'] = "trans"; 
						$data['client_id'] = $client_query[0]["id"];
						$data['firm_id'] = $q[0]["firm_id"];
						$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
						$data['triggered_by'] = 1;
						$data['document_date_checkbox'] = 1;
						$data['transaction_date'] = DATE("d/m/Y",now());
						$data['content'] = $content;
	            		$data['created_by']=$this->session->userdata('user_id');

	            		$this->save_incorporate_pdf($data);
		            }
				}
				else if($q[0]["document_name"] == "DRIW-Allotment of Shares" || $q[0]["document_name"] == "F24 - Return of allotment of shares" || $q[0]["document_name"] == "DRIW - Transfer of Shares")
				{	
					$header_content = '';

					if($q[0]["document_name"] == "DRIW-Allotment of Shares" || $q[0]["document_name"] == "DRIW - Transfer of Shares")
					{
						$header_content = $this->get_header_template("DRIW");

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
						$obj_pdf->SetAutoPageBreak(TRUE, 30);
						$obj_pdf->AddPage();
						$obj_pdf->setListIndentWidth(4);
					}
					else 
					{
						$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setPrintHeader(false);
						$obj_pdf->setPrintFooter(false);
						$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						$obj_pdf->SetDefaultMonospacedFont('helvetica');
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

						if($q[0]["document_name"] == "F24 - Return of allotment of shares")
						{
							$obj_pdf->SetAutoPageBreak(TRUE, 15);
						}

						$obj_pdf->SetFont('helvetica', '', 10);
						$obj_pdf->setFontSubsetting(false);
						$obj_pdf->AddPage();
						$obj_pdf->setListIndentWidth(4);
					}

					//$obj_pdf->SetTopMargin(37);

					$str = $new_contents_info;
					$latest_allotment_table = "";
					$latest_transfer_table = "";
					$list_no = "";
		            $allotment_string = "";
		            // 

		            	
		            if($q[0]["document_name"] == "DRIW - Transfer of Shares")
		            {
		            	$member_transfer_info = $this->transaction_model->getTransactionClientTransferMemberInfo($transaction_master_id);

		            	//echo ($member_transfer_info);
		            	if(strpos($str, '<tr class="loop"') !== false)
	                	{
		            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
		            	
		                	for($g = 0; $g < count($member_transfer_info); $g++)
		                	{
		                		$transfer_html_string = $abstract_string_array[0][0];

		                		if(strpos($transfer_html_string, '{{no}}') !== false)
			                	{
			                		$list_no = ($g + 1).".";
			                		$transfer_html_string = str_replace('{{no}}', $list_no, $transfer_html_string);
			                	}

		                		if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - share number}}</span>') !== false)
			                	{
			                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - share number}}</span>', number_format($member_transfer_info[$g]->to_number_of_share, 2), $transfer_html_string);
			                	}

			                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - name}}</span>') !== false)
			                	{
			                		if($member_transfer_info[$g]->from_officer_name != null)
			                		{
			                			$transferor_name = $member_transfer_info[$g]->from_officer_name;
			                		}
			                		elseif($member_transfer_info[$g]->from_officer_company_name != null)
			                		{
			                			$transferor_name = $member_transfer_info[$g]->from_officer_company_name;
			                		}
			                		elseif($member_transfer_info[$g]->from_client_company_name != null)
			                		{
			                			$transferor_name = $member_transfer_info[$g]->from_client_company_name;
			                		}

			                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - name}}</span>', $transferor_name, $transfer_html_string);
			                	}

			                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferor - ID}}</span>') !== false)
			                	{
			                		if($member_transfer_info[$g]->from_officer_identification_no != null)
			                		{
			                			$transferor_id = $member_transfer_info[$g]->from_officer_identification_no;
			                		}
			                		elseif($member_transfer_info[$g]->from_officer_company_register_no != null)
			                		{
			                			$transferor_id = $member_transfer_info[$g]->from_officer_company_register_no;
			                		}
			                		elseif($member_transfer_info[$g]->from_client_regis_no != null)
			                		{
			                			$transferor_id = $member_transfer_info[$g]->from_client_regis_no;
			                		}

			                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferor - ID}}</span>', $transferor_id, $transfer_html_string);
			                	}

			                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferee - name}}</span>') !== false)
			                	{
			                		if($member_transfer_info[$g]->to_officer_name != null)
			                		{
			                			$transferee_name = $member_transfer_info[$g]->to_officer_name;
			                		}
			                		elseif($member_transfer_info[$g]->to_officer_company_name != null)
			                		{
			                			$transferee_name = $member_transfer_info[$g]->to_officer_company_name;
			                		}
			                		elseif($member_transfer_info[$g]->to_client_company_name != null)
			                		{
			                			$transferee_name = $member_transfer_info[$g]->to_client_company_name;
			                		}

			                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - name}}</span>', $transferee_name, $transfer_html_string);
			                	}

			                	if(strpos($transfer_html_string, '<span class="myclass mceNonEditable">{{Transferee - ID}}</span>') !== false)
			                	{
			                		if($member_transfer_info[$g]->to_officer_identification_no != null)
			                		{
			                			$transferee_id = $member_transfer_info[$g]->to_officer_identification_no;
			                		}
			                		elseif($member_transfer_info[$g]->to_officer_company_register_no != null)
			                		{
			                			$transferee_id = $member_transfer_info[$g]->to_officer_company_register_no;
			                		}
			                		elseif($member_transfer_info[$g]->to_client_regis_no != null)
			                		{
			                			$transferee_id = $member_transfer_info[$g]->to_client_regis_no;
			                		}

			                		$transfer_html_string = str_replace('<span class="myclass mceNonEditable">{{Transferee - ID}}</span>', $transferee_id, $transfer_html_string);
			                	}
			                	$latest_transfer_table = $latest_transfer_table.$transfer_html_string;
		                	}
		                	$new_contents = str_replace($abstract_string_array[0][0], $latest_transfer_table, $str);
		                }

		            	//echo json_encode($member_transfer_info);
		                $new_contents = str_replace('{{no}}', (($list_no + 1)."."), $new_contents);

		            	$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents, null, $q[0]["document_name"]);

		            	
		            }
		            else
		            {
			            $allotment_member_result = $this->db->query("select transaction_member_shares.*, officer.identification_no, officer.name, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, nationality.nationality AS `officer_nationality`, officer_company.register_no, officer_company.country_of_incorporation, officer_company.company_name, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.registration_no, client.company_name as client_company_name, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, transaction_client_member_share_capital.other_class, sharetype.id as sharetype_id, sharetype.sharetype as sharetype_name, currency.currency as currency_name, transaction_certificate.certificate_no, transaction_certificate.new_certificate_no from transaction_member_shares left join transaction_certificate on transaction_certificate.officer_id = transaction_member_shares.officer_id and transaction_certificate.field_type = transaction_member_shares.field_type and transaction_certificate.transaction_id = transaction_member_shares.transaction_id and transaction_certificate.client_member_share_capital_id = transaction_member_shares.client_member_share_capital_id left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on transaction_member_shares.officer_id = client.id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id left join currency on currency.id = transaction_client_member_share_capital.currency_id  left join nationality on nationality.id = officer.nationality where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.transaction_page_id='".$transaction_master_id."'");

			            $allotment_member_result = $allotment_member_result->result_array();
			            // echo json_encode($allotment_member_result);
			            //print($allotment_member_result);
						if(strpos($str, '<tr class="loop"') !== false)
	                	{
		            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
		            	
		                	for($g = 0; $g < count($allotment_member_result); $g++)
		                	{
		                		$allotment_html_string = $abstract_string_array[0][0];
		            			//echo json_encode($abstract_string_array[0][0]);
		                		if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
			                	{
		                			if($allotment_member_result[$g]["name"] != '')
		                			{
										if($allotment_member_result[$g]['officer_address_type'] == "Local")
										{
											if($allotment_member_result[$g]["unit_no1"] != "" || $allotment_member_result[$g]["unit_no2"] != "")
											{
												$client_unit = ' #'.$allotment_member_result[$g]["unit_no1"] .' - '.$allotment_member_result[$g]["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($allotment_member_result[$g]["building_name1"] != "")
											{
												$members_building_name_1 = ' '.$allotment_member_result[$g]["building_name1"];
											}

											$offis_address_content = $allotment_member_result[$g]["street_name1"].',<br/>'.$client_unit.''.$members_building_name_1.',<br/>SINGAPORE '.$allotment_member_result[$g]["postal_code1"];
										}
										else if($allotment_member_result[$g]['officer_address_type'] == "Foreign")
										{
											$foreign_address1 = !empty($allotment_member_result[$g]["foreign_address1"])?$allotment_member_result[$g]["foreign_address1"]: '';
											$foreign_address2 = !empty($allotment_member_result[$g]["foreign_address2"])?',<br/>'. $allotment_member_result[$g]["foreign_address2"]: '';
											$foreign_address3 = !empty($allotment_member_result[$g]["foreign_address3"])?',<br/>'.$allotment_member_result[$g]["foreign_address3"]: '';

											$offis_address_content = $foreign_address1.$foreign_address2.$foreign_address3;
										}

		                				$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["name"].'<br/>(Identification no: '.$allotment_member_result[$g]["identification_no"].')<br/>('.$offis_address_content.')<br/>'. $allotment_member_result[$g]["officer_nationality"] .'<br/>', $allotment_html_string);

		                			}
		                			elseif($allotment_member_result[$g]["company_name"] != '')
		                			{
		                				if($allotment_member_result[$g]['officer_company_address_type'] != null)
										{
											if($allotment_member_result[$g]['officer_company_address_type'] == "Local")
											{
												if($allotment_member_result[$g]["company_unit_no1"] != "" || $allotment_member_result[$g]["company_unit_no2"] != "")
												{
													$client_unit = ' #'.$allotment_member_result[$g]["company_unit_no1"].' - '.$allotment_member_result[$g]["company_unit_no2"];
												}
												else
												{
													$client_unit = "";
												}

												if($allotment_member_result[$g]["building_name1"] != "")
												{
													$members_building_name_2 = ' '.$allotment_member_result[$g]["building_name1"];
												}

												$offis_company_address = $allotment_member_result[$g]["company_street_name"].',<br/>'. $client_unit.''.$members_building_name_2.',<br/>SINGAPORE '.$allotment_member_result[$g]["company_postal_code"];
											}
											else if($allotment_member_result[$g]['officer_company_address_type'] == "Foreign")
											{
												$company_foreign_address1 = !empty($allotment_member_result[$g]["company_foreign_address1"])?$allotment_member_result[$g]["company_foreign_address1"]: '';
												$company_foreign_address2 = !empty($allotment_member_result[$g]["company_foreign_address2"])?',<br/>'.$allotment_member_result[$g]["company_foreign_address2"]: '';
												$company_foreign_address3 = !empty($allotment_member_result[$g]["company_foreign_address3"])? ',<br/>'.$allotment_member_result[$g]["company_foreign_address3"]: '';

												$offis_company_address = $company_foreign_address1 . $company_foreign_address2 . $company_foreign_address3;
											}
										}
		                				$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["company_name"].'<br/>(Identification no: '.$allotment_member_result[$g]["register_no"].')<br/>('.$offis_company_address.')<br/>'. $allotment_member_result[$g]["country_of_incorporation"] .'<br/>', $allotment_html_string);
		                			}
		                			elseif($allotment_member_result[$g]["client_company_name"] != '')
		                			{
										if($allotment_member_result[$g]["client_unit_no1"] != "" || $allotment_member_result[$g]["client_unit_no2"] != "")
										{
											$client_unit = ' #'.$allotment_member_result[$g]["client_unit_no1"].' - '.$allotment_member_result[$g]["client_unit_no2"];
										}
										else
										{
											$client_unit = "";
										}

										if($allotment_member_result[$g]["building_name1"] != "")
										{
											$members_building_name_3 = ' '.$allotment_member_result[$g]["building_name1"];
										}

										$client_street_name = !empty($allotment_member_result[$g]["client_street_name"])?$allotment_member_result[$g]["client_street_name"]: '';
										if(!empty($client_unit) || !empty($members_building_name_3))
										{
											$break = ',<br/>';
										}
										else
										{
											$break = '';
										}

										$client_address = $client_street_name . $break . $client_unit.''.$members_building_name_3.',<br/>SINGAPORE '.$allotment_member_result[$g]["client_postal_code"];	

										$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $allotment_member_result[$g]["client_company_name"].'<br/>(Identification no: '.$allotment_member_result[$g]["registration_no"].')<br/>('.$client_address.')<br/>', $allotment_html_string);
					
		                			}
		                			// elseif($allotment_member_result[$g]["client_company_name"] != '')
		                			// {

		                			// }
		                		}

		                		if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
			                	{
			                		$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($allotment_member_result[$g]["number_of_share"], 2), $allotment_html_string);
			                	}

			                	if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>') !== false)
			                	{
			                		$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - amount of shares}}</span>', number_format($allotment_member_result[$g]["amount_share"], 2), $allotment_html_string);
			                	}

			                	if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - currency}}</span>') !== false)
			                	{
			                		$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - currency}}</span>', $allotment_member_result[$g]["currency_name"], $allotment_html_string);
			                	}

			                	if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>') !== false)
			                	{
			                		$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - certificate}}</span>', $allotment_member_result[$g]["new_certificate_no"], $allotment_html_string);
			                	}

			                	if(strpos($allotment_html_string, '<span class="myclass mceNonEditable">{{Allotment - Number of shares paid}}</span>') !== false)
			                	{
			                		$allotment_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - Number of shares paid}}</span>', number_format($allotment_member_result[$g]["amount_paid"], 2), $allotment_html_string);
			                	}

								// if($q[0]["document_name"] == "DRIW-Allotment of Shares" && $g == 4 && $obj_pdf->getY() < 50)
								if($q[0]["document_name"] == "DRIW-Allotment of Shares" && $g == 3)
								{
									// break page and create new table tag so that they have 2 separate tables
									$latest_allotment_table = $latest_allotment_table . $allotment_html_string . '</tbody></table></td></tr></tbody></table>' . "{{break_page}} <br/><br/><br/>" . 
									'<table style="height: 292px; width: 100%; border-collapse: collapse;" border="0">
										<tbody>
										<tr>
										<td style="width: 6.49432%;"><span style="font-size: 10pt;"></span></td>
										<td style="width: 93.5057%;"><span style="font-size: 10pt;"></span>
										<table style="width: 100%; border-collapse: collapse;" border="0">
										<tbody>';
								}
								elseif($q[0]["document_name"] == "F24 - Return of allotment of shares" && $g == 1 && $g != count($allotment_member_result) - 1)
								{
									$latest_allotment_table = $latest_allotment_table  . $allotment_html_string . '</tbody></table>' . "{{break_page}} <br/><br/><br/>" . 
									'<table style="width: 100%; border-collapse: collapse;" border="1">
										<tbody>';
								}
								else
								{
									$obj_pdf->SetAutoPageBreak(TRUE, 25);

									$latest_allotment_table = $latest_allotment_table . $allotment_html_string;
								}

		                	}

		                	$new_contents = str_replace($abstract_string_array[0][0], $latest_allotment_table, $str);
		                }

		                if($q[0]["document_name"] == "F24 - Return of allotment of shares" && (count($allotment_member_result) < 3 || count($allotment_member_result) > 7)) 
		                {
		                	if(strpos($new_contents, '<p style="text-align: justify;"><strong>Summary of Capital</strong></p>') !== false)
				            {
				            	// echo "Summary";
				            	$new_contents = str_replace('<p style="text-align: justify;"><strong>Summary of Capital</strong></p>', '{{break_page}} <p style="text-align: justify;"><strong>Summary of Capital</strong></p>', $new_contents);
				            }
		                }

		                $pattern = "/{{[^}}]*}}/";
						$subject = $new_contents;
						preg_match_all($pattern, $subject, $matches);
						
						for($r = 0; $r < count($matches[0]); $r++)
						{
							$string1 = (str_replace('{{', '',$matches[0][$r]));
							$string2 = (str_replace('}}', '',$string1));
							
							$replace_string = $matches[0][$r];

							if($string2 == "Allotment - overall amount of share")
							{
								$temp = '______________';

								$overall_amount_paid = 0;

								for($amrc = 0; $amrc < count($allotment_member_result); $amrc++)
								{
									$overall_amount_paid += (float)$allotment_member_result[$amrc]["amount_paid"];
								}

								$temp = number_format($overall_amount_paid,2);

								$new_contents = str_replace($replace_string, $temp, $new_contents);
							}
						}

			            // echo $obj_pdf->getY() . '<br/>';

		                //echo ($new_contents);
		                $content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents, null, $q[0]["document_name"]);
		            }

					$content = $this->break_page($obj_pdf, $content);
		            $content = $this->end_of_resol_page_break($obj_pdf, $content);

					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
				else if($q[0]["document_name"] == "Ltr of Indemnity")
				{	

					$new_contents = '';

					$get_secretary_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4");

					if($get_secretary_info->num_rows())
					{
						$get_secretary_info = $get_secretary_info->result_array();
						
						for($t = 0 ; $t < count($get_secretary_info) ; $t++)
						{

							$obj_pdf = new NEWPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
							$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
							$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
							$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);
							//$obj_pdf->SetTopMargin(37);
							//$obj_pdf->setY(33);

							$str = $new_contents_info;
							$latest_directors_table = "";

							// echo $new_contents_info;

				            $get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_client_officers.position = 1");

							$get_directors_info = $get_directors_info->result_array();
							//echo json_encode(count($get_billing_info));
							if(strpos($str, '<tr class="loop"') !== false)
		                	{
			            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
			            		// echo json_encode($get_directors_info);
			                	for($g = 0; $g < count($get_directors_info); $g++)
			                	{
			                		
			            			$directors_html_string = $abstract_string_array[0][0];
			            			//echo json_encode($abstract_string_array[0][0]);
			                		if(strpos($directors_html_string, '<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>') !== false)
				                	{
			                				$directors_html_string = str_replace('<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>', $get_directors_info[$g]["name"], $directors_html_string);
			                		}


			                		$latest_directors_table = $latest_directors_table.'<br/><br/><br/><br/><br/><br/>&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;..<br/>'.$directors_html_string;
			                		
				            		
			                	}
			                	$new_contents = str_replace($abstract_string_array[0][0], $latest_directors_table, $str);
			                }
						
							$below_content = $new_contents;
							$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $below_content, $get_secretary_info[$t]["id"], $q[0]["document_name"]);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_secretary_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_secretary_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
		            		$data['created_by']=$this->session->userdata('user_id');

		            		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "First Director Resolutions (Many)")
				{
					//echo json_encode($new_contents_info);
					$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Document";
					$obj_pdf->SetTitle($title);
					// $obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs='<div style="font-family: Helvetica, Sans-Serif;text-align: center;position: fixed; bottom: 0; width: 100%;"><h5>'.$client_query[0]["company_name"].'</h5></div><hr><br/>', $tc=array(0,0,0), $lc=array(0,0,0));
					$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs='<div style="font-family: Helvetica, Sans-Serif;text-align: center;position: fixed; margin: 20px; width: 100%; font-weight: bold; font-size: 17px;">'.$client_query[0]["company_name"].'</div><br/><hr>', $tc=array(0,0,0), $lc=array(0,0,0));

					$obj_pdf->setPrintFooter(false);
					$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
					$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();
					$obj_pdf->SetTopMargin(25);
					//$obj_pdf->setY(38);

					// $new_contents = $this->replaceToggle($matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, null, $q[0]["document_name"]);

					$director_str = $new_contents_info;
					$latest_directors_table = "";

		            $get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1");

					$get_directors_info = $get_directors_info->result_array();
					//echo json_encode(count($get_billing_info));
					if(strpos($director_str, '<tr class="loop director"') !== false)
                	{
	            		preg_match_all ('/<tr class="loop director"(.+?)<\/tr>/s', $director_str, $abstract_string_array);
	            	
	                	for($k = 0; $k < count($get_directors_info); $k++)
	                	{
	                		
	            			$directors_html_string = $abstract_string_array[0][0];
	            			//echo json_encode($abstract_string_array[0][0]);
	                		if(strpos($directors_html_string, '<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>') !== false)
		                	{
	                			$directors_html_string = str_replace('<span class="myclass mceNonEditable">{{Directors name - appointment}}</span>', $get_directors_info[$k]["name"], $directors_html_string);
	                		}

	                		if(strpos($directors_html_string, '<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>') !== false)
		                	{
	                			$directors_html_string = str_replace('<span class="myclass mceNonEditable">{{Directors ID - appointment}}</span>', $get_directors_info[$k]["identification_no"], $directors_html_string);
	                		}


	                		$latest_directors_table = $latest_directors_table.$directors_html_string;
	                		
		            		
	                	}
	                	$new_director_contents = str_replace($abstract_string_array[0][0], $latest_directors_table, $director_str);
	                }

					$member_str = $new_director_contents;
					$latest_member_table = "";

		            $get_member_info = $this->db->query("select transaction_member_shares.*, officer.name, officer_company.company_name, client.company_name as client_company_name, officer.identification_no, officer_company.register_no, client.registration_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_page_id='".$transaction_master_id."'");

					$get_member_info = $get_member_info->result_array();
					// echo json_encode($get_member_info);
					if(strpos($member_str, '<tr class="loop allotment"') !== false)
                	{
	            		preg_match_all ('/<tr class="loop allotment"(.+?)<\/tr>/s', $member_str, $abstract_string_array);
	            	
	                	for($g = 0; $g < count($get_member_info); $g++)
	                	{

	            			$member_html_string = $abstract_string_array[0][0];
	            			// echo json_encode($member_html_string);
	                		if(strpos($member_html_string, '<span class="myclass mceNonEditable">{{Allotment - members}}</span>') !== false)
		                	{
	                				if($get_member_info[$g]["name"] != null)
									{
										$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $get_member_info[$g]["name"], $member_html_string);
									}
									else if($get_member_info[$g]["company_name"] != null)
									{
										$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $get_member_info[$g]["company_name"], $member_html_string);
									}
									else if($get_member_info[$g]["client_company_name"] != null)
									{
										$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members}}</span>', $get_member_info[$g]["client_company_name"], $member_html_string);
									}
	                		}

	                		if(strpos($member_html_string, '<span class="myclass mceNonEditable">{{Allotment - members ID}}</span>') !== false)
	                		{
	                			if($get_member_info[$g]["identification_no"] != null)
								{
									$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members ID}}</span>', $get_member_info[$g]["identification_no"], $member_html_string);
								}
								else if($get_member_info[$g]["register_no"] != null)
								{
									$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members ID}}</span>', $get_member_info[$g]["register_no"], $member_html_string);
								}
								else if($get_member_info[$g]["registration_no"] != null)
								{
									$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - members ID}}</span>', $get_member_info[$g]["registration_no"], $member_html_string);
								}

								//echo json_encode($member_html_string);
	                		}

	                		if(strpos($member_html_string, '<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>') !== false)
	                		{

	                			$member_html_string = str_replace('<span class="myclass mceNonEditable">{{Allotment - number of shares}}</span>', number_format($get_member_info[$g]["number_of_share"]).'<br/>('.strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_info[$g]["number_of_share"])).')', $member_html_string);
	                		}

	                		$latest_member_table = $latest_member_table.$member_html_string;
	                		
		            		
	                	}
	                	$new_member_contents = str_replace($abstract_string_array[0][0], $latest_member_table, $member_str);
	                }
				
					$other_content = $new_member_contents;

					$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $other_content, null, $q[0]["document_name"]);

					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
				else if($q[0]["document_name"] == "First Director Resolution - ATTENDANCE SHEET")
				{
					$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Document";
					$obj_pdf->SetTitle($title);
					$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs='<div style="font-family: Helvetica, Sans-Serif;text-align: center;position: fixed; margin: 20px; width: 100%; font-weight: bold; font-size: 17px;">'.$client_query[0]["company_name"].'</div><br/><hr>', $tc=array(0,0,0), $lc=array(0,0,0));

					$obj_pdf->setPrintFooter(false);
					$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
					$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
					$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
					$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
					$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();
					$obj_pdf->SetTopMargin(25);
					//$obj_pdf->SetTopMargin(37);
					//$obj_pdf->setY(33);

					$str = $new_contents_info;
					$latest_directors_table = "";

		            // $get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1");
		            $get_member_info = $this->db->query('select transaction_member_shares.*, transaction_client.company_name as tr_client_company_name, sum(transaction_member_shares.number_of_share) as number_of_share, sum(transaction_member_shares.amount_share) as amount_share, sum(transaction_member_shares.no_of_share_paid) as no_of_share_paid, sum(transaction_member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, client.registration_no as client_registration_no, client.company_name as client_company_name from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join transaction_client on transaction_client.company_code = transaction_member_shares.company_code  where transaction_member_shares.company_code="'.$company_code.'" GROUP BY transaction_member_shares.field_type, transaction_member_shares.officer_id,transaction_member_shares.client_member_share_capital_id HAVING sum(transaction_member_shares.number_of_share) != 0');

					$get_member_info = $get_member_info->result_array();
					//echo json_encode($get_member_info);
					if(strpos($str, '<tr class="loop"') !== false)
                	{
	            		preg_match_all ('/<tr class="loop"(.+?)<\/tr>/s', $str, $abstract_string_array);
	            	
	                	for($g = 0; $g < count($get_member_info); $g++)
	                	{
	                		$directors_html_string_corp_rep = "";
	            			$directors_html_string = $abstract_string_array[0][0];
	            			//echo json_encode($abstract_string_array[0][0]);
	                		if(strpos($directors_html_string, '<span class="myclass mceNonEditable">{{Members name - all}}</span>') !== false)
		                	{
                				if($get_member_info[$g]["name"] != null)
		                		{
		                			$directors_html_string = str_replace('<span class="myclass mceNonEditable">{{Members name - all}}</span>', $get_member_info[$g]["name"], $directors_html_string);
		                		}
		                		elseif($get_member_info[$g]["company_name"] != null)
		                		{
		                			//echo json_encode($get_member_info[$g]["name_of_corp_rep"]);
		                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$get_member_info[$g]["register_no"].'" and corporate_representative.subsidiary_name = "'.$get_member_info[$g]["tr_client_company_name"].'"');

		                			$get_corp_rep_info = $get_corp_rep_info->result_array();

		                			for($b = 0; $b < count($get_corp_rep_info); $b++)
	                				{
	                					$directors_html_string_corp_rep = $directors_html_string_corp_rep.str_replace('<span class="myclass mceNonEditable">{{Members name - all}}</span>', '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$get_member_info[$g]["company_name"].')</span>', $directors_html_string);
	                					
	                				}

	                				$directors_html_string = $directors_html_string_corp_rep;

		                		}
		                		elseif($get_member_info[$g]["client_company_name"] != null)
		                		{
		                			//echo json_encode($get_member_info[$g]["name_of_corp_rep"]);
		                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$get_member_info[$g]["client_registration_no"].'" and corporate_representative.subsidiary_name = "'.$get_member_info[$g]["tr_client_company_name"].'"');

		                			$get_corp_rep_info = $get_corp_rep_info->result_array();
		                			//echo json_encode($get_corp_rep_info);
		                			for($b = 0; $b < count($get_corp_rep_info); $b++)
	                				{
	                					$directors_html_string_corp_rep = $directors_html_string_corp_rep.str_replace('<span class="myclass mceNonEditable">{{Members name - all}}</span>', '<span>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'</span><br/><span>(Corporate Representative of '.$get_member_info[$g]["client_company_name"].')</span>', $directors_html_string);
	                				}

	                				$directors_html_string = $directors_html_string_corp_rep;

		                		}
	                		}

	                		//print($latest_directors_table);
	                		$latest_directors_table = $latest_directors_table.$directors_html_string;
	                		
		            		//print($latest_directors_table);
	                	}
	                	//echo json_encode($latest_directors_table);
	                	$new_contents = str_replace($abstract_string_array[0][0], $latest_directors_table, $str);

	                }
				
					//$content = $new_contents;

					$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents, null, $q[0]["document_name"]);
					//print($content);
					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
					
				}
				else if($q[0]["document_name"] == "First Director Resolutions (One)")
				{
					$get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1");
					//echo json_encode($get_directors_info->num_rows());
					if($get_directors_info->num_rows())
					{
						$get_directors_info = $get_directors_info->result_array();
						
						for($t = 0 ; $t < count($get_directors_info) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw='', $ht='', $hs='<div style="font-family: Helvetica, Sans-Serif;text-align: center;position: fixed; margin: 20px; width: 100%; font-weight: bold; font-size: 17px;">'.$client_query[0]["company_name"].'</div><br/><hr>', $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(25, PDF_MARGIN_TOP, 25);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_directors_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_directors_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
		            		$data['created_by']=$this->session->userdata('user_id');

		            		$this->save_incorporate_pdf($data);
						}
					}
					
				}
				else if($q[0]["document_name"] == "DRIW-Appt of Director")
				{
					$get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 1");
					//echo json_encode($get_directors_info->num_rows());
					if($get_directors_info->num_rows())
					{
						$get_directors_info = $get_directors_info->result_array();
						
						for($t = 0 ; $t < count($get_directors_info) ; $t++)
						{
							$header_content = $this->get_header_template("DRIW");

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_directors_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_directors_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW - Resignation of Director" || $q[0]["document_name"] == "Ltr - Resignation of Director")
				{
					$get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join transaction_resign_officer_reason on transaction_resign_officer_reason.transaction_client_officers_id = transaction_client_officers.id where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_resign_officer_reason.is_resign = 1");
					
					if($get_directors_info->num_rows())
					{
						$get_directors_info = $get_directors_info->result_array();
						
						for($t = 0 ; $t < count($get_directors_info) ; $t++)
						{
							if($q[0]["document_name"] == "Ltr - Resignation of Director")
							{
								$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
								$obj_pdf->SetCreator(PDF_CREATOR);
								$title = "Document";
								$obj_pdf->SetTitle($title);
								$obj_pdf->setPrintHeader(false);
								$obj_pdf->setPrintFooter(false);
								$obj_pdf->SetDefaultMonospacedFont('helvetica');
								$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
								$obj_pdf->SetAutoPageBreak(TRUE, 0);
								$obj_pdf->SetFont('helvetica', '', 10);
								$obj_pdf->setFontSubsetting(false);

								$obj_pdf->AddPage();
								$obj_pdf->setListIndentWidth(4);

								$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_directors_info[$t]["id"], $q[0]["document_name"]);

								$content = $new_contents;
								$content = $this->end_of_resol_page_break($obj_pdf, $content);

								$obj_pdf->writeHTML($content, true, false, false, false, '');

								$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf', 'F');

								if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf'))
				    			{
							        echo "File Doesn't Exist...";exit;
							    }

								$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf');

								$data['transaction_id'] = $transaction_master_id;
								$data['type'] = "trans"; 
								$data['client_id'] = $client_query[0]["id"];
								$data['firm_id'] = $q[0]["firm_id"];
								$data['document_name'] = $q[0]["document_name"].' - '.$get_directors_info[$t]["name"];
								$data['triggered_by'] = 1;
								$data['document_date_checkbox'] = 1;
								$data['transaction_date'] = DATE("d/m/Y",now());
								$data['content'] = $content;
		                		$data['created_by']=$this->session->userdata('user_id');

		                		$this->save_incorporate_pdf($data);
							}
						}

						if($q[0]["document_name"] == "DRIW - Resignation of Director")
						{
							$header_content = $this->get_header_template("DRIW");
							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);
							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);
							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);

							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Directors name and IC")
								{
									// echo count($get_directors_info);
									if(count($get_directors_info) > 0)
									{
										for($count = 0 ; $count < count($get_directors_info) ; $count++)
										{
											$get_directors = $this->db->query("select officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$get_directors_info[$count]["id"]."'");
											$get_directors = $get_directors->result_array();

											$director_name_ic = $get_directors[0]["name"] . " (Identification No. " . $get_directors[0]["identification_no"] . ")";

											if($count == 0)
											{
												$temp = $director_name_ic;
											}
											elseif($count == count($get_directors_info) - 1)
											{
												$temp = $temp . " and " . $director_name_ic;
											}
											else
											{
												$temp = $temp . ", " . $director_name_ic;
											}
										}
									}

									$new_contents_info = str_replace($replace_string, $temp, $new_contents_info);

									$new_contents_info = $this->replace_verbs_plural($new_contents_info, count($get_directors_info));
								}
								elseif($string2 == "Appointment of Director")
								{
									// echo $company_code . '<br/>' . $transaction_master_id;
									$get_appt_director = $this->db->query("select transaction_client_officers.*, officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 1");

									// echo json_encode($get_appt_directors_info);

									if($get_appt_director->num_rows())
									{
										$get_appt_director = $get_appt_director->result_array();

										for($count = 0; $count < count($get_appt_director); $count++)
										{	
											$appt_director_name_ic = $get_appt_director[$count]["name"] . " (Identification No. " . $get_appt_director[$count]["identification_no"] . ")";

											if($count == 0)
											{
												$temp = $appt_director_name_ic;
											}
											elseif($count == count($get_appt_director) - 1)
											{
												$temp = $temp . " and " . $appt_director_name_ic;
											}
											else
											{
												$temp = $temp . ', ' . $appt_director_name_ic;
											}
										}

										$new_contents_info = str_replace('<td style="width: 100%; font-size: 10pt;" colspan="2">{{Appointment of Director}}</td>', '<td></td></tr><tr><td style="width: 5.23329%;"><span style="font-size: 10pt;">{{no}}</span></td><td style="width: 94.7667%;"><span style="font-size: 10pt;">That the appointment of ' . $temp . ' as Director of the Company be and is hereby accepted with effect from the date of her giving her consent.</span></td></tr><tr><td></td></tr>', $new_contents_info);
									}
									else
									{
										$new_contents_info = str_replace($replace_string, '', $new_contents_info);
									}
								}
							}
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, '', $q[0]["document_name"]);

							$content = $new_contents;
							$content = $this->write_list_number($content);
							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							// $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_appt_directors_info[$t]["name"]).'.pdf', 'F');

							// if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_appt_directors_info[$t]["name"]).'.pdf'))
			    // 			{
						 //        echo "File Doesn't Exist...";exit;
						 //    }

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_appt_directors_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_appt_directors_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
						
					}
					
				}
				else if($q[0]["document_name"] == "DRIW-Appt of Auditor" || $q[0]["document_name"] == "Auditor-Notice of EGM" || $q[0]["document_name"] == "Auditor-Minutes of EGM" || $q[0]["document_name"] == "DRIW - Resignation Of Auditor")
				{
					
					if($q[0]["document_name"] == "DRIW - Resignation Of Auditor")
					{
						$get_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_cessation !='' AND transaction_client_officers.position = 5");
					}
					else
					{
						$get_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_cessation='' AND transaction_client_officers.position = 5");
					}

					if($get_auditor_info->num_rows())
					{
						$get_auditor_info = $get_auditor_info->result_array();
						
						for($t = 0 ; $t < count($get_auditor_info) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);

							// $new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_auditor_info[$t]["id"], $q[0]["document_name"]);

							// $get_appt_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 5");

							// $get_appt_auditor_info = $get_appt_auditor_info->result_array();

					   		for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								$get_auditor = $this->db->query("select officer_company.register_no, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment = '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$get_auditor_info[$t]["id"]."'");

								$get_auditor = $get_auditor->result_array();

								if($string2 == "Auditors ID - appointment")
								{
									$content = $get_auditor[0]["register_no"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Auditors name - appointment")
								{
									$content = $get_auditor[0]["company_name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								
								
							}

							$get_resign_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment !='' AND transaction_client_officers.position = 5");

							if($get_resign_auditor_info->num_rows())
							{
								$get_resign_auditor_info = $get_resign_auditor_info->result_array();

								$new_resign_auditor_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_resign_auditor_info[0]["id"], $q[0]["document_name"]);

								//echo json_encode($get_resign_auditor_info[0]["id"]);
							}
							else
							{
								$new_hide_resign_auditor_contents = str_replace('<div class="resign_auditor">', '<div class="resign_auditor" style="display: none; margin-bottom: 0px;">', $new_contents_info);

								$new_resign_auditor_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_hide_resign_auditor_contents, $get_auditor[0]["id"], $q[0]["document_name"]);
							}

							$content = $new_resign_auditor_contents;
			
							

							//$content = $new_contents;

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_auditor_info[$t]["company_name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_auditor_info[$t]["company_name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_auditor_info[$t]["company_name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_auditor_info[$t]["company_name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW - Change of Auditor")
				{					
					$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$obj_pdf->SetCreator(PDF_CREATOR);
					$title = "Document";
					$obj_pdf->SetTitle($title);
					$obj_pdf->setPrintHeader(false);
					$obj_pdf->setPrintFooter(false);
					$obj_pdf->SetDefaultMonospacedFont('helvetica');
					$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					$obj_pdf->SetFont('helvetica', '', 10);
					$obj_pdf->setFontSubsetting(false);
					$obj_pdf->AddPage();

					$get_appt_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 5");

					$get_appt_auditor_info = $get_appt_auditor_info->result_array();

			   		for($r = 0; $r < count($matches[0]); $r++)
					{
						$string1 = (str_replace('{{', '',$matches[0][$r]));
						$string2 = (str_replace('}}', '',$string1));
						
						$replace_string = $matches[0][$r];

						$get_auditor = $this->db->query("select officer_company.register_no, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment = '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$get_appt_auditor_info[0]["id"]."'");

						$get_auditor = $get_auditor->result_array();

						if($string2 == "Auditors ID - appointment")
						{
							$content = $get_auditor[0]["register_no"];

							$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
						}
						elseif($string2 == "Auditors name - appointment")
						{
							$content = $get_auditor[0]["company_name"];

							$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
						}
						
						
					}

					$get_resign_auditor_info = $this->db->query("select transaction_client_officers.*, officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment !='' AND transaction_client_officers.position = 5");

					if($get_resign_auditor_info->num_rows())
					{
						$get_resign_auditor_info = $get_resign_auditor_info->result_array();

						$new_resign_auditor_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_resign_auditor_info[0]["id"], $q[0]["document_name"]);

						//echo json_encode($get_resign_auditor_info[0]["id"]);
					}

					$content = $new_resign_auditor_contents;

					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
				else if($q[0]["document_name"] == "DRIW-Change of FYE")
				{
					$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);
					//echo json_encode($get_directors_info->num_rows());
					if($get_transaction_change_fye_info->num_rows())
					{
						$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();
						
						for($t = 0 ; $t < count($get_transaction_change_fye_info) ; $t++)
						{
							$header_content = $this->get_header_template("DRIW");

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$content = "______________";
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Year end old")
								{
									if(!empty($get_transaction_change_fye_info[0]["old_year_end"]))
									{
										$content 		   = $get_transaction_change_fye_info[0]["old_year_end"];
										$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
									}
								}
								// elseif($string2 == "new_year_end_month")
								// {
								// 	if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
								// 	{
								// 		$content = date('d F', strtotime($get_transaction_change_fye_info[0]["new_year_end"]));
								// 	}
								// }
							}
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_transaction_change_fye_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_fye_info[$t]["company_name"].'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_fye_info[$t]["company_name"].'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_fye_info[$t]["company_name"].'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_transaction_change_fye_info[$t]["company_name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW-Change Biz Activity")
				{
					$get_transaction_change_biz_activity_info = $this->db->query("SELECT * FROM transaction_change_biz_activity WHERE transaction_id=". $transaction_master_id);
					//echo json_encode($get_directors_info->num_rows());
					if($get_transaction_change_biz_activity_info->num_rows())
					{
						$get_transaction_change_biz_activity_info = $get_transaction_change_biz_activity_info->result_array();
						
						for($t = 0 ; $t < count($get_transaction_change_biz_activity_info) ; $t++)
						{
							$header_content = $this->get_header_template("DRIW");

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Changes in principal activities")
								{
									$content = '';
									// if activity 1 is changed
									if(!empty($get_transaction_change_biz_activity_info[0]["activity1"]) && !($get_transaction_change_biz_activity_info[0]["activity1"] == $get_transaction_change_biz_activity_info[0]["old_activity1"])){
										$content .= "Primary Activity (1): " . $get_transaction_change_biz_activity_info[0]["old_activity1"];

										$content.= ' to "' . $get_transaction_change_biz_activity_info[0]["activity1"] . '" <br>';
									}

									// if activity 2 is removed 
									if($get_transaction_change_biz_activity_info[0]["remove_activity_2"]){
										if(!empty($get_transaction_change_biz_activity_info[0]["old_activity2"])){
											$content.= "Secondary Activity (2): " . $get_transaction_change_biz_activity_info[0]["old_activity2"] . " is removed.";
										}
									}
									elseif(!empty($get_transaction_change_biz_activity_info[0]["activity2"])){
										if(!empty($get_transaction_change_biz_activity_info[0]["old_activity2"]) && !($get_transaction_change_biz_activity_info[0]["old_activity2"] == $get_transaction_change_biz_activity_info[0]["activity2"])){
											$content.= "Secondary Activity (2): " . $get_transaction_change_biz_activity_info[0]["old_activity2"] . ' to "' . $get_transaction_change_biz_activity_info[0]["activity2"] . '"';
										}
										else if(!($get_transaction_change_biz_activity_info[0]["old_activity2"] == $get_transaction_change_biz_activity_info[0]["activity2"])){
											$content.= "Secondary Activity (2): " . $get_transaction_change_biz_activity_info[0]["activity2"] . " is added.";
										}
									}
									
									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Principal activity 1 - new")
								{
									if(!empty($get_transaction_change_biz_activity_info[0]["activity1"])){
										$content = 'Primary Activity (1): ' . $get_transaction_change_biz_activity_info[0]["activity1"];
									}
									elseif(!empty($get_transaction_change_biz_activity_info[0]["old_activity1"])){
										$content = 'Primary Activity (1): ' . $get_transaction_change_biz_activity_info[0]["old_activity1"];
									}else
									{	
										$content = '';
									}

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Principal activity 2 - new")
								{
									if(!empty($get_transaction_change_biz_activity_info[0]["activity2"])){
										$content = "Secondary Activity (2): " . $get_transaction_change_biz_activity_info[0]["activity2"];
									}
									elseif(!$get_transaction_change_biz_activity_info[0]["remove_activity_2"] && !empty($get_transaction_change_biz_activity_info[0]["old_activity2"])){
										$content = "Secondary Activity (2): " . $get_transaction_change_biz_activity_info[0]["old_activity2"];
									}else
									{	
										$content = '';
									}

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								
							}
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_transaction_change_biz_activity_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_biz_activity_info[$t]["company_name"].'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_biz_activity_info[$t]["company_name"].'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_change_biz_activity_info[$t]["company_name"].'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_transaction_change_biz_activity_info[$t]["company_name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW-Issue Director Fee & EGM" || $q[0]["document_name"] == "Director Fee-Notice of EGM" || $q[0]["document_name"] == "Director Fee-Minutes of EGM" || $q[0]["document_name"] == "Director Fee-Attendance List")
				{
					// print_r(json_encode($q[0]));
					$transaction_issue_director_fee = $this->db->query("SELECT transaction_issue_director_fee.*, client.company_name FROM `transaction_issue_director_fee`JOIN client ON client.registration_no = transaction_issue_director_fee.registration_no WHERE transaction_id =". $transaction_master_id . " AND client.deleted != 1");

					//echo json_encode($get_directors_info->num_rows());
					if($transaction_issue_director_fee->num_rows())
					{
						$transaction_issue_director_fee = $transaction_issue_director_fee->result_array();

						for($t = 0 ; $t < count($transaction_issue_director_fee) ; $t++)
						{	
							if(strpos($q[0]["document_name"], "DRIW") !== false)
							{
								$header_content = $this->get_header_template("DRIW");
							}
							elseif(strpos($q[0]["document_name"], "Attendance") !== false)
							{
								$header_content = $this->get_header_template("Attendance");
							}
							else
							{
								$header_content = $this->get_header_template("headerOnly");
							}

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);

							if(strpos($q[0]["document_name"], "DRIW") !== false)
							{
								$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
								$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
								$obj_pdf->SetAutoPageBreak(TRUE, 30);
							}
							elseif(strpos($q[0]["document_name"], "Minutes") !== false || $q[0]["document_name"] == "Director Fee-Notice of EGM")
							{	
								$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+22);
								$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP + 32, PDF_MARGIN_RIGHT);
								$obj_pdf->SetAutoPageBreak(TRUE, 20);
							}
							else
							{
								$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+22);
								$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
								$obj_pdf->SetAutoPageBreak(TRUE, 30);
							}

							$obj_pdf->setListIndentWidth(4);
							$obj_pdf->AddPage();

							$director_fee_list = $this->db->query("SELECT * FROM transaction_director_fee_list WHERE transaction_issue_director_fee_id=". $transaction_issue_director_fee[0]["id"]);

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Director Fee - table of director and fee")
								{	
									// print_r(json_encode($director_fee_list->result_array()));
									if($director_fee_list->num_rows())
									{
										$content = '<table style="width: 100%; border-collapse: collapse; font-size: 10pt;" border="0"><tbody><tr style="height: 17px;"><td style="width: 70%; height: 17px;"><span style="text-decoration: underline;"><strong>Director</strong></span></td><td style="width: 30%; height: 17px;"><span style="text-decoration: underline;"><strong>Amounts</strong></span></td></tr>';

										foreach($director_fee_list->result_array() as $row){
											// print_r(json_encode($row["director_fee"]));
											$currency_name = $this->db->query("SELECT currency FROM currency WHERE id='". $row["currency"] ."'");
											$currency_name = $currency_name->result_array()[0]["currency"];

											if(number_format($row["director_fee"],2) != 0.00)
											{
												$content .= '<tr style="height: 17px;">
															<td style="width: 70%;">'. $row["director_name"] .'</td>
															<td style="width: 30%;">'. $currency_name . ' ' . number_format($row["director_fee"],2) .'/-</td>
															</tr>';
											}
										}

										$content .= '</tr></tbody></table>';

									}

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Director Fee - declare of financial year end")
								{
									$content = !empty($transaction_issue_director_fee[0]["declare_of_fye"])?date('d F Y', strtotime(str_replace('/', '-', $transaction_issue_director_fee[0]["declare_of_fye"]))): '';

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Director Fee - resolution date")
								{
									$content = !empty($transaction_issue_director_fee[0]["resolution_date"])?date('d F Y', strtotime(str_replace('/', '-', $transaction_issue_director_fee[0]["resolution_date"]))): '';

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Director Fee - notice date")
								{
									$content = !empty($transaction_issue_director_fee[0]["notice_date"])?date('d F Y', strtotime(str_replace('/', '-', $transaction_issue_director_fee[0]["notice_date"]))): '';

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Director Fee - meeting date")
								{
									$content = !empty($transaction_issue_director_fee[0]["meeting_date"])?date('d F Y', strtotime(str_replace('/', '-', $transaction_issue_director_fee[0]["meeting_date"]))): '';

									if($content == '' && ($q[0]["document_name"] == "Director Fee-Notice of EGM" || $q[0]["document_name"] == "DRIW-Issue Director Fee & EGM"))
									{
										$content = '______________';
									}

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								
							}
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $transaction_issue_director_fee[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;
							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$transaction_issue_director_fee[$t]["company_name"].'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$transaction_issue_director_fee[$t]["company_name"].'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$transaction_issue_director_fee[$t]["company_name"].'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$transaction_issue_director_fee[$t]["company_name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW-Incorp of subsidiary" || $q[0]["document_name"] == "Subsidiary-Cert of Appt Company Representative")
				{
					$get_transaction_incorp_subsidiary = $this->db->query("SELECT * FROM transaction_corporate_representative WHERE transaction_id=". $transaction_master_id);

					if($get_transaction_incorp_subsidiary->num_rows())
					{
						$get_transaction_incorp_subsidiary = $get_transaction_incorp_subsidiary->result_array();

						$currency_name = $this->db->query("SELECT currency FROM currency WHERE id='". $get_transaction_incorp_subsidiary[0]["currency"] ."'");
						$currency_name = $currency_name->result_array()[0]["currency"];
						
						for($t = 0 ; $t < count($get_transaction_incorp_subsidiary) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Subsidiary - company name")
								{
									$content = $get_transaction_incorp_subsidiary[0]["subsidiary_name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - company country")
								{
									$content = $get_transaction_incorp_subsidiary[0]["country_of_incorporation"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - currency of investment")
								{
									$content = $currency_name;

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - total investment capital")
								{
									$content = number_format($get_transaction_incorp_subsidiary[0]["total_investment_amount"], 2);

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - name of corporate representative")
								{
									$content = $get_transaction_incorp_subsidiary[0]["name_of_corp_rep"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - identification no. of corporate representative")
								{
									$content = $get_transaction_incorp_subsidiary[0]["identity_number"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Subsidiary - propose effective date")
								{
									$content = date('d F Y', strtotime($get_transaction_incorp_subsidiary[0]["propose_effective_date"]));

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								
							}
			
							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_transaction_incorp_subsidiary[$t]["id"], $q[0]["document_name"]);

							// $new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, 0, $q[0]["document_name"])

							$content = $new_contents;

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_incorp_subsidiary[$t]["subsidiary_name"].'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_incorp_subsidiary[$t]["subsidiary_name"].'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$get_transaction_incorp_subsidiary[$t]["subsidiary_name"].'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_transaction_incorp_subsidiary[$t]["subsidiary_name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				elseif($q[0]["document_name"] == "DRIW - Appt of Co Sec" || $q[0]["document_name"] == "DRIW-Appt of Co Sec (Take Over)")
				{
					$get_directors_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment='' AND transaction_client_officers.position = 4");

					if($get_directors_info->num_rows())
					{
						$get_directors_info = $get_directors_info->result_array();
						
						for($t = 0 ; $t < count($get_directors_info) ; $t++)
						{
							$header_content = $this->get_header_template("DRIW");

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);
							
							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Subsidiary - company name")
								{
									$content = $get_transaction_incorp_subsidiary[0]["subsidiary_name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_directors_info[$t]["id"], $q[0]["document_name"]);

							$content = $new_contents;

							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $get_directors_info[$t]["name"]).'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$get_directors_info[$t]["name"];
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW-Dividends" || $q[0]["document_name"] == "Dividends-Notice Of EGM" || $q[0]["document_name"] == "Dividends-Minutes Of EGM" || $q[0]["document_name"] == "Dividends-Attendance List" || $q[0]["document_name"] == "Dividend voucher")
				{
					if($q[0]["document_name"] == "DRIW-Dividends")
					{
						$header_content = $this->get_header_template("DRIW");

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
						$obj_pdf->SetAutoPageBreak(TRUE, 30);
						// $obj_pdf->AddPage();
						$obj_pdf->setListIndentWidth(4);
					}else{
						$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setPrintHeader(false);
						$obj_pdf->setPrintFooter(false);
						$obj_pdf->SetDefaultMonospacedFont('helvetica');
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$obj_pdf->SetFont('helvetica', '', 10);
						$obj_pdf->setFontSubsetting(false);
					}

					$get_dividends_info = $this->db->query("SELECT * FROM transaction_issue_dividend WHERE transaction_id=". $transaction_master_id);
					$get_dividends_info = $get_dividends_info->result_array();

					$currency_name = $this->db->query("SELECT currency FROM currency WHERE id='". $get_dividends_info[0]["currency"] ."'");
					$currency_name = $currency_name->result_array()[0]["currency"];

					$nature_name = $this->db->query("SELECT nature_name FROM nature where id=". $get_dividends_info[0]["nature"]);
					$nature_name = $nature_name->result_array()[0]["nature_name"];

					if($q[0]["document_name"] == "Dividend voucher"){
						$get_dividend_info_individuals = $this->db->query("SELECT * FROM transaction_dividend_list WHERE transaction_issue_dividend_id=". $get_dividends_info[0]["id"]);
						$get_dividend_info_individuals = $get_dividend_info_individuals->result_array();

						foreach($get_dividend_info_individuals as $individual)
						{
							$template_content = $new_contents_info;
							
							$obj_pdf->AddPage();

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Dividend - Nature (UPPERCASE)")
								{
									$content = strtoupper($nature_name);

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Nature (Lowercase)")
								{
									$content = $nature_name;

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Currency")
								{
									$content = $currency_name;

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Total dividend amount")
								{
									$content = number_format($get_dividends_info[0]["total_dividend_amount"], 2);

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Declare of financial year end")
								{
									$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["declare_of_fye"])));

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Cut off date")
								{
									$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["devidend_of_cut_off_date"])));

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Payment date")
								{
									$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["devidend_payment_date"])));

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Amount of value per share"){
									$content = $get_dividends_info[0]["devidend_per_share"];

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Shareholder name"){
									$content = $individual["shareholder_name"];

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Shareholder address")
								{
									$individual_address = '';

									// get individual address
									if ($individual["field_type"] == "individual")
									{
										$individual_unit = '';
										$individual_building_name_1 = '';

										$individual_info = $this->db->query("SELECT * FROM officer WHERE id ='". $individual["officer_id"] . "'");
										$individual_info = $individual_info->result_array()[0];

										if($individual_info['address_type'] == "Local")
										{
											if($individual_info["unit_no1"] != "" || $individual_info["unit_no2"] != "")
											{
												$individual_unit = ' #'.$individual_info["unit_no1"] .' - '.$individual_info["unit_no2"];
											}
											else
											{
												$individual_unit = "";
											}

											if($individual_info["building_name1"] != "")
											{
												$individual_building_name_1 = $individual_info["building_name1"];
											}

											$individual_address = $individual_info["street_name1"].', <br/>'.$individual_unit.' '.$individual_building_name_1.', <br/> SINGAPORE '.$individual_info["postal_code1"];
										}
										else if($individual_info['address_type'] == "Foreign")
										{
											$individual_address = $individual_info["foreign_address1"].'<br/>'.$individual_info["foreign_address2"].'<br/>'.$individual_info["foreign_address3"];
										}
									}
									elseif($individual["field_type"] == "client")
									{
										$client_unit = '';
										$client_building_name_1 = '';

										$individual_info = $this->db->query("SELECT * FROM client WHERE id ='". $individual["officer_id"] . "' AND client.deleted != 1");
										$individual_info = $individual_info->result_array()[0];

										// print_r(json_encode($individual_info));

										// if($individual_info['address_type'] == "Local")
										// {
											if($individual_info["unit_no1"] != "" || $individual_info["unit_no2"] != "")
											{
												$client_unit = ' #'.$individual_info["unit_no1"] .' - '.$individual_info["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($individual_info["building_name"] != "")
											{
												$client_building_name_1 = $individual_info["building_name"];
											}

											$individual_address = $individual_info["street_name"].', <br/>'.$client_unit.' '.$client_building_name_1.', <br/> SINGAPORE '.$individual_info["postal_code"];
										// }
										// else if($individual_info['address_type'] == "Foreign")
										// {
										// 	$individual_address = $individual_info["foreign_address1"].'<br/>'.$individual_info["foreign_address2"].'<br/>'.$individual_info["foreign_address3"];
										// }
									}
									elseif($individual["field_type"] == "company")
									{
										$company_unit = '';
										$company_building_name_1 = '';

										$individual_info = $this->db->query("SELECT * FROM officer_company WHERE id ='". $individual["officer_id"] . "'");
										$individual_info = $individual_info->result_array()[0];

										if($individual_info['address_type'] == "Local")
										{
											if($individual_info["company_unit_no1"] != "" || $individual_info["company_unit_no2"] != "")
											{
												$company_unit = ' #'.$individual_info["company_unit_no1"] .' - '.$individual_info["company_unit_no2"];
											}
											else
											{
												$company_unit = "";
											}

											if($individual_info["company_building_name"] != "")
											{
												$company_building_name_1 = $individual_info["company_building_name"];
											}

											$individual_address = $individual_info["company_street_name"].', <br/>'.$company_unit.' '.$company_building_name_1.', <br/> SINGAPORE '.$individual_info["company_postal_code"];
										}
										else if($individual_info['address_type'] == "Foreign")
										{
											$individual_address = $individual_info["company_foreign_address1"].'<br/>'.$individual_info["company_foreign_address2"].'<br/>'.$individual_info["company_foreign_address3"];
										}
									}

									$content = $individual_address;

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Dividend no. (voucher)"){
									$content = $individual["payment_voucher_no"];

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Total of share (individual)"){
									$content = number_format($individual["number_of_share"], 2);

									$template_content = str_replace($replace_string, $content, $template_content);
								}
								elseif($string2 == "Dividend - Dividend paid (individual)"){
									$content = number_format($individual["devidend_paid"], 2);

									$template_content = str_replace($replace_string, $content, $template_content);
								}
							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $template_content, $get_dividends_info[0]["id"], $q[0]["document_name"]);

							$obj_pdf->writeHTML($new_contents, true, false, false, false, '');
						}
					}
					else{
						$obj_pdf->AddPage();

						for($r = 0; $r < count($matches[0]); $r++)
						{
							$string1 = (str_replace('{{', '',$matches[0][$r]));
							$string2 = (str_replace('}}', '',$string1));
							
							$replace_string = $matches[0][$r];

							if($string2 == "Dividend - Nature (UPPERCASE)")
							{
								$content = strtoupper($nature_name);

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Nature (Lowercase)")
							{
								$content = $nature_name;

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Currency")
							{
								$content = $currency_name;

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Total dividend amount")
							{
								$content = number_format($get_dividends_info[0]["total_dividend_amount"], 2);

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Declare of financial year end")
							{
								$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["declare_of_fye"])));

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Cut off date")
							{
								$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["devidend_of_cut_off_date"])));

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
							elseif($string2 == "Dividend - Payment date")
							{
								$content = date('d F Y', strtotime(str_replace('/', '-', $get_dividends_info[0]["devidend_payment_date"])));

								$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
						}

						$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_dividends_info[0]["id"], $q[0]["document_name"]);

						$content = $new_contents;

						$content = $this->end_of_resol_page_break($obj_pdf, $content);

						$obj_pdf->writeHTML($content, true, false, false, false, '');
            		}

            		$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
				else if($q[0]["document_name"] == "03 Declaration" || $q[0]["document_name"] == "03 Declaration (Letter)")
				{
					$get_transaction_strike_off = $this->db->query("SELECT * FROM transaction_strike_off WHERE transaction_id=". $transaction_master_id);

					if($get_transaction_strike_off->num_rows())
					{
						$get_transaction_strike_off = $get_transaction_strike_off->result_array();

						for($t = 0 ; $t < count($get_transaction_strike_off) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Strike Off";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							// $obj_pdf->AddPage();

							$get_directors = $this->db->query("select officer.* from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");
							$get_directors = $get_directors->result_array();

							foreach($get_directors as $director)
							{
								$obj_pdf->AddPage();
								$temp_template = $new_contents_info;
								// print_r(json_encode($director));

								for($r = 0; $r < count($matches[0]); $r++)
								{
									$string1 = (str_replace('{{', '',$matches[0][$r]));
									$string2 = (str_replace('}}', '',$string1));
									
									$replace_string = $matches[0][$r];

									if($string2 == "Strike off - Directors information")
									{	
										$content = '';
										$director_address = '';

										if($director['address_type'] == "Local")
										{
											if($director["unit_no1"] != "" || $director["unit_no2"] != "")
											{
												$client_unit = ' #'.$director["unit_no1"] .' - '.$director["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($director["building_name1"] != "")
											{
												$members_building_name_1 = $director["building_name1"];
											}

											$director_address = $director["street_name1"].', '.$client_unit.' '.$members_building_name_1.', SINGAPORE '.$director["postal_code1"];
										}
										else if($director['address_type'] == "Foreign")
										{
											$director_address = $director["foreign_address1"].'<br/>'.$director["foreign_address2"].'<br/>'.$director["foreign_address3"];
										}

										$content .= $director["name"] . " (Identification No.: " . $director["identification_no"] . ") of " . $director_address;

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Reason for application")
									{
										$get_client_incorporation_date = $this->db->query("select incorporation_date from client where company_code='".$company_code."' AND client.deleted != 1");

										$get_client_incorporation_date = $get_client_incorporation_date->result_array();

										if($get_transaction_strike_off[0]["reason_for_application_id"] == 1){
											$content = 'The Company has not commenced business since <span class="myclass mceNonEditable">'. date('d F Y', strtotime(str_replace('/', '-', $get_client_incorporation_date[0]["incorporation_date"]))) .'</span> (date of incorporation).';
										}else{
											$content = "The Company has ceased business since ______________ and does not intend to do any business in the future.";
										}

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Director's name"){
										$content = $director["name"];

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Director's address"){
										$content = '';
										$director_address = '';

										if($director['address_type'] == "Local")
										{
											if($director["unit_no1"] != "" || $director["unit_no2"] != "")
											{
												$client_unit = ' #'.$director["unit_no1"] .' - '.$director["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($director["building_name1"] != "")
											{
												$members_building_name_1 = $director["building_name1"];
											}

											$director_address = $director["street_name1"].',<br/>'.$client_unit.' '.$members_building_name_1.', <br/>SINGAPORE '.$director["postal_code1"];
										}
										else if($director['address_type'] == "Foreign")
										{
											$director_address = $director["foreign_address1"].'<br/>'.$director["foreign_address2"].'<br/>'.$director["foreign_address3"];
										}

										$content = $director_address;

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
								}

								$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $temp_template, $get_transaction_strike_off[$t]["id"], $q[0]["document_name"]);
								$content = $new_contents;
								$obj_pdf->writeHTML($content, true, false, false, false, '');
							}

							$company_name = $this->db->query("SELECT company_name FROM `client` WHERE company_code = '". $company_code ."' AND client.deleted != 1");
							$company_name = $company_name->result_array()[0]["company_name"];

							// $obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$company_name;
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "01 Letter of Authorisation" || $q[0]["document_name"] == "02 Letter to IRAS for Striking Off" || $q[0]["document_name"] == "04 DRIW-Strike-Off & EGM - Shareholder" || $q[0]["document_name"] == "04 Strike-Off-Notice Of EGM" || $q[0]["document_name"] == "04 Strike-Off-Minutes Of EGM" || $q[0]["document_name"] == "04 Strike-Off-Attendance List")
				{
					$get_transaction_strike_off = $this->db->query("SELECT * FROM transaction_strike_off WHERE transaction_id=". $transaction_master_id);

					if($get_transaction_strike_off->num_rows())
					{
						$get_transaction_strike_off = $get_transaction_strike_off->result_array();

						for($t = 0 ; $t < count($get_transaction_strike_off) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Strike Off";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							// $obj_pdf->AddPage();

							$get_directors = $this->db->query("select officer.* from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");
							$get_directors = $get_directors->result_array();

							foreach($get_directors as $director)
							{
								$obj_pdf->AddPage();
								$temp_template = $new_contents_info;
								// print_r(json_encode($director));

								for($r = 0; $r < count($matches[0]); $r++)
								{
									$string1 = (str_replace('{{', '',$matches[0][$r]));
									$string2 = (str_replace('}}', '',$string1));
									
									$replace_string = $matches[0][$r];

									if($string2 == "Strike off - Directors information")
									{	
										$content = '';
										$director_address = '';

										if($director['address_type'] == "Local")
										{
											if($director["unit_no1"] != "" || $director["unit_no2"] != "")
											{
												$client_unit = ' #'.$director["unit_no1"] .' - '.$director["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($director["building_name1"] != "")
											{
												$members_building_name_1 = $director["building_name1"];
											}

											$director_address = $director["street_name1"].', '.$client_unit.' '.$members_building_name_1.', SINGAPORE '.$director["postal_code1"];
										}
										else if($director['address_type'] == "Foreign")
										{
											$director_address = $director["foreign_address1"].'<br/>'.$director["foreign_address2"].'<br/>'.$director["foreign_address3"];
										}

										$content .= $director["name"] . " (Identification No.: " . $director["identification_no"] . ") of " . $director_address;

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Reason for application")
									{
										$get_client_incorporation_date = $this->db->query("select incorporation_date from client where company_code='".$company_code."' AND client.deleted != 1");

										$get_client_incorporation_date = $get_client_incorporation_date->result_array();

										if($get_transaction_strike_off[0]["reason_for_application_id"] == 1){
											$content = 'The Company has not commenced business since <span class="myclass mceNonEditable">'. date('d F Y', strtotime(str_replace('/', '-', $get_client_incorporation_date[0]["incorporation_date"]))) .'</span> (date of incorporation).';
										}else{
											$content = "The Company has ceased business since ______________ and does not intend to do any business in the future.";
										}

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Director's name"){
										$content = $director["name"];

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
									elseif($string2 == "Strike off - Director's address"){
										$content = '';
										$director_address = '';

										if($director['address_type'] == "Local")
										{
											if($director["unit_no1"] != "" || $director["unit_no2"] != "")
											{
												$client_unit = ' #'.$director["unit_no1"] .' - '.$director["unit_no2"];
											}
											else
											{
												$client_unit = "";
											}

											if($director["building_name1"] != "")
											{
												$members_building_name_1 = $director["building_name1"];
											}

											$director_address = $director["street_name1"].',<br/>'.$client_unit.' '.$members_building_name_1.', <br/>SINGAPORE '.$director["postal_code1"];
										}
										else if($director['address_type'] == "Foreign")
										{
											$director_address = $director["foreign_address1"].'<br/>'.$director["foreign_address2"].'<br/>'.$director["foreign_address3"];
										}

										$content = $director_address;

										$temp_template = str_replace($replace_string, $content, $temp_template);
									}
								}

								$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $temp_template, $get_transaction_strike_off[$t]["id"], $q[0]["document_name"]);
								$content = $new_contents;
								$obj_pdf->writeHTML($content, true, false, false, false, '');
							}

							$company_name = $this->db->query("SELECT company_name FROM `client` WHERE company_code = '". $company_code ."' AND client.deleted != 1");
							$company_name = $company_name->result_array()[0]["company_name"];

							// $obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$company_name;
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				elseif($q[0]["document_name"] == "Letter taking over of Secretarial Services")
				{
					$get_transaction_previous_secretarial = $this->db->query("SELECT * FROM transaction_previous_secretarial WHERE transaction_id=". $transaction_master_id);

					if($get_transaction_previous_secretarial->num_rows())
					{
						$get_transaction_previous_secretarial = $get_transaction_previous_secretarial->result_array();

						// echo json_encode($get_transaction_previous_secretarial);

						for($t = 0 ; $t < count($get_transaction_previous_secretarial) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Strike Off";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Company Previous Name")
								{	
									$content = $get_transaction_previous_secretarial[0]["company_name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Company Previous Address")
								{
									if($get_transaction_previous_secretarial[0]["unit_no1"] != "" || $get_transaction_previous_secretarial[0]["unit_no2"] != "")
									{
										$client_unit = ' #'.$get_transaction_previous_secretarial[0]["unit_no1"] .' - '.$get_transaction_previous_secretarial[0]["unit_no2"];
									}
									else
									{
										$client_unit = "";
									}

									if($get_transaction_previous_secretarial[0]["building_name"] != "")
									{
										$members_building_name_1 = $get_transaction_previous_secretarial[0]["building_name"];
									}

									$director_address = $get_transaction_previous_secretarial[0]["street_name"].',<br/>'.$client_unit.' '.$members_building_name_1.', <br/>SINGAPORE '.$get_transaction_previous_secretarial[0]["postal_code"];

									$new_contents_info = str_replace($replace_string, $director_address, $new_contents_info);
								}
								// elseif($string2 == "Directors - Authorized")
								// {
									// $get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.date_of_appointment =''");

									// $get_directors = $get_directors->result_array();
									// $director = '';
									// $count = count($get_directors);

									// if($count > 0)
									// {
									// 	foreach($get_directors as $index=>$director){
									// 		if($index == 0)
									// 		{
									// 			$director = $get_directors[$index]["name"];
									// 		}
									// 		else{
									// 			$director .= ' or ' . $get_directors[$index]["name"];
									// 		}
									// 	}
									// }

									// $new_contents_info = str_replace($replace_string, $director, $new_contents_info);

								// }
							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_transaction_previous_secretarial[0][$t]["id"], $q[0]["document_name"]);
							$content = $new_contents;
							// $obj_pdf->writeHTML($content, true, false, false, false, '');

							$company_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
							$company_name = $company_name->result_array()[0]["company_name"];

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$company_name;
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				elseif($q[0]["document_name"] == "Letter taking over of Secretarial Services")
				{
					$get_transaction_previous_secretarial = $this->db->query("SELECT * FROM transaction_previous_secretarial WHERE transaction_id=". $transaction_master_id);

					if($get_transaction_previous_secretarial->num_rows())
					{
						$get_transaction_previous_secretarial = $get_transaction_previous_secretarial->result_array();

						// echo json_encode($get_transaction_previous_secretarial);

						for($t = 0 ; $t < count($get_transaction_previous_secretarial) ; $t++)
						{
							$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Strike Off";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setPrintHeader(false);
							$obj_pdf->setPrintFooter(false);
							$obj_pdf->SetDefaultMonospacedFont('helvetica');
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
							$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
							$obj_pdf->SetFont('helvetica', '', 10);
							$obj_pdf->setFontSubsetting(false);
							$obj_pdf->AddPage();

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Company Previous Name")
								{	
									$content = $get_transaction_previous_secretarial[0]["company_name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Company Previous Address")
								{
									if($get_transaction_previous_secretarial[0]["unit_no1"] != "" || $get_transaction_previous_secretarial[0]["unit_no2"] != "")
									{
										$client_unit = ' #'.$get_transaction_previous_secretarial[0]["unit_no1"] .' - '.$get_transaction_previous_secretarial[0]["unit_no2"];
									}
									else
									{
										$client_unit = "";
									}

									if($get_transaction_previous_secretarial[0]["building_name"] != "")
									{
										$members_building_name_1 = $get_transaction_previous_secretarial[0]["building_name"];
									}

									$director_address = $get_transaction_previous_secretarial[0]["street_name"].',<br/>'.$client_unit.' '.$members_building_name_1.', <br/>SINGAPORE '.$get_transaction_previous_secretarial[0]["postal_code"];

									$new_contents_info = str_replace($replace_string, $director_address, $new_contents_info);
								}
							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_transaction_previous_secretarial[0][$t]["id"], $q[0]["document_name"]);
							$content = $new_contents;
							// $obj_pdf->writeHTML($content, true, false, false, false, '');

							$company_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
							$company_name = $company_name->result_array()[0]["company_name"];

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$company_name;
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				else if($q[0]["document_name"] == "DRIW - Resignation of Co Sec- Co (Take Over)")
				{
					$get_secretary_info = $this->db->query("select transaction_client_officers.*, officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 4");

					if($get_secretary_info->num_rows())
					{
						$get_secretary_info = $get_secretary_info->result_array();

						for($t = 0 ; $t < count($get_secretary_info) ; $t++)
						{
							$header_content = $this->get_header_template("DRIW");

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

							$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
							$obj_pdf->SetCreator(PDF_CREATOR);
							$title = "Document";
							$obj_pdf->SetTitle($title);
							$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
							$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
							$obj_pdf->SetAutoPageBreak(TRUE, 30);
							$obj_pdf->AddPage();
							$obj_pdf->setListIndentWidth(4);

							for($r = 0; $r < count($matches[0]); $r++)
							{
								$string1 = (str_replace('{{', '',$matches[0][$r]));
								$string2 = (str_replace('}}', '',$string1));
								
								$replace_string = $matches[0][$r];

								if($string2 == "Secretarys name - resigning")
								{	
									$content = $get_secretary_info[0]["name"];

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
								elseif($string2 == "Company Previous Name")
								{
									$content = '________________________________';

									$get_transaction_previous_secretarial = $this->db->query("SELECT * FROM transaction_previous_secretarial WHERE transaction_id=". $transaction_master_id);

									if($get_transaction_previous_secretarial->num_rows())
									{
										$get_transaction_previous_secretarial = $get_transaction_previous_secretarial->result_array();

										$content = $get_transaction_previous_secretarial[0]["company_name"];
									}

									$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
								}
							}

							$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, $get_secretary_info[0][$t]["id"], $q[0]["document_name"]);
							$content = $new_contents;

							$content = $this->end_of_resol_page_break($obj_pdf, $content);

							$company_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
							$company_name = $company_name->result_array()[0]["company_name"];

							$obj_pdf->writeHTML($content, true, false, false, false, '');

							$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf', 'F');

							if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf'))
			    			{
						        echo "File Doesn't Exist...";exit;
						    }

							$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$company_name.'.pdf');

							$data['transaction_id'] = $transaction_master_id;
							$data['type'] = "trans"; 
							$data['client_id'] = $client_query[0]["id"];
							$data['firm_id'] = $q[0]["firm_id"];
							$data['document_name'] = $q[0]["document_name"].' - '.$company_name;
							$data['triggered_by'] = 1;
							$data['document_date_checkbox'] = 1;
							$data['transaction_date'] = DATE("d/m/Y",now());
							$data['content'] = $content;
	                		$data['created_by']=$this->session->userdata('user_id');

	                		$this->save_incorporate_pdf($data);
						}
					}
				}
				elseif($q[0]["document_name"] == "Proposal")
				{
					$client_proposal_info = $this->db->query("SELECT transaction_master.*, transaction_service_proposal_info.*, transaction_client_contact_info.name AS `contact_person` FROM `transaction_master` 
															LEFT JOIN transaction_service_proposal_info ON transaction_service_proposal_info.transaction_id = transaction_master.id 
															LEFT JOIN transaction_client_contact_info ON transaction_client_contact_info.transaction_id = transaction_master.id
															WHERE transaction_master.id =" . $transaction_master_id);

					$client_proposal_info = $client_proposal_info->result_array();

					$service_engage_info = $this->db->query("SELECT * FROM transaction_service_proposal_service_info
															LEFT JOIN unit_pricing ON transaction_service_proposal_service_info.unit_pricing = unit_pricing.id
															LEFT JOIN our_service_info ON our_service_info.id = transaction_service_proposal_service_info.our_service_id
															LEFT JOIN currency ON currency.id = transaction_service_proposal_service_info.currency_id
															WHERE transaction_service_proposal_service_info.transaction_id =" . $transaction_master_id);

					if($service_engage_info->num_rows())
					{
						$service_engage_info_arr = $service_engage_info->result_array();

						// echo json_encode($service_engage_info_arr[0]["servicing_firm"]);
						// if($service_engage_info_arr[0]['servicing_firm'] != 0)	// set header if need
						// {
							// echo json_encode($service_engage_info_arr);
						$header_content = $this->get_header_template("Company Info Header", $q[0]["firm_id"]);

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);
						// }
						// else
						// {
						// 	$header_content = '';
						// }

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT+3);
						$obj_pdf->SetAutoPageBreak(TRUE, 28);
						$obj_pdf->AddPage();
						$obj_pdf->setListIndentWidth(4);

						for($r = 0; $r < count($matches[0]); $r++)
						{
							$string1 = (str_replace('{{', '',$matches[0][$r]));
							$string2 = (str_replace('}}', '',$string1));
							
							$replace_string = $matches[0][$r];
							$temp_content = '';

							if($string2 == "proposal date")
							{
								$temp_content = $client_proposal_info[0]['proposal_date'];

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "client company name")
							{
								$temp_content = $client_proposal_info[0]['client_name'];

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "client address")
							{
								$temp_content = $this->write_address($client_proposal_info[0]['street_name'], $client_proposal_info[0]['unit_no1'], $client_proposal_info[0]['unit_no2'], $client_proposal_info[0]['building_name'], $client_proposal_info[0]['postal_code'], "letter");

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "Director Name")
							{
								if($client_proposal_info[0]['client_type_id'] == 1)	// Existing Client
								{
									$director_1 = $this->db->query("select director_signature_1 from client_signing_info where company_code='". $client_proposal_info[0]['company_code'] ."'");
									$director_1 = $director_1->result_array();

									$client_officer = $this->db->query("select * from client_officers where id='".$director_1[0]["director_signature_1"]."'");
			                		$client_officer = $client_officer->result_array();

			                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");
			                		$officer_result = $officer_result->result_array();

									$temp_content = $officer_result[0]['name'];
								}
								else
								{
									$temp_content = $client_proposal_info[0]['contact_person'];
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "Scope of Engagement and details")
							{
								if($service_engage_info->num_rows())
								{	

									for($se_count = 0; $se_count < count($service_engage_info_arr); $se_count++)
									{
										$se_descript = $service_engage_info_arr[$se_count]["service_proposal_description"];

										$title = '<p style="line-height: 1.5; text-align:justify"><strong>' . $service_engage_info_arr[$se_count]["service_name"] . '<br/></strong>';
										$description = '<em>' . nl2br($service_engage_info_arr[$se_count]["service_proposal_description"]) . '</em></p>';

										$temp_content = $temp_content . $title . $description;
									}
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "Table of Proposed Fees")
							{
								if (($pos = strpos($new_contents_info, '<strong class="proposed_fees" style="font-size: 10pt;">')) !== FALSE) { 

									$proposal_content = substr($new_contents_info, 0, $pos); 

									$obj_pdf->writeHTML($proposal_content, true, false, false, false, '');

									while($obj_pdf->getY() > 240)
									{
										$obj_pdf->writeHTML('<p></p>', true, false, false, false, '');
									};
									
									if (($table_pos = strpos($new_contents_info, '<span style="font-size: 8pt;">{{Table of Proposed Fees}}</span>')) !== FALSE)
									{
										$proposed_title = substr($new_contents_info, $pos, $table_pos - $pos);
										$obj_pdf->writeHTML($proposed_title, true, false, false, false, '');
									} 
									
									// set table header
									$obj_pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(68,114,196)));
									// $obj_pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255,255,255)));
									$obj_pdf->SetFillColor(68,114,196);
									$obj_pdf->SetTextColor(255,255,255);
									$obj_pdf->SetFont('helvetica', 'B', 8);

									// $obj_pdf->setCellHeightRatio(0.8);

									$obj_pdf->MultiCell(95, 7, 'Scope of Engagement', 1, 'C', 1, 0);
									$obj_pdf->MultiCell(30, 7, 'Currency', 1, 'C', 1, 0);
									$obj_pdf->MultiCell(40, 7, 'Proposed Fees', 1, 'C', 1, 1);

									// set table the rest tr td
									// $obj_pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(68,114,196)));
									$obj_pdf->SetFillColor(255,255,255);
									$obj_pdf->SetTextColor(0,0,0);
									$obj_pdf->SetFont('helvetica', '', 8);

									// $obj_pdf->MultiCell($w=100, $h=7, $txt='Scope of Engagement', $border='TL', $align='C', $fill='', 1, $x='', $y='', $reseth='', $strech='', $ishtml=false, $autopadding='', $maxh='', $valign='');
									if($service_engage_info->num_rows())
									{
										for($se_count = 0; $se_count < count($service_engage_info_arr); $se_count++)
										{
											while($obj_pdf->getY() > 260)
											{
												$obj_pdf->writeHTML('<p></p>', true, false, false, false, '');
											};
											// if($obj_pdf->getY() > 260)
											// {
											// 	$obj_pdf->AddPage();
											// }

											// if($se_count != count($service_engage_info_arr) - 1)
											// {
											// 	$obj_pdf->MultiCell(95, 8, $service_engage_info_arr[$se_count]['service_name'] . "\n", 'T', 'L', 1, 0);
											// 	$obj_pdf->MultiCell(30, 8, $service_engage_info_arr[$se_count]["currency"] . "\n", 'T', 'C', 1, 0);
											// 	$obj_pdf->MultiCell(40, 8, number_format($service_engage_info_arr[$se_count]["fee"], 2) . "\nPER " . strtoupper($service_engage_info_arr[$se_count]["unit_pricing_name"]), 'T', 'R', 1, 1);
											// }
											// else
											// {
												$obj_pdf->MultiCell(95, 8, $service_engage_info_arr[$se_count]['service_name'] . "\n", 'TB', 'L', 1, 0);
												$obj_pdf->MultiCell(30, 8, $service_engage_info_arr[$se_count]["currency"] . "\n", 'TB', 'C', 1, 0);
												$obj_pdf->MultiCell(40, 8, number_format($service_engage_info_arr[$se_count]["fee"], 2) . "\nPER " . strtoupper($service_engage_info_arr[$se_count]["unit_pricing_name"]), 'TB', 'R', 1, 1);
											// }

											// $obj_pdf->MultiCell(95, 7, $service_engage_info_arr[$se_count]['service_name'] . "\n", 1, 'L', 1, 0);
											// $obj_pdf->MultiCell(30, 7, $service_engage_info_arr[$se_count]["currency"] . "\n", 1, 'C', 1, 0);
											// $obj_pdf->MultiCell(40, 7, number_format($service_engage_info_arr[$se_count]["fee"], 2), 1, 'R', 1, 1);
										}
									}

									$new_contents_info = '<p style="line-height: 1.5; text-align:justify;"><br/>' . substr($new_contents_info, $table_pos + strlen('<span style="font-size: 8pt;">{{Table of Proposed Fees}}</span>'));
								}

								$obj_pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0)));
							}
						}

						$content = $new_contents_info;

						if (($pos = strpos($content, '<p class="conclusion_start" style="line-height: 1.5;">')) !== FALSE) { 
							$top_content = substr($content, 0, $pos - 1); 

							$obj_pdf->writeHTML($top_content, true, false, false, false, '');

							// echo $obj_pdf->getY();
							$obj_pdf->SetAutoPageBreak(TRUE, 10);
							if($obj_pdf->getY() > 190)
							{
								$obj_pdf->AddPage();
							}

							$content = substr($content, $pos);
						}

						$obj_pdf->writeHTML($content, true, false, false, false, '');

						$obj_pdf->setY($obj_pdf->getY() - 45);

						$obj_pdf->writeHTML('<img src="img/woelly_signature.png" width="167" height="105" />', true, false, false, false, '');

						$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_proposal_info[0]['client_name'].'.pdf', 'F');

						if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_proposal_info[0]['client_name'].'.pdf'))
		    			{
					        echo "File Doesn't Exist...";exit;
					    }

						$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$client_proposal_info[0]['client_name'].'.pdf');

						$data['transaction_id'] = $transaction_master_id;
						$data['type'] = "trans"; 
						$data['client_id'] = $client_query[0]["id"];
						$data['firm_id'] = $q[0]["firm_id"];
						$data['document_name'] = $q[0]["document_name"].' - '.$client_proposal_info[0]['client_name'];
						$data['triggered_by'] = 1;
						$data['document_date_checkbox'] = 1;
						$data['transaction_date'] = DATE("d/m/Y",now());
						$data['content'] = $content;
	            		$data['created_by']=$this->session->userdata('user_id');

	            		$this->save_incorporate_pdf($data);
					}
				}
				elseif($q[0]["document_name"] == "Audit Engagement" || $q[0]["document_name"] == "Engagement letter - Corporate Tax" || $q[0]["document_name"] == "Engagement - Compilation")
				{	
					$client_engagement_info = $this->db->query("SELECT * FROM `transaction_engagement_letter_info` 
																LEFT JOIN transaction_master ON transaction_master.id = transaction_engagement_letter_info.transaction_master_id
																LEFT JOIN transaction_service_proposal_info ON transaction_service_proposal_info.transaction_id = transaction_master.id 
																LEFT JOIN transaction_engagement_letter_additional_info ON transaction_engagement_letter_additional_info.transaction_id = transaction_engagement_letter_info.transaction_id
																WHERE transaction_engagement_letter_info.transaction_id = " . $transaction_master_id . " AND transaction_engagement_letter_info.deleted=0");

					if($client_engagement_info->num_rows() > 0)
					{
						$client_engagement_info = $client_engagement_info->result_array();
					}
					else{
						// if(count($client_engagement_info) == 0) // this part will retrieve from proposal
						// {
							$client_engagement_info = $this->db->query("SELECT client.*, client.company_name AS `client_name`, transaction_engagement_letter_additional_info.uen, transaction_engagement_letter_additional_info.fye_date, transaction_engagement_letter_additional_info.director_signing FROM transaction_master 
																		LEFT JOIN client ON client.company_code = transaction_master.company_code AND client.deleted != 1
																		LEFT JOIN transaction_engagement_letter_additional_info ON transaction_engagement_letter_additional_info.transaction_id = transaction_master.id
																		WHERE transaction_master.id = " . $transaction_master_id);

							$client_engagement_info = $client_engagement_info->result_array();
						// }
					}
						
					if(count($client_engagement_info) > 0)
					{
						// $client_engagement_info = $client_engagement_info->result_array();

						// calculate fee depend on document type.
						if($q[0]["document_name"] == "Engagement - Compilation")
						{
							$engagement_letter_list_id = 1;
						}
						elseif($q[0]["document_name"] == "Engagement letter - Corporate Tax")
						{
							$engagement_letter_list_id = 2;
						}
						elseif($q[0]["document_name"] == "Audit Engagement")
						{
							$engagement_letter_list_id = 3;
						}

						$service_info_list = $this->db->query("SELECT transaction_engagement_letter_service_info.*, currency.currency, firm.name FROM transaction_engagement_letter_service_info 
																LEFT JOIN currency ON currency.id = transaction_engagement_letter_service_info.currency_id 
																LEFT JOIN firm ON firm.id = transaction_engagement_letter_service_info.servicing_firm
																WHERE transaction_engagement_letter_service_info.transaction_id =" . $transaction_master_id . " AND transaction_engagement_letter_service_info.engagement_letter_list_id=" . $engagement_letter_list_id);

						$service_info_list = $service_info_list->result_array();

						// echo $service_info_list[0]["servicing_firm"];
						// display or not for header
						if($service_info_list[0]["servicing_firm"] != 0)
						{
							$header_content = $this->get_header_template("Company Info Header", $service_info_list[0]["servicing_firm"]);

							$pattern = "/{{[^}}]*}}/";
							$subject = $header_content;
							preg_match_all($pattern, $subject, $header_tag_matches);

							$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);
						}
						else
						{
							$header_content = '';
						}
						
						$obj_pdf = new ENGAGEMENT_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Engagement";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));

						// if($q[0]["document_name"] == "Audit Engagement"){
						// 	$obj_pdf->setPrintHeader(false);
						// 	$obj_pdf->setPrintFooter(false);
						// }
						// else
						// {
							$obj_pdf->setPrintHeader(true);
							$obj_pdf->setPrintFooter(true);
						// }

						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);

						// set margins
						if($q[0]["document_name"] == "Audit Engagement")
						{
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+20, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT);
						}
						else
						{
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT+3);
						}
						
						$obj_pdf->SetAutoPageBreak(TRUE, 25);
						$obj_pdf->AddPage();
						$obj_pdf->setListIndentWidth(4);

						for($r = 0; $r < count($matches[0]); $r++)
						{
							$string1 = (str_replace('{{', '',$matches[0][$r]));
							$string2 = (str_replace('}}', '',$string1));
							
							$replace_string = $matches[0][$r];
							$temp_content = "______________";

							if($string2 == "FYE Date")
							{	
								if(!empty($client_engagement_info[0]['fye_date']))
								{
									$temp_content = date('d F Y', strtotime(str_replace('/', '-', $client_engagement_info[0]['fye_date'])));
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}

							if($string2 == "client company name")
							{
								if(!empty($client_engagement_info[0]['client_name']))
								{
									$temp_content = $client_engagement_info[0]['client_name'];
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "client address")
							{
								$temp_content = $this->write_address(ucwords(strtolower($client_engagement_info[0]['street_name'])), $client_engagement_info[0]['unit_no1'], $client_engagement_info[0]['unit_no2'], ucwords(strtolower($client_engagement_info[0]['building_name'])), $client_engagement_info[0]['postal_code'], "letter");

								if(empty($temp_content))
								{
									$temp_content = "______________________________________________________________";
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "client company uen")
							{	
								if(!empty($client_engagement_info[0]['registration_no']))
								{
									$temp_content = "(UEN: " . $client_engagement_info[0]['registration_no'] . ")";
								}
								else
								{
									$temp_content = '';
								}

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "Total Engagement Fees")
							{
								$total_engagement_fee = 0;

								if(count($service_info_list) > 0)
								{
									for($b = 0; $b < count($service_info_list); $b++)
									{
										// echo json_encode($service_info_list[$b]) . "	";
										$total_engagement_fee = $total_engagement_fee + $service_info_list[$b]["fee"];
										$temp_content 	   = number_format($total_engagement_fee, 2);
										$new_contents_info = str_replace($replace_string, $service_info_list[$b]["currency"] . $temp_content, $new_contents_info);
									}
								}
								else
								{
									// echo json_encode(count($service_info_list));
									$temp_content 	   = "______________";
									$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
								}
							}
							elseif($string2 == "Director Name")
							{
								$temp_content = $client_engagement_info[0]['director_signing'];

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "Engagement Letter Date")
							{
								$temp_content = $client_engagement_info[0]['engagement_letter_date'];

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
							elseif($string2 == "servicing firm name")
							{
								$temp_content = $service_info_list[0]['name'];

								$new_contents_info = str_replace($replace_string, $temp_content, $new_contents_info);
							}
						}

						// $obj_pdf->setCellPaddings(0, 0, 0, 0);
						$tagvs = array('p' => array(1 => array('h' => 0.0001, 'n' => 1)), 'ul' => array(0 => array('h' => 0.0001, 'n' => 1)));
						$obj_pdf->setHtmlVSpace($tagvs);

						$content = $new_contents_info;

						$obj_pdf->writeHTML($content, true, 0, true, true);

						// company signature
						if($service_info_list[0]['name'] == "ACUMEN BIZCORP PTE. LTD.")
						{
							$img_tag = '<img src="img/Signature - ABC.png" height="130px;"' . ' />';

							if($q[0]["document_name"] == "Engagement letter - Corporate Tax")
							{
								$obj_pdf->setY($obj_pdf->getY() - 52);
							}
							// elseif($q[0]["document_name"] == "Audit Engagement")
							// {
							// 	$obj_pdf->setY($obj_pdf->getY() - 52);
							// }
							else
							{
								$obj_pdf->setY($obj_pdf->getY() - 52);
							}
						}
						elseif($service_info_list[0]['name'] == "ACUMEN ASSOCIATES LLP")
						{
							$img_tag = '<img src="img/Signature - AA LLP.png" height="85px;"' . ' />';

							// if($q[0]["document_name"] == "Audit Engagement")
							// {
							// 	$obj_pdf->setY($obj_pdf->getY() - 33);
							// }
							// else
							// {
								$obj_pdf->setY($obj_pdf->getY() - 40);
							// }
						}
						elseif($service_info_list[0]['name'] == "SYA PAC")
						{
							$img_tag = '<img src="img/Signature - SYA.png" height="200px;"' . ' />';

							if($q[0]["document_name"] == "Engagement letter - Corporate Tax")
							{
								$obj_pdf->setY($obj_pdf->getY() - 68);
							}
							elseif($q[0]["document_name"] == "Audit Engagement")
							{
								$obj_pdf->setY($obj_pdf->getY() - 60);
							}
							else
							{
								$obj_pdf->setY($obj_pdf->getY() - 58);
							}
						}
						elseif($service_info_list[0]['name'] == "ACUMEN ASSURANCE")
						{
							$img_tag = '<img src="img/Signature - AA.png" height="85px;"' . ' />';

							// if($q[0]["document_name"] == "Audit Engagement")
							// {
							// 	$obj_pdf->setY($obj_pdf->getY() - 40);
							// }
							// else
							// {
								$obj_pdf->setY($obj_pdf->getY() - 40);
							// }
						}
						elseif($service_info_list[0]['name'] == "SIMPEX CONSULTING (S) PTE. LTD.")
						{
							$img_tag = '<img src="img/Signature - Simpex.png" height="85px;"' . ' />';

							// if($q[0]["document_name"] == "Audit Engagement")
							// {
							// 	$obj_pdf->setY($obj_pdf->getY() - 40);
							// }
							// else
							// {
								$obj_pdf->setY($obj_pdf->getY() - 40);
							// }
						}
						else
						{
							$img_tag = '';
						}

						$obj_pdf->writeHTML($img_tag, true, false, false, false, '');

						$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$service_info_list[0]['name'].'.pdf', 'F');

						if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$service_info_list[0]['name'].'.pdf'))
		    			{
					        echo "File Doesn't Exist...";exit;
					    }

						$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.$service_info_list[0]['name'].'.pdf');

						$data['transaction_id'] = $transaction_master_id;
						$data['type'] = "trans"; 
						$data['client_id'] = $client_query[0]["id"];
						$data['firm_id'] = $q[0]["firm_id"];
						$data['document_name'] = $q[0]["document_name"].' - '.$service_info_list[0]['name'];
						$data['triggered_by'] = 1;
						$data['document_date_checkbox'] = 1;
						$data['transaction_date'] = DATE("d/m/Y",now());
						$data['content'] = $content;
	            		$data['created_by']=$this->session->userdata('user_id');

	            		$this->save_incorporate_pdf($data);
					}
				}
				else
				{
					// if document contains DRIW word, set header
					// if(strpos($q[0]["document_name"], "DRIW") !== false)
					// {
					if($q[0]["document_name"] == "DRIW-Allotment of Shares" || $q[0]["document_name"] == "AGM & AR - DRIW" || $q[0]["document_name"] == "DRIW-Change of Reg Ofis")
					{
						$header_content = $this->get_header_template("DRIW");

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
						$obj_pdf->SetAutoPageBreak(TRUE, 30);
						$obj_pdf->setListIndentWidth(4);

						if($q[0]["document_name"] == "AGM & AR - DRIW")
						{
							$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
						}
					}
					// elseif(strpos($q[0]["document_name"], "Attendance") !== false || strpos($q[0]["document_name"], "Shorter notice") !== false)
					elseif($q[0]["document_name"] == "Allotment-Attendance List" || $q[0]["document_name"] == "Allotment-Shorter notice of EGM" || $q[0]["document_name"] == "AGM & AR - Attendance List")
					{
						$template_type = '';

						if(strpos($q[0]["document_name"], "Attendance") !== false)
						{
							$header_content = $this->get_header_template("Attendance");
						}
						elseif(strpos($q[0]["document_name"], "Shorter notice"))
						{
							$header_content = $this->get_header_template("headerOnly");
							$header_title = '
							<p style="text-align: center;"><span style="font-size: 10pt;"><strong>AGREEMENT BY MEMBER TO SHORTER NOTICE FOR<br /></strong><strong>AN EXTRAORDINARY GENERAL MEETING</strong></span></p>
							<p style="text-align: justify;">&nbsp;</p>';

							$header_content = $header_content . $header_title;
						}

						$member_name_result = $this->db->query('select member_shares.*, z.company_name as tr_client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, y.registration_no as client_registration_no, y.company_name as client_company_name from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client as y on y.id = member_shares.officer_id and member_shares.field_type = "client" and y.deleted <> 1 left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client as z on z.company_code = member_shares.company_code AND z.deleted <> 1 where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
	                	//officer_company.company_corporate_representative, 
	                	$member_name_result = $member_name_result->result_array();

	                	$num_of_members = count($member_name_result);

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+22);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+40, PDF_MARGIN_RIGHT+3);
						// echo $num_of_members;
						if($num_of_members < 5)
						{
							$obj_pdf->SetAutoPageBreak(TRUE, 10);
						}else
						{
							$obj_pdf->SetAutoPageBreak(TRUE, 35);
						}

						$obj_pdf->setListIndentWidth(4);
					}
					// elseif(strpos($q[0]["document_name"], "Shorter notice") !== false)
					// {
					// 	$header_content = $this->get_header_template("headerOnly");
					// 	$header_title = '<p style="text-align: center;">&nbsp;</p>
					// 			<p style="text-align: center;"><span style="font-size: 10pt;"><strong>AGREEMENT BY MEMBER TO SHORTER NOTICE FOR<br /></strong><strong>AN EXTRAORDINARY GENERAL MEETING</strong></span></p>
					// 			<p style="text-align: justify;">&nbsp;</p>';

					// 	$header_content = $header_content . $title;

					// 	$pattern = "/{{[^}}]*}}/";
					// 	$subject = $header_content;
					// 	preg_match_all($pattern, $subject, $header_tag_matches);

					// 	$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

					// 	$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					// 	$obj_pdf->SetCreator(PDF_CREATOR);
					// 	$title = "Document";
					// 	$obj_pdf->SetTitle($title);
					// 	$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));
					// 	$obj_pdf->setPrintHeader(true);
					// 	$obj_pdf->setPrintFooter(true);
					// 	$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
					// 	$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+30, PDF_MARGIN_RIGHT+3);
					// 	$obj_pdf->SetAutoPageBreak(TRUE, 30);
					// 	$obj_pdf->setListIndentWidth(4);
					// }
					elseif($q[0]["document_name"] == "Allotment-Authority to EGM"){	// set header

						$header_content = $this->get_header_template("DRIW");

						$pattern = "/{{[^}}]*}}/";
						$subject = $header_content;
						preg_match_all($pattern, $subject, $header_tag_matches);

						$header_content = $this->replaceToggle($transaction_master_id, $header_tag_matches[0], $company_code, $q[0]["firm_id"], $header_content, null, $q[0]["document_name"]);

						$obj_pdf = new DRIW_PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));

						$obj_pdf->setPrintHeader(true);
						$obj_pdf->setPrintFooter(true);
						$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER+20);
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT+2, PDF_MARGIN_TOP+35, PDF_MARGIN_RIGHT+3);
						$obj_pdf->SetAutoPageBreak(TRUE, 30);

					}
					else
					{
						$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
						$obj_pdf->SetCreator(PDF_CREATOR);
						$title = "Document";
						$obj_pdf->SetTitle($title);
						$obj_pdf->setPrintHeader(false);
						$obj_pdf->setPrintFooter(false);
						$obj_pdf->SetDefaultMonospacedFont('helvetica');
						$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
						$obj_pdf->SetFont('helvetica', '', 10);
						$obj_pdf->setFontSubsetting(false);
					}

					if($q[0]["document_name"] == "AGM & AR - Annual Return")
					{
						$obj_pdf->setListIndentWidth(4);
					}

					$obj_pdf->AddPage();

					if($q[0]["document_name"] == "AGM & AR - DRIW" || $q[0]["document_name"] == "AGM & AR - Notice for AGM" || $q[0]["document_name"] == "AGM & AR - Minutes of AGM")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						if(strpos($new_contents_info, '<span class="continuous_director"><span class="myclass mceNonEditable">{{Directors name - all}}</span></span>') !== false)
	                	{
		                	for($j = 0; $j < count($get_directors); $j++)
							{
								if($j == 0)
								{
									$directors_name = $get_directors[$j]["name"];
								}
								elseif($j == (count($get_directors)-1))
								{
									$directors_name = $directors_name." and ".$get_directors[$j]["name"];
								}
								else
								{
									$directors_name = $directors_name.", ".$get_directors[$j]["name"];
								}
							}

							$new_contents_info = str_replace('<span class="continuous_director"><span class="myclass mceNonEditable">{{Directors name - all}}</span></span>', $directors_name, $new_contents_info);
						}

						if(strpos($new_contents_info, '<span class="title"><span class="myclass mceNonEditable">{{un/audited}}</span></span>') !== false)
						{
							$audited_fs = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

							$audited_fs = $audited_fs->result_array();

							if(count($audited_fs) > 0)
							{
								if($audited_fs[0]["audited_fs"] == 1)
								{
									$audited_type = "AUDITED";
								}
								else if($audited_fs[0]["audited_fs"] == 2)
								{
									$audited_type = "UNAUDITED";
								}

								$new_contents_info = str_replace('<span class="title"><span class="myclass mceNonEditable">{{un/audited}}</span></span>', $audited_type, $new_contents_info);
							}
						}
					}

					$new_contents = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $new_contents_info, null, $q[0]["document_name"]);

					$content = $new_contents;

					if($q[0]["document_name"] == "AGM & AR - Annual Return")
					{
						$transaction_agm_ar = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

						$transaction_agm_ar = $transaction_agm_ar->result_array();

						// if($transaction_agm_ar[0]['epc_status_id'] == 2)
						// {
						// 	if(strpos($content, '<li class="is_epc"') !== false)
	     //            		{
	     //            			$content = str_replace('<li class="is_epc" style="text-align: justify;">', '<li class="is_epc" style="text-align: justify; display: none">', $content);
	     //            		}
	     //            	}

	                	if($transaction_agm_ar[0]['solvency_status'] == 2)
						{
							if(strpos($content, '<li class="is_solvent"') !== false)
	                		{
	                			$content = str_replace('<li class="is_solvent" style="text-align: justify;">', '<li class="is_solvent" style="text-align: justify; display: none">', $content);
	                		}
	                	}

	                	if($transaction_agm_ar[0]['activity_status'] == 1)
						{
							if(strpos($content, '<li class="is_dormant"') !== false)
	                		{
	                			$content = str_replace('<li class="is_dormant" style="text-align: justify;">', '<li class="is_dormant" style="text-align: justify; display: none">', $content);
	                		}
	                	}

	                	// for AGM & AR - Annual Return (EPC)
						if(strpos($content, '<span class="myclass mceNonEditable">{{AGM &amp; AR - EPC}}</span>') !== false)
                		{
                			// echo json_encode($transaction_agm_ar);

                			$temp_content = '';

                			if($transaction_agm_ar[0]["epc_status_id"] == 1)	// for yes
							{
								$temp_content = 'for the entire financial year concerned, the company had been an exempt private company at all relevant times as defined under Section 4(1) of the Companies Act by virtue of its being a private company of which no beneficial interest in its shares is held, directly or indirectly, by any corporation and having not more than 20 members;';
							}
							elseif($transaction_agm_ar[0]["epc_status_id"] == 2) 	// for no
							{
								$temp_content = 'the company is a private company and the number of its member is not more than 50 (counting joint holders of shares as one person and not counting any person in the employment of the company or of its subsidiary or any person who while previously in the employment of the company or of its subsidiary was and thereafter has continued to be a member of the company;';
							}
							elseif($transaction_agm_ar[0]["epc_status_id"] == 0)
							{
								$temp_content = "EPC IS NOT SELECTED.";
							}

							$content = str_replace('<span class="myclass mceNonEditable">{{AGM &amp; AR - EPC}}</span>', $temp_content, $content);
                		}
					}

					if($q[0]["document_name"] == "AGM & AR - DRIW" || $q[0]["document_name"] == "AGM & AR - Notice for AGM"|| $q[0]["document_name"] == "AGM & AR - Minutes of AGM")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						if(2 > count($get_directors))
						{
							if(strpos($content, '<span class="many_director">s</span>') !== false)
		                	{
		                		$content = str_replace('<span class="many_director">s</span>', '<span class="many_director" style="display: none">s</span>', $content);

		                	}

		                	if(strpos($content, '<span class="many_director">S</span>') !== false)
		                	{
		                		$content = str_replace('<span class="many_director">S</span>', '<span class="many_director" style="display: none">S</span>', $content);

		                	}

		                	// if(strpos($content, '<p class="directors">') !== false)
	                		// {
	                		// 	$content = str_replace('<p class="directors">', '<p class="directors" style="display: none">', $content);
	                		// }
		                }
		                else
		                {
		                	if(strpos($content, '<span class="single_director">SOLE</span>') !== false)
		                	{
		                		$content = str_replace('<span class="single_director">SOLE</span>', '<span class="single_director" style="display: none">SOLE</span>', $content);

		                	}
		                }

		                if(strpos($content, '<span class="myclass mceNonEditable">{{number of director}}</span>') !== false)
	                	{
	                		$content = str_replace('<span class="myclass mceNonEditable">{{number of director}}</span>', $this->convert_number_to_word_model->convert_number_to_words(count($get_directors)), $content);

	                	}

	                	$transaction_agm_ar = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

						$transaction_agm_ar = $transaction_agm_ar->result_array();

						if($transaction_agm_ar[0]['small_company'] == 2)
						{
							// if(strpos($content, '<p class="small_company" style="text-align: justify;">') !== false)
	      //           		{
	      //           			$content = str_replace('<p class="small_company" style="text-align: justify;">', '<p class="small_company" style="display: none">', $content);
	      //           		}

							if(strpos($content, '{{exemption_small_company}}') !== false)
	                		{
	                			$content = str_replace('{{exemption_small_company}}', '', $content);
	                		}

	                		if($transaction_agm_ar[0]['solvency_status'] == 2)
							{
								if(strpos($content, '<p class="solvent">') !== false)
		                		{
		                			$content = str_replace('<p class="solvent">', '<p class="solvent" style="display: none">', $content);
		                		}
		                	}
	                	}
	                	else
	                	{
	                		// $exemption_template = '<p class="small_company" style="text-align: justify;"><span style="text-align: justify; font-size: 10pt;"><strong>EXEMPTION FROM AUDIT REQUIREMENTS FOR THE FINANCIAL YEAR ENDED <span style="text-transform: uppercase;"><span class="myclass mceNonEditable">{{Year end new}}</span></span></strong></span></p><p class="small_company" style="text-align: justify;"><span style="text-align: justify; font-size: 10pt;">NOTED that the Company satisfies the criteria of an exempt private company as defined under the Companies Act, Cap. 50 (the &ldquo;Act&rdquo;) and pursuant to the provisions of Section 205C of the Act, is exempted from audit requirements in respect of the profit and loss accounts and balance sheet for the financial year ended <span class="myclass mceNonEditable">{{Year end new}}</span>.</span></p>';

	                		$exemption_template = '<span style="text-align: justify; font-size: 10pt;"><strong>EXEMPTION FROM AUDIT REQUIREMENTS FOR THE FINANCIAL YEAR ENDED <span style="text-transform: uppercase;"><span class="myclass mceNonEditable">{{Year end new}}</span></span></strong></span></p><p class="small_company" style="text-align: justify;"><span style="text-align: justify; font-size: 10pt;">NOTED that the Company satisfies the criteria of an exempt private company as defined under the Companies Act, Cap. 50 (the &ldquo;Act&rdquo;) and pursuant to the provisions of Section 205C of the Act, is exempted from audit requirements in respect of the profit and loss accounts and balance sheet for the financial year ended <span class="myclass mceNonEditable">{{Year end new}}</span>.</span></p><p class="small_company" style="text-align: justify;">';

							if(strpos($content, '{{exemption_small_company}}') !== false)
	                		{
	                			$content = str_replace('{{exemption_small_company}}', $exemption_template, $content);
	                		}

	                		$content = $this->replaceToggle($transaction_master_id, $matches[0], $company_code, $q[0]["firm_id"], $content, null, $q[0]["document_name"]);
	                	}

	                	// for AGM & AR - DRIW (retirement paragraph)
	                	$get_directors_retirement = $this->db->query('select transaction_agm_ar_director_retire.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id left join transaction_agm_ar_director_retire on transaction_agm_ar_director_retire.transaction_agm_ar_id = transaction_agm_ar.id and transaction_agm_ar_director_retire.director_retiring_checkbox = 1 where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

	                	$get_directors_retirement = $get_directors_retirement->result_array();

	                	$directors_name_all_retire = '';

	                	if(count($get_directors_retirement) > 0 && !is_null($get_directors_retirement[0]["director_retire_name"]))
	                	{
		                	for($x = 0; $x < count($get_directors_retirement); $x++)
							{	
								if($x == 0)
								{
									$directors_name_all_retire = $get_directors_retirement[$x]["director_retire_name"];
								}
								else if($x == count($get_directors_retirement) - 1)
								{
									$directors_name_all_retire = $directors_name_all_retire . ' and ' . $get_directors_retirement[$x]["director_retire_name"];
								}
								else
								{	
									$directors_name_all_retire = $directors_name_all_retire . ', ' . $get_directors_retirement[$x]["director_retire_name"];
								}
							}

							$retire_paragraph = '</p><p class="directors"><span style="text-align: justify; font-size: 10pt;"><strong>RETIREMENT AND RE-ELECTION OF DIRECTORS PURSUANT TO ARTICLES 110 OF THE COMPANY&rsquo;S ARTICLES OF ASSOCIATION</strong></span></p><p class="directors"><span style="text-align: justify; font-size: 10pt;">RESOLVED THAT <span class="continuous_director"><span class="myclass mceNonEditable">'. $directors_name_all_retire. '</span></span>, as agree among the directors, shall retire from office and be eligible for re-election pursuant to regulation of the Constitution of the Company.</span>';
							
							if($q[0]["document_name"] == "AGM & AR - Notice for AGM")
							{
								$content = str_replace('<tr class="retirement">', '<tr><td style="width: 4.98107%;">&nbsp;</td><td style="width: 95.0189%;">&nbsp;</td></tr><tr class="retirement">', $content);

								$content = str_replace('{{Director Retirement}}', $directors_name_all_retire, $content);

								$content = $this->write_list_number($content);
							}
							elseif($q[0]["document_name"] == "AGM & AR - Minutes of AGM")
							{
								$content = str_replace('{{Director Retirement}}', $directors_name_all_retire, $content);
							}
							else
							{
								$content = str_replace('<span class="retire_para">{{retirement paragraph}}</span>', $retire_paragraph, $content);
							}
						}
						else
						{
							if($q[0]["document_name"] == "AGM & AR - Notice for AGM")
							{
								// Do not modify this.
								$content = str_replace('<tr class="retirement">', '<tr class="retirement" style="display:none">', $content);
								$content = str_replace('<td style="width: 4.98107%;"><span style="font-size: 10pt;">{{no}}</span></td>', '<td style="width: 4.98107%;"><span style="font-size: 10pt;"></span></td>', $content);

								$content = $this->write_list_number($content);
							}
							elseif($q[0]["document_name"] == "AGM & AR - Minutes of AGM")
							{
								$content = str_replace('<p class="retirement">', '<p class="retirement" style="display:none;">', $content);
							}
							else
							{
								$content = str_replace('<span class="retire_para">{{retirement paragraph}}</span>', '', $content);
							}
						}
					}

					$content = $this->end_of_resol_page_break($obj_pdf, $content);

					$obj_pdf->writeHTML($content, true, false, false, false, '');

					$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf', 'F');

					if(!file_exists($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf'))
	    			{
				        echo "File Doesn't Exist...";exit;
				    }

					$this->zip->read_file($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].' - '.preg_replace('/[^a-zA-Z0-9 _\.-]/', '', $client_query[0]["company_name"]).'.pdf');

					$data['transaction_id'] = $transaction_master_id;
					$data['type'] = "trans"; 
					$data['client_id'] = $client_query[0]["id"];
					$data['firm_id'] = $q[0]["firm_id"];
					$data['document_name'] = $q[0]["document_name"].' - '.$client_query[0]["company_name"];
					$data['triggered_by'] = 1;
					$data['document_date_checkbox'] = 1;
					$data['transaction_date'] = DATE("d/m/Y",now());
					$data['content'] = $content;
            		$data['created_by']=$this->session->userdata('user_id');

            		$this->save_incorporate_pdf($data);
				}
			}
			$this->zip->archive($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$transaction_task_name.' ('.(DATE("Y",now())) .') - '. $client_query[0]["company_name"].'.zip');

			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

			$link = $protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/document/'.$transaction_task_name.' ('.(DATE("Y",now())) .') - '. $client_query[0]["company_name"].'.zip';

			$data = array('status'=>'success', 'zip_link'=>$link);

	    	echo json_encode($data);
			
		}


		
	}

	public function replaceToggle($transaction_master_id, $match, $company_code, $firm_id, $new_contents, $id = null, $document_name = null)
   	{	         	
   		$content = "";

   		if(count($match) != 0)
   		{
	   		for($r = 0; $r < count($match); $r++)
			{
				$string1 = (str_replace('{{', '',$match[$r]));
				$string2 = (str_replace('}}', '',$string1));

				//print_r($string2 == "Client name");
				//echo($string2 == "Company current name");
				if($string2 == "Company current name")
				{
					$replace_string = $match[$r];

					$get_client_name = $this->db->query("select company_name from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

					$get_client_name = $get_client_name->result_array();
					
					if(0 == count($get_client_name))
					{
						$get_client_name = $this->db->query("select company_name from client where company_code='".$company_code."' AND client.deleted != 1");

						$get_client_name = $get_client_name->result_array();
					}

					$content = $get_client_name[0]["company_name"];
					//echo json_encode($company_code);
				}
				elseif($string2 == "Company old name")
				{
					$replace_string = $match[$r];

					$get_company_name = $this->db->query("select company_name from transaction_change_company_name where transaction_id='".$transaction_master_id."'");

					$get_company_name = $get_company_name->result_array();

					$content = $get_company_name[0]["company_name"];
					//echo json_encode($company_code);
				}
				elseif($string2 == "Company new name")
				{
					$replace_string = $match[$r];

					$get_new_company_name = $this->db->query("select new_company_name from transaction_change_company_name where transaction_id='".$transaction_master_id."'");

					$get_new_company_name = $get_new_company_name->result_array();

					$content = $get_new_company_name[0]["new_company_name"];
					//echo json_encode($company_code);
				}
				elseif($string2 == "UEN")
				{
					$replace_string = $match[$r];

					$get_client_registration_no = $this->db->query("select registration_no from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

					$get_client_registration_no = $get_client_registration_no->result_array();

					if(0 == count($get_client_registration_no))
					{
						$get_client_registration_no = $this->db->query("select registration_no from client where company_code='".$company_code."' AND client.deleted != 1");

						$get_client_registration_no = $get_client_registration_no->result_array();
					}

					if($get_client_registration_no[0]["registration_no"] != null)
					{
						$content = $get_client_registration_no[0]["registration_no"];
					}
					else
					{
						$content = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					}
					
					//echo json_encode($company_code);
				}
				elseif($string2 == "Company type")
				{	
					$replace_string = $match[$r];

					$get_client_company_type = $this->db->query("select company_type.company_type as company_type_name from client left join company_type on company_type.id = client.company_type where company_code='".$company_code."' AND client.deleted != 1");

					$get_client_company_type = $get_client_company_type->result_array();

					$content = $get_client_company_type[0]["company_type_name"];
				}
				elseif($string2 == "Incorporation date")
				{
					$replace_string = $match[$r];

					$get_client_incorporation_date = $this->db->query("select incorporation_date from client where company_code='".$company_code."' AND client.deleted != 1");

					$get_client_incorporation_date = $get_client_incorporation_date->result_array();

					if($document_name == "Form 45" || $document_name == "Form 45B")
					{
						$get_transaction_master = $this->db->query("select transaction_task_id from transaction_master where id = '".$transaction_master_id."'");

						$get_transaction_master = $get_transaction_master->result_array();

						if($get_transaction_master[0]["transaction_task_id"] == 1)
						{
							$content = '<strong><span style="text-decoration: underline;">&nbsp; date of incorporation&nbsp;&nbsp;</span></strong>';
						}
						else
						{
							$content = '______________________';
						}
					}
					else
					{
						$content = $get_client_incorporation_date[0]["incorporation_date"];
					}
					
				}
				elseif($string2 == "Secretarys name - appointment")
				{
					$replace_string = $match[$r];

					if($document_name == "First Director Resolutions (One)" || $document_name == "First Director Resolutions (Many)" || $document_name == "{{Secretarys name - appointment}}")
					{
						$get_secretarys = $this->db->query("select officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4");

						$get_secretarys = $get_secretarys->result_array();

						for($j = 0; $j < count($get_secretarys); $j++)
						{
							if($j == 0)
							{
								$secretary_name = $get_secretarys[$j]["name"]." (Identification No: ".$get_secretarys[$j]["identification_no"].")";
							}
							elseif($j == (count($get_secretarys)-1))
							{
								$secretary_name = $secretary_name." and ".$get_secretarys[$j]["name"]." (Identification No: ".$get_secretarys[$j]["identification_no"].")";
							}
							else
							{
								$secretary_name = $secretary_name.", ".$get_secretarys[$j]["name"]." (Identification No: ".$get_secretarys[$j]["identification_no"].")";
							}
						}
						$content = $secretary_name;
						//echo json_encode($get_secretarys);
					}
					else
					{
						$get_secretarys = $this->db->query("select officer.name, officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");

						$get_secretarys = $get_secretarys->result_array();

						$content = $get_secretarys[0]["name"];
					}
				}
				elseif($string2 == "Secretarys ID - appointment")
				{
					$replace_string = $match[$r];

					// if($document_name == "First Director Resolutions (One)")
					// {
					// 	$get_secretarys_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4");
					// }
					// else
					// {
						$get_secretarys_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");
					//}

					$get_secretarys_ID = $get_secretarys_ID->result_array();

					$content = $get_secretarys_ID[0]["identification_no"];
				}
				elseif($string2 == "Secretarys address - appointment")
				{
					$replace_string = $match[$r];
					$address = '';

					// $get_secretarys_address = $this->db->query("select officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2 from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");

					// $get_secretarys_address = $this->db->query("SELECT officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2 from transaction_client_officers LEFT JOIN officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type WHERE transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");
					$get_secretarys_address = $this->db->query("SELECT officer.* from transaction_client_officers LEFT JOIN officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type WHERE transaction_client_officers.position=4 AND transaction_client_officers.id=".$id);

					$get_secretarys_address = $get_secretarys_address->result_array();

					if(empty($get_secretarys_address[0]["unit_no1"]) || empty($get_secretarys_address[0]["unit_no2"]))
					{
						$unit_no = ' #'.$get_secretarys_address[0]["unit_no1"].' - '.$get_secretarys_address[0]["unit_no2"];
					}

					if($document_name == "Ltr of Indemnity")
					{	
						if($get_secretarys_address[0]['address_type'] == "Local"){
							if(!empty($get_secretarys_address[0]["building_name1"]))
							{
								$address = $get_secretarys_address[0]["street_name1"].'<br/>'.$unit_no.'<br/>'.$get_secretarys_address[0]["building_name1"].'<br/>SINGAPORE '.$get_secretarys_address[0]["postal_code1"];
							}
							else
							{
								$address = $get_secretarys_address[0]["street_name1"].'<br/>'.$unit_no.'<br/>SINGAPORE '.$get_secretarys_address[0]["postal_code1"];
							}
						}
						elseif ($get_secretarys_address[0]['address_type'] == "Foreign") {
							$address = $get_secretarys_address[0]["foreign_address1"].'<br/>'.$get_secretarys_address[0]["foreign_address2"].'<br/>'.$get_secretarys_address[0]["foreign_address3"];
						}
						// $address = $company_code . ", " . $transaction_master_id . ", " . $id;
					}
					else
					{
						// if(!empty($get_secretarys_address[0]["building_name1"]))
						// {
						// 	$address = $get_secretarys_address[0]["street_name1"].$unit_no.' '.$get_secretarys_address[0]["building_name1"].' SINGAPORE '.$get_secretarys_address[0]["postal_code1"];
						// }
						// else
						// {
						// 	$address = $get_secretarys_address[0]["street_name1"].$unit_no.' SINGAPORE '.$get_secretarys_address[0]["postal_code1"];
						// }
						$address = $this->write_address($get_secretarys_address[0]["street_name1"], $get_secretarys_address[0]["unit_no1"], $get_secretarys_address[0]["unit_no2"], $get_secretarys_address[0]["building_name1"], $get_secretarys_address[0]["postal_code1"], "comma");
					}

					$content = $address;
				}
				elseif($string2 == "Secretarys nationality - appointment")
				{
					$replace_string = $match[$r];

					$get_secretarys_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position=4 AND transaction_client_officers.id='".$id."'");

					$get_secretarys_nationality = $get_secretarys_nationality->result_array();

					$content = $get_secretarys_nationality[0]["nationality_name"];
				}
				elseif($string2 == "Directors name - appointment")
				{
					$replace_string = $match[$r];

					if($document_name == "Letter on appointment of Secretary")
					{
						$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1");

						$get_directors = $get_directors->result_array();
					}
					else
					{
						$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

						$get_directors = $get_directors->result_array();
					}

					//echo json_encode($id);

					$content = $get_directors[0]["name"];
				}
				elseif($string2 == "Directors address - appointment")
				{
					$replace_string = $match[$r];

					$get_directors_address = $this->db->query("select officer.* from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors_address = $get_directors_address->result_array();

					$address = array(
						'type' 			=> $get_directors_address[0]['address_type'],
						'street_name1' 	=> $get_directors_address[0]["street_name1"],
						'unit_no1'		=> $get_directors_address[0]["unit_no1"],
						'unit_no2'		=> $get_directors_address[0]["unit_no2"],
						'building_name1'=> $get_directors_address[0]["building_name1"],
						'postal_code1'	=> $get_directors_address[0]["postal_code1"],
						'foreign_address1' => $get_directors_address[0]["foreign_address1"],
						'foreign_address2' => $get_directors_address[0]["foreign_address2"],
						'foreign_address3' => $get_directors_address[0]["foreign_address3"]
					);

					// if($get_directors_address[0]["unit_no1"] != " " || $get_directors_address[0]["unit_no2"] != " ")
					// {
					// 	$unit_no = ' #'.$get_directors_address[0]["unit_no1"].' - '.$get_directors_address[0]["unit_no2"];
					// }

					// $address = $get_directors_address[0]["street_name1"].$unit_no.', '.$get_directors_address[0]["building_name1"].', SINGAPORE '.$get_directors_address[0]["postal_code1"];
					$address = $this->write_address_local_foreign($address, "comma");

					$content = $address;
				}
				elseif($string2 == "Directors ID - appointment")
				{
					$replace_string = $match[$r];

					$get_directors_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors_ID = $get_directors_ID->result_array();

					$content = $get_directors_ID[0]["identification_no"];
				}
				elseif($string2 == "Directors nationality - appointment")
				{
					$replace_string = $match[$r];

					$get_directors_nationality = $this->db->query("select nationality.nationality as nationality_name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type left join nationality on nationality.id = officer.nationality where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors_nationality = $get_directors_nationality->result_array();

					$content = $get_directors_nationality[0]["nationality_name"];
				}
				elseif($string2 == "Display title type for board of director" || $string2 == "Display board of director - content" || $string2 == "Display All members of the")
				{
					$replace_string = $match[$r];

					$count_director = 1;
					$content = "";

					if($document_name == "DRIW-Appt of Director" || $document_name == "DRIW-Change of Reg Ofis" || $document_name == "DRIW - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "DRIW - Resignation Of Auditor" || $document_name == "DRIW - Change of Auditor" || $document_name == "DRIW-Change of name of company" || $document_name == "DRIW-Allotment of Shares" || $document_name == "Allotment-Authority to EGM" || $document_name == "DRIW - Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "DRIW-Change of FYE" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Incorp of subsidiary" || $document_name = "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice Of EGM" || $document_name == "Director Fee-Minutes Of EGM" || $document_name == "01 Letter of Authorisation" || $document_name == "Letter of Appointment")
					{
						$director = "";

						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						$count_director = count($get_directors);
					}
					elseif($document_name == "Auditor-Notice of EGM" || $document_name == "Company Name-Notice of EGM")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						$count_director = count($get_directors);
					}
					elseif($document_name == "Form 11")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						$count_director = count($get_directors);
					}
					// echo (int)$count_director == 2;

					if($count_director == 1)
					{
						if($string2 == "Display title type for board of director")
						{
							$content = 'Sole director';
						}
						elseif($string2 == "Display board of director - content")
						{
							$content = 'sole director';
						}
						elseif($string2 == "Display All members of the")	// Display words of "All members of the " before board of director
						{
							$content = 'The ';
						}

						$new_contents = $this->replace_verbs_plural($new_contents, $count_director);
					}
					elseif($count_director == 2)
					{
						if($string2 == "Display title type for board of director")
						{
							$content = 'The Board of Directors';
						}
						elseif($string2 == "Display board of director - content")
						{
							$content = 'board of directors';
						}
						elseif($string2 == "Display All members of the")	// Display words of "All members of the " before board of director
						{
							$content = "All members of the ";
						}

						$new_contents = $this->replace_verbs_plural($new_contents, $count_director);
					}
					elseif($count_director > 2)
					{
						if($string2 == "Display title type for board of director")
						{
							$content = 'On behalf of the Board of Directors';
						}
						elseif($string2 == "Display board of director - content")
						{
							$content = 'board of directors';
						}
						elseif($string2 == "Display All members of the") // Display words of "All members of the " before board of director
						{
							$content = "All members of the ";
						}

						$new_contents = $this->replace_verbs_plural($new_contents, $count_director);
					}
				}
				elseif($string2 == "Directors name - all")
				{
					$replace_string = $match[$r];
					$director = "";
					$content = '';

					$temp_document_name = $document_name;	// due to $document_name will change to 1 after if condition, therefore we use this to avoid the problem

					if($document_name == "DRIW-Appt of Director" || $document_name == "DRIW-Change of Reg Ofis" || $document_name == "DRIW - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "DRIW - Resignation Of Auditor" || $document_name == "DRIW - Change of Auditor" || $document_name == "DRIW-Change of name of company" || $document_name == "DRIW-Allotment of Shares" || $document_name == "Allotment-Authority to EGM" || $document_name == "DRIW - Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "DRIW-Change of FYE" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Dividends" || $document_name == "DRIW-Incorp of subsidiary" || $document_name = "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice Of EGM" || $document_name == "Director Fee-Minutes Of EGM" || $document_name == "01 Letter of Authorisation" || $document_name == "Letter of Appointment")
					{
						// print_r('Document name???? = ' . $document_name . "<br/>" . "temp_document_name = ". $temp_document_name);
						$director = "";

						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						//echo json_encode($get_directors);
						// print_r('Document name = ' . $document_name . "<br/>");
						if($temp_document_name == "01 Letter of Authorisation")
						{
							// print_r($document_name . "<br/>");
							for($i = 0; $i < count($get_directors); $i++)
							{	
								if($i == 0)
								{
									$director = "<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_directors[$i]["name"]."<br/>Director<br/>Date:";
									//echo json_encode($director);
								}
								else
								{
									$director = $director."<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_directors[$i]["name"]."<br/>Director<br/>Date:";

								}
							}
						}
						else if($temp_document_name == "AGM & AR - Notice for AGM")
						{
							for($i = 0; $i < count($get_directors); $i++)
							{	
								if($i == 0)
								{
									$director = $get_directors[$i]["name"];
								}
								else if($i == count($get_directors) - 1)
								{
									$director = $director . ' and ' . $get_directors[$i]["name"];
								}
								else
								{	
									$director = $director . ', ' . $get_directors[$i]["name"];
								}
							}
						}
						elseif($temp_document_name == "AGM & AR - DRIW")
						{
							// $each_member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';

							$director = '<table style="width: 100%; border-collapse: collapse;"><tbody>';

							for($i = 0; $i < count($get_directors); $i++)
							{	
								if($i == 0)
								{
									$director = $director . '<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$get_directors[$i]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
									//echo json_encode($director);
								}
								else
								{
									$director = $director . '<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$get_directors[$i]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';

								}
							}

							$director = $director.'</tbody></table>';
						}
						else{
							for($i = 0; $i < count($get_directors); $i++)
							{	
								if($i == 0)
								{
									$director = "<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_directors[$i]["name"];
									//echo json_encode($director);
								}
								else
								{
									$director = $director."<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_directors[$i]["name"];

								}
							}
						}

						
						// loop
						if($document_name == "DRIW - Resignation of Director" || $document_name == "Letter of Appointment")
						{
							$get_resign_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.date_of_appointment =''");

							$get_resign_directors = $get_resign_directors->result_array();

							if(count($get_resign_directors) > 0)
							{
								for($i = 0; $i < count($get_resign_directors); $i++)
								{	

									$director = $director."<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_resign_directors[$i]["name"];

									if($document_name == "Letter of Appointment"){
										$director .= "<br/>Director";
									}
								}
							}
						}
					}
					elseif($document_name == "Auditor-Notice of EGM" || $document_name == "Company Name-Notice of EGM")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						$director = "<p>&nbsp;</p><p>&nbsp;</p>________________________________<br/>".$get_directors[0]["name"];
					}
					elseif($document_name == "Form 11")
					{
						$get_directors = $this->db->query("select officer.name from client_officers left join officer on client_officers.officer_id = officer.id AND client_officers.field_type = officer.field_type where client_officers.company_code='".$company_code."' AND client_officers.date_of_cessation = '' AND client_officers.position = 1");

						$get_directors = $get_directors->result_array();

						$director = $get_directors[0]["name"];
					}
					
					//echo json_encode($id);
					//echo json_encode($director);
					$content = $director;
				}
				elseif($string2 == "Directors name - resigning")
				{
					$replace_string = $match[$r];

					$get_directors = $this->db->query("select officer.name from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors = $get_directors->result_array();

					$content = $get_directors[0]["name"];
				}
				elseif($string2 == "Directors ID - resigning")
				{
					$replace_string = $match[$r];

					$get_directors_ID = $this->db->query("select officer.identification_no from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors_ID = $get_directors_ID->result_array();

					$content = $get_directors_ID[0]["identification_no"];
				}
				elseif($string2 == "Directors address - resigning")
				{
					$replace_string = $match[$r];

					$get_directors_address = $this->db->query("select officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2 from transaction_client_officers left join officer on transaction_client_officers.officer_id = officer.id AND transaction_client_officers.field_type = officer.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.position = 1 AND transaction_client_officers.id='".$id."'");

					$get_directors_address = $get_directors_address->result_array();

					if($get_directors_address[0]["unit_no1"] != " " || $get_directors_address[0]["unit_no2"] != " ")
					{
						$unit_no = '<br/> #'.$get_directors_address[0]["unit_no1"].' - '.$get_directors_address[0]["unit_no2"];
					}

					if($get_directors_address[0]["building_name1"] != "")
					{
						$building_name = '<br/>'.$get_directors_address[0]["building_name1"];
					}

					$address = $get_directors_address[0]["street_name1"].''.$unit_no.''.$building_name.'<br/>SINGAPORE '.$get_directors_address[0]["postal_code1"];

					$content = $address;
				}
				elseif($string2 == "Auditors ID - resigning")
				{
					$replace_string = $match[$r];

					$get_auditor_id = $this->db->query("select officer_company.register_no from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment != '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

					$get_auditor_id = $get_auditor_id->result_array();

					$content = $get_auditor_id[0]["register_no"];
				}
				elseif($string2 == "Auditors name - resigning")
				{
					$replace_string = $match[$r];

					$get_auditor_name = $this->db->query("select officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment != '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

					$get_auditor_name = $get_auditor_name->result_array();

					$content = $get_auditor_name[0]["company_name"];
				}
				elseif($string2 == "Auditors ID - appointment")
				{
					$replace_string = $match[$r];

					$get_auditor_id = $this->db->query("select officer_company.register_no from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment = '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

					$get_auditor_id = $get_auditor_id->result_array();

					$content = $get_auditor_id[0]["register_no"];
				}
				elseif($string2 == "Auditors name - appointment")
				{
					$replace_string = $match[$r];

					$get_auditor_name = $this->db->query("select officer_company.company_name from transaction_client_officers left join officer_company on transaction_client_officers.officer_id = officer_company.id AND transaction_client_officers.field_type = officer_company.field_type where transaction_client_officers.company_code='".$company_code."' AND transaction_id='".$transaction_master_id."' AND transaction_client_officers.date_of_appointment = '' AND transaction_client_officers.position = 5 AND transaction_client_officers.id='".$id."'");

					$get_auditor_name = $get_auditor_name->result_array();

					$content = $get_auditor_name[0]["company_name"];
				}
				elseif($string2 == "Allotment - members")
				{
					$replace_string = $match[$r];

					if($document_name == "First Director Resolutions (One)")
					{
						$get_member_name = $this->db->query("select transaction_member_shares.*, officer.name, officer_company.company_name, client.company_name as client_company_name from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_page_id='".$transaction_master_id."'");
					}
					else
					{
						$get_member_name = $this->db->query("select transaction_member_shares.*, officer.name, officer_company.company_name, client.company_name as client_company_name from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_page_id='".$transaction_master_id."' AND transaction_member_shares.id = '".$id."'");
					}

					$get_member_name = $get_member_name->result_array();

					if($get_member_name[0]["name"] != null)
					{
						$content = $get_member_name[0]["name"];
					}
					else if($get_member_name[0]["company_name"] != null)
					{
						$content = $get_member_name[0]["company_name"];
					}
					else if($get_member_name[0]["client_company_name"] != null)
					{
						$content = $get_member_name[0]["client_company_name"];
					}
				}
				elseif($string2 == "Allotment - members ID")
				{
					$replace_string = $match[$r];

					$get_member_name = $this->db->query("select transaction_member_shares.*, officer.identification_no, officer_company.register_no, client.registration_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND client.deleted != 1 where transaction_member_shares.company_code='".$company_code."' AND transaction_member_shares.id = '".$id."' AND transaction_page_id='".$transaction_master_id."'");

					$get_member_name = $get_member_name->result_array();

					if($get_member_name[0]["identification_no"] != null)
					{
						$content = $get_member_name[0]["identification_no"];
					}
					else if($get_member_name[0]["register_no"] != null)
					{
						$content = $get_member_name[0]["register_no"];
					}
					else if($get_member_name[0]["registration_no"] != null)
					{
						$content = $get_member_name[0]["registration_no"];
					}
				}
				elseif($string2 == "Allotment - members address" || $string2 == "Transferee - members address")
				{
					$replace_string = $match[$r];

					$get_member_address = $this->db->query('select transaction_member_shares.*, officer.address_type as officer_address_type, officer.postal_code1, officer.street_name1, officer.building_name1, officer.unit_no1, officer.unit_no2, officer.foreign_address1, officer.foreign_address2, officer.foreign_address3, officer_company.field_type as officer_company_field_type, officer_company.register_no, officer_company.address_type as officer_company_address_type, officer_company.company_postal_code, officer_company.company_street_name, officer_company.company_building_name, officer_company.company_unit_no1, officer_company.company_unit_no2, officer_company.company_foreign_address1, officer_company.company_foreign_address2, officer_company.company_foreign_address3, client.postal_code as client_postal_code, client.street_name as client_street_name, client.building_name as client_building_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2 from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

					$get_member_address = $get_member_address->result_array();

					if($get_member_address[0]['officer_address_type'] != null)
					{
						if($get_member_address[0]['officer_address_type'] == "Local")
						{
							if($get_member_address[0]["unit_no1"] != "" || $get_member_address[0]["unit_no2"] != "")
							{
								$client_unit = ', #'.$get_member_address[0]["unit_no1"] .' - '.$get_member_address[0]["unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($get_member_address[0]["building_name1"] != "")
							{
								$members_building_name_1 = ' ' . $get_member_address[0]["building_name1"];
							}

							$content = $get_member_address[0]["street_name1"].$client_unit . $members_building_name_1.', SINGAPORE '.$get_member_address[0]["postal_code1"];
						}
						else if($get_member_address[0]['officer_address_type'] == "Foreign")
						{
							$foreign_address2 = !empty($get_member_address[0]["foreign_address2"])? ', '.$get_member_address[0]["foreign_address2"]: '';
							$foreign_address3 = !empty($get_member_address[0]["foreign_address3"])? ', '.$get_member_address[0]["foreign_address3"]: '';

							$content = $get_member_address[0]["foreign_address1"] . $foreign_address2 . $foreign_address3;
						}
					}
					else if($get_member_address[0]['officer_company_address_type'] != null)
					{
						if($get_member_address[0]['officer_company_address_type'] == "Local")
						{
							if($get_member_address[0]["company_unit_no1"] != "" || $get_member_address[0]["company_unit_no2"] != "")
							{
								$client_unit = ', #'.$get_member_address[0]["company_unit_no1"].' - '.$get_member_address[0]["company_unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($get_member_address[0]["building_name1"] != "")
							{
								$members_building_name_2 = ' ' . $get_member_address[0]["building_name1"];
							}

							$content = $get_member_address[0]["company_street_name"]. $client_unit . $members_building_name_2.', SINGAPORE '.$get_member_address[0]["company_postal_code"];
						}
						else if($get_member_address[0]['officer_company_address_type'] == "Foreign")
						{
							$company_foreign_address2 = !empty($get_member_address[0]["company_foreign_address2"])? ', '.$get_member_address[0]["company_foreign_address2"]: '';
							$company_foreign_address3 = !empty($get_member_address[0]["company_foreign_address3"])? ', '.$get_member_address[0]["company_foreign_address3"]: '';

							$content = $get_member_address[0]["company_foreign_address1"] . $company_foreign_address2 . $company_foreign_address3;
						}
					}
					else 
					{
						if($get_member_address[0]["client_unit_no1"] != "" || $get_member_address[0]["client_unit_no2"] != "")
						{
							$client_unit = ', #'.$get_member_address[0]["client_unit_no1"].' - '.$get_member_address[0]["client_unit_no2"];
						}
						else
						{
							$client_unit = "";
						}

						if($get_member_address[0]["client_building_name"] != "")
						{
							$members_building_name_3 = ' ' . $get_member_address[0]["client_building_name"];
						}

						$content = $get_member_address[0]["client_street_name"]. $client_unit.$members_building_name_3.', SINGAPORE '.$get_member_address[0]["client_postal_code"];	
					}
				}
				elseif($string2 == "Allotment - members nationality")
				{
					$replace_string = $match[$r];

					$get_member_nationality = $this->db->query('select transaction_member_shares.*, nationality.nationality as nationality_name from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id and transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id and transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join nationality on nationality.id = officer.nationality where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

					$get_member_nationality = $get_member_nationality->result_array();

					if($get_member_nationality[0]["nationality_name"] != null)
					{
						$content = $get_member_nationality[0]["nationality_name"];
					}
					else
					{
						$content = "";
					}
				}
				elseif($string2 == "Allotment - number of shares")
				{
					$replace_string = $match[$r];

					if($document_name == "First Director Resolutions (One)" || $document_name == "Allotment-Share Cert")
					{
						if($document_name == "Allotment-Share Cert")
						{
							$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
						}
						else
						{
							$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
						}

						$get_member_shares = $get_member_shares->result_array();

						if($document_name == "Allotment-Share Cert")
						{
							$content = number_format($get_member_shares[0]["number_of_share"]);
						}
						else
						{
							$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[0]["number_of_share"]))." (".number_format($get_member_shares[0]["number_of_share"]).")";
						}
					}
					else
					{
						$get_member_shares = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.id = "'.$id.'" AND transaction_page_id="'.$transaction_master_id.'"');

						$get_member_shares = $get_member_shares->result_array();

						if($document_name == "Allotment-Share Application Form")
						{
							$content = number_format($get_member_shares[0]["number_of_share"]);
						}
						else
						{
							$content = number_format($get_member_shares[0]["number_of_share"])."/-";
						}
					}
					
				}
				elseif($string2 == "Allotment - type of shares")
				{
					$replace_string = $match[$r];

					if($document_name == "DRIW-Allotment of Shares" || $document_name == "F24 - Return of allotment of shares")
					{
						$get_type_shares = $this->db->query('select transaction_member_shares.*, transaction_client_member_share_capital.class_id, transaction_client_member_share_capital.other_class, sharetype.sharetype as class_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
					}
					else
					{
						$get_type_shares = $this->db->query('select transaction_member_shares.*, transaction_client_member_share_capital.class_id, transaction_client_member_share_capital.other_class, sharetype.sharetype as class_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = transaction_client_member_share_capital.class_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
					}

					$get_type_shares = $get_type_shares->result_array();

					if($get_type_shares[0]["class_id"] == 2)
					{
						$content = $get_type_shares[0]["other_class"];
					}
					else
					{
						$content = $get_type_shares[0]["class_name"];
					}

				}
				elseif($string2 == "Allotment - currency")
				{
					$replace_string = $match[$r];

					if($document_name == "DRIW-Allotment of Shares" || $document_name == "F24 - Return of allotment of shares")
					{
						$get_currency = $this->db->query('select transaction_member_shares.*, currency.currency as currency_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join currency on currency.id = transaction_client_member_share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
					}
					else
					{
						$get_currency = $this->db->query('select transaction_member_shares.*, currency.currency as currency_name from transaction_member_shares left join transaction_client_member_share_capital on transaction_client_member_share_capital.id = transaction_member_shares.client_member_share_capital_id left join currency on currency.id = transaction_client_member_share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
					}

					$get_currency = $get_currency->result_array();

					$content = $get_currency[0]["currency_name"];

				}
				elseif($string2 == "Allotment - certificate")
				{
					$replace_string = $match[$r];

					$get_certificate = $this->db->query('select transaction_certificate.* from transaction_certificate left join transaction_member_shares on transaction_member_shares.id = "'.$id.'" where transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'"');

					$get_certificate = $get_certificate->result_array();

					$content = $get_certificate[0]["new_certificate_no"];

				}
				elseif($string2 == "Allotment - amount of shares")
				{
					$replace_string = $match[$r];

					if($document_name == "Shares allotment form")
					{
						$get_member_amount_shares = $this->db->query('select transaction_member_shares.*, currencies.currency from transaction_member_shares left join transaction_client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
					}
					else
					{
						$get_member_amount_shares = $this->db->query('select transaction_member_shares.*, currencies.currency from transaction_member_shares left join client_member_share_capital as share_capital on transaction_member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
					}

					$get_member_amount_shares = $get_member_amount_shares->result_array();

					//echo json_encode($get_member_amount_shares[0]["currency"])

					if($document_name == "Allotment-Share Cert")
					{
						$content = number_format($get_member_amount_shares[0]["amount_share"],2)."/-";
					}
					else
					{
						$content = $get_member_amount_shares[0]["currency"].number_format($get_member_amount_shares[0]["amount_share"],2)."/-";
					}
					
				}
				elseif($string2 == "Allotment - number of shares all")
				{
					$replace_string = $match[$r];

					$get_member_total_shares = $this->db->query('select sum(number_of_share) as total_number_of_share from transaction_member_shares where transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.company_code="'.$company_code.'"');

					$get_member_total_shares = $get_member_total_shares->result_array();

					if($document_name == "First Director Resolutions (Many)")
					{
						$total_share = $get_member_total_shares[0]["total_number_of_share"];
						$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($total_share))." (".number_format($get_member_total_shares[0]["total_number_of_share"]).")";
					}
					else if($document_name == "DRIW-Allotment of Shares" || $document_name = 'F24 - Return of allotment of shares')
					{
						$content = number_format($get_member_total_shares[0]["total_number_of_share"]);
					}
					else
					{
						$content = number_format($get_member_total_shares[0]["total_number_of_share"])."/-";
					}
				}
				elseif($string2 == "Allotment - per shared")
        		{
        			$replace_string = $match[$r];

        			// if($document_name == "First Director Resolutions (Many)")
        			// {
        			// 	$get_per_share = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id = "'.$id.'"');
        			// }
        			// else
        			// {
        				$get_per_share = $this->db->query('select transaction_member_shares.* from transaction_member_shares where transaction_member_shares.company_code="'.$company_code.'" AND transaction_page_id="'.$transaction_master_id.'"');
        			//} || $document_name == "F24 - Return of allotment of shares"

        			$get_per_share = $get_per_share->result_array();

        			if($document_name == "DRIW-Allotment of Shares")
        			{
        				$doc_amount_share = 0;
        				$doc_number_of_share = 0;
        				for($y = 0; $y < count($get_per_share); $y++)
            			{
            				$doc_amount_share = $doc_amount_share + $get_per_share[$y]["amount_share"];
            				$doc_number_of_share = $doc_number_of_share + $get_per_share[$y]["number_of_share"];
            			}

            			$per_shared = $doc_amount_share / $doc_number_of_share;
        			}
        			else
        			{
        				$per_shared = $get_per_share[0]["amount_share"] / $get_per_share[0]["number_of_share"];
        			}
        			$content = number_format($per_shared, 2);
        		}
        		elseif($string2 == "Class of shares - all" || $string2 == "Currency of shares - all" || $string2 == "No of shares issued - all" || $string2 == "Amount of shares issued - all" || $string2 == "Amount of shares paid up - all")
        		{
        			$replace_string = $match[$r];

        			$get_transaction_client_member_share_capital = $this->db->query('select transaction_client_member_share_capital.*, currency.currency as currency_name from transaction_client_member_share_capital left join currency on currency.id = transaction_client_member_share_capital.currency_id where transaction_client_member_share_capital.company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

        			$get_transaction_client_member_share_capital = $get_transaction_client_member_share_capital->result_array();

        			$client_transaction_member_share_capital_id_info = $this->db->query("select transaction_client_member_share_capital.*, sum(transaction_member_shares.number_of_share) as transaction_number_of_shares, sum(transaction_member_shares.amount_share) as transaction_amount, sum(transaction_member_shares.no_of_share_paid) as transaction_number_of_shares_paid, sum(transaction_member_shares.amount_paid) as transaction_paid_up from transaction_client_member_share_capital left join transaction_member_shares on transaction_member_shares.client_member_share_capital_id = transaction_client_member_share_capital.id where transaction_client_member_share_capital.company_code = '".$company_code."' AND transaction_client_member_share_capital.transaction_id='".$transaction_master_id."' group by transaction_client_member_share_capital.id");

        			$client_transaction_member_share_capital_id_info = $client_transaction_member_share_capital_id_info->result_array();

        			$client_member_share_capital_id_info = $this->db->query("select client_member_share_capital.*, sum(member_shares.number_of_share) as number_of_shares, sum(member_shares.amount_share) as amount, sum(member_shares.no_of_share_paid) as number_of_shares_paid, sum(member_shares.amount_paid) as paid_up 
        				from client_member_share_capital 
        				left join member_shares on member_shares.client_member_share_capital_id = client_member_share_capital.id AND member_shares.company_code = client_member_share_capital.company_code 
        				where client_member_share_capital.company_code = '".$get_transaction_client_member_share_capital[0]["company_code"]."' 
        				AND client_member_share_capital.class_id='".$get_transaction_client_member_share_capital[0]["class_id"]."' 
        				AND client_member_share_capital.other_class='".$get_transaction_client_member_share_capital[0]["other_class"]."' 
        				AND client_member_share_capital.currency_id='".$get_transaction_client_member_share_capital[0]["currency_id"]."' 
        				group by client_member_share_capital.id");

			        $client_member_share_capital_id_info = $client_member_share_capital_id_info->result_array();

			        //echo json_encode($client_member_share_capital_id_info);
			        if($string2 == "Currency of shares - all")
			        {
			        	$content = $get_transaction_client_member_share_capital[0]["currency_name"];
			        }
			        else if($string2 == "Class of shares - all")
			        {
				        if($get_transaction_client_member_share_capital[0]["class_id"] == 2)
						{
							$content = $get_transaction_client_member_share_capital[0]["other_class"];
						}
						else
						{
							$content = $get_transaction_client_member_share_capital[0]["class_name"];
						}
					}
					else if($string2 == "No of shares issued - all")
					{
						// echo json_encode($client_member_share_capital_id_info) . json_encode($client_transaction_member_share_capital_id_info);
						if(count($client_member_share_capital_id_info) > 0)
						{
							$content = number_format($client_member_share_capital_id_info[0]["number_of_shares"] + $client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares"]);
						}
						else
						{
							$content = number_format($client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares"]);
						}
					}
					else if($string2 == "Amount of shares issued - all")
					{
						if(count($client_member_share_capital_id_info) > 0)
						{
							$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_member_share_capital_id_info[0]["amount"] + $client_transaction_member_share_capital_id_info[0]["transaction_amount"]);
						}
						else
						{
							$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_transaction_member_share_capital_id_info[0]["transaction_amount"]);
						}
						
					}
					else if($string2 == "Amount of shares paid up - all")
					{
						if(count($client_member_share_capital_id_info) > 0)
						{
							// $content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_member_share_capital_id_info[0]["number_of_shares_paid"] + $client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares_paid"]);
							$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_member_share_capital_id_info[0]["paid_up"] + $client_transaction_member_share_capital_id_info[0]["transaction_paid_up"]);
						}
						else
						{
							// $content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_transaction_member_share_capital_id_info[0]["transaction_number_of_shares_paid"]);
							$content = $get_transaction_client_member_share_capital[0]["currency_name"].number_format($client_transaction_member_share_capital_id_info[0]["transaction_paid_up"]);
						}
						
					}

        		}
        		elseif($string2 == "Transferor - share number" || $string2 == "Transferee - name"  || $string2 == "Transferee - ID" || $string2 == "Transferor - name" || $string2 == "Transferor - ID" || $string2 == "Transferee - share number" || $string2 == "Transferee - share type" || $string2 == "Transferee - currency" || $string2 == "Transferee - share amount" || $string2 == "Transferee - certificate" || $string2 == "Transferor - consideration" || $string2 == "Transferor - Address")
				{
					$replace_string = $match[$r];

					if($document_name == "Transferee-Share Cert")
					{
						$where = 'transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'" AND transaction_certificate.number_of_share > 0 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id="'.$id.'" AND transaction_member_shares.number_of_share > 0';

						$share_capital = "client_member_share_capital";
					}
					else if($document_name == "Ltrs Transfer of Shares")
					{
						$where = 'transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'" AND 0 >transaction_certificate.number_of_share where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.transaction_page_id="'.$transaction_master_id.'" AND transaction_member_shares.id="'.$id.'" AND 0 > transaction_member_shares.number_of_share';

						$share_capital = "client_member_share_capital";
					}
					else
					{
						$where = 'transaction_certificate.company_code="'.$company_code.'"AND transaction_member_shares.officer_id = transaction_certificate.officer_id AND transaction_member_shares.field_type = transaction_certificate.field_type AND transaction_certificate.transaction_page_id="'.$transaction_master_id.'" AND transaction_certificate.number_of_share > 0 where transaction_member_shares.company_code="'.$company_code.'" AND transaction_member_shares.transaction_page_id="'.$transaction_master_id.'"';

						$share_capital = "transaction_client_member_share_capital";
					}

					$get_member_shares = $this->db->query('select transaction_member_shares.*, '.$share_capital.'.class_id, '.$share_capital.'.other_class, sharetype.sharetype as class_name, officer.name, officer.identification_no, officer.postal_code1 as officer_postal_code1, officer.building_name1 as officer_building_name1, officer.street_name1 as officer_street_name1, officer.unit_no1 as officer_unit_no1, officer.unit_no2 as officer_unit_no2, officer_company.company_name, officer_company.register_no, officer_company.company_postal_code, officer_company.company_building_name, officer_company.company_street_name, officer_company.company_unit_no1, officer_company.company_unit_no2, client.company_name as client_company_name, client.registration_no, client.postal_code as client_postal_code, client.building_name as client_building_name, client.street_name as client_street_name, client.unit_no1 as client_unit_no1, client.unit_no2 as client_unit_no2, currency.currency as currency_name, transaction_certificate.new_certificate_no from transaction_member_shares left join '.$share_capital.' on '.$share_capital.'.id = transaction_member_shares.client_member_share_capital_id left join sharetype on sharetype.id = '.$share_capital.'.class_id left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client on client.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = "client" AND client.deleted != 1 left join currency on currency.id = '.$share_capital.'.currency_id left join transaction_certificate on '.$where.'');

					$get_member_shares = $get_member_shares->result_array();

					//echo json_encode($get_member_shares);

					for($i = 0; $i < count($get_member_shares); $i++)
					{
						if($get_member_shares[$i]["number_of_share"] > 0)
						{
							if($string2 == "Transferor - share number")
							{
								$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$i]["number_of_share"]))." (".number_format($get_member_shares[$i]["number_of_share"]).")";
							}

							if($string2 == "Transferee - share number")
							{
								if($document_name == "Transferee-Share Cert")
								{
									$content = strtoupper(number_format($get_member_shares[$i]["number_of_share"]));
								}
								else
								{
									$content = strtoupper($this->convert_number_to_word_model->convert_number_to_words($get_member_shares[$i]["number_of_share"]))." (".number_format($get_member_shares[$i]["number_of_share"]).")";
								}
							}

							if($string2 == "Transferee - name")
							{
								if($get_member_shares[$i]["name"] != null)
								{
									$content = $get_member_shares[$i]["name"];
								}
								else if($get_member_shares[$i]["company_name"] != null)
								{
									$content = $get_member_shares[$i]["company_name"];
								}
								else if($get_member_shares[$i]["client_company_name"] != null)
								{
									$content = $get_member_shares[$i]["client_company_name"];
								}
					
							}

							if($string2 == "Transferee - ID")
							{
								if($get_member_shares[$i]["identification_no"] != null)
								{
									$content = $get_member_shares[$i]["identification_no"];
								}
								else if($get_member_shares[$i]["register_no"] != null)
								{
									$content = $get_member_shares[$i]["register_no"];
								}
								else if($get_member_shares[$i]["registration_no"] != null)
								{
									$content = $get_member_shares[$i]["registration_no"];
								}
							}

							if($string2 == "Transferee - share type")
							{
								if($get_member_shares[$i]["class_id"] == 2)
								{
									$content = $get_member_shares[$i]["other_class"];
								}
								else
								{
									$content = $get_member_shares[$i]["class_name"];
								}
							}

							if($string2 == "Transferee - currency")
							{
								$content = $get_member_shares[$i]["currency_name"];
							}

							if($string2 == "Transferee - share amount")
							{
								$content = $get_member_shares[$i]["amount_share"];
							}

							if($string2 == "Transferee - certificate")
							{
								$content = $get_member_shares[$i]["new_certificate_no"];
							}

							
						}
						else if(0 > $get_member_shares[$i]["number_of_share"])
						{
							if($string2 == "Transferor - Address")
							{
								if($get_member_shares[$i]["officer_unit_no1"] != "" || $get_member_shares[$i]["officer_unit_no2"] != "")
								{
									$client_unit = ' #'.$get_member_shares[$i]["officer_unit_no1"] .' - '.$get_member_shares[$i]["officer_unit_no2"];
								}
								elseif($get_member_shares[$i]["company_unit_no1"] != "" || $get_member_shares[$i]["company_unit_no2"] != "")
								{
									$client_unit = ' #'.$get_member_shares[$i]["company_unit_no1"] .' - '.$get_member_shares[$i]["company_unit_no2"];
								}
								elseif($get_member_shares[$i]["client_unit_no1"] != "" || $get_member_shares[$i]["client_unit_no2"] != "")
								{
									$client_unit = ' #'.$get_member_shares[$i]["client_unit_no1"] .' - '.$get_member_shares[$i]["client_unit_no2"];
								}
								else
								{
									$client_unit = "";
								}

								if($get_member_shares[$i]["officer_street_name1"] != "")
								{
									$street_name = $get_member_shares[$i]["officer_street_name1"];
								}
								elseif($get_member_shares[$i]["company_street_name"] != "")
								{
									$street_name = $get_member_shares[$i]["company_street_name"];
								}
								elseif($get_member_shares[$i]["client_street_name"] != "")
								{
									$street_name = $get_member_shares[$i]["client_street_name"];
								}

								if($get_member_shares[$i]["officer_building_name1"] != "")
								{
									$building_name = $get_member_shares[$i]["officer_building_name1"];
								}
								elseif($get_member_shares[$i]["company_building_name"] != "")
								{
									$building_name = $get_member_shares[$i]["company_building_name"];
								}
								elseif($get_member_shares[$i]["client_building_name"] != "")
								{
									$building_name = $get_member_shares[$i]["client_building_name"];
								}

								if($get_member_shares[$i]["officer_postal_code1"] != "")
								{
									$postal_code = $get_member_shares[$i]["officer_postal_code1"];
								}
								elseif($get_member_shares[$i]["company_postal_code"] != "")
								{
									$postal_code = $get_member_shares[$i]["company_postal_code"];
								}
								elseif($get_member_shares[$i]["client_postal_code"] != "")
								{
									$postal_code = $get_member_shares[$i]["client_postal_code"];
								}

								$content = $street_name.'<br/>'.$client_unit.' '.$building_name.'<br/> SINGAPORE '.$postal_code;
							}

							if($string2 == "Transferor - name")
							{
								if($get_member_shares[$i]["name"] != null)
								{
									$content = $get_member_shares[$i]["name"];
								}
								else if($get_member_shares[$i]["company_name"] != null)
								{
									$content = $get_member_shares[$i]["company_name"];
								}
								else if($get_member_shares[$i]["client_company_name"] != null)
								{
									$content = $get_member_shares[$i]["client_company_name"];
								}
					
							}

							if($string2 == "Transferor - ID")
							{
								if($get_member_shares[$i]["identification_no"] != null)
								{
									$content = $get_member_shares[$i]["identification_no"];
								}
								else if($get_member_shares[$i]["register_no"] != null)
								{
									$content = $get_member_shares[$i]["register_no"];
								}
								else if($get_member_shares[$i]["registration_no"] != null)
								{
									$content = $get_member_shares[$i]["registration_no"];
								}
							}

							if($string2 == "Transferor - consideration")
							{
								$content = $get_member_shares[$i]["currency_name"].$get_member_shares[$i]["consideration"];
							}
						}
					}
				}
				// elseif($string2 == "Meeting Date - All"){
				// 	$replace_string = $match[$r];

				// 	$content = "";

				// 	if($document_name == "Allotment-Shorter notice of EGM" || $document_name == "DRIW-Allotment of Shares"){
				// 		$content = '___________________';
				// 	}

				// 	if($document_name == "Allotment-Attendance List" || $document_name == "Allotment-Minutes of EGM" || $document_name == "Allotment-Shorter notice of EGM" || $document_name == "DRIW-Allotment of Shares"){

				// 		$meeting_query = $this->db->query("SELECT * FROM transaction_share_allotment_date WHERE transaction_master_id=". $transaction_master_id);

				// 		if(count($meeting_query->result_array()) > 0){
				// 			$meeting_query = $meeting_query->result_array()[0];

				// 			$content = date('d F Y', strtotime(str_replace('/', '-', $meeting_query['meeting_date'])));
				// 		}
				// 	}
				// }
				elseif($string2 == "Address - new")
				{
					$replace_string = $match[$r];

					if($document_name == "Allotment-Share Cert" || $document_name == "Transferee-Share Cert" || $document_name == "Dividend voucher" || $document_name == "01 Letter of Authorisation" || $document_name == "02 Letter to IRAS for Striking Off" || $document_name == "04 DRIW-Strike-Off & EGM - Shareholder" || $document_name == "04 Strike-Off-Notice Of EGM" || $document_name == "04 Strike-Off-Minutes Of EGM" || $document_name == "04 Strike-Off-Attendance List" || $document_name == "DRIW - Appt of Co Sec")
					{
						$get_transaction_master = $this->db->query("select transaction_task_id from transaction_master where id = '".$transaction_master_id."'");

						$get_transaction_master = $get_transaction_master->result_array();

						if($get_transaction_master[0]["transaction_task_id"] == 1)
						{
							$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
						}
						else
						{
							$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from client where company_code='".$company_code."' AND client.deleted != 1");
						}

						$get_client_address = $get_client_address->result_array();

						if($get_client_address[0]["unit_no1"] != "" || $get_client_address[0]["unit_no2"] != "")
						{
							$client_unit = ' #'.$get_client_address[0]["unit_no1"] .' - '.$get_client_address[0]["unit_no2"];
						}
						else
						{
							$client_unit = "";
						}

						if($get_client_address[0]["building_name"] != "")
						{
							$building_name = ' '.$get_client_address[0]["building_name"];
						}

						if($document_name == "Dividend voucher" || $document_name == "01 Letter of Authorisation"){
							$content = ucwords(strtolower($get_client_address[0]["street_name"].', <br/>'.$client_unit.''.$building_name.', <br/> SINGAPORE '.$get_client_address[0]["postal_code"]));
						}else{
							$content = ucwords(strtolower($get_client_address[0]["street_name"].', '.$client_unit.''.$building_name.', SINGAPORE '.$get_client_address[0]["postal_code"]));
						}
					}
					else if($document_name == "DRIW-Change of Reg Ofis")
					{
						$get_client_address = $this->db->query("select transaction_change_regis_ofis_address.postal_code, transaction_change_regis_ofis_address.building_name, transaction_change_regis_ofis_address.street_name, transaction_change_regis_ofis_address.unit_no1, transaction_change_regis_ofis_address.unit_no2 from transaction_change_regis_ofis_address left join transaction_master on transaction_master.id = transaction_change_regis_ofis_address.transaction_id where transaction_master.company_code='".$company_code."' AND transaction_change_regis_ofis_address.transaction_id='".$transaction_master_id."'");

						$get_client_address = $get_client_address->result_array();

						if($get_client_address[0]["unit_no1"] != "" || $get_client_address[0]["unit_no2"] != "")
						{
							$client_unit = ' #'.$get_client_address[0]["unit_no1"] .' - '.$get_client_address[0]["unit_no2"];
						}
						else
						{
							$client_unit = "";
						}

						if($get_client_address[0]["building_name"] != "")
						{
							$building_name = ' '.$get_client_address[0]["building_name"];
						}

						$content = $get_client_address[0]["street_name"].','.$client_unit.''.$building_name.', SINGAPORE '.$get_client_address[0]["postal_code"];
					}
					else if($document_name == "Ltr - Resignation of Director" || $document_name == "DRIW-Appt of Auditor" || $document_name == "Auditor-Shorter notice of EGM" || $document_name == "Auditor-Notice of EGM" || $document_name == "Auditor-Minutes of EGM" || $document_name == "Auditor-Attendance List" || $document_name == "Company Name-Notice of EGM" || $document_name == "Company Name-Attendance List" || $document_name == "Company Name-Minutes of EGM" || $document_name == "Company Name-Shorter notice of EGM" || $document_name == "Allotment-Minutes of EGM" || $document_name == "Allotment-Authority to Allot" || $document_name == "Allotment-Authority to EGM" || $document_name == "Allotment-Attendance List" || $document_name == "Allotment-Shorter notice of EGM" || $document_name == "Allotment-Share Application Form" || $document_name == "Ltrs Transfer of Shares" || $document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM & AR - Attendance List" || $document_name == "DRIW-Change Biz Activity" || $document_name == "DRIW-Add Biz Activity" || $document_name == "DRIW-Incorp of subsidiary" || $document_name == "Subsidiary-Cert of Appt Company Representative" || $document_name == "DRIW-Issue Director Fee & EGM" || $document_name == "Director Fee-Notice of EGM" || $document_name == "Director Fee-Minutes of EGM" || $document_name == "Director Fee-Attendance List" || $document_name == "DRIW-Dividends" || $document_name == "Dividends-Notice Of EGM" || $document_name == "Dividends-Minutes Of EGM" || $document_name == "Dividends-Attendance List")
					{
						$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from client where company_code='".$company_code."' AND client.deleted != 1");

						$get_client_address = $get_client_address->result_array();

						if(!empty($get_client_address[0]["unit_no1"]) || !empty($get_client_address[0]["unit_no2"]))
						{
							$client_unit = '#'.$get_client_address[0]["unit_no1"] .' - '.$get_client_address[0]["unit_no2"];
						}
						else
						{
							$client_unit = "";
						}

						if($get_client_address[0]["building_name"] != "")
						{
							$building_name = ' '.$get_client_address[0]["building_name"];
						}

						if($document_name == "Ltr - Resignation of Director" || $document_name == "Allotment-Share Application Form" || $document_name == "Ltrs Transfer of Shares")
						{	
							if(!empty($client_unit) || !empty($building_name))
							{
								$content = $get_client_address[0]["street_name"].',<br/>'.$client_unit.''.$building_name.',<br/> SINGAPORE '.$get_client_address[0]["postal_code"];
							}
							else 
							{
								$content = $get_client_address[0]["street_name"].$client_unit.''.$building_name.',<br/> SINGAPORE '.$get_client_address[0]["postal_code"];
							}
						}
						elseif($document_name == "DRIW-Dividends")
						{
							$content = ucwords(strtolower($this->write_address($get_client_address[0]["street_name"], $get_client_address[0]["unit_no1"], $get_client_address[0]["unit_no2"], $get_client_address[0]["building_name"], $$get_client_address[0]["postal_code"], "comma")));
						}
						else
						{
							if(!empty($client_unit) || !empty($building_name))
							{
								$content = $get_client_address[0]["street_name"].', '.$client_unit.''.$building_name.', SINGAPORE '.$get_client_address[0]["postal_code"];
							}
							else 
							{
								$content = $get_client_address[0]["street_name"].$client_unit.''.$building_name.', SINGAPORE '.$get_client_address[0]["postal_code"];
							}
						}
						
					}
					else
					{
						$get_client_address = $this->db->query("select postal_code, building_name, street_name, unit_no1, unit_no2 from transaction_client where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");

						$get_client_address = $get_client_address->result_array();

						if($document_name == "Letter on appointment of Secretary" || $document_name == "Letter of Appointment" || $document_name == "Ltr - Resignation of Director" || $document_name == "CSS Proposal" || $document_name == "Letter taking over of Secretarial Services")
						{
							if($get_client_address[0]["unit_no1"] != "" || $get_client_address[0]["unit_no2"] != "")
							{
								$client_unit = '#'.$get_client_address[0]["unit_no1"] .' - '.$get_client_address[0]["unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($get_client_address[0]["building_name"] != "")
							{
								$building_name = ' '.$get_client_address[0]["building_name"];
							}
							$content = $get_client_address[0]["street_name"].',<br/>'.$client_unit.''.$building_name.',<br/> SINGAPORE '.$get_client_address[0]["postal_code"];

						}
						else
						{
							if($get_client_address[0]["unit_no1"] != "" || $get_client_address[0]["unit_no2"] != "")
							{
								$client_unit = '#'.$get_client_address[0]["unit_no1"] .' - '.$get_client_address[0]["unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($get_client_address[0]["building_name"] != "")
							{
								$building_name = ' '.$get_client_address[0]["building_name"];
							}
							$content = $get_client_address[0]["street_name"].', '.$client_unit.''.$building_name.', SINGAPORE '.$get_client_address[0]["postal_code"];
						}


					}
				}
				elseif($string2 == "Chairman")
				{
					$replace_string = $match[$r];

					if($document_name == "Auditor-Minutes of EGM" || $document_name == "Company Name-Minutes of EGM" || $document_name == "Allotment-Minutes of EGM" || $document_name == "Dividends-Minutes Of EGM" || $document_name == "Director Fee-Minutes of EGM" || $document_name == "04 Strike-Off-Minutes Of EGM")
					{
						$chairman_result = $this->db->query("select chairman from client_signing_info where company_code='".$company_code."'");
					}
					elseif($document_name == "AGM & AR - Minutes of AGM")
					{
						$chairman_result = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');
					}
					else
					{
						$chairman_result = $this->db->query("select chairman from transaction_client_signing_info where company_code='".$company_code."' AND transaction_id='".$transaction_master_id."'");
					}
                	
                	if ($chairman_result->num_rows() > 0) {

	                	$chairman_result = $chairman_result->result_array();

	                	$chairman_result_info = (explode("-",$chairman_result[0]["chairman"]));

	                	if($chairman_result_info[1] == "individual")
	                	{
	                		$officer_result = $this->db->query("select * from officer where id='".$chairman_result_info[0]."' AND field_type='".$chairman_result_info[1]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["name"];
	                	}
	                	elseif($chairman_result_info[1] == "corp_rep")
	                	{
	                		$officer_company_result = $this->db->query("select * from corporate_representative where id='".$chairman_result_info[0]."'");

	                		$officer_company_result = $officer_company_result->result_array();

	                		$name = $officer_company_result[0]["name_of_corp_rep"];
	                	}

						$content = $name;
					}
				}
				elseif($string2 == "Director Signature 1")
				{
					$content = '';
					$replace_string = $match[$r];

					if($document_name == "Allotment-Authority to Allot" || $document_name == "Auditor-Notice of EGM" || $document_name == "Dividends-Notice Of EGM" || $document_name == "Dividend voucher" || $document_name == "AGM & AR - Annual Return" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "Declaration for Controller" || $document_name == "F24 - Return of allotment of shares" || $document_name == "Director Fee-Notice of EGM")
					{
						$director_signature_1_result = $this->db->query("select director_signature_1 from client_signing_info where company_code='".$company_code."'");
					
						if ($director_signature_1_result->num_rows() > 0) {

		                	$director_signature_1_result = $director_signature_1_result->result_array();

		                	$client_officer = $this->db->query("select * from client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

	                		$client_officer = $client_officer->result_array();

	                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["name"];
		                }

		                $content = $name;
	                }
					elseif($document_name == "Letter on appointment of Secretary")
					{
						$director_signature_1_result = $this->db->query("select director_signature_1 from transaction_client_signing_info where company_code='".$company_code."'");
					

						if ($director_signature_1_result->num_rows() > 0) {

		                	$director_signature_1_result = $director_signature_1_result->result_array();

		                	$client_officer = $this->db->query("select * from transaction_client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

	                		$client_officer = $client_officer->result_array();

	                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["name"];
		                }

		                $content = $name;
	                }
				}
				elseif($string2 == "Director Signature 1's identification no")
				{
					$replace_string = $match[$r];
					$content = '';

					if($document_name == "AGM & AR - Annual Return" || $document_name == "Declaration for Controller")
					{
						$director_signature_1_result = $this->db->query("select director_signature_1 from client_signing_info where company_code='".$company_code."'");
					
						if ($director_signature_1_result->num_rows() > 0) {

		                	$director_signature_1_result = $director_signature_1_result->result_array();

		                	$client_officer = $this->db->query("select * from client_officers where id='".$director_signature_1_result[0]["director_signature_1"]."'");

	                		$client_officer = $client_officer->result_array();

	                		$officer_result = $this->db->query("select * from officer where id='".$client_officer[0]["officer_id"]."' AND field_type='".$client_officer[0]["field_type"]."'");

	                		$officer_result = $officer_result->result_array();

	                		$name = $officer_result[0]["identification_no"];
		                }

		                $content = $name;
	                }
				}
				elseif($string2 == "Year end new")
				{
					$content = "___________________";
					$replace_string = $match[$r];

					if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Minutes of AGM" || $document_name == "AGM & AR - Notice for AGM")
					{
						$agm_year_end = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

						$agm_year_end = $agm_year_end->result_array();

						if(count($agm_year_end) > 0)
						{
							$content = $agm_year_end[0]["year_end_date"];
						}
					}
					else if($document_name == "DRIW-Change of FYE")
					{
						$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);

						$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();

						if(count($get_transaction_change_fye_info) > 0)
						{
							if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
							{
								$content = $get_transaction_change_fye_info[0]["new_year_end"];
							}
						}
					}
					else
					{
						$get_year_end = $this->db->query('select year_end from transaction_filing where company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

						$get_year_end = $get_year_end->result_array();

						if(count($get_year_end) > 0)
						{
							$content = $get_year_end[0]["year_end"];
						}
						else
						{
							$content = '<span style="text-align: justify;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
						}
					}
					
				}
				elseif($string2 == "Year end new (No Year)")
				{
					$content = "___________________";
					$replace_string = $match[$r];

					if($document_name == "DRIW-Change of FYE")
					{
						$get_transaction_change_fye_info = $this->db->query("SELECT * FROM transaction_change_fye WHERE transaction_id=". $transaction_master_id);

						$get_transaction_change_fye_info = $get_transaction_change_fye_info->result_array();

						if(count($get_transaction_change_fye_info) > 0)
						{
							if(!empty($get_transaction_change_fye_info[0]["new_year_end"]))
							{
								$content = date('d F', strtotime($get_transaction_change_fye_info[0]["new_year_end"]));

							// 		$new_contents_info = str_replace($replace_string, $content, $new_contents_info);
							}
						}
					}
					else
					{
						$get_year_end = $this->db->query('select year_end from transaction_filing where company_code="'.$company_code.'" AND transaction_id="'.$transaction_master_id.'"');

						$get_year_end = $get_year_end->result_array();

						if(count($get_year_end) > 0)
						{
							$date = explode(' ', $get_year_end[0]["year_end"]);
							$day = $date[0];
							$month   = $date[1];
							$year  = $date[2];
							$content = $day.' '.$month;
						}
						else
						{
							$content = '<span style="text-align: justify;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span>';
						}
					}
				}
				elseif($string2 == 'Corporate representative')
                {
                	$replace_string = $match[$r];

                	$get_member_name = $this->db->query("select transaction_member_shares.*, z.company_name as tr_client_company_name, officer.name,officer.identification_no, officer_company.company_name, officer_company.register_no, y.company_name as client_company_name, y.registration_no as client_registration_no from transaction_member_shares left join officer on transaction_member_shares.officer_id = officer.id AND transaction_member_shares.field_type = officer.field_type left join officer_company on transaction_member_shares.officer_id = officer_company.id AND transaction_member_shares.field_type = officer_company.field_type left join client as y on y.id = transaction_member_shares.officer_id and transaction_member_shares.field_type = 'client' AND y.deleted <> 1 left join client as z on z.company_code = transaction_member_shares.company_code where transaction_member_shares.company_code='".$company_code."' AND transaction_page_id='".$transaction_master_id."' AND transaction_member_shares.id = '".$id."'");

                	$get_member_name = $get_member_name->result_array();

                	if($get_member_name[0]["company_name"] != null)
            		{
            			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$get_member_name[0]["register_no"].'" and corporate_representative.subsidiary_name = "'.$get_member_name[0]["tr_client_company_name"].'"');

            			$get_corp_rep_info = $get_corp_rep_info->result_array();

            			if($get_corp_rep_info[0]["name_of_corp_rep"] != null)
            			{
            				$content = $get_corp_rep_info[0]["name_of_corp_rep"];
            			}
            			else
            			{
            				$new_contents = str_replace('<span class="is_corp_rep">', '<span class="is_corp_rep" style="display: none;">', $new_contents);
            			}
            		}
            		else if($get_member_name[0]["client_company_name"] != null)
	                {
	                	$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$get_member_name[0]["client_registration_no"].'" and corporate_representative.subsidiary_name = "'.$get_member_name[0]["tr_client_company_name"].'"');

            			$get_corp_rep_info = $get_corp_rep_info->result_array();

            			if($get_corp_rep_info[0]["name_of_corp_rep"] != null)
            			{
            				$content = $get_corp_rep_info[0]["name_of_corp_rep"];
            			}
            			else
            			{
            				$new_contents = str_replace('<span class="is_corp_rep">', '<span class="is_corp_rep" style="display: none;">', $new_contents);
            			}
	                }
	                else
	                {
	                	$new_contents = str_replace('<span class="is_corp_rep">', '<span class="is_corp_rep" style="display: none;">', $new_contents);
	                }
                }
				elseif($string2 == 'Members name - all')
                {
                	$replace_string = $match[$r];

                	$member_name_result = $this->db->query('select member_shares.*, z.company_name as tr_client_company_name, sum(member_shares.number_of_share) as number_of_share, sum(member_shares.amount_share) as amount_share, sum(member_shares.no_of_share_paid) as no_of_share_paid, sum(member_shares.amount_paid) as amount_paid, officer.identification_no, officer.name, officer_company.register_no, officer_company.company_name, share_capital.id as share_capital_id, share_capital.class_id, share_capital.other_class, share_capital.currency_id, class.sharetype, currencies.currency, y.registration_no as client_registration_no, y.company_name as client_company_name from member_shares left join officer on member_shares.officer_id = officer.id and member_shares.field_type = officer.field_type left join officer_company on member_shares.officer_id = officer_company.id and member_shares.field_type = officer_company.field_type left join client as y on y.id = member_shares.officer_id and member_shares.field_type = "client" AND y.deleted <> 1 left join client_member_share_capital as share_capital on member_shares.client_member_share_capital_id = share_capital.id left join sharetype as class on class.id = share_capital.class_id left join currency as currencies on currencies.id = share_capital.currency_id left join client as z on z.company_code = member_shares.company_code where member_shares.company_code="'.$company_code.'" GROUP BY member_shares.field_type, member_shares.officer_id,member_shares.client_member_share_capital_id HAVING sum(member_shares.number_of_share) != 0');
                	//officer_company.company_corporate_representative, 
                	$member_name_result = $member_name_result->result_array();
                	//echo json_encode($member_name_result);

                	$each_member_name = '<table style="width: 100%; border-collapse: collapse;"><tbody>';

                	for($g = 0; $g < count($member_name_result); $g++)
                	{
                		$directors_html_string_corp_rep = "";
                		
                		if($member_name_result[$g]["name"] != null)
                		{
                			$each_member_name = $each_member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["name"].'</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';

                			//echo json_encode($each_member_nameeach_member_name);
                		}
                		else if($member_name_result[$g]["company_name"] != null)
                		{
                			// $member_name = $member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p>'.$member_name_result[$g]["company_corporate_representative"].'</p><br/><p>(Corporate Representative of '.$member_name_result[$g]["company_name"].')</p></td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
                			$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_name_result[$g]["register_no"].'" and corporate_representative.subsidiary_name = "'.$member_name_result[$g]["tr_client_company_name"].'"');

                			$get_corp_rep_info = $get_corp_rep_info->result_array();

                			for($b = 0; $b < count($get_corp_rep_info); $b++)
            				{
                				$each_member_name = $each_member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'<br/>(Corporate Representative of '.$member_name_result[$g]["company_name"].')</td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
                			}
                		}
                		else if($member_name_result[$g]["client_company_name"] != null)
		                {
		                	$get_corp_rep_info = $this->db->query('select * from corporate_representative where corporate_representative.cessation_date = "" and corporate_representative.registration_no = "'.$member_name_result[$g]["client_registration_no"].'" and corporate_representative.subsidiary_name = "'.$member_name_result[$g]["tr_client_company_name"].'"');

                			$get_corp_rep_info = $get_corp_rep_info->result_array();
                			//echo json_encode($get_corp_rep_info);
                			for($b = 0; $b < count($get_corp_rep_info); $b++)
            				{
            					$each_member_name = $each_member_name.'<tr style="height: 89px;"><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p>'.$get_corp_rep_info[$b]["name_of_corp_rep"].'<br/>(Corporate Representative of '.$member_name_result[$g]["client_company_name"].')</td><td style="width: 50%; height: 89px;"><p>&nbsp;</p><p>&nbsp;</p><p> _______________________________</p></td></tr>';
            				}
		                }
                		
                	}

                	$each_member_name = $each_member_name.'</tbody></table>';


	                if(2 > count($member_name_result))
					{
						if(strpos($new_contents, '<span class="many_member">s</span>') !== false)
	                	{
	                		$new_contents = str_replace('<span class="many_member">s</span>', '<span class="many_member" style="display: none">s</span>', $new_contents);

	                	}
	                }

                	$content = $each_member_name;

                }
                elseif($string2 == "have been registered / have not been registered / have not taken place")
				{
					$replace_string = $match[$r];

					$share_transfer = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$share_transfer = $share_transfer->result_array();

					if(count($share_transfer) > 0)
					{
						if($share_transfer[0]["agm_share_transfer_id"] == 1)
						{
							$content = "have been registered";
						}
						else if($share_transfer[0]["agm_share_transfer_id"] == 2)
						{
							$content = "have not been registered";
						}
						else if($share_transfer[0]["agm_share_transfer_id"] == 3)
						{
							$content = "have not taken place";
						}
						
					}
				}
				elseif($string2 == "the last annual return / the incorporation of the company" || $string2 == "from the date of incorporation / since the end of the previous financial year")
				{
					$replace_string = $match[$r];

					$first_agm = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$first_agm = $first_agm->result_array();

					if(count($first_agm) > 0)
					{
						if($string2 == "the last annual return / the incorporation of the company")
						{
							if($first_agm[0]["is_first_agm_id"] == 1)
							{
								$content = "the incorporation of the company";
							}
							else if($first_agm[0]["is_first_agm_id"] == 2)
							{
								$content = "the last annual return";
							}
							else
							{
								$content = "___________________";
							}
						}
						else if($string2 == "from the date of incorporation / since the end of the previous financial year")
						{
							if($first_agm[0]["is_first_agm_id"] == 1)
							{
								$content = "from the date of incorporation";
							}
							else if($first_agm[0]["is_first_agm_id"] == 2)
							{
								$content = "since the end of the previous financial year";
							}
						}
					}
				}
				elseif($string2 == "a duly audited / an unaudited profit" || $string2 == "un/audited")
				{
					$replace_string = $match[$r];

					$audited_fs = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$audited_fs = $audited_fs->result_array();

					if(count($audited_fs) > 0)
					{
						if($audited_fs[0]["audited_fs"] == 1)
						{
							if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM")
							{
								$content = "audited";
							}
							else
							{
								$content = "a duly audited";
							}
							
						}
						else if($audited_fs[0]["audited_fs"] == 2)
						{
							if($document_name == "AGM & AR - DRIW" || $document_name == "AGM & AR - Notice for AGM" || $document_name == "AGM & AR - Minutes of AGM")
							{
								$content = "unaudited";
							}
							else
							{
								$content = "an unaudited profit";
							}
						}
					}
				}
				elseif($string2 == "annual general meeting / by way of a resolution")
				{
					$replace_string = $match[$r];

					$agm_dispense = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$agm_dispense = $agm_dispense->result_array();

					if(count($agm_dispense) > 0)
					{
						if($agm_dispense[0]["agm_date"] == "dispensed")
						{
							$content = "by way of a resolution";
						}
						else if($agm_dispense[0]["agm_date"] != "dispensed")
						{
							$content = "annual general meeting";
						}
					}
				}
				elseif($string2 == "not / exempt")
				{
					$replace_string = $match[$r];

					$small_company = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$small_company = $small_company->result_array();

					if(count($small_company) > 0)
					{
						if($small_company[0]["small_company"] == 1)
						{
							$content = "exempt";
						}
						else if($small_company[0]["small_company"] == 2)
						{
							$content = "not";
						}
					}
				}
				elseif($string2 == "not / considered")
				{
					$replace_string = $match[$r];

					$small_company1 = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$small_company1 = $small_company1->result_array();

					if(count($small_company1) > 0)
					{
						if($small_company1[0]["small_company"] == 1)
						{
							$content = "considered";
						}
						else if($small_company1[0]["small_company"] == 2)
						{
							$content = "not";
						}
					}
				}
				elseif($string2 == "Resolution Date")
				{
					$content = "___________________";
					$replace_string = $match[$r];

					$reso_date = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$reso_date = $reso_date->result_array();

					if(count($reso_date) > 0)
					{
						$content = $reso_date[0]["reso_date"];

						if(empty($reso_date[0]["reso_date"]))
						{
							$content = "___________________";
						}
					}
				}
				elseif($string2 == "AGM date")
				{
					$content = "___________________";
					$replace_string = $match[$r];

					$agm_date = $this->db->query('select transaction_agm_ar.* from transaction_agm_ar left join transaction_master on transaction_master.id = transaction_agm_ar.transaction_id where transaction_master.company_code="'.$company_code.'" AND transaction_master.id="'.$transaction_master_id.'"');

					$agm_date = $agm_date->result_array();

					if(count($agm_date) > 0)
					{
						$content = $agm_date[0]["agm_date"];

						if(empty($agm_date[0]["agm_date"]) && $document_name == "AGM & AR - Notice for AGM")
						{
							$content = "___________________";
						}
					}

					if(strpos($document_name, "Attendance") !== FALSE)
					{
						$content = "";
					}
				}
				elseif($string2 == "Firm Name")
				{
					$replace_string = $match[$r];

					$query = $this->db->query("select firm.* from firm where firm.id = '".$this->session->userdata("firm_id")."'");

					$query = $query->result_array();

					$content = $query[0]['name'];
				}
				elseif($string2 == "Firm Initial Name")
				{
					$replace_string = $match[$r];

					$query = $this->db->query("select firm.* from firm where firm.id = '".$this->session->userdata("firm_id")."'");

					$query = $query->result_array();

					$firm_full_name = $query[0]['name'];
					$firm_first_name = explode(' ',trim($firm_full_name));

					$content = $firm_first_name[0];
				}
				elseif($string2 == "Firm Address")
				{
					$replace_string = $match[$r];

					$query = $this->db->query("select firm.* from firm where firm.id = '".$this->session->userdata("firm_id")."'");

					$query = $query->result_array();

					if($query[0]["unit_no1"] != "" || $query[0]["unit_no2"] != "")
					{
						$client_unit = '#'.$query[0]["unit_no1"] .' - '.$query[0]["unit_no2"];
					}
					else
					{
						$client_unit = "";
					}

					if($query[0]["building_name"] != "")
					{
						$building_name = ' '.$query[0]["building_name"];
					}

					if($document_name == "DRIW-Appt of Co Sec (Take Over)")
					{
						$content = $query[0]["street_name"].','.$client_unit.''.$building_name.', SINGAPORE '.$query[0]["postal_code"];
					}
					else
					{	
						$content = $query[0]["street_name"].',<br/>'.$client_unit.''.$building_name.',<br/> SINGAPORE '.$query[0]["postal_code"];
					}
					
				}
				elseif($string2 == "Directors' Meeting Date")
				{
					$replace_string = $match[$r];

					$content = "___________________";

					$transaction_share_allotment_date_info = $this->db->query('select transaction_share_allotment_date.* from transaction_share_allotment_date where transaction_master_id="'.$transaction_master_id.'"');

					$transaction_share_allotment_date_info = $transaction_share_allotment_date_info->result_array();

					if(count($transaction_share_allotment_date_info) > 0)
					{
						if(!empty($transaction_share_allotment_date_info[0]["director_meeting_date"]))
						{
							$content = $transaction_share_allotment_date_info[0]["director_meeting_date"];
						}
					}
				}
				elseif($string2 == "Directors' Meeting Time")
				{
					$replace_string = $match[$r];

					$content = "___________________";

					$transaction_share_allotment_date_info = $this->db->query('select transaction_share_allotment_date.* from transaction_share_allotment_date where transaction_master_id="'.$transaction_master_id.'"');

					$transaction_share_allotment_date_info = $transaction_share_allotment_date_info->result_array();

					if(count($transaction_share_allotment_date_info) > 0)
					{
						if(!empty($transaction_share_allotment_date_info[0]["director_meeting_time"]))
						{
							$content = $transaction_share_allotment_date_info[0]["director_meeting_time"];
						}
					}
				}
				elseif($string2 == "Members' Meeting Date")
				{
					$replace_string = $match[$r];

					$content = "___________________";

					$transaction_share_allotment_date_info = $this->db->query('select transaction_share_allotment_date.* from transaction_share_allotment_date where transaction_master_id="'.$transaction_master_id.'"');

					$transaction_share_allotment_date_info = $transaction_share_allotment_date_info->result_array();

					if(count($transaction_share_allotment_date_info) > 0)
					{
						if(!empty($transaction_share_allotment_date_info[0]["member_meeting_date"]))
						{
							$content = $transaction_share_allotment_date_info[0]["member_meeting_date"];
						}
					}
				}
				elseif($string2 == "Members' Meeting Time")
				{
					$replace_string = $match[$r];

					$content = "___________________";

					$transaction_share_allotment_date_info = $this->db->query('select transaction_share_allotment_date.* from transaction_share_allotment_date where transaction_master_id="'.$transaction_master_id.'"');

					$transaction_share_allotment_date_info = $transaction_share_allotment_date_info->result_array();

					if(count($transaction_share_allotment_date_info) > 0)
					{
						if(!empty($transaction_share_allotment_date_info[0]["member_meeting_time"]))
						{
							$content = $transaction_share_allotment_date_info[0]["member_meeting_time"];
						}
					}
				}
				elseif($string2 == "Meeting's Venue")
				{
					$replace_string = $match[$r];

					$content = "______________________________________________________________";

					$transaction_share_allotment_date_info = $this->db->query('select transaction_share_allotment_date.* from transaction_share_allotment_date where transaction_master_id="'.$transaction_master_id.'"');

					$transaction_share_allotment_date_info = $transaction_share_allotment_date_info->result_array();

					if(count($transaction_share_allotment_date_info) > 0)
					{
						//$content = $transaction_share_allotment_date_info[0]["member_meeting_time"];
						if($transaction_share_allotment_date_info[0]["address_type"] == "Registered Office Address")
						{
							if($transaction_share_allotment_date_info[0]["registered_unit_no1"] != "" || $transaction_share_allotment_date_info[0]["registered_unit_no2"] != "")
							{
								$client_unit = '#'.$transaction_share_allotment_date_info[0]["registered_unit_no1"] .' - '.$transaction_share_allotment_date_info[0]["registered_unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($transaction_share_allotment_date_info[0]["registered_building_name1"] != "")
							{
								$building_name = ' '.$transaction_share_allotment_date_info[0]["registered_building_name1"];
							}

							$content = $transaction_share_allotment_date_info[0]["registered_street_name1"].', '.$client_unit.''.$building_name.', SINGAPORE '.$transaction_share_allotment_date_info[0]["registered_postal_code1"];

						}
						else if($transaction_share_allotment_date_info[0]["address_type"] == "Local")
						{
							if($transaction_share_allotment_date_info[0]["unit_no1"] != "" || $transaction_share_allotment_date_info[0]["unit_no2"] != "")
							{
								$client_unit = '#'.$transaction_share_allotment_date_info[0]["unit_no1"] .' - '.$transaction_share_allotment_date_info[0]["unit_no2"];
							}
							else
							{
								$client_unit = "";
							}

							if($transaction_share_allotment_date_info[0]["building_name"] != "")
							{
								$building_name = ' '.$transaction_share_allotment_date_info[0]["building_name"];
							}


							$content = $transaction_share_allotment_date_info[0]["street_name"].', '.$client_unit.''.$building_name.', SINGAPORE '.$transaction_share_allotment_date_info[0]["postal_code"];
						}
						else if($transaction_share_allotment_date_info[0]["address_type"] == "Foreign")
						{
							$content = $transaction_share_allotment_date_info[0]["foreign_address1"].', '.$transaction_share_allotment_date_info[0]["foreign_address2"].', '.$transaction_share_allotment_date_info[0]["foreign_address3"];
						}
					}

				}
				elseif($string2 == "END OF THE RESOLUTION IN WRITING")
				{
					$replace_string = $match[$r];

					$content = "";
					
					$content = "END OF THE RESOLUTION IN WRITING";
				}

				$new_contents = str_replace($replace_string, $content, $new_contents);
				


				//echo json_encode($latest_contents);
				
			}

			return $new_contents;
		}
		else
		{
			return $new_contents;
		}
   	}

   	public function save_incorporate_pdf($data)
	{

		$this->db->insert("transaction_pending_documents",$data);
	}

	public function get_header_template($document_type, $firm_id = NULL)
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

		if($document_type == "DRIW")
		{
			return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
				<p style="text-align: center;"><span style="font-size: 10pt;">RESOLUTION IN WRITING PURSUANT TO REGULATION OF THE COMPANY&rsquo;S CONSTITUTION</span></p>
				<hr />';
		}
		elseif($document_type == "Attendance")
		{
			return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>
				<p style="text-align: center;"><span style="font-size: 10pt;"><strong>ATTENDANCE LIST</strong></span></p>';
		}
		elseif($document_type == "Company Info Header")
		{
			return '<table style="width: 100%; border-collapse: collapse; height: 80px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
					<tbody>
						<tr style="height: 80px;"><td style="width: 24.275%; text-align: left; height: 80px;" align="center">'.$firm_logo.'</td><td style="width:5px;"></td>
							<td style="width: 75.725%; height: 80px;"><span style="font-size: 14pt;"><strong>'.$firm[0]["name"].'</strong></span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $firm[0]["registration_no"] .'<br />Address: '. $firm[0]["street_name"] .', #'. $firm[0]["unit_no1"] .'-'.$firm[0]["unit_no2"].' '. $firm[0]["building_name"] .', Singapore '. $firm[0]["postal_code"] .'<br />Tel: '. $firm[0]["telephone"] .' &nbsp; Fax: '. $firm[0]["fax"] .'<br />Email: <span style="font-size: 7pt;">'. $firm[0]["email"] .'</span>&nbsp;</span></td>
						</tr>
					</tbody>
				</table>';
		}
		elseif($document_type == "headerOnly")
		{
			return '<p style="text-align: center;"><strong style="font-size: 12pt;"><span class="myclass mceNonEditable">{{Company current name}}</span><br /></strong><span style="font-size: 9pt;">(the &ldquo;Company&rdquo;)</span><br /><span style="font-size: 9pt;">(Company Registration No.: </span><span style="font-size: 9pt;"><span class="myclass mceNonEditable">{{UEN}}</span></span><span style="font-size: 9pt;">)</span><br /><span style="font-size: 9pt;">(Incorporated in the Republic of Singapore)</span></p>';
		}
	}

	public function end_of_resol_page_break($obj_pdf, $content)
	{
		// write content and page break
		if (($pos = strpos($content, "END OF THE RESOLUTION IN WRITING")) !== FALSE) { 
			$resol_content = substr($content, 0, $pos + 33); 

			$obj_pdf->writeHTML($resol_content, true, false, false, false, '');
			$obj_pdf->AddPage();

			return substr($content, $pos + strlen('END OF THE RESOLUTION IN WRITING'));
		}
		return $content;
	}

	public function break_page($obj_pdf, $content)
	{
		$pattern = "/{{[^}}]*}}/";
		$subject = $content;
		preg_match_all($pattern, $subject, $matches);
		
		for($r = 0; $r < count($matches[0]); $r++)
		{
			// write content and page break
			if (($pos = strpos($content, "{{break_page}}")) !== FALSE) { 
				$prev_content = substr($content, 0, $pos); 

				$obj_pdf->writeHTML($prev_content, true, false, false, false, '');
				$obj_pdf->AddPage();

				$content =  substr($content, $pos + 14);
			}
		}

		return $content;
	}

	// this is will removed after all address have use the write_address_local_foreign function.
	public function write_address($street_name, $unit_no1, $unit_no2, $building_name, $postal_code, $type)
	{
		// echo $street_name . ", " . $unit_no1 . ", " . $unit_no2 . ", " . $building_name . ", " . $postal_code . ", " . $type;

		$unit = '';
		$unit_building_name = '';

		if($type == "normal")
		{
			$br1 = '';
			$br2 = '';
		}
		elseif($type == "letter")
		{
			$br1 = ' <br/>';
			$br2 = ' <br/>';
		}
		elseif($type == "comma")
		{
			$br1 = ', ';
			$br2 = ', ';
		}

		// Add unit
		if(!empty($unit_no1) && !empty($unit_no2))
		{
			$unit = '#' . $unit_no1 . '-' . $unit_no2;
		}
		else
		{
			if($type != "letter")
			{
				$br2 = '';
			}
		}

		// Add building
		if(!empty($building_name) && !empty($unit))
		{
			$unit_building_name = $unit . ' ' . $building_name;
		}
		elseif(!empty($unit))
		{
			$unit_building_name = $unit;
		}
		elseif(!empty($building_name))
		{
			$unit_building_name = $building_name;
		}

		return $street_name . $br1 . $unit_building_name . $br2 . 'Singapore ' . $postal_code;
	}

	// latest version include foreign address. 
	public function write_address_local_foreign($address, $type)
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
			$br1 = ' <br/>';
			$br2 = ' <br/>';
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
			else
			{
				if($type != "letter")
				{
					$br2 = '';
				}
			}

			// Add building
			if(!empty($address['building_name1']) && !empty($unit))
			{
				$unit_building_name = $unit . ' ' . $address['building_name1'];
			}
			elseif(!empty($unit))
			{
				$unit_building_name = $unit;
			}
			elseif(!empty($address['building_name1']))
			{
				$unit_building_name = $address['building_name1'];
			}

			return $address['street_name1'] . $br1 . $unit_building_name . $br2 . 'Singapore ' . $address['postal_code1'];
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

	public function write_list_number($content)
	{
		// echo $content;
		$temp = '';
    	$pattern = "/{{[^}}]*}}/";
		$subject = $content;
		preg_match_all($pattern, $subject, $matches_no);
		$counter = 1;

		for($c = 0; $c < count($matches_no[0]); $c++)
		{
			$string1 = (str_replace('{{', '',$matches_no[0][$c]));
			$string2 = (str_replace('}}', '',$string1));

			$replace_string = $matches_no[0][$c];

			// echo $replace_string;
			if($string2 == "no")
			{	
				$temp = $counter . '.';
				$content = preg_replace('/{{no}}/', $temp, $content, 1);

				$counter++;
			}
		}

		return $content;
	}

	public function replace_verbs_plural($new_contents, $count_length) 
	{
		// echo json_encode((int)$count_length > 1);

		$pattern = "/{_[^}}]*_}/";
		preg_match_all($pattern, $new_contents, $match);

		$isPlural = $count_length > 1? true: false;

		if(count($match[0]) != 0)
   		{
   			// echo json_encode(count($count_length) > 1);
	   		for($r = 0; $r < count($match[0]); $r++)
			{
				$string1 = (str_replace('{_', '',$match[0][$r]));
				$string2 = (str_replace('_}', '',$string1));

				$content = '';

				if($string2 == "sing/plu s")	// eg. director(s)
				{
					$replace_string = $match[0][$r];

					if($isPlural)
					{
						$content = "s";
					}
				}
				elseif($string2 == "sing/plu s'") // eg. director(s')
				{
					$replace_string = $match[0][$r];
					
					if($isPlural)
					{
						$content = "s";
					}
					else
					{
						$content = "'s";
					}
				}
				elseif($string2 == "tense's")	// eg. director('s)
				{
					$replace_string = $match[0][$r];

					if(!$isPlural)
					{
						$content = "s";
					}
				}
				elseif($string2 == "is/are")
				{
					$replace_string = $match[0][$r];

					if(!$isPlural)
					{
						$content = "is";
					}
					else
					{
						$content = "are";
					}
				}

				$new_contents = str_replace($replace_string, $content, $new_contents);
			}

			return $new_contents;
		}
		else 
		{
			return $new_contents;
		}
	}
}



class MYPDF extends TCPDF {
	public function Header() {
		$headerData = $this->getHeaderData();
		//$this->SetY(0);
        //$this->SetFont('helvetica', '', 17);
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);

        // $document_name = '';
   }

   public function Footer() {
		// Position at 25 mm from bottom
        $this->SetY(-20);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, SINGAPORE 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Page number
		// $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().' of '.$this->getAliasNbPages(), 0, 0, 'R');
		$this->Ln();

		$logoX = 133; // 186mm. The logo will be displayed on the right side close to the border of the page
		$logoFileName = base_url()."/img/footer_img.png";
		$logoWidth = 50; // 15mm
		$logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

		//$this->SetX($this->w - $this->documentRightMargin - $logoWidth); // documentRightMargin = 18
		//$this->Cell(0,0, $logo, 0, 0, 'R');

   }
   
}

class ENGAGEMENT_PDF extends TCPDF {
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

		$logoX = 133; // 186mm. The logo will be displayed on the right side close to the border of the page
		$logoFileName = base_url()."/img/footer_img.png";
		$logoWidth = 50; // 15mm
		$logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
   }
   
}

class NEWPDF extends TCPDF {

	public function Footer() {
		// Position at 25 mm from bottom
        $this->SetY(-20);
        // $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, Singapore 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Page number
		$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		// $this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().' of '.$this->getAliasNbPages(), 0, 0, 'R');
		// //$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, 'T', 'C');
		// $this->Ln();

		// $logoX = 133; // 186mm. The logo will be displayed on the right side close to the border of the page
		// $logoFileName = base_url()."/img/footer_img.png";
		// $logoWidth = 50; // 15mm
		// $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

		//$this->SetX($this->w - $this->documentRightMargin - $logoWidth); // documentRightMargin = 18
		//$this->Cell(0,0, $logo, 0, 0, 'R');

   }
}

class DRIW_PDF extends TCPDF {
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

	public function Header() {
		$headerData = $this->getHeaderData();
		//$this->SetY(0);
        //$this->SetFont('helvetica', '', 17);
        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);

        // $document_name = '';
   }

   public function Footer() {
		// Position at 25 mm from bottom
        $this->SetY(-20);
        $this->SetX(10);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        
        // $this->Cell(0, 0, '53 Ubi Ave 3 #01-01 Travelite Building, SINGAPORE 408863', 0, 0, 'C');
        // $this->Ln();
        // $this->Cell(0, 0,'Tel:(65) 6785 8000      Fax:(65) 6785 7000      Email: jonfresh@singnet.com.sg', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        // Page number
		// $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		// $this->Cell(0, 0, $this->getAliasRightShift().'Page '.$this->PageNo().' of '.$this->getAliasNbPages(), 0, 0, 'R');
		// //$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, 0, 'R', 0, '', 0, false, 'T', 'C');
		// $this->Ln();

		// $logoX = 133; // 186mm. The logo will be displayed on the right side close to the border of the page
		// $logoFileName = base_url()."/img/footer_img.png";
		// $logoWidth = 50; // 15mm
		// $logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);

		//$this->SetX($this->w - $this->documentRightMargin - $logoWidth); // documentRightMargin = 18
		//$this->Cell(0,0, $logo, 0, 0, 'R');

		// Page number
        if (empty($this->pagegroups)) {
            $pagenumtxt = 'Page '.' '.$this->getAliasNumPage().'of'.$this->getAliasNbPages();
        } else {
            $pagenumtxt = 'Page '.' '.$this->getPageNumGroupAlias().'of'.$this->getPageGroupAlias();
        }

        // if(!$this->one_page_only){
        // 	// $this->SetFont('helvetica', '', 8);
        // 	// $this->Cell(0, 10, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // }
        
        if(!$this->one_page_only){
	        $this->SetFont('helvetica', 'I', 8);
	        // Page number
			$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
			$this->Ln();
        }

        $this->total_page++;

   }
   
}

