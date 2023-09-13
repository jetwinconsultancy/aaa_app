<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// header("Content-type:application/pdf");

// include('vendor/tecnickcom/tcpdf/tcpdf.php');
require_once('assets/vendor/tcpdf/tcpdf.php');


class CreateEmploymentContractPdf extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	public function index()
	{
		// $this->load->helper('pdf_helper'); 
	}

	public function create_document_pdf($data)
	{
		$document_id = [1];

		//echo json_encode($document_id);
		$array_link = [];
		if(count($document_id) != 0)
		{
			for($i = 0; $i < count($document_id); $i++)
			{
		        $q = $this->db->query("select * from payroll_pending_documents where id = '".$document_id[$i]."'");
				
		       	$q = $q->result_array();

		       	$query = $this->db->query("SELECT * from firm 
											LEFT JOIN firm_telephone ON firm.id = firm_telephone.firm_id AND firm_telephone.primary_telephone = 1 
											LEFT JOIN firm_fax ON firm.id = firm_fax.firm_id AND firm_fax.primary_fax = 1 
											LEFT JOIN firm_email ON firm.id = firm_email.firm_id AND firm_email.primary_email = 1 
											WHERE firm.id ='".$data['firm_id']."'");

		       	$query = $query->result_array();

		       	// $this->load->helper('pdf_helper');

	    		// create new PDF document
			    $obj_pdf = new MYPDF_OL(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Employment Letter";
				$obj_pdf->SetTitle($title);
				$obj_pdf->setPrintHeader(true);
		  		
				//$obj_pdf->setPrintFooter(false);
				/*$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));*/
				$obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs='
					<table style="width: 100%; border-collapse: collapse; height: 95px; font-family: arial, helvetica, sans-serif; font-size: 10pt" border="0">
						<tbody>
							<tr style="height: 95px;">
								<td style="width: 18%; height: 95px;" align="center"><img src="/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="60" /></td>
								<td style="width: 80%; height: 95px;">
								<span style="font-size: 14pt;"><strong>'.$query[0]["name"].'</strong></span><br />
								<span style="font-size: 8pt; text-align:left;">'. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'<br />Email: <span style="font-size:7pt;">'. $query[0]["email"] .'<br />Website:'. $query[0]["url"] .'</span>&nbsp;</span></td>
							</tr>
						</tbody>
					</table>', 
					$tc=array(0,0,0), $lc=array(0,0,0));

				$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				// $obj_pdf->SetMargins(43, 44, PDF_MARGIN_RIGHT);
				$obj_pdf->SetMargins(43, 40, PDF_MARGIN_RIGHT);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);
				//$obj_pdf->setPrintFooter(false);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->startPageGroup();
				$obj_pdf->setListIndentWidth(4);
				$obj_pdf->AddPage();

				//-----------------------------------API-------------------------------------
				// $ch = curl_init();
				// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				// curl_setopt($ch, CURLOPT_URL, 'http://192.168.1.100/payroll/offer_letter/info');
				// $result = curl_exec($ch);
				// curl_close($ch);

				$employment_contract_info = $data;

				// echo json_encode($data);
				//------------------------------------------------------------------------

				$document_content = $q[0]["template"];
				if(strpos($document_content, '{{company_name}}') !== false)
            	{
            		$document_content = str_replace('{{company_name}}', $employment_contract_info['firm'], $document_content);
            	}
            	if(strpos($document_content, '{{employee_name}}') !== false)
            	{
            		$document_content = str_replace('{{employee_name}}', $employment_contract_info['name'], $document_content);
            	}
            	if(strpos($document_content, '{{identification_no}}') !== false)
            	{
            		$document_content = str_replace('{{identification_no}}', $employment_contract_info['ic_passport_no'], $document_content);
            	}
            	if(strpos($document_content, '{{effective_date}}') !== false)
            	{
            		$document_content = str_replace('{{effective_date}}', date('d F Y', strtotime($employment_contract_info['effective_from'])), $document_content);
            	}
            	if(strpos($document_content, '<span class="is_singaporean">') !== false)
            	{
            		if($employment_contract_info['is_pr_singaporean'] == 1)
            		{
            			$document_content = str_replace('<span class="is_singaporean">', '.<span class="is_singaporean" style="display: none;">', $document_content);
            		}
            	}
            	if($employment_contract_info['is_employee'] == false)
            	{
            		
            		$document_content = str_replace('<li class="old_employee">', '<li class="old_employee" style="display: none;">', $document_content);
            	}
            	elseif($employment_contract_info['is_employee'] == true)
            	{
            		$document_content = str_replace('<li class="new_employee">', '<li class="new_employee" style="display: none;">', $document_content);
            	}

            	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            	if(strpos($document_content, '{{probation_period}}') !== false)
            	{
            		$document_content = str_replace('{{probation_period}}', $f->format($employment_contract_info['probationary_period']).' ('.$employment_contract_info['probationary_period'].')', $document_content);
            	}
            	if(strpos($document_content, '{{working_start_time}}') !== false)
            	{
            		$document_content = str_replace('{{working_start_time}}', $employment_contract_info['working_hour_time_start'], $document_content);
            	}
            	if(strpos($document_content, '{{working_end_time}}') !== false)
            	{
            		$document_content = str_replace('{{working_end_time}}', $employment_contract_info['working_hour_time_end'], $document_content);
            	}
            	if(strpos($document_content, '{{working_start_day}}') !== false)
            	{
            		$document_content = str_replace('{{working_start_day}}', $employment_contract_info['working_hour_day_start'], $document_content);
            	}
            	if(strpos($document_content, '{{working_end_day}}') !== false)
            	{
            		$document_content = str_replace('{{working_end_day}}', $employment_contract_info['working_hour_day_end'], $document_content);
            	}
            	if(strpos($document_content, '{{salary}}') !== false)
            	{
            		$document_content = str_replace('{{salary}}', $employment_contract_info['given_salary'], $document_content);
            	}
            	if(strpos($document_content, '{{terminar_notice}}') !== false)
            	{
            		$document_content = str_replace('{{terminar_notice}}', $f->format($employment_contract_info['termination_notice']).' ('.$employment_contract_info['termination_notice'].')', $document_content);
            	}
            	if(strpos($document_content, '{{employer_name}}') !== false)
            	{
            		$document_content = str_replace('{{employer_name}}', $employment_contract_info['employer'], $document_content);
            	}

				$content = $document_content;
				$obj_pdf->writeHTML($content, true, false, false, false, '');

				// $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'payroll/pdf/employement_letter/'.$q[0]["document_name"].'.pdf', 'I');

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf', 'F');

				chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf',0644);

                // $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

                $link = 'http://'. $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf';
                // $link = 'https://'. $_SERVER['SERVER_NAME'] .'/payroll/pdf/employment_letter/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] . ').pdf';

                $data = array('status'=>'success', 'pdf_link'=>$link, 'path'=> '/pdf/document/' . $q[0]["document_name"] . '(' . $employment_contract_info['name'] .').pdf');

                return json_encode($data);
			}
		}
	}

	public function create_employment_contract_pdf($data)
	{
        $array_link = [];
        $obj_pdf= new MYPDF_OL(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $obj_pdf->SetCreator(PDF_CREATOR);
        $title  = "Employment Contract";
        $obj_pdf->SetTitle($title);
        $obj_pdf->SetDefaultMonospacedFont('helvetica');
        $obj_pdf->SetHeaderMargin(10);
        // $obj_pdf->SetFooterMargin(5);
        $obj_pdf->SetMargins(20, 40, 15);
        $obj_pdf->SetFont('helvetica', '', 10);
        $obj_pdf->setFontSubsetting(false);
        $header_company_info = $this->write_header($data['firm_id']);
        $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_company_info,$tc=array(0,0,0), $lc=array(0,0,0));
        

        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: right;"><strong>PRIVATE AND CONFIDENTIAL</strong></p>
		<p>Date:'.$data['date_of_offer'].'</p>
		<p>'.$data['name'].' (NRIC/Passport: '.$data['nric/passport'].')</p>
		<p style="text-align: left;"><strong>By Hand</strong></p>
		<p><strong><u>EMPLOYMENT CONTRACT</u></strong></p>
		<p style="text-align: justify;">We are pleased to offer you employment with the Company on the terms and conditions set out in this contract. This contract will supersede any previous contract(s) with you, if any.</p>
		<p style="text-align: justify;">This Contract sets out the main terms and conditions of employment between you and <strong>ACUMEN ALPHA ADVISORY GROUP</strong> (referred to as &ldquo;<strong>THE COMPANY&rdquo;</strong> and the Company throughout this Contract). The content of this Agreement may need to change over time according to the needs of the business. Although you would be fully consulted if this situation arose, the Company would not expect you to unreasonably withhold your consent to any changes.</p>
		<p style="text-align: justify;">For avoidance of doubt, Acumen Alpha Advisory Group refers to one or more of the companies below:</p>

		<p style="text-align: justify;">1. Acumen Alpha Advisory Pte. Ltd.<br />2. SYA PAC<br />3. Acumen Bizcorp Pte. Ltd.<br />4. Alpha Corporate Services Pte. Ltd<br />5. Acumen Alpha Advisory Sdn. Bhd.</p>

		<p style="text-align: justify;">This contract expands upon the points detailed in your Principal Statement and provides further information regarding your terms and conditions of employment. The Company values the contribution of every employee and expects all employees to promote the interests of the Company and devote the whole of their working time and attention to the business of The Company.</p>
		<p style="text-align: justify;">Wherever there is a text reference to &lsquo;your Manager&rsquo; it means the person to whom you report, whatever the person&rsquo;s actual title may be. This may include a Director or the Board of Directors.</p>
		<p style="text-align: justify;"><strong>1. PRE-EMPLOYMENT</strong></p>
		<p style="text-align: justify;"><em>1.1 Pre-Employment Medical &amp; Questionnaire</em></p>
		<p style="text-align: justify;">The Company may ask you to complete a medical questionnaire and if necessary, attend a Pre-Employment Medical. This would be a standard medical carried out by a Doctor approved by the Company to ensure that you are fit to take on the responsibilities of your job. If so, your employment with The Company will be conditional upon a satisfactory report being received from the Doctor.</p>
		<p style="text-align: justify;"><em>1.2 Warranty</em></p>
		<p style="text-align: justify;">By entering into this Agreement, you confirm that you will not be in breach of any other contract held with a third party, including any previous employer. If you are subsequently found to be in breach of another contract, the Company reserves the right, during the employment, to terminate this Agreement without notice and without a payment in lieu of notice.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><em>1.3 References</em></p>
		<p style="text-align: justify;">The Company may require you to provide references, one of which should be from your previous employer. Any offer of employment with the Company is conditional upon the references received being satisfactory. If you have already started work with the Company and references cannot be obtained or they are unsatisfactory, the Company may need to give you notice to terminate your employment as detailed in your Principal Statement.</p>
		<p style="text-align: justify;"><em>1.4 Residential</em></p>
		<p style="text-align: justify;">The Company discourages daily commuting outside Singapore as it will cause exhaustion to the employee and will result in lack of focus and energy. Therefore, employee is required to reside in Singapore during the employment term in Singapore unless other work is agreed.</p>
		<p style="text-align: justify;"><strong>2. CONDITIONS OF SERVICE</strong></p>
		<p style="text-align: justify;"><em>2.1 Probationary Period</em></p>
		<p style="text-align: justify;">All new appointments remain subject to satisfactory performance during the probationary period agreed in Principal Statement, save candidates that select to take up the period of bond with the Company of which there is NO probationary period. If during this period either party wishes to terminate this Agreement, notice may be given as detailed in your Principal Statement. Alternatively, the Company may extend your probationary period and, in this event, your notice period (and any relevant benefits) will be as those detailed for the probationary period.</p>
		<p style="text-align: justify;">Your probationary period will automatically be extended to cover any holiday leave that is taken within your probationary period. Extensions to your probationary period for any other reasons will be detailed to you, in writing, before the end of the probation period.</p>
		<p style="text-align: justify;"><em>2.2 Location of Work and Mobility</em></p>
		<p style="text-align: justify;">Your main place of work is that detailed in your Principal Statement. This however, may be subject to change, according to the commercial needs of the business. In the event that the Company needs to move to alternative premises, you would be expected to make yourself available at any new location.</p>
		<p style="text-align: justify;"><em>2.3 Travel on Company Business</em></p>
		<p style="text-align: justify;">Due to the nature of your position within the Company, you may on occasions be required to travel domestically and internationally to discharge your duties, attend meetings and training courses as required by the business.</p>
		<p style="text-align: justify;"><em>2.4 Hours of Work</em></p>
		<p style="text-align: justify;">Your official hours of work are detailed in your Principal Statement. The Company may designate when you are able to take your lunch break each day. Due to the nature of your position with the Company you may be asked to work any additional hours that are reasonably required to fulfil the responsibilities of your job.</p>
		<p style="text-align: justify;"><em>2.5 Changes to Working Hours</em></p>
		<p style="text-align: justify;">It may be necessary to change your working hours, on either a temporary or permanent basis, in order to meet the commercial needs of the business. Notice will be given to you regarding any changes in working hours and you would be required to oblige. Permanent changes to working hours will only be introduced after full consultation, and you would be expected to co-operate and not to unreasonably withhold your consent to any changes.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><em>2.6 Time Recording and Attendance</em></p>
		<p style="text-align: justify;">An accurate record of your attendance at work is important not only for payment purposes but also for reasons of safety, security and professional growth. You would be required to adhere to any procedures introduced or are currently in operation for recording of hours of work.</p>
		<p style="text-align: justify;"><em>2.7 Dress Code</em></p>
		<p style="text-align: justify;">You are required to follow professional dress attire from Monday to Thursday. Men are expected to wear formal long sleeve shirt with tie whereas women are expected to put on formal shirt and pantsuit or skirt. On Friday, you may wear business casual attire save when you are visiting the client where you are expected to dress in a professional manner.</p>
		<p style="text-align: justify;">You are also expected to wear business shoe from Monday to Thursday regardless of gender, whereas on Friday you are permitted to wear casual shoe. Slipper is strictly prohibited from Monday to Friday.</p>
		<p style="text-align: justify;"><em>2.8 Notice Period</em></p>
		<p style="text-align: justify;">The notice period that either party is required to give the other, in respect to the termination of your employment under this Agreement is as detailed in the Principal Statement. The day on which the notice is given shall be included in the notice period. the Company reserves the right to transfer you to alternative work for the duration of your notice period if this is considered appropriate to satisfy the needs or protect the interests of the business. You will not be entitled to receive notice of termination, or a payment in lieu, if you are dismissed for gross misconduct.</p>
		<p style="text-align: justify;"><em>2.9 Garden Leave</em></p>
		<p style="text-align: justify;">The Company reserves the right to place you on Garden Leave (requiring you to remain at home but to be available to work for the duration of your notice period). You are also bound by the terms of this Agreement in all other respects for the duration of the notice period. During any period of Garden Leave, the Company does not guarantee to provide you with work. During any period of Garden Leave, you may not enter into employment, service agreements or assignments (whether paid or unpaid) with another company. You will receive the same salary and benefits whilst on Garden Leave as if you remained at work, unless there is an expressed provision dealing with salary and benefits in this Agreement that excludes you the right to receive such salary and benefits.</p>
		<p style="text-align: justify;">Any refusal to comply will result in the Company seeking an injunction with the Court to be placed onto you.</p>

		<p style="text-align: justify;"><em>2.10 Termination of Employment without Notice </em></p>
		<p style="text-align: justify;">Either party may at any time, terminate the contract of service without giving notice; or if notice has already been given, without waiting for the expiry of that notice, by paying to the other party a sum equal to the amount of salary at the gross rate of pay which would have accrued to the employee during the period of notice for employee not serving the bond. In the case of employee serving the bond, the employee who has not completed the service of the period of bond shall compensate the employer the amount equivalent to additional salaries that have been paid to the employee throughout the bond period that are not completed.</p>
		<p style="text-align: justify;">In the case of a monthly-rated salary where the notice period is less than a month, the amount payable for any one day shall be the gross rate of pay for one day’s work. By mutual consent between you and the Company, the notice period can be waived.</p>';

        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;">For Singaporeans and Singapore Permanent Residents, salary-in-lieu of notice will not be subjected to provident funds contribution (CPF) if the notice period is waived by mutual consent.</p>
        <p style="text-align: justify;">The Company reserves the right to terminate your employment without giving notice, and you agree to pay the Company salary-in-lieu of notice if your employment is terminated if:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">You are absent for work continuously for more than two (2) working days without approval or good excuse;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">You are absent for work continuously for more than two (2) working days without informing or attempting to inform the employer of the reason for absence.</td>
		</tr>
		</tbody>
		</table>
		<p><em>2.11 Termination of Employment with Notice</em></p>
		<p style="text-align: justify;">Either party may at any time, terminate the contract of service. The party who intends to terminate the contract shall give notice to the other party in writing. For Singaporeans and Singapore Permanent Residents, the salary for the notice period will still be subjected to CPF contributions made by you and the Company.</p>
		<p style="text-align: justify;">The employee who has not completed the service of the period of bond shall compensate the employer the amount equivalent to additional salaries that have been paid to the employee throughout the bond period that are not completed.</p>
		<p style="text-align: justify;"><u>Offsetting of annual leave</u><br>Any unconsumed annual leave cannot be used to offset the notice period for the termination of this contract, and you would only be paid till your last day of work and the annual leave would not be available for encashment.</p>
		<p style="text-align: justify;"><u>Taking sick leave during notice period</u><br>Any sick leave, (whether paid or unpaid) taken during the notice period will be treated as part of the notice period.</p>
		<p style="text-align: justify;"><u>Starting work with new employer while serving notice of termination with the Company</u><br>You will still be considered an employee of the Company while serving notice. You are not allowed to work with another employer before the date of termination, unless there is written permission from the Company allowing you to do so.</p>
		<p style="text-align: justify;"><u>Using reservist period as notice of termination</u><br>This applies only to Singaporeans. You are not allowed to use reservist training to offset the notice period, unless it is mutually agreed between yourself and the Company.</p>

		<p><em>2.12 Dismissal</em></p>
		<p style="text-align: justify;">The Company may terminate the services of an employee without giving any notice, salary in lieu of notice of notice or any other benefits payment if the employee is guilty of:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Misconduct;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Breach of confidentiality;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Negligence or conduct prejudicial to the interest of the Company;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Criminal acts either on or outside of the Company&rsquo;s premises;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Harassment of Managers, co-workers, or their families;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Non-compliance with the instructions or regulations imposed by the Company or any of the terms and conditions of employment;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Absence from work for more than 2 working days without proper authorization;</td>
		</tr>
		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');

        $content = '';
        $obj_pdf->AddPage();
        $page = '
        <table>
		<tbody>
        <tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Unreasonably poor performance despite counsels have been given;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Any other events separately indicated in this employment contract.</td>
		</tr>
		</tbody>
		</table>
        <p style="text-align: justify;">Misconduct generally refers to failing to fulfil the expressed or implied conditions of employment, including breach of duty, theft, dishonesty, immoral conduct at work, wilful insubordination). The total salary shall be paid on the day of dismissal or within 3 working days thereafter.</p>
		<p style="text-align: justify;">The employee who is guilty for misconduct and has not completed the service of the period of bond shall compensate the employer the amount equivalent to additional salaries that have been paid to the employee throughout the bond period that are not completed.</p>
        <p style="text-align: justify;"><em>2.13 Retirement</em></p>
		<p style="text-align: justify;">The Company may offer re-employment to Singaporean and Singapore Permanent Resident employees aged 62 up to 65, with at least a one year renewal contract. In order to be eligible for re-employment, you will be required to be medically fit to continue your employment with the Company, on top of having satisfactory work performances.</p>
		<p style="text-align: justify;"><em>2.14 Retrenchment</em></p>
		<p style="text-align: justify;">The notice period for retrenchment works the same way as prescribed under paragraph 2.11 [Termination of Employment with Notice]. You will be entitled to retrenchment benefits if you have been employed in the Company for at least <strong>Five (5) years</strong> and the Company reserves the right to specify the quantum of your retrenchment benefits. Any retrenchment benefits and additional (ex-gratia) payments are not subject to CPF contributions.</p>
		<p style="text-align: justify;"><em>2.15 Property of the Company</em></p>
		<p style="text-align: justify;">Property of the Company includes the computer, data, files, information among others must be used for business purpose and strictly not for personal use. Each and every property of the Company are to be used for business purposes and/or for communicating with staff, clients and other third parties on professional basis. You may access the Internet and email systems to send or receive small number of personal mails but you should do so, as far as possible, outside normal business hours.</p>
		<p style="text-align: justify;">The Company does not tolerate the use of email or the internet for accessing, receiving or distributing material that is sexist, pornographic, culturally insensitive, racist or otherwise offensive. If you breach this requirement or misuse the email system (for example, by tampering or introducing viruses), the Company may take disciplinary action against you, including removal of your e-mail or internet access rights, or termination of your employment or getting court order for you to indemnify loss caused to the Company as result of your action.</p>
		<p style="text-align: justify;">From time to time the Company may monitor the content of your emails and your use of the internet, including personal emails to ensure that you do not breach these provisions. The Company may also filter and bock offensive emails or attachments. In addition to this, Company may also monitor the data stored in equipment supplied to you for your work purposes by the Company.</p>
		<p style="text-align: justify;">Upon the termination of the employment employee shall return to the employer all documents, records, items, materials and equipment in the possession or custody of employee belonging to the employer or its clients and employee shall not retain any copies (including electronic or soft copies) thereof.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><strong>3. COMPENSATION</strong></p>
		<p style="text-align: justify;"><em>3.1 Remuneration</em></p>
		<p style="text-align: justify;">You will be paid at the amount stated in your Principal Statement. Your monthly remuneration has <em>two</em> components, that is your fixed salary and productivity allowance. The cap of the productivity allowance is stated in the Principal Statement.</p>
		<p style="text-align: justify;">You will be notified, on your monthly payslip, of your gross and net salary and the nature and amount of any deductions. The Company reserves the right to alter the method or intervals of payment.</p>
		<p style="text-align: justify;">Your salary will be paid to you in end of month, in arrears each month by electronic transfer directly into your nominated bank account or cheque payment.</p>
		<p style="text-align: justify;">The Company reserves the right to make your final salary payment by cheque on your last working day and subject to the return of any property owned by the Company.</p>
		<p style="text-align: justify;"><em>3.2 Computation of Salary for an Incomplete Month&rsquo;s Work</em></p>
		<p style="text-align: justify;">For the purpose of calculating salary, a &lsquo;month&rsquo; or &lsquo;complete month&rsquo; refers to any one of the months in the calendar year. An incomplete month of work is one where an employee starts work after the first day of the month; or leaves employment before the last day of the month; or takes no-pay leave of one day or more during the month; or is on reservist training during the month.</p>
		<p style="text-align: justify;">Salary payable on a monthly basis is calculated using the monthly gross of pay pro-rated by the total number of days the employee actually worked in that month. The monthly gross pay includes allowances payable to an employee, <u>excluding</u> bonus payments, any sum paid to you for any reimbursement of special expenses incurred, productivity incentive payments and travelling, food or housing allowances.</p>
		<p style="text-align: justify;"><em>3.3 Variable Bonus Scheme</em></p>
		<p style="text-align: justify;">From time to time, the Company may operate a discretionary (non-contractual) bonus scheme. Payments from any schemes in the Company are based on the Company achieving its targeted profits and you achieving any personal targets or objectives as set by the Company. All schemes are subject to change or withdrawal by the Company without notice or compensation. If you are to receive a bonus, it will generally be paid to you with your salary, one month following the calendar half-yearly end (e.g. July and December). In order to receive a payment under the Bonus Scheme, you must be employed by the Company and not have tendered your resignation on the date on which bonus payments are made. Full details of any bonus scheme that may be relevant to your job role or project objective will be issued to you separately.</p>
		<p style="text-align: justify;"><em>3.4 Deductions from Salary</em></p>
		<p style="text-align: justify;">The Company reserves the right to make deductions from your salary for any monies owed to the Company. In the event of termination however, all monies will become immediately payable to the Company. The Company reserves the right to make a deduction from your final payment for any sums that are due at your time of leaving. The following deductions may be made:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for absence from work;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for damage to or loss of goods expressly entrusted to you for custody or for loss of money for which you are required to account, where the damage or loss is directly attributable to your neglect or default;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for the actual cost of meals supplied by the Company on your request;</td>
		</tr>
		</tbody>
		</table>
		';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for any housing accommodation supplied by the Company;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for such amenities and services supplied by the Company;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions for the recovery of advances or loans or for the adjustment of over-payments of salary;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%;text-align: justify;">Deductions of contributions payable by the Company on your behalf under the provisions of the Central Provident Fund Act (Cap. 36);</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Deductions made with the written consent of the employee and paid by the employer to any cooperative society registered under any written law for the time being in force in respect of subscriptions, entrance fees, instalments of loans, interest and other dues payable by the employee to such society;</p>
        <p style="text-align: justify;"><em>3.5 Deductions for Absence</em></p>
		<p style="text-align: justify;">the Company reserves the right to make deductions from your salary for your absence for the whole or any part of the period during which you are required to work. The amount of deduction shall be based on the monthly gross salary as prescribed under the paragraph 3.2 [Computation of Salary for an Incomplete Month&rsquo;s Work].</p>
		<p style="text-align: justify;"><em>3.6 Deductions for Damages or Loss</em></p>
		<p style="text-align: justify;">A deduction will not exceed the amount of the damages or loss caused to the Company by your neglect or default, and except with the permission of the Commissioner shall in no case exceed of one month&rsquo;s wages.</p>
		<p style="text-align: justify;"><em>3.7 Recovery of Advances and Loans</em></p>
		<p style="text-align: justify;">The Company shall recover any advances and/or loans made to you, in instalments by deductions from your salary up to a maximum of twelve (12) months. No instalment shall exceed one-quarter of the salary due for the salary period in respect of which the deduction is made.</p>
		<p style="text-align: justify;"><em>3.8 Performance Review</em></p>
		<p style="text-align: justify;">The Employee will be provided with a performance appraisal at least once per year and said appraisal will be reviewed at which time all aspects of the assessment can be fully discussed. Pay review usually follows subsequent to performance review at discretion of the management.</p>
		<p style="text-align: justify;">Salary adjustments are completely at the discretion of the Directors taking into consideration individual performance and the overall performance of the Company. You will be advised on the effective date of the revised salary.</p>
		<p style="text-align: justify;"><em>3.9 Overpayments and Errors</em></p>
		<p style="text-align: justify;">Although unlikely, mistakes may occur with the calculation and payment of salaries. In the event of any discrepancy, you should raise the matter immediately. If there is an underpayment, the Company will reimburse the difference. However in the event of an overpayment, you agree that the adjustment will be made in your next salary payment. If an overpayment is not noticed for some time, you agree that the Company will reclaim the overpayment by making deductions from your salary, possibly on a deferred payment basis and by agreement with you.</p>
		<p style="text-align: justify;"><em>3.10 Salary to Employees on Maternity Leave under the Child Development Co-Savings Act</em></p>
		<p style="text-align: justify;">Female employees who qualifies for Government-paid maternity leave under the Child Development Co-Savings Act as detailed in paragraph 5.2 [Maternity Leave under the Child Development Co-Savings Act] will be paid by the Company during the entire 16 weeks of maternity leave, regardless of the birth order of the child.</p>
		';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><em>3.11 Salary to Employees on Maternity Leave under the Employment Act</em></p>
		<p style="text-align: justify;">This section applies to female employees who will be going on maternity covered by the Employment Act. The Company will continue paying out salary at the monthly gross rate for the first <strong>eight (8) weeks</strong> of maternity leave if:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee been employed for at least 3 months before the birth of the child;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee have less than two children of your own at the time of delivery (in the case of multiple births e.g. twins, triplets during the first pregnancy, the employer is still required to pay eight weeks of maternity leave for the next pregnancy); and</td>
		</tr>
		</tbody>
		</table>
        <table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee has given her employer at least one week&rsquo;s notice before going on maternity leave, and informed her employer as soon as practicable of her delivery. Otherwise, the employee is only entitled to half the payment during the maternity leave, unless she can show sufficient cause that prevented her from giving such notice to the employer</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;"><em>3.12&nbsp;</em><em>Training fee</em></p>
		<p style="text-align: justify;">To support your professional growth, the Company will provide you with training either in-house, on job or you may be sent for training provided by the approved institution listed in the Principal Statement. You may also request to attend training provided by approved institution at the cost of the Company capped at the amount described in the Principal Statement.</p>
		<p style="text-align: justify;">Training fee cannot be carried forward to the following year, neither can it be exchanged to cash or other benefits.</p>
		<p style="text-align: justify;"><em>3.13 Course and exam fee</em></p>
		<p style="text-align: justify;">The Company may support your course and examination fee for business related courses on a condition that you agree to stay employed with the Company for a period of six (6) months for each subject that supported by the Company (&ldquo;bond period&rdquo;).</p>
		<p style="text-align: justify;">Bond period starts on the first day of the month following the last day of the examination supported by the Company.</p>
		<p style="text-align: justify;">You will be required to compensate amount equivalent to your salary over the remaining bond period shall you terminate your employment with the Company during the bond period.</p>
		<p style="text-align: justify;">Prior to registration of courses, you are required to seek approval from your Manager or Director where a separate bond agreement will be executed with you.</p>
		<p style="text-align: justify;"><strong>4. HOLIDAYS AND LEAVE ENTITLEMENTS</strong></p>
		<p style="text-align: justify;"><em>4.1 Public Holidays</em></p>
		<p style="text-align: justify;">All employees are entitled to 11 paid public holidays per year. The 11 gazetted public holidays are:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">New Year&rsquo;s Day</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Chinese New Year (2 days)</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Good Friday</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Labour Day</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Hari Raya Puasa</td>
		</tr>
		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<table>
		<tbody>
        <tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Vesak Day</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">National Day</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Deepavali</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Hari Raya Haji</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Christmas Day</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">In the event that you are required to work on a public holiday, the Company reserves the right to either substitute a public holiday for any other day.</p>
		<p><em>4.2 Off-in-Lieu </em></p>
		<p style="text-align: justify;">If a public holiday falls on Sunday, the next working day will be a paid holiday (off-in-lieu).</p>
		<p style="text-align: justify;"><em>4.3 Vacation Leave</em></p>
		<p style="text-align: justify;">The vacation leave entitlement is described in the Principal Statement provided that you have served the Company for at least three (3) months. The vacation leave entitlement will also be calculated on the basis of a calendar year (1 January to 31 December). This means that the leave entitlement will be pro-rated if the employee has completed less than twelve (12) months of service in a calendar year.</p>
		<p style="text-align: justify;">All unclaimed annual leave as of 31<sup>st</sup> December of that year may be carried forward and utilize not later than 31<sup>st</sup> March of the next calendar year. All unused leave after 31<sup>st</sup> March of the subsequent year will be considered as being forfeited. The Company reserves the rights to approve or reject all annual leave applied for.</p>
		<p style="text-align: justify;">Annual leave can only be taken on working days as stipulated in the Principal statement of this employment contract.</p>
		<p style="text-align: justify;"><u>Forfeiture of annual leave</u><br>Annual leave entitlement may be forfeited if:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">You are absent from work without permission or reasonable excuse for more than 20% of the working days in a month or year, as the case may be;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">You fail to take leave within 12 months after the end of 12 months of continuous service; or</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">You are dismissed on the grounds of misconduct</td>
		</tr>
		</tbody>
		</table>
		<p><em>4.4 Unpaid Leave</em></p>
		<p style="text-align: justify;">Once the total annual leave entitlement has been fully utilized, any excess leave taken will be classified as unpaid leave. During the first three (3) months of your employment, you will not be entitled to paid annual leave, but may apply for unpaid leave which may be granted at the discretion of your Manager or the Company. Deductions will also be made from your salary or from an advance leave given by the Company if any unpaid leave is taken.</p>
		<p><em>4.5 Medical Leave</em></p>
		<p style="text-align: justify;">You will be entitled to paid medical leave provided that all the following conditions are met:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">You have been employed by the Company for at least 3 months;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">A valid certification has been obtained by either the Company&rsquo;s panel doctors or approved public medical doctors; and</td>
		</tr>
		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">If you communicate your absence to your Manager within 48 hours. Failure to do so will result your absence as absenteeism without permission or reasonable excuse.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">An employee will be deemed to be hospitalised if it is certified by a doctor to be in need of hospitalisation. Being warded in a hospital may not be necessary in this case. Any medical leave taken on a half working day shall be considered as <strong>one (1) day</strong> medical leave.</p>
		<p style="text-align: justify;"><u>Salary on medical leave</u><br>You will be paid at your gross rate of pay excluding any shift allowance if you are on paid medical leave, for both hospitalisation and outpatient non-hospitalisation leave.</p>
        <p style="text-align: justify;"><u>Limit on the total number of paid medical leave</u><br />The number of paid outpatient and hospitalisation leave is capped at sixty (60) days if you have been employed by the Company for more than three (3) months. If you have already taken fourteen (14) days of outpatient sick leave during the year, the number of days of hospitalisation sick leave that you are entitled to is forty-six (46) days (60 days &ndash; 14 days).</p>
		<p style="text-align: justify;">The Company will not grant any paid medical leave or bear any medical consultation fees if the medical treatment is for cosmetic purposes, in the opinion of the doctor performing the examination.</p>
		<p style="text-align: justify;"><u>Reimbursement of medical expenses</u><br />The Company will reimburse your medical consultation fees if you have worked for at least three (3) months with the Company. The amount of reimbursement is detailed in the Principal Statement.</p>
		<p style="text-align: justify;">The balance of unclaimed amount will not return to employee.</p>
		<p style="text-align: justify;"><u>Medical leave on rest days, public holidays, non-working days and during annual leave &amp; no-pay leave</u><br />You will not be granted paid sick leave on rest days, public holidays, non-working days, during annual leave and during no-pay leave. However, you will still be entitled to claim for the medical consultation fees.</p>
		<p style="text-align: justify;"><strong>5. MATERNITY AND CHILDCARE LEAVE FOR PARENTS</strong></p>
		<p style="text-align: justify;"><em>5.1 Notice of Confinement</em></p>
		<p style="text-align: justify;">A notice of at least one week in advance must be made to the Manager and/or the Company before taking maternity leave. Female employees would also have to inform their employers about their delivery as soon as practicable; failure to do so will result in an entitlement to only half the payment during maternity leave.</p>
		<p><em>5.2 Maternity Leave under the Child Development Co-Savings Act</em></p>
		<p style="text-align: justify;">This section applies to female employees who:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">are legally married;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">have worked with the Company for least three (3) months before childbirth; and</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">whose child has the legal status of a Singapore Citizen</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Female employees who fulfil the requirements mentioned will be entitled to a total of sixteen (16) weeks of maternity leave, and may utilize such leave of up to four (4) weeks before the anticipated date of delivery. Foreigners or Singapore Permanent Residents may receive such benefits if they fulfil the mentioned requirements.</p>
		';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;">Although it is recommended that this entitlement should be utilized continuously, the last 8 weeks of the maternity leave may be taken flexibly over a 12 month period after child birth if a mutual agreement with the Company is arranged.</p>
		<p style="text-align: justify;">Any female employee who does not meet criterion (i) and/or (ii) prescribed above at the time of confinement but meets them within 12 months of childbirth may be eligible for the remaining maternity leave entitlement from the date she meets all the criteria. The remaining maternity leave entitlement must be utilized before the child turns twelve (12) months old. Any maternity leave that has lapsed cannot be utilized.</p>
        <p style="text-align: justify;"><em>5.3 Maternity Leave under the Employment Act</em></p>
		<p style="text-align: justify;">Any female employee who is earning less than S$4,500 a month is covered under the Employment Act but not the Child Development Co-Savings Act will be entitled to twelve (12) weeks of maternity leave. The Company will pay only the first eight (8) weeks of maternity leave if the female employee has fewer than two (2) living children (excluding the new-born), and has served employment with the Company for at least three (3) months before childbirth. The last four (4) weeks of maternity leave can be taken flexibly over a twelve (12) month period from the child&rsquo;s birth.</p>
		<p style="text-align: justify;"><u>On probation</u><br />Female employees who are on probation will not be eligible for paid maternity leave.</p>
		<p style="text-align: justify;"><u>Twins or more children</u><br />A female employee who gives birth to twins will also be treated in the same way as an employee who gives birth to a single child. It will be considered as a single confinement and there will be no double maternity benefits.</p>
		<p style="text-align: justify;"><u>Stillbirth</u><br />A female employee with stillbirth will also be entitled to twelve (12) weeks of maternity leave.</p>
		<p style="text-align: justify;"><u>Abortions or miscarriage</u><br />Maternity leave benefits do not cover abortions or miscarriages. However, paid sick leave can be applied provided that all qualifying conditions for paid sick leave have been satisfied.</p>
		<p style="text-align: justify;"><u>Premature delivery</u><br />The sixteen (16) weeks of maternity leave can be commenced from the date of confinement.</p>
		<p style="text-align: justify;"><u>Sick leave</u><br />There will be no entitlement to paid sick leave while on maternity leave. Medical expenses incurred in connection with the delivery of the child will not be claimable from the Company.</p>
		<p style="text-align: justify;">This section also covers:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Single (unmarried) female employees</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Female employees whose child is not a Singapore Citizen</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Female foreigners or Singapore Permanent Residents working in Singapore</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;"><em>5.4 Paternity Leave</em></p>
		<p style="text-align: justify;">Male employees will be entitled to one (1) week of Government-Paid Paternity Leave for all births provided that the following requirements are met:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Child is a Singapore Citizen</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child&rsquo;s parents are lawfully married;</td>
		</tr>
		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<table>
		<tbody>
        <tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee (i.e. child&rsquo;s father) must have served the Company for a continuous duration of at least 3 calendar months immediately preceding the birth of the child;</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Paternity leave is to be taken within 16 weeks after the birth of the child. It can also be taken flexibly within 12 months after the birth of the child, if there is mutual agreement with the Company.</p>
        <p><em>5.5 Shared Parental Leave under the Child Development Co-Savings Act</em></p>
		<p style="text-align: justify;">Male employees are entitled to share one (1) week out of the sixteen (16) weeks&rsquo; maternity leave provided that the following requirements are met:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child is a Singapore Citizen;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The male employee is legally married to the child&rsquo;s mother; and</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child&rsquo;s mother qualifies for paid maternity leave under the Child Development Co-Savings Act</td>
		</tr>
		</tbody>
		</table>
		<p><em>5.6 Unpaid Infant Care Leave under the Child Development Co-Savings Act</em></p>
		<p style="text-align: justify;">The unpaid infant care leave under the Child Development Co-Savings Act covers all parents of Singapore citizens, including managerial, executive or confidential staff if all&nbsp;three of the following conditions are met:&nbsp;</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child (including legally adopted children or stepchildren) is below&nbsp;2 years of age</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child is a Singapore Citizen; and</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee (i.e. parent) has served the Company for a continuous period of at least&nbsp;3 months.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">An employee is entitled to six (6) days of unpaid infant care leave per year if he/she is covered under the Child Development Co-Savings Act, regardless of the number of children. For divorced or widowed parents, the leave entitlement will not be doubled. The unpaid infant care leave entitlement is six (6) days per year if the employee has worked for at least three (3) months with the Company. It will not be pro-rated even if the employee has worked for less than twelve (12) months with the Company. Infant care leave cannot be used to offset the notice period for termination of employment.</p>
		<p style="text-align: justify;">Any unused infant care leave at the end of the yearly entitlement period will lapse.</p>
		<p><em>5.7 Childcare Leave under the Child Development Co-Savings Act</em></p>
		<p style="text-align: justify;">The childcare leave under the Child Development Co-Savings Act covers all parents of Singapore citizens, including managerial, executive or confidential staff if all&nbsp;three of the following conditions are met:&nbsp;</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child is below 7 years old;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The child is a Singapore Citizen; and</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The parent has served the Company for a continuous period of at least&nbsp;3 months.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">An employee is entitled to six (6) days of childcare leave per year if he/she is covered under the Child Development Co-Savings Act until the year the child turns seven (7) years old, regardless of the number of children. In the case where the employee has not been with the Company for a full year, the number of eligible childcare days is as follows:</p>
		<p><em>5.8 Extended Childcare Leave</em></p>
		<p style="text-align: justify;">Extended childcare leave applies to parents whose youngest child is a Singapore Citizen (children) aged 7 to 12. The extended childcare leave entitles parents to two (2) additional days of childcare leave, covered by the government, provided that the employee has served the Company for a continuous period of at least 3 months.</p>
		';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p><em>5.9 Adoption Leave for Female Employees</em></p>
		<p style="text-align: justify;">Female employees who are adoptive mothers will be entitled to four (4) weeks of Government-Paid adoption leave. The adoption leave applies to female employees who meet the following criteria:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adopted child is below the age of 12 months at the point of &ldquo;formal intent to adopt&rdquo;, i.e. Court Application to adopt (for local child) or issuance of in-principle approval for Dependant&rsquo;s Pass (for foreign child);&nbsp;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adopted child is a Singapore Citizen;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adoptive mother is lawfully married at the point of &ldquo;formal intent to adopt&rdquo;;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">If the child is a foreigner, one of the adoptive parents must be a Singapore Citizen;&nbsp;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">For a foreign child, the child must become a Singapore Citizen within 6 months of the child&rsquo;s adoption;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee (i.e. adoptive mother) has served the Company for at least 3 calendar months;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The Adoption Order is passed within 1 year from the point of &ldquo;formal intent to adopt&rdquo;;</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">The term &ldquo;formal intent to adopt&rdquo; refers to the point of filing of the adoption petition to the Family Court for adoption (Singapore Citizen child); or the point of issuance of a document stating that the application for the Dependant&rsquo;s Pass for the adopted child has been approved (for non-Singapore Citizen child)</p>
		<p style="text-align: justify;">Adoptive mothers who meet the eligibility criteria can start to consume their adoption leave commencing not earlier than:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The date of application to adopt, if the child is a Singapore Citizen</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The date on which the Dependant&rsquo;s Pass is issued, if the child is not a Singapore Citizen</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Failure to take leave within a period of 12 months commencing on the date of birth of the child will cease to be entitled to that leave and shall not be entitled to any payment in lieu thereof.</p>
		<p style="text-align: justify;"><em>5.10 Adoption Leave for Male Employees</em></p>
		<p style="text-align: justify;">Male employees who are adoptive fathers will be entitled to one (1) week of Government-paid adoption leave. The adoption leave applies to male employees who meet the following criteria:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adopted child is below 12 months of age;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adopted child is a Singapore Citizen, or in cases where the adopted child is not a Singapore Citizen, at least one of the adoptive parents must be a Singapore Citizen;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The adoptive father is lawfully married at the point of the &lsquo;formal intent to adopt&rsquo;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee (i.e. adoptive father) has served the Company for a continuous period of at least 3 calendar months immediately preceding the point of &lsquo;formal intent to adopt&rsquo;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The Adoption Order is granted within 1 year from the point of &lsquo;formal intent to adopt&rsquo;, and the adopted child obtains Singapore Citizenship within 6 months after the Adoption Order is passed; AND</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">The employee (i.e. adoptive father) is an applicant to the adoption</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">The term &ldquo;formal intent to adopt&rdquo; refers to the point of filing of the adoption petition to the Family Court for adoption (Singapore Citizen child); or the point of issuance of a document stating that the application for the Dependant&rsquo;s Pass for the adopted child has been approved (for non-Singapore Citizen child)</p>
		<p style="text-align: justify;"><em>5.11 Study leave</em></p>
		<p style="text-align: justify;">You are also entitled to 2 days&rsquo; study leave per subject for the purpose of examination of approved institution or programme detailed in Principal Statement.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><strong>6. CENTRAL PROVIDENT FUND (CPF) </strong></p>
		<p style="text-align: justify;">The CPF contribution rates apply to employees who are Singapore Citizens, Singapore Permanent Resident (SPR) in the 3<sup>rd</sup> year and onwards of obtaining the SPR status, and Singapore Permanent Residents in the 1<sup>st</sup> and 2<sup>nd</sup> year of obtaining the SPR status but who has jointly applied with their employers to contribute at full employer and employee rates.</p>
		<p style="text-align: justify;">The Ordinary Wage ceiling for the following rates is capped in accordance with the provision of CPF Act.</p>
		<p style="text-align: justify;"><strong>7. NATIONAL SERVICE (NS) </strong></p>
		<p style="text-align: justify;">This applies to all male Singapore Citizens and second generation Singapore Permanent Residents who have served National Service. This leave is considered unpaid leave and the employee has to submit the appropriate papers to claim the salary for the period from MINDEF (Ministry of Defence) directly. Claims should be submitted two (2) weeks before training commences to enable MINDEF to pay make-up pay promptly. Please note that all make-up pay claims must be submitted within three (3) months from the payment of service pay. Late claims submitted later than 3 months from payment of service pay will not be accepted.</p>
		<p style="text-align: justify;"><strong>8. WORKPLACE SAFETY AND HEALTH</strong></p>
		<p style="text-align: justify;"><em>8.1 Duties of Employees</em></p>
		<p style="text-align: justify;">During your employment with the Company, you agree to follow the safe working procedures and principles introduced; you must not engage in any unsafe act that may endanger yourself or others working around you; you must use, in proper manner, any personal protective equipment, devices, equipment or other means provided to secure your safety, health and welfare while working; and you must not engage in any negligent acts that may endanger yourself.</p>
		<p style="text-align: justify;"><em>8.2 Work Injury Compensation</em></p>
		<p style="text-align: justify;">The Work Injury Compensation Act (WICA) provides injured employees, both local and foreign employees with a low-cost and expeditious alternative to settle compensation claims. Compensation is payable when you have:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Suffered an injury by accident arising out of and in the course of employment; or</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Contracted an occupational disease; or</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Contracted a disease due to work-related exposure to biological or chemical agents</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">The three (3) compensation benefits can be claimed:</p>
		<p style="text-align: justify;">(i)&nbsp;<u>Medical leave wages </u><br />Full salary is payable for outpatient medical leave up to 14 days and hospitalization leave of 60 days a year which cannot be carried forward to the following year nor be exchanged to cash, kind or other benefits.</p>
		<p style="text-align: justify;">(ii) <u>Medical Expenses</u><br />Medical expenses is payable by the Company as long as treatment is considered necessary by a Singapore-registered doctor, up to amount stipulated in the Principal Statement or 1 year from the date of accident, whichever is reached first. Medical expenses include cost of medical consultation fees, ward charges, treatment, charges for physiotherapy, occupational and speech therapy, medicine, artificial limbs and surgical appliances, etc.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;">(iii) <u>Compensation for Permanent Incapacity and Death</u><br />Compensation for permanent incapacity is payable when the injury / illness has permanent effects on the employee&rsquo;s ability to work. Compensation as such is payable to the injured employee.</p>
		<p style="text-align: justify;">Compensation for death is payable when the injury caused the death of the employee. Compensation as such would be payable to the dependants of the deceased employee.</p>
		<p style="text-align: justify;"><strong>&nbsp;9.&nbsp;</strong><strong>WOMEN&rsquo;S CHARTER</strong></p>
		<p style="text-align: justify;">This is only applicable to female employees. the Company is prohibited from employing a female employee at any time during the four weeks immediately following the confinement. This applies to those employees covered under the Employment Act or the Child Development Co-Savings Act.</p>
		<p style="text-align: justify;"><strong>10. TRIPARTITE GUIDELINES</strong></p>
		<p style="text-align: justify;"><em>10. 1Fair Employment Practices</em></p>
		<p style="text-align: justify;">The Company fully adopts the Singapore Tripartite Guidelines for Fair Employment Practices and recruits all employees on the basis of merit, regardless of age, race, gender, religion, marital status, family responsibilities or disabilities. The Company rewards all employees based on ability, performance contribution and experience.</p>
		<p style="text-align: justify;"><em>10.2 Issuance of Itemized Payslips</em></p>
		<p style="text-align: justify;">Payslips will be issued by the Company within seven (7) days after the issuance of salary as detailed in the Principal Statement. Where there are more than one salary period within the month, all salary payment details for the calendar month will be consolidated into a single payslip.</p>
		<p style="text-align: justify;"><em>10.3 Flexible Work Schedules</em></p>
		<p style="text-align: justify;">You will be required to operate flexibly by undertaking duties to fulfil the needs of the business. During uneven and fluctuating business cycles, you may be required to support the other functional areas of the Company that are generally within your own work scope of level of ability.</p>
		<p style="text-align: justify;"><strong>11. CONFIDENTIALITY</strong></p>
		<p style="text-align: justify;">During the course of your employment with the Company, you will have access to confidential information. (Examples include information relating to existing and prospective clients, client information on past and future film releases, client artwork and imagery, profit margins, security arrangements for the office, and contact details for clients, brands and anyone else associated with the business. This list is not exhaustive.</p>
		<p style="text-align: justify;">You shall not without prior written consent of the employer destroy, make copies, duplicate or reproduce in any form the employer&rsquo;s confidential information.</p>
		<p style="text-align: justify;">To protect the business of the Company and its clients, you are expressly forbidden, either during or after your employment, to disclose any confidential information relating to the Company or its clients either verbally or in writing to any person or company, or make use of any such information, without the prior written consent of a Director of the Company. This clause shall not affect the Company&rsquo;s common law rights. the Company reserves the right to seek adequate compensation and an injunction if this obligation is not fulfilled.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p><strong>12. CONFLICT OF INTEREST</strong></p>
		<p style="text-align: justify;">During your employment with the Company, you shall not be concerned or interested directly or indirectly, whether solely or with others in any trade, business or occupation, which competes with the interests of the Company or has the potential of causing a conflict of interest, without the prior written permission of a Director. This does not prohibit your right to hold shares, securities or debentures in any other company as a bona fide investor.</p>
		<p style="text-align: justify;">Before engaging in any other employment outside of the Company, you should gain written permission from a Director. Although permission will not be unreasonably withheld, it may not be given or may be withdrawn if the &lsquo;other&rsquo; employment interferes or affects, in any way, your ability to effectively carry out your duties, or causes a conflict of interest. If permission is given for you to engage in other employment, and the total amount of hours you work (by combining all paid working hours) exceeds 48 hours per week, you will be required to complete a Working Time Consent Form, contracting yourself out of the Working Time Regulations.</p>
		<p style="text-align: justify;">The Company will not permit you (or any employee) under any circumstances, to undertake private work for clients of the Company. Anyone found to be in breach of this will be dealt with through the Disciplinary Procedure. You are required to immediately advise a Director if you are approached by a client or prospective client in respect to private work.</p>
		<p style="text-align: justify;">You are not permitted to accept any financial payments or payments in kind from clients or suppliers. If you receive a gift from a client or supplier, these must not be received at your home address, must be declared to a Director and acknowledged on Company letterhead.</p>
		<p><strong>13. INVENTION AND INTELLECTUAL PROPERTY</strong></p>
		<p style="text-align: justify;">All intellectual property, software, systems, structures and processes being used or designed by you during the course of your employment with the Company in relation to the projects and applications, and all patents, designs, copyright and other artistic, commercial or intellectual property rights covering the same, are the absolute property of the Company or its clients. At the Company&rsquo;s expense, you will do all things necessary to ensure these (and any inventions) remain the property of the Company.</p>
		<p><strong>14. PROTECTION OF BUSINESS DURING EMPLOYMENT AND FOLLOWING TERMINATION</strong></p>
		<p style="text-align: justify;">To protect the current and future business of the Company, you are bound during your employment and for a period of 24 months following termination:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Not to approach, solicit or entice away (or endeavour to do so) either directly or indirectly any clients or brand partners of the Company with whom you are actively concerned or were actively concerned during the 24 months prior to the termination of your employment, whether by yourself or with or on behalf of any person, firm or company, or by acting through others;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Not to solicit or entice away (or endeavour to do so) any employee of the Company who holds a management, sales, account management or technical position, whether by yourself or with or on behalf of any person, firm or company, or by acting through others;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Not to prevent or seek to prevent any person or company who is or was a supplier to the Company from supplying goods or services to the Company or any associated company.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Each of these undertakings is separate and distinct from each other. In the event that any of the above restrictive covenants are determined to be void and/or unenforceable, that clause shall stand struck out and the remainder of the Agreement shall remain in force. If you apply for, or are offered, employment</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;">or engagement with another company or firm, you will bring this clause to the attention of the third party proposing directly or indirectly to employ, appoint or engage you before entering into the contract.</p>
		<p style="text-align: justify;"><strong>&nbsp;15.&nbsp;</strong><strong>PUBLIC APPEARANCES AND COMMENTS TO THE PRESS</strong></p>
		<p style="text-align: justify;">To protect the business of the Company you are expressly forbidden, either during or after your employment:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">To directly or indirectly publish any opinion, fact or material on any matter connected with or relating to the business of the Company or other associated company or client of the Company without the prior written approval of the Board of Directors;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">To make any public appearances or comments to the press on any matter connected with or relating to the business of the Company or other associated company or client of the Company without the prior written approval of the Board of Directors.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;">Any requests for comments, opinions or public appearances should be referred to a Director.</p>
		<p style="text-align: justify;"><strong>16. PERSONAL DATA PROTECTION</strong></p>
		<p style="text-align: justify;"><em>16.1 Company&rsquo;s Responsibility in the Protection of Employees&rsquo; Personal Data</em></p>
		<p style="text-align: justify;">The Company will make reasonable security arrangements to protect your personal data by preventing unauthorized access, collection, use, disclosure or similar risks. Personal data covered under the PDPA includes the following:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Full name and residential address</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">National Registration Identification Card (NRIC) number or Foreign Identification Number (FIN)</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Passport details (including passport number, photograph or video image of an individual)</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Mobile telephone number</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Personal email address</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">Thumbprint</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">DNA profile</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;"><em>16.2 Company&rsquo;s Access to Employee&rsquo;s Personal Data</em></p>
		<p style="text-align: justify;">The Company will not provide an individual access to an employee&rsquo;s personal data if the provision of the data or other information could reasonably be expected to:</p>
		<table>
		<tbody>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">cause immediate or <em>grave harm</em> to the <em>individual&rsquo;s safety or physical or mental health</em>;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">threaten the safety or physical or mental health of another individual;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">reveal personal data about another individual;</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">reveal the identity of another individual who has provided the personal data, and the individual has not consented to the disclosure of his or her identity; or</td>
		</tr>
		<tr>
		<td style="width: 3%;">&ordm;</td>
		<td style="width: 96%; text-align: justify;">be contrary to national interest.</td>
		</tr>
		</tbody>
		</table>
		<p style="text-align: justify;"><strong>17. SEVERABILITY</strong></p>
		<p style="text-align: justify;">Both parties hereto agree that in the event any article or part thereof of this contract is held to be unenforceable or invalid then said article or part shall be struck and all remaining provision shall remain in full force and effect.</p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;"><strong>18. CONTRACT </strong><strong>(RIGHTS OF THIRD PARTIES) ACT</strong></p>
		<p style="text-align: justify;">A person who is not party to this Agreement has no rights under the Contracts (Rights of Third Parties) Act, Chapter 53B of Singapore to enforce any term of this Agreement, but this does not affect any right or remedy of a third party which exists or is available apart from the said Act.</p>
		<p><strong>19. GOVERNING LAW</strong></p>
		<p style="text-align: justify;">This Contract shall be governed by the laws of Singapore.</p>
		<p><strong>20. ENTIRE CONTRACT</strong></p>
		<p style="text-align: justify;">This Contract and its Principal Statement along with any Employee Handbook changes that may be communicated to you from time to time in the Company system which is accessible with your user login constitute the complete and exclusive statement of the Employment Contract between you and the Company. All previous representations, discussions and writings are merged in, and superseded by this Contract. This Contract may be modified only in writing signed by authorized representative of both parties. This Contract and its Principal Statements shall prevail over any additional, conflicting or inconsistent terms and conditions which may appear on any other document.</p>
		<p style="text-align: justify;">We are pleased to offer you a career with the Company and ask that you confirm your acceptance of the above by signing this letter with its Principal Statement.</p>
		<p style="text-align: justify;">&nbsp;</p>
		<p style="text-align: justify;">Yours sincerely</p>
		<table style="width: 100%; height: 225px;">
		<tbody>

		<tr style="height: 90px;">
		<td style="width: 47.5%; height: 34px; text-align: justify;">
		<p>On behalf of <strong>ACUMEN ALPHA ADVISORY GROUP</strong></p>
		</td>
		<td style="width: 5%; height: 34px; text-align: justify;">&nbsp;</td>
		<td style="width: 47.5%;height: 34px; text-align: justify;">I accept the terms &amp; conditions of service outlined above.</td>
		</tr>

		<tr style="height: 146px;">
		<td style="width: 47.5%; text-align: justify; height: 146px;">
		<p>&nbsp;</p>
		</td>
		<td style="width: 5%; text-align: justify; height: 146px;">&nbsp;</td>
		<td style="width: 47.5%;text-align: justify; height: 146px;">&nbsp;</td>
		</tr>

		<tr style="height: 45px;">
		<td style="width: 47.5%; text-align: justify; height: 45px;">
		<p>_________________________________________</p>
		<p>Name:<br />Date:</p>
		</td>
		<td style="width: 5%; text-align: justify; height: 45px;">&nbsp;</td>
		<td style="width: 47.5%;text-align: justify; height: 45px;">
		<p>_________________________________________</p>
		<p>Name:<br />Date:</p>
		</td>
		</tr>

		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '<p style="text-align: justify;">This Principal Statement summarises the main terms of your employment with the Company but must be read in conjunction with the rest of the Contract.</p>
		<table style="border-collapse: collapse;border: 1px solid black;">
		<tbody>
		<tr>
		<td width="27%">
		<p><strong>Job title</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['job_title'].'</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p>&nbsp;</p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">&nbsp;</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Date of commencement</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['date_of_commencement'].'<br>Subject to approval of work pass from Ministry of Manpower (if required)</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Monthly remuneration</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>Without bond &ndash; '.$data['salary_currency_code'].' '.$data['salary'].'</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p>&nbsp;</p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">&nbsp;</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Additional monthly compensation</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['bond_currency_code'].' '.$data['bond_allowance'].'</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Period of bond</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['bond_period'].' months from date of commencement stated in this letter or from date where necessary work pass has been granted, whichever is later.</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Business Addresses</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<table>
		<tbody>
		<tr>
		<td style="width:5%">o</td>
		<td style="width:95%">18 Howard Road #08-06/07 Novelty Bizcentre Singapore 369585</td>
		</tr>
		<tr>
		<td style="width:5%">o</td>
		<td style="width:95%">18 Howard Road #08-11 Novelty Bizcentre Singapore 369585</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Location of Work</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>Your main place of work is in the business addresses and clients&rsquo; premises. You may be required to attend training or meeting at location other than the business addresses of the Company or its clients.</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Hours of Work</strong></p>
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['work_hour'].' hours a week</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Probation period</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<table>
		<tr>
		<td style="width:5%">o</td><td style="width:95%">First 3 months from the date of commencement of work; or</td>
		</tr>
		<tr>
		<td>o</td><td>Where bond has been opted of which there will not be a probation.</td>
		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Notice period</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>
		During probation period, either party must serve <strong>two (2) weeks&rsquo;</strong> notice.<br>
		<br>
		Subsequent to probation period, either party must serve <strong>two (2) months&rsquo;</strong> notice during peak period which usually runs right after Lunar New Year until end of August and <strong>one (1) months&rsquo;</strong> notice during off peak which runs from September to a week prior to Lunar New Year.
		</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Vacation leave</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>'.$data['vacation_leave'].' days <em>per annum</em></p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Medical Expenses</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>
		A maximum of $50 will be reimbursed per medical receipt. The annual medical reimbursement cap is S$300 per Employee for outpatient claim.<br>
		<br>
		Up to S$2,000 on work related injury that requires hospitalization
		</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Approved Institutions</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>Refer to rule book</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>&nbsp;</p>
		</td>
		</tr>
		<tr>
		<td width="27%">
		<p><strong>Training fee</strong></p>
		</td>
		<td width="3%">
		<p><strong>&nbsp;</strong></p>
		</td>
		<td width="69%">
		<p>S$1,500 per annum</p>
		</td>
		</tr>
		</tbody>
		</table>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');


        $content = '';
        $obj_pdf->AddPage();
        $page = '
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p><strong>&nbsp;</strong></p>
        <p style="text-align: center;font-size:150%"><strong>THIS PAGE IS INTENTIONALLY LEFT BLANK</strong></p>';
        $content .= $page;
        $obj_pdf->writeHTML($content, true, false, false, false, '');

        // $uts = time('Y.m.d H:i:s');
        $uts = time();


        $obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Employment Contract - ('.$data['name'].') UTS'.$uts.'.pdf', 'F');

		chmod($_SERVER['DOCUMENT_ROOT'].'hrm/pdf/document/Employment Contract - ('.$data['name'].') UTS'.$uts.'.pdf',0644);

        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

        array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/hrm/pdf/document/Employment Contract - ('.$data['name'].') UTS'.$uts.'.pdf');

        echo json_encode(array("link" => $array_link, "filename" => "Employment Contract - (".$data['name'].") UTS".$uts.".pdf"));
	}

	public function write_header($firm_id)
    {
        $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
                                                LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
                                                LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
                                                LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
                                                where firm.id = '26'");
        $query = $query->result_array();

        // Calling getimagesize() function 
        list($width, $height, $type, $attr) = getimagesize(base_url('../secretary/uploads/logo/'.$query[0]["file_name"].'')); 

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

        if(!empty($query[0]["file_name"]))
        {
            // $img = '<img src="/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
            $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
            $img = '<img src="'.$protocol . $_SERVER['SERVER_NAME'].'/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
        }
        else
        {
            $img = '';
        }

        if( $query[0]["address_type"] == 'Foreign')
        {
            $fax = $query[0]["fax"];

            if(empty($fax))
            {
                $fax = '-';
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
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["foreign_address2"] .' '.$query[0]["foreign_address3"].'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $fax .'&nbsp;</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }
        else
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
                        <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">ACUMEN ALPHA ADVISORY GROUP</span><br /><span style="font-size: 8pt; text-align: left;">18 HOWARD ROAD #0807 NOVELTY BIZCENTRE SINGAPORE 369585<br />T: (65) 6538 1993 | F: (65) 6536 2969 &nbsp;<br />E: enquiry@aaa-global.com | W: aaa-global.com</span></td>
                    </tr>
                    </tbody>
                    </table>';
        }

        return $header_content;
    }

    // // BACK-UP write_header CODE 
    // public function write_header($firm_id)
    // {
    //     $query = $this->db->query("select firm.*, firm_email.email, firm_telephone.telephone, firm_fax.fax from firm 
    //                                             LEFT JOIN firm_email ON firm_email.firm_id = firm.id AND firm_email.primary_email = 1 
    //                                             LEFT JOIN firm_telephone ON firm_telephone.firm_id = firm.id AND firm_telephone.primary_telephone = 1 
    //                                             LEFT JOIN firm_fax ON firm_fax.firm_id = firm.id AND firm_fax.primary_fax = 1
    //                                             where firm.id = '".$firm_id."'");
    //     $query = $query->result_array();

    //     // Calling getimagesize() function 
    //     list($width, $height, $type, $attr) = getimagesize(base_url('../secretary/uploads/logo/'.$query[0]["file_name"].'')); 

    //     $different_w_h = (float)$width - (float)$height;

    //     if((float)$width > (float)$height && $different_w_h > 100)
    //     {
    //         //before width is 25, height is 73.75
    //         $td_width = 25;
    //         $td_height = 73.75;
    //     }
    //     else
    //     {
    //         $td_width = 15;
    //         $td_height = 83.75;
    //     }

    //     if(!empty($query[0]["file_name"]))
    //     {
    //         // $img = '<img src="/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
    //         $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
    //         $img = '<img src="'.$protocol . $_SERVER['SERVER_NAME'].'/secretary/uploads/logo/'. $query[0]["file_name"] .'" height="55" />';
    //     }
    //     else
    //     {
    //         $img = '';
    //     }

    //     if( $query[0]["address_type"] == 'Foreign')
    //     {
    //         $fax = $query[0]["fax"];

    //         if(empty($fax))
    //         {
    //             $fax = '-';
    //         }

    //         $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
    //                 <tbody>
    //                 <tr style="height: 60px;">
    //                     <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
    //                         <table style="border-collapse: collapse; width: 100%;" border="0">
    //                         <tbody>
    //                         <tr>
    //                         <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
    //                         </tr>
    //                         </tbody>
    //                         </table>
    //                     </td>
    //                     <td style="width: 1.25%; text-align: left;">&nbsp;</td>
    //                     <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["foreign_address2"] .' '.$query[0]["foreign_address3"].'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $fax .'&nbsp;</span></td>
    //                 </tr>
    //                 </tbody>
    //                 </table>';
    //     }
    //     else
    //     {
    //         $header_content = '<table style="width: 100%; border-collapse: collapse; height: 60px; font-family: arial, helvetica, sans-serif; font-size: 10pt;" border="0">
    //                 <tbody>
    //                 <tr style="height: 60px;">
    //                     <td style="width: '. $td_width .'%; text-align: left; height: 60px; padding: 5%;" align="center">
    //                         <table style="border-collapse: collapse; width: 100%;" border="0">
    //                         <tbody>
    //                         <tr>
    //                         <td style="text-align: left; height: 60px;" align="center"><p>'. $img .'  </p></td>
    //                         </tr>
    //                         </tbody>
    //                         </table>
    //                     </td>
    //                     <td style="width: 1.25%; text-align: left;">&nbsp;</td>
    //                     <td style="width: '. $td_height .'%; height: 60px;"><span style="font-size: 18pt;">'.$query[0]["name"].'</span><br /><span style="font-size: 8pt; text-align: left;">UEN: '. $query[0]["registration_no"] .'<br />Address: '. $query[0]["street_name"] .', #'. $query[0]["unit_no1"] .'-'.$query[0]["unit_no2"].' '. $query[0]["building_name"] .', Singapore '. $query[0]["postal_code"] .'<br />Tel: '. $query[0]["telephone"] .' &nbsp; Fax: '. $query[0]["fax"] .'&nbsp;</span></td>
    //                 </tr>
    //                 </tbody>
    //                 </table>';
    //     }

    //     return $header_content;
    // }

}

 class MYPDF_OL extends TCPDF {
 	//Page header
    public function Header() {
    	if($this->page != "19" && $this->page != "20")
       	{
			$headerData = $this->getHeaderData();
	        $this->SetFont('helvetica', 'B', 23);
	        // $this->SetXY(10, 10);
	        // $this->writeHTML($headerData['string'], true, false, false, false, '');
	        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
       	}
       	else
       	{
       		$this->Cell(175, 0, "PRINCIPAL STATEMENT", 0, false, 'C', 0, '', 0, false, 'T', 'M');
       	}
   	}

   public function Footer() {
        $this->SetY(-23);
        
        // Page number
     //    if($this->page != "4")
     //   	{
	    //     if (empty($this->pagegroups)) {
	    //         $pagenumtxt = $this->getAliasNumPage();
	    //     } else {
	    //         $pagenumtxt = $this->getPageNumGroupAlias();
	    //     }
	    //     $this->SetFont('helvetica', 'B', 10);
	    //     $this->Cell(140, 0, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    // }
	    // else
	    // {
	    // 	if (empty($this->pagegroups)) {
	    //         $pagenumtxt = $this->getAliasNumPage();
	    //     } else {
	    //         $pagenumtxt = $this->getPageNumGroupAlias();
	    //     }
	    //     $this->SetFont('helvetica', 'B', 10);
	    //     $this->Cell(0, 0, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	    //     // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
	    // }

	    if(empty($this->pagegroups)) 
	    {
            $pagenumtxt = $this->getAliasNumPage();
        } 
        else 
        {
            $pagenumtxt = $this->getPageNumGroupAlias();
        }

        $pagenumtxt = 'Page '.$pagenumtxt.' | 20';

        $this->SetFont('helvetica', '', 8);
        $this->Cell(175, 0, $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
       
       	// if($this->page != "4")
       	// {
	        $this->SetFont('helvetica', '', 11);
	        $this->MultiCell(20, 5, 'Employer', 1, 'C', 0, 0, '155', '', true);
			$this->MultiCell(20, 5, 'Employee', 1, 'C', 0, 1, '', '', true);
			$this->MultiCell(20, 5, '', 1, 'C', 0, 0, '155', '', true);
			$this->MultiCell(20, 5, '', 1, 'C', 0, 2, '175' ,'', true);
			// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

			$this->Ln(4);
		// }
   }
}
