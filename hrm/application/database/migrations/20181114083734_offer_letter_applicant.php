<?php

class Migration_offer_letter_applicant extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'offer_letter_id' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'applicant_id' => array(
                'type' => 'INT',
                'constraint' => 11
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('offer_letter_applicant');
    }

    public function down() {
        $this->dbforge->drop_table('offer_letter_applicant');
    }

}