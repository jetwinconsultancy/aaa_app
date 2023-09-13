<?php

class Migration_mc_apply extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'mc_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'employee_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'start_date' => array(
                'type' => 'DATETIME'
            ),
            'end_date' => array(
                'type' => 'DATETIME'
            ),
            'reason' => array(
                'type' => 'VARCHAR',
                'constraint' => 500
            ),
            'date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'mc_status' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'status_updated_by' => array(
                'type' => 'TIMESTAMP'
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('mc_apply');
    }

    public function down() {
        $this->dbforge->drop_table('mc_apply');
    }

}