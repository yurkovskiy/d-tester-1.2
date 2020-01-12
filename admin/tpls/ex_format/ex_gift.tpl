<?php

/**
 * @package d-tester
 * @subpackage d-tester admin subsystem: export manager
 * @version 1.1 RC1
 * @name  GIFT Format template [tested with LMS Moodle] 
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2006-2007 Yuriy Bezgachnyuk, IF, Ukraine
 *
 * Start date  07/03/2007 06:38 GMT +02:00
 * Last Update 07/03/2007 18:16 GMT +02:00
 * 
 * This template does not enter in base system release
 * 
 * All Rights Reserved
 */

require_once("../inc/q_types.inc");
 
$FILE_HEADER = array(
"// DUMP Test FROM d-tester DATABASE\n// System version: {$lang['system_version']}\n// Unit Version: 0.2 \n// Copyright (c)2006-2007 by Yuriy Bezgachnyuk, IF, Ukraine\n// Format: {$lang['ex_formats'][$ex_format]}\n// Exported: {$cur_date}\n\n",
"// End of file\n"
);

$npass = 1; // temporary pass variable for numerical/multi choice question type
$mc_mark = 0.0; // multi choice question temporary mark variable

$EXPORT['TEST_ID'] = $_POST['test_id'];

global $q_row, $a_row, $qid, $EX_FILE_C_Q;

$file_name = $PARAM['EXFILE_PREFIX']."test_gift";

// Data Generating Process
$EX_FILE_C_Q[0] = "// questions structure\n";

$count = 1;
$questions = $DB->query("SELECT * FROM questions WHERE q_test_id='".$EXPORT['TEST_ID']."' ORDER BY question_id ASC");
while($q_row = $DB->fetch_row($questions)) {
	
	$q_row[2] = stripslashes(stripslashes($q_row[2]));
	
	$EX_FILE_C_Q[$count] = "// question {$count} name: q_{$count}\n::q_{$count}::[html]{$q_row[2]}";
	$qid = $q_row[0];

	$answers = $DB->query("SELECT * FROM answers WHERE aq_id=$qid ORDER BY ans_id ASC");
	$EX_FILE_C_Q[$count].="{\n";
	while($a_row = $DB->fetch_row($answers)) {
		
		$a_row[4] = stripslashes(stripslashes($a_row[4]));
		
		switch($q_row[4]) {
			// Simple Choice
			case SIMPLE_CHOICE:
			case SIMPLE_CHOICE_IMG: {
				if($a_row[1] == "2") $EX_FILE_C_Q[$count].="={$a_row[4]}\n";
				else $EX_FILE_C_Q[$count].="~{$a_row[4]}\n";
				break;
			}
			
			// Multi Choice
			case MULTI_CHOICE:
			case MULTI_CHOICE_IMG: {
				
				if($npass == 1) {
					$true_aq = $DB->query("SELECT COUNT(*) FROM answers WHERE ans_true=2 AND aq_id=$qid");
					$crow = $DB->fetch_row($true_aq);
					$c = intval($DB->record_row[0]);
					$mc_mark = round((100 / $c), 3);
					$DB->free_result($true_aq);
					unset($true_aq);
					unset($crow);
					unset($c);
					$npass++;
				}
				
				if($a_row[1] == "2") $EX_FILE_C_Q[$count].="~%{$mc_mark}%{$a_row[4]}\n";
				else $EX_FILE_C_Q[$count].="~%-100%{$a_row[4]}\n";
								
				break;
			}
			
			// Input Field [Fill in blank]
			case INPUT_FIELD:
			case INPUT_FIELD_IMG: {
				$EX_FILE_C_Q[$count].="=%100%{$a_row[4]}#\n";
				break;
			}
			
			// Numerical
			case NUMERICAL:
			case NUMERICAL_IMG: {
				if($npass == 1) {$EX_FILE_C_Q[$count].="#{$a_row[4]}..";$npass++;}
				else $EX_FILE_C_Q[$count].="{$a_row[4]}#\n";
				break;
			}
		}
	}
	$npass = 1;
	$EX_FILE_C_Q[$count].="}\n";
	$DB->free_result($answers);
	$count++;
}
$DB->free_result($questions);

header("Content-type: application/txt");
header("Content-Disposition: attachment; filename={$file_name}.txt");

// Saving Data to file
echo "{$FILE_HEADER[0]}\n";

for($i = 0;$i < sizeof($EX_FILE_C_Q);$i++) echo "{$EX_FILE_C_Q[$i]}\n";

echo "{$FILE_HEADER[1]}\n";

// End of saving

?>