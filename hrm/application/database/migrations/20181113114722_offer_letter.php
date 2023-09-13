<?php

class Migration_offer_letter extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'effective_from' => array(
                'type' => 'DATE'
            ),
            'probationary_period' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'working_hour_time_start' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'working_hour_time_end' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'working_hour_day_start' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'working_hour_day_end' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'termination_notice' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'employer' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'given_salary' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('offer_letter');
    }

    public function down() {
        $this->dbforge->drop_table('offer_letter');
    }

}