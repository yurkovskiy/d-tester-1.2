<?php

/**
 *
 * @package d-tester 
 * @version 1.2 RC1
 * @subpackage d-tester administrative subsystem
 * @name import interface unit
 * @author Yuriy V. Bezgachnyuk
 * @copyright (c) 2008 by Yuriy V. Bezgachnyuk
 *
 * Start date  26/09/2008 21:32 GMT +02:00
 * Last update 
 *
 * All Rights Reserved
 */

require_once("req.inc");
require_once("inc/im_const.inc");
require_once("inc/functions.inc");

if ($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

/**
 * @var array - questions structure array
 */

$questions = array();

/**
 * @var array - answers array of all questions
 */
$answers = array();

/**
 * @var array - answers array of one question
 */
$pre_answer = array();

if(!isset($action)) {
	page_begin($lang['import_href']);
	require_once("tpls/im_form.tpl");
	page_end();
	exit;
}

if($action == "import") {
	$im_format = $_POST['im_format'];
	$cur_date = date("d/m/Y H:i:s");
	$test_id = intval($_POST['test_id']);
	$im_level_offset = intval($_POST['im_level_offset']);
	
	require_once("inc/file_upload.inc");
	
	switch($im_format) {
		
		case IM_dtPHP_FORMAT: // [dt-PHP] Format
		{
			//require_once("tpls/im_format/im_dtphp.tpl");
			die("Not realized at present moment");
			break;
		}

		case IM_dtXML_FORMAT: // d-tester XML
		{
			//echo "DT_XML";
			require_once("tpls/im_format/im_dtXML.tpl");
			break;
		}
	}
	
	// INSERT information to database

	for($i = 0;$i < sizeof($questions);$i++)
	{
		$q_query = "INSERT INTO questions (q_test_id, q_text, q_level, q_media, media_file)
				VALUES('".$questions[$i][1]."','".$questions[$i][2]."','".$questions[$i][3]."','".$questions[$i][4]."','".$questions[$i][5]."')";

		//echo "{$q_query}<br>\n";

		$DB->query($q_query);

		$DB->query("SELECT MAX(question_id) FROM questions");
		$DB->fetch_row();
		$aq_id = $DB->record_row[0];
		
		//echo "<b>Answers:</b><br>\n";

		for($k = 0;$k < sizeof($answers[$i]);$k++)
		{
			$a_query = "INSERT INTO answers (aq_id, ans_true, ans_media, media_file, ans_text)
					VALUES ('".$aq_id."','".$answers[$i][$k][0]."','".$answers[$i][$k][1]."','".$answers[$i][$k][2]."','".$answers[$i][$k][3]."')";

			//echo "{$a_query}<br>\n";

			$DB->query($a_query);
		}
		//echo "<hr><br><br>\n";
	}
}

$how_tasks = sizeof($questions);
echo "<b>All operations complete <br> {$how_tasks} question(-s) imported</b>";

@unlink($upfile); // Delete temporary file

?>