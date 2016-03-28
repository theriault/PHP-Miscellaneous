<?php
/**
 * Convert a string consisting of only letters into an integer
 * (A = 1, B = 2, ..., Z = 26, AA = 27, AB = 28, ..., ZZ = 702, AAA = 703, ...)
 *
 * @param string $a The string to convert
 * @param bool $memoization Whether to cache results to increase
 *  speed of subsequent calls at the expense of memory.
 * @return int The converted value or FALSE on failure
 */
function alpha2num($a, $memoization = false) {
	static $cache = array("" => false);
	$a = (string) $a;
	$memoization = (bool) $memoization;
	
	if ($memoization && isset($cache[$a])) {
		return $cache[$a];
	}
	
	for ($n = 0, $i = strlen($a) - 1, $j = 0; $i >= 0; $i--, $j++) {
		$ord = ord($a[$i]);
		if ($ord >= 97 && $ord <= 122) {
			$ord &= 95;
		} else if ($ord < 65 || $ord > 90) {
			return ($memoization ? $cache[$a] = false : false);
		}
		$n += pow(26, $j) * ($ord - 0x40);
	}
	
	return ($memoization ? $cache[$a] = $n : $n);
}
