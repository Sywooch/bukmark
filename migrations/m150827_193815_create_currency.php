<?php

use yii\db\Schema;
use yii\db\Migration;

class m150827_193815_create_currency extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('currency', [
			'id' => Schema::TYPE_PK,
			'date' => Schema::TYPE_DATE,
			'us' => Schema::TYPE_MONEY,
				], $tableOptions);
	}

	public function down() {
		$this->dropTable('currency');
	}
}
