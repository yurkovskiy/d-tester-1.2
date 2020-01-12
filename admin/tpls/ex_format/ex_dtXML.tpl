<?php

/**
 * @package d-tester
 * @subpackage d-tester admin subsystem: export manager
 * @version 1.2 RC1
 * @name  d-tester XML format template v. 1.0
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2007 Yuriy Bezgachnyuk, IF, Ukraine
 *
 * Start date  24/06/2007 05:53 GMT +02:00
 * Last Update 07/12/2007 21:50 GMT +02:00
 * 
 * This template do not enter in base system release
 * Note: Working only in PHP5
 * 
 * All Rights Reserved
 */

require_once("inc/functions.inc");

if (!check_php_version()) {
	echo "<b>[ERROR]</b>&nbsp;PHP Version must be 5.1.x or later";
	exit;
}

// Global constants
define("XML_VERSION", "1.0");
define("XML_ENCODING", "UTF-8");

$EXPORT['TEST_ID'] = $_POST['test_id'];

$file_name = $PARAM['EXFILE_PREFIX']."test_dtXML";

// XML tags for questions
$dtXML_question_tags = array("q_text", "q_level", "q_media", "q_media_file");

// XML tags for answers
$dtXML_answer_tags = array("ans_true", "ans_media", "ans_media_file", "ans_text");

/**
 * @var Questions tags array [which will be written to file]
 */
$qtags = array();

/**
 * @var Answers tags array [which will be written to file]
 */
$atags = array();

/**
 * @var Temporary variable for saving current node value
 */
$tmp_v = "";

// Create new DOM object
$doc = new DOMDocument(XML_VERSION, XML_ENCODING); // XML version = 1.0 encoding = UTF-8

// Root Element <questions></questions>
$root_el = $doc->createElement("questions");

$questions = $DB->query("SELECT * FROM questions WHERE q_test_id='".$EXPORT['TEST_ID']."' ORDER BY question_id ASC");

while ($q_row = $DB->fetch_row($questions)) {

	$q_el = $doc->createElement("question");
	//$q_row[2] = iconv("Windows-1251", "UTF-8", dt_preg_replaceb($q_row[2])); // converting text to UTF-8
	$q_row[2] = iconv("Windows-1251", "UTF-8", $q_row[2]);
	
	for ($i = 0;$i < sizeof($dtXML_question_tags);$i++) {
		$qtags[$i] = $doc->createElement($dtXML_question_tags[$i]);
		$tmp_v = $doc->createTextNode($q_row[$i + 2]);
		$qtags[$i]->appendChild($tmp_v);
		$q_el->appendChild($qtags[$i]);
	}

	$question_id = $q_row[0];
	$ra_el = $doc->createElement("answers");
	$res_a = $DB->query("SELECT * FROM answers WHERE aq_id=$question_id ORDER BY ans_id ASC");

	while ($a_row = $DB->fetch_row($res_a)) {

		$a_el = $doc->createElement("answer");

		$a_row[4] = iconv("Windows-1251", "UTF-8", dt_preg_replaceb($a_row[4]));

		for ($i = 0;$i < sizeof($dtXML_answer_tags);$i++) {
			$atags[$i] = $doc->createElement($dtXML_answer_tags[$i]);
			$tmp_v = $doc->createTextNode($a_row[$i + 1]);
			$atags[$i]->appendChild($tmp_v);
			$a_el->appendChild($atags[$i]);
		}

		$ra_el->appendChild($a_el);
		$q_el->appendChild($ra_el);
	}

	$root_el->appendChild($q_el);
}

$doc->appendChild($root_el);

header("Content-type: application/xml");
header("Content-Disposition: attachment; filename={$file_name}.xml");

echo $doc->saveXML();

// End of saving

?>