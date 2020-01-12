<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2 RC1
 * @name reg.php - Registration Unit
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2004-2008 Yuriy Bezgachnyuk
 *
 * Last update: 06/02/2008 18:59 GMT +02:00 
 *
 * All Rights Reserved
 */

require_once("req.inc");

// Register Data
if (isset($_GET['action'])) $action = $_GET['action'];
if (isset($_GET['what'])) $what = $_GET['what'];

if(!isset($action)) {
	switch($what) {

		case "new_ip": {
			if($_SESSION['adm_priv'] != ROOT) {
				header("Location: main.php");
				break;
			}

			$ip_addr = trim($_POST['ip_addr']);
			$DB->query("SELECT * FROM admins_ip WHERE ip_addr='$ip_addr'");
			if($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_REPEAT_DATA");
			}
			$DB->query("INSERT INTO admins_ip (ip_addr) VALUES('".$ip_addr."')");
			$rs = $DB->get_affected_rows();
			if($rs) header("Location: ip_cont.php");
			break;
		}

		case "new_subj": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$subject_name = addslashes(trim($_POST['subject_name']));
			if(strlen($subject_name) < 6) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("SELECT subject_id FROM subjects WHERE subject_name = '{$subject_name}'");
			if ($DB->get_num_rows() != 0 ) {
				$DB->free_result();
				Show_Message("DB_ERROR_REPEAT_DATA");
			}
			$DB->free_result();
						$DB->query("INSERT INTO subjects (subject_name, subject_id) VALUES ('".$subject_name."',null)");
			$rs = $DB->get_affected_rows();

			if($rs) header("Location: subjects.php");
			break;
		}

		case "new_tt": {
			if(($_SESSION['adm_priv'] == SUBJECT_MAN)
			&&($_SESSION['SB_WRITE'] == "N")) {
				header("Location: main.php");
				break;
			}
			$subj = $_POST['subject'];
			$group = $_POST['group'];
			$e_date = $_POST['e_date'];
			$DB->query("SELECT * FROM time_table WHERE group_id='$group' AND subject_id='$subj'");
			if($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("INSERT INTO time_table (id, group_id, subject_id, event_date) VALUES(null,'".$group."','".$subj."','".$e_date."')");
			$_GET['subject'] = $subj;
			header("Location: time_table.php?subject={$subj}");
			break;
		}

		case "new_user": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$user_name = addslashes(trim($_POST['user_name']));
			$user_order_num = addslashes(trim($_POST['user_order_num']));
			$pass_word = md5(addslashes(trim($_POST['pass_word'])));
			$conf_pass = md5(addslashes(trim($_POST['conf_pass'])));
			$gr = $_GET['gr'];

			$DB->query("SELECT user_name FROM users WHERE user_name='$user_name' AND user_group='$gr'");
			if($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_TWICE_USER_REG");
			}

			if(strstr($pass_word, $conf_pass) == null) {
				Show_Message("DB_ERROR_INPUT_PASSWORDS");
			}
			$DB->query("INSERT INTO users (user_name, user_group, user_pass, user_order_num, user_id) 
						VALUES('".$user_name."',$gr,'".$pass_word."', '".$user_order_num."', null)");
			$rs = $DB->get_affected_rows();
			if($rs) header("Location: users.php?group={$gr}");
			break;
		}

		case "new_group": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$group_name = trim($_POST['group_name']);
			$spec_id = intval($_POST['spec_id']);
			
			$DB->query("SELECT group_name FROM groups WHERE group_name='$group_name'");
			if ($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_REPEAT_DATA");
			}
			
			$DB->free_result();
			if(strlen($group_name) < 7) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("INSERT INTO groups (group_name, group_id, spec_id) VALUES ('".$group_name."',null, '".$spec_id."')");
			$rs = $DB->get_affected_rows();
			if($rs) header("Location: groups.php");
			break;
		}

		case "new_test": {
			if(($_SESSION['adm_priv'] == SUBJECT_MAN)
			&&($_SESSION['SB_WRITE']=="N")) {
				header("Location: main.php");
				break;
			}
			$test_name = addslashes(trim($_POST['test_name']));
			$subjects = $_POST['subjects'];
			$tasks = intval($_POST['tasks']);
			$h_chan = intval($_POST['h_chan']);
			$test_time = $_POST['test_time'];
			$show_test = $_POST['show_test'];
			$test_type = intval($_POST['test_type']);
			if($tasks <= 0) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("SELECT test_name FROM tests WHERE test_subject_id=$subjects");
			while($DB->fetch_row()) {
				if(strstr($DB->record_row[0], $test_name) != null) {
					Show_Message("DB_ERROR_REPEAT_DATA");
				}
			}
			$DB->free_result();
			$DB->query("INSERT INTO tests (test_name, test_subject_id, how_tasks, test_time, enabled, chances, test_type, test_id)
						VALUES ('".$test_name."','".$subjects."','".$tasks."','".$test_time."','".$show_test."','".$h_chan."', '".$test_type."', null)");
			$rs = $DB->get_affected_rows();
			$DB->query("SELECT MAX(test_id) FROM tests");
			$DB->fetch_row();
			$test_dir = $PARAM['TEST_BASE'].$DB->record_row[0];
			$dir = @mkdir($test_dir, $PARAM['TEST_DIR_MASK']); // Creting test directory in a server
			if(!$dir) {
				Show_Message("MKDIR_ERROR");
			}
			$DB->free_result();
			if($rs) header("Location: tests.php?subject={$subjects}");
			break;
		}

		case "new_detail": {
			if(($_SESSION['adm_priv'] == SUBJECT_MAN)
			&&($_SESSION['SB_WRITE'] == "N")) {
				header("Location: main.php");
				break;
			}
			$test = $_GET['test'];
			$level_id = $_POST['level_id'];
			$tasks_num = $_POST['tasks_num'];
			$level_rate = $_POST['level_rate'];
			
			$DB->query("SELECT level_id 
						FROM test_details 
						WHERE test_id='$test' 
						AND level_id ='$level_id'");
			
			if($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_REPEAT_DATA");
			}
			
			$DB->free_result();
			
			$DB->query("INSERT INTO test_details (id, test_id, level_id, level_tasks, level_rate)
						VALUES (null,'".$test."','".$level_id."','".$tasks_num."','".$level_rate."')");
			
			$rs = $DB->get_affected_rows();
			
			if($rs) header("Location: test_details.php?tst={$test}");
			break;
		}
	}
}

// Update Data

if($action == "update") {
	$edit = $_GET['edit'];

	switch($edit) {
		case "group": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$gp = $_GET['gp'];
			$group_name = trim($_POST['group_name']);
			$spec_id = intval($_POST['spec_id']);
			$DB->query("SELECT group_name FROM groups WHERE group_name='$group_name' AND spec_id = '$spec_id'");
			if ($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_REPEAT_DATA");
			}
			
			$DB->free_result();
			if(strlen($group_name) < 7) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("UPDATE groups SET group_name='$group_name', spec_id = '$spec_id' WHERE group_id='$gp'");
			$rs = $DB->get_affected_rows();
			if($rs) header("Location: groups.php");
			break;
		}

		case "user": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$user_name = addslashes(trim($_POST['user_name']));
			$user_order_num = addslashes(trim($_POST['user_order_num']));
			$pass_word = md5(addslashes(trim($_POST['pass_word'])));
			$conf_pass = md5(addslashes(trim($_POST['conf_pass'])));
			$group_id = intval($_POST['group_id']);
			$us = $_GET['us'];

			$DB->query("SELECT user_name FROM users WHERE user_name='$user_name' AND user_pass = '$pass_word' AND user_group='$group_id'");
			if($DB->get_num_rows() != 0) {
				Show_Message("DB_ERROR_TWICE_USER_REG");				
			}
			
			if(strstr($pass_word, $conf_pass)==null) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->free_result();
			$DB->query("UPDATE users SET user_name='$user_name', user_pass='$pass_word', user_group='$group_id', user_order_num = '$user_order_num' WHERE user_id='$us'");
			header("Location: users.php?group={$group_id}");
			break;
		}

		case "test": {
			if(($_SESSION['adm_priv'] == SUBJECT_MAN)
			&&($_SESSION['SB_WRITE'] == "N")) {
				header("Location: main.php");
				break;
			}
			$ts = $_GET['ts'];
			$test_name = addslashes(trim($_POST['test_name']));
			$subjects = $_POST['subjects'];
			$tasks = intval($_POST['tasks']);
			$h_chan = intval($_POST['h_chan']);
			$show_test = $_POST['show_test'];
			$test_type = $_POST['test_type'];
			if($tasks <= 0) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$test_time = $_POST['test_time'];
			if(strlen($test_name) < 4) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$DB->query("SELECT test_name, how_tasks, test_subject_id, test_time, enabled, chances, test_type 
						FROM tests 
						WHERE test_id={$ts}");
			
			while($DB->fetch_row()) {
				if((strstr($DB->record_row[0], $test_name) != null)
				&&($DB->record_row[1] == $tasks)
				&&($DB->record_row[2] == $subjects)
				&&(strstr($DB->record_row[3], $test_time) != null)
				&&($DB->record_row[4] == $show_test)
				&&($DB->record_row[5] == $h_chan)
				&&($DB->record_row[6] == $test_type)) {
					Show_Message("DB_ERROR_REPEAT_DATA");
				}
			}
			$DB->free_result();
			$DB->query("UPDATE tests SET test_name='$test_name', test_subject_id='$subjects', how_tasks='$tasks', test_time='$test_time', enabled='$show_test', chances='$h_chan', test_type='$test_type'
						WHERE test_id='$ts'");
			$rs = $DB->get_affected_rows();
			if($rs) header("Location: tests.php?subject={$_GET['subj']}");
			break;
		}

		case "timetable": {
			if(($_SESSION['adm_priv'] == SUBJECT_MAN)
			&&($_SESSION['SB_WRITE'] == "N")) {
				header("Location: main.php");
				break;
			}
			$ID = $_GET['ID'];
			$e_date = $_POST['e_date'];
			$DB->query("UPDATE time_table SET event_date='$e_date' WHERE id='$ID'");
			header("Location: time_table.php?subject={$_POST['subject']}");
			break;
		}

		case "test_details": {
				if(($_SESSION['adm_priv'] == SUBJECT_MAN)
				&&($_SESSION['SB_WRITE'] == "N")) {
					header("Location: main.php");
					break;
				}
				$test = $_GET['test'];
				$ID = $_GET['ID'];
				$tasks_num_value = $_POST['tasks_num'];
				$level_rate_value = $_POST['level_rate'];

				$DB->query("UPDATE test_details SET level_tasks='$tasks_num_value', level_rate='$level_rate_value'
							WHERE id='$ID'");
				$_GET['tst'] = $test;
				require_once("test_details.php");
				break;
			}

		case "subject": {
			if($_SESSION['adm_priv'] == SUBJECT_MAN) {
				header("Location: main.php");
				break;
			}
			$sub = $_GET['sub'];
			$subject_name = addslashes(trim($_POST['subject_name']));
			if(strlen($subject_name) < 7) {
				Show_Message("DB_ERROR_INPUT_DATA");
			}
			$subject_name = addslashes($subject_name);
			$DB->query("SELECT subject_name FROM subjects");
			while($DB->fetch_row()) {
				if(strlen($subject_name) == strlen($DB->record_row[0])) {
					if(strstr($subject_name, $DB->record_row[0]) != null) {
						Show_Message("DB_ERROR_REPEAT_DATA");
					}
				}
			}
			$DB->free_result();
			$DB->query("UPDATE subjects SET subject_name='$subject_name' WHERE subject_id='$sub'");
			$rs = $DB->get_affected_rows();

			if($rs) header("Location: subjects.php");
			break;
		}
	}
}

?>