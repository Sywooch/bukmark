<?php

use yii\db\Schema;
use yii\db\Migration;

class m150817_232821_add_checked_to_estimate extends Migration
{
    public function up() {
		$this->addColumn('estimate', 'total_checked', Schema::TYPE_MONEY . ' AFTER cost');
		$this->addColumn('estimate', 'cost_checked', Schema::TYPE_MONEY . ' AFTER total_checked');
	}

	public function down() {
		$this->dropColumn('estimate', 'total_checked');
		$this->dropColumn('estimate', 'cost_checked');
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
