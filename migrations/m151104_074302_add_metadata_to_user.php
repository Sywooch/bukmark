<?php

use yii\db\Schema;
use yii\db\Migration;

class m151104_074302_add_metadata_to_user extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'admin', Schema::TYPE_BOOLEAN);
		$this->addColumn('user', 'first_name', Schema::TYPE_STRING);
		$this->addColumn('user', 'last_name', Schema::TYPE_STRING);
    }

    public function down()
    {
        $this->dropColumn('user', 'admin');
		$this->dropColumn('user', 'first_name');
		$this->dropColumn('user', 'last_name');
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
