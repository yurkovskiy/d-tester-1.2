<?php

$sort_where = "";

$DB->query("SELECT COUNT(*) 
			FROM test_details 
			WHERE test_id = {$test}");

$DB->fetch_row();
if($DB->record_row[0] ==0 ) {$test_rate = 100;}
else
{
	$DB->query("SELECT level_id, level_tasks, level_rate 
				FROM test_details 
				WHERE test_id = {$test}");

	$count = 0;
	$test_rate = 0;
	while($DB->fetch_row())
	{
		$q_level_rate[$DB->record_row[0]] = $DB->record_row[2]; // Кількість балів за одне завдання даного рівня
		$q_level_tasks[$DB->record_row[0]] = $DB->record_row[1]; // Кількість завдань даного рівня
		$test_rate = $test_rate + ($q_level_rate[$DB->record_row[0]] * $q_level_tasks[$DB->record_row[0]]); // Обчислюємо кількість балів за весть тест
	}
}
$DB->free_result();

$DB->query("SELECT subjects.subject_name, tests.test_name, tests.test_time, tests.test_type, groups.group_name
			FROM subjects, tests, groups
			WHERE tests.test_id = {$test} 
			AND groups.group_id = {$group}
			AND subjects.subject_id = tests.test_subject_id");

$row = $DB->fetch_row();
$subj = $row[0];
$tes = $row[1];
$tes_time = $row[2];
$test_type = $row[3];
$grp = $row[4];
$DB->free_result();

$sess_res = "session_results.result";
if($avg_enable != 0)
{
	$sort_where.=" GROUP BY session_results.user_id";
	switch($avg_enable)
	{
		case 1:{$sess_res = "AVG(session_results.result)";break;}
		case 2:{$sess_res = "MAX(session_results.result)";break;}
		case 3:{$sess_res = "MIN(session_results.result)";break;}
	}
}
$sort_where.=" ORDER BY ";
switch ($sort)
{
	case 0:{$sort_where.="session_results.sess_id ";break;}
	case 1:{$sort_where.="session_results.result ";break;}
	case 2:{$sort_where.="users.user_id ";break;}
}
$sort_where.=$sort_order;

$sess_id=", session_results.sess_id";

if(strstr($_SERVER['REQUEST_URI'],"print_results.php")) $sess_id="";

if ($test_type == 1) {
	
	$DB->query("SELECT users.user_name, {$sess_res}, rating_results.rating, session_results.full_res,
				DATE_FORMAT(session_results.date_ses,'%d-%m-%Y'),
				session_results.start_time, session_results.time_ses {$sess_id}
				FROM users, session_results, rating_results 
				WHERE users.user_group = {$group} 
				AND session_results.test_id = {$test}
				AND rating_results.user_id = session_results.user_id
				AND rating_results.test_id = session_results.test_id
				AND users.user_id = session_results.user_id {$sort_where}");
}

else {
	$DB->query("SELECT users.user_name, {$sess_res}, null, session_results.full_res,
				DATE_FORMAT(session_results.date_ses,'%d-%m-%Y'),
				session_results.start_time, session_results.time_ses {$sess_id}
				FROM users, session_results 
				WHERE users.user_group = {$group} 
				AND session_results.test_id = {$test}
				AND users.user_id = session_results.user_id {$sort_where}");
}

?>