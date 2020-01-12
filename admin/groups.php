<?php

require_once("req.inc");

if($_SESSION['adm_priv'] == SUBJECT_MAN) {
	header("Location: index.php");
	break;
}

page_begin($lang['groups_students_href']);

echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH align=\"center\" class=\"maintitle\" colspan=\"5\"><IMG src=\"styles/atb_members.gif\">{$lang['groups_students_href']}</TH></TR>\n";
echo "<TR>\n<TH class=\"row3\" width=\"10%\" align=\"center\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH class=\"row3\" width=\"55%\" align=\"left\"><B>{$lang['group_name']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" colspan=\"3\" width=\"35%\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

$k = 1;
$DB->query("SELECT group_id, group_name FROM groups ORDER BY group_id");
while($DB->fetch_row())
{
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		if($i == 0) {
			echo "<TD class=\"row1\" align=\"center\">{$k}&nbsp;({$DB->record_row[0]})</TD>\n";
			continue;
		}
		echo "<TD class=\"row1\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "<TD class=\"row1\" align=\"center\"><a href=\"edit.php?action=groups&grp={$DB->record_row[0]}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"users.php?group={$DB->record_row[0]}\">{$lang['students_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=group&ID={$DB->record_row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n</TR>\n";
	$k++;
}
echo "<TR><TD width=\"100%\" colspan=\"5\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=new_group\" title=\"{$lang['add_new_group']}\">{$lang['add_new_group']}</a></DIV>\n<BR>\n";
echo "<DIV align=\"center\" class=\"copyright\">{$lang['grp_warning']}</DIV>\n";
$DB->free_result();
page_end();
?>