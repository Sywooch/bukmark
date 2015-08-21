<?php

use yii\db\Schema;
use yii\db\Migration;

class m150803_081915_create_variant extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('variant', [
			'id' => Schema::TYPE_PK,
			'product_id' => Schema::TYPE_INTEGER,
			'description' => Schema::TYPE_TEXT,
			'price' => Schema::TYPE_MONEY,
			'currency' => Schema::TYPE_SMALLINT,
				], $tableOptions);
		
		$this->addForeignKey('fk_variant_product', 'variant', 'product_id', 'product', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropForeignKey('fk_variant_product', 'variant');
		$this->dropTable('variant');
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
