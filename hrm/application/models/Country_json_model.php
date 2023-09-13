<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Country_json_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getCountry_list(){
        $url         = 'application/json/country.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        // $workpass   = $data_decode[0]->WorkPass;

        return $data_decode[0];
    }

    public function getCountry_dropdown(){
        // $url         = 'application/json/country.json'; // path to your JSON file
        // $data        = file_get_contents($url); // put the contents of the file into a variable
        // $data_decode = json_decode($data); // decode the JSON feed
        $data = $this->getCountry_list();

        $countries          = $data->country;

        $country_list       = array();
        $country_list['']   = "Select country";

        foreach($countries as $country){
            // echo $country->value;

            // echo $country->name;

            $country_list[$country->value] = $country->name; 
        }

        return $country_list;
    }

    public function getCurrency_dropdown(){
        // $url         = 'application/json/country.json'; // path to your JSON file
        // $data        = file_get_contents($url); // put the contents of the file into a variable
        // $data_decode = json_decode($data); // decode the JSON feed
        $data = $this->getCountry_list();
        
        $currencies         = $data->currency;

        $currency_list      = array();
        $currency_list['']  = "Currency";

        foreach($currencies as $currency){
            $currency_list[$currency->value] = $currency->name; 
        }

        return $currency_list;
    }

    public function getNationality(){
        $query = $this->db->query("SELECT * FROM nationality");

        $nationality_list      = array();

        $nationality_list['']  = "Select a nationality";

        foreach($query->result() as $item){
            $nationality_list[$item->id] = $item->nationality; 
        }

        return $nationality_list;
    }

    public function getFamilyRelationship(){
        $query = $this->db->query("SELECT * FROM payroll_family_relationship");

        $nationality_list      = array();

        foreach($query->result() as $item){
            $relationship_list[$item->id] = $item->relationship_name; 
        }

        return $relationship_list;
    }

    public function get_country_name($country_id){
        $data = $this->getCountry_list();

        foreach($data->country as $item){
            if($item->value == $country_id){
                return $item->name;
            }
        }
    }
}
?>