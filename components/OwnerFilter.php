<?php

namespace app\components;

use Yii;
use yii\base\ActionFilter;
use app\models\User;
use yii\web\ForbiddenHttpException;

/**
 * Checks if the current user is admin or it's id corresponds to the id
 * get parameter
 */
class OwnerFilter extends ActionFilter {

	public function beforeAction($action) {
		$user = User::getActiveUser();
		$id = Yii::$app->request->getQueryParam('id');
		if ($user && ($user->admin || $user->id == $id)) {
			return parent::beforeAction($action);
		}
		throw new ForbiddenHttpException('You are not allowed to perform this operation.');
	}
}
