<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Day_time_json_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getDayTime(){
        $url         = 'application/json/day_time.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        // $months      = $data_decode[0];

        return $data_decode[0];
    }

    public function getMonth_name($month_id){
        $data = $this->getDayTime();

        foreach($data->month as $month){
            if($month->value == $month_id){
                return $month->name;
            }
        }
    }

    public function getMonth_dropdown(){
        $data = $this->getDayTime();

        $months = array();
        $months[''] = 'Month';

        foreach($data->month as $month){
            $months[$month->value] = $month->name; 
        }

        return $months;
    }

    public function getDay_dropdown(){
        $data = $this->getDayTime();

        $days = array();

        foreach($data->day as $day){
            $days[$day->value] = $day->name; 
        }

        return $days;
    }

    public function getTime_dropdown(){
        $data = $this->getDayTime();

        $times = array();

        foreach($data->time as $month){
            $times[$month->value] = $month->name; 
        }

        return $times;
    }
}
?>