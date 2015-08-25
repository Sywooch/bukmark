<?php

namespace app\helpers;

/*
 * This class is used to format date from dd/MM/yyyy to yyyy-MM-dd
 */

class DateConverter {

	/**
	 * @param string $date
	 * @return string
	 */
	public static function convert($date) {
		if ($date) {
			$array = explode('/', $date);
			if (count($array) == 3) {
				$date = $array[2] . '-' . $array[1] . '-' . $array[0];
			}
		}
		return $date;
	}

}
