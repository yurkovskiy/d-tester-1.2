<?PHP

require_once("req.inc");

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
    &&($_SESSION['SB_READ']=="N")) {
	header("Location: main.php");
}

$subject = $_GET['subject'];

page_begin($lang['timetable_href']);

$DB->query("SELECT subject_name FROM subjects WHERE subject_id='$subject'");
$DB->fetch_row();

echo "<TABLE width=\"100%\" border=\"0\" align=\"center\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH width=\"100%\" colspan=\"5\" class=\"maintitle\" align=\"center\">{$lang['timetable_href_for']}&nbsp;{$DB->record_row[0]}</TH></TR>\n";
echo "<TR>\n<TH class=\"row3\" align=\"center\" width=\"7%\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"40%\"><B>{$lang['group_name']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"18%\"><B>{$lang['capt_date']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"35%\" colspan=\"2\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

$DB->query("SELECT time_table.id, groups.group_name, DATE_FORMAT(time_table.event_date,'%d-%m-%Y') 
	    FROM time_table, groups 
	    WHERE time_table.subject_id='$subject' 
	    AND groups.group_id=time_table.group_id
	    ORDER BY time_table.id ASC");

while($DB->fetch_row())
{
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		if($i == 1) $align = "left";
		else $align = "center";
		echo "<TD class=\"row1\" align=\"{$align}\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "<TD class=\"row1\" align=\"center\"><a href=\"edit.php?action=timetable&subj={$subject}&ID={$DB->record_row[0]}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD align=\"center\" class=\"row1\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=timetable&subj={$subject}&ID={$DB->record_row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n";
	echo "</TR>\n";
}
echo "<TR><TD colspan=\"5\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=timetable&subject={$subject}\" title=\"{$lang['add_new_test_detail']}\">{$lang['add_new_test_detail']}</a></DIV>\n";

page_end();

?>