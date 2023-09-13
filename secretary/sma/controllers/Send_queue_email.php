<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_queue_email extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'Send_email')
    {
        echo "Hello {$to}!".PHP_EOL;
    }

    //php index.php cron_billing check_recurring
   	public function start_to_send_mail()
   	{
   		$email_queue_info = $this->db->query("select * from email_queue where sended = 0 LIMIT 10");// AND auto_generate = 1

   		if ($email_queue_info->num_rows() > 0) 
      {
      	$email_queue_info = $email_queue_info->result_array();

        for($i = 0; $i < count($email_queue_info); $i++)
        {
          //$is_send = $this->sma->send_email(json_decode($email_queue_info[$i]['email']), $email_queue_info[$i]['subject'], $email_queue_info[$i]['message'], $email_queue_info[$i]['from_email'], $email_queue_info[$i]['from_name'], $email_queue_info[$i]['attachment'], $email_queue_info[$i]['cc'], $email_queue_info[$i]['bcc']);

          //$attachment = array();
          //$pdfDocPath = array();
          // Configure API key authorization: api-key
          //Justin API
          $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');
          //Xin Yee API
          //$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

          $apiInstance = new SendinBlue\Client\Api\SMTPApi(
              // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
              // This is optional, `GuzzleHttp\Client` will be used as default.
              new GuzzleHttp\Client(),
              $config
          );

          $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
          $sendSmtpEmail['subject'] = $email_queue_info[$i]['subject'];
          $sender_email = json_decode($email_queue_info[$i]['from_email'], true);
          $sendSmtpEmail['sender'] = $sender_email;
          $sendSmtpEmail['to'] = json_decode($email_queue_info[$i]['email'], true);
          if($email_queue_info[$i]['cc'] != null)
          {
            $sendSmtpEmail['cc'] = json_decode($email_queue_info[$i]['cc'], true);
          }
          $sendSmtpEmail['htmlContent'] = $email_queue_info[$i]['message'];

          // $attachment['content'] = base64_encode(file_get_contents($_SERVER["DOCUMENT_ROOT"] .'/secretary/pdf/invoice/AA-20200013.pdf'));
          // $attachment['name'] = "AA-20200013.pdf";
          //array_push($pdfDocPath, json_decode($email_queue_info[$i]['attachment'], true));
          
          $sendSmtpEmail['attachment'] = json_decode($email_queue_info[$i]['attachment'], true);

          //print_r($sendSmtpEmail);
          
          try {
              $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
              //print_r($result);
              if ($result) 
              {
                  $email_queue['sended'] = 1;
                  $email_queue['sendInBlueResult'] = $result;
                  $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
                  //echo 'Your Email has successfully been sent.';
              }
          } catch (Exception $e) {
              echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
          }

 			    // if ($is_send) 
        //   {

 					  //   $email_queue['sended'] = 1;
        //       $this->db->update("email_queue",$email_queue,array("id" => $email_queue_info[$i]['id']));
	       //      //echo 'Your Email has successfully been sent.';
	       //  }
        }
      }
   	}
}