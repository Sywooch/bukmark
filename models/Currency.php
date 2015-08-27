<?php

namespace app\models;
use Yii;

class Currency {
	
	/* Currency types go here.
	  IMPORTANT: If a currency is added it must also be added
	  to labels(). */
	const CURRENCY_ARS = 0;
	const CURRENCY_USD = 1;
	
	const US_TO_ARS = 9.2;
	const US_TO_ARS_MARGIN = 0.04;

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
		if($value) {
			$valueFormatted = self::labels()[$currency] . ' ' . Yii::$app->formatter->asDecimal($value, 2);
		}
		return $valueFormatted;
	}
}
