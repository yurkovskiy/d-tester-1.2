<?php

// ADD.PHP - Show ADDs forms (subjects,users,tests,groups)
// Copyright (c) 2004,2005 by Yuriy Bezgachnyuk, IF, Ukraine

require_once("req.inc");

$action = $_GET['action'];

page_begin("");
echo "<script src=\"".$PARAM['FJS_FILE']."\">\n</script>\n";

switch ($action)
{
	case "new_admin":
	{
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg_admin.php?what=new_admin\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_admin.tpl");
		echo "</FORM>\n";
		break;
	}

	case "new_subj":
	{
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_subj\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_subj.tpl");
		echo "</FORM>\n";
		break;
	}

	case "timetable":
	{
		$subj = $_GET['subject'];
		$DB->query("SELECT group_name, group_id FROM groups ORDER BY group_id ASC");
		if($DB->get_num_rows() < 1)
		{
			Show_Message("DB_ERROR_NO_GROUPS");
		}
		$type = "add";
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_tt\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_timetbl.tpl");
		echo "</FORM>\n";
		break;
	}

	case "new_group":
	{
		$DB->query("SELECT spec_id, spec_code, spec_name FROM specialities ORDER BY spec_id ASC");
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_group\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_group.tpl");
		echo "</FORM>\n";
		break;
	}

	case "new_user":
	{
		$user_group = $_GET['user_group'];
		$DB->query("SELECT group_id, group_name FROM groups WHERE group_id={$user_group}");
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_user&gr={$user_group}\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_user.tpl");
		echo "</FORM>\n";
		break;
	}

	case "new_test":
	{
		$subj = $_GET['subj'];
		$DB->query("SELECT subject_id, subject_name FROM subjects WHERE subject_id='$subj'");
		$how = $DB->get_num_rows();
		if($how == 0) Show_Message("DB_ERROR_NO_SUBJECTS");
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_test\" method=\"post\" name=\"register_form\" id=\"register_form\">\n";
		require_once("tpls/ae_test.tpl");
		echo "</FORM>\n";
		break;
	}

	case "test_detail":
	{
		$test = $_GET['test'];
		$type = "add";
		echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"reg.php?what=new_detail&test={$test}\" method=\"POST\" name=\"td_FORM\">\n";
		require_once("tpls/ae_testdet.tpl");
		echo "</FORM>\n";
		break;
	}

	case "new_quest":
	{
		$test = $_GET['test'];
		//require_once("inc/insert_ha.php");
		require_once("tpls/ae_q_s1.tpl");
		break;
	}

	case "new_ip":
	{
?>
<DIV align="center">
<FORM onsubmit="return checkedForm(this)" action="reg.php?what=new_ip" method="post" name="register_form" id="register_form">
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="4">
<TR>
<TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_ip']?></TD>
</TR>
<TR>
<TD width="20%" class="row3"><?php echo $lang['IP_addr'];?></TD>
<TD class="row1"><INPUT type="text" name="ip_addr" size="15" maxlength="15"></TD>
</TR>
<TR>
<TD width="20%" class="row3"></TD>
<TD class="row1"><INPUT type="submit" name="submit" class="button" value="<?php echo $lang['reg_button']?>"></TD>
</TR>
<TR>
<TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD>
</TR>
</TABLE>
</FORM>	
</DIV>
<?PHP
break;
	}
}

page_end();
?>