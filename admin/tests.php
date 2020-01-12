<?php

require_once("req.inc");

$subject = $_GET['subject'];

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
    &&($subject != $_SESSION['SUBJ_ID'])) {
	header("Location: index.php");
}

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
    &&($_SESSION['SB_READ'] == "N")) {
	header("Location: main.php");
}

page_begin($lang['tests_href']);

$lang['del_confirm'].=$lang['capt_test'];

$DB->query("SELECT subject_name FROM subjects WHERE subject_id='$subject'");
$DB->fetch_row();

?>
<!-- Checking test type functions: [changing CSS TD style]-->
<script type="text/javascript">
function test_type(tid, tt) {
	var td_ids;
	var cN;

	td_ids = ["a[", "b[", "c[", "d[", "e[", "m1[", "m2[", "m3[", "m4["];

	
	switch (tt) {
		
		case 1: {
			cN = 'exam';
			break;
		}
		
		case 2: {
			cN = 'yrow';
			break;			
		}
		
		default: {
			cN = 'row1';
			break;
		}
	}
				
	for (i = 0;i < td_ids.length;i++) {
		td_ids[i] = td_ids[i] + tid + "]";
		document.getElementById(td_ids[i]).className = cN;
	}
}
</script>
<!-- / -->

<?php

echo "<TABLE width=\"100%\" border=\"0\" align=\"center\" class=\"tbl_view_frame\" cellpadding=\"3\" cellspacing=\"4\">\n";
echo "<TR><TH width=\"100%\" colspan=\"9\" class=\"maintitle\" align=\"center\">{$lang['tests_href_for']}&nbsp;{$DB->record_row[0]}</TH></TR>\n";
echo "<TR>\n<TH class=\"row3\" align=\"center\" width=\"7%\"><B>{$lang['capt_num']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"left\" width=\"35%\"><B>{$lang['test_name']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"8%\"><B>{$lang['test_how_tasks']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"10%\"><B>{$lang['test_time']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"8%\"><B>{$lang['show_test_client']}</B></TH>\n";
echo "<TH class=\"row3\" align=\"center\" width=\"32%\" colspan=\"4\"><B>{$lang['capt_manage']}</B></TH>\n</TR>\n";

$DB->query("SELECT test_id, test_name, how_tasks, test_time, enabled, test_type FROM tests WHERE test_subject_id='$subject' ORDER BY test_id");
$td_ids = array("a", "b", "c", "d", "e");
while($DB->fetch_row())
{
	echo "<TR>\n";
	for($i = 0;$i < $DB->get_fields_num() - 1;$i++)
	{
		if($i == 1) {
			echo "<TD class=\"row1\" align=\"left\" id=\"{$td_ids[$i]}[{$DB->record_row[0]}]\"><a href=\"\" onClick=\"if (confirm('{$lang['print_test']}')){window.open('print_test_simple.php?test_id={$DB->record_row[0]}','newWindow');return false}else{return false}\">{$DB->record_row[$i]}</a></TD>\n";
			continue;
		}
		echo "<TD class=\"row1\" align=\"center\" id=\"{$td_ids[$i]}[{$DB->record_row[0]}]\">{$DB->record_row[$i]}</TD>\n";
	}
	echo "<TD class=\"row1\" align=\"center\" id=\"m1[{$DB->record_row[0]}]\"><a href=\"edit.php?action=tests&subj={$subject}&tst={$DB->record_row[0]}\">{$lang['edit_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\" id=\"m2[{$DB->record_row[0]}]\"><a href=\"tasks.php?tst={$DB->record_row[0]}\">{$lang['tasks_href']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\" id=\"m3[{$DB->record_row[0]}]\"><a href=\"test_details.php?tst={$DB->record_row[0]}\">{$lang['test_details']}</a></TD>\n";
	echo "<TD class=\"row1\" align=\"center\" id=\"m4[{$DB->record_row[0]}]\"><a href=\"\" onClick=\"if (confirm('{$lang['del_confirm']}')){window.open('del.php?what=test&subj={$subject}&ID={$DB->record_row[0]}','mainFrame');return false}else{return false}\">{$lang['del_button']}</a></TD>\n";
	echo "</TR>\n";
	?>
	<script type="text/javascript">
	test_type(<?php echo $DB->record_row[0]?>, <?php echo $DB->record_row[5]?>);
	</script>
	<?php
}
echo "<TR><TD colspan=\"9\" class=\"darkrow2\">&nbsp;</TD></TR>\n</TABLE>\n<BR>\n";
echo "<DIV align=\"center\"><a href=\"add.php?action=new_test&subj={$subject}\" title=\"{$lang['add_new_test']}\">{$lang['add_new_test']}</a></DIV>\n<BR>\n";
echo "<DIV align=\"center\" class=\"copyright\">{$lang['test_warning']}</DIV>\n";
page_end();
?>