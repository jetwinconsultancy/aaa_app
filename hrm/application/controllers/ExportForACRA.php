<?php define( 'APPLICATION_LOADED', true );

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportForACRA extends MX_Controller {

    public function message() {
        echo "halo ExportForACRA!" ;
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('encryption', 'session'));
    }

    public function generate_Service_Listing() {
        $result = $this->get_all_transaction(2);

        $spreadsheet = new Spreadsheet();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/service_listing.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;

        foreach($result as $data){
                foreach( range('A', 'E') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = $data->client_name?$data->client_name:$data->company_name;
                            break;
                        }
                        case 'B': {
                            $value = $data->registration_no;
                            break;
                        }
                        case 'C': {
                            $value = $data->created_at;
                            break;
                        }
                        case 'D': {
                            $value = $data->lodgement_date;
                            break;
                        }
                        case 'E': {
                            $value = $data->transaction_task;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/ACRA/Service_Listing.xlsx';
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/excel/Service_Listing.xlsx',0644);
        $response = $filename;

        $writer->save($filename);
        echo "localhost/hrm/".$response;
    }

    public function get_all_transaction($group_id){
        if($group_id != 4)
        {
            $this->db->select('transaction_tasks.transaction_task, transaction_master.id, transaction_master.registration_no, transaction_master.client_name, transaction_client.company_name,transaction_master.created_at,transaction_master.lodgement_date');
            $this->db->from('transaction_master');
            $this->db->join('transaction_client', 'transaction_client.transaction_id = transaction_master.id', 'left');
            $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
            $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
            //$this->db->where('transaction_master.registration_no = ""');
            $this->db->where('transaction_master.transaction_task_id = 1');
            $this->db->where('transaction_master.firm_id IN (18,26)');
            $this->db->where('Date(transaction_master.created_at) >= Date("2021-01-01")');
            $this->db->where('Date(transaction_master.created_at) <= Date("2021-12-31")');
            $this->db->order_by("id", "asc");
            $q = $this->db->get();

            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    if($row->client_name != null)
                    {
                        $row->client_name = $this->encryption->decrypt($row->client_name);
                    }
                    if($row->company_name != null)
                    {
                        $row->company_name = $this->encryption->decrypt($row->company_name);
                    }
                    $data[] = $row;
                }
            }

            $this->db->select('transaction_tasks.transaction_task, transaction_master.id, transaction_master.registration_no, transaction_master.client_name, transaction_client.company_name,transaction_master.created_at,transaction_master.lodgement_date');
            $this->db->from('transaction_master');
            $this->db->join('transaction_client', 'transaction_client.transaction_id = transaction_master.id', 'left');
            $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
            $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
            //$this->db->where('transaction_master.registration_no = ""');
            $this->db->where('transaction_master.transaction_task_id = 28');
            $this->db->where('transaction_master.firm_id IN (18,26)');
            $this->db->where('Date(transaction_master.created_at) >= Date("2021-01-01")');
            $this->db->where('Date(transaction_master.created_at) <= Date("2021-12-31")');
            $this->db->order_by("id", "asc");
            $q = $this->db->get();
            //echo json_encode($q->result_array());
            if ($q->num_rows() > 0) {
                foreach (($q->result()) as $row) {
                    $row->registration_no = $this->encryption->decrypt($row->registration_no);
                    if($row->client_name != null)
                    {
                        $row->client_name = $this->encryption->decrypt($row->client_name);
                    }
                    if($row->company_name != null)
                    {
                        $row->company_name = $this->encryption->decrypt($row->company_name);
                    }
                    $data[] = $row;
                }
            }

            //echo json_encode($data);
        }

        $this->db->select('transaction_tasks.transaction_task, transaction_master.id, transaction_master.registration_no, transaction_master.client_name, client.company_name,transaction_master.created_at,transaction_master.lodgement_date');
        $this->db->from('transaction_master');
        $this->db->join('client', 'client.company_code = transaction_master.company_code', 'left');// and client.firm_id = "'.$this->session->userdata('firm_id').'" // AND client.deleted = 0
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND transaction_master.transaction_task_id != 29 AND transaction_master.transaction_task_id != 30 AND transaction_master.transaction_task_id != 35');
        $this->db->where('transaction_master.firm_id IN (18,26)');
        $this->db->where('Date(transaction_master.created_at) >= Date("2021-01-01")');
        $this->db->where('Date(transaction_master.created_at) <= Date("2021-12-31")');
        $this->db->order_by("id", "asc");

        $p = $this->db->get(); 

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                if($row->company_name != null)
                {
                    $row->company_name = $this->encryption->decrypt($row->company_name);
                }
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                if($row->client_name != null)
                {
                    $row->client_name = $this->encryption->decrypt($row->client_name);
                }
                $data[] = $row;
            }
            
        }

        $this->db->select('transaction_tasks.transaction_task, transaction_master.id, transaction_master.registration_no, transaction_master.client_name, transaction_master.created_at,transaction_master.lodgement_date');
        $this->db->from('transaction_master');
        $this->db->join('transaction_status', 'transaction_status.id = transaction_master.status', 'left');
        $this->db->join('transaction_tasks', 'transaction_tasks.id = transaction_master.transaction_task_id', 'left');
        //$this->db->where('transaction_master.registration_no != ""');
        $this->db->where('transaction_master.transaction_task_id != 1 AND transaction_master.transaction_task_id != 28 AND (transaction_master.transaction_task_id = 29 OR transaction_master.transaction_task_id = 30 OR transaction_master.transaction_task_id = 35)');
        $this->db->where('transaction_master.firm_id IN (18,26)');
        $this->db->where('Date(transaction_master.created_at) >= Date("2021-01-01")');
        $this->db->where('Date(transaction_master.created_at) <= Date("2021-12-31")');
        $this->db->order_by("id", "asc");

        $p = $this->db->get();

        if ($p->num_rows() > 0) {
            foreach (($p->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                if($row->client_name != null)
                {
                    $row->client_name = $this->encryption->decrypt($row->client_name);
                }
                if($group_id == 4)
                {
                    foreach ($access_client_list_data as $client_list_row) 
                    {
                        if(trim($row->client_name) == trim($client_list_row->company_name))
                        {
                            $data[] = $row;
                        }
                    }
                }
                else
                {
                    $data[] = $row;
                }
            }
        }

        //echo json_encode($data);
        if(isset($data))
        {
            if(count($data) > 0)
            {
                return $data;
            }
            else
                return FALSE;
        }
        else
            return FALSE;
    }

    public function generate_Company_Listing() {
        $result = $this->get_company_listing();

        $spreadsheet = new Spreadsheet();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/company_listing_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;

        foreach($result as $data){
                foreach( range('A', 'J') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = $data->company_name;
                            break;
                        }
                        case 'B': {
                            $value = $data->registration_no;
                            break;
                        }
                        case 'C': {
                            $value = $data->postal_code;
                            break;
                        }
                        case 'D': {
                            $value = $data->street_name;
                            break;
                        }
                        case 'E': {
                            $value = $data->building_name;
                            break;
                        }
                        case 'F': {
                            $value = $data->unit_no1;
                            break;
                        }
                        case 'G': {
                            $value = $data->unit_no2;
                            break;
                        }
                        case 'H': {
                            $value = $data->foreign_add_1;
                            break;
                        }
                        case 'I': {
                            $value = $data->foreign_add_2;
                            break;
                        }
                        case 'J': {
                            $value = $data->foreign_add_3;
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/ACRA/Company_Listing.xlsx';
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/excel/Company_Listing.xlsx',0644);
        $response = $filename;

        $writer->save($filename);
        echo "localhost/hrm/".$response;
    }

    public function get_company_listing(){
        $list = $this->db->query("SELECT client.company_name,client.registration_no,client.postal_code,client.street_name,client.building_name,client.unit_no1,client.unit_no2,client.foreign_add_1,client.foreign_add_2,client.foreign_add_3 FROM client WHERE client.status = 1 AND client.acquried_by = 1 AND client.firm_id IN (18,26)");
        
        if ($list->num_rows() > 0) {
            foreach (($list->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);
            }
        }

        return $list->result();
    }

    public function generate_Compang_Member_Listing() {
        $result = $this->search_register_member();

        $spreadsheet = new Spreadsheet();

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load("./excel/company_member_listing_format.xlsx");
        $sheet = $spreadsheet->getActiveSheet();

        $i = 2;

        foreach($result as $data){
                foreach( range('A', 'G') as $v ) {
                    switch( $v ) {
                        case 'A': {
                            $value = $data['company_name'];
                            break;
                        }
                        case 'B': {
                            $value = $data['registration_no'];
                            break;
                        }
                        case 'C': {
                            $value = $data['member_share'];
                            break;
                        }
                        case 'D': {
                            $value = $data['identification_no'];
                            break;
                        }
                        case 'E': {
                            $value = $data['type'];
                            break;
                        }
                        case 'F': {
                            $value = $data['appoint'];
                            break;
                        }
                        case 'G': {
                            $value = $data['resign'];
                            break;
                        }
                    }
                    $spreadsheet->getActiveSheet()->setCellValue($v.$i, $value);
                }
                $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'excel/ACRA/Company_Member_Listing.xlsx';
        chmod($_SERVER['DOCUMENT_ROOT'].'hrm/excel/excel/Company_Member_Listing.xlsx',0644);
        $response = $filename;

        $writer->save($filename);
        echo "localhost/hrm/".$response;
    }

    public function search_register_member($from = null, $to = null)
	{
        $clients = $this->db->query('select client.company_code,client.registration_no,client.company_name from client WHERE client.status = 1 AND client.acquried_by = 1 AND client.firm_id IN (18,26)');

        $result = array();

        if ($clients->num_rows() > 0) {
            foreach (($clients->result()) as $row) {
                $row->registration_no = $this->encryption->decrypt($row->registration_no);
                $row->company_name = $this->encryption->decrypt($row->company_name);

                $query = $this->db->query('select officer.name, officer.identification_no from member_shares LEFT JOIN officer ON officer.id = member_shares.officer_id
                WHERE member_shares.company_code = "'.$row->company_code.'"');

                foreach (($query->result()) as $row2) {
                    $row2->name = $this->encryption->decrypt($row2->name);
                    $row2->identification_no = $this->encryption->decrypt($row2->identification_no);
                    array_push($result,array(
                        "registration_no"   => $row->registration_no,
                        "company_name"      => $row->company_name,
                        "member_share"      => $row2->name,
                        "identification_no" => $row2->identification_no,
                        "type"              => "shareholder",
                        "appoint"           => "",
                        "resign"            => ""
                    ));
                }

                $query = $this->db->query('select officer.name, officer.identification_no, client_nominee_director.date_become_nominator ,client_nominee_director.date_of_cessation from client_nominee_director LEFT JOIN officer ON officer.id = client_nominee_director.nd_officer_id
                WHERE client_nominee_director.company_code = "'.$row->company_code.'"');

                foreach (($query->result()) as $row2) {
                    $row2->name = $this->encryption->decrypt($row2->name);
                    $row2->identification_no = $this->encryption->decrypt($row2->identification_no);
                    array_push($result,array(
                        "registration_no"   => $row->registration_no,
                        "company_name"      => $row->company_name,
                        "member_share"      => $row2->name,
                        "identification_no" => $row2->identification_no,
                        "type"              => "nominee director",
                        "appoint"           => $row2->date_become_nominator,
                        "resign"            => $row2->date_of_cessation
                    ));
                }

                $query = $this->db->query('select officer.name, officer.identification_no, client_officers.date_of_appointment ,client_officers.date_of_cessation from client_officers LEFT JOIN officer ON officer.id = client_officers.officer_id
                WHERE client_officers.company_code = "'.$row->company_code.'"');

                foreach (($query->result()) as $row2) {
                    $row2->name = $this->encryption->decrypt($row2->name);
                    $row2->identification_no = $this->encryption->decrypt($row2->identification_no);
                    array_push($result,array(
                        "registration_no"   => $row->registration_no,
                        "company_name"      => $row->company_name,
                        "member_share"      => $row2->name,
                        "identification_no" => $row2->identification_no,
                        "type"              => "director",
                        "appoint"           => $row2->date_of_appointment,
                        "resign"            => $row2->date_of_cessation
                    ));
                }
                
            }
        }

        return $result;
	}
}