<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_081909_add_utilty_to_product extends Migration
{
    public function up()
    {
		$this->addColumn('product', 'utility', Schema::TYPE_MONEY);
    }

    public function down()
    {
        $this->dropColumn('product', 'utility');
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
