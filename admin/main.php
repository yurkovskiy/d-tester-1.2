<?php

require_once("req.inc");

page_begin($lang['system_version']);

$DB->query("SELECT COUNT(group_id) FROM groups");
$DB->fetch_row();
$grp = $DB->record_row[0];

$DB->query("SELECT COUNT(subject_id) FROM subjects");
$DB->fetch_row();
$sub = $DB->record_row[0];

$DB->query("SELECT COUNT(test_id) FROM tests");
$DB->fetch_row();
$tst = $DB->record_row[0];

$DB->query("SELECT COUNT(user_id) FROM users");
$DB->fetch_row();
$usr = $DB->record_row[0];

$DB->query("SELECT COUNT(sess_id) FROM session_results");
$DB->fetch_row();
$ses = $DB->record_row[0];

$DB->query("SELECT COUNT(user_id) FROM active_sess");
$DB->fetch_row();
$a_ses = $DB->record_row[0];

$DB->query("SELECT COUNT(id) FROM dt_enter_logs");
$DB->fetch_row();
$logs = $DB->record_row[0];

$DB->query("SELECT COUNT(question_id) FROM questions");
$DB->fetch_row();
$q_res = $DB->record_row[0];

$DB->query("SELECT VERSION()");
$DB->fetch_row();
$ver = $DB->record_row[0];

?>

<script language="JavaScript">
function fulltime() {
	var time = new Date();
	document.clock.full.value = time.toLocaleString();
	setTimeout('fulltime()',500)
}
</script>
<div align="center">
<table width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<tr><td colspan="2" align="center" class="maintitle"><?php echo $lang['sys_stat_href']?></td></tr>
<tr>
<form name="clock">
<td colspan="2" valign="middle" align="center" class="darkrow2">
<input type="text" size="45" name="full" class="timer">
</td></form>
<script language="JavaScript">fulltime();</script>
</tr>
<tr><td class="row1"><b><?php echo $lang['how_groups'];?></b></td><td class="row1" width="15%"><b><?php echo $grp; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_users'];?></b></td><td class="row1" width="15%"><b><?php echo $usr; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_subjects'];?></b></td><td class="row1" width="15%"><b><?php echo $sub; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_tests'];?></b></td><td class="row1" width="15%"><b><?php echo $tst; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_questions'];?></b></td><td class="row1" width="15%"><b><?php echo $q_res; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_sessions'];?></b></td><td class="row1" width="15%"><b><?php echo $ses; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_active_sess'];?></b></td><td class="row1" width="15%"><b><?php echo $a_ses; ?></b></td></tr>
<tr><td class="row1"><b><?php echo $lang['how_logs'];?></b></td><td class="row1" width="15%"><b><?php echo $logs; ?></b></td></tr>
</table>
<table align="center" width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<tr><td align="center" colspan="2" class="maintitle"><?php echo $lang['general_info']?></td></tr>
<tr><td width="60%" class="row1"><b>System Version:</b></td><td class="row1"><i><b><?php echo $lang['system_version'];?></b></i></td></tr>
<tr><td width="60%" class="row1"><b>PHP Version:</b></td><td class="row1"><b><?php echo phpversion();?></b></td></tr>
<tr><td class="row1"><b>MySQL Version:</b></td><td class="row1"><b><?php echo $ver?></b></td></tr>
<tr><td class="row1"><b>Web Server:</b></td><td class="row1"><b><?php echo $_SERVER['SERVER_SOFTWARE'];?></b></td></tr>
</table>
</div>
<div align="center" class="copyright"><?php echo $lang['aut_copy']?></div>
<?php
page_end();
?>