<?php

class Migration_leave extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'leave_no' => array(
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
            'start_time' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'end_date' => array(
                'type' => 'DATETIME'
            ),
            'end_time' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'total_days' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'status_updated_by' => array(
                'type' => 'TIMESTAMP'
            ),
            'al_left_before' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'al_left_after' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('leave');
    }

    public function down() {
        $this->dbforge->drop_table('leave');
    }

}