<?php

namespace app\components;

use yii\db\ActiveRecord;
use yii\base\Behavior;

class NoDeleteBehavior extends Behavior {
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
		];
	}
	
	public function beforeDelete($event) {
		$event->sender->deleted = true;
		$event->sender->save(false);
		$event->isValid = false;
	}
}

