<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Location extends MX_Controller
{
    function __construct()
    {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            redirect('auth/login');
        }

        $this->load->library('form_validation');
        $this->load->library(array('session','parser'));
        $this->load->model('location/location_model');
        $this->load->library(array('encryption'));
        
    }

    public function index()
    {   
        $this->data['User'] = $this->user_id;
        $this->meta['page_name'] = 'Location';
        $bc   = array(array('link' => '#', 'page' => 'Location'));
        $meta = array('page_title' => 'Location', 'bc' => $bc, 'page_name' => 'Location');

        $this->data['all_arrangement'] = $this->location_model->get_all_arrangement();
        $this->data['new_arrangement'] = $this->location_model->get_new_arrangement();
        $this->data['old_arrangement'] = $this->location_model->get_old_arrangement();

        foreach($this->data['all_arrangement'] as $row)
        {
            if($row->is_client_office == 1)
            {
                $row->arrangement_location = 'Client Office';
            }
            else if($row->is_client_office == 0)
            {
                if($row->arrangement_location == '15')
                {
                    $row->arrangement_location = 'SBF Office';
                }
                else if($row->arrangement_location == '18')
                {
                    $row->arrangement_location = 'Novelty Office';
                }
                else if($row->arrangement_location == '24')
                {
                    $row->arrangement_location = 'UOA Office';
                } 
            }
            else if($row->is_client_office == 2)
            {
                $row->arrangement_location = 'Work from home';
            }
            else if($row->is_client_office == 3)
            {
                $row->arrangement_location = 'Others';
            }
        }

        foreach($this->data['new_arrangement'] as $row)
        {
            if($row->is_client_office == 1)
            {
                $row->arrangement_location = $this->location_model->get_client_name($row->arrangement_location);
            }
            else if($row->is_client_office == 0)
            {
                if($row->arrangement_location == '15')
                {
                    $row->arrangement_location = 'SBF Office';
                }
                else if($row->arrangement_location == '18')
                {
                    $row->arrangement_location = 'Novelty Office';
                }
                else if($row->arrangement_location == '24')
                {
                    $row->arrangement_location = 'UOA Office';
                } 
            }
            else if($row->is_client_office == 2)
            {
                $row->arrangement_location = 'Work from home';
            }
            else if($row->is_client_office == 3)
            {
                $row->arrangement_location = 'Others';
            }
        }

        foreach($this->data['old_arrangement'] as $row)
        {
            if($row->is_client_office == 1)
            {
                $row->arrangement_location = $this->location_model->get_client_name($row->arrangement_location);
            }
            else if($row->is_client_office == 0)
            {
                if($row->arrangement_location == '15')
                {
                    $row->arrangement_location = 'SBF Office';
                }
                else if($row->arrangement_location == '18')
                {
                    $row->arrangement_location = 'Novelty Office';
                }
                else if($row->arrangement_location == '24')
                {
                    $row->arrangement_location = 'UOA Office';
                } 
            }
            else if($row->is_client_office == 2)
            {
                $row->arrangement_location = 'Work from home';
            }
            else if($row->is_client_office == 3)
            {
                $row->arrangement_location = 'Others';
            }
        }

        $this->page_construct('index.php', $meta, $this->data);
    }

    public function create($id = NULL, $status = NULL)
    {   
        $this->data['User'] = $this->user_id;
        $this->meta['page_name'] = 'Create Arrangement';
        $bc   = array(array('link' => '#', 'page' => 'Create Arrangement'));
        $meta = array('page_title' => 'Create Arrangement', 'bc' => $bc, 'page_name' => 'Create Arrangement');

        $arrangement_array = array();

        if(!$id == NULL){
            $arrangement_details = $this->location_model->get_arrangement_details($id);

            if(count($arrangement_details))
            {
                $arrangement_array = array(
                    'id'                    => $arrangement_details[0]->id,
                    'employee_id'           => $arrangement_details[0]->emp_id,
                    'form_datetime'         => date('d F Y - h:s a', strtotime($arrangement_details[0]->arrangement_start)),
                    'to_datetime'           => date('d F Y - h:s a', strtotime($arrangement_details[0]->arrangement_end)),
                    'is_client_office'      => $arrangement_details[0]->is_client_office,
                    'arrangement_location'  => $arrangement_details[0]->arrangement_location,
                    'location_address'      => $arrangement_details[0]->location_address,
                );
            }
        }

        $this->data['arrangement_details'] = $arrangement_array;
        $this->data['employee_list']       = $this->location_model->get_employee_list();
        $this->data['our_office']          = $this->location_model->get_our_office();
        $this->data['client_office']       = $this->location_model->get_client_office();
        $this->data['status']              = $status;

        $this->page_construct('create.php', $meta, $this->data);
    }

    public function submit_arrangement()
    {
        $form_data = $this->input->post();
        $result = $this->location_model->submit_arrangement($form_data);
        echo $result;
    }

    public function withdraw_arrangement()
    {
        $form_data = $this->input->post();
        $id = $form_data['id'];

        $result = $this->location_model->withdraw_arrangement($id);
        echo $result;
    }

    public function get_firm_address()
    {
        $form_data = $this->input->post();
        $id = $form_data['id'];

        $result = $this->location_model->get_firm_address($id);
        echo $result;
    }

    public function get_client_address()
    {
        $form_data = $this->input->post();
        $id = $form_data['id'];

        $result = $this->location_model->get_client_address($id);
        echo $result;
    }

    public function get_employee_address()
    {
        $form_data = $this->input->post();
        $id = $form_data['employeeId'];

        $result = $this->location_model->get_employee_address($id);
        $address = $result[0]->address;

        echo $address;
    }

    public function write_address()
    {
        $form_data = $this->input->post();
        $street_name   = $form_data['street_name'];
        $unit_no1      = $form_data['unit_no1'];
        $unit_no2      = $form_data['unit_no2'];
        $building_name = $form_data['building_name'];
        $postal_code   = $form_data['postal_code'];
        $type          = $form_data['type'];

        $unit = '';
        $unit_building_name = '';

        $comma = '';

        if($type == "normal")
        {
            $br = '';
        }
        elseif($type == "letter")
        {
            $br = ' <br/>';
        }
        elseif($type == "letter with comma")
        {
            $br = ' <br/>';
            $comma = ',';
        }
        elseif($type == "comma")
        {
            $br = ', ';
        }

        $street_name = strtoupper($street_name);
        $building_name = strtoupper($building_name);
        $unit_no1 = strtoupper($unit_no1);
        $unit_no2 = strtoupper($unit_no2);
        $postal_code = strtoupper($postal_code);

        // Add unit
        if(!empty($unit_no1) && !empty($unit_no2))
        {
            $unit = '#' . $unit_no1 . '-' . $unit_no2 . $comma;
        }

        // Add building
        if(!empty($building_name) && !empty($unit))
        {
            $unit_building_name = $unit . ' ' . $building_name . $comma;
        }
        elseif(!empty($unit))
        {
            $unit_building_name = $unit;
        }
        elseif(!empty($building_name))
        {
            $unit_building_name = $building_name . $comma;
        }
        //print_r($street_name . $br . $unit_building_name . $br . 'Singapore ' . $postal_code);
        if(!empty($unit))
        {
            $address = $street_name . $comma . $br . $unit_building_name . $br . 'SINGAPORE ' . $postal_code;
        }
        elseif(!empty($building_name))
        {
            $address = $street_name . $comma . $br . $building_name . $comma . $br . 'SINGAPORE ' . $postal_code;
        }
        else
        {
            $address = $street_name . $comma . $br . 'SINGAPORE' . $postal_code;
        }

        echo $address;
    }

}
