<?php

/**
 * @package d-tester
 * @version 1.1 RC1
 * @subpackage d-tester admin subsystem
 * @name user logs manager
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2006-2007 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date: 05/07/2005 17:27 (GMT+02.00)
 * Last update: 11/08/2007 13:30 GMT +02:00
 */

require_once("req.inc");

page_begin($lang['logs']);

$div = $PARAM['MAX_LOGS_RECORDS_IN_PAGE'];

if (isset($_GET['range'])) {
	$range = $_GET['range'];
}

if(!isset($range)) $range = 0;
$range_s = $div * $range;
$DB->query("SELECT COUNT(*) FROM dt_enter_logs");
$DB->fetch_row();
$how = $DB->record_row[0];
if($how == 0) {
	Show_Message("DB_ERROR_NO_LOGS");
}
$DB->free_result();

$pages = ceil($how/$div);

$DB->query("SELECT dt_enter_logs.id, users.user_name, groups.group_name, tests.test_name, admins.admin_name, 
			dt_enter_logs.remote_ip, DATE_FORMAT(dt_enter_logs.e_date,'%d-%m-%Y'), dt_enter_logs.e_time 
			FROM dt_enter_logs, users, groups, admins, tests 
			WHERE users.user_id=dt_enter_logs.user_id AND groups.group_id=users.user_group 
			AND admins.admin_id=dt_enter_logs.admin_id AND tests.test_id=dt_enter_logs.test_id 
			ORDER BY dt_enter_logs.id ASC LIMIT $range_s,$div"); 
?>

<!-- TABLE STRUCTURE -->
<STYLE type="text/css">
body, td, th, h1, h2 {font-family: Verdana, Arial, Tahoma, sans-serif; font-size: 12px;}
</STYLE>
<TABLE align="center" width="100%" border="0" cellpadding="3" cellspacing="4">
<TR><TD colspan="8" align="center" class="maintitle"><?php echo $lang['logs']?></TD></TR>

<?php

echo "<script src=\"js/pic.js\"></script>\n";

// display pages navigator (switch)
// Newer: added 11.08.2007 10:54 GMT +02:00
echo "<form id=\"pagesNavi\">\n";
echo "<tr><td colspan=\"8\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
echo "{$lang['pages']}&nbsp;<select style=\"font-size: 12px;\" name=\"pNavi\" onChange=\"open_rURL('{$PHP_SELF}?', document.forms['pagesNavi'].pNavi.value)\">\n";

for($i = 0;$i < $pages;$i++) {
	$href = $i + 1;
	echo "<option value=\"{$i}\">{$href}</option>\n";
}
echo "</select>\n";
echo "</td></tr>\n</form>\n";
echo "<script type=\"text/javascript\">document.forms[0].pNavi.value = {$range};</script>\n";

/*if($how > $div)
{
	$pages = ceil($how/$div);
	echo "<TR><TD colspan=\"8\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
	for($i = 0;$i < $pages;$i++)
	{
		$href = $i + 1;
		if($range == $i) {
			echo "[{$href}]&nbsp;&nbsp;&nbsp;";
			continue;
		}
		echo "<a href=\"{$PHP_SELF}?range={$i}\"><B>[{$href}]</B></a>&nbsp;&nbsp;&nbsp;\n";
	}
	echo "</TD></TR>\n";
}*/

?>

<TR>
<TH class="row2" width="8%"><B><?php echo $lang['capt_num']?></B></TH>
<TH align="left" class="row2" width="20%"><B><?php echo $lang['capt_student']?></B></TH>
<TH class="row2" width="8%"><B><?php echo $lang['capt_group']?></B></TH>
<TH class="row2" width="20%"><B><?php echo $lang['capt_test']?></B></TH>
<TH class="row2" width="20%"><B><?php echo $lang['capt_admin']?></B></TH>
<TH class="row2" width="8%"><B><?php echo $lang['IP_addr']?></B></TH>
<TH class="row2" width="8%"><B><?php echo $lang['capt_date']?></B></TH>
<TH class="row2" width="8%"><B><?php echo $lang['capt_time']?>(<?php echo $lang['capt_enter']?>)</B></TH>
</TR>
<?php
while($DB->fetch_row())
{
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++)
	{
		if(($i == 1)
		||($i == 3)) $align = "left";
		else $align = "center";
		echo "<TD align=\"{$align}\" class=\"row1\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "</TR>\n";
}
echo "<TR><TD class=\"darkrow2\" colspan=\"8\">&nbsp;</TD></TR>\n</TABLE>\n";
$DB->free_result();
page_end();

?>