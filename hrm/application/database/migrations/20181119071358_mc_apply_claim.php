<?php

class Migration_mc_apply_claim extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'mc_apply_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'claim_id' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('mc_apply_claim');
    }

    public function down() {
        $this->dbforge->drop_table('mc_apply_claim');
    }

}