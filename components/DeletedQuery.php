<?php

namespace app\components;

use yii\db\ActiveQuery;

class DeletedQuery extends ActiveQuery {
	
	public function active() {
		return $this->andWhere(['or', ['deleted' => null], ['deleted' => 0]]);
	}
}