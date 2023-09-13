<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will try guess the protocol, domain
| and path to your installation. However, you should always configure this
| explicitly and never rely on auto-guessing, especially in production
| environments.
|
*/
$config['base_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/TDS/aaa/secretary/';//test_secretary

$config['curr_ver'] = "0.1";

//$config['base_url'] = 'http://'.$_SERVER['SERVER_NAME'].'/test_secretary/';
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'REQUEST_URI' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
| 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
| 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
|
| WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
| See http://php.net/htmlspecialchars for a list of supported charsets.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = TRUE;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Composer auto-loading
|--------------------------------------------------------------------------
|
| Enabling this setting will tell CodeIgniter to look for a Composer
| package auto-loader script in application/vendor/autoload.php.
|
|	$config['composer_autoload'] = TRUE;
|
| Or if you have your vendor/ directory located somewhere else, you
| can opt to set a specific path as well:
|
|	$config['composer_autoload'] = '/path/to/vendor/autoload.php';
|
| For more information about Composer, please visit http://getcomposer.org/
|
| Note: This will NOT disable or override the CodeIgniter-specific
|	autoloading (application/config/autoload.php)
*/
$config['composer_autoload'] = 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| The configured value is actually a regular expression character group
| and it will be executed as: ! preg_match('/^[<permitted_uri_chars>]+$/i
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = ""; #keep it blank to allow all characters


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| You can also pass an array with threshold levels to show individual error types
|
| 	array(2) = Debug Messages, without Error Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ directory. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Log File Extension
|--------------------------------------------------------------------------
|
| The default filename extension for log files. The default 'php' allows for
| protecting the log files via basic scripting, when they are to be stored
| under a publicly accessible directory.
|
| Note: Leaving it blank will default to 'php'.
|
*/
$config['log_file_extension'] = '';

/*
|--------------------------------------------------------------------------
| Log File Permissions
|--------------------------------------------------------------------------
|
| The file system permissions to be applied on newly created log files.
|
| IMPORTANT: This MUST be an integer (no quotes) and you MUST use octal
|            integer notation (i.e. 0700, 0644, etc.)
*/
$config['log_file_permissions'] = 0644;

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Error Views Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/views/errors/ directory.  Use a full server path with trailing slash.
|
*/
$config['error_views_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/cache/ directory.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Cache Include Query String
|--------------------------------------------------------------------------
|
| Set this to TRUE if you want to use different cache files depending on the
| URL query string.  Please be aware this might result in numerous cache files.
|
*/
$config['cache_query_string'] = FALSE;

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class, you must set an encryption key.
| See the user guide for more info.
|
| http://codeigniter.com/user_guide/libraries/encryption.html
|
*/
$config['encryption_key'] = 'asdf3123sadf1ssww2w';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependant.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'sess';
$config['sess_expiration'] = 18000; //300
$config['sess_expire_on_close'] = TRUE;
$config['sess_save_path'] = 'sessions';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 100; //100
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
| 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
| 'cookie_path'     = Typically will be a forward slash
| 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
| 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
|
| Note: These settings (with the exception of 'cookie_prefix' and
|       'cookie_httponly') will also affect sessions.
|
*/
$config['cookie_prefix']	= 'sma_';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

/*
|--------------------------------------------------------------------------
| Standardize newlines
|--------------------------------------------------------------------------
|
| Determines whether to standardize newline characters in input data,
| meaning to replace \r\n, \r, \n occurences with the PHP_EOL value.
|
| This is particularly useful for portability between UNIX-based OSes,
| (usually \n) and Windows (\r\n).
|
*/
$config['standardize_newlines'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
| WARNING: This feature is DEPRECATED and currently available only
|          for backwards compatibility purposes!
|
*/
$config['global_xss_filtering'] = TRUE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
| 'csrf_regenerate' = Regenerate token on every submission
| 'csrf_exclude_uris' = Array of URIs which ignore CSRF checks
*/
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'token';
$config['csrf_cookie_name'] = 'token_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = FALSE;
$config['csrf_exclude_uris'] = array('auth/login', 'masterclient/save', 'payments/paypalipn', 'payments/skrillipn', 'welcome/image_upload', 'personprofile/update', 'personprofile/uploadFile', 'personprofile/deleteFile/[\s\S]*', 'personprofile/uploadCompanyFile', 'personprofile/deleteCompanyFile/[\s\S]*', 'masterclient/add_officer', 'masterclient/add', 'masterclient/get_officer', 'masterclient/get_director', 'masterclient/delete_officer', 'masterclient/get_currency', 'masterclient/add_charge', 'masterclient/delete_charge', 'masterclient/delete_share_capital', 'masterclient/add_share_capital', 'masterclient/get_sharetype', 'masterclient/get_person', 'masterclient/delete_allotment', 'masterclient/get_client_officers_position', 'companytype/getDirectorSignature1', 'companytype/getDirectorSignature2', 'masterclient/add_client_billing_info', 'masterclient/get_billing_info_service', 'masterclient/get_billing_info_frequency', 'billings/get_billing_info', 'billings/save_receipt', 'createbillingpdf/create_billing_pdf', 'billings/get_company_service', 'billings/save_billing', 'our_firm/uploadFile', 'masterclient/get_allotment_people' ,'masterclient/get_allotment_certificate','masterclient/check_first_due_date_175','masterclient/add_filing_info','masterclient/delete_filing','masterclient/search_register', 'billings/get_gst_rate', 'masterclient/get_edit_allotment_certificate', 'masterclient/get_amount_share', 'masterclient/check_allotment_object', 'personprofile/updateCompany', 'masterclient/get_template_billing_info_service','billings/save_template', 'companytype/getAllChairman', 'companytype/getChairman', 'billings', 'masterclient/check_incorporation_date', 'masterclient/refresh_member', 'masterclient/delete_client','billings/get_receipt_info', 'masterclient/check_cert_no', 'masterclient/filter_position', 'masterclient/add_guarantee', 'masterclient/delete_guarantee', 'masterclient/get_guarantee_officer', 'masterclient/delete_controller', 'masterclient/add_controller', 'masterclient/get_nationality', 'documents/add_document_toggle', 'documents/clientSearch', 'documents/add_pending_document', 'documents/insert_pending_document_file', 'documents/uploadDocumentFile', 'documents/uploadDocumentFile/trans', 'documents/deleteDocumentFile/[\s\S]*', 'documents/delete_document', 'documents/get_billing_info_service', 'documents/delete_master_document', 'documents/add_document_reminder', 'documents/delete_reminder_document', 'masterclient/check_client_data', 'masterclient/check_officer_data', 'masterclient/check_controller_data', 'masterclient/check_charge_data', 'masterclient/check_filing_data', 'masterclient/check_guarantee_data', 'createdocumentpdf/create_document_pdf', 'report/search_report', 'our_firm/check_default_company', 'our_firm/change_default_company', 'auth/check_status', 'auth/delete_user', 'our_firm/check_in_use_company', 'our_firm/change_in_use_company', 'system_settings/getUser', 'system_settings/getAccessRight', 'user_billings/get_billing_info_admin_service', 'masterclient/get_director_appointment_date', 'companytype/getTodayDirectorSignature1', 'companytype/getTodayDirectorSignature2', 'masterclient/get_latest_retire_director','billings/delete_billing', 'masterclient/delete_allot_follow_by_cert', 'masterclient/change_auto_generate', 'masterclient/delete_buyback_follow_by_cert', 'masterclient/get_the_previous_certificate', 'masterclient/get_transfer_certificate', 'masterclient/get_the_previous_certificate_for_to', 'masterclient/delete_to', 'masterclient/delete_transfer', 'masterclient/delete_subsequent_allotment', 'masterclient/delete_transfer_follow_by_cert', 'masterclient/check_transfer_share', 'masterclient/delete_subsequent_transfer', 'masterclient/get_transfer_people', 'masterclient/check_negative_number_of_share', 'masterclient/get_buyback_people', 'masterclient/register_get_director', 'auth/change_session', 'masterclient/get_type_of_day', 'masterclient/check_next_recurring_date', 'masterclient/get_billing_service', 'masterclient/add_setup_info', 'documents/get_reminder_tag', 'transaction/save_company_info', 'transaction/get_incorporation_new_company_page', 'transaction/get_director', 'transaction/add_officer', 'transaction/get_officer', 'transaction/delete_transaction_officer', 'transaction/add_controller', 'transaction/delete_controller', 'transaction/save_filing_info', 'transaction/get_client_officers_position', 'transaction/add_client_billing_info', 'transaction/get_billing_info_service', 'transaction/add_share_capital', 'transaction/save_allotment', 'transaction/delete_member', 'transaction/delete_billing', 'companytype/getTransactionDirectorSignature2', 'companytype/getTodayTransactionDirectorSignature2', 'companytype/getTransactionDirectorSignature1', 'companytype/getTodayTransactionDirectorSignature1', 'companytype/getTransactionChairman', 'companytype/getAllTransactionChairman', 'transaction_document/generate_document', 'transaction/get_latest_document', 'transaction/get_all_transaction_incorporation_info', 'transaction/get_appointment_of_director_page', 'transaction/check_client_info', 'transaction/add_appoint_new_director', 'transaction/get_all_appoint_new_director_info', 'transaction/get_resign_of_director_page', 'transaction/get_change_of_reg_ofis_page', 'transaction/add_new_regis_office_address', 'transaction/get_change_regis_office_address_info', 'transaction/get_appointment_of_auditor_page', 'transaction/get_resign_director_info', 'transaction/add_resign_director', 'transaction/get_all_resign_director_info', 'transaction/get_resign_director_info', 'transaction/add_appoint_resign_auditor', 'transaction/get_all_appoint_resign_auditor_info', 'transaction/get_resign_auditor_info', 'transaction/get_change_of_company_name_page', 'transaction/add_new_company_name', 'transaction/get_change_company_name_info', 'transaction/get_change_of_biz_activity_page', 'transaction/add_new_biz_activity', 'transaction/get_change_biz_activity_info', 'transaction/get_change_of_FYE_page', 'transaction/check_filing_info', 'transaction/add_new_fye', 'transaction/get_change_FYE_info', 'transaction/get_share_allot_page', 'transaction/save_share_allotment', 'transaction/get_share_allotment_info', 'transaction/get_share_transfer_page', 'transaction/get_transfer_people', 'transaction/save_share_transfer', 'transaction/get_share_transfer_info', 'transaction/edit_share_transfer_page', 'transaction/get_agm_ar_page', 'documents/delete_transaction_document', 'transaction/get_all_member', 'transaction/get_all_director_retiring', 'transaction/save_agm_ar', 'transaction/get_agm_ar_info', 'transaction_document/delete_document', 'createbillingpdf/delete_invoice', 'createbillingpdf/delete_receipt', 'auth/delete_client_user', 'masterclient/calculate_new_filing_date', 'transaction/cancel_transaction_by_user', 'transaction/get_opening_bank_account_page', 'transaction/select_banker_info', 'transaction/delete_member_transfer', 'transaction/check_lodge_status', 'auth/close_browser', 'transaction/get_incorporation_subsidiary_page', 'transaction/add_incorp_subsidiary', 'transaction/get_incorp_subsidiary_info', 'transaction/get_transaction_person', 'our_firm/deleteFile/[\s\S]*', 'masterclient/check_latest_fye', 'masterclient/get_next_eci_filing_due_date', 'masterclient/add_eci_filing_info', 'masterclient/delete_eci_filing', 'masterclient/check_latest_fye_for_tax', 'masterclient/get_tax_period_due_date', 'masterclient/add_tax_filing_info', 'masterclient/delete_tax_filing', 'our_firm/add_bank_info', 'our_firm/delete_bank_info', 'our_services/save_our_service_data', 'our_services/delete_our_service_data', 'our_firm/check_in_use_bank', 'our_firm/change_in_use_bank', 'billings/save_recurring', 'masterclient/delete_client_billing_info', 'transaction/get_issue_director_fee', 'transaction/add_issue_director_fee', 'transaction/get_issue_director_fee_info', 'transaction/get_issue_dividend', 'transaction/get_before_cut_off_date_member', 'transaction/add_issue_dividend', 'transaction/get_issue_dividend_info', 'transaction/get_strike_off_page', 'transaction/add_strike_off', 'transaction/get_strike_off_info', 'transaction/get_appointment_of_secretarial_page', 'transaction/add_appoint_new_secretarial', 'transaction/get_all_appoint_new_secretarial_info', 'transaction/check_valid_officer', 'transaction/get_take_over_of_secretarial_page', 'transaction/save_previous_secretarial_info', 'transaction/get_all_take_secretarial_info', 'transaction/add_follow_up_info', 'transaction/delete_follow_up_history', 'transaction/get_service_proposal_page', 'transaction/add_service_proposal', 'transaction/get_service_proposal_info', 'transaction/get_engagement_letter_page', 'transaction/add_engagement_letter', 'transaction/get_engagement_letter_info', 'billings/save_credit_note', 'billings/get_credit_note_info', 'createbillingpdf/delete_credit_note', 'masterclient/check_latest_fye_for_gst', 'masterclient/delete_gst_filing', 'payment_voucher/save_vendor', 'payment_voucher/add_vendor_setup_info', 'payment_voucher/delete_vendor', 'payment_voucher/get_vendor_address', 'payment_voucher/save_payment_voucher', 'createpvpdf/create_pv_pdf', 'payment_voucher/cancel_pv', 'payment_voucher/approve_pv', 'payment_voucher/save_claim', 'createclaimpdf/create_claim_pdf', 'payment_voucher/cancel_claim', 'payment_voucher/approve_claim', 'payment_voucher/save_claim_cheque', 'payment_voucher/save_payment_cheque', 'report/get_name', 'payment_voucher/export_excel', 'payment_voucher/delete_pv_excel', 'createclaimpdf/delete_claim_pdf', 'createpvpdf/delete_pv_pdf', 'payment_voucher/save_payment_receipt', 'payment_voucher/cancel_pv_receipt', 'payment_voucher/approve_pv_receipt', 'payment_voucher/save_receipt_cheque', 'createpvreceiptpdf/create_pv_receipt_pdf', 'createpvreceiptpdf/delete_receipt_pdf', 'masterclient/calculate_new_gst_date', 'billings/get_transaction_company_service', 'transaction/get_create_billing_interface', 'billings/save_transaction_create_billing', 'transaction/check_transaction_master_with_billing', 'transaction/get_status_and_follow_up_detail', 'transaction/get_ml_quarterly_statements_page', 'transaction/add_ml_quarterly_statements_info', 'transaction/get_ml_quarterly_statements_info', 'report/get_bank_info', 'auth/add_rules_info', 'auth/delete_rules_info', 'auth/get_rules', 'billings/get_edit_billing_info', 'masterclient/search_client_billing', 'masterclient/submit_signing_information', 'masterclient/submit_contact_information', 'masterclient/submit_reminder', 'masterclient/submit_corporate_representative', 'transaction/select_address_service_engagement', 'transaction/check_number_of_share_person', 'transaction/check_edit_number_of_share_person', 'transaction/save_share_transfer_latest_cert_number', 'personprofile/kycIndividualUpdate', 'personprofile/kycCheckIndividualInfo', 'personprofile/saveRiskReport', 'personprofile/kycIndividualRiskReportInfo', 'personprofile/kycCheckCorporateInfo', 'personprofile/kycCorporateUpdate', 'personprofile/kycCorporateRiskReportInfo', 'personprofile/updateApprovalStatus', 'masterclient/deactivateServiceEngagement', 'billings/get_out_of_balance_receipt_info', 'transaction/get_transaction_share_transfer_record', 'masterclient/check_current_number_of_share_person', 'masterclient/getShareTransferInfo', 'welcome/update_acknowledgement', 'masterclient/get_client_code', 'report/export_register_of_controller', 'masterclient/refresh_controller', 'masterclient/save_business_activity_list', 'admin_setting/add_jurisdiction_info', 'admin_setting/delete_jurisdiction_info', 'admin_setting/save_category_list', 'admin_setting/get_edit_category', 'transaction/save_company_info_and_status', 'transaction/save_notice', 'transaction/save_agenda', 'transaction/save_ar_declaration', 'masterclient/search_letter_of_conf_auditor_function', 'createlistofconfauditor/create_pdf', 'masterclient/get_controller_info', 'transaction/get_update_register_of_controller', 'transaction/get_register_controller_info', 'transaction/add_register_controller', 'transaction/get_controller_info', 'transaction/delete_register_controller', 'billings/get_our_service_info_for_transaction', 'transaction/get_conf_register_controller_info', 'masterclient/add_nominee_director', 'masterclient/get_nominee_director_info', 'masterclient/refresh_nominee_director', 'masterclient/delete_nominee_director', 'transaction/get_update_register_of_nominee_director', 'transaction/get_register_nominee_director_info', 'transaction/add_nominee_director', 'transaction/get_nominee_director_info', 'transaction/delete_nominee_director', 'transaction/get_conf_register_nominee_director_info', 'billings/get_previous_credit_note', 'billings/get_a_billing_info', 'billings/get_latest_credit_note_info', 'billings/get_client_invoice', 'createbillingpdf/create_old_credit_note_pdf', 'payment_voucher/get_client_address', 'transaction/get_omp_grant_page', 'transaction/add_omp_grant', 'transaction/get_omp_grant_info', 'transaction/get_upload_document_list', 'masterclient/save_company_document', 'masterclient/delete_company_document', 'transaction/get_transaction_task', 'transaction/get_resign_secretarial_info', 'transaction/check_previous_transaction', 'billings/get_client_transaction_company_service', 'transaction/add_lodgement_info', 'billings/check_progress_billing_data', 'admin_setting/get_edit_payment_voucher_type', 'admin_setting/save_payment_voucher_type', 'masterclient/submit_related_group', 'masterclient/showClientDO', 'transaction/get_purchase_common_seal_page', 'transaction/add_purchase_common_seal', 'transaction/get_purchase_common_seal_info', 'transaction/save_strike_off_notice', 'our_services/approve_our_service_data', 'our_services/reject_our_service_data', 'transaction/set_transaction_master_id', 'transaction/send_common_seal_email', 'billings/get_client_address', 'transaction/send_common_seal_email_under_services', 'quickbook_auth/auth_request_accounting', 'our_services/import_service_to_qb', 'our_services/get_income_account', 'billings/import_all_invoice_to_qb', 'billings/create_invoice_in_qb', 'billings/create_receipt_in_qb', 'billings/import_all_receipt_to_qb', 'billings/create_credit_note_in_qb', 'billings/import_all_cn_to_qb', 'masterclient/import_qb_client_to_quickbook', 'masterclient/import_client_to_quickbook', 'createbillingpdf/create_all_statement_pdf', 'transaction/import_qb_client_to_quickbook','transaction/getdata','personprofile/serverside');

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| Only used if zlib.output_compression is turned off in your php.ini.
| Please do not use it together with httpd-level output compression.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or any PHP supported timezone. This preference tells
| the system whether to use your server's local time as the master 'now'
| reference, or convert it to the configured one timezone. See the 'date
| helper' page of the user guide for information regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = TRUE;


/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy
| IP addresses from which CodeIgniter should trust headers such as
| HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
| the visitor's IP address.
|
| You can use both an array or a comma-separated list of proxy addresses,
| as well as specifying whole subnets. Here are a few examples:
|
| Comma-separated:	'10.0.1.200,192.168.5.0/24'
| Array:		array('10.0.1.200', '192.168.5.0/24')
*/
$config['proxy_ips'] = '';
