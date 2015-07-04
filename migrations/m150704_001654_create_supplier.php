<?php

use yii\db\Schema;
use yii\db\Migration;

class m150704_001654_create_supplier extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('supplier', [
			'id' => Schema::TYPE_PK,
			'code' => Schema::TYPE_STRING,
			'name' => Schema::TYPE_STRING,
			'website' => Schema::TYPE_STRING,
			'address' => Schema::TYPE_STRING,
			'notes' => Schema::TYPE_TEXT,
				], $tableOptions);
	}

	public function down() {
		$this->dropTable('supplier');
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
