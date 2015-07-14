<?php

use yii\db\Schema;
use yii\db\Migration;

class m150714_080447_create_client extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('client', [
			'id' => Schema::TYPE_PK,
			'name' => Schema::TYPE_STRING,
			'cuit' => Schema::TYPE_STRING,
			'address' => Schema::TYPE_STRING,
			'payment_conditions' => Schema::TYPE_STRING,
			'notes' => Schema::TYPE_TEXT,
				], $tableOptions);
	}

	public function down() {
		$this->dropTable('client');
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
