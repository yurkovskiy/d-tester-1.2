<?PHP

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @name results order print version (NUNG UP-10 FORM)
 * @author Yuriy Bezgachnyuk
 * @copyright 2009-2012 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 19/06/2012 08:24 GMT +02:00
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

// group name, spec_name, faculty_name
$DB->query("SELECT groups.group_name, specialities.spec_code, specialities.spec_name, faculty.f_name 
			FROM groups, specialities, faculty 
			WHERE groups.group_id = {$group}
			AND specialities.spec_id = groups.spec_id
			AND faculty.f_id = specialities.f_id");
$DB->fetch_row();
$group_name = $DB->record_row[0];
$spec_name = $DB->record_row[1]." - ".$DB->record_row[2];
$f_name = $DB->record_row[3];
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
<div id="pro_form_name"><?php echo "{$lang['pro_form_name']}&nbsp;<u>&nbsp;&nbsp;{$cur_date}&nbsp;&nbsp;</u>"?></div>
<!-- /Header text -->

<table class="pro_header_table" cellpadding="1" cellspacing="1" align="center">

<!-- Row 4 -->
<tr>
<td class="pro_header_row_title" width="22%"><?php echo $lang['pro_std_year']?></td>
<td class="pro_header_row_value" width="8%"><?php echo $std_year?></td>

<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_semestr']?></td>
<td class="pro_header_row_value" width="5%">&nbsp;</td>

<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_faculty_name']?></td>
<td class="pro_header_row_value" width="45%"><?php echo $f_name?></td>

<!--<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_date']?></td>-->
<!--<td class="pro_header_row_value" width="10%"><?php echo $cur_date?></td>-->

<!--<td class="pro_header_row_title" width="35%">&nbsp;</td>-->

</tr>
<!-- /Row 4 -->

</table>



<!-- Header table -->
<table class="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<!-- Row 1 -->
<tr>
<td class="pro_header_row_title" width="20%"><?php echo $lang['pro_spec_title']?></td>
<td class="pro_header_row_value" width="35%"><?php echo $spec_name?></td>
</tr>
</table>
<!-- /Row 1 -->

<!-- Row 2 -->
<table class="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_header_row_title" width="5%"><?php echo $lang['pro_course_name']?></td>
<td class="pro_header_row_value" width="5%">&nbsp;&nbsp;&nbsp;</td>
<td class="pro_header_row_title" width="5%"><?php echo $lang['pro_group_name']?></td>
<td class="pro_header_row_value" width="10%"><?php echo $group_name?></td>
<td class="pro_header_row_title" width="10%"><?php echo $lang['pro_discipline_name']?></td>
<td class="pro_header_row_value" width="65%"><?php echo $subject_name?></td>
</tr>
</table>
<!-- /Row 2 -->

<!-- Row 3 -->
<table class="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<!--<td class="pro_header_row_title" width="16%"><?php echo $lang['pro_disp_control_type']?></td>
<td class="pro_header_row_value" width="12%"><?php echo $control_type?></td>-->
<td width="55%" class="pro_header_row_value">&nbsp;</td>
<td width="45%" class="pro_header_row_title"><?php echo "{$lang['pro_date']}&nbsp;&nbsp;<u>&nbsp;{$cur_date}&nbsp;</u>"?></td>
</tr>
</table>
<!-- /Row 3 -->

<!-- Row 5 -->
<table class="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_header_row_title" width="7%"><?php echo $lang['pro_teacher_name']?></td>
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

<!--<tr>
<?php
for ($i = 1;$i <= 9;$i++) {
	echo "<td class=\"pro_results_row\"><b>{$i}</b></td>\n";
}
?>
</tr>-->
<!-- /Header -->

<?php

// Main query for this order
$rating = array();
$user_names = array();
$user_order_nums = array();
$test_res = array();
$user_status = array();
$full_rating = array();

// Extract user's rating before pass exam

$DB->query("SELECT users.user_id, rating_results.rating, rating_results.status, users.user_name, users.user_order_num
			FROM users, rating_results
			WHERE users.user_group = {$group}
			AND rating_results.user_id = users.user_id
			AND rating_results.test_id = {$test}
			ORDER BY rating_results.id ASC");

while ($row = $DB->fetch_row()) {
	$rating[$row[0]] = $row[1];
	$test_res[$row[0]] = -1;
	$user_status[$row[0]] = $row[2];
	$user_names[$row[0]] = $row[3];
	$user_order_nums[$row[0]] = $row[4];
}
$DB->free_result();
//print_r($rating);
//print_r($user_names);

// Extract user's marks

$DB->query("SELECT session_results.user_id, session_results.result, session_results.full_res
			FROM session_results, users 
			WHERE session_results.test_id = {$test}
			AND users.user_group = {$group}
			AND session_results.user_id = users.user_id
			ORDER BY session_results.sess_id ASC");

while ($row = $DB->fetch_row()) {
	$res_percent = 100 * round(($row[1] / $test_rate), 2);
	$test_res[$row[0]] = $res_percent;
	$full_rating[$row[0]] = $row[2];
}
$DB->free_result();
//print_r($test_res);

while (list($key, $val) = each($rating)) {
	//echo "{$key}&nbsp;&nbsp;==&nbsp;&nbsp;{$val}<br>\n\n";
	
	// user sucessfully passed test
	if (($test_res[$key] != -1) && ($user_status[$key] != 1)) {
		//echo "tuhes<br>\n";
		//$full_rating[$key] = ceil(($rating[$key] + $test_res[$key]) / 2);
	}
	
	// user can pass test but not passed!!!
	if ($test_res[$key] == -1) {
		$full_rating[$key] = "--";
	}

	// Best [90 - 100]
	if (($rating[$key] >= 90) && ($user_status[$key] != 1) && ($test_res[$key] == -1)) {
	// Added by Yuriy Bezgachnyuk 26.05.2011 18:56 GMT +02:00
	//if (($rating[$key] >= 90) && ($user_status[$key] != 1) && ($test_res[$key] >= 82)) {
		$full_rating[$key] = $rating[$key];
	}
		
	// user cannot pass test
	if ($user_status[$key] == 1) {
		$test_res[$key] = "---";
		//$rating[$key] = "---";
		$full_rating[$key] = "---";
	}
	//echo "{$key}&nbsp;&nbsp;==&nbsp;&nbsp;{$full_rating[$key]}<br>\n\n";
}

global $hut;
$group_res = 0;
$k = 1;

while (list($key, $val) = each($user_names)) {
	echo "<tr>\n";

	echo "<td class=\"pro_results_row\">{$k}</td>\n";
	
	echo "<td class=\"pro_results_row_name\">{$val}</td>\n";

	echo "<td class=\"pro_results_row\">{$user_order_nums[$key]}</td>\n";

	// Test result
	
	$test_mark = "&nbsp;";

	if ($test_res[$key] != -1) $test_mark = $test_res[$key];

	// Rating before exam
	echo "<td class=\"pro_results_row\">{$rating[$key]}</td>\n";
	
	// Exam mark
	echo "<td class=\"pro_results_row\">{$test_mark}</td>\n";

	// Full rating
	// 100 balls scale
	echo "<td class=\"pro_results_row\">{$full_rating[$key]}</td>\n";
	
	
	// Fixed by Yuriy Bezgachnyuk 21.05.2011 18:59 GMT +02:00
	if ($full_rating[$key] == "---") {
		echo "<td class=\"pro_results_row\">---</td>\n";
		echo "<td class=\"pro_results_row\">н/д</td>\n";
		$s_markes['cannot_pass']++;
	}
	
	if ($full_rating[$key] == "--") {
		echo "<td class=\"pro_results_row\">--</td>\n";
		echo "<td class=\"pro_results_row\">н/я</td>\n";
		$s_markes['not_present']++;
	}

	else {

		// ECTS scale
		foreach ($ECTS_SYMBOLS as $MARK) {
			if (($full_rating[$key] >= $ECTS_MIN_VALUES[$MARK]) && ($full_rating[$key] <= $ECTS_MAX_VALUES[$MARK])) {
				echo "<td class=\"pro_results_row\">{$MARK}</td>\n";
				echo "<td class=\"pro_results_row\">{$NAT_SCALE[$MARK]}</td>\n";
				$markes[$NAT_SCALE[$MARK]]++;
			}
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
	echo "{$lang['pro_footer_not_present']}&nbsp;/&nbsp;{$lang['pro_footer_cannot_pass']}&nbsp;&nbsp;{$s_markes['not_present']}&nbsp;/&nbsp;{$s_markes['cannot_pass']}&nbsp;&nbsp;";
	//echo "{$lang['pro_footer_rate']}&nbsp;_______&nbsp;&nbsp;";
	//echo "{$lang['pro_footer_not_rate']}&nbsp;_______&nbsp;";
?>
</div>
<div class="pro_footer_row">
<?php	
	//echo "{$lang['pro_footer_cannot_pass']}&nbsp;&nbsp;&nbsp;{$s_markes['cannot_pass']}&nbsp;&nbsp;&nbsp;";
?>	
</div>
</div>
<!-- /Left Row -->

<!-- Right Row -->
<div id="pro_footer_right_row">
<div class="pro_footer_row"><?php echo "{$lang['pro_exam_teacher_name']}_______________(____________)"?></div>
<div class="pro_footer_row_sign" style="padding-left: 150px;">підпис&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;П.І.Б.&nbsp;&nbsp;&nbsp;</div>
<div class="pro_footer_row"><?php echo "{$lang['pro_responsible_person_name']}__________________(____________)"?></div>
<div class="pro_footer_row_sign" style="padding-left: 150px;">підпис&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;П.І.Б.&nbsp;&nbsp;&nbsp;</div>
<!--<div class="pro_footer_row"><?php echo "{$lang['pro_decan_name']}_______________________"?></div>-->
</div>
<!-- /Right Row -->
<div class="pro_footer_row">&nbsp;</div>
<div class="pro_footer_row" style="font-size: 18px; padding-left: 20px;"><?php echo "{$lang['pro_decan_name']}____________________(_______________________)"?></div>
<div class="pro_footer_row_sign" style="padding-left: 220px;">підпис&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;П.І.Б.&nbsp;&nbsp;&nbsp;</div>

<!-- /Footer -->
<?php

//echo "<DIV align=\"center\" class=\"copyright\">{$lang['print_footer']}<BR><b>{$cur_date}</b></DIV>\n";
page_end();
?>