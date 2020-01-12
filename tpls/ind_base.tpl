<?php

$subj = $_GET['subj'];
$range = $_GET['range'];
$to_page[].="<!-- Subjects view page TPL -->\n";
if(!$subj) {
	$cnt_subjects = $DB->query("SELECT COUNT(*) FROM subjects");
	$c_row = $DB->fetch_row($cnt_subjects);
	$how_subjects = $c_row[0];
	$DB->free_result($cnt_subjects);
	$pages = ceil($how_subjects / $PARAM['DIV_SUBJ']);
	
	if((!$range)||($range < 0)||($range > ($pages - 1))) {
		$range = 0;
	}
	$subj_range = $range * $PARAM['DIV_SUBJ'];
	$result = $DB->query("SELECT subject_name, subject_id FROM subjects ORDER BY subject_id ASC LIMIT $subj_range, ".$PARAM['DIV_SUBJ']."");
	$num_subj = $DB->get_num_rows($result);
	if($num_subj < 1) {
		Show_Message("DB_ERROR_NO_SUBJECTS");
	}
	
	$to_page[].="<table align=\"center\" width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tbl_index_stat\">\n";
	$to_page[].="<tr><td align=\"center\" valign=\"top\" class=\"NavItem\" colspan=\"4\">{$lang['reg_subjects']}</td></tr>\n<tr>\n";
	$to_page[].="<th align=\"center\" class=\"titlemedium\" width=\"10%\">{$lang['num']}</th>\n<th align=\"left\" class=\"titlemedium\" width=\"50%\">{$lang['subj_name']}</th>\n<th align=\"center\" class=\"titlemedium\" width=\"20%\">{$lang['how_tests']}</th>\n<th align=\"center\" class=\"titlemedium\" width=\"20%\">{$lang['status']}</th>\n</tr>\n";
	$count = $range * $PARAM['DIV_SUBJ'] + 1;
	while($row = $DB->fetch_row($result)) {
		$cnt_tests = $DB->query("SELECT COUNT(*) FROM tests WHERE test_subject_id='$row[1]'");
		$test_row = $DB->fetch_row($cnt_tests);
		$cnt_tt = $DB->query("SELECT COUNT(*) FROM time_table WHERE time_table.subject_id='$row[1]' AND CURDATE() =time_table.event_date");
		$tt_row = $DB->fetch_row($cnt_tt);
		$to_page[].="<!--  Subject {$row[1]} -->\n<tr>\n<td align=\"center\" class=\"row4\">{$count}</td>\n";
		if($tt_row[0] && $test_row[0]) {
			$to_page[].="<td align=\"left\" class=\"row4\"><a href=\"{$_SERVER['PHP_SELF']}?subj={$row[1]}\" title=\"{$row[0]}\">{$row[0]}</a></td>\n";
			$to_page[].="<td align=\"center\" class=\"row4\">{$test_row[0]}</td>\n";
			$to_page[].="<td align=\"center\" class=\"row4\">{$lang['enable']}</td>\n";
		}
		else {
			$to_page[].="<td align=\"left\" class=\"row4\">{$row[0]}</td>\n";
			$to_page[].="<td align=\"center\" class=\"row4\">{$test_row[0]}</td>\n";
			$to_page[].="<td align=\"center\" class=\"row4\">{$lang['disable']}</td>\n";
		}
		$to_page[].="</tr>\n";
		$count++;
	}
	$to_page[].="<tr>\n<td colspan=\"4\" align=\"center\" class=\"darkrow2\">&nbsp;</td>\n</tr>\n</table>\n";
	
	if($how_subjects > $PARAM['DIV_SUBJ']) {
		$message = $lang['how_subjects'].=" (".$how_subjects.")&nbsp;&nbsp;";
		$to_page[].="\n<!-- Show pages navigator -->\n";
		$to_page[].="<table align=\"center\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" class=\"tbl_nav\">\n";
		$to_page[].="<tr>\n<td align=\"left\" width=\"100%\" class=\"row1\"><b>{$message}<b>";
		for($i = 0;$i < $pages;$i++) {
			$href = $i + 1;
			if($i == $range) {
				$to_page[].="<font color=\"Red\">[{$href}]</font>&nbsp;&nbsp;&nbsp;\n";
			}
			else {
				$to_page[].="<a href=\"{$_SERVER['PHP_SELF']}?action=main&range={$i}\"><b><font color=\"#000\">[{$href}]</font></b></a>&nbsp;&nbsp;&nbsp;\n";
			}
		}
		$to_page[].="</td>\n</tr>\n</table>\n\n";
	}
	$to_page[].="<!-- End of Subjects TPL -->\n";
}


if($subj) {
	// Load Login Form
	require_once("tpls/ind_login.tpl");
}

?>