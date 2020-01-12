<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage admin subsystem
 * @name ECTS marks
 * @author Yuriy Bezgachnyuk
 * @copyright 2009 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date
 * Last update
 */

require_once("../inc/mysql.inc");

$ECTS_SYMBOLS = array(); // ECTS mark symbols (A, B, C, D, E, FX, F)

$NAT_SCALE = array(); // National (Ukrainian) scale (відмінно, добре, добре, задовільно, задовільно, незадовільно, незадовільно)

$ECTS_MIN_VALUES = array();

$ECTS_MAX_VALUES = array();

$DB->query("SELECT ects_mark_symbol, state_mark_name, percent_min_value, percent_max_value
			FROM ects_marks ORDER BY ects_mark_id ASC");

while ($DB->fetch_row()) {
	$ECTS_SYMBOLS[$DB->record_row[0]] = $DB->record_row[0];
	$NAT_SCALE[$DB->record_row[0]] = $DB->record_row[1];
	$ECTS_MIN_VALUES[$DB->record_row[0]] = $DB->record_row[2];
	$ECTS_MAX_VALUES[$DB->record_row[0]] = $DB->record_row[3];
}

$DB->free_result();

?>