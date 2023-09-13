<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_check_common_seal_and_stamp_record extends CI_Controller {

	//php index.php cron_billing message
    public function message($to = 'World')
    {
        $this->load->library('encryption');	
    }

    public function send_report()
   	{
   		$common_seal_q = $this->db->query("select * from purchase_common_seal_and_stamp_record where sended_at between date_sub(now(),INTERVAL 1 WEEK) and now()");
   		
   		$common_seal_array = $common_seal_q->result_array();

   		//echo json_encode($common_seal_array);

   		$config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
			// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
			// This is optional, `GuzzleHttp\Client` will be used as default.
			new GuzzleHttp\Client(),
			$config
        );
		$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = "Report of Self-inking round stamp or Common Seal";
        $sender_email = json_decode('{"name":"ACUMEN ALPHA ADVISORY","email":"admin@aaa-global.com"}', true);
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = array(array("email"=> trim("paul@aaa-global.com")));//paul
        //$sendSmtpEmail['cc'] = array(array("email" => trim($users_list[0]["email"])));

        $tr_common_seal_detail = "";
        foreach($common_seal_array as $key => $value)
    	{
			$tr_common_seal_detail = $tr_common_seal_detail . '<tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p>'.$value["company_name"].'</p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p>'.$value["uen"].'</p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p>'.$value["order_for"].'</p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p>'.$value["sended_at"].'</p>
                        </td>
                    </tr>';
        }

        $common_seal_detail = '
            <table style="width: 609px; border: 1px solid black; border-collapse: collapse;">
                <tbody>
                    <tr style="border: 1px solid black;">
                        <td style="border: 1px solid black; width: 484px; height: 20px;">
                            <p><strong>Company Name</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>UEN</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Order for</strong></p>
                        </td>
                        <td style="border: 1px solid black; width: 214px; height: 20px;">
                            <p><strong>Sended at</strong></p>
                        </td>
                    </tr>
                    '.$tr_common_seal_detail.'
                </tbody>
            </table>';

        $parse_data = array(
            '$common_seal_table' => $common_seal_detail
        );
        $msg = file_get_contents('./themes/default/views/email_templates/report_acknowledgement_page_email.html');
        $message = $this->parser->parse_string($msg, $parse_data, true);

        $sendSmtpEmail['htmlContent'] = $message;
		try {
			$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
		} catch (Exception $e) {
			echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
		}
   	}
}