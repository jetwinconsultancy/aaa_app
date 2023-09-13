<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header("Content-type:application/pdf");

require_once(__DIR__.'/../helpers/tcpdf/tcpdf.php');

class CreateDocumentPdf extends MY_Controller {

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

				$query = $this->db->query("select * from firm where id = '".$q[0]["firm_id"]."'");

				$query = $query->result_array();

		       	$this->load->helper('pdf_helper');

	    		create new PDF document
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

		       	//-----Engagement-----------
		  //      	$header_content = '';
		  //      	$obj_pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				// $obj_pdf->SetCreator(PDF_CREATOR);
				// $title = "Engagement";
				// $obj_pdf->SetTitle($title);
				// $obj_pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$header_content, $tc=array(0,0,0), $lc=array(0,0,0));

				// $obj_pdf->setPrintHeader(true);
				// $obj_pdf->setPrintFooter(true);

				// $obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER + 10);

				// // set margins
				// $obj_pdf->SetMargins(PDF_MARGIN_LEFT+10, PDF_MARGIN_TOP + 15, PDF_MARGIN_RIGHT+3);
				// $obj_pdf->SetAutoPageBreak(TRUE, 22);
				// $obj_pdf->AddPage();
				//------------------------------------------------

				$content = $q[0]["content"];
				$content = str_replace('class="check_new_page"', 'nobr="true"', $content);	// replace static 
				$obj_pdf->writeHTML($content, true, false, false, false, '');

				$obj_pdf->Output($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/document/'.$q[0]["document_name"].'.pdf', 'F');

				chmod($_SERVER['DOCUMENT_ROOT'].$this->systemName.'/pdf/document/'.$q[0]["document_name"].'.pdf',0644);

    			//$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
				$protocol = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
				array_push($array_link,$protocol . $_SERVER['SERVER_NAME'] .'/'.$this->systemName.'/pdf/document/'.$q[0]["document_name"].'.pdf');

			}
			echo json_encode(array("link" => $array_link)); 
			
		}
		
	}

 }

class MYPDF extends TCPDF {
	// public function Header() {
	// 	$headerData = $this->getHeaderData();
 //        $this->writeHTMLCell(0, 0, '', '', $headerData['string'], 0, 0, false, "L", true);
 //   	}

 //   public function Footer() {
	// 	// Position at 25 mm from bottom
 //        $this->SetY(-20);
 //        $this->SetX(30);
 //        // Set font
 //        $this->SetFont('helvetica', '', 8);
        
 //        // Page number
	// 	$this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	// 	$this->Ln();

	// 	$logoX = 148; // 186mm. The logo will be displayed on the right side close to the border of the page
	// 	$logoFileName = base_url()."/img/footer_img.png";
	// 	$logoWidth = 40; // 15mm
	// 	$logo = $this->Image($logoFileName, $logoX, $this->GetY(), $logoWidth);
 //   }
   
}

