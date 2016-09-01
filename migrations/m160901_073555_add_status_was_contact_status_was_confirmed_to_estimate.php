<?php

use yii\db\Migration;
use yii\db\Schema;

class m160901_073555_add_status_was_contact_status_was_confirmed_to_estimate extends Migration
{
    public function up()
    {
		$this->addColumn('estimate', 'status_was_contact', Schema::TYPE_BOOLEAN . ' AFTER status');
		$this->addColumn('estimate', 'status_was_confirmed', Schema::TYPE_BOOLEAN . ' AFTER status_was_contact');
    }

    public function down()
    {
		$this->dropColumn('estimate', 'status_was_confirmed');
		$this->dropColumn('estimate', 'status_was_contact');
    }
}
