<?php

class Migration_employee_annual_leave extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'employee_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'annual_leave_days' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('employee_annual_leave');
    }

    public function down() {
        $this->dbforge->drop_table('employee_annual_leave');
    }

}