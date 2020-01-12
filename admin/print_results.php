<?PHP

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @name results order print version (NUNG UP-10 FORM)
 * @author Yuriy Bezgachnyuk
 * @copyright 2009-2011 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 15/01/2011 19:11 GMT +02:00
 * 
 */

require_once("req.inc");
require_once("inc/ects.inc");
require_once("inc/dia_func.inc");

page_begin($lang['capt_resume']);

$markes = array("відмінно" => 0, "добре" => 0, "задовільно" => 0, "незадовільно" => 0);

$s_markes = array("cannot_pass" => 0, "not_present" => 0);

$test = $_GET['test'];
$group = $_GET['group'];

$cur_date = date("d.m.Y");

$test_rate = get_test_rate($test);

/**
 * Queries
 */

// group name
$DB->query("SELECT group_name FROM groups WHERE group_id = {$group}");
$DB->fetch_row();
$group_name = $DB->record_row[0];
$DB->free_result();

// subject name
$DB->query("SELECT subjects.subject_name, tests.test_type
			FROM subjects, tests
			WHERE tests.test_id = {$test}
			AND subjects.subject_id = tests.test_subject_id
");
$DB->fetch_row();
$subject_name = $DB->record_row[0];
$control_type = $DB->record_row[1];
$DB->free_result();

// Generating study year
$std_year = get_std_year();

if ($control_type == 1) $control_type = "екзамен";
else $control_type = "&nbsp;";

/**
 * Form Structure
 * HEADER
 */
?>

<!-- Header text -->
<!--<div id="pro_form_type"><?php echo $lang['pro_form_type_name']?></div>-->
<div id="pro_uni_name"><?php echo $lang['pro_univer_name']?></div>
<div id="pro_form_name"><?php echo $lang['pro_form_name']?></div>
<!-- /Header text -->

<!-- Header table -->
<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<!-- Row 1 -->
<tr>
<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_faculty_name']?></td>
<td class="pro_header_row_value" width="35%">Автоматизації і комп&#180;ютерних наук</td>
<td class="pro_header_row_title" width="20%"><?php echo $lang['pro_spec_title']?></td>
<td class="pro_header_row_value" width="35%">6.050201 - Системна інженерія</td>
</tr>
</table>
<!-- /Row 1 -->

<!-- Row 2 -->
<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_course_name']?></td>
<td class="pro_header_row_value" width="10%">&nbsp;&nbsp;</td>
<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_group_name']?></td>
<td class="pro_header_row_value" width="15%"><?php echo $group_name?></td>
<td class="pro_header_row_title" width="55%">&nbsp;</td>
</tr>
</table>
<!-- /Row 2 -->

<!-- Row 3 -->
<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_header_row_title" width="12%"><?php echo $lang['pro_discipline_name']?></td>
<td class="pro_header_row_value" width="60%"><?php echo $subject_name?></td>
<td class="pro_header_row_title" width="16%"><?php echo $lang['pro_disp_control_type']?></td>
<td class="pro_header_row_value" width="12%"><?php echo $control_type?></td>
</tr>
</table>
<!-- /Row 3 -->

<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">

<!-- Row 4 -->
<tr>
<td class="pro_header_row_title" width="15%"><?php echo $lang['pro_std_year']?></td>
<td class="pro_header_row_value" width="10%"><?php echo $std_year?></td>

<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_semestr']?></td>
<td class="pro_header_row_value" width="10%">&nbsp;</td>

<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_date']?></td>
<td class="pro_header_row_value" width="10%"><?php echo $cur_date?></td>

<td class="pro_header_row_title" width="35%">&nbsp;</td>

</tr>
<!-- /Row 4 -->

</table>

<!-- Row 5 -->
<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_header_row_title" width="5%"><?php echo $lang['pro_teacher_name']?></td>
<td class="pro_header_row_title" width="12%">&nbsp;</td>
<td class="pro_header_row_title" width="20%">&nbsp;</td>
</tr>
<tr>
<td class="pro_header_row_title" width="5%">&nbsp;</td>
<td class="pro_header_row_" width="12%"><?php echo $lang['pro_teacher_lector']?></td>
<td class="pro_header_row_" width="20%"><?php echo $lang['pro_teacher_lab']?></td>
</tr>
</table>
<!-- /Row 5 -->

<!-- /Header table -->

<!-- Results Table -->
<!-- Header -->
<table id="pro_results_table" cellpadding="0" cellspacing="0" align="center">
<tr>
<td class="pro_results_row" width="3%" rowspan="2"><?php echo $lang['pro_results_num']?></td>
<td class="pro_results_row" width="25%" rowspan="2"><?php echo $lang['pro_results_PIB']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_ob_num']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_current_mark']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_test_mark']?></td>
<td class="pro_results_row" width="32%" colspan="3"><?php echo $lang['pro_results_semestr_mark']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_teacher_sign']?></td>
</tr>
<tr>
<td class="pro_results_row"><?php echo $lang['pro_results_100_balls_scale']?></td>
<td class="pro_results_row"><?php echo $lang['pro_results_ECTS_scale']?></td>
<td class="pro_results_row"><?php echo $lang['pro_results_4_balls_scale']?></td>
</tr>

