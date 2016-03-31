<?php

use yii\db\Schema;
use yii\db\Migration;

class m160331_090520_add_product_image_id_to_estimate_entry extends Migration {

	public function up() {
		$this->addColumn('estimate_entry', 'product_image_id', Schema::TYPE_INTEGER . ' AFTER product_id');
		$this->addForeignKey('fk_estimate_entry_product_image', 'estimate_entry', 'product_image_id', 'product_image', 'id');
	}

	public function down() {
		$this->dropForeignKey('fk_estimate_entry_product_image', 'estimate_entry');
		$this->dropColumn('estimate_entry', 'product_image_id');
	}
}
