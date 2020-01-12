<?php

/**
 * @package d-tester
 * @subpackage d-tester admin subsystem: export manager
 * @version 1.2 RC1
 * @name  Moodle XML format template v. 1.0 [Tested with LMS Moodle 1.5.3, 1.6, 1.6.5, 1.9.5]
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2007-2008 Yuriy Bezgachnyuk, IF, Ukraine
 *
 * Start date  28/11/2007 19:16 GMT +02:00
 * Last Update 10/02/2008 08:31 GMT +02:00
 * 
 * This template does not enter in base system release
 * Note: Working only in PHP5
 * 
 * All Rights Reserved
 */

require_once("inc/functions.inc");
require_once("../inc/q_types.inc");

if (!check_php_version()) {
	echo "<b>[ERROR]</b>&nbsp;PHP Version must be 5.1.x or later";
	exit;
}

// Global constants
define("XML_VERSION", "1.0");
define("XML_ENCODING", "UTF-8");

// Global Constants for LMS moodle [in a next version may be a variable parameter]
/* 
Next parameters needed for register question in LMS Moodle, but not present in d-tester, then 
we are using some default parameters
*/
define("Q_PENALTY", 0.1); // question penalty [default]
define("Q_HIDDEN", 0); // question hidden [default]
define("USE_CASE", 1); // use case for short answer 1 is default
define("A_SHUFFLE", "true"); // answer shuffle 

// Answer mark round error
define("ROUND_COEF", 3);

// Answers constants
define("MAX_RATE", "100");
define("MIN_RATE", "0");

define("TRUE_ANS", "2");
define("FALSE_ANS", "1");

define("NUM_MAX_ANS", 2);

// temporary variables for numerical questions
$ans_n = array();
$count = 0;
$base_value = 0.0;
$tolerance = 0.0;

/**
 * @var $q_mtypes - d-tester & moodle questions types equ
 * 
 * $q_mtypes = array(key => value) 
 * key - d-tester question type constant
 * value - moodle question type
 */
$q_mtypes = array(SIMPLE_CHOICE => 'multichoice', SIMPLE_CHOICE_IMG => 'multichoice',
MULTI_CHOICE => 'multichoice', MULTI_CHOICE_IMG => 'multichoice',
INPUT_FIELD => 'shortanswer', INPUT_FIELD_IMG => 'shortanswer',
NUMERICAL => 'numerical', NUMERICAL_IMG => 'numerical');

/**
 * @var moodle single tag equivalent for d-tester question types
 * 
 * $q_mc_single = array(key => value)
 * key - d-tester question type constant [SIMPLE_CHOICE, SIMPLE_CHOICE_IMG, MULTI_CHOICE, MULTI_CHOICE_IMG] only
 * value - moodle single tag value for simple/multi choice questions type
 */
$q_mc_single = array(SIMPLE_CHOICE => 'true', SIMPLE_CHOICE_IMG => 'true', MULTI_CHOICE => 'false', MULTI_CHOICE_IMG => 'false');

$EXPORT['TEST_ID'] = $_POST['test_id'];

$file_name = $PARAM['EXFILE_PREFIX']."moodle_XML";

/**
 * @var Temporary variable for saving current node value
 */
$tmp_v = "";

/**
 * @var temporary rate
 */
$tmp_rate = "";

// Create new DOM object
$doc = new DOMDocument(XML_VERSION, XML_ENCODING); // XML version = 1.0 encoding = UTF-8

// Root Element <quiz></quiz>
$root_el = $doc->createElement("quiz");

$questions = $DB->query("SELECT * FROM questions WHERE q_test_id='".$EXPORT['TEST_ID']."' ORDER BY question_id ASC");

