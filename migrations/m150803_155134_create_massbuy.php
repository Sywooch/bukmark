<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_155134_create_massbuy extends Migration {

	public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('massbuy', [
			'id' => Schema::TYPE_PK,
			'product_id' => Schema::TYPE_INTEGER,
			'quantity' => Schema::TYPE_INTEGER,
			'utility_drop' => Schema::TYPE_MONEY,
				], $tableOptions);

		$this->addForeignKey('fk_massbuy_product', 'massbuy', 'product_id', 'product', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropTable('massbuy');
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
