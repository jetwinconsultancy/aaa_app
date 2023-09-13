<?php

class Migration_timesheet extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'timesheet_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 30
            ),
            'month' => array(
                'type' => 'DATE'
            ),
            'employee_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'status_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'content' => array(
                'type' => 'LONGTEXT'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('timesheet');
    }

    public function down() {
        $this->dbforge->drop_table('timesheet');
    }

}