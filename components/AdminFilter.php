<?php

namespace app\components;

use Yii;
use yii\base\ActionFilter;
use app\models\User;
use yii\web\ForbiddenHttpException;

/**
 * Checks if the current user is admin
 */
class AdminFilter extends ActionFilter {

	public function beforeAction($action) {
		$user = User::getActiveUser();
		if ($user && $user->admin) {
			return parent::beforeAction($action);
		}
		throw new ForbiddenHttpException('You are not allowed to perform this operation.');
	}
}
