<?PHP

// show_sess.php - Show user rate results 

require_once("req.inc");

$id = $_GET['id'];

page_begin($lang['user_result']);
$DB->query("SELECT user_name FROM users WHERE user_id='$id'");
$DB->fetch_row();
$user_name = $DB->record_row[0];
?>
<TABLE width="100%" align="center" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="5"><?php echo $lang['user_result']?>: <U><B><?php echo $user_name?></B></U></TD></TR>
<TR>
<TD width="10%" class="row2"><B><?php echo $lang['capt_num']?></B></TD>
<TD width="60%" class="row2"><B><?php echo $lang['capt_test']?></B></TD>
<TD width="10%" class="row2"><B><?php echo $lang['capt_date']?></B></TD>
<TD width="10%" class="row2"><B><?php echo $lang['capt_time']?></B></TD>
<TD width="10%" class="row2"><B><?php echo $lang['capt_mark']?></B></TD>
</TR>
<?php
$DB->query("SELECT tests.test_name, DATE_FORMAT(session_results.date_ses,'%d-%m-%Y'), time_ses, session_results.result
			FROM session_results, tests
			WHERE session_results.user_id='$id'
			AND tests.test_id=session_results.test_id
			ORDER BY session_results.date_ses, session_results.time_ses ASC");
		
$k = 1;
while($DB->fetch_row())
{
	echo "<TR>\n<TD class=\"row1\">{$k}</TD>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		echo "<TD class=\"row1\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "</TR>\n";
	$k++;
}
$DB->free_result();

echo "<TR><TD colspan=\"5\" class=\"darkrow2\"></TD></TR>\n</TABLE>\n";

page_end();

?>