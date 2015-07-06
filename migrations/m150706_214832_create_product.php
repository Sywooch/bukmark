<?php

use yii\db\Schema;
use yii\db\Migration;

class m150706_214832_create_product extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('product', [
			'id' => Schema::TYPE_PK,
			'category_id' => Schema::TYPE_INTEGER,
			'supplier_id' => Schema::TYPE_INTEGER,
			'provider_code' => Schema::TYPE_STRING,
			'bukmark_code' => Schema::TYPE_STRING,
			'image' => Schema::TYPE_STRING,
			'description' => Schema::TYPE_TEXT,
			'price' => Schema::TYPE_DECIMAL,
			'currency' => Schema::TYPE_SMALLINT,
				], $tableOptions);
		
		$this->addForeignKey('fk_product_category', 'product', 'category_id', 'category', 'id', 'CASCADE');
		$this->addForeignKey('fk_product_supplier', 'product', 'supplier_id', 'supplier', 'id', 'CASCADE');
	}

	public function down() {
		$this->dropTable('product');
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
