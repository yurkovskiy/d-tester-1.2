<?PHP

// show_results.php - Сценраій для формування звіту по результатам проведення тестувань

//header("Location:main.php");

require_once("req.inc");
require_once("inc/timer.inc");
require_once("inc/ects.inc");

$test = $_POST['test'];
$group = $_POST['group'];
$sort = $_POST['sort'];
$sort_order = $_POST['sort_order'];
$avg_enable = $_POST['avg_enable'];

page_begin($lang['capt_resume']);
$border = 0;

require_once("inc/res_base.inc");

$ids = array("tdb", "tdc", "tdd", "tde", "tdf", "tfg", "tdh", "tdi");

echo "<script language=\"JavaScript\">\nfunction check(form)\n{\nif (confirm('{$lang['del_confirm']}'))\nreturn true;\nelse return false;\n}\n</script>\n";

echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<form onsubmit=\"return check(this)\" action=\"del.php?what=session\" method=\"post\" name=\"del_q\">\n";
echo "<input type=\"hidden\" name=\"test\" value=\"{$test}\">\n<input type=\"hidden\" name=\"group\" value=\"{$group}\">\n";
echo "<TR><TD align=\"center\" class=\"maintitle\">{$lang['capt_resume']}</TD></TR>\n";
echo "<TR><TD class=\"row3\">{$lang['test_subject']}:&nbsp;<B>{$subj}</B></TD></TR>\n";
echo "<TR><TD class=\"row3\">{$lang['capt_test']}:&nbsp;<B>{$tes}</B></TD></TR>\n";
echo "<TR><TD class=\"row3\">{$lang['test_time']}:&nbsp;&nbsp;<B>{$tes_time}</B>&nbsp;&nbsp;&nbsp;{$lang['test_rate']}&nbsp;<B>{$test_rate}</B></TD></TR>\n";
echo "<TR><TD class=\"row3\">{$lang['capt_group']}:&nbsp;<B>{$grp}</B></TD></TR>\n</TABLE>\n";
echo "<TABLE width=\"100%\" align=\"center\" border=\"{$border}\" class=\"tbl_view_frame\" cellpadding=\"1\" cellspacing=\"4\">\n<TR>\n";
echo "<TH align=\"center\" width=\"8%\" class=\"row4\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH align=\"left\" width=\"15%\" class=\"row4\"><B>{$lang['capt_student']}</B></TH>\n";

echo "<TH align=\"center\" width=\"10%\" class=\"row4\"><B>{$lang['capt_mark']}</B></TH>\n";
echo "<TH align=\"center\" width=\"10%\" class=\"row4\"><B>{$lang['capt_omark']}</B></TH>\n";
echo "<TH align=\"center\" width=\"10%\" class=\"row4\"><B>{$lang['capt_full']}</B></TH>\n";

echo "<TH align=\"center\" width=\"10%\" class=\"row4\"><B>{$lang['capt_date']}</B></TH>\n";
echo "<TH align=\"center\" width=\"10%\" class=\"row4\"><B>{$lang['test_time']}</B></TH>\n";
echo "<TH align=\"center\" width=\"8%\" class=\"row4\"><B>{$lang['capt_time']}</B></TH>\n";
echo "<TH align=\"center\" width=\"5%\" class=\"row4\"><B>{$lang['capt_ID']}</B></TH>\n";
echo "<TH align=\"center\" width=\"14%\" class=\"row4\" colspan=\"2\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

global $hut;

$group_res = 0;
$k = 1;

while($row = $DB->fetch_row()) {
	$id_c = $row[7];
	echo "<TR>";
	echo "<TD class=\"row1\" align=\"center\" id=\"tda[{$id_c}]\">{$k}</TD>\n";
	for($i = 0;$i < $DB->get_fields_num();$i++) {
		if ($i == 0) {
			$align = "left";
		}
		if ($i != 0) {
			$align="center";
		}

		if ($i == 1) {
			$group_res += $row[$i];
			$res_quality = round(($row[1] / $test_rate), 2); $res_percent = 100 * $res_quality;
			$row[$i].="&nbsp;(".$res_percent."%)";

		}
		/*if ($i == 2) {
		$res_quality = round(($row[1] / $test_rate), 2); $res_percent = 100 * $res_quality;
		echo "<TD class=\"row1\" align=\"center\" id=\"tdq[{$row[5]}]\">{$res_percent}% </TD>\n";
		}*/

		if ($i == 3) {
			foreach ($ECTS_SYMBOLS as $MARK) {

				if (($row[$i] >= $ECTS_MIN_VALUES[$MARK]) && ($row[$i] <= $ECTS_MAX_VALUES[$MARK])) {

					$row[$i].="&nbsp;(&nbsp;<b>".$MARK."</b>&nbsp;)";
					break;
				}
			}
		}

		if($i == 5) {
			$row[$i] = TimeToStr(sub_time($row[$i + 1],$row[$i]));
		}
		$td_id = $ids[$i]."[".$id_c."]";
		echo "<TD class=\"row1\" align=\"{$align}\" id=\"{$td_id}\">{$row[$i]}</TD>\n";

	}

	echo "<TD class=\"row1\" align=\"center\" id=\"tdj[{$id_c}]\"><a href=\"res_det.php?sess_id={$id_c}\">{$lang['res_details_href']}</a></TD>\n";
	if(($_SESSION['adm_priv'] != SUBJECT_MAN)
	||(($_SESSION['adm_priv'] == SUBJECT_MAN)
	&&($_SESSION['RES_DELETE'] == "Y"))) {
		echo "<TD class=\"row1\" align=\"center\" id=\"tdk[{$id_c}]\"><input type=\"checkbox\" name=\"sess[{$id_c}]\" value=\"{$id_c}\" onClick=\"isChecked(this)\" onmouseover=\"mCng(this)\" onmouseout=\"mtCng(this)\"></TD>\n";
	}
	else {
		echo "<TD class=\"row1\" align=\"center\">{$lang['del_button']}</a></TD>\n";
	}
	$k++;
	$hut = $k;
	echo "</TR>\n";
}
?>
<script type="text/javascript">

