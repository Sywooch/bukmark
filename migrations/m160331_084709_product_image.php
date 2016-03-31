<?php

use yii\db\Schema;
use yii\db\Migration;

class m160331_084709_product_image extends Migration {

	public function up() {
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('product_image', [
			'id' => Schema::TYPE_PK,
			'product_id' => Schema::TYPE_INTEGER,
			'filename' => Schema::TYPE_STRING,
			'deleted' => Schema::TYPE_BOOLEAN,
				], $tableOptions);

		$this->addForeignKey('fk_product_product_image', 'product_image', 'product_id', 'product', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropTable('product_image');
	}

}
