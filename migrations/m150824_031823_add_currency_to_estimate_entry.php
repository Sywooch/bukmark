<?php

use yii\db\Schema;
use yii\db\Migration;

class m150824_031823_add_currency_to_estimate_entry extends Migration
{
    public function up() {
		$this->addColumn('estimate_entry', 'currency', Schema::TYPE_SMALLINT . ' AFTER price');
		$this->addColumn('estimate_entry', 'variant_currency', Schema::TYPE_SMALLINT . ' AFTER variant_price');
		$this->addColumn('estimate_entry', 'description', Schema::TYPE_TEXT . ' AFTER variant_currency');
	}

	public function down() {
		$this->dropColumn('estimate_entry', 'description');
		$this->dropColumn('estimate_entry', 'currency');
		$this->dropColumn('estimate_entry', 'variant_currency');
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
