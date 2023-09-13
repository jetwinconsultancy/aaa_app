<?php

class Migration_payslip_setting extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'cdac' => array(
                'type' => 'decimal',
                'constraint' => '20,2'
            ),
            'sdl' => array(
                'type' => 'decimal',
                'constraint' => '20,2'
            ),
            'health_incentive' => array(
                'type' => 'decimal',
                'constraint' => '20,2'
            ),
            'last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('payslip_setting');
    }

    public function down() {
        $this->dbforge->drop_table('payslip_setting');
    }

}