while ($q_row = $DB->fetch_row($questions)) {

	$q_id = "qid_".$q_row[0];

	$q_row[2] = iconv("Windows-1251", "UTF-8", dt_preg_replaceb($q_row[2])); // converting text to UTF-8
	//$q_row[2] = iconv("Windows-1251", "UTF-8", $q_row[2]); // converting text to UTF-8

	$q_el = $doc->createElement("question");

	switch ($q_row[4]) {

		default: {
			$q_el->setAttribute("type", $q_mtypes[$q_row[4]]);
			$q_name = $doc->createElement("name");
			$q_ntext = $doc->createElement("text");
			$tmp_v = $doc->createTextNode($q_id);
			$q_ntext->appendChild($tmp_v);
			$q_name->appendChild($q_ntext);
			$q_el->appendChild($q_name);

			$q_txformat = $doc->createElement("questiontext");
			$q_txformat->setAttribute("format", "html");
			$q_txtext = $doc->createElement("text");
			$tmp_v = $doc->createTextNode($q_row[2]);

			$q_txtext->appendChild($tmp_v);
			$q_txformat->appendChild($q_txtext);
			$q_el->appendChild($q_txformat);

			$q_img = $doc->createElement("image");
			$tmp_v = $doc->createTextNode($q_row[5]);
			$q_img->appendChild($tmp_v);
			$q_el->appendChild($q_img);

			$q_pen = $doc->createElement("penalty");
			$tmp_v = $doc->createTextNode(Q_PENALTY);
			$q_pen->appendChild($tmp_v);
			$q_el->appendChild($q_pen);

			$q_hid = $doc->createElement("hidden");
			$tmp_v = $doc->createTextNode(Q_HIDDEN);
			$q_hid->appendChild($tmp_v);
			$q_el->appendChild($q_hid);

			$q_shuffle_ans = $doc->createElement("shuffleanswers");
			$tmp_v = $doc->createTextNode(A_SHUFFLE);
			$q_shuffle_ans->appendChild($tmp_v);
			$q_el->appendChild($q_shuffle_ans);

			if (($q_row[4] >= SIMPLE_CHOICE) && ($q_row[4] <= MULTI_CHOICE_IMG)) {
				$q_sin = $doc->createElement("single");
				$tmp_v = $doc->createTextNode($q_mc_single[$q_row[4]]);
				$q_sin->appendChild($tmp_v);
				$q_el->appendChild($q_sin);
			}

			if (($q_row[4] == INPUT_FIELD) || ($q_row[4] == INPUT_FIELD_IMG)) {
				$q_usc = $doc->createElement("usecase");
				$tmp_v = $doc->createTextNode(USE_CASE);
				$q_usc->appendChild($tmp_v);
				$q_el->appendChild($q_usc);
			}

			if (($q_row[4] != NUMERICAL) && ($q_row[4] != NUMERICAL_IMG)) {
				// Extract answers information
				$answers = $DB->query("SELECT * FROM answers WHERE aq_id=$q_row[0] ORDER BY ans_id ASC");
				while ($a_row = $DB->fetch_row($answers)) {

					$aq_root = $doc->createElement("answer");

					switch ($q_row[4]) {
						case SIMPLE_CHOICE:
						case SIMPLE_CHOICE_IMG:
						case INPUT_FIELD:
						case INPUT_FIELD_IMG: {
							if ($a_row[1] == TRUE_ANS) {
								$tmp_rate = MAX_RATE;
							}
							if ($a_row[1] == FALSE_ANS) {
								$tmp_rate = MIN_RATE;
							}
							break;
						}

						case MULTI_CHOICE:
						case MULTI_CHOICE_IMG: {
							$mc_markq = $DB->query("SELECT COUNT(ans_true) FROM answers WHERE ans_true='2' AND aq_id=$q_row[0]");
							$mc_row = $DB->fetch_row($mc_markq);
							$mc_mark = round((MAX_RATE / $mc_row[0]), ROUND_COEF);
							$DB->free_result($mc_markq);
							if ($a_row[1] == TRUE_ANS) {
								$tmp_rate = $mc_mark;
							}
							if ($a_row[1] == FALSE_ANS) {
								$tmp_rate = -MAX_RATE;
							}
							break;
						}
					}

					$aq_root->setAttribute("fraction", $tmp_rate);
					$ans_text = $doc->createElement("text");
					$tmp_v = $doc->createTextNode(iconv("Windows-1251", "UTF-8", $a_row[4]));
					$ans_text->appendChild($tmp_v);
					$aq_root->appendChild($ans_text);

					$q_el->appendChild($aq_root);
				}
			}
			
			if (($q_row[4] == NUMERICAL) || ($q_row[4] == NUMERICAL_IMG)) {
				$an_q = $DB->query("SELECT ans_text FROM answers WHERE aq_id=$q_row[0] ORDER BY ans_id ASC");
				while ($an_r = $DB->fetch_row($an_q)) {
					$ans_n[$count] = doubleval($an_r[0]);
					$count++;
				}
				$base_value = ($ans_n[0] + $ans_n[1]) / NUM_MAX_ANS;
				$tolerance = round(($base_value - $ans_n[0]), 3);
				
				$aq_nroot = $doc->createElement("answer");
				$tmp_v = $doc->createTextNode($base_value);
				$aq_nroot->appendChild($tmp_v);
				$an_tol = $doc->createElement("tolerance");
				$tmp_v = $doc->createTextNode($tolerance);
				$an_tol->appendChild($tmp_v);
				$aq_nroot->appendChild($an_tol);
				$aq_nf = $doc->createElement("fraction");
				$tmp_v = $doc->createTextNode("1");
				$aq_nf->appendChild($tmp_v);
				$aq_nroot->appendChild($aq_nf);
				$q_el->appendChild($aq_nroot);	
				
				$count = 0;
				$base_value = 0.0;
				$tolerance = 0.0;
			}
			
			break;
		}
	}
	$root_el->appendChild($q_el);
}

$doc->appendChild($root_el);

header("Content-type: application/xml");
header("Content-Disposition: attachment; filename={$file_name}.xml");

echo $doc->saveXML();

// End of saving

?>