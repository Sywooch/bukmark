<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_185633_add_data_to_estimate extends Migration
{
    public function up() {
		$this->addColumn('estimate', 'client_id', Schema::TYPE_INTEGER . ' AFTER id');
		$this->addColumn('estimate', 'user_id', Schema::TYPE_INTEGER . ' AFTER client_id');
		$this->addColumn('estimate', 'status', Schema::TYPE_SMALLINT . ' AFTER user_id');
		$this->addColumn('estimate', 'request_date', Schema::TYPE_DATE . ' AFTER status');
		$this->addColumn('estimate', 'sent_date', Schema::TYPE_DATE . ' AFTER request_date');
		
		$this->addForeignKey('fk_estimate_client', 'estimate', 'client_id', 'client', 'id', 'CASCADE');
		$this->addForeignKey('fk_estimate_user', 'estimate', 'user_id', 'user', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropForeignKey('fk_estimate_client', 'estimate');
		$this->dropForeignKey('fk_estimate_user', 'estimate');
		
		$this->dropColumn('estimate', 'client_id');
		$this->dropColumn('estimate', 'user_id');
		$this->dropColumn('estimate', 'status');
		$this->dropColumn('estimate', 'request_date');
		$this->dropColumn('estimate', 'sent_date');
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
