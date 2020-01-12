<?php

/**
 * @package d-tester
 * @subpackage tester subsystem
 * @version 1.2 RC1
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2008 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date: 23/03/2005
 * Last update: 07/01/2008 09:48 GMT +02:00
 * 
 * All Rights Reserved
 */

// built with Apache Web Server 1.3.11/PHP 5.2.2, MySQL 5.0.18

$to_page[].="<!-- Login Form TPL -->\n";

if (!$PARAM['VIA_PROXY']) {
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	$DB->query("SELECT * FROM active_sess WHERE remote_ip='$ip_addr'");
	if(($DB->get_num_rows() != 0)) {
		Show_Message("DB_ERROR_ACCESS_SESSION_IP");
	}
}
// end of remote_ip control

$res_group = $DB->query("SELECT groups.group_id, groups.group_name FROM groups, time_table
WHERE time_table.subject_id='$subj' AND CURDATE()<=time_table.event_date
AND groups.group_id=time_table.group_id ORDER BY groups.group_id ASC");
if($DB->get_num_rows() < 1) {
	Show_Message("DB_ERROR_NO_TIME_TABLE_DATA");
}

$res_tests = $DB->query("SELECT test_id, test_name FROM tests WHERE test_subject_id='$subj' AND enabled='1' ORDER BY test_id ASC");
if($DB->get_num_rows() < 1) {
	Show_Message("DB_ERROR_NO_TESTS");
}

$to_page[].="<script src=\"".$PARAM['FJS_FILE']."\"></script>\n";
$to_page[].="<table align=\"center\" width=\"75%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tbl_index_stat\">\n";
$to_page[].="<form onsubmit=\"return checkedForm(this)\" action=\"start.php\" name=\"aut_form\" method=\"POST\">\n";
$to_page[].="<tr><td colspan=\"2\" align=\"center\" class=\"NavItem\">{$lang['entry_capt']}</td></tr>\n";
$to_page[].="<tr>\n<td width=\"40%\" class=\"row2\">{$lang['user_name']}</td>\n";
$to_page[].="<td width=\"60%\" class=\"row1\"><input class=\"textinputb\" type=\"text\" name=\"us_name\" size=\"35\" maxlength=\"35\"></td>\n</tr>\n";
$to_page[].="<tr>\n<td class=\"row2\">{$lang['pass_word']}</td>\n";
$to_page[].="<td class=\"row1\"><input class=\"textinputb\" type=\"password\" name=\"us_pass\" size=\"20\" maxlength=\"20\"></td>\n</tr>\n";
$to_page[].="<tr>\n<td class=\"row2\">{$lang['group_name']}</td>\n";
$to_page[].="<td class=\"row1\">\n";
$to_page[].="<select class=\"textinputb\" name=\"group_id\">\n";

while($DB->fetch_row($res_group)) {
	$to_page[].="<option value=\"{$DB->record_row[0]}\">{$DB->record_row[1]}</option>\n";
}
$to_page[].="</select>\n</td>\n</tr>\n";

if($PARAM['ADMIN_PASS_CHECK'] == 1) {
	$to_page[].="<tr>\n<td class=\"darkrow2\" align=\"left\" >{$lang['admin_name']}</td>\n";
	$to_page[].="<td class=\"row1\" align=\"left\"><input class=\"textinputb\" type=\"text\" name=\"admin_name\" size=\"35\" maxlength=\"35\"></td>\n</tr>\n";
	$to_page[].="<tr>\n<td class=\"darkrow2\" align=\"left\" >{$lang['admin_quote']}</td>\n";
	$to_page[].="<td class=\"row1\" align=\"left\"><input class=\"textinputb\" type=\"password\" name=\"admin_pass\" size=\"20\" maxlength=\"20\"></td>\n</tr>\n";
}
$to_page[].="<tr><td class=\"darkrow2\" align=\"center\" colspan=\"2\">{$lang['test_name']}</td></tr>\n";
$to_page[].="<tr><td width=\"80%\" class=\"row1\" colspan=\"2\" align=\"center\">\n";
$to_page[].="<select class=\"textinputb\" name=\"test_id\">\n";

while($DB->fetch_row($res_tests)) {
	$to_page[].="<option value=\"{$DB->record_row[0]}\">{$DB->record_row[1]}</option>\n";
}

$to_page[].="</select>\n</td>\n</tr>\n";
$to_page[].="<tr><td align=\"center\" colspan=\"2\" class=\"darkrow2\"><input type=\"submit\" class=\"button\" name=\"start\" value=\"{$lang['enter_button']}\">&nbsp;</td></tr>\n";
$to_page[].="</form>\n</table>\n";
$to_page[].="<!-- End of Login Form TPL -->\n";