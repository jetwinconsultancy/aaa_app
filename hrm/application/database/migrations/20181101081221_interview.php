<?php

class Migration_interview extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'interview_no' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'interview_time' => array(
                'type' => 'DATETIME'
            ),
            'firm' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'expired_at' => array(
                'type' => 'DATETIME'
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'result' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('interview');
    }

    public function down() {
        $this->dbforge->drop_table('interview');
    }

}