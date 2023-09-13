<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(__DIR__ . '/vendor/autoload.php');

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
            'firm_email' => "justin@aaa-global.com",//then.k.w@hotmail.com
            'user_name' => "Justin",
            'email' => "justin@aaa-global.com",
            'total_amount' => "100.00",
            'issue_date' => "10/4/2018",
            'currency_name' => "SGD",
            'company_name' => "VENTURE CORPORATE SERVICES PTE. LTD."
        );
        $msg = file_get_contents('./themes/default/views/email_templates/recurring_invoice.html');
        $message = $this->parser->parse_string($msg, $parse_data);
        //$message = "<p>Thanks!</p>";


        $subject =  'INVOICE FOR AAA';

        //$undersigned = base_url().'img/acumen_bizcorp_header.jpg';
        $invoice_pdf_link = $_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf';

        $check_email_send_to_contact_person = $this->sma->send_email('justin@aaa-global.com', $subject, $message.'<p>Best regards,<br />Mr Paul Yeap<br />ACUMENBIZCORP PTE. LTD.<br />Address: 18 Howard Road, #08-06 Novelty BizCentre, Singapore 369585<br />Tel: (+65) 62468801 / (+ 65) 62468802</p>', 'admin@bizfiles.com.sg', 'ACT Secretary', $invoice_pdf_link);

        echo json_encode($check_email_send_to_contact_person);
    }

    public function test_sendinblue()
    {
        $attachment = array();
        $pdfDocPath = array();
        // Configure API key authorization: api-key
        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
        // Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('api-key', 'Bearer');
        // Configure API key authorization: partner-key
        //$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', 'YOUR_API_KEY');
        // Uncomment below to setup prefix (e.g. Bearer) for API key, if needed
        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKeyPrefix('partner-key', 'Bearer');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            new GuzzleHttp\Client(),
            $config
        );
        //$email_campaigns = 'sender: {email: "justin@aaa-global.com"}, to: [{email: "then@acumenbizcorp.com.sg", name: "Then"}], replyTo: {email: "justin@aaa-global.com"}, templateId: 1';
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = "My subject";
        $sendSmtpEmail['sender'] = array("name" => "Admin", "email" => "admin@aaa-global.com");
        // $ar=array(); 
        //$ar=array(array("email"=> "then@acumenbizcorp.com.sg", "name"=> "Then"));
        // $to = new to();
        // $to->name = 'Then';
        // $to->email = 'then@acumenbizcorp.com.sg';
        //$ar=array($to); 
        print_r($sendSmtpEmail['sender']);

        $sendSmtpEmail['to'] = array(array("email"=> "then@acumenbizcorp.com.sg", "name"=> "Then"));
        $sendSmtpEmail['htmlContent'] = "Congratulations! You successfully sent this example campaign via the SendinBlue API.";
        //array_push($attachment, $pdfDocPath);
        //$pdfDocPath = array(array("content" => base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf')), "name" => "AA-20200013.pdf"));
        $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
        $attachment['name'] = "AA-20200013.pdf";
        array_push($pdfDocPath, $attachment);
        
        $sendSmtpEmail['attachment'] = $pdfDocPath;
        //print_r($pdfDocPath);
        
        try {
            $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
            print_r($result);
        } catch (Exception $e) {
            echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
        }

        //# Instantiate the client\
        // SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey("api-key", "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");

        // $api_instance = new SendinBlue\Client\Api\EmailCampaignsApi();
        // $emailCampaigns = new \SendinBlue\Client\Model\CreateEmailCampaign();

        // # Define the campaign settings\
        // $email_campaigns['name'] = "Campaign sent via the API";
        // $email_campaigns['subject'] = "My subject";
        // $email_campaigns['sender'] = array("name" => "From name", "email" => "justin@aaa-global.com");
        // $email_campaigns['type'] = "classic";

        //     # Content that will be sent\
        // $email_campaigns['htmlContent'] = "Congratulations! You successfully sent this example campaign via the SendinBlue API.";

        //     # Select the recipients\
        //  $email_campaigns['recipients'] = array("listIds"=> [2]);

        //     # Schedule the sending in one hour\
        //  $email_campaigns['scheduledAt'] = "2018-01-01 00:00:01";
        

        // # Make the call to the client\
        // try {
        //     $result = $api_instance->createEmailCampaign($emailCampaigns);
        //     print_r($result);
        // } catch (Exception $e) {
        //     echo 'Exception when calling EmailCampaignsApi->createEmailCampaign: ', $e->getMessage(), PHP_EOL;
        // }


        // $dataEmail = new \SendinBlue\Client\Model\SendEmail();
        // $dataEmail['emailTo'] = ['then@acumenbizcorp.com.sg'];
        // // PDF wrapper
        // $pdfDocPath = $_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf';
        // $content = chunk_split(base64_encode(file_get_contents($pdfDocPath)));
        // // Ends pdf wrapper
        // $attachment_item = array(
        //         'name'=>'AA-20200013.pdf',
        //         'content'=>$content
        // );
        // $attachment_list = array($attachment_item);
        // // Ends pdf wrapper

        // $dataEmail['attachment']    = $attachment_list;

        // $templateId = 1;

        // $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        // $apiInstance = new SendinBlue\Client\Api\SMTPApi(new GuzzleHttp\Client(),$config);

        // try {
        //     $result = $apiInstance->sendTemplate($templateId, $dataEmail);
        //     print_r($result);
        // } catch (Exception $e) {
        //     echo 'Exception when calling SMTPApi->sendTemplate: ', $e->getMessage(), PHP_EOL;
        // }
    }
}