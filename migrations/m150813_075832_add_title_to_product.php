<?php

use yii\db\Schema;
use yii\db\Migration;

class m150813_075832_add_title_to_product extends Migration
{
    public function up() {
		$this->addColumn('product', 'title', Schema::TYPE_STRING . ' AFTER supplier_id');
	}

	public function down() {
		$this->dropColumn('product', 'title');
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
