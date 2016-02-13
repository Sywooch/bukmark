<?php

use yii\db\Schema;
use yii\db\Migration;

class m160211_150419_add_client_contact_id_to_estimate extends Migration
{
    public function up()
    {
		$this->addColumn('estimate', 'client_contact_id', Schema::TYPE_INTEGER . ' AFTER client_id');
		$this->addForeignKey('fk_estimate_client_contact', 'estimate', 'client_contact_id', 'client_contact', 'id');
    }

    public function down()
    {
		$this->dropForeignKey('fk_estimate_client_contact', 'estimate');
        $this->dropColumn('estimate', 'client_contact_id');
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
