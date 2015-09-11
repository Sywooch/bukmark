<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_160132_remove_code_from_supplier extends Migration
{
    public function up()
    {
		$this->dropColumn('supplier', 'code');
    }

    public function down()
    {
        $this->addColumn('supplier', 'code', Schema::TYPE_STRING . ' AFTER id');
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
