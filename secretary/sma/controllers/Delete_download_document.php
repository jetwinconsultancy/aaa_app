<?php
class Delete_download_document extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'Delete_download_document')
    {
        echo "Hello {$to}!".PHP_EOL;
    }


   	public function delete_document()
   	{
   		$this->load->helper("file");
		delete_files('./pdf/document/');
		delete_files('./pdf/invoice/');
		delete_files('./pdf/receipt/');
		delete_files('./pdf/credit_note/');
		delete_files('./assets/uploads/excel');
		delete_files('./pdf/claim');
		delete_files('./pdf/pv_receipt');
		delete_files('./pdf/payment_voucher');
   	}
}