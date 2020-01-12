<?php

// print_test.php - Версія для друку тестового блоку завдань
// Copyright (c) 2006 by Yuriy Bezgachnyuk, IF, Ukraine
// Update: 19.04.2006 21:47 GMT+02:00

require_once("req.inc");
require_once("../inc/q_types.inc");

$test_id = $_GET['test_id'];
$cur_date = date("d/m/Y H:i:s");

page_begin($lang['capt_tasks']);

$DB->query("SELECT subjects.subject_name FROM subjects, tests WHERE tests.test_id='$test_id' AND subjects.subject_id=tests.test_subject_id");
$DB->fetch_row();
$subject_name = $DB->record_row[0];
$DB->free_result();

$DB->query("SELECT test_name FROM tests WHERE test_id='$test_id'");
$DB->fetch_row();
$test_name = $DB->record_row[0];
$DB->free_result();

echo "<!-- Order Structure -->\n";
echo "<div align=\"center\" class=\"tbl_view_frame\"><b>{$lang['capt_tasks']}</b></div><br>\n";
echo "<table width=\"95%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['test_subject']}:</b>&nbsp;&nbsp;{$subject_name}</td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_test']}:</b>&nbsp;&nbsp;{$test_name}</td></tr>\n";
echo "</table><br>\n\n";

echo "<!-- Questions Information -->\n";

$test_details = $DB->query("SELECT level_id, level_rate FROM test_details WHERE test_id='$test_id' ORDER BY level_id");
if($DB->get_num_rows() != 0)
{
    while($td_row = $DB->fetch_row($test_details))
    {
        $level = $td_row[0];
        $level_rate = $td_row[1];
        echo "<table width=\"95%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"tbl_view_frame\">\n<tr><td align=\"left\">{$lang['q_group']}&nbsp;{$level}&nbsp;[{$lang['level_rate']}]:&nbsp;{$level_rate}</td></tr>\n</table>\n<br>\n\n";
        $question = $DB->query("SELECT * FROM questions WHERE q_test_id='$test_id' AND q_level='$level' ORDER BY question_id");
        $count = 1;
        while($q_row = $DB->fetch_row($question))
        {
            for($k = 0;$k < $DB->get_fields_num();$k++) $q_row[$k] = stripslashes(stripslashes($q_row[$k]));
            echo "<!-- Question #{$count} -->\n";
            echo "<table width=\"95%\" align=\"center\" border=\"1\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
            echo "<tr><td width=\"100%\" align=\"left\" colspan=\"2\">&nbsp;<b>{$lang['tasks_href']}</b>&nbsp;{$count}&nbsp;[{$q_row[0]}],<b>{$lang['mmedia_res_type']}</b>&nbsp;{$lang['task_types'][$q_row[4]]}</td></tr>\n<tr>\n";
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
            echo "</tr>\n";
            $ans_query = $DB->query("SELECT * FROM answers WHERE aq_id='$q_row[0]' ORDER BY ans_id");
            $ans_counter = 1;
            while($ans_row = $DB->fetch_row($ans_query)) {
                echo "<!-- Answer Information [Favorite: {$ans_counter}] -->\n";
                echo "<tr>\n<td align=\"left\" width=\"100%\" colspan=\"2\" valign=\"middle\" class=\"row3\">";
                printf($lang['ans_favorite'],$ans_counter);echo "</td>\n</tr>\n";
                echo "<tr>\n";
                if($ans_row[2] != "0") {
                    $a_media_file = $PARAM['TEST_BASE_URL'].$test_id."/".$ans_row[3];
                    echo "<td width=\"80%\" align=\"center\"><img src=\"{$a_media_file}\"><br>{$ans_row[4]}</td>\n";
                }

                else {
                    echo "<td width=\"80%\" align=\"center\">{$ans_row[4]}</td>\n";
                }

                if($ans_row[1] == "2") {
                    echo "<td width=\"20%\" align=\"center\">{$lang['true_ans']}</td>\n";
                }
                if($ans_row[1] == "1") {
                    echo "<td width=\"20%\" align=\"center\">{$lang['false_ans']}</td>\n";
                }
                echo "</tr>\n";
                $ans_counter++;
            }
            echo "</table>\n<br><br>\n\n";
            $count++;
        }
    }
}

echo "<div align=\"center\" class=\"copyright\">{$lang['print_footer']}<br><b>{$cur_date}</b></div>\n";

page_end();

?>