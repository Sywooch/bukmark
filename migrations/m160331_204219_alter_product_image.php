<?php

use yii\db\Schema;
use yii\db\Migration;

class m160331_204219_alter_product_image extends Migration {

	public function up() {
		$this->dropForeignKey('fk_product_product_image', 'product_image');
		$this->dropColumn('product_image', 'product_id');
		$this->dropColumn('product_image', 'filename');
		$this->dropColumn('product_image', 'deleted');

		$this->addColumn('product_image', 'type', Schema::TYPE_STRING);
		$this->addColumn('product_image', 'ownerId', Schema::TYPE_STRING . ' NOT NULL');
		$this->addColumn('product_image', 'rank', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0');
		$this->addColumn('product_image', 'name', Schema::TYPE_STRING);
		$this->addColumn('product_image', 'description', Schema::TYPE_TEXT);
	}

	public function down() {
		$this->dropColumn('product_image', 'description');
		$this->dropColumn('product_image', 'name');
		$this->dropColumn('product_image', 'rank');
		$this->dropColumn('product_image', 'ownerId');
		$this->dropColumn('product_image', 'type');
		
		$this->addColumn('product_image', 'product_id', Schema::TYPE_INTEGER);
		$this->addColumn('product_image', 'filename', Schema::TYPE_STRING);
		$this->addColumn('product_image', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addForeignKey('fk_product_product_image', 'product_image', 'product_id', 'product', 'id', 'CASCADE');
	}

}
