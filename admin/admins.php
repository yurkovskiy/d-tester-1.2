<?PHP

// admins.php - Root Manager Unit
// (c) 2006 by Yuriy Bezgachnyuk, IF, Ukraine 05.06.2006 08:57 (GMT+02.00)

require_once("req.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

page_begin("");

$DB->query("SELECT admin_id, admin_name, real_name, priv 
	    FROM admins 
	    WHERE priv = 2 
	    ORDER BY admin_id ASC");

echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH colspan=\"6\" align=\"center\" class=\"maintitle\">{$lang['capt_group']}ROOTs</TH></TR>\n";
echo "<TR>\n<TH class=\"row3\" align=\"center\" width=\"10%\"><B>{$lang['capt_reg_num']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"20%\"><B>{$lang['capt_admin']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"25%\"><B>{$lang['user_name']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"10%\"><B>{$lang['adm_level_priv']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"35%\" colspan=\"2\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

$count = 1;
while($row = $DB->fetch_row())
{
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		$row[$i] = stripslashes($row[$i]);
		if($i == 0) {
			$width = "10%";
			echo "<TD class=\"row1\" width=\"{$width}\" align=\"center\">{$count}&nbsp;({$row[$i]})</TD>\n";
			continue;
		}
		if($i == 3) {
		    echo "<TD class=\"row1\" align=\"center\" width=\"10%\">{$row[$i]}</TD>\n";continue;
		}
		echo "<TD class=\"row1\">{$row[$i]}</TD>\n";
	}
	echo "<TD class=\"row1\" align=\"center\"><a href=\"edit.php?action=admins&id={$row[0]}\">{$lang['priv_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=admin&ID={$row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n</TR>\n";
	$count++;
}
unset($count);
echo "<TR><TD width=\"100%\" colspan=\"6\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=new_admin\" title=\"{$lang['add_new_admin']}\">{$lang['add_new_admin']}</a></DIV>\n";

page_end();

?>