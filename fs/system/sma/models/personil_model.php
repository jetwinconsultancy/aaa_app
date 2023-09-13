<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personil_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBillerCompanies()
    {
        $q = $this->db->get_where('companies', array('group_name' => 'biller'));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getPersonilById($id)
    {
        $q = $this->db->get_where('personil', array('id' => $id));
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }
	
    public function getPersonil()
    {
        $q = $this->db->get_where('personil');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function addPersonil($data = array())
    {
        if ($this->db->insert('personil', $data)) {
            $cid = $this->db->insert_id();
            return $cid;
        }
        return false;
    }
	public function updatePersonil($id, $data = array())
    {
        $this->db->where('id', $id);
        if ($this->db->update('personil', $data)) {
            return true;
        }
        return false;
    }

}