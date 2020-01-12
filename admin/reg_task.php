<?php

/**
* @package d-tester
* @version 1.1 RC1
* @name reg_task.php	1.1	02/04/2007
* @author Yuriy Bezgachnyuk
* @copyright (c) 2006-2007 Yuriy Bezgachnyuk
* Last Update 07.08.2006 21:47 GMT +02:00
* 
* 
* All Rights Reserved
*/

require_once("req.inc");
require_once("inc/functions.inc");

$action = $_GET['action']; // new/update

switch($action) {
	case "new": {
		if(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['SB_WRITE'] == "N")) {
			header("Location: main.php");
			break;
		}

		$step = $_GET['step'];

		switch($step) {
			case 1: {
				page_begin("");
				echo "<script src=\"{$PARAM['FJS_FILE']}\">\n</script>\n";
				require_once("tpls/ae_q_s2.tpl");
				break;
			}

			case 2: {
				// Save data to database
				$DATA['test_id'] = intval($_POST['test_id']);
				$DATA['q_level'] = intval($_POST['q_level']);
				$DATA['q_type'] = intval($_POST['q_type']);
				$DATA['q_text'] = dt_preg_replace($_POST['q_text']);
				$DATA['amedia_type'] = intval($_POST['amedia_type']);
				$DATA['ans_num'] = intval($_POST['ans_num']);
				$DATA['qm_file'] = $_POST['qm_file'];

				// question
				$DB->query("INSERT INTO questions (question_id, q_test_id, q_text, q_level, q_media, media_file)
							VALUES(null,'".$DATA['test_id']."','".$DATA['q_text']."','".$DATA['q_level']."','".$DATA['q_type']."','".$DATA['qm_file']."')");

				$DB->query("SELECT MAX(question_id) FROM questions");
				$DB->fetch_row();
				$qid = $DB->record_row[0]; // question_id
				$DB->free_result();

				// answers
				for($i = 0;$i < $DATA['ans_num'];$i++) {
					$a_name = "a_body_".$i;
					$a_bool_name = "a_true_".$i;
					$amd_name = "amedia_file_".$i;

					// preg_replace special symbols
					$_POST[$a_name] = dt_preg_replace(stripslashes($_POST[$a_name]));

					$DB->query("INSERT INTO answers (aq_id, ans_true, ans_media, media_file, ans_text, ans_id)
				   				VALUES('".$qid."','".$_POST[$a_bool_name]."','".$DATA['amedia_type']."','".$_POST[$amd_name]."','".$_POST[$a_name]."',null)");
				}

				header("Location: tasks.php?ts={$DATA['test_id']}");
				break;
			}
		}
		break;
	}
	case "update": {
		$qid = intval($_GET['question']);
		$DATA['test_id'] = intval($_GET['test_id']);
		$DATA['ans_num'] = intval($_POST['ans_num']);
		$DATA['q_level'] = intval($_POST['q_level']);
		$DATA['qm_file'] = $_POST['qm_file'];

		// preg_replace special symbols
		$q_text = dt_preg_replace(stripslashes(trim($_POST['q_text'])));

		$DATA['q_text'] = $q_text;
		unset($q_text);

		$q_query = "UPDATE questions SET q_text='".$DATA['q_text']."', q_level='".$DATA['q_level']."', media_file='".$DATA['qm_file']."' WHERE question_id=$qid";
		$DB->query($q_query);

		for($i = 0;$i < $DATA['ans_num'];$i++)
		{
			$a_id = "aid_".$i;
			$a_name = "a_body_".$i;
			$a_bool_name = "a_true_".$i;
			$amd_name = "amedia_file_".$i;

			// preg_replace special symbols
			$_POST[$a_name] = dt_preg_replace(stripslashes($_POST[$a_name]));

			$aid = $_POST[$a_id];

			$a_query = "UPDATE answers SET ans_true='".$_POST[$a_bool_name]."', media_file='".$_POST[$amd_name]."', ans_text='".$_POST[$a_name]."' WHERE ans_id=$aid";
			$DB->query($a_query);
		}

		header("Location: tasks.php?ts={$DATA['test_id']}");
		break;
	}
}
page_end();

?>