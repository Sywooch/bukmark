<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_171234_create_estimate extends Migration {

	public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('estimate', [
			'id' => Schema::TYPE_PK,
			'total' => Schema::TYPE_MONEY,
			'cost' => Schema::TYPE_MONEY,
			'us' => Schema::TYPE_MONEY,
				], $tableOptions);
	}

	public function down() {
		$this->dropTable('estimate');
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
