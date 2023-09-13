<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class CreateDocumentPdf extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('form');
	}

	

	public function index()
	{
		$this->load->helper('pdf_helper');

	    
	}

	public function create_document_pdf()
	{
		$document_id = $_POST["document_id"];

		//echo json_encode(count($billing_id));
		$array_link = [];
		if(count($document_id) != 0)
		{
			for($i = 0; $i < count($document_id); $i++)
			{
				//echo ($billing_id[$i]);

				if(strpos($document_id[$i], '/trans') !== false)
		        {
		        	$pending_document_id = str_replace('/trans', "", $document_id[$i]);
		        	$q = $this->db->query("select * from transaction_pending_documents where id = '".$document_id[$i]."'");
		        }
		        else
		        {
		        	$q = $this->db->query("select * from pending_documents where id = '".$document_id[$i]."'");
		        }
				
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
				//$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				$obj_pdf->SetCreator(PDF_CREATOR);
				$title = "Document";
				$obj_pdf->SetTitle($title);
				$obj_pdf->setPrintHeader(false);

		  		
				$obj_pdf->setPrintFooter(false);
				/*$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));*/
				$obj_pdf->SetDefaultMonospacedFont('helvetica');
				/*$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);*/
				$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				$obj_pdf->SetFont('helvetica', '', 10);
				$obj_pdf->setFontSubsetting(false);
				//$obj_pdf->setPrintFooter(false);
				//$obj_pdf->SetAutoPageBreak(false);
				//$obj_pdf->setListIndentWidth(4);
				$obj_pdf->AddPage();

				/*$table_content = "";
				$sub_total = 0;
				$gst = 0;
				for($j = 0; $j < count($p); $j++)
				{
					$table_part_content = '<tr class="item-row">
						<td style="padding: 5px;">'.$p[$j]['invoice_description'].'</td>
						<td style="padding: 5px;text-align: right;">'.number_format($p[$j]['amount'],2).'</td>
					</tr><tr></tr>';
					$table_content = $table_content.$table_part_content;

					$sub_total += (float)$p[$j]['amount'];

					$gst += round((($p[$j]['gst_rate'] / 100) * (float)$p[$j]['amount']), 2);
				}*/
				/*<div style="text-align: center; color: black; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 10px; font-size: 14px; font-family: Georgia, Serif; overflow: hidden; resize: none; text-decoration: none; font-weight: bold;">'.$q[0]["document_name"].'</div>*/
				$content = $q[0]["content"];
				$obj_pdf->writeHTML($content, true, false, false, false, '');
				/*$obj_pdf->SetY(-20);
		        // Set font
		        $obj_pdf->SetFont('Helvetica','', 8);

		        $address = $query[0]["street_name"].'
#'.$query[0]["unit_no1"].$query[0]["unit_no2"].' '.$query[0]["building_name"].' 
Singapore '.$query[0]["postal_code"];

		        $div = '<div style="font-size: 10px; font-family: Arial, Sans-Serif;border-bottom: 1px solid black;text-align: center;position: fixed;bottom: 0;width: 100%;"><p>'.$address.' | '.$query[0]["telephone"].' | '.$query[0]["fax"].' | '.$query[0]["email"].' | '.$query[0]["url"].'</p></div>';
				
				$obj_pdf->writeHTMLCell(0, 0, '', '', $div, 0, 0, false, "L", true);*/

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].'secretary/pdf/document/'.$q[0]["document_name"].'.pdf', 'F');

    			$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';

				array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/secretary/pdf/document/'.$q[0]["document_name"].'.pdf');

			}
			echo json_encode(array("link" => $array_link)); 
			
		}
		
	}

 }

 class MYPDF extends TCPDF {

   
}
