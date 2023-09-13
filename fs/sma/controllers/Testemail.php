<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testemail extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // $this->load->library('input');
        // $this->load->model('cron_model');
    }
    
    public function testinsert()
    {
        $billing_credit_note_record['testemail'] = "testemail";

        $this->db->insert("testemail",$billing_credit_note_record);
    }

    public function check_recurring_bill()
    {
        $parse_data = array(
            'firm_name' => "AAA PTE. LTD.",
            'firm_email' => "then.k.w@hotmail.com",
            'user_name' => "Justin",
            'email' => "then.k.w@hotmail.com",
            'total_amount' => "100.00",
            'issue_date' => "10/4/2018",
            'currency_name' => "SGD"
        );
        $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
        $message = $this->parser->parse_string($msg, $parse_data);


        $subject =  '(TESTING) INVOICE FOR AAA';

        //$undersigned = base_url().'img/acumen_bizcorp_header.jpg';
        $invoice_pdf_link = $_SERVER["DOCUMENT_ROOT"] .'/test_secretary/pdf/invoice/AAA-2019-0019.pdf';

        $check_email_send_to_contact_person = $this->sma->send_email('then.k.w@hotmail.com', $subject, $message.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'paul@acumenbizcorp.com.sg', 'ACT Secretary', $invoice_pdf_link);

        echo json_encode($invoice_pdf_link);
    }
}