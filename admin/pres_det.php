<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2 RC1
 * @name Full order about user testing [Print Version]
 * @author Yuriy Bezgachnyuk
 * @copyright Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date: 14/07/2006
 * Last update: 01/01/2008 19:49 GMT +02:00
 * 
 * All rights reserved
 */

// pres_det.php - Детальні результати тестування [Версія для друку]
// Copyright (c) Yuriy Bezgachnyuk, IF, Ukraine
// Release: 14.07.2006 12:36 GMT +02:00

require_once("req.inc");
require_once("inc/timer.inc");
require_once("inc/dia_func.inc");
require_once("../inc/q_types.inc");

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
&&($_SESSION['SB_READ'] == "N")) {
    header("Location: index.php");
}

$sess_id = $_GET['sess_id'];

if(isset($_SESSION['SUBJ_ID'])
&&($_SESSION['SB_READ']=="Y")) {
    $DB->query("SELECT tests.test_subject_id FROM tests, session_results WHERE tests.test_id=session_results.test_id");
    $DB->fetch_row();
    if($_SESSION['SUBJ_ID'] != $DB->record_row[0]) {
        $DB->free_result();
        Show_Message("ACCESS_DENIED_TO_THIS_AREA");
    }
}


$ans_ = array(0 => $lang['false_ans'], 1 => $lang['true_ans']);

