<?php

/**
 * @package d-tester
 * @subpackage admin subsystem [import unit]
 * @version 1.2 RC1
 * @name dt-XML import template
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2008 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 07/10/2008 11:58 GMT +02:00
 */

$file = "temp/".$new_file_name;

// Codepages constants, in later versions will be change to variable parameters
define("IN_CODEPAGE", "UTF-8");

define("OUT_CODEPAGE", "Windows-1251");

$xml = @simplexml_load_file($file);

if (!$xml) {
	@unlink($upfile);
	die("XML Parse Error");
}

$count = 1;

// extract questions structure
foreach ($xml->question as $question) {
	$q_text = dt_preg_replace(iconv(IN_CODEPAGE, OUT_CODEPAGE, $question->q_text));
	//$q_text = iconv(IN_CODEPAGE, OUT_CODEPAGE, $question->q_text);
	$q_level = intval($question->q_level) + $im_level_offset;
	$q_media = intval($question->q_media);
	$q_media_file = $question->q_media_file;
	
	$question_element = array("", $test_id, $q_text, $q_level, $q_media, $q_media_file);
	
	array_push($questions, $question_element); // Push question element into questions array
	
	// answers
	foreach ($question->answers->answer as $answer) {
		$ans_true = strval($answer->ans_true);
		$ans_media = intval($answer->ans_media);
		$ans_media_file = strval($answer->ans_media_file);
		$ans_text = dt_preg_replace(iconv(IN_CODEPAGE, OUT_CODEPAGE, $answer->ans_text));
		
		$answer_element = array($ans_true, $ans_media, $ans_media_file, $ans_text, "");
		
		array_push($pre_answer, $answer_element);
		
	}
	
	array_push($answers, $pre_answer); // Push pre_answer element into answers array
	
	$pre_answer = array();
	
	$count++;
}

?>