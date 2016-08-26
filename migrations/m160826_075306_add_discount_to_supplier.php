<?php

use yii\db\Migration;
use yii\db\Schema;

class m160826_075306_add_discount_to_supplier extends Migration
{
    public function up()
    {
		$this->addColumn('supplier', 'discount', Schema::TYPE_MONEY . ' AFTER name');
    }

    public function down()
    {
		$this->dropColumn('supplier', 'discount');
    }
}
