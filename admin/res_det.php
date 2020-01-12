<?PHP

// res_det.php - сценарій для видачі детальної інформації про проходження тестування

require_once("req.inc");
require_once("inc/timer.inc");

$sess_id = $_GET['sess_id'];

function extract_aq($aq)
{
	$sep = "||"; // Розділювач
	$res = explode($sep,$aq);
	return $res;
}

page_begin($lang['capt_res_det']);

$DB->query("SELECT users.user_name, tests.test_name, DATE_FORMAT(session_results.date_ses,'%d-%m-%Y'), session_results.start_time,
				   session_results.time_ses, session_results.result, session_results.questions, session_results.true_answers
			FROM users, tests, session_results
			WHERE session_results.sess_id=$sess_id AND users.user_id=session_results.user_id
			AND tests.test_id=session_results.test_id");
$row = $DB->fetch_row();

?>

<TABLE align="center" width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR>
<TH align="center" class="maintitle" colspan="2"><?php echo $lang['capt_res_det']?></TH>
</TR>
<TR>
<TH width="25%" align="left" class="row2"><?php echo $lang['capt_name']?></TH>
<TH width="75%" align="left" class="row2"><?php echo $lang['capt_value']?></TH>
</TR>

<TR>
<TD align="left" class="row1"><b><?php echo $lang['user_name']?></b></TD>
<TD align="left" class="row1"><?php echo $row[0]?></TD>
</TR>
<TR>
<TD align="left" class="row1"><b><?php echo $lang['test_name']?></b></TD>
<TD align="left" class="row1"><?php echo $row[1]?></TD>
</TR>
<TR>
<TD align="left" class="row1"><b><?php echo $lang['capt_date']?></b></TD>
<TD align="left" class="row1"><?php echo $row[2]?></TD>
</TR>
<TR>
<TD align="left" class="row1"><B><?php echo $lang['capt_time']?>(<?php echo $lang['capt_enter']?>)</B></TD>
<TD align="left" class="row1"><?php echo $row[3]?></TD>
</TR>
<TR>
<TD align="left" class="row1"><B><?php echo $lang['test_time']?></B></TD>
<?php $test_time=TimeToStr(sub_time($row[4],$row[3]))?>
<TD align="left" class="row1"><?php echo $test_time?></TD>
</TR>
<TR>
<TD align="left" class="row1"><B><?php echo $lang['capt_mark']?></B></TD>
<TD align="left" class="row1"><?php echo $row[5]?></TD>
</TR>
</TABLE>

<TABLE align="center" width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR>
<TH align="center" class="maintitle" colspan="3"><?php echo $lang['capt_quests']?></TH>
</TR>

<TR>
<TH width="8%" align="center" class="row2"><?php echo $lang['capt_num']?></TH>
<TH width="77%" align="center" class="row2"><?php echo $lang['tasks_href']?></TH>
<TH width="15%" align="center" class="row2"><?php echo $lang['ans_id']?></TH>
</TR>
<?PHP

$questions = extract_aq($row[6]); // ID завдань
$answers = extract_aq($row[7]); // специфікатор варіанту true/false
$DB->free_result();

$ut = $DB->query("SELECT session_results.user_id, session_results.test_id, users.user_group FROM session_results, users WHERE sess_id=$sess_id AND users.user_id=session_results.user_id");
$row = $DB->fetch_row($ut);
$user_id = $DB->record_row[0];
$test_id = $DB->record_row[1];
$group_id = $DB->record_row[2];
$DB->free_result($ut);

for($i = 1;$i < sizeof($questions);$i++) {
	$DB->query("SELECT q_text FROM questions WHERE question_id='$questions[$i]'");
	$DB->fetch_row();
	echo "<TR>\n";
	$quest_body = stripslashes(stripslashes($DB->record_row[0]));
	echo "<TD align=\"center\" class=\"row1\"><b>$i</b> ($questions[$i])</TD>";
	echo "<TD align=\"left\" class=\"row1\"><a href=\"task_info.php?id=$questions[$i]\" target=\"_blank\">$quest_body</a></TD>";

	if($answers[$i] == "1") {
		$ans_type = $lang['true_ans'];
	}

	if($answers[$i] == "0") {
		$ans_type = $lang['false_ans'];
	}
	echo "<TD align=\"center\" class=\"row1\">$ans_type</TD>";
	echo "</TR>\n";
}


echo "<TR>\n<TD colspan=\"3\" align=\"center\" class=\"darkrow2\">\n<INPUT type=\"button\" class=\"button\" value=\"{$lang['diagram_capt']}\" onclick=\"show_dia()\">\n<INPUT type=\"button\" class=\"button\" value=\"{$lang['print_ver_button']}\" onclick=\"print_rd()\">\n<INPUT type=\"button\" class=\"button\" value=\"{$lang['stable_wizard']}\" onclick=\"show_sttb()\">\n</TD>\n</TR>\n</TABLE>\n";

echo "<SCRIPT Language=\"JavaScript\">\nfunction show_dia() {\n";
echo "\twindow.open(\"show_dia.php?dia_type=2&sess_id={$sess_id}\",\"mainFrame\");\n}\n";
echo "function print_rd() {\n\twindow.open(\"pres_det.php?sess_id={$sess_id}\",\"newWindow\");\n}\n";
echo "function show_sttb() {\n\twindow.open(\"show_dia.php?dia_type=4&group_id={$group_id}&test_id={$test_id}&user_id={$user_id}\",\"newWindow\");\n}\n";
echo "</SCRIPT>\n</DIV>\n";

page_end();

?>