<tr>
<?php
for ($i = 1;$i <= 9;$i++) {
	echo "<td class=\"pro_results_row\"><b>{$i}</b></td>\n";
}
?>
</tr>
<!-- /Header -->

<?php

// Main query for this order
require_once("inc/res_base.inc");

global $hut;
$group_res = 0;
$k = 1;

while ($row = $DB->fetch_row()) {
	echo "<tr>\n";

	echo "<td class=\"pro_results_row\">{$k}</td>\n";

	echo "<td class=\"pro_results_row_name\">{$row[0]}</td>\n";
	
	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";

	// Rating before exam
	if ($row[2] == 0) $row[2] = "&nbsp;";
	echo "<td class=\"pro_results_row\">{$row[2]}</td>\n";

	// Test result
	$res_quality = round(($row[1] / $test_rate), 2); $res_percent = 100 * $res_quality;
	$row[1] = $res_percent;

	echo "<td class=\"pro_results_row\">{$row[1]}</td>\n";
	
	// Full rating
	// 100 balls scale
	echo "<td class=\"pro_results_row\">{$row[3]}</td>\n";

	// ECTS scale
	foreach ($ECTS_SYMBOLS as $MARK) {
		if (($row[3] >= $ECTS_MIN_VALUES[$MARK])&& ($row[3] <= $ECTS_MAX_VALUES[$MARK])) {
			echo "<td class=\"pro_results_row\">{$MARK}</td>\n";
			echo "<td class=\"pro_results_row\">{$NAT_SCALE[$MARK]}</td>\n";
			$markes[$NAT_SCALE[$MARK]]++;
		}
	}
	
	// Teacher Signature
	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";

	echo "</tr>\n";

	$k++;
}
echo "</table>\n";
echo "<!-- /Results Table -->\n";

?>
<!-- Footer -->
<!-- Left Part -->
<div id="pro_footer_left_row">
<div class="pro_footer_row"><?php $k = $k - 1;echo "{$lang['pro_footer_all']}&nbsp;{$k}&nbsp;&nbsp;,&nbsp;&nbsp;{$lang['pro_from_pe']}";?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_footer_excelence']}&nbsp;&nbsp;&nbsp;{$markes['відмінно']}"?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_footer_good']}&nbsp;&nbsp;&nbsp;{$markes['добре']}"?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_footer_nbad']}&nbsp;&nbsp;&nbsp;{$markes['задовільно']}"?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_footer_bad']}&nbsp;&nbsp;&nbsp;{$markes['незадовільно']}"?></div>
<div class="pro_footer_row">
<?php 
	echo "{$lang['pro_footer_not_present']}&nbsp;&nbsp;&nbsp;{$s_markes['not_present']}&nbsp;&nbsp;&nbsp;";
	echo "{$lang['pro_footer_rate']}&nbsp;_______&nbsp;&nbsp;";
	echo "{$lang['pro_footer_not_rate']}&nbsp;_______&nbsp;";
?>
</div>
<div class="pro_footer_row">
<?php	
	echo "{$lang['pro_footer_cannot_pass']}&nbsp;&nbsp;&nbsp;{$s_markes['cannot_pass']}&nbsp;&nbsp;&nbsp;";
?>	
</div>
</div>
<!-- /Left Row -->

<!-- Right Row -->
<div id="pro_footer_right_row">
<div class="pro_footer_row"><?php echo "{$lang['pro_exam_teacher_name']}____________________________"?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_responsible_person_name']}_________________"?></div>
<div class="pro_footer_row"><?php echo "{$lang['pro_decan_name']}_______________________"?></div>
</div>
<!-- /Right Row -->

<!-- /Footer -->
<?php

//echo "<DIV align=\"center\" class=\"copyright\">{$lang['print_footer']}<BR><b>{$cur_date}</b></DIV>\n";
page_end();
?>