<?php

use yii\db\Migration;
use yii\db\Schema;

class m160826_075414_add_supplier_discount_to_estimate_entry extends Migration
{
    public function up()
    {
		$this->addColumn('estimate_entry', 'supplier_discount', Schema::TYPE_MONEY . ' AFTER price');
    }

    public function down()
    {
		$this->dropColumn('estimate_entry', 'supplier_discount');
    }
}
