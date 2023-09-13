<?php

class Migration_block_holiday extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'holiday_date' => array(
                'type' => 'DATETIME'
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => 200
            ),
            'department_id' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('block_holiday');
    }

    public function down() {
        $this->dbforge->drop_table('block_holiday');
    }

}