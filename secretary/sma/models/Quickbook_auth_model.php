<?php defined('BASEPATH') OR exit('No direct script access allowed');

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;

class Quickbook_auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption', 'session'));
    }

    public function get_refresh_token_accounting($quickbook_clientID, $quickbook_clientSecret)
    {
        if($this->session->userdata('refresh_token_value'))
        {
        	//The first parameter of OAuth2LoginHelper is the ClientID, second parameter is the client Secret
    		$oauth2LoginHelper = new OAuth2LoginHelper($quickbook_clientID, $quickbook_clientSecret);
    		
    		$accessTokenObj = $oauth2LoginHelper->
    		                    refreshAccessTokenWithRefreshToken($this->session->userdata('refresh_token_value'));

    		$accessTokenValue = $accessTokenObj->getAccessToken();
    		$refreshTokenValue = $accessTokenObj->getRefreshToken();

            $data['deleted'] = 1;
            $this->db->update("qb_access_token_record",$data,array("refresh_token" =>  $this->session->userdata('refresh_token_value')));

            $qb_access_token["qb_company_id"] = $this->session->userdata("qb_company_id");
            $qb_access_token["access_token"] = $accessTokenValue;
            $qb_access_token["refresh_token"] = $refreshTokenValue;

            $this->db->insert("qb_access_token_record",$qb_access_token);

    		$this->session->set_userdata(array(
                    'access_token_value' 	=> $accessTokenValue,
                    'refresh_token_value' 	=> $refreshTokenValue
            ));

            return true;
        }
        else
        {
            return false;
        }
    }
}