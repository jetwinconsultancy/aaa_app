<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Term_and_condition extends MY_Controller
{

    function __construct()
    {

    }

    public function index()
    {
        $bc = array(array('link' => '#', 'page' => "Term and condition"));
        $meta = array('page_title' => "Term and condition", 'bc' => $bc);

        $this->page_construct('term_and_condition', $meta);
    }
}