<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage d-tester admin subsystem
 * @name diagram functions source
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2005-2011 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 15/01/2011 19:10 GMT +02:00
 */

/**
 * explode questions/answers ID's information from array with any separator
 *
 * @param string $aq
 * @return array with ID's
 */

function extract_aq($aq) {
	$sep = "||"; // Розділювач
	$res = explode($sep,$aq);
	return $res;
}

/**
 * reducing user_name to max length in data
 *
 * @param string $user_name
 * @param int $max_length
 * @return string $new_user_name
 */

function user_name_reduce($user_name, $max_length) {
	$new_user_name = $user_name;
	$du = $max_length - strlen($user_name);
	if ($du != 0) {
		for ($i = 0;$i < $du;$i++) {
			$new_user_name.=" ";
		}
	}
	return $new_user_name;
}

// ----------------------------------------------

/**
 * Getting (calculating) test mark
 *
 * @param int $test - test ID
 * @return int $test_rate
 */

function get_test_rate($test) {
	global $DB;

	$test_rate = 0;

	// Визначаємо параметри тесту
	$DB->query("SELECT COUNT(*) FROM test_details WHERE test_id={$test}");
	$DB->fetch_row();
	if($DB->record_row[0] == 0) {$test_rate = 100;}
	else
	{
		$DB->query("SELECT level_id, level_tasks, level_rate FROM test_details WHERE test_id={$test}");
		$test_rate = 0;
		while($DB->fetch_row()) {
			$q_level_rate[$DB->record_row[0]] = $DB->record_row[2]; // Кількість балів за одне завдання даного рівня
			$q_level_tasks[$DB->record_row[0]] = $DB->record_row[1]; // Кількість завдань даного рівня
			$test_rate = $test_rate + ($q_level_rate[$DB->record_row[0]] * $q_level_tasks[$DB->record_row[0]]); // Обчислюємо кількість балів за весть тест
		}
		$DB->free_result();
	}

	return $test_rate;
}

// ----------------------------------------------

/**
 * Getting information of Study year: (ex: 2010/2011)
 * @param current date
 * @return string $std_year
 */

function get_std_year() {
	
	// initialization
	$st_year = 0; // Start year
	$en_year = 0; // End year
	$std_year = ""; // Study year - $st_year / $en_year

	// Current date
	// [0] - month, [1] - year
	$c_d = explode("-", date("m-Y"));
	
	// Test!!!
	//$c_d = explode("-", date("m-Y", mktime(0, 0, 0, 6, 20, 1998)));

	$c_d[0] = intval($c_d[0]);
	$c_d[1] = intval($c_d[1]);

	if (($c_d[0] >= 9) && ($c_d[0] <= 12)) {
		$st_year = $c_d[1];
		$en_year = $c_d[1] + 1;
	}

	else {
		$st_year = $c_d[1] - 1;
		$en_year = $c_d[1];
	}

	$std_year = $st_year."/".$en_year;
	
	return $std_year;	
}

// ----------------------------------------------

/**
 * Generating knowledge quality diagram for all students group who passed this test ($test)
 *
 * @param int $test - test ID 
 * @return array $out - HTML source
 */

