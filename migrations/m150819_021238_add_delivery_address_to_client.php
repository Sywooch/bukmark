<?php

use yii\db\Schema;
use yii\db\Migration;

class m150819_021238_add_delivery_address_to_client extends Migration
{
    public function up() {
		$this->addColumn('client', 'delivery_address', Schema::TYPE_STRING . ' AFTER address');
	}

	public function down() {
		$this->dropColumn('client', 'delivery_address');
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
