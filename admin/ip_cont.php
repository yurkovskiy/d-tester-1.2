<?php

require_once("req.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

page_begin($lang['IP_href']);

$DB->query("SELECT * FROM admins_ip ORDER BY ip_addr");
echo "<table width=\"100%\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<tr><td align=\"center\" class=\"maintitle\" colspan=\"3\">{$lang['IP_href']}</td></tr>\n";
echo "<tr>\n<th class=\"row3\" width=\"10%\"><b>{$lang['capt_num']}</b></th>\n";
echo "<th class=\"row3\" align=\"left\"><b>{$lang['IP_addr']}</b></th>\n";
echo "<th class=\"row3\" width=\"15%\"><b>{$lang['capt_manage']}</b></th>\n</tr>\n";
$k = 1;
while($DB->fetch_row())
{
	echo "\n<!-- Show {$k} record -->\n<tr>\n<td class=\"row1\" valign=\"middle\" align=\"center\">{$k}</td>\n";
	echo "<td class=\"row1\" valign=\"middle\">{$DB->record_row[0]}</td>\n";
	echo "<td align=\"center\" class=\"row1\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=ad_ips&ID={$DB->record_row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></td>\n</tr>\n";
	$k++;
}
echo "<tr><td class=\"darkrow2\" colspan=\"3\">&nbsp;</td></tr>\n</table>\n<br>\n";
echo "<div align=\"center\"><a href=\"add.php?action=new_ip\" title=\"{$lang['add_new_ip']}\">{$lang['add_new_ip']}</a></div>\n<br>\n";
echo "<div align=\"center\" class=\"copyright\">{$lang['ip_warning']}</div>\n";
$DB->free_result();
page_end();
?>