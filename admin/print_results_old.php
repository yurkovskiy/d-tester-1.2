<?PHP

// print_results.php - Show print version results after query

require_once("req.inc");
require_once("inc/timer.inc");

page_begin($lang['capt_resume']);

$test = $_GET['test'];
$group = $_GET['group'];
$sort = $_GET['sort'];
$sort_order = $_GET['sort_order'];
$avg_enable = $_GET['avg_enable'];
$cur_date = date("d/m/Y H:i:s");

require_once("inc/res_base.inc");

echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TD align=\"center\" bgcolor=\"#CCCCCC\"><B>{$lang['capt_resume']}</B></TD></TR>\n";
echo "<TR><TD>{$lang['test_subject']}:&nbsp;<B>{$subj}</B></TD></TR>\n";
echo "<TR><TD>{$lang['capt_test']}:&nbsp;<B>{$tes}</B></TD></TR>\n";
echo "<TR><TD>{$lang['test_time']}:&nbsp;<B>{$tes_time}</B>&nbsp;&nbsp;&nbsp;{$lang['test_rate']}&nbsp;<B>{$test_rate}</B></TD></TR>\n";
echo "<TR><TD>{$lang['capt_group']}:&nbsp;<B>{$grp}</B></TD></TR>\n";
echo "</TABLE>\n<HR>\n";
echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"1\" cellspacing=\"4\">\n";
echo "<TR>\n<TH align=\"center\" width=\"8%\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH align=\"left\" width=\"25%\"><B>{$lang['capt_student']}</B></TH>\n";
echo "<TH align=\"center\" width=\"12%\"><B>{$lang['capt_mark']}</B></TH>\n";
echo "<TH align=\"center\" width=\"10%\"><B>{$lang['capt_quality']}</B></TH>\n";
echo "<TH align=\"center\" width=\"15%\"><B>{$lang['capt_date']}</B></TH>\n";
echo "<TH align=\"center\" width=\"15%\"><B>{$lang['test_time']}</B></TH>\n";
echo "<TH align=\"center\" width=\"8%\"><B>{$lang['capt_time']}</B></TH>\n</TR>\n";

global $hut;
$group_res = 0;
$k = 1;
while($row = $DB->fetch_row())
{
    echo "<TR>\n<TD align=\"center\">{$k}</TD>\n";
    for($i = 0;$i < $DB->get_fields_num();$i++)
    {
        if($i == 0) {$align = "left";} if($i != 0) {$align = "center";}
        if($i == 2) {
            $res_quality = round(($row[1]/$test_rate),2); $res_percent = 100 * $res_quality;
            echo "<TD align=\"center\">{$res_percent}% </TD>";
        }
        if($i==3) {
            $row[$i] = TimeToStr(sub_time($row[$i + 1], $row[$i]));
        }
        echo "<TD align=\"{$align}\">{$row[$i]}</TD>\n";
        if($i == 1) $group_res+=$row[$i];
    }
    $k++;
    $hut = $k;
    echo "</TR>\n";
}
$DB->free_result();
$hut = $hut - 1;
if($hut < 0) {
	$hut = 0; 
	$avg_res = 0; 
	$avg_q = 0;
}
if($hut != 0) {
	$avg_res = round($group_res/$hut,3); 
	$avg_q = 100 * round($avg_res / $test_rate, 3);
}

echo "</TABLE>\n";
echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"1\" cellspacing=\"4\">\n";
echo "<TR><TH width=\"100%\" colspan=\"6\" align=\"center\" bgcolor=\"#CCCCCC\"><B>{$lang['capt_common_res']}</B></TH></TR>\n";
echo "<TR><TD width=\"35%\">{$lang['avg_test_result']}</TD>\n";
echo "<TD width=\"30%\"><B>{$avg_res}&nbsp;({$avg_q}%)</B></TD>\n";
echo "<TD width=\"25%\">{$lang['how_users_tested']}</TD>\n";
echo "<TD width=\"10%\"><B>{$hut}</B></TD>\n</TR>\n";
echo "</TABLE>\n";
echo "<DIV align=\"center\" class=\"copyright\">{$lang['print_footer']}<BR><b>{$cur_date}</b></DIV>\n";
page_end();
?>