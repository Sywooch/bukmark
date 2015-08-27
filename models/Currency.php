<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $date
 * @property string $us
 */
class Currency extends \yii\db\ActiveRecord {

	const API_URL = 'http://api.bluelytics.com.ar/v2/latest';
	const EXCEPTION_MSG = 'Could not update dollar value.';

	/* Currency types go here.
	  IMPORTANT: If a currency is added it must also be added
	  to labels(). */
	const CURRENCY_ARS = 0;
	const CURRENCY_USD = 1;
	const US_TO_ARS_MARGIN = 0.04;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'currency';
	}

	/**
	 * Get currency labels
	 * @return string[]
	 */
	public static function labels() {
		return [
			self::CURRENCY_ARS => '$',
			self::CURRENCY_USD => 'US$',
		];
	}

	/**
	 * Format value to $ value
	 * @param float $value
	 * @param integer $currency
	 * @return string
	 */
	public static function format($value, $currency) {
		$valueFormatted = null;
		if ($value) {
			$valueFormatted = self::labels()[$currency] . ' ' . Yii::$app->formatter->asDecimal($value, 2);
		}
		return $valueFormatted;
	}

	/**
	 * 
	 * @return float
	 */
	public static function getUsToArs() {
		$last = self::find()->orderBy(['id' => SORT_DESC])->one();
		$today = date('Y-m-d');
		if (!$last || $last->date != $today) {
			$last = self::updateUsToArs();
		}
		if (!$last) {
			throw new \yii\base\Exception(self::EXCEPTION_MSG);
		}
		return $last->us + self::US_TO_ARS_MARGIN;
	}

	/**
	 * @return Currency
	 */
	public static function updateUsToArs() {
		$model = new self();
		$json = file_get_contents(self::API_URL);
		$obj = json_decode($json);
		if (isset($obj->oficial) && isset($obj->oficial->value_sell)) {
			$model = new self();
			$model->date = date('Y-m-d');
			$model->us = $obj->oficial->value_sell;
			$model->save();
			return $model;
		} else {
			throw new \yii\base\Exception(self::EXCEPTION_MSG);
		}
	}

}
