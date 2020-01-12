<?php

// EDIT.PHP - EDIT administration information id d-tester DKCS database
// Copyright (c) 2005 by Yuriy Bezgachnyuk, IF, Ukraine

require_once("req.inc");

page_begin("");
echo "<script src=\"".$PARAM['FJS_FILE']."\"></script>\n";

$action = $_GET['action'];

switch ($action)
{
	case "admins": {
		$id = $_GET['id'];
		$DB->query("SELECT admin_name, priv, real_name FROM admins WHERE admin_id='".$id."'");
		$DB->fetch_row();
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg_admin.php?what=update&id={$id}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		$value_1 = $DB->record_row[0];
		$value_2 = $DB->record_row[1];
		$value_3 = $DB->record_row[2];
		$priv_values = array();
		$DB->free_result();
		$DB->query("SELECT * FROM admin_priv WHERE admin_id='".$id."' ORDER BY id LIMIT 1");
		$DB->fetch_row();
		for($i = 0;$i < sizeof($lang['ROOT_PRIV']);$i++) {
			if($DB->record_row[$i + 3] == "Y") $priv_values[$i] = "checked";
			else $priv_values[$i] = "";
		}
		$value_4 = $DB->record_row[2];
		$DB->free_result();
		$disabled = "disabled";
		require_once("tpls/ae_admin.tpl");
		echo "</FORM>\n";
		break;
	}

	case "groups":
	{
		$grp = $_GET['grp'];
		$DB->query("SELECT group_name, spec_id FROM groups WHERE group_id='$grp'");
		$DB->fetch_row();
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=group&gp={$grp}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		$value_1 = $DB->record_row[0];
		$value_2 = $DB->record_row[1];
		$DB->free_result();
		$DB->query("SELECT spec_id, spec_code, spec_name FROM specialities ORDER BY spec_id ASC");
		require_once("tpls/ae_group.tpl");
		echo "<SCRIPT language=\"JavaScript\">\ndocument.forms['register_form'].spec_id.value=\"{$value_2}\"\n</SCRIPT>\n";		
		echo "</FORM>\n";
		break;
	}

	case "users":
	{
		$id = $_GET['id'];
		$DB->query("SELECT user_name, user_order_num, user_group FROM users WHERE user_id='$id'");
		$DB->fetch_row();
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=user&us={$id}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		$value_1 = $DB->record_row[0];
		$value_2 = $DB->record_row[1];
		$group_id = $DB->record_row[2];
		$DB->free_result();
		$DB->query("SELECT group_id, group_name FROM groups ORDER BY group_id ASC");
		require_once("tpls/ae_user.tpl");
		echo "<SCRIPT language=\"JavaScript\">\ndocument.forms['register_form'].group_id.value=\"{$group_id}\"\n</SCRIPT>\n";
		echo "</FORM>\n";
		break;
	}

	case "tests":
	{
		$tst = $_GET['tst'];
		$subj = $_GET['subj'];
		$DB->query("SELECT  test_name, test_subject_id, how_tasks, test_time, enabled, chances, test_type FROM tests WHERE test_id='$tst'");
		$row = $DB->fetch_row();
		$sid = $row[1];
		$DB->free_result();
		$DB->query("SELECT subject_id, subject_name FROM subjects");
		// $value_x - Template Variable
		$value_1 = $row[0];
		$value_2 = $row[2];
		$value_3 = $row[3];
		$value_4 = $row[4];
		$value_5 = $row[5];
		$value_6 = $row[6];
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=test&subj={$subj}&ts={$tst}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_test.tpl");
		echo "<SCRIPT language=\"JavaScript\">\ndocument.forms['register_form'].subjects.value=\"{$sid}\"\n</SCRIPT>\n";
		echo "</FORM>\n";
		break;
	}

	case "timetable":
	{
		$ID = $_GET['ID'];
		$subj = $_GET['subj'];
		$DB->query("SELECT groups.group_name, time_table.event_date
					FROM groups, time_table 
					WHERE time_table.id='$ID' AND groups.group_id=time_table.group_id");
		$DB->fetch_row();
		$type = "edit";
		$value_1 = $DB->record_row[0];
		$value_2 = $DB->record_row[1];
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=timetable&ID={$ID}\" method=\"POST\" name=\"td_FORM\">\n";
		require_once("tpls/ae_timetbl.tpl");
		echo "</FORM>\n";
		$DB->free_result();
		break;
	}

	case "test_details":
	{
		$test = $_GET['test'];
		$ID = $_GET['ID'];
		$DB->query("SELECT level_id, level_tasks, level_rate FROM test_details WHERE id='$ID'");
		$DB->fetch_row();
		$type = "edit";
		$value_1 = $DB->record_row[0];
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=test_details&test={$test}&ID={$ID}\" method=\"POST\" name=\"td_FORM\">\n";
		require_once("tpls/ae_testdet.tpl");
		echo "<SCRIPT language=\"JavaScript\">\ndocument.forms['td_FORM'].tasks_num.value=\"{$DB->record_row[1]}\"\ndocument.forms['td_FORM'].level_rate.value=\"{$DB->record_row[2]}\"\n</SCRIPT>\n";
		echo "</FORM>\n";
		$DB->free_result();
		break;
	}

	case "subject":
	{
		$subj = $_GET['subj'];
		$DB->query("SELECT subject_name FROM subjects WHERE subject_id='$subj'");
		$DB->fetch_row();
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?action=update&edit=subject&sub={$subj}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		$value_1 = $DB->record_row[0];
		require_once("tpls/ae_subj.tpl");
		echo "</FORM>\n";
		break;
	}

	case "quest":
	{
		$quest = $_GET['quest'];
		require_once("tpls/e_quest.tpl");
		break;
	}
}
page_end();
?>