var ids = ["tda", "tdb", "tdc", "tdd", "tde", "tdf", "tfg", "tdh", "tdi", "tdj", "tdk"];
// isChecked changer
function isChecked(cb) {
	if (cb.checked) {
		for (var i = 0;i < ids.length;i++) {
			var temp_td = ids[i] + "[" + cb.value + "]";
			document.getElementById(temp_td).className = 'yrow';
		}
	}

	else {
		for (var i = 0;i < ids.length;i++) {
			var temp_td = ids[i] + "[" + cb.value + "]";
			document.getElementById(temp_td).className = 'row1';
		}
	}
}

// onmouseover
function mCng(cb) {
	if (!cb.checked) {
		for (var i = 0;i < ids.length;i++) {
			var temp_td = ids[i] + "[" + cb.value + "]";
			document.getElementById(temp_td).className = 'mrow';
		}
	}
}

// onmouseout
function mtCng(cb) {
	if (!cb.checked) {
		for (var i = 0;i < ids.length;i++) {
			var temp_td = ids[i] + "[" + cb.value + "]";
			document.getElementById(temp_td).className = 'row1';
		}
	}
}

</script>
<?php
$DB->free_result();
$hut = $hut - 1;
if($hut < 0) { $hut = 0; $avg_res = 0; $avg_q = 0;}
if($hut != 0) {$avg_res = round($group_res / $hut, 3); $avg_q = 100 * round($avg_res / $test_rate, 3);};

echo "<tr><td colspan=\"9\" class=\"row2\">&nbsp;</td><td colspan=\"2\" class=\"row2\" align=\"center\" valign=\"middle\"><input type=\"submit\" name=\"del\" class=\"button\" value=\"{$lang['del_button']}\"></td></tr>\n";
echo "</form></TABLE>\n";
echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"1\" cellspacing=\"4\">\n";
echo "<TR><TH colspan=\"4\" align=\"center\" class=\"darkrow2\">{$lang['capt_common_res']}</TH></TR>\n";
echo "<TR><TD align=\"left\" width=\"40%\" class=\"row3\">{$lang['avg_test_result']}</TD>\n";
echo "<TD align=\"left\" width=\"25%\" class=\"row1\"><B>{$avg_res}&nbsp;({$avg_q}%)</B></TD>\n";
echo "<TD align=\"left\" width=\"30%\" class=\"row3\">{$lang['how_users_tested']}</TD>\n";
echo "<TD align=\"left\" width=\"5%\" class=\"row1\"><B>{$hut}</B></TD>\n</TR>\n</TABLE>\n";
echo "<DIV align=\"center\" class=\"darkrow2\">\n";
if($hut != 0) {
	echo "<input type=\"button\" class=\"button\" value=\"{$lang['print_ver_button']}\" onclick=\"print_result()\">\n
	<input type=\"button\" class=\"button\" value=\"{$lang['print_ver_button_up_10']}\" onclick=\"print_result_up10()\">
	<input type=\"button\" class=\"button\" value=\"{$lang['diagram_capt']}\" onclick=\"show_dia()\">\n
	<input type=\"button\" class=\"button\" value=\"{$lang['stable_wizard']}\" onclick=\"show_stable()\">\n";
	echo "<SCRIPT language=\"JavaScript\">\n";
	echo "function print_result() {\n";
	echo "\twindow.open(\"print_results.php?group={$group}&test={$test}&sort={$sort}&sort_order={$sort_order}&avg_enable={$avg_enable}\",\"newWindow\");\n}\n";
	echo "function print_result_up10() {\n";
	echo "\twindow.open(\"print_results_up10.php?group={$group}&test={$test}&sort={$sort}&sort_order={$sort_order}&avg_enable={$avg_enable}\",\"newWindow\");\n}\n";
	echo "function show_dia() {\n";
	echo "\twindow.open(\"show_dia.php?dia_type=1&group={$group}&test={$test}&sort={$sort}&sort_order={$sort_order}\",\"mainFrame\");\n}\n";
	echo "\tfunction show_stable() {\n";
	echo "window.open(\"show_dia.php?dia_type=5&group={$group}&test={$test}\",\"newWindow\");\n}\n";
	echo "</SCRIPT>\n</DIV>\n";
}

echo "{$lang['res_group_warn']}";

page_end();
exit;
?>