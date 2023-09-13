<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ion_auth
{

    protected $status;
    public $_extra_where = array();
    public $_extra_set = array();
    public $_cache_user_in_group;

    public function __construct()
    {
        $this->load->config('ion_auth', TRUE);

        // Load IonAuth MongoDB model if it's set to use MongoDB,
        $this->load->model('auth/ion_auth_model');

        $this->_cache_user_in_group = &$this->ion_auth_model->_cache_user_in_group;

        //auto-login the user if they are remembered
        if (!$this->logged_in() && get_cookie('identity') && get_cookie('remember_code')) {
            if ($this->ion_auth_model->login_remembered_user()) {
                redirect($this->session->userdata('requested_page') ? $this->session->userdata('requested_page') : 'welcome');
            }
        }
        

        $this->ion_auth_model->trigger_events('library_constructor');
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->ion_auth_model, $method)) {
            throw new Exception('Undefined method Ion_auth::' . $method . '() called');
        }

        return call_user_func_array(array($this->ion_auth_model, $method), $arguments);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function forgotten_password($identity)
    {    //changed $email to $identity
        if ($this->ion_auth_model->forgotten_password($identity)) {   //changed
            // Get user information
            $user = $this->where($this->config->item('identity', 'ion_auth'), $identity)->where('active', 1)->where( "users.user_deleted", 0)->users()->row();  //changed to get_user_by_identity from email

            if ($user) {
                $data = array(
                    'identity' => $user->{$this->config->item('identity', 'ion_auth')},
                    'forgotten_password_code' => $user->forgotten_password_code
                );

                // if (!$this->config->item('use_ci_email', 'ion_auth')) {
                //     $this->set_message('forgot_password_successful');
                //     return $data;
                // } else {

                    $this->load->library('parser');
                    $parse_data = array(
                        'user_name' => $user->first_name . ' ' . $user->last_name,
                        'email' => $user->email,
                        'reset_password_link' => anchor('auth/reset_password/' . $user->forgotten_password_code, "here"),
                        'site_link' => base_url(),
                        'backup_link' => site_url('auth/reset_password/' . $user->forgotten_password_code),
                        'site_name' => 'HRM SYSTEM',
                        'logo' => '<img src="' . base_url() . 'assets/logo/logo.png" alt="HRM SYSTEM"/>'
                    );
                    $msg        = file_get_contents('./application/modules/auth/email_templates/forgot_password.html');
                    $subject    = 'Forgot Password - HRM SYSTEM';
                    $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                    $to_email   = json_encode(array(array("email"=> $user->email)));
                    $cc         = null;
                    $message    = $this->parser->parse_string($msg, $parse_data,true);

                    if ($this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null)) {
                        $this->set_message('forgot_password_successful');
                        return TRUE;
                    } else {
                        $this->set_error('sending_email_failed');
                        return FALSE;
                    }
                //}
            } else {
                $this->set_error('forgot_password_unsuccessful');
                return FALSE;
            }
        } else {
            $this->set_error('forgot_password_unsuccessful');
            return FALSE;
        }
    }

    public function forgotten_password_complete($code)
    {
        $this->ion_auth_model->trigger_events('pre_password_change');

        $identity = $this->config->item('identity', 'ion_auth');
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!$profile) {
            $this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        }

        $new_password = $this->ion_auth_model->forgotten_password_complete($code, $profile->salt);

        if ($new_password) {
            $data = array(
                'identity' => $profile->{$identity},
                'new_password' => $new_password
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->set_message('password_change_successful');
                $this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
                return $data;
            } else {

                $this->load->library('parser');
                $parse_data = array(
                    'client_name' => $profile->first_name . ' ' . $profile->last_name,
                    'email' => $profile->email,
                    'password' => $password,
                    'site_link' => base_url(),
                    'site_name' => $this->Settings->site_name,
                    'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                $msg        = file_get_contents('./application/modules/auth/email_templates/new_password.html');
                $subject    = lang('email_new_password_subject') . ' - ' . $this->Settings->site_name;
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM SYSTEM', "email" => "admin@aaa-global.com"));
                $to_email   = json_encode(array(array("email"=> $profile->email)));
                $cc         = null;
                $message    = $this->parser->parse_string($msg, $parse_data,true);

                if ($this->sma->send_by_sendinblue($subject, $from_email, $to_email, $cc, $message, null)) {
                    $this->set_message('password_change_successful');
                    $this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
                    return TRUE;
                } else {
                    $this->set_error('password_change_unsuccessful');
                    $this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
                    return FALSE;
                }
            }
        }

        $this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
        return FALSE;
    }

    public function forgotten_password_check($code)
    {
        $profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

        if (!is_object($profile)) {
            $this->set_error('password_change_unsuccessful');
            return FALSE;
        } else {
            if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
                //Make sure it isn't expired
                $expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
                if (time() - $profile->forgotten_password_time > $expiration) {
                    //it has expired
                    $this->clear_forgotten_password_code($code);
                    $this->set_error('password_change_unsuccessful');
                    return FALSE;
                }
            }
            return $profile;
        }
    }

    public function register($username, $user_type_id, $password, $email, $additional_data = array(), $term_and_condition, $active = FALSE, $notify = FALSE, $selected_firm = FALSE, $selected_person = FALSE)
    { //need to test email activation
        $this->auth_model->trigger_events('pre_account_creation');

        $email_activation = $this->config->item('email_activation', 'ion_auth');

        if (!$email_activation || $active == '1') {
            $id = $this->auth_model->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition, NULL, $active, $selected_firm, $selected_person);
            if ($id !== FALSE) {
                if ($notify) {
                    $this->load->library('parser');
                    $parse_data = array(
                        'client_name' => $additional_data['first_name'] . ' ' . $additional_data['last_name'],
                        'site_link' => site_url(),
                        'site_name' => 'HRM SYSTEM',
                        'email' => $email,
                        'password' => $password/*,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'*/
                    );
                    $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM', "email" => "admin@aaa-global.com"));

                    $msg = file_get_contents('./application/modules/auth/email_templates/credentials.html');
                    $message = $this->parser->parse_string($msg, $parse_data);
                    $subject = 'New User Created - ' . 'HRM SYSTEM';
                    $credential_email = json_encode(array(array("email"=> $email)));
                    $this->sma->send_by_sendinblue($subject, $from_email, $credential_email, null, $message, null);

                    if($additional_data['group_id'] != "4")
                    {
                        $welcome_email_msg = file_get_contents('./application/modules/auth/email_templates/welcome_email.html');
                        $welcome_email_message = $this->parser->parse_string($welcome_email_msg, $parse_data);
                        $welcome_email_subject = 'Welcome to our firm';
                        $welcome_email = json_encode(array(array("email"=> $email)));
                        $this->sma->send_by_sendinblue($welcome_email_subject, $from_email, $welcome_email, null, $welcome_email_message, null);
                    }
                }

                //$this->set_message('account_creation_successful');
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
                if($additional_data["group_id"] != "4")
                {
                    $this->recalculate();
                }
                return $id;
            } else {
                //$this->set_error('account_creation_unsuccessful');
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }
        } else {

            $id = $this->auth_model->register($username, $user_type_id, $password, $email, $additional_data, $term_and_condition, $this->auth_model->activation_code, $active);

            if (!$id) {
                $this->set_error('account_creation_unsuccessful');
                return FALSE;
            }

            $deactivate = $this->auth_model->deactivate($id);

            if (!$deactivate) {
                /*$this->set_error('deactivate_unsuccessful');*/
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
                return FALSE;
            }

            $activation_code = $this->auth_model->activation_code;
            $identity = $this->config->item('identity', 'ion_auth');
            $user = $this->auth_model->user($id)->row();

            $data = array(
                'identity' => $user->{$identity},
                'id' => $user->id,
                'email' => $email,
                'activation' => $activation_code,
            );
            if (!$this->config->item('use_ci_email', 'ion_auth')) {
                $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                $this->set_message('Account has been registered. Activation link will be sent to your email shortly.');

                return $data;
            } else {

                $this->load->library('parser');
                $parse_data = array(
                    'user_name' => $additional_data['first_name'] . ' ' . $additional_data['last_name'],
                    'site_link' => site_url(),
                    'site_name' => 'HRM SYSTEM',
                    'email' => $email,
                    'activation_link' => anchor('auth/activate/' . $data['id'] . '/' . $data['activation'], "here"),
                    'backup_link' => site_url('auth/activate/' . $data['id'] . '/' . $data['activation'])/*,
                    'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'*/
                );
                $from_email = json_encode(array("name" => 'ACUMEN ALPHA ADVISORY HRM', "email" => "admin@aaa-global.com"));
                $msg = file_get_contents('./application/modules/auth/email_templates/activate_email.html');
                $message = $this->parser->parse_string($msg, $parse_data);
                $subject = "Email Activation" . ' - ' . 'HRM SYSTEM';
                $credential_email = json_encode(array(array("email"=> $email)));

                if ($this->sma->send_by_sendinblue($subject, $from_email, $credential_email, null, $message, null)) {
                    $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
                    
                    $this->set_message('Account has been registered. Activation link will be sent to your email shortly.');
                    if($additional_data["group_id"] != "4")
                    {
                        $this->recalculate();
                    }
                    return $id;
                }
            }

            $this->auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
            $this->set_error('activation_email_unsuccessful');
            return FALSE;
        }
    }

    public function recalculate()
    {
        // $this->db->select("users.id")
        //         ->from("users")
        //         ->join('groups', 'users.group_id = groups.id', 'inner')
        //         ->join('user_firm as a', 'a.user_id = "'.$this->session->userdata("user_id").'"', 'inner')
        //         ->join('user_firm as b', 'a.firm_id=b.firm_id', 'inner')
        //         ->where('b.user_id = users.id')
        //         ->where('b.user_id != "'.$this->session->userdata("user_id").'"')
        //         ->group_by('users.id');

        $this->db->select("users.id")
                ->from("users")
                ->join('groups', 'users.group_id = groups.id', 'inner')
                ->where('users.user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"')
                // ->where('users.group_id = 2')
                // ->where('users.group_id = 3')
                ->where('users.user_deleted = 0')
                ->group_by('users.id');

        $test = $this->db->get();
        $test = $test->result_array();

        $data_user_id = array();

        foreach ($test as $rr) {
            array_push($data_user_id, $rr["id"]);
        }

        $this->db->select("firm_id")
        ->from("user_firm")
        ->where('user_admin_code_id = "'.$this->session->userdata("user_admin_code_id").'"')
        ->group_by('firm_id');

        $firm_id = $this->db->get();
        $firm_id = $firm_id->result_array();

        $data_firm_id = array();

        foreach ($firm_id as $rows) {
            array_push($data_firm_id, $rows["firm_id"]);
        }

        $user["no_of_user"] = count($data_user_id);
        $user["no_of_firm"] = count($data_firm_id);

        $this->db->where('user_admin_code_id', $this->session->userdata("user_admin_code_id"));
        $this->db->where('group_id = 2');
        $this->db->update('users',$user);

        if(count($data_firm_id) != 0)
        {
            

            $this->db->select('id');
            $this->db->from('client');
            $this->db->where_in('firm_id', $data_firm_id);

            $num_client = $this->db->get();
            $num_client = $num_client->result_array();

            if(count($num_client) != 0)
            {   
                //echo json_encode($row["id"]);
                $users["no_of_client"] = count($num_client);

            }
            else
            {
                $users["no_of_client"] = 0;
            }

            $this->db->where('user_admin_code_id', $this->session->userdata("user_admin_code_id"));
            $this->db->update('users',$users);

            // $data_user_id = array();

            // foreach ($test as $r) {
            //     array_push($data_user_id, $r["id"]);
            // }

            // if(count($data_user_id) != 0)
            // {  
            //     $this->db->where_in('id', $data_user_id);
            //     $this->db->update('users',$users);
            // }
        }
    }

    public function logout()
    {
        $this->ion_auth_model->trigger_events('logout');

        if ($this->Settings->mmode) {
            if (!$this->ion_auth->in_group('owner')) {
                $this->set_message('site_is_offline_plz_try_later');
            } else {
                $this->set_message('logout_successful');
            }
        }

        $check_user_firm = $this->db->get_where("user_firm", array("user_id" => $this->session->userdata('user_id')));

        $check_user_firm = $check_user_firm->result_array();

        for($r = 0; $r < count($check_user_firm); $r++)
        {
            if($check_user_firm[$r]["default_company"] == 1)
            {
                $data["in_use"] = 1;
            }
            else
            {
                $data["in_use"] = 0;
            }
            $this->db->update("user_firm", $data, array("firm_id" => $check_user_firm[$r]['firm_id'], "user_id" => $check_user_firm[$r]["user_id"]));
        }

        $identity = $this->config->item('identity', 'ion_auth');
        $this->session->unset_userdata(array($identity => '', 'id' => '', 'user_id' => ''));

        //delete the remember me cookies if they exist
        if (get_cookie('identity')) {
            delete_cookie('identity');
        }
        if (get_cookie('remember_code')) {
            delete_cookie('remember_code');
        }

        //Destroy the session
        $this->session->sess_destroy();

        return TRUE;
    }


    public function logged_in()
    {
        $this->ion_auth_model->trigger_events('logged_in');

        return (bool)$this->session->userdata('identity');
    }

    public function get_user_id()
    {
        $user_id = $this->session->userdata('user_id');
        if (!empty($user_id)) {
            return $user_id;
        }
        return null;
    }

    public function in_group($check_group, $id = false)
    {
        $this->ion_auth_model->trigger_events('in_group');

        $id || $id = $this->session->userdata('user_id');

        $group = $this->getUserGroup($id);

        if ($group->name === $check_group) {
            return TRUE;
        }

        return FALSE;
    }

    public function getUserGroup($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $group_id = $this->getUserGroupID($user_id);
        return $this->ion_auth->group($group_id)->row();

    }

    public function getUserGroupID($user_id = false)
    {
        $user_id || $user_id = $this->session->userdata('user_id');

        $user = $this->ion_auth->user($user_id)->row();
        return $user->group_id;
    }


}

