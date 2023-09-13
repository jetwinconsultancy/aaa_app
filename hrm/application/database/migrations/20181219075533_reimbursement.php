<?php

class Migration_reimbursement extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'reimbursement_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'employee_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'client_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'date' => array(
                'type' => 'DATETIME'
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'firm_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            ),
            'receipt_img_filename' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'invoice_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'status_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'status_updated_by' => array(
                'type' => 'DATETIME'
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('reimbursement');
    }

    public function down() {
        $this->dbforge->drop_table('reimbursement');
    }

}