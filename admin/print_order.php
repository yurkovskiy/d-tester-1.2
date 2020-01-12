<?PHP

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @name results order print version (NUNG UP-10 FORM)
 * @author Yuriy Bezgachnyuk
 * @copyright 2009 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * 
 */

require_once("req.inc");
require_once("inc/ects.inc");
require_once("inc/dia_func.inc");

page_begin($lang['capt_resume']);

$markes = array("відмінно" => 0, "добре" => 0, "задовільно" => 0, "незадовільно" => 0);

$test = $_GET['test'];
$group = $_GET['group'];

$cur_date = date("d.m.Y");

$test_rate = get_test_rate($test);

/**
 * Queries
 */

// group name
$DB->query("SELECT group_name 
			FROM groups 
			WHERE group_id = {$group}");

$DB->fetch_row();
$group_name = $DB->record_row[0];
$DB->free_result();

// subject name
$DB->query("SELECT subject_name
			FROM subjects, tests
			WHERE tests.test_id = {$test}
			AND subjects.subject_id = tests.test_subject_id
");
$DB->fetch_row();
$subject_name = $DB->record_row[0];
$DB->free_result();

/**
 * Form Structure
 * HEADER
 */
?>

<!-- Header text -->
<div id="pro_form_type"><?php echo $lang['pro_form_type_name']?></div>
<div id="pro_uni_name"><?php echo $lang['pro_univer_name']?></div>
<div id="pro_form_name"><?php echo $lang['pro_form_name']?></div>
<!-- /Header text -->

<!-- Header table -->
<table id="pro_header_table" cellpadding="1" cellspacing="1" align="center">
<!-- Row 1 -->
<tr>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_faculty_name']?></td>
<td class="pro_header_row" width="34%">Автоматизації і комп&#180;ютерних наук</td>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_group_name']?></td>
<td class="pro_header_row" width="20%"><?php echo $group_name?></td>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_semestr']?></td>
<td class="pro_header_row" width="10%">&nbsp;</td>
</tr>

<!-- Row 2 -->
<tr>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_discipline_name']?></td>
<td class="pro_header_row" width="34%"><?php echo $subject_name?></td>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_teacher_name']?></td>
<td class="pro_header_row" width="20%">&nbsp;</td>
<td class="pro_header_row" width="12%"><?php echo $lang['pro_date']?></td>
<td class="pro_header_row" width="10%"><?php echo $cur_date?></td>
</tr>
</table>
<!-- /Header table -->

<!-- Results Table -->
<!-- Header -->
<table id="pro_results_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_results_row" width="3%" rowspan="2"><?php echo $lang['pro_results_num']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_ob_num']?></td>
<td class="pro_results_row" width="20%" rowspan="2"><?php echo $lang['pro_results_PIB']?></td>
<td class="pro_results_row" width="10%" rowspan="2"><?php echo $lang['pro_results_test_mark']?></td>
<td class="pro_results_row" width="15%" rowspan="2"><?php echo $lang['pro_results_current_mark']?></td>
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
	echo "<td class=\"pro_results_row\">{$i}</td>\n";
}
?>
</tr>
<!-- /Header -->

<?php

// Main query for this order
$rating = array();
$user_names = array();
$test_res = array();
$full_rating = array();

$DB->query("SELECT users.user_id, rating_results.rating, users.user_name
			FROM users, rating_results
			WHERE users.user_group = {$group}
			AND rating_results.user_id = users.user_id
			AND rating_results.test_id = {$test}
			ORDER BY rating_results.id ASC");

while ($row = $DB->fetch_row()) {
	$rating[$row[0]] = $row[1];
	$test_res[$row[0]] = 0;
	$user_names[$row[0]] = $row[2];
	$full_rating[$row[0]] = "&nbsp;";
}
$DB->free_result();
//print_r($rating);
//print_r($user_names);

global $hut;
$group_res = 0;
$k = 1;

while (list($key, $val) = each($user_names)) {
	echo "<tr>\n";

	echo "<td class=\"pro_results_row\">{$k}</td>\n";

	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";

	echo "<td class=\"pro_results_row_name\">{$val}</td>\n";

	// Test result
	
	$test_mark = "&nbsp;";
	
	if ($test_res[$key] != 0) $test_mark = $test_res[$key];

	echo "<td class=\"pro_results_row\">{$test_mark}</td>\n";

	// Rating before exam
	echo "<td class=\"pro_results_row\">{$rating[$key]}</td>\n";

	// Full rating
	// 100 balls scale
	echo "<td class=\"pro_results_row\">{$full_rating[$key]}</td>\n";

	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";
	
	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";
	
	// Teacher Signature
	echo "<td class=\"pro_results_row\">&nbsp;</td>\n";

	echo "</tr>\n";

	$k++;
}
echo "</table>\n";
echo "<!-- /Results Table -->\n";

?>
<!-- Footer -->
<table id="pro_footer_table" cellpadding="1" cellspacing="1" align="center">
<tr>
<td class="pro_footer_row"><?php echo $lang['pro_footer_all']?></td>
<td class="pro_footer_row">&nbsp;<?php echo $k - 1?>,&nbsp;</td>
<td class="pro_footer_row"><?php echo $lang['pro_footer_excelence']?></td>
<td class="pro_footer_row"><?php echo $markes['відмінно']?>,</td>
<td class="pro_footer_row"><?php echo $lang['pro_footer_good']?></td>
<td class="pro_footer_row"><?php echo $markes['добре']?>,</td>
<td class="pro_footer_row"><?php echo $lang['pro_footer_nbad']?></td>
<td class="pro_footer_row"><?php echo $markes['задовільно']?>,</td>
<td class="pro_footer_row"><?php echo $lang['pro_footer_bad']?></td>
<td class="pro_footer_row"><?php echo $markes['незадовільно']?></td>
</tr>
</table>

<div class="pro_footer_signature"><?php echo "<b>{$lang['pro_teacher_name']}</b>_______________"?></div>
<div class="pro_footer_signature"><?php echo "<b>{$lang['pro_decan_name']}</b>_______________"?></div>

<!-- /Footer -->
<?php

//echo "<DIV align=\"center\" class=\"copyright\">{$lang['print_footer']}<BR><b>{$cur_date}</b></DIV>\n";
page_end();
?>