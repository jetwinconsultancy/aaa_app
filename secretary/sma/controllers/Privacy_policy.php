<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Privacy_policy extends MY_Controller
{

    function __construct()
    {

    }

    public function index()
    {
        $bc = array(array('link' => '#', 'page' => "Privacy policy"));
        $meta = array('page_title' => "Privacy policy", 'bc' => $bc);

        $this->page_construct('privacy_policy', $meta);
    }
}