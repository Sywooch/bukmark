<?php

use yii\db\Migration;
use yii\db\Schema;

class m160422_180655_add_number_to_receipt extends Migration
{
    public function up()
    {
		$this->addColumn('receipt', 'number', Schema::TYPE_STRING . ' AFTER estimate_id');
    }

    public function down()
    {
		$this->dropColumn('receipt', 'number');
    }
}
