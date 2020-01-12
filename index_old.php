<?php

/**
* @package d-tester
* @subpackage tester subsystem
* @version 1.1 RC1
* @name index page for d-tester tester subsystem
* @author Yuriy V. Bezgachnyuk
* @copyright (c) 2005-2007 Yuriy Bezgachnyuk, IF, Ukraine
* 
* All Rights Reserved
*/

require_once("inc/settings.inc");
require_once("inc/mysql.inc");
require_once("inc/page.inc");
require_once("inc/db_mes.inc");
require_once($PARAM['LANG_SET']);

$action = $_GET['action'];

$DB = new db_driver();
$DB->obj['sql_database'] = $PARAM['DB_DBNAME'];
$DB->obj['sql_host'] = $PARAM['DB_HOST'];
$DB->obj['sql_user'] = $PARAM['DB_USER'];
$DB->obj['sql_pass'] = $PARAM['DB_PASSWORD'];

$DB->connect(); // Connect to MySQL server

$to_page[].= "<br>\n<br>\n<!-- <Main Table> -->\n<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n<tr>";
echo "galaxy d-testser";
// Left table
$to_page[].="\n<!-- <Left table> -->\n<td width=\"150\" valign=\"top\">\n<table width=\"99%\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\">\n";

$left = $DB->query("SELECT * FROM index_page WHERE position='L' AND visible=1 ORDER BY priority ASC");

while ($DB->fetch_row($left)) {
	$to_page[].="\n<!-- Block: {$DB->record_row[1]} -->\n<tr><td align=\"center\">\n";
	require_once($INDEX['BASE_TPL_DIR'].$DB->record_row[4]);
	$to_page[].="</td></tr>\n";
}
$DB->free_result( $left );
$to_page[].="\n</table>\n</td>\n<!-- </Left table> -->\n";

// Center Table
$to_page[].="\n<!-- <Center table> -->\n<td width=\"100%\" align=\"center\" valign=\"top\">\n<table width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\">\n";
if (!isset($action)) $action = "main";
switch ($action) {
	case "about": {
		$to_page[].="\n<!-- Block: d-tester group -->\n<tr><td align=\"center\">\n";
		require_once($INDEX['BASE_TPL_DIR']."/about.tpl");
		$to_page[].="</td></tr>\n";
		break;
	}

	case "main":
	default: {
		$_SERVER['REQUEST_URI'] = "/";
		$center = $DB->query("SELECT * FROM index_page WHERE position='C' AND visible=1 ORDER BY priority ASC");
		while ($DB->fetch_row($center)) {
			$to_page[].="\n<!-- Block: {$DB->record_row[1]} -->\n<tr><td align=\"center\">\n";
			require_once($INDEX['BASE_TPL_DIR'].$DB->record_row[4]);
			$to_page[].="</td></tr>\n";
		}
		$DB->free_result( $center );
		break;
	}
}
$to_page[].="\n</table></td>\n<!-- </Center table> -->\n";

// Right table
$to_page[].="\n<!-- <Right table> -->\n<td width=\"150\" valign=\"top\">\n<table width=\"99%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">\n";
$right = $DB->query("SELECT * FROM index_page WHERE position='R' AND visible=1 ORDER BY priority ASC");
while ($DB->fetch_row($right)) {
	$to_page[].="\n<!-- Block: {$DB->record_row[1]} -->\n<tr><td align=\"center\">\n";
	require_once($INDEX['BASE_TPL_DIR'].$DB->record_row[4]);
	$to_page[].="</td></tr>\n";
}
$DB->free_result($right);
$to_page[].="\n</table>\n</td>\n<!-- </Right table> -->\n";

$to_page[].="\n</tr></table>\n<!-- </Main table> -->";

$index_page = new page();
$index_page->SetContent($to_page);
$index_page->SetTitle($lang['tester_title']);
$index_page->SetCopyright($lang['aut_copy']);
$index_page->DisplayPage();

?>