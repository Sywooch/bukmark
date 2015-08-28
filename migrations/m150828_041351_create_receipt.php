<?php

use yii\db\Schema;
use yii\db\Migration;

class m150828_041351_create_receipt extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('receipt', [
			'id' => Schema::TYPE_PK,
			'estimate_id' => Schema::TYPE_INTEGER,
			'status' => Schema::TYPE_SMALLINT,
			'created_date' => Schema::TYPE_DATE,
			'type' => Schema::TYPE_SMALLINT,
			'iva' => Schema::TYPE_MONEY,
				], $tableOptions);
		
		$this->addForeignKey('fk_receipt_estimate', 'receipt', 'estimate_id', 'estimate', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropTable('receipt');
	}
}
