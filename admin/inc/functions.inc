<?php

/**
* @name functions.inc 	1.1	02/04/2007
* @package d-tester
* @subpackage d-tester admin subsystem
* @version 1.1 RC1
* @author Yuriy Bezgachnyuk
* @copyright (c) 2006-2007 Yuriy Bezgachnyuk
* 
* Last update: 03.03.2007 07:45 GMT +02:00
* All Rights Reserved
*/

/**
 * changing HTML entities (some symbols) to a real symbols
 *
 * @param string $in_word
 * @return string $out_word
 */

function dt_preg_replace($in_word) {
    $SYMBOLS = array("/\&quot;/","/\'/","/\&gt;/","/\&lt;/","/\&amp;nbsp;/");
    $PR_SYMB = array("\"","&#180;",">","<","&nbsp;");

    $out_word = $in_word;

    for($i = 0;$i < sizeof($SYMBOLS);$i++) {
        $out_word = preg_replace($SYMBOLS[$i], $PR_SYMB[$i], $out_word);
    }

    return $out_word;
}

/**
 * Changing HTML special chars to a code
 *
 * @param string $in_word
 * @return string $out_word
 */

function dt_preg_replaceb($in_word) {
    $SYMBOLS = array("/\>/","/\</","/\&nbsp;/","/\"/");
    $PR_SYMB = array("&gt;","&lt;","&amp;nbsp;","&quot;");

    $out_word = $in_word;

    for($i = 0;$i < sizeof($SYMBOLS);$i++) {
        $out_word = preg_replace($SYMBOLS[$i], $PR_SYMB[$i], $out_word);
    }

    return $out_word;
}

/**
 * Generating random password string
 *
 * @param int $length
 * @return string
 */

function generate_password($length, $rep = false) {
    $password = array();
    $password_s = ""; // string return variable

    for($i = 0;$i < $length;$i++) {
    	if (!$rep) $password[$i] = rand(0, 9);
    	else $password[$i] = 1;
        $password_s.=strval($password[$i]);
    }

    return $password_s;
}

/**
 * Returns true if the current version of PHP is greater that the specified one.
 *
 * @param string $version The version of php being tested.
 * @return boolean
 * @todo Finish documenting this function
 */

function check_php_version($version = "5.1.0") {
    return (version_compare(phpversion(), $version) >= 0);
}

/**
 * Reducing image width
 *
 * @param int $width
 * @param int $base_width
 * @return int $r_width
 */

function reduce_img_width($width, $base_width) {
	if ($width > $base_width) {
		$coeff = ($base_width / $width);
		$r_width = floor($coeff * $width);
		return $r_width;
	}
	else {
		return $width;
	}
}

?>