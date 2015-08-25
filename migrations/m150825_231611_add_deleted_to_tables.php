<?php

use yii\db\Schema;
use yii\db\Migration;

class m150825_231611_add_deleted_to_tables extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addColumn('client', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addColumn('supplier', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addColumn('category', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addColumn('product', 'deleted', Schema::TYPE_BOOLEAN);
		$this->addColumn('estimate', 'deleted', Schema::TYPE_BOOLEAN);
    }

    public function down()
    {
        $this->dropColumn('user', 'deleted');
		$this->dropColumn('client', 'deleted');
		$this->dropColumn('supplier', 'deleted');
		$this->dropColumn('category', 'deleted');
		$this->dropColumn('product', 'deleted');
		$this->dropColumn('estimate', 'deleted');
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