$DB->query("SELECT users.user_name, groups.group_name, tests.test_name, tests.test_id, DATE_FORMAT(session_results.date_ses,'%d-%m-%Y'), session_results.start_time,
				   session_results.time_ses, session_results.result, session_results.questions, session_results.true_answers, session_results.user_ans, subjects.subject_name
			FROM users, groups, tests, session_results, subjects
			WHERE session_results.sess_id='$sess_id' AND users.user_id=session_results.user_id
			AND groups.group_id=users.user_group AND tests.test_id=session_results.test_id
			AND subjects.subject_id=tests.test_subject_id");

$DB->fetch_row();

// Create Main Variables
$user_name = $DB->record_row[0];
$group_name = $DB->record_row[1];
$test_name = $DB->record_row[2];
$subject_name = $DB->record_row[11];
$test_id = $DB->record_row[3];
$date_sess = $DB->record_row[4];
$time_sess = $DB->record_row[6];
$test_time = TimeToStr(sub_time($time_sess,$DB->record_row[5]));
$result = $DB->record_row[7];
$questions = extract_aq($DB->record_row[8]); // ID заданих завдань
$ans_type = extract_aq($DB->record_row[9]); // Ознака правильної/неправильної відповіді
$user_ans = extract_aq($DB->record_row[10]); // ID відповідей користувача
$cur_date = date("d/m/Y H:i:s"); // Дата генерації звіту

$DB->free_result();

$test_rate = get_test_rate($test_id);
$quality = round(($result/$test_rate),2) * 100;

$q_type = SIMPLE_CHOICE;

page_begin($lang['rpv_order']);

// Generate Order Structure

echo "<!-- Order Structure -->\n";
echo "<div align=\"center\" class=\"tbl_view_frame\"><b>{$lang['rpv_order']}</b></div><br>\n";
echo "<table width=\"98%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['test_subject']}:</b>&nbsp;&nbsp;{$subject_name}</td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_test']}:</b>&nbsp;&nbsp;{$test_name}</td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\">{$lang['test_rate']}&nbsp;&nbsp;{$test_rate}</td></tr>\n";
echo "</table><br>\n\n";

echo "<!-- Session Information -->\n";
echo "<table width=\"98%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_student']}:</b>&nbsp;&nbsp;{$user_name}&nbsp;[<b>{$lang['capt_group']}</b>&nbsp;{$group_name}]</td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_date']}:&nbsp;&nbsp;{$date_sess}&nbsp;&nbsp;{$time_sess}</b></td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['test_time']}:&nbsp;&nbsp;{$test_time}</b></td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_mark']}:</b>&nbsp;&nbsp;{$result}&nbsp;(<b>{$lang['capt_quality']}:</b>&nbsp;{$quality}%)</td></tr>\n";
echo "</table><br>\n\n";

echo "<!-- Questions Information -->\n";
echo "<div align=\"center\">{$lang['capt_quests']}:</div>\n<br>\n\n";

for($i = 1;$i < sizeof($questions);$i++) {
    $q_query=$DB->query("SELECT * FROM questions WHERE question_id='".$questions[$i]."'");
    while($q_row = $DB->fetch_row($q_query))
    {
        for($k = 0;$k < $DB->get_fields_num();$k++) $q_row[$k] = stripslashes(stripslashes($q_row[$k]));
        $qq_rate = $DB->query("SELECT level_rate FROM test_details WHERE test_id='$test_id' AND level_id='".$q_row[3]."'");
        if($DB->get_num_rows() == 0) $q_rate = round(($test_rate/(sizeof($questions)-1)),2);
        else {
            $DB->fetch_row($qq_rate);
            $q_rate = $DB->record_row[0];
            $DB->free_result();
        }

        echo "<!-- Question #{$i} -->\n";
        echo "<table width=\"98%\" align=\"center\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
        echo "<tr><td align=\"left\" colspan=\"2\">&nbsp;<b>{$lang['tasks_href']}</b>&nbsp;{$i}&nbsp;[{$q_row[0]}].&nbsp;&nbsp;<b>{$lang['rpv_q_mark']}</b>&nbsp;{$q_rate}&nbsp;&nbsp;&nbsp;&nbsp;<b>{$lang['ans_id']}:</b>&nbsp;{$ans_[$ans_type[$i]]}</td></tr>\n<tr>\n";
        if(($q_row[4] == SIMPLE_CHOICE_IMG)
        ||($q_row[4] == MULTI_CHOICE_IMG)
        ||($q_row[4] == INPUT_FIELD_IMG)
        ||($q_row[4] == 7)
        ||($q_row[4] == NUMERICAL_IMG)) {
            $source = $PARAM['TEST_BASE_URL'].$test_id."/".$q_row[5];
            echo "<td align=\"center\" width=\"100%\" colspan=\"2\"><font color=\"Red\"><code><b>{$q_row[2]}</b></code></font><br><img src=\"{$source}\" border=\"0\"></td>\n";
        }
        else {
            echo "<td align=\"center\" width=\"100%\" colspan=\"2\"><font color=\"Red\"><code><b>{$q_row[2]}</b></code></font></td>\n";
        }
        $q_type = $q_row[4];
    }

    echo "</tr>\n";

    echo "<!-- Answer Information -->\n";
    echo "<tr><td align=\"center\" width=\"50%\"><b>{$lang['capt_bool']}</b></td><td align=\"center\" width=\"50%\"><b>{$lang['ans_id']}</b></td></tr>\n";

    $DB->query("SELECT * FROM answers WHERE ans_true='2' AND aq_id='$questions[$i]'");
    $out="<tr>\n";
    $count = 1;
    while ($DB->fetch_row())
    {
        if($count > 1) $out.="<br>\n";
        if($count == 1) $out.="<td align=\"center\" width=\"50%\">\n";

        for($k = 0;$k < $DB->get_fields_num();$k++) $DB->record_row[$k] = stripslashes(stripslashes($DB->record_row[$k]));

        if($DB->record_row[2] == "1") {
            $source=$PARAM['TEST_BASE_URL'].$test_id."/".$DB->record_row[3];
            $out.="<font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font><br><img src=\"{$source}\" border=\"0\">\n";
        }
        else {
            $out.="<font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font>\n";
        }
        $count++;
    }
    $out.="</td>\n";
    echo $out;

    $DB->free_result();

    if(($q_type == MULTI_CHOICE)
    ||($q_type == MULTI_CHOICE_IMG)) {
        $res = explode("<",$user_ans[$i]);
        echo "<td align=\"center\" width=\"50%\">\n";
        for($c = 1;$c < sizeof($res);$c++) {
            $DB->query("SELECT * FROM answers WHERE ans_id='".$res[$c]."'");
            $DB->fetch_row();
            for($cc = 0;$cc < $DB->get_fields_num();$cc++) $DB->record_row[$cc] = stripslashes(stripslashes($DB->record_row[$cc]));

            if($DB->record_row[2] == "1") {
                $source = $PARAM['TEST_BASE_URL'].$test_id."/".$DB->record_row[3];
                echo "<font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font><br><img src=\"{$source}\" border=\"0\">\n<br>\n";
            }
            else {
                echo "<font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font>\n<br>\n";
            }
        }
        echo "</td>\n</tr>\n";
    }

    else {
        $DB->query("SELECT * FROM answers WHERE ans_id='".$user_ans[$i]."'");
        $DB->fetch_row();
        for($k = 0;$k < $DB->get_fields_num();$k++) $DB->record_row[$k] = stripslashes(stripslashes($DB->record_row[$k]));

        if(strlen($DB->record_row[4]) < 1) $DB->record_row[4] = "&nbsp;";

        if($DB->record_row[2] == "1") {
            $source = $PARAM['TEST_BASE_URL'].$test_id."/".$DB->record_row[3];
            echo "<td align=\"center\" width=\"50%\"><font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font><br><img src=\"{$source}\" border=\"0\"></td>\n";
        }
        else {
            echo "<td align=\"center\" width=\"50%\"><font color=\"Black\"><code><b>{$DB->record_row[4]}</b></code></font></td>\n";
        }
        echo "</tr>\n";

        $DB->free_result();
    }

    echo "</table>\n<br><br><br>\n\n";
}

echo "<div align=\"center\" class=\"copyright\">{$lang['print_footer']}<br><b>{$cur_date}</b></div>\n";

page_end();

?>