<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_205232_add_checked_to_estimate_entry extends Migration
{
    public function up() {
		$this->addColumn('estimate_entry', 'checked', Schema::TYPE_BOOLEAN);
	}

	public function down() {
		$this->dropColumn('estimate_entry', 'checked');
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
