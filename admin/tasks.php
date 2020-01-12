<?PHP

/**
 * @(#)tasks.php 	1.1	09/04/2007
 * 
 * @package d-tester
 * @version 1.1 RC1
 * @subpackage d-tester admin subsystem
 * @name tasks manager
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2006-2007 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 11/08/2007 13:30 GMT +02:00
 */

require_once("req.inc");

// create short global variable
$div = $PARAM['MAX_QUESTIONS_IN_PAGE'];

if (isset($_GET['range'])) $range = $_GET['range'];

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
&&($_SESSION['SB_READ'] == "N")) {
	header("Location: main.php");
}

if (isset($_GET['tst'])) $tst = $_GET['tst'];
if (isset($_GET['ts'])) $ts = $_GET['ts'];

if (isset($ts)) $tst = $ts;

if($_SESSION['adm_priv'] == SUBJECT_MAN) {
	$DB->query("SELECT test_subject_id FROM tests WHERE test_id='".$tst."' AND test_subject_id='".$_SESSION['SUBJ_ID']."'");
	$DB->fetch_row();
	if($DB->record_row[0] != $_SESSION['SUBJ_ID']) {
		header("Location: main.php");
		exit;
	}
	$DB->free_result();
}

page_begin($lang['capt_tasks']);

$DB->query("SELECT test_name, how_tasks FROM tests WHERE test_id='$tst'");
$DB->fetch_row();
$tn = $DB->record_row[0];
$how_tasks_must = $DB->record_row[1];
$DB->free_result();
$DB->query("SELECT COUNT(question_id) FROM questions WHERE q_test_id='$tst'");
$DB->fetch_row();
$how_tasks_present = $DB->record_row[0];
$DB->free_result();

if (!isset($how_tasks_present)) $how_tasks_present = 0;

$pages = ceil($how_tasks_present / $div);

if (($pages >= 1) && (!isset($range))) $range = $pages - 1;

if ($pages < 1)	$range = 0;

$range_s = $div * $range;
//echo "{$pages}&nbsp;&nbsp;{$range}";
?>
<TABLE width="100%" align="center" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD class="maintitle" align="center" colspan="6"><B><?php echo $lang['capt_tasks'],":";?> </B><?php echo $tn ?></TD></TR>

<?php

echo "<script src=\"js/pic.js\"></script>\n";

// display pages navigator (switch)
echo "<form id=\"pagesNavi\">\n";
echo "<tr><td colspan=\"6\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how_tasks_present}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
echo "{$lang['pages']}&nbsp;<select name=\"pNavi\" onChange=\"open_rURL('{$PHP_SELF}?ts={$tst}&', document.forms['pagesNavi'].pNavi.value)\">\n";

for($i = 0;$i < $pages;$i++) {
	$href = $i + 1;
	echo "<option value=\"{$i}\">{$href}</option>\n";
}
echo "</select>\n";
echo "</td></tr>\n</form>\n";
echo "<script type=\"text/javascript\">document.forms[0].pNavi.value = {$range};</script>\n";

/*if($how_tasks_present > $div)
{
echo "<TR><TD colspan=\"6\" align=\"left\" class=\"row4\">{$lang['records']}&nbsp;{$how_tasks_present}&nbsp;($pages)&nbsp;&nbsp;&nbsp;&nbsp;\n";
for($i = 0;$i < $pages;$i++)
{
$href = $i + 1;
if($range == $i) {
echo "[{$href}]&nbsp;&nbsp;&nbsp;";
continue;
}
echo "<a href=\"{$PHP_SELF}?ts={$tst}&range={$i}\"><B>[{$href}]</B></a>&nbsp;&nbsp;&nbsp;\n";
}
echo "</TD></TR>\n";
}*/
?>

<TR><TD class="row4" align="center" colspan="6"><B><?php printf($lang['tasks_contains'],$how_tasks_present,$how_tasks_must)?></B></TD></TR>
<?php echo "<TR><TD class=\"row1\" align=\"center\" colspan=\"6\"><a href=\"add.php?action=new_quest&test={$tst}\" title=\"{$lang['add_new_task']}\">{$lang['add_new_task']}</a></TD></TR>\n";?>
<TR><TH class="row3" width="5%"><B><?php echo $lang['capt_num'];?></B></TH>
<TH class="row3" width="8%"><B><?php echo $lang['capt_reg_num'];?></B></TH>
<TH class="row3" width="5%"><B>G</B></TH>
<TH class="row3" width="62%" align="left"><B><?php echo $lang['tasks_href'];?></B></TH>
<TH class="row3" width="20%" colspan="2"><B><?php echo $lang['capt_manage'];?></B></TH></TR>
<?php

$k = 1 + $range_s;

$query = "SELECT question_id, q_level, q_text FROM questions WHERE q_test_id='$tst' ORDER BY q_level, question_id ASC ";

$query.="LIMIT $range_s, $div";

$DB->query($query);
while ($row = $DB->fetch_row()) {
	echo "\n<!-- Show question #{$k} -->\n<TR>\n<TD class=\"row1\" align=\"center\">{$k}</TD>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++) {
		$row[$i] = stripslashes(stripslashes($row[$i]));

		if($i == 2) {
			echo "<TD class=\"row1\" align=\"left\"><a href=\"task_info.php?id={$row[0]}\">{$row[$i]}</a></TD>\n";
		}

		else {
			echo "<TD class=\"row1\" align=\"center\">{$row[$i]}</TD>\n";
		}
	}
	$k++;
	$qid = $row[0];
	echo "<TD class=\"row1\" align=\"center\"><a href=\"edit.php?action=quest&quest={$qid}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=quest&ts={$tst}&ID={$qid}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n</TR>\n";
}
echo "<TR><TD colspan=\"6\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=new_quest&test={$tst}\" title=\"{$lang['add_new_task']}\">{$lang['add_new_task']}</a></DIV>\n";
echo "<BR><DIV align=\"center\" class=\"copyright\">{$lang['task_warning']}</DIV>\n";
page_end();

?>