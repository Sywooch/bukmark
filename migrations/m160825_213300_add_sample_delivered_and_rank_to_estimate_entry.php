<?php

use yii\db\Migration;
use yii\db\Schema;

class m160825_213300_add_sample_delivered_and_rank_to_estimate_entry extends Migration
{
    public function up()
    {
		$this->addColumn('estimate_entry', 'rank', Schema::TYPE_INTEGER . ' AFTER product_image_id');
		$this->addColumn('estimate_entry', 'sample_delivered', Schema::TYPE_BOOLEAN);
    }

    public function down()
    {
		$this->dropColumn('estimate_entry', 'sample_delivered');
		$this->dropColumn('estimate_entry', 'rank');
    }
}
