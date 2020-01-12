<?PHP

/*
test_details.php - edit tests questions level information
*/

require_once("req.inc");

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
    &&($_SESSION['SB_READ'] == "N")) {
    header("Location: main.php");
}

$tst = $_GET['tst'];

page_begin($lang['capt_test_details']);
$DB->query("SELECT test_name FROM tests WHERE test_id='$tst'");
$DB->fetch_row();
$test_name = $DB->record_row[0];
$DB->free_result();

echo "<TABLE align=\"center\" border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"4\" class=\"tbl_view_frame\">\n";
echo "<TR><TD width=\"100%\" colspan=\"6\" class=\"maintitle\" align=\"center\">{$lang['capt_test_details']}&nbsp;{$test_name}</TD></TR>\n";
echo "<TR>\n<TH align=\"center\" width=\"10%\" class=\"row4\">{$lang['capt_num']}</TH>\n";
echo "<TH align=\"center\" width=\"15%\" class=\"row4\">{$lang['level_id']}</TH>\n";
echo "<TH align=\"center\" width=\"20%\" class=\"row4\">{$lang['tasks_num']}</TH>\n";
echo "<TH align=\"center\" width=\"25%\" class=\"row4\">{$lang['level_rate']}</TH>\n";
echo "<TH align=\"center\" width=\"30%\" class=\"row4\" colspan=\"2\">{$lang['capt_manage']}</TH>\n</TR>\n";

$test_rate = 0;
$test_tasks = 0;
$DB->query("SELECT id, level_id, level_tasks, level_rate FROM test_details WHERE test_id='$tst' ORDER BY id");
while($DB->fetch_row()) {
    $test_rate += ($DB->record_row[2] * $DB->record_row[3]);
    $test_tasks += $DB->record_row[2];
    echo "<TR>\n";
    for($i = 0;$i < $DB->get_fields_num();$i++) {
        echo "<TD align=\"center\" class=\"row1\"><B>{$DB->record_row[$i]}</B></TD>\n";
    }
    echo "<TD align=\"center\" class=\"row1\"><a href=\"edit.php?action=test_details&test={$tst}&ID={$DB->record_row[0]}\">{$lang['edit_href']}</a></TD>\n";
    echo "<TD align=\"center\" class=\"row1\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=test_details&test={$tst}&ID={$DB->record_row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n";
    echo "</TR>\n";
}
echo "<TR><TD align=\"left\" colspan=\"6\" class=\"row2\">{$lang['test_rate']}&nbsp;{$test_rate}</TD></TR>\n";
echo "<TR><TD align=\"left\" colspan=\"6\" class=\"row2\">{$lang['test_tasks']}&nbsp;{$test_tasks}</TD></TR>\n";
echo "<TR><TD colspan=\"6\" width=\"100%\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=test_detail&test={$tst}\" title=\"{$lang['add_new_test_detail']}\">{$lang['add_new_test_detail']}</a></DIV>\n";
page_end();
?>