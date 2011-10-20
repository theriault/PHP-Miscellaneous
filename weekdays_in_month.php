<?php
# Calculate number of weekdays (Mon..Fri) in given year and month.
function weekdays($year, $month) {
	list($t, $N) = explode(' ', date('t N', mktime(0, 0, 0, $month, 1, $year)));
        return $t - 8 - ($t > 28 ? ($N > ($t < 30 ? 5 : 3)) + ($t != 29 && $N == 6) : 0);
}