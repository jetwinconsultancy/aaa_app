<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->loggedIn = $this->sma->logged_in();
        if(strtolower($this->router->fetch_method()) != "get_our_service_data")
        {
            $this->Settings = $this->site->get_setting();

            //$this->output->delete_cache();

            if($sma_language = $this->input->cookie('sma_language', TRUE)) {
                $this->config->set_item('language', $sma_language);
                $this->lang->load('sma', $sma_language);
                $this->Settings->user_language = $sma_language;
            } else {
                $this->config->set_item('language', $this->Settings->language);
                $this->lang->load('sma', $this->Settings->language);
                $this->Settings->user_language = $this->Settings->language;
            }
            if($rtl_support = $this->input->cookie('sma_rtl_support', TRUE)) {
                $this->Settings->user_rtl = $rtl_support;
            } else {
                $this->Settings->user_rtl = $this->Settings->rtl;
            }
            $this->theme = $this->Settings->theme.'/views/';
            if(is_dir(VIEWPATH.$this->Settings->theme.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR)) {
                $this->data['assets'] = base_url() . 'themes/' . $this->Settings->theme . '/assets/';
            } else {
                $this->data['assets'] = base_url() . 'themes/default/assets/';
            }

            $this->data['Settings'] = $this->Settings;
            

            if($this->loggedIn) {
                //$this->default_currency = $this->site->getCurrencyByCode($this->Settings->default_currency);
                $this->data['default_currency'] = $this->default_currency;
                $this->Owner = $this->sma->in_group('owner') ? TRUE : NULL;
                $this->data['Owner'] = $this->Owner;
                $this->Client = $this->sma->in_group('client') ? TRUE : NULL;
                $this->data['Client'] = $this->Client;
                $this->Supplier = $this->sma->in_group('supplier') ? TRUE : NULL;
                $this->data['Supplier'] = $this->Supplier;
                $this->Admin = $this->sma->in_group('admin') ? TRUE : NULL;
                $this->data['Admin'] = $this->Admin;
                $this->Individual = $this->sma->user_type('Individual') ? TRUE : NULL;
                $this->data['Individual'] = $this->Individual;

                if($sd = $this->site->getDateFormat($this->Settings->dateformat)) {
                    $dateFormats = array(
                        'js_sdate' => $sd->js,
                        'php_sdate' => $sd->php,
                        'mysq_sdate' => $sd->sql,
                        'js_ldate' => $sd->js . ' hh:ii',
                        'php_ldate' => $sd->php . ' H:i',
                        'mysql_ldate' => $sd->sql . ' %H:%i'
                        );
                } else {
                    $dateFormats = array(
                        'js_sdate' => 'mm-dd-yyyy',
                        'php_sdate' => 'm-d-Y',
                        'mysq_sdate' => '%m-%d-%Y',
                        'js_ldate' => 'mm-dd-yyyy hh:ii:ss',
                        'php_ldate' => 'm-d-Y H:i:s',
                        'mysql_ldate' => '%m-%d-%Y %T'
                        );
                }
                if(file_exists(APPPATH.'controllers'.DIRECTORY_SEPARATOR.'Pos.php')) {
                    define("POS", 1);
                } else {
                    define("POS", 0);
                }
                if(!$this->Owner && !$this->Admin) {
                    $gp = $this->site->checkPermissions();
                    $this->GP = $gp[0];
                    $this->data['GP'] = $gp[0];
                } else {
                    $this->data['GP'] = NULL;
                }
                $this->dateFormats = $dateFormats;
                $this->data['dateFormats'] = $dateFormats;
                $this->load->language('calendar');
                //$this->default_currency = $this->Settings->currency_code;
                //$this->data['default_currency'] = $this->default_currency;
                $this->m = strtolower($this->router->fetch_class());
                $this->v = strtolower($this->router->fetch_method());
                $this->data['m']= $this->m;
                $this->data['v'] = $this->v;
                $this->data['dt_lang'] = json_encode(lang('datatables_lang'));
                $this->data['dp_lang'] = json_encode(array('days' => array(lang('cal_sunday'), lang('cal_monday'), lang('cal_tuesday'), lang('cal_wednesday'), lang('cal_thursday'), lang('cal_friday'), lang('cal_saturday'), lang('cal_sunday')), 'daysShort' => array(lang('cal_sun'), lang('cal_mon'), lang('cal_tue'), lang('cal_wed'), lang('cal_thu'), lang('cal_fri'), lang('cal_sat'), lang('cal_sun')), 'daysMin' => array(lang('cal_su'), lang('cal_mo'), lang('cal_tu'), lang('cal_we'), lang('cal_th'), lang('cal_fr'), lang('cal_sa'), lang('cal_su')), 'months' => array(lang('cal_january'), lang('cal_february'), lang('cal_march'), lang('cal_april'), lang('cal_may'), lang('cal_june'), lang('cal_july'), lang('cal_august'), lang('cal_september'), lang('cal_october'), lang('cal_november'), lang('cal_december')), 'monthsShort' => array(lang('cal_jan'), lang('cal_feb'), lang('cal_mar'), lang('cal_apr'), lang('cal_may'), lang('cal_jun'), lang('cal_jul'), lang('cal_aug'), lang('cal_sep'), lang('cal_oct'), lang('cal_nov'), lang('cal_dec')), 'today' => lang('today'), 'suffix' => array(), 'meridiem' => array()));

            }
        }
        
       /* if ($this->session->userdata['logged'] == FALSE)
        {
            redirect('logout');
        }*/
    }

    function page_construct($page, $meta = array(), $data = array()) {
        $meta['message'] = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $meta['error'] = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $meta['warning'] = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        //$meta['info'] = $this->site->getNotifications();
        //$meta['events'] = $this->site->getUpcomingEvents();
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Owner'] = $data['Owner'];
        $meta['Admin'] = $data['Admin'];
        $meta['User'] = $data['User'];
        $meta['Client'] = $data['Client'];
        $meta['Individual'] = $data['Individual'];
        $meta['Settings'] = $data['Settings'];
        $meta['dateFormats'] = $data['dateFormats'];
        $meta['assets'] = $data['assets'];
        $meta['GP'] = $data['GP'];
        $meta['qty_alert_num'] = $this->site->get_total_qty_alerts();
        $meta['exp_alert_num'] = $this->site->get_expiring_qty_alerts();

        //$files = $this->db->get_where('firm',array('user_id'=>$this->session->userdata('user_id')));

        if(!$this->Owner) 
        {
            $this->db->select('firm.*, user_firm.default_company, user_firm.in_use')
                    ->from('firm')
                    ->join('user_firm', 'user_firm.firm_id = firm.id AND user_firm.user_id = '.$this->session->userdata('user_id'), 'left')
                    ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                    ->where('user_firm.in_use = 1');
                        
        }
        else
        {
            $this->db->select('firm.*')
                    ->from('firm')
                    ->where('id = '.$this->session->userdata('firm_id'));
        }
        $files = $this->db->get();
        $file_info = $files->result_array();
        $meta['firm_name'] = $file_info[0]["name"];
        $meta['logo'] = $file_info[0]["file_name"];
        $this->session->set_userdata('firm_id', $file_info[0]["id"]);

        $this->db->select('*')
                ->from('user_firm')
                ->where('user_firm.firm_id = '.$this->session->userdata('firm_id'))
                ->where('user_firm.user_id = '.$this->session->userdata('user_id'))
                ->where('user_firm.in_use = 1');
        $access_right = $this->db->get();
        $access_right = $access_right->result_array();

        $meta['client_module'] = $access_right[0]["client_module"];
        $meta['company_info_module'] = $access_right[0]["company_info_module"];
        $meta['officer_module'] = $access_right[0]["officer_module"];
        $meta['member_module'] = $access_right[0]["member_module"];
        $meta['controller_module'] = $access_right[0]["controller_module"];
        $meta['charges_module'] = $access_right[0]["charges_module"];
        $meta['filing_module'] = $access_right[0]["filing_module"];
        $meta['register_module'] = $access_right[0]["register_module"];
        $meta['setup_module'] = $access_right[0]["setup_module"];
        $meta['person_module'] = $access_right[0]["person_module"];
        $meta['document_module'] = $access_right[0]["document_module"];
        $meta['pending_module'] = $access_right[0]["pending_module"];
        $meta['all_module'] = $access_right[0]["all_module"];
        $meta['master_module'] = $access_right[0]["master_module"];
        $meta['reminder_module'] = $access_right[0]["reminder_module"];
        $meta['report_module'] = $access_right[0]["report_module"];
        $meta['billing_module'] = $access_right[0]["billing_module"];
        $meta['unpaid_module'] = $access_right[0]["unpaid_module"];
        $meta['paid_module'] = $access_right[0]["paid_module"];
        $meta['receipt_module'] = $access_right[0]["receipt_module"];
        $meta['template_module'] = $access_right[0]["template_module"];

        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');

        $this->session->set_userdata(array(
                'last_activity' => time()
        ));
    }

}
