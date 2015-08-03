<?php

namespace app\models;

class Currency {
	
	/* Currency types go here.
	  IMPORTANT: If a currency is added it must also be added
	  to currencyLabels(). */
	const CURRENCY_ARS = 0;
	const CURRENCY_USD = 1;
	
	/**
	 * Pattern for number validator
	 */
	const VALIDATOR_PATTERN = '/^\s*\-*[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

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
