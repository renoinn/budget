<?php

function get_period_one_month() {
	$start = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
	$end = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 1, date('Y')));
	return '\''.$start.'\' < created AND created < \''.$end.'\'';
}
