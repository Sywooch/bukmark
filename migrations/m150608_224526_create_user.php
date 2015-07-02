<?php

use yii\db\Schema;
use yii\db\Migration;

class m150608_224526_create_user extends Migration
{
    public function up() {

		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('user', [
			'id' => Schema::TYPE_PK,
			'username' => Schema::TYPE_STRING,
			'password' => Schema::TYPE_STRING,
				], $tableOptions);
	}

	public function down() {
		$this->dropTable('user');
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
