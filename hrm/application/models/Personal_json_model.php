<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_json_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('session'));
    }

    public function getPerson_list(){
        $url         = 'application/json/personal.json'; // path to your JSON file
        $data        = file_get_contents($url); // put the contents of the file into a variable
        $data_decode = json_decode($data); // decode the JSON feed

        // $workpass   = $data_decode[0]->WorkPass;

        return $data_decode[0];
    }

    public function getGender_dropdown(){
        $data = $this->getPerson_list();
        // $url         = 'application/json/personal.json'; // path to your JSON file
        // $data        = file_get_contents($url); // put the contents of the file into a variable
        // $data_decode = json_decode($data); // decode the JSON feed

        $genders     = $data->Gender;

        $gender_list = array();
        $gender_list[''] = "-- Select gender --";

        foreach($genders as $gender){
            $gender_list[$gender->value] = $gender->name; 
        }

        return $gender_list;
    }

    /* Education */
    public function getQualification_dropdown(){
        // $url                = 'application\json\personal.json'; // path to your JSON file
        // $data               = file_get_contents($url); // put the contents of the file into a variable
        // $data_decode        = json_decode($data); // decode the JSON feed

        $data = $this->getPerson_list();

        $qualifications     = $data->Qualification;

        $qualification_list = array();
        $qualification_list[''] = "-- Select qualification --";

        foreach($qualifications as $qualification){
            $qualification_list[$qualification->value] = $qualification->name; 
        }

        return $qualification_list;
    }

    public function getGrade_dropdown(){
        // $url         = 'application\json\personal.json'; // path to your JSON file
        // $data        = file_get_contents($url); // put the contents of the file into a variable
        // $data_decode = json_decode($data); // decode the JSON feed

        $data = $this->getPerson_list();

        $grades      = $data->Grade;

        $grade_list  = array();
        $grade_list[''] = "-- Select grade --";

        foreach($grades as $grade){
            $grade_list[$grade->value] = $grade->name; 
        }

        return $grade_list;
    }

    public function getFieldOfStudy_dropdown(){
        // $url         = 'application\json\personal.json'; // path to your JSON file
        // $data        = file_get_contents($url);     // put the contents of the file into a variable
        // $data_decode = json_decode($data);          // decode the JSON feed

        $data = $this->getPerson_list();

        $FieldOfStudies         = $data->FieldOfStudy;

        $FieldOfStudy_list      = array();
        $FieldOfStudy_list['']  = "-- Select field of study --";

        foreach($FieldOfStudies as $FieldOfStudy){
            $FieldOfStudy_list[$FieldOfStudy->value] = $FieldOfStudy->name; 
        }

        return $FieldOfStudy_list;
    }

    public function getFieldOfStudy_name($fieldOfStudy_id){
        $data = $this->getPerson_list();

        foreach($data->FieldOfStudy as $item){
            if($item->value == $fieldOfStudy_id){
                return $item->name;
            }
        }
    }

    /* Experience */
    public function getPosition_level_dropdown(){
        // $url         = 'application\json\personal.json'; // path to your JSON file
        // $data        = file_get_contents($url);     // put the contents of the file into a variable
        // $data_decode = json_decode($data);          // decode the JSON feed

        $data = $this->getPerson_list();

        $position_levels            = $data->Position_level;

        $position_level_list        = array();
        $position_level_list['']    = "-- Select position level --";

        foreach($position_levels as $position_level){
            $position_level_list[$position_level->value] = $position_level->name; 
        }

        return $position_level_list;
    }

    public function getPosition_level_name($position_level_id){
        $data = $this->getPerson_list();

        foreach($data->Position_level as $item){
            if($item->value == $position_level_id){
                return $item->name;
            }
        }
    }
}
?>