<?php

/**
 * @package d-tester 
 * @version 1.1 RC1
 * @subpackage d-tester administrative subsystem
 * @name dt-PHP export format template
 * @author Yuriy V. Bezgachnyuk
 * @copyright Yuriy V. Bezgachnyuk, IF, Ukraine
 *
 * Start date  03/09/2006 08:01 GMT +02:00
 * Last Update 
 *
 * All Rights Reserved
 */

$FILE_HEADER = array(
"<?php\n// DUMP Test FROM d-tester DATABASE\n// System version: {$lang['system_version']}\n// Unit Version: 0.2 \n// Copyright (c)2006-2007 by Yuriy Bezgachnyuk, IF, Ukraine\n// Format: {$lang['ex_formats'][$ex_format]}\n// Exported: {$cur_date}\n\n",
"// Для занесення інформації в базу даних необхідно доповнити файл функціями запису в базу даних або скористатись модулем імпорту\n",
"// End of file\n?>"
);

$HELP_COMMETS = array(
"// \$test=array(\"<TEST_NAME>\",\"<SUBJECT_ID>\",\"<HOW_TASKS>\",\"<TEST_TIME>\",\"<ENABLED>\",\"<CHANCES>\",\"<TEST_ID>\")\n",
"// \$questions=array(array(\"<QUESTION_ID>\",\"<TEST_ID>\",\"<Q_TEXT>\",\"<Q_LEVEL>\",\"<Q_MEDIA>\",\"<MEDIA_FILE>\"),array(),...,array()\n",
"// \$ans=array(array(array(\"<TRUE_ANS>\",\"<ANS_MEDIA>\",\"<MEDIA_FILE>\",\"<ANS_TEXT>\",\"<ANS_ID>\"),.....))\n",
);

global $q_row, $a_row, $qid, $EX_FILE_C_Q, $EX_FILE_C_A;
$DB->query("SELECT test_name, test_id, how_tasks, test_time FROM tests WHERE test_id='".$_POST['test_id']."'");
$DB->fetch_row();
$EXPORT['TEST_NAME'] = $DB->record_row[0];
$EXPORT['TEST_ID'] = $DB->record_row[1];
$EXPORT['TEST_TASKS'] = $DB->record_row[2];
$EXPORT['TEST_TIME'] = $DB->record_row[3];
$DB->free_result();

$file_name = $PARAM['EXFILE_PREFIX']."test_".$EXPORT['TEST_ID'];

// Data Generating Process
$EX_FILE_C['TEST'] = "\$test=array(\"{$EXPORT['TEST_NAME']}\",0,{$EXPORT['TEST_TASKS']},\"{$EXPORT['TEST_TIME']}\",0,1,{$EXPORT['TEST_ID']});";
$EX_FILE_C_Q[0] = "\$questions=array(";
$EX_FILE_C_A[0] = "\$ans=array(";

$count = 1;
$a_count = 1;
$questions = $DB->query("SELECT * FROM questions WHERE q_test_id='".$EXPORT['TEST_ID']."' ORDER BY question_id ASC");
while($q_row = $DB->fetch_row($questions))
{
	$q_row[2] = stripslashes(stripslashes($q_row[2]));
	$EX_FILE_C_Q[$count] = "array(null,{$EXPORT['TEST_ID']},\"{$q_row[2]}\",{$q_row[3]},{$q_row[4]},\"{$q_row[5]}\"),";

	$qid = $q_row[0];

	$answers = $DB->query("SELECT * FROM answers WHERE aq_id=$qid ORDER BY ans_id ASC");
	$EX_FILE_C_A[$a_count] = "array(";
	$a_count++;
	while($a_row = $DB->fetch_row($answers))
	{
		$a_row[4] = stripslashes(stripslashes($a_row[4]));
		$EX_FILE_C_A[$a_count] = "array(\"{$a_row[1]}\",{$a_row[2]},\"{$a_row[3]}\",\"{$a_row[4]}\",null),";
		$a_count++;
	}
	$EX_FILE_C_A[$a_count] = "),";
	$a_count++;
	$DB->free_result($answers);
	$count++;
}
$DB->free_result($questions);
$EX_FILE_C_Q[$count] = ");";
$EX_FILE_C_A[$a_count] = ");";

header("Content-type: application/dt-php");
header("Content-Disposition: attachment; filename={$file_name}.dt_php");


// Saving Data to file
echo "{$FILE_HEADER[0]}\n{$FILE_HEADER[1]}\n{$HELP_COMMETS[0]}\n{$EX_FILE_C['TEST']}\n\n{$HELP_COMMETS[1]}\n";

for($i = 0;$i < sizeof($EX_FILE_C_Q);$i++) echo "{$EX_FILE_C_Q[$i]}\n";

echo "\n{$HELP_COMMETS[2]}";

for($i = 0;$i < sizeof($EX_FILE_C_A);$i++) echo "{$EX_FILE_C_A[$i]}\n";

echo "{$FILE_HEADER[2]}\n";

// End of saving

?>