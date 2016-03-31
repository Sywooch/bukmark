<?php

use yii\db\Schema;
use yii\db\Migration;

class m160331_084338_drop_image_from_product extends Migration {

	public function up() {
		$this->dropColumn('product', 'image');
	}

	public function down() {
		$this->addColumn('product', 'image', Schema::TYPE_STRING . ' AFTER bukmark_code');
	}

}
