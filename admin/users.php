<?php

// Users.php - Show information about students of the group
// Copyright (c) 2005 by Yuriy Bezgachnyuk, IF, Ukraine

require_once("req.inc");

if($_SESSION['adm_priv'] == SUBJECT_MAN) {
	header("Location: index.php");
	break;
}

page_begin($lang['students_href']);

if (isset($_GET['group'])) $group = $_GET['group'];

if(isset($gr)) $group = $gr;
$DB->query("SELECT group_name FROM groups WHERE group_id='{$group}'");
$DB->fetch_row();
$grp = $DB->record_row[0];
$DB->query("SELECT user_id, user_name FROM users WHERE user_group='{$group}' ORDER BY user_id");

echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH colspan=\"5\" align=\"center\" class=\"maintitle\">{$lang['capt_group']}{$grp}</TH></TR>\n";
echo "<TR>\n<TH class=\"row3\" align=\"center\" width=\"10%\"><B>{$lang['capt_reg_num']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"50%\"><B>{$lang['user_name']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"40%\" colspan=\"3\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

/*if ($DB->get_num_rows() < 1) {
	header("Location: index.php");
}*/

$count = 1;
while($row = $DB->fetch_row()) {
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++) {
		$row[$i] = stripslashes($row[$i]);
		if($i == 0) {
			$width = "10%";
			echo "<TD class=\"row1\" width=\"{$width}\" align=\"center\">{$count}&nbsp;({$row[$i]})</TD>\n";
			continue;
		}
		if($i == 1) $width = "50%";
		if($i == 2) $width = "40%";
		echo "<TD class=\"row1\" width=\"{$width}\">{$row[$i]}</TD>\n";
	}
	echo "<TD class=\"row1\" align=\"center\"><a href=\"edit.php?action=users&id={$row[0]}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"show_sess.php?id={$row[0]}\">{$lang['results_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=users&gr={$group}&ID={$row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n</TR>\n";
	$count++;
}
unset($count);
echo "<TR><TD width=\"100%\" colspan=\"5\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=new_user&user_group={$group}\" title=\"{$lang['add_new_user']}\">{$lang['add_new_user']}</a></DIV>\n";
echo "<br><DIV align=\"center\"><a href=\"\" onClick=\"if (confirm('{$lang['gpass_confirm']}')){window.open('genpass.php?group={$group}','newWindow');return false}else{return false}\" title=\"{$lang['generate_passwords']}\">{$lang['generate_passwords']}</a></DIV>\n";
page_end(); 

?>