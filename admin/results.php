<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.1
 * @name results.php - results order wizard
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2007 Yuriy Bezgachnyuk
 * 
 * Start: 08.01.2005
 * Last Update: 13.05.2006 19:55 GMT +02:00
 * 
 * All rights reserved 
 */

require_once("req.inc");

if (isset($_GET['subject'])) {
	$subject = $_GET['subject'];
}

page_begin($lang['results_href']);

$DB->query("SELECT COUNT(sess_id) FROM session_results");
$DB->fetch_row();
if($DB->record_row[0] == 0) {
	Show_Message("DB_ERROR_NO_SESSIONS_DATA");
}

if(!isset($subject)) {
?>
<TABLE width="100%" align="center" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['res_choose_subject']?></TD></TR>
<TR>
<TH align="center" width="8%" class="row3"><?php echo $lang['capt_num']?></TH>
<TH align="left" width="92%" class="row3"><?php echo $lang['subj_name']?></TH>
</TR>
<?PHP
$dist_res=$DB->query("SELECT DISTINCT tests.test_subject_id
					FROM tests, session_results 
					WHERE tests.test_id = session_results.test_id
					ORDER BY test_subject_id ASC");

while($subj_row = $DB->fetch_row($dist_res))
{
	$query = "SELECT subject_id, subject_name FROM subjects WHERE subject_id=$subj_row[0]";
	if($_SESSION['adm_priv'] == SUBJECT_MAN) {
		if($_SESSION['RES_READ']=="Y") {
			$query = "SELECT subject_id, subject_name FROM subjects WHERE subject_id=$subj_row[0] AND subject_id='".$_SESSION['SUBJ_ID']."'";
		}
		else Show_Message("DB_ERROR_NO_SESSIONS_DATA");
	}
	$subj_res = $DB->query($query);
	while($row = $DB->fetch_row($subj_res))
	{
		echo "<TR>\n";
		for($i = 0;$i < $DB->get_fields_num();$i++)
		{
			if($i == 1) {
				echo "<TD align=\"left\" class=\"row1\"><a href=\"results.php?subject=$row[0]\">$row[1]</a></TD>";
			}
			else
			echo "<TD align=\"center\" class=\"row1\">$row[$i]</TD>";
		}
		echo "</TR>\n";
	}
}

?>
<TR><TD align="center" width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
<BR>
<DIV align="center" class="copyright">
<?php echo $lang['res_subj_tip']?>
</DIV> 
<?php
}

if(isset($subject)) {
	$groups = array();
	$DB->query("SELECT DISTINCT users.user_group
				FROM users, session_results, tests 
				WHERE tests.test_subject_id = $subject AND session_results.test_id = tests.test_id
				AND users.user_id = session_results.user_id");
	$count = 0;
	while($DB->fetch_row()) {
		$groups[$count] = $DB->record_row[0];
		$count++;
	}
	// Show Form
?>
<FORM action="show_results.php" method="POST" name="results">
<TABLE width="100%" align="center" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="4"><?php echo $lang['capt_resume_wizard']?></TD></TR>
<TR>
<TD class="row3" align="left" width="10%"><B><?php echo $lang['capt_group']?> </B></TD>
<TD class="row1" align="center" width="10%">
<SELECT name="group">
<?PHP
$g_query="SELECT group_name, group_id FROM groups WHERE group_id=$groups[0] ";
for($i = 1;$i < sizeof($groups);$i++) {
	$g_query.="OR group_id={$groups[$i]} ";
}
$g_query.="ORDER BY group_id ASC";
unset($groups);
$DB->query($g_query);
while($DB->fetch_row()) {
	echo "<OPTION value=\"{$DB->record_row[1]}\">{$DB->record_row[0]}</OPTION>\n";
}
$DB->free_result();
$DB->query("SELECT DISTINCT tests.test_name, tests.test_id FROM tests, session_results
			WHERE tests.test_subject_id = $subject 
			AND tests.test_id = session_results.test_id 
			ORDER BY test_id ASC");
?>
</SELECT>
</TD>
<TD class="row3" width="10%"><B><?php echo $lang['capt_test']?> </B></TD>
<TD class="row1">
<SELECT name="test">
<?PHP
while($DB->fetch_row()) {
	echo "<OPTION value=\"{$DB->record_row[1]}\">{$DB->record_row[0]}</OPTION>\n";
}
$DB->free_result();
?>
</SELECT>
</TD>
</TR>
</TABLE>
<BR>
<TABLE width="99%" align="center" border="0" cellpadding="3" cellspacing="0" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="6"><?php echo $lang['capt_order_opt']?></TD></TR>

<TR>

<TD class="row3" align="left" valign="top" width="15%">
<FIELDSET class="search">
<LEGEND><STRONG><B><?php echo $lang['capt_order']?></B></STRONG></LEGEND>
<SELECT name="sort">
<OPTION value="0"><?php echo $lang['sort_date']?></OPTION>
<OPTION value="1"><?php echo $lang['sort_rate']?></OPTION>
<OPTION value="2"><?php echo $lang['sort_user']?></OPTION>
</SELECT>
<BR><BR>
</FIELDSET>
</TD>

<TD class="row3" width="25%" valign="top">
<FIELDSET class="search">
<LEGEND><STRONG><B><?php echo $lang['capt_sort_order']?></B></STRONG></LEGEND>
<input type="radio" name="sort_order" id="sort_asc" class="radiobutton" value="asc" checked="checked" />
<label for="sort_asc"><?php echo $lang['lab_asc']?></label></BR>
<input type="radio" name="sort_order" id="sort_desc" class="radiobutton" value="desc" />
<label for="sort_desc"><?php echo $lang['lab_desc']?></label></FIELDSET></TD>

<TD class="row3" align="left" valign="top" width="15%">
<FIELDSET class="search">
<LEGEND><STRONG><B><?php echo $lang['capt_some_attempts']?></B></STRONG></LEGEND>
<SELECT name="avg_enable">
<OPTION value="0"><?php echo $lang['all_sess_res']?></OPTION>
<OPTION value="1"><?php echo $lang['user_avg_res']?></OPTION>
<OPTION value="2"><?php echo $lang['user_max_res']?></OPTION>
<OPTION value="3"><?php echo $lang['user_min_res']?></OPTION>
</SELECT>
<BR><BR>
</FIELDSET>
</TD>

<TD class="row3" width="45%"></TD>
</TR>
<TR><TD class="darkrow2" colspan="6" align="center"><INPUT type="submit" class="button" value="<?php echo $lang['show_res_button']?>"></TD></TR>
</TABLE>
</FORM>
<BR>

<FORM action="show_dia.php" method="POST" name="diagram">
<TABLE width="100%" align="center" border="0" cellpadding="3" cellspacing="0" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="6"><?php echo $lang['diagram_capt']?></TD></TR>
<TR>
<TD align="left" class="row3" width="15%"><b><?php echo $lang['capt_dia_type']?></b></TD>
<TD align="left" class="row3" width="25%">
<SELECT name="dia_type">
<OPTION value="0"><?php echo $lang['dia_g_quality']?></OPTION>
</SELECT>
</TD>

<TD class="row3" width="10%"><B><?php echo $lang['capt_test']?> </B></TD>
<TD class="row1">
<SELECT name="test">
<?PHP
$DB->query("SELECT DISTINCT tests.test_name, tests.test_id 
			FROM tests, session_results
			WHERE tests.test_subject_id = $subject 
			AND tests.test_id = session_results.test_id 
			ORDER BY test_id ASC");

while($DB->fetch_row()) {
	echo "<OPTION value=\"{$DB->record_row[1]}\">{$DB->record_row[0]}</OPTION>\n";
}
$DB->free_result();
?>
</SELECT>
</TD>

</TR>
<TR><TD class="darkrow2" colspan="6" align="center"><INPUT type="submit" class="button" value="<?php echo $lang['show_res_button']?>"></TD></TR>
</TABLE>
</FORM>

<?PHP
}
page_end();
?>