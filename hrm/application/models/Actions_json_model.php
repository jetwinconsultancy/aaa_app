<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Actions_json_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getActions_list(){
        $url         = 'application/json/actions.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        // $workpass   = $data_decode[0]->WorkPass;

        return $data_decode[0];
    }

    public function get_interview_status(){
        $list = $this->getActions_list();

        $interview_status_list = array();

        foreach($list->interview_status as $item){
            $interview_status_list[$item->value] = $item->name; 
        }

        return $interview_status_list;
    }

     public function get_interview_result(){
        $list = $this->getActions_list();

        $interview_result_list = array();

        foreach($list->interview_result as $item){
            $interview_result_list[$item->value] = $item->name; 
        }

        return $interview_result_list;
    }

    public function get_interview_status_name($status_id){
        $list = $this->getActions_list();

        foreach($list->interview_status as $item){
            if($item->value == $status_id){
                return $item->name;
            }
        }
    }
}
?>