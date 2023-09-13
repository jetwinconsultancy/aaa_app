<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class Quickbook_auth extends MY_Controller 
{
	function __construct()
    {
    	parent::__construct();
        $this->load->model(array('quickbook_auth_model'));
    }

    public function auth_request_accounting()
    {
    	//echo json_encode($this->redirectQBUrl);
    	$dataService = DataService::Configure(array(
		      'auth_mode' => 'oauth2',
		      'ClientID' => $this->quickbook_clientID,
		      'ClientSecret' => $this->quickbook_clientSecret,
		      'RedirectURI' => $this->redirectQBUrl,
		      'scope' => "com.intuit.quickbooks.accounting",
		      'baseUrl' => $this->quickbookBaseUrl
		));

		$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
		$authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

		header('Location: '. $authorizationCodeUrl);
    }

    public function auth_redirect_url_accounting()
    {
    	$code = $this->input->get('code', TRUE);
    	$realmId = $this->input->get('realmId', TRUE);

    	$dataService = DataService::Configure(array(
		      'auth_mode' => 'oauth2',
		      'ClientID' => $this->quickbook_clientID,
		      'ClientSecret' => $this->quickbook_clientSecret,
		      'RedirectURI' => $this->redirectQBUrl,
		      'scope' => "com.intuit.quickbooks.accounting",
		      'baseUrl' => $this->quickbookBaseUrl
		));

    	$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    	$accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmId);
    	$accessTokenValue = $accessTokenObj->getAccessToken();
    	$refreshTokenValue = $accessTokenObj->getRefreshToken();

    	$qb_access_token["qb_company_id"] = $this->session->userdata("qb_company_id");
    	$qb_access_token["access_token"] = $accessTokenValue;
        $qb_access_token["refresh_token"] = $refreshTokenValue;

        $this->db->insert("qb_access_token_record",$qb_access_token);

    	$this->session->set_userdata(array(
                'access_token_value' 	=> $accessTokenValue,
                'refresh_token_value' 	=> $refreshTokenValue
        ));

    	$this->save_audit_trail("Billings", "QuickBooks", $this->session->userdata('first_name'). " " . $this->session->userdata('last_name') . " login to QuickBooks.");

    	if($accessTokenValue)
    	{
	    	echo "<script type='text/javascript'>";
		    echo "window.close();";
		    echo "</script>";
		}
    	
    }

    public function revoke_token_accounting()
    {
    	try {

	    	$oauth2LoginHelper = new OAuth2LoginHelper($this->quickbook_clientID, $this->quickbook_clientSecret);
			$revokeResult = $oauth2LoginHelper->revokeToken($this->session->userdata('refresh_token_value'));
			
			if($revokeResult){
				$data['deleted'] = 1;

				$this->db->update("qb_access_token_record",$data,array("refresh_token" =>  $this->session->userdata('refresh_token_value')));

				$this->session->unset_userdata('access_token_value');
				$this->session->unset_userdata('refresh_token_value');
			    echo false;
			}
		}
		catch (Exception $e){
			echo 'Message: ' .$e;
		}
    }

    public function cron_job_refresh_token_value()
    {
    	$refresh_token_status = $this->quickbook_auth_model->get_refresh_token_accounting($this->quickbook_clientID, $this->quickbook_clientSecret);
    	echo $refresh_token_status;
    }

    public function access_token_value()
    {
    	echo json_encode($this->session->userdata('access_token_value'));
    }

    public function refresh_token_value()
    {
    	echo json_encode($this->session->userdata('refresh_token_value'));
    }

    public function save_audit_trail($modules, $events, $actions)
    {
        $secretary_audit_trail["user_id"] = $this->session->userdata("user_id");
        $secretary_audit_trail["modules"] = $modules;
        $secretary_audit_trail["events"] = $events;
        $secretary_audit_trail["actions"] = $actions;

        $this->db->insert("secretary_audit_trail",$secretary_audit_trail);
    }

    public function get_access_token() 
    {
    	$qb_access_token_record = $this->db->query("select * 
                                                        from  qb_access_token_record
                                                        where qb_company_id = '".$this->session->userdata("qb_company_id")."' AND deleted = 0 ORDER BY id DESC LIMIT 1");
        $qb_access_token_record = $qb_access_token_record->result_array();
        print_r($qb_access_token_record);
        print_r(count($qb_access_token_record));
        print_r($qb_access_token_record[0]["refresh_token"]);
    }

    public function get_tax_code()
    {
    	$url = $this->quickbookURL.'/v3/company/'.$this->session->userdata('qb_company_id').'/query?query=select%20*%20from%20TaxCode%20where%20Active=true';

        /* Init cURL resource */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        /* Array Parameter Data */
        //$data = ['Authorization' => 'Bearer '.$this->session->userdata('access_token_value')];
        $authorization = "Authorization:Bearer ".$this->session->userdata('access_token_value');

        curl_setopt($ch, CURLOPT_POST, false);

        /* pass encoded JSON string to the POST fields */
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(""));
            
        /* set the content type json */
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:text/plain', $authorization));
        //curl_setopt($ch, CURLOPT_TIMEOUT,500); // 500 seconds
        /* set return type json */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
        /* execute request */
        $result = curl_exec($ch);

        /* close cURL resource */
        curl_close($ch);

        $xml_snippet = simplexml_load_string( $result );
        $json_convert = json_encode( $xml_snippet );
        $json = json_decode( $json_convert );

        $taxCodeID = 21; // Default Out of Scope from Quickbook
        $taxCode = $json->QueryResponse->TaxCode;
        for($t = 0; $t < count($taxCode); $t++)
        {
            echo json_encode("1");
        }
        echo json_encode("2in");
    }
}
