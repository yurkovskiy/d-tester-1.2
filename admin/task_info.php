<?PHP

// task_info.php - сценарій для видачі детальної інформації про тестове завдання

require_once("req.inc");
require_once("../inc/q_types.inc");

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
	&&($_SESSION['SB_READ'] == "N")) {
		header("Location: index.php");
}

$task_id = $_GET['id'];

if($_SESSION['adm_priv'] == SUBJECT_MAN) {
    $DB->query("SELECT tests.test_subject_id FROM tests, questions
				WHERE question_id='".$task_id."' AND tests.test_id=q_test_id AND test_subject_id='".$_SESSION['SUBJ_ID']."'");
    if($DB->get_num_rows() == 0) {
        header("Location: index.php");
    }
}

page_begin($lang['capt_task_info']);

$DB->query("SELECT * FROM questions WHERE question_id='".$task_id."'");
$row = $DB->fetch_row();
for($i = 0;$i < $DB->get_fields_num();$i++) $row[$i] = stripslashes(stripslashes($row[$i]));

echo "<TABLE align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"3\" cellspacing=\"1\" class=\"tbl_view_frame\">\n";
echo "<TR><TD align=\"center\" class=\"maintitle\">{$lang['capt_task_info']}&nbsp;&nbsp;&nbsp;[{$lang['q_group']}&nbsp;{$row[3]},
	  &nbsp;{$lang['capt_reg_num']}&nbsp;{$row[0]},&nbsp;{$lang['mmedia_res_type']}&nbsp;{$lang['task_types'][$row[4]]}]</TD></TR>\n";
echo "<TR><TD align=\"center\" valign=\"middle\" class=\"darkrow3\"><font color=\"Red\"><code><b>{$row[2]}</b></code></font></TD></TR>\n";

if(($row[4] == SIMPLE_CHOICE_IMG)
	||($row[4] == MULTI_CHOICE_IMG)
	||($row[4] == INPUT_FIELD_IMG)
	||($row[4] == 7)
	||($row[4] == NUMERICAL_IMG)) {
		$q_media_file = $PARAM['TEST_BASE_URL'].$row[1]."/".$row[5];
    	echo "<TR><TD align=\"center\" colspan=\"2\"><img src=\"{$q_media_file}\"></TD></TR>\n</TABLE>\n\n";
}

$DB->query("SELECT * FROM answers WHERE aq_id='$task_id' ORDER BY ans_id ASC");
$ans_counter = 1;
while($ans_row = $DB->fetch_row()) {
    $ans_row[4] = stripslashes($ans_row[4]);
    echo "<TABLE align=\"center\" width=\"100%\" border=\"1\" cellpadding=\"0\" cellspacing=\"1\" class=\"tbl_view_frame\">\n";
    echo "<TR><TD align=\"left\" width=\"100%\" colspan=\"2\" valign=\"middle\" class=\"row3\">";
    printf($lang['ans_favorite'],$ans_counter);echo "</TD></TR>\n";

    echo "<TR>\n";
    if($ans_row[2] != "0") {
        $a_media_file = $PARAM['TEST_BASE_URL'].$row[1]."/".$ans_row[3];
        echo "<TD width=\"80%\" align=\"center\"><img src=\"{$a_media_file}\"></BR>{$ans_row[4]}</TD>\n";
    }

    else {
        echo "<TD width=\"80%\" align=\"center\">{$ans_row[4]}</TD>\n";
    }

    if($ans_row[1] == "2") {
        echo "<TD width=\"20%\" align=\"center\">{$lang['true_ans']}</TD>\n";
    }
    if($ans_row[1] == "1") {
        echo "<TD width=\"20%\" align=\"center\">{$lang['false_ans']}</TD>\n";
    }
    echo "</TR>\n";

    echo "</TABLE>\n";
    $ans_counter++;
}

page_end();

?>