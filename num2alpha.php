<?php
/**
 * Convert an integer into a string consisting of only letters.
 * (1 = A, 2 = B, ..., 26 = Z, 27 = AA, 28 = AB, ..., 702 = ZZ, 703 = AAA, ...)
 *
 * @param int $n The number to convert (1..PHP_INT_MAX)
 * @param bool $memoization Whether to cache results to increase speed
 *  at the expense of memory.
 * @return string The converted string or FALSE on failure.
 */
function num2alpha($n, $memoization = false) {
	static $cache = array();
	$n = (int) $n;
	$memoization = (bool) $memoization;
	if ($memoization && isset($cache[$n])) {
		return $cache[$n];
	}
	if ($n < 1) return ($memoization ? $cache[$n] = false : false);
	for ($r = "", $m = $n - 1; $m >= 0; $m = intval($m / 26) - 1) {
		$r = chr($m % 26 + 0x41) . $r;
	}
	return ($memoization ? $cache[$n] = $r : $r);
}
