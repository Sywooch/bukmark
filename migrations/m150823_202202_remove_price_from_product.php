<?php

use yii\db\Schema;
use yii\db\Migration;

class m150823_202202_remove_price_from_product extends Migration {

	public function up() {
		$this->dropColumn('product', 'price');
		$this->dropColumn('product', 'currency');
		$this->dropColumn('product', 'utility');
	}

	public function down() {
		$this->addColumn('product', 'price', Schema::TYPE_MONEY);
		$this->addColumn('product', 'currency', Schema::TYPE_SMALLINT);
		$this->addColumn('product', 'utility', Schema::TYPE_MONEY);
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