function dia_g_quality($test) // Генерація діаграми якості знань по групам згідно деякого тесту
{
	global $PARAM, $DB, $lang;
	// Variables for output parameters
	$max_width = $PARAM['dia_max_width'];
	$max_height = $PARAM['dia_max_heigth'];

	$group_names = array();
	$group_rate = array();

	$DB->query("SELECT test_name FROM tests WHERE test_id={$test}");
	$DB->fetch_row();
	$test_name = $DB->record_row[0];
	$DB->free_result();

	$test_rate = get_test_rate($test);

	$groups = $DB->query("SELECT group_id, group_name FROM groups ORDER BY group_id ASC");
	if($DB->get_num_rows() == 0) {
		Show_Message("DB_ERROR_NO_DATA");
	}

	$count = 0;
	while($DB->fetch_row($groups)) {
		$group_names[$count] = $DB->record_row[1];
		$group_id = $DB->record_row[0];
		$DB->query("SELECT session_results.result 
					FROM session_results, users 
					WHERE session_results.test_id = {$test} 
					AND users.user_group = {$group_id} 
					AND session_results.user_id = users.user_id 
					ORDER BY users.user_id");
		
		$how_users = $DB->get_num_rows();
		if($how_users == 0) continue;
		while($DB->fetch_row()) {
			$group_rate[$count] += $DB->record_row[0];
		}
		$group_rate[$count] = ($group_rate[$count] / $how_users);
		$count++;
	}

	// Generate output HTML code
	$out[].="<table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"4\" border=\"0\" class=\"tbl_view_frame\">\n";
	$out[].="<tr><td align=\"center\" colspan=\"3\" class=\"maintitle\">{$lang['dia_g_quality']}</td></tr>\n";
	$out[].="<tr><td align=\"center\" width=\"100%\" colspan=\"3\" class=\"row4\"><b>{$lang['capt_test']}:&nbsp;{$test_name}&nbsp;&nbsp;[{$lang['test_rate']}&nbsp;{$test_rate}]</b></td></tr>\n";
	$out[].="<tr><th align=\"center\" width=\"10%\" class=\"row3\">{$lang['capt_group']}</th>\n<th align=\"center\" width=\"12%\" class=\"row3\">{$lang['avg_group_result']}</th>\n<th align=\"left\" width=\"78%\" class=\"row3\">{$lang['avg_group_quality']}</th></tr>\n";

	for($i = 0;$i < sizeof($group_rate);$i++) {
		$group_avg_rate = round($group_rate[$i], 2);
		$percent = round(($group_avg_rate / $test_rate) * 100, 2);
		$width = round(($max_width * $percent) / 100);
		$out[].="<!-- Show {$i} gauge -->\n";
		$out[].="<tr>\n<td class=\"row3\" align=\"center\"><b>{$group_names[$i]}</b></td>\n<td align=\"center\" class=\"row1\"><b>{$group_avg_rate}</b></td>\n";
		$out[].="<td class=\"row1\" valign=\"middle\" align=\"left\"><img src=\"./images/diagram/bar_left.gif\" height=\"{$max_height}\" width=\"8\"><img src=\"./images/diagram/bar.gif\" height=\"{$max_height}\" width=\"{$width}\"><img src=\"./images/diagram/bar_right.gif\" height=\"{$max_height}\" width=\"8\">&nbsp;&nbsp;<b>[{$percent} %]</b></td>\n</tr>\n";
	}
	$out[].="<tr><td colspan=\"3\" width=\"100%\" class=\"darkrow2\">&nbsp;</td></tr>\n</table>\n";
	return $out;
}

// ----------------------------------------------

/**
 * Generating quality diagram for all students from any group
 *
 * @param int $test - test ID
 * @param int $group - group ID
 * @param int $sort - sort type [0,1,2,....]
 * @param string $sort_order - sorting order [asc - ascending order, desc - descending order]
 * @return array $out - HTML source
 */

function dia_ug_quality($test, $group, $sort = 0, $sort_order = "asc") // Генерація діаграми якості відповідей для користувачів деякої групи
{
	global $PARAM, $DB, $lang;

	$max_width = $PARAM['dia_max_width'];
	$max_height = $PARAM['dia_max_heigth'];

	$DB->query("SELECT group_name FROM groups WHERE group_id={$group}");
	$DB->fetch_row();
	$group_name = $DB->record_row[0];
	$DB->free_result();

	$DB->query("SELECT test_name FROM tests WHERE test_id={$test}");
	$DB->fetch_row();
	$test_name = $DB->record_row[0];

	$test_rate = get_test_rate($test);

	switch ($sort) {
		case 0:$sort_where = "session_results.sess_id ";break;
		case 1:$sort_where = "session_results.result ";break;
		case 2:$sort_where = "users.user_id ";break;
	}
	$sort_where.=$sort_order;
	$DB->query("SELECT users.user_name, session_results.result
				FROM users, session_results 
				WHERE users.user_group = {$group} 
				AND session_results.test_id = {$test}
				AND users.user_id = session_results.user_id 
				ORDER BY {$sort_where}");

	$lang['dia_ug_quality'].=$group_name;
	$counter = 1;

	// Generate output HTML code
	$out[].="<table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"4\" border=\"0\" class=\"tbl_view_frame\">\n";
	$out[].="<tr><td align=\"center\" colspan=\"3\" class=\"maintitle\">{$lang['dia_ug_quality']}</td></tr>\n";
	$out[].="<tr><td align=\"center\" width=\"100%\" colspan=\"3\" class=\"row4\"><b>{$lang['capt_test']}:&nbsp;{$test_name}&nbsp;&nbsp;[{$lang['test_rate']}&nbsp;{$test_rate}]</b></td></tr>\n";
	$out[].="<tr>\n<th align=\"center\" width=\"8%\" class=\"row3\">{$lang['capt_num']}</th>\n<th align=\"left\" width=\"25%\" class=\"row3\">{$lang['capt_student']}</th>\n<th align=\"left\" width=\"67%\" class=\"row3\">{$lang['capt_mark']}/{$lang['capt_quality']}</th>\n</tr>\n";

	while($DB->fetch_row()) {
		$user_rate = $DB->record_row[1];
		$user_quality = round(($user_rate / $test_rate) * 100, 2);
		$width = round(($max_width * $user_quality) / 100);
		$out[].="<!-- Show {$counter} gauge -->\n";
		$out[].="<tr>\n<td class=\"row3\" align=\"center\"><b>{$counter}</b></td>\n<td align=\"left\" class=\"row1\"><b>{$DB->record_row[0]}</b></td>\n";
		$out[].="<td class=\"row1\" valign=\"middle\" align=\"left\"><img src=\"./images/diagram/bar_left.gif\" height=\"{$max_height}\" width=\"8\"><img src=\"./images/diagram/bar.gif\" height=\"{$max_height}\" width=\"{$width}\"><img src=\"./images/diagram/bar_right.gif\" height=\"{$max_height}\" width=\"8\">&nbsp;&nbsp;<b>[{$user_rate}/{$user_quality} %]</b></td>\n</tr>\n";
		$counter++;
	}
	$out[].="<tr><td colspan=\"3\" width=\"100%\" class=\"darkrow2\">&nbsp;</td></tr>\n</table>\n";

	$DB->free_result();

	return $out;
}

// ----------------------------------------------

/**
 * Generating user detail quality diagram
 *
 * @param int $sess_id - session ID
 * @return array $out - HTML source
 */

function dia_ut_quality($sess_id) // Генерація діаграми детальної інформації про здачу тесту студентом
{
	global $PARAM, $DB, $lang;

	$max_width = $PARAM['dia_max_width'];
	$max_height = $PARAM['dia_max_heigth'];

	$DB->query("SELECT users.user_name, groups.group_name 
				FROM users, groups, session_results
				WHERE session_results.sess_id = {$sess_id} 
				AND users.user_id = session_results.user_id 
				AND groups.group_id = users.user_group");
	
	$DB->fetch_row();
	$user_name = $DB->record_row[0];
	$group_name = $DB->record_row[1];
	$DB->free_result();

	$DB->query("SELECT tests.test_name, tests.test_id 
				FROM tests, session_results 
				WHERE session_results.sess_id = {$sess_id} 
				AND tests.test_id = session_results.test_id");
	
	$DB->fetch_row();
	$test_name = $DB->record_row[0];
	$test_id = $DB->record_row[1];
	$DB->free_result();

	$DB->query("SELECT level_id, level_tasks, level_rate FROM test_details WHERE test_id={$test_id} ORDER BY id");
	$count = 0;
	$test_rate = 0;
	$total_u_rate = 0;
	$total_tasks = 0;
	$levels_ta = array();
	if(!$DB->get_num_rows()) Show_Message("DIA_UT_ERROR");
	while($DB->fetch_row()) {
		$q_level_rate[$DB->record_row[0]] = $DB->record_row[2]; // Кількість балів за одне завдання даного рівня
		$q_level_tasks[$DB->record_row[0]] = $DB->record_row[1]; // Кількість завдань даного рівня, що будуть задані
		$test_rate = $test_rate + ($q_level_rate[$DB->record_row[0]] * $q_level_tasks[$DB->record_row[0]]); // Обчислюємо кількість балів за весть тест
		$q_levels_used[$count] = $DB->record_row[0];
		$total_tasks += $q_level_tasks[$DB->record_row[0]];
		$count++;
	}
	$DB->free_result();

	$DB->query("SELECT true_answers 
				FROM session_results 
				WHERE sess_id={$sess_id}");
	
	$DB->fetch_row();
	$true_answers = extract_aq($DB->record_row[0]); // Відповіді
	$DB->free_result();

	// Визначаємо кількість правильних відповідей по рівнях $levels_ta['level_id']='true_answers'

	$j = 1;
	$true_answers_c = 0;
	$glb_count = 0;
	for($i = 0;$i < $count;$i++) {
		while($j <= $q_level_tasks[$q_levels_used[$i]]) {
			if($true_answers[$glb_count + $j] == 1) {
				$true_answers_c++;
			}
			$j++;
		}
		$levels_ta[$q_levels_used[$i]] = $true_answers_c;
		$glb_count += ($j - 1);
		$j = 1;
		$true_answers_c = 0;
	}

	// Generate output HTML code
	$out[].="<table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"4\" border=\"0\" class=\"tbl_view_frame\">\n";
	$out[].="<tr>\n<td align=\"center\" colspan=\"4\" class=\"maintitle\">{$lang['dia_ut_quality']}&nbsp;&nbsp;(<b>{$lang['capt_student']}:&nbsp;{$user_name}&nbsp;&nbsp;[{$lang['capt_group']}&nbsp;{$group_name}]</b>)</td>\n</tr>\n";
	$out[].="<tr>\n<td align=\"center\" width=\"100%\" colspan=\"4\" class=\"row4\"><b>{$lang['capt_test']}:&nbsp;{$test_name}&nbsp;&nbsp;[{$lang['test_rate']}&nbsp;{$test_rate}]</b></td>\n</tr>\n";
	$out[].="<tr>\n<th align=\"center\" width=\"10%\" class=\"row3\">{$lang['q_group']}</th>\n<th align=\"center\" width=\"15%\" class=\"row3\">{$lang['h_tasks_rate']}</th>\n<th align=\"center\" width=\"10%\" class=\"row3\"><b>{$lang['capt_mark']}</b></th>\n<th align=\"left\" width=\"65%\" class=\"row3\"><b>{$lang['capt_quality']}</b></th>\n</tr>\n";

	for($i = 0;$i < sizeof($levels_ta);$i++) {
		$l_rate = ($levels_ta[$q_levels_used[$i]] * $q_level_rate[$q_levels_used[$i]]);
		$max_level_rate = ($q_level_tasks[$q_levels_used[$i]] * $q_level_rate[$q_levels_used[$i]]);
		$l_quality = round(round((($l_rate / $max_level_rate) * 100), 2), 2);

		$total_u_rate += $l_rate;

		$width = round($max_width * ($l_rate / $max_level_rate));

		$out[].="\n<!-- Show {$i} gauge -->\n";
		$out[].="<tr>\n<td align=\"center\" class=\"row1\"><b>{$q_levels_used[$i]}</b></td>\n<td align=\"center\" class=\"row1\"><b>{$q_level_tasks[$q_levels_used[$i]]}/{$q_level_rate[$q_levels_used[$i]]}&nbsp;=>&nbsp;{$max_level_rate}</b></td>\n";
		$out[].="<td align=\"center\" class=\"row1\"><b>{$l_rate}</b></td>\n";
		$out[].="<td align=\"left\" class=\"row1\"><img src=\"./images/diagram/bar_left.gif\" height=\"{$max_height}\" width=\"8\"><img src=\"./images/diagram/bar.gif\" height=\"{$max_height}\" width=\"{$width}\"><img src=\"./images/diagram/bar_right.gif\" height=\"{$max_height}\" width=\"8\">&nbsp;[<b>{$l_quality}%</b>]</td>\n</tr>\n";
	}

	$width = round($max_width * ($total_u_rate / $test_rate));
	$res_quality = round(round((($total_u_rate / $test_rate) * 100), 2), 2);

	$out[].="\n<!-- Show Total Results -->\n";
	$out[].="<tr>\n<td align=\"center\" class=\"row2\">{$lang['ut_total']}</td>\n<td align=\"center\" class=\"row2\"><b>{$total_tasks}/{$test_rate}</b></td>\n<td align=\"center\" class=\"row2\"><b>{$total_u_rate}</b></td>\n";
	$out[].="<td align=\"left\" class=\"row2\"><img src=\"./images/diagram/bar_left.gif\" height=\"{$max_height}\" width=\"8\"><img src=\"./images/diagram/bar.gif\" height=\"{$max_height}\" width=\"{$width}\"><img src=\"./images/diagram/bar_right.gif\" height=\"{$max_height}\" width=\"8\">&nbsp;[<b>{$res_quality}%</b>]</td>\n</tr>\n";
	$out[].="\n<tr>\n<td colspan=\"4\" width=\"100%\" class=\"darkrow2\">&nbsp;</td>\n</tr>\n";
	$out[].="</table>\n";

	return $out;
}

// ----------------------------------------------

/**
 * Generating G. Rash quality matrix for all sessions or for all any user sessions
 *
 * @param int $group_id - group ID
 * @param int $test_id - test ID
 * @param int $user_id - user ID (default $user_id = 0 - generating matrix for all sessions)
 * @return array $out - HTML source
 */

function generate_stable($group_id, $test_id, $user_id = 0) // CVS Edition
{
	global $DB, $lang, $PARAM;

	/**
	 * minimal float value: ex: 0/1 = 0 but log(0) = INF, then 0/1 = DIA_MIN_FLOAT_VALUE
	 */
	define("DIA_MIN_FLOAT_VALUE", 0.01);

	define("DIA_MAX_FLOAT_VALUE", 99.0);

	$DB->query("SELECT group_name FROM groups WHERE group_id={$group_id}");
	$DB->fetch_row();
	$RES['group_name'] = $DB->record_row[0];

	$tbl_row_main = array(array());
	$tbl_row_user = array();

	$DB->query("SELECT subjects.subject_name, tests.test_name
				FROM subjects, tests
				WHERE tests.test_id = {$test_id} 
				AND subjects.subject_id = tests.test_subject_id");
	$DB->fetch_row();
	$RES['subject_name'] = $DB->record_row[0];
	$RES['test_name'] = $DB->record_row[1];
	$DB->free_result();

	if($user_id == 0) {

		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	else {
		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND users.user_id = {$user_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	$count = 1;
	while($DB->fetch_row()) {
		$tbl_row_user[$count] = $DB->record_row[0];
		$tbl_row_main[$count] = extract_aq($DB->record_row[1]);
		$count++;
	}

	$DB->free_result();

	// Generate output HTML code

	$out[]="\n<!-- Show Stat Table-->\n";
	$out[].="<table width=\"100%\" align=\"center\" cellpadding=\"3\" cellspacing=\"3\" border=\"0\" class=\"tbl_view_frame\">\n";
	$out[].="<tr><td align=\"center\" width=\"100%\" class=\"maintitle\">{$lang['stable_wizard']}</td></tr>\n";
	$out[].="<tr><td align=\"left\"><b>{$lang['test_subject']}:&nbsp;{$RES['subject_name']}</b></td></tr>\n";
	$out[].="<tr><td align=\"left\"><b>{$lang['capt_test']}:&nbsp;{$RES['test_name']}</b></td></tr>\n";
	$out[].="<tr><td align=\"left\"><b>{$lang['capt_group']}:&nbsp;{$RES['group_name']}</b></td></tr>\n</table>\n";

	$out[].="\n<!-- Main Results -->\n";
	$out[].="<table width=\"98%\" align=\"center\" cellpadding=\"2\" cellspacing=\"2\" class=\"stat_table\">\n";

	$out[].="<tr>\n<td align=\"center\" class=\"row4\" width=\"5%\"><b>№</b></td>\n<td align=\"left\" class=\"row4\" width=\"17%\"><b>{$lang['capt_student']}</b></td>\n";
	for($j = 1;$j < (sizeof($tbl_row_main[1]));$j++) {
		$out[].="<td align=\"center\" class=\"row4\"><b>{$j}</b></td>\n";
	}
	$out[].="<td align=\"center\" class=\"row4\" width=\"8%\"><b>Y(i)</b></td>\n";
	$out[].="<td align=\"center\" class=\"row4\" width=\"8%\"><b>p(i)</b></td>\n";
	$out[].="<td align=\"center\" class=\"row4\" width=\"8%\"><b>q(i)</b></td>\n";
	$out[].="<td align=\"center\" class=\"row4\" width=\"8%\"><b>p(i)/q(i)</b></td>\n";
	$out[].="<td align=\"center\" class=\"row4\" width=\"8%\"><b>ln(p(i)/q(i))</b></td>\n";
	$out[].="</tr>\n";

	// Parameters in columns
	$yi = array();
	$pi = array();
	$qi = array();
	$pi_qi = array();
	$log_piqi = array();

	// Parameters in rows
	$Rj = array();
	$R_SY = 0;
	$Wj = array();
	$pj = array();
	$pj_sum = 0;
	$qj = array();
	$pjqj = array();
	$qj_d_pj = array();
	$log_qj_d_pj = array();

	for($i = 1;$i < (sizeof($tbl_row_user) + 1);$i++) {
		$out[].="\n<!-- Show {$i} row -->\n<tr>\n<td align=\"center\" class=\"row1\"><b>{$i}</b></td>\n<td align=\"left\" class=\"row1\"><b>{$tbl_row_user[$i]}</b></td>\n";
		for($j = 1;$j < (sizeof($tbl_row_main[1]));$j++) {
			if($tbl_row_main[$i][$j] == 0) {
				$color = "red";
				//$yi[$i] += 0;
			}
			else {
				$yi[$i]++;
				$color = "blue";
			}
			$out[].="<td align=\"center\" class=\"row1\"><font color=\"{$color}\"><b>{$tbl_row_main[$i][$j]}</b></font></td>\n";
		}

		$R_SY += $yi[$i];

		// Calculating parameters
		$pi[$i] = @round($yi[$i] / (sizeof($tbl_row_main[1]) - 1), 2);
		$qi[$i] = 1 - $pi[$i];
		if(($pi[$i] != 1) && ($qi[$i] != 1)) {
			$pi_qi[$i] = @round(($pi[$i] / $qi[$i]), 2);
		}
		else {
			if ($qi[$i] != 1) $pi_qi[$i] = DIA_MAX_FLOAT_VALUE;
			else {
				$pi_qi[$i] = DIA_MIN_FLOAT_VALUE;
			}
		}

		$log_piqi[$i] = @round(log($pi_qi[$i]), 2);
		// P(i)<60%
		if($log_piqi[$i] < $PARAM['st_satisf']) $class = "stattable_bad";
		// P(i)=60...74%
		if(($log_piqi[$i] < $PARAM['st_good'])
		&&($log_piqi[$i] >= $PARAM['st_satisf'])) $class = "stattable_satisf";
		// P(i)=75...89%
		if(($log_piqi[$i] < $PARAM['st_fine'])
		&&($log_piqi[$i] >= $PARAM['st_good'])) $class = "stattable_good";
		// P(i)=90-100%
		if($log_piqi[$i] >= $PARAM['st_fine']) $class = "stattable_fine";
		if($pi[$i] == 1) $class = "stattable_fine";

		$out[].="<td align=\"center\" class=\"row1\"><b>{$yi[$i]}</b></td>\n";
		$out[].="<td align=\"center\" class=\"row1\"><b>{$pi[$i]}</b></td>\n";
		$out[].="<td align=\"center\" class=\"row1\"><b>{$qi[$i]}</b></td>\n";
		$out[].="<td align=\"center\" class=\"row1\"><b>{$pi_qi[$i]}</b></td>\n";
		$out[].="<td align=\"center\" class=\"{$class}\"><b>{$log_piqi[$i]}</b></td>\n";
		$out[].="</tr>\n";
	}

	// Calculating R parameter
	$out[].="<!-- Rj -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>Rj</b></td>\n";
	for($j = 1;$j < (sizeof($tbl_row_main[1]));$j++) {
		$Rj[$j] = 0;
		for($i = 1;$i < (sizeof($tbl_row_user) + 1);$i++) {
			if($tbl_row_main[$i][$j] == 1) $Rj[$j]++;
		}
		$out[].="<td align=\"center\" class=\"row1\"><b>{$Rj[$j]}</b></td>\n";
	}
	$out[].="<td align=\"center\" bgcolor=\"#FFFF00\"><b>{$R_SY}</b></td>\n";
	$out[].="</tr>\n";

	// Calculating W parameter
	$out[].="<!-- Wj -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>Wj</b></td>\n";
	for($j = 1;$j < (sizeof($tbl_row_main[1]));$j++) {
		$Wj[$j] = 0;
		for($i = 1;$i < (sizeof($tbl_row_user) + 1);$i++) {
			if($tbl_row_main[$i][$j] == 0) $Wj[$j]++;
		}
		$out[].="<td align=\"center\" class=\"row1\"><b>{$Wj[$j]}</b></td>\n";
	}
	$out[].="</tr>\n";

	// Calculating p(j) parameter
	$out[].="<!-- p(j) -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>p(j)</b></td>\n";
	for($j = 1;$j < (sizeof($Rj) + 1);$j++) {
		$pj[$j] = @round(($Rj[$j] / ($Rj[$j] + $Wj[$j])), 2);
		$pj_sum += $pj[$j];
		$out[].="<td align=\"center\" class=\"row1\"><b>{$pj[$j]}</b></td>\n";
	}
	$out[].="<td align=\"center\" bgcolor=\"#FFFF00\"><b>{$pj_sum}</b></td>\n";
	$out[].="</tr>\n";

	// Calculating q(j) parameter
	$out[].="<!-- q(j) -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>q(j)</b></td>\n";
	for($j = 1;$j < (sizeof($Rj) + 1);$j++) {
		$qj[$j] = 1 - $pj[$j];
		$out[].="<td align=\"center\" class=\"row1\"><b>{$qj[$j]}</b></td>\n";
	}
	$out[].="</tr>\n";

	// Calculating p(j)q(j) parameter
	$out[].="<!-- p(j)q(j) -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>p(j)q(j)</b></td>\n";
	for($j = 1;$j < (sizeof($Rj) + 1);$j++) {
		$pjqj[$j] = round(($pj[$j] * $qj[$j]), 2);
		$out[].="<td align=\"center\" class=\"row1\"><b>{$pjqj[$j]}</b></td>\n";
	}
	$out[].="</tr>\n";

	// Calculating qj_d_pj parameter
	$out[].="<!-- qj_d_pj -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>q(j)/p(j)</b></td>\n";
	for($j = 1;$j < (sizeof($Rj) + 1);$j++) {
		$qj_d_pj[$j] = @round(($qj[$j] / $pj[$j]), 2);
		if($qj_d_pj[$j] == 0) $qj_d_pj[$j] = DIA_MIN_FLOAT_VALUE;
		$out[].="<td align=\"center\" class=\"row1\"><b>{$qj_d_pj[$j]}</b></td>\n";
	}
	$out[].="</tr>\n";

	// Calculating log_qj_d_pj parameter
	$out[].="<!-- log_qj_d_pj -->\n<tr>\n<td align=\"center\" class=\"row4\">&nbsp;</td>\n";
	$out[].="<td align=\"center\" class=\"row4\"><b>ln(q(j)/p(j))</b></td>\n";
	for($j = 1;$j < (sizeof($Rj) + 1);$j++) {
		$log_qj_d_pj[$j] = round(@log($qj_d_pj[$j]), 2);
		$out[].="<td align=\"center\" class=\"row1\"><b>{$log_qj_d_pj[$j]}</b></td>\n";
	}
	$out[].="</tr>\n";

	$out[].="</table>\n";

	$out[].="<br>\n<div align=\"center\" class=\"tbl_view_frame\"><a href=\"export_res.php?ex_format=0&group_id={$group_id}&test_id={$test_id}&user_id={$user_id}\"><b>{$lang['EX_WINSTEP_FILE']}</b></a></div>\n";

	$out[].="<br><br>\n<div align=\"center\" class=\"tbl_view_frame\"><a href=\"export_res.php?ex_format=1&group_id={$group_id}&test_id={$test_id}&user_id={$user_id}\"><b>{$lang['EX_CK_MOODLE_PLG_FILE']}</b></a></div>\n";

	return $out;
}

// ----------------------------------------------

/**
 * Generating G. Rash quality matrix (only 0/1) for all sessions or for all any user sessions [to file]
 * WINSTEPS/MINISTEP Format
 *
 * @param int $group_id - group ID
 * @param int $test_id - test ID
 * @param int $user_id - user ID (default $user_id = 0 - generating matrix for all sessions)
 * @return array $out - File source
 */

function generate_stable_file($group_id, $test_id, $user_id = 0) {

	global $DB, $lang, $PARAM;

	$cur_date = date("d/m/Y H:i:s");

	$DB->query("SELECT test_name FROM tests WHERE test_id={$test_id}");
	$DB->fetch_row();
	$test_name = $DB->record_row[0];
	$DB->free_result();

	$matrix_rows = array();

	$tbl_row_main = array(array());
	$tbl_row_user = array();

	$out_mi = array(); // main info
	$out = array();

	if($user_id == 0) {

		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	else {
		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND users.user_id = {$user_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	$count = 1;
	$max_user_name_length = 0;
	while($DB->fetch_row()) {
		$tbl_row_user[$count] = $DB->record_row[0];
		$tbl_row_main[$count] = extract_aq($DB->record_row[1]);

		// Calculating max_user_name_length
		if (strlen($tbl_row_user[$count]) > $max_user_name_length) {
			$max_user_name_length = strlen($tbl_row_user[$count]);
		}

		$count++;
	}

	$DB->free_result();

	$num_items = sizeof($tbl_row_main[$count - 1]) - 1;

	$item1 = $max_user_name_length + 2;

	// Winsteps / Ministep import file header
	$header = array("; This file was generated d-tester(tm) v 1.2 RC1 RCSK",
	"; date: {$cur_date}",
	"; Copyright (c) 2005-2008 by Yuriy Bezgachnyuk",
	"&INST",
	"TITLE = '{$test_name}'",
	"NAME1 = 1",
	"NAMELENGTH = {$max_user_name_length}",
	"ITEM1 = {$item1}",
	"NI = {$num_items}",
	"CODES = 01",
	"CLFILE = *",
	"0 Wrong",
	"1 Right",
	"*",
	"PERSON = STUDENT",
	"ITEM = TAP",
	"&END"); // variables

	for ($i = 1;$i < $count;$i++) {
		$matrix_row = ""; // variable for 1 row info [temp]. ex.: 000000/111111
		for ($j = 1;$j <= $num_items;$j++) {
			$matrix_row.=$tbl_row_main[$i][$j];
		}
		$matrix_rows[$i] = $matrix_row; // array for matrix rows (results)
	}

	$out = $header;
	for ($i = 1;$i <= $num_items;$i++) {
		array_push($out, "Question #{$i}");
	}
	array_push($out, "END NAMES");

	for ($i = 1;$i < $count;$i++) {
		$tbl_row_user[$i] = user_name_reduce($tbl_row_user[$i], $max_user_name_length);
		$out_mi[$i] = $tbl_row_user[$i]." ".$matrix_rows[$i];
		array_push($out, $out_mi[$i]);
	}

	return $out;
}

/**
 * generating G. Rash Matrix to XML file for next statistical analysis in CK_Moodle_Plugin
 * CK_Moodle_Plugin (authors: Yuriy M. Chopey, Sergiy O. Kartashov)
 * 
 * this function it's a liitle gift from Me (Yuriy Bezgachnyuk) for his diplomas :-))))))))
 *
 * @author Yuriy Bezgachnyuk
 * 
 * @param int $group_id
 * @param int $test_id
 * @param int $user_id
 */

function generate_stable_XML_file($group_id, $test_id, $user_id = 0) {

	global $DB, $lang, $PARAM;

	$file_name = "sraka"; // :-)))))))))))

	$tbl_row_user = array();
	$tbl_row_main = array(array());

	$DB->query("SELECT test_name FROM tests WHERE test_id={$test_id}");
	$DB->fetch_row();
	$test_name = $DB->record_row[0];
	$DB->free_result();

	if($user_id == 0) {

		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	else {
		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND users.user_id = {$user_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	$count = 1;

	while($DB->fetch_row()) {
		$tbl_row_user[$count] = $DB->record_row[0];
		$tbl_row_main[$count] = extract_aq($DB->record_row[1]);

		$count++;
	}

	//print_r($tbl_row_main);

	$DB->free_result();

	$num_users = sizeof($tbl_row_user);
	$num_items = sizeof($tbl_row_main[$count - 1]) - 1;

	// XML File Section
	// Global constants
	define("XML_VERSION", "1.0");
	define("XML_ENCODING", "UTF-8");

	$doc = new DOMDocument(XML_VERSION, XML_ENCODING); // XML version = 1.0 encoding = UTF-8

	$root_el = $doc->createElement("main");
	$root_el->setAttribute("xmlns", "xml-schema:import_matrix.xdr");

	$am_users = $doc->createElement("amount_users", $num_users);
	$am_questions = $doc->createElement("amount_questions", $num_items);

	$matrix = $doc->createElement("matrix");
	$matrix->setAttribute("group", "tuhes");
	$test_name = iconv("Windows-1251", "UTF-8", $test_name);
	$matrix->setAttribute("course", $test_name);

	$i = 1;
	foreach ($tbl_row_user as $user) {
		$x_user = $doc->createElement("user");
		$user = iconv("Windows-1251", "UTF-8", $user);
		$x_user->setAttribute("name", $user);

		for ($j = 1;$j <= $num_items;$j++) {
			$x_question = $doc->createElement("question", $tbl_row_main[$i][$j]);
			$x_user->appendChild($x_question);
		}
		
		$i++;

		$matrix->appendChild($x_user);
	}


	$root_el->appendChild($am_questions);
	$root_el->appendChild($am_users);

	$root_el->appendChild($matrix);

	$doc->appendChild($root_el);

	header("Content-type: application/xml");
	header("Content-Disposition: attachment; filename={$file_name}.xml");

	echo $doc->saveXML();

	// End of saving

}

?>