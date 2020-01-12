<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage admin subsystem
 * @name copy module [optional]
 * @author Yuriy Bezgachnyuk
 * @copyright 2006-2008 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 28/09/2008 17:29 GMT +02:00
 */

require_once("req.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

if (isset($_GET['action'])) $action = $_GET['action'];

if(!isset($_GET['action'])) {
	page_begin($lang['cptest_href']);
	require_once("tpls/cp_form.tpl");
	page_end();
	exit;
}

if($action == "copy") {
	// Parameters:
	// From:
	$FROM['test_id'] = $_POST['from_test_id'];
	$FROM['level_id'] = $_POST['from_level_id'];

	// To:
	$TO['test_id'] = $_POST['to_test_id'];
	$TO['level_id'] = $_POST['to_level_id'];
	
	if(($TO['test_id'] == $FROM['test_id'])
	   &&($TO['level_id'] == $FROM['level_id'])) {
		Show_Message("COULD_NOT_COPY_IN_SRC");
	}

	// Process

	global $q_row, $a_row, $qid;

	// Визначаємо структуру завдань, що необхідно скопіювати
	$questions = $DB->query("SELECT * FROM questions WHERE q_test_id='".$FROM['test_id']."' AND q_level='".$FROM['level_id']."' ORDER BY question_id");

	while($q_row = $DB->fetch_row($questions)) {
		// Копіюємо завдання
		
		$q_row[2] = addslashes($q_row[2]);
		
		$q_query="INSERT INTO questions (question_id, q_test_id, q_text, q_level, q_media, media_file)
			VALUES (null,'".$TO['test_id']."','".$q_row[2]."','".$TO['level_id']."','".$q_row[4]."','".$q_row[5]."')";
		$DB->query($q_query);

		//echo "Q:&nbsp;&nbsp;&nbsp;{$q_query}<br>\n";

		$DB->query("SELECT MAX(question_id) FROM questions");
		$DB->fetch_row();
		$qid = $DB->record_row[0]; // New question identificator
		$DB->free_result();

		// Визначаємо структуру відповідей даного завдання
		$answers = $DB->query("SELECT * FROM answers WHERE aq_id='".$q_row[0]."' ORDER BY ans_id");
		while($a_row = $DB->fetch_row($answers)) {
			
			$a_row[4] = addslashes($a_row[4]);
			
			// Копіюємо відповіді
			$a_query = "INSERT INTO answers (aq_id, ans_true, ans_media, media_file, ans_text, ans_id) 
			            VALUES('".$qid."','".$a_row[1]."','".$a_row[2]."','".$a_row[3]."','".$a_row[4]."',null)";
			//echo "A:&nbsp;&nbsp;&nbsp;{$a_query}<br>\n";
			$DB->query($a_query);
		} // 1 Question structure with answers is copied
	} // All questions structures with answers is copied
	
	$_SERVER['PHP_SELF'] = "tasks.php";
	header("Location: tasks.php?tst={$TO['test_id']}");
}

?>