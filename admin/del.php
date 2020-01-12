<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2 RC1
 * @name del.php - eraser unit
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2005-2008 Yuriy Bezgachnyuk
 *
 * Last update: 07/01/2008 09:17 GMT +02:00 
 *
 * All Rights Reserved
 */

require_once("req.inc");

// What Data

if (isset($_GET['what'])) $what = $_GET['what'];
if (isset($_GET['ID'])) $ID = $_GET['ID'];

switch($what) {
	case "ad_ips": {
		if($_SESSION['adm_priv'] != ROOT) {
			header("Location: main.php");
			break;
		}
		$DB->query("DELETE FROM admins_ip WHERE ip_addr='$ID'");
		header("Location: ip_cont.php");
		break;
	}

	case "a_sess": {
		$file = $PARAM['SESS_DIR'].$ID;
		if (file_exists($file)) {
			$f = @unlink($file);
			if(!$f) {
				Show_Message("FILE_ERROR");
			}
		}
		$DB->query("DELETE FROM active_sess WHERE session_id='$ID'");
		header("Location: sess.php");
		break;
	}

	case "session": {
		$sess = $_POST['sess'];
		if(($_SESSION['adm_priv'] != SUBJECT_MAN)
		||(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['RES_DELETE']=="Y"))) {
			if(isset($sess)) {
				while(list($key,$val) = each($sess)) {
					$DB->query("DELETE FROM session_results WHERE sess_id=$val");
				}
			}
			$_POST['test'] = $_POST['test'];
			$_POST['group'] = $_POST['group'];
			$_POST['sort_order'] = "asc";
			$_POST['sort'] = 0;
			require_once("show_results.php");
		}
		else header("Location: main.php");
		break;
	}

	case "users": {
		if($_SESSION['adm_priv'] == SUBJECT_MAN) {
			header("Location: main.php");
			break;
		}
		$DB->query("SELECT user_id FROM active_sess WHERE user_id='$ID'");
		if($DB->get_num_rows() != 0) {
			Show_Message("CANNOT_DEL_USER");
		}
		$DB->query("DELETE FROM users WHERE user_id='$ID'");
		$DB->query("DELETE FROM session_results WHERE user_id='$ID'");
		$DB->query("DELETE FROM dt_enter_logs WHERE user_id='$ID'");
		$DB->query("DELETE FROM rating_results WHERE user_id='$ID'");
		header("Location: users.php?group={$_GET['gr']}");
		break;
	}

	case "timetable": {
		if(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['SB_DELETE'] == "N")) {
			header("Location: main.php");
			break;
		}
		$DB->query("DELETE FROM time_table WHERE id='$ID'");
		header("Location: time_table.php?subject={$_GET['subj']}");
		break;
	}

	case "group": {
		if($_SESSION['adm_priv'] == SUBJECT_MAN) {
			header("Location: main.php");
			break;
		}
		$DB->query("SELECT COUNT(*) FROM active_sess, users WHERE active_sess.user_id=users.user_id AND users.user_group='$ID'");
		$DB->fetch_row();
		if($DB->record_row[0] != 0) {
			Show_Message("CANNOT_DEL_USER");
		}
		$DB->query("DELETE FROM groups WHERE group_id='$ID'");
		$DB->query("DELETE FROM time_table WHERE group_id='$ID'");
		$user_ids = $DB->query("SELECT user_id FROM users WHERE user_group='$ID'");
		while($DB->fetch_row($user_ids)) {
			$user_id = $DB->record_row[0];
			$DB->query("DELETE FROM dt_enter_logs WHERE user_id='$user_id'");
			$DB->query("DELETE FROM session_results WHERE user_id='$user_id'");
			$DB->query("DELETE FROM rating_results WHERE user_id='$user_id'");
		}

		$DB->query("DELETE FROM users WHERE user_group='$ID'");
		header("Location: groups.php");
		break;
	}

	case "test": {
		if(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['SB_DELETE'] == "N")) {
			header("Location: main.php");
			break;
		}
		$DB->query("DELETE FROM test_details WHERE test_id='$ID'");
		$res_questions = $DB->query("SELECT question_id FROM questions WHERE q_test_id='$ID'");
		while($row = $DB->fetch_row($res_questions)) {
			$DB->query("DELETE FROM answers WHERE aq_id='$row[0]'");
		}
		$DB->query("DELETE FROM questions WHERE q_test_id='$ID'");
		$test_dir = $PARAM['TEST_BASE'].$ID;
		$dir = @opendir($test_dir);
		while($file = @readdir($dir)) {
			if(($file == ".") ||($file == "..")) continue;
			$del_file = $test_dir."/".$file;
			@unlink($del_file);
		}
		@closedir($dir);
		@rmdir($test_dir);
		$DB->query("DELETE FROM tests WHERE test_id='$ID'");
		$DB->query("DELETE FROM session_results WHERE test_id='$ID'");
		$DB->query("DELETE FROM rating_results WHERE test_id='$ID'");
		$DB->query("DELETE FROM dt_enter_logs WHERE test_id='$ID'");
		header("Location: tests.php?subject={$_GET['subj']}");
		break;
	}

	case "test_details": {
		if(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['SB_DELETE'] == "N")) {
			header("Location: main.php");
			break;
		}
		$DB->query("DELETE FROM test_details WHERE id='$ID'");
		header("Location: test_details.php?tst={$_GET['test']}");
		break;
	}

	case "quest": {
		if(($_SESSION['adm_priv'] == SUBJECT_MAN)
		&&($_SESSION['SB_DELETE'] == "N")) {
			header("Location: main.php");
			break;
		}
		$DB->query("DELETE FROM answers WHERE aq_id='$ID'");
		$DB->query("SELECT q_test_id FROM questions WHERE question_id=$ID");
		$DB->fetch_row();
		$test_id = $DB->record_row[0];
		$DB->query("DELETE FROM questions WHERE question_id='$ID'");

		header("Location: tasks.php?tst={$test_id}");
		break;
	}

	case "admin": {
		if($_SESSION['adm_priv'] != ROOT) {
			header("Location: index.php");
			break;
		}
		$DB->query("SELECT priv FROM admins WHERE admin_id='".$ID."'");
		$DB->fetch_row();
		if(intval($DB->record_row[0]) == 0) {
			header("Location: index.php");
			break;
		}
		else {
			$DB->query("DELETE FROM admin_logs WHERE admin_id='".$ID."'");
			$DB->query("DELETE FROM dt_enter_logs WHERE admin_id='".$ID."'");
			$DB->query("DELETE FROM admin_priv WHERE admin_id='".$ID."'");
			$DB->query("DELETE FROM admins WHERE admin_id='".$ID."'");
			header("Location: admins.php");
		}
		break;
	}
}

?>