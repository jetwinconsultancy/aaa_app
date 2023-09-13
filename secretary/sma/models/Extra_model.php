<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Extra_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption', 'session'));
    }

    public function send_approval_result($our_services_id, $result)
    {
    	$get_our_service_info_list = $this->db->get_where("our_service_info", array("id" => $our_services_id));
	    $get_our_service_info_list = $get_our_service_info_list->result_array();

    	$get_user_list = $this->db->get_where("users", array("id" => $get_our_service_info_list[0]["created_by"]));
        $get_user_list = $get_user_list->result_array();

        $requested_by = $get_user_list[0]["last_name"]." ".$get_user_list[0]["first_name"];

        $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', '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');

        $apiInstance = new SendinBlue\Client\Api\SMTPApi(
			// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
			// This is optional, `GuzzleHttp\Client` will be used as default.
			new GuzzleHttp\Client(),
			$config
        );
		$sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail(); // \SendinBlue\Client\Model\SendSmtpEmail | Values to send a transactional email
        $sendSmtpEmail['subject'] = "Approval Status for Services";
        $sender_email = json_decode('{"name":"ACUMEN ALPHA ADVISORY","email":"admin@aaa-global.com"}', true);
        $sendSmtpEmail['sender'] = $sender_email;
        $sendSmtpEmail['to'] = array(array("email"=> trim($get_user_list[0]["email"]))); //json_decode('[{"email":"justin@aaa-global.com"}]', true);//$get_user_list[0]["email"]
		$parse_data = array(
            '$approval_result' => $result,
            '$requested_by' => $requested_by,
            '$services_name' => $get_our_service_info_list[0]["service_name"]
        );
        $msg = file_get_contents('./themes/default/views/email_templates/approval_result.html');
        $message = $this->parser->parse_string($msg, $parse_data, true);

        $sendSmtpEmail['htmlContent'] = $message;
		try {
			$result = $apiInstance->sendTransacEmail($sendSmtpEmail);
		} catch (Exception $e) {
			echo 'Exception when calling SMTPApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
		}
    }
}