<?php

use yii\db\Schema;
use yii\db\Migration;

class m150821_223051_delete_variant_and_massbuy extends Migration
{
    public function up()
    {
		require_once 'm150803_081915_create_variant.php';
		require_once 'm150803_155134_create_massbuy.php';
		$this->dropForeignKey('fk_estimate_entry_variant', 'estimate_entry');
		$this->dropColumn('estimate_entry', 'variant_id');
		$migration = new m150803_081915_create_variant();
		$migration->down();
		$migration = new m150803_155134_create_massbuy();
		$migration->down();
    }

    public function down()
    {
		require_once 'm150803_081915_create_variant.php';
		require_once 'm150803_155134_create_massbuy.php';
        $migration = new m150803_081915_create_variant();
		$migration->up();
		$migration = new m150803_155134_create_massbuy();
		$migration->up();
		$this->addColumn('estimate_entry', 'variant_id', Schema::TYPE_INTEGER . ' AFTER product_id');
		$this->addForeignKey('fk_estimate_entry_variant', 'estimate_entry', 'variant_id', 'variant', 'id', 'CASCADE');
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
