<?php

/**
 * @(#)subjects.php 	1.1	09/04/2007
 * 
 * @package d-tester
 * @version 1.1 RC1
 * @subpackage d-tester admin subsystem
 * @name courses manager
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2006-2007 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 11/08/2007 13:26 GMT +02:00
 */

require_once("req.inc");

$how = 1; // default count of subjects

// checking user permissions
if(($_SESSION['adm_priv'] == SUBJECT_MAN)
&&($_SESSION['SB_READ'] == "N")) {
	header("Location: main.php");
}

// create short global variable
$div = $PARAM['MAX_SUBJECTS_IN_PAGE'];

// get page number (part of data rows)
if (isset($_GET['range'])) {
	$range = $_GET['range'];
}

// hom much records in table
if(($_SESSION['adm_priv'] == ROOT)
|| ($_SESSION['adm_priv'] == ADMIN)) {
	$query_count = "SELECT COUNT(*) FROM subjects";
	$DB->query($query_count);
	$DB->fetch_row();
	$how = $DB->record_row[0];
}

if(isset($_SESSION['SUBJ_ID'])
&&($_SESSION['SB_READ']=="Y")) {
	$query = "SELECT subject_id, subject_name FROM subjects WHERE subject_id='".$_SESSION['SUBJ_ID']."'";
}

// how much pages (data parts in web page)
$pages = ceil($how / $div);

if (($pages >= 1) && (!isset($range))) $range = $pages - 1;

if ($pages < 1) $range = 0;
 
$range_s = $div * $range;

// Extract subjects information from database
if(($_SESSION['adm_priv'] == ROOT)
|| ($_SESSION['adm_priv'] == ADMIN)) {
	$query = "SELECT subject_id, subject_name FROM subjects ORDER BY subject_id LIMIT $range_s, $div";
}

page_begin($lang['subjects_href']);

$DB->query($query);

echo "<script src=\"js/pic.js\"></script>\n";

echo "<TABLE width=\"100%\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH class=\"maintitle\" align=\"center\" colspan=\"5\">{$lang['subjects_href']}</TH></TR>\n";

// display pages navigator (switch)
// Newer: added 11.08.2007 10:54 GMT +02:00
echo "<form id=\"pagesNavi\">\n";
echo "<tr><td colspan=\"5\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
echo "{$lang['pages']}&nbsp;<select name=\"pNavi\" onChange=\"open_rURL('{$PHP_SELF}?', document.forms['pagesNavi'].pNavi.value)\">\n";

for($i = 0;$i < $pages;$i++) {
	$href = $i + 1;
	echo "<option value=\"{$i}\">{$href}</option>\n";
}
echo "</select>\n";
echo "</td></tr>\n</form>\n";
echo "<script type=\"text/javascript\">document.forms[0].pNavi.value = {$range};</script>\n";

// Old
/*if ($how > $div) {
echo "<TR><TD colspan=\"5\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
for($i = 0;$i < $pages;$i++) {
$href = $i + 1;
if ($range == $i) {
echo "[{$href}]&nbsp;&nbsp;&nbsp;";
continue;
}
echo "<a href=\"{$PHP_SELF}?range={$i}\"><B>[{$href}]</B></a>&nbsp;&nbsp;&nbsp;\n";
}
echo "</TD></TR>\n";
}*/

echo "<TR>\n<TH width=\"10%\" align=\"center\" class=\"row3\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH width=\"60%\" align=\"left\" class=\"row3\"><B>{$lang['subj_name']}</B></TH>\n";
echo "<TH width=\"30%\" align=\"center\" class=\"row3\" colspan=\"3\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

while($DB->fetch_row()) {
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++) {
		if($i == 0) $align = "center";
		else $align = "left";
		echo "<TD class=\"row1\" align=\"{$align}\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "<TD width=\"10%\" class=\"row1\" align=\"center\"><a href=\"edit.php?action=subject&subj={$DB->record_row[0]}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD width=\"10%\" class=\"row1\" align=\"center\"><a href=\"tests.php?subject={$DB->record_row[0]}\">{$lang['tests_href']}</a></TD>\n";
	echo "<TD width=\"10%\" class=\"row1\" align=\"center\"><a href=\"time_table.php?subject={$DB->record_row[0]}\">{$lang['timetable_href']}</a></TD>\n";
	echo "</TR>\n";
}

echo "<TR><TD width=\"100%\" colspan=\"5\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n";
if(!isset($_SESSION['SUBJ_ID'])) {
	echo "<BR>\n<DIV align=\"center\"><a href=\"add.php?action=new_subj\" title=\"{$lang['add_new_subject']}\">{$lang['add_new_subject']}</a></DIV>\n";
}
page_end();

?>