<?php

namespace app\models;

class Currency {
	
	/* Currency types go here.
	  IMPORTANT: If a currency is added it must also be added
	  to labels(). */
	const CURRENCY_ARS = 0;
	const CURRENCY_USD = 1;
	
	const US_TO_ARS = 9.2;

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

	

}
