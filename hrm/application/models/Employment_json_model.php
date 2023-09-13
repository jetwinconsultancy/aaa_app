<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employment_json_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getEmployment_list(){
        $url         = 'application/json/employment.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        // $workpass   = $data_decode[0]->WorkPass;

        return $data_decode[0];
    }

    public function get_workpass(){
        $list = $this->getEmployment_list();

        $workpass_list = array();

        foreach($list->WorkPass as $item){
            $workpass_list[$item->value] = $item->name; 
        }

        return $workpass_list;
    }

    public function get_workpass_details(){
        $list = $this->getEmployment_list();

        $workpass_list = array();
        $workpass_list[''] = 'Select a Work Pass';

        foreach($list->WorkPass_details as $item){
            $workpass_list[$item->value] = $item->name; 
        }

        return $workpass_list;
    }
    
    public function get_action_result(){
        $list = $this->getEmployment_list();

        $action_list = array();

        foreach($list->Action_status as $item){
            $action_list[$item->value] = $item->name; 
        }

        return $action_list;
    }

    public function get_action_name($status_id){
        $list = $this->getEmployment_list();

        // $action_list = array();

        foreach($list->Action_status as $item){
            // return $item->value;
            if($item->value == $status_id){
                return $item->name;
            }
            // $action_list[$item->value] = $item->name; 
        }
        // return $action_list;
    }

    public function get_timesheet_action_name($status_id){
        $list = $this->getEmployment_list();

        // $action_list = array();

        foreach($list->Timesheet_action_status as $item){
            // return $item->value;
            if($item->value == $status_id){
                return $item->name;
            }
            // $action_list[$item->value] = $item->name; 
        }
        // return $action_list;
    }
}
?>