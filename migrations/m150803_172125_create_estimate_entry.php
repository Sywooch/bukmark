<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_172125_create_estimate_entry extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('estimate_entry', [
			'id' => Schema::TYPE_PK,
			'estimate_id' => Schema::TYPE_INTEGER,
			'product_id' => Schema::TYPE_INTEGER,
			'variant_id' => Schema::TYPE_INTEGER,
			'quantity' => Schema::TYPE_MONEY,
			'utility' => Schema::TYPE_MONEY,
			'price' => Schema::TYPE_MONEY,
				], $tableOptions);
		
		$this->addForeignKey('fk_estimate_entry_estimate', 'estimate_entry', 'estimate_id', 'estimate', 'id', 'CASCADE');
		$this->addForeignKey('fk_estimate_entry_product', 'estimate_entry', 'product_id', 'product', 'id', 'CASCADE');
		$this->addForeignKey('fk_estimate_entry_variant', 'estimate_entry', 'variant_id', 'variant', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropTable('estimate_entry');
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
