<?php

use yii\db\Schema;
use yii\db\Migration;

class m150704_004115_create_contact extends Migration {

	public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('contact', [
			'id' => Schema::TYPE_PK,
			'supplier_id' => Schema::TYPE_INTEGER,
			'first_name' => Schema::TYPE_STRING,
			'last_name' => Schema::TYPE_STRING,
			'email' => Schema::TYPE_STRING,
			'phone' => Schema::TYPE_STRING,
				], $tableOptions);
		
		$this->addForeignKey('fk_contact_supplier', 'contact', 'supplier_id', 'supplier', 'id');
	}

	public function down() {
		$this->dropTable('contact');
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
