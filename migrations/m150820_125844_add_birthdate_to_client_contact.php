<?php

use yii\db\Schema;
use yii\db\Migration;

class m150820_125844_add_birthdate_to_client_contact extends Migration
{
    public function up() {
		$this->addColumn('client_contact', 'birthdate', Schema::TYPE_DATE);
	}

	public function down() {
		$this->dropColumn('client_contact', 'birthdate');
	}
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
