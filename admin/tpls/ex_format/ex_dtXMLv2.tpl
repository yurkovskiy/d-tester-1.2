<?php

/**
 * @package d-tester
 * @subpackage d-tester admin subsystem: export manager
 * @version 1.2 RC1
 * @name  d-tester XML format template v. 2.0
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2017 Yuriy Bezgachnyuk, IF, Ukraine
 *
 * Start date  15/12/2017 10:54 GMT +02:00
 * Last update 18/12/2017 12:57 GMT +02:00
 * 
 * This export format created for exporting Q/A from d-tester 1.2 to dtapi 2.1 DB
 * 
 * All Rights Reserved
 */

require_once("inc/functions.inc");

function generate_image_header($ext) {
	$ext = strtoupper($ext);
	$header_base = array("JPG" => "jpeg", "PNG" => "png");
	return "data:image/".$header_base[$ext].";base64,";	
}

if (!check_php_version()) {
	echo "<b>[ERROR]</b>&nbsp;PHP Version must be 5.1.x or later";
	exit;
}

// Global constants
define("XML_VERSION", "1.0");
define("XML_ENCODING", "UTF-8");

$EXPORT['TEST_ID'] = $_POST['test_id'];

$IMAGE_FOLDER = "http://". $_SERVER["HTTP_HOST"]."/tests/test_".$EXPORT["TEST_ID"];

$file_name = "test_".$EXPORT['TEST_ID']."dtXMLv2"; // d-tester XML v2 has some diffs between d-tester XML 1.0

/**
 * Question matching array v1 => v2
 */

$dtXMLv2_question_tags = array("q_text" => "question_text", "q_level" => "level", "q_media" => "type", "media_file" => "attachment");

/**
 * Answer matching array v1 => v2
 */

$dtXMLv2_answer_tags = array("ans_true" => "true_answer", "ans_text" => "answer_text", "media_file" => "attachment");

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
	$q_row["q_text"] = iconv("Windows-1251", "UTF-8", $q_row["q_text"]);
	
	foreach ($dtXMLv2_question_tags as $key => $value) {
		$qtags[$key] = $doc->createElement($value);
		// generate base64 for image which in dt 1.2 is a file
		if (($key == "media_file") && ($q_row[$key] != "")) {
			$path_to_image = $IMAGE_FOLDER."/".$q_row[$key];
			$image_base64 = generate_image_header(pathinfo($path_to_image)["extension"]).base64_encode(file_get_contents($path_to_image));
			$tmp_v = $doc->createTextNode($image_base64);
		}
		else {
			$tmp_v = $doc->createTextNode($q_row[$key]);
		}
		
		$qtags[$key]->appendChild($tmp_v);
		$q_el->appendChild($qtags[$key]);
	}

	$question_id = $q_row[0];
	$ra_el = $doc->createElement("answers");
	$res_a = $DB->query("SELECT * FROM answers WHERE aq_id={$question_id} ORDER BY ans_id ASC");

	// Answers
	while ($a_row = $DB->fetch_row($res_a)) {

		$a_el = $doc->createElement("answer");
		$a_row["ans_text"] = iconv("Windows-1251", "UTF-8", $a_row["ans_text"]);
		
		foreach ($dtXMLv2_answer_tags as $key => $value) {
			$atags[$key] = $doc->createElement($value);
			// generate base64 for image which in dt 1.2 is a file
			if (($key == "media_file") && ($a_row[$key] != "")) {
				$path_to_image = $IMAGE_FOLDER."/".$a_row[$key];
				$image_base64 = generate_image_header(pathinfo($path_to_image)["extension"]).base64_encode(file_get_contents($path_to_image));
				$tmp_v = $doc->createTextNode($image_base64);
			}
			else {
				$tmp_v = $doc->createTextNode($a_row[$key]);
			}
			$atags[$key]->appendChild($tmp_v);
			$a_el->appendChild($atags[$key]);
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