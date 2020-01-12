<?php

require_once("../inc/header.inc");
require_once("inc/settings.inc");
require_once($PARAM['HELP_FILE']);

page_begin("");

if (isset($_GET['topic'])) $topic = $_GET['topic'];
if (!isset($topic)) $topic = "";
?>
<style type="text/css">
a:link, a:visited, a:active { text-decoration: underline; color: #0000ff }
a:hover { color: #465584; text-decoration:underline }
.tablefill   { border:2px solid #345487;background-color:#ffffff;padding:6px;  }
.tbl_view_frame {
font-size: 16px; color: black;
}
</style>
<?php
switch ($topic) {
	case "0": {
		echo "<table width=\"100%\" height=\"85%\" align=\"center\"><tr><td valign=\"middle\">\n";
		echo "<table width=\"50%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"tablefill\">\n";
		echo "<tr><td align=\"center\"><img src=\"./images/logo.jpg\" align=\"middle\" alt=\"tester_logo\"></td></tr>\n";
		echo "<tr><td align=\"center\" class=\"tbl_view_frame\">{$hlp_lang['main_title']}<br>{$hlp_lang['tester_title']}<br>\n";
		echo "<img align=\"middle\" src=\"./images/ukraine.gif\"><font color=\"red\"> (c) 2005-2012 d-tester Group, IF, Ukraine<br>\n
			This product are licensed by Yuriy Bezgachnyuk<br><a href=\"info.html\"><b>d-tester Credits</b></a></font>\n";
		echo "</td></tr>\n</table>\n</td></tr>\n</table>\n";
		page_end();
		exit;
		break;
	}

	case "1": {
		echo "<table width=\"100%\" height=\"85%\" align=\"center\">\n<tr><td valign=\"middle\">\n";
		echo "<table width=\"50%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" class=\"tablefill\">\n";
		echo "<tr><td align=\"center\"><img src=\"./images/logo.jpg\" align=\"middle\" alt=\"tester_logo\"></td></tr>\n";
		echo "<tr><td align=\"center\" class=\"tbl_view_frame\">{$hlp_lang['main_title']}<br>{$hlp_lang['tester_title']}<br>\n";
		echo "<img align=\"middle\" src=\"./images/ukraine.gif\"><font color=\"red\"> (c) 2005-2012 d-tester Group, IF, Ukraine<br>\n";
		echo "<a href=\"{$hlp_lang['doc_file']}\" title=\"d-tester User Manual\">{$hlp_lang['themes'][1]}</a>\n";
		echo "</td></tr>\n</table>\n</td></tr>\n</table>\n";
		page_end();
		exit;
		break;
	}

	default: {
		echo "<table align=\"center\" width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"4\" class=\"tbl_view_frame\">\n";
		echo "<tr><td align=\"center\" class=\"maintitle\">{$hlp_lang['title']}</td></tr>\n";
		for($i = 0;$i < sizeof($hlp_lang['themes']);$i++) {
			echo "<tr><td class=\"row1\"><li><a href=\"help.php?topic={$i}\">{$hlp_lang['themes'][$i]}</a></li></td></tr>\n";
			echo "<tr><td class=\"row1\"><b>{$hlp_lang['themes_m'][$i]}</b></td></tr>\n";
		}
		echo "</table>\n";
		page_end();
		break;
	}
}

?>