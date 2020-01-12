<?PHP

// sess.php - Show active sessions information
// (c) 2005 by Yuriy Bezgachnyuk, IF, Ukraine
// this file was added 11.04.2005 21:46 (GMT+02.00)

require_once("req.inc");

$DB->query("SELECT users.user_name, DATE_FORMAT(active_sess.start_date,'%d-%m-%Y'),	active_sess.start_time, active_sess.remote_ip, active_sess.session_id
			FROM users, active_sess 
			WHERE users.user_id=active_sess.user_id ORDER BY active_sess.start_date, active_sess.start_time");
if($DB->get_num_rows() == 0) {
	Show_Message("DB_ERROR_A_SESS");
}

page_begin($lang['SESS_href']);
?>

<!-- TABLE STRUCTURE -->
<TABLE align="center" width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD colspan="6" align="center" class="maintitle"><?php echo $lang['SESS_href']?></TD></TR>
<TR>
<TH class="row2" width="10%"><B><?php echo $lang['capt_num']?></B></TH>
<TH class="row2" width="30%"><B><?php echo $lang['capt_student']?></B></TH>
<TH class="row2" width="15%"><B><?php echo $lang['capt_date']?></B></TH>
<TH class="row2" width="15%"><B><?php echo $lang['capt_time']?>(<?php echo $lang['capt_enter']?>)</B></TH>
<TH class="row2" width="15%"><B><?php echo $lang['IP_addr']?></B></TH>
<TH class="row2" width="15%"><B><?php echo $lang['capt_manage']?></B></TH>
</TR>
<?php
$count = 1;
while($DB->fetch_row())
{
	echo "<TR>\n<TD class=\"row1\" width=\"10%\"><B>{$count}</B></TD>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		if($i == 4) {
			echo "<TD align=\"center\" class=\"row1\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=a_sess&ID={$DB->record_row[$i]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n";
		}
		else {
			echo "<TD class=\"row1\"><B>{$DB->record_row[$i]}</B></TD>";
		}
	}
	$count++;
	echo "</TR>\n";
}
echo "<TR><TD class=\"darkrow2\" colspan=\"6\">&nbsp;</TD></TR>\n</TABLE>\n";
$DB->free_result();
page_end();
?>