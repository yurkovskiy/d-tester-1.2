<?php

// e_quest.tpl - question editor

require_once("inc/dir.inc");
require_once("../inc/q_types.inc");
require_once("inc/functions.inc");

$q_query = $DB->query("SELECT * FROM questions WHERE question_id=$quest");
$q_row = $DB->fetch_row($q_query);

// replace special symbols to a code
$q_row[2] = dt_preg_replaceb(stripslashes(stripslashes($q_row[2])));

$test_id = $q_row[1];
$media_type = $q_row[4];

$a_query = $DB->query("SELECT * FROM answers WHERE aq_id=$quest ORDER BY ans_id ASC");
$a_row = $DB->fetch_row($a_query);
$ans_num = $DB->get_num_rows($a_query);
$amedia_type = $a_row[2];
$DB->free_result();

if(($media_type == SIMPLE_CHOICE_IMG)
	||($media_type == MULTI_CHOICE_IMG)
	||($media_type == INPUT_FIELD_IMG)
	||($media_type == TASK_FORM_IMG)
	||($media_type == NUMERICAL_IMG)
	||($amedia_type > 0)) {
	$TEST_DIR = $PARAM['TEST_BASE'].$test_id;
	$out = get_images_from_dir($TEST_DIR);
}
echo "<!-- Edit question [Form Structure] -->\n";
echo "<script src=\"js/pic.js\"></script>\n";
echo "<form onsubmit=\"return checkedForm(this)\" action=\"reg_task.php?action=update&question={$quest}&test_id={$test_id}\" name=\"reg_form\" method=\"POST\">\n";
echo "<input type=\"hidden\" name=\"ans_num\" value=\"{$ans_num}\">\n";
echo "<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"2\" class=\"tbl_view_frame\">\n";
echo "<tr><td align=\"center\" class=\"maintitle\" width=\"100%\" colspan=\"2\">{$lang['capt_reg_task']}</td></tr>\n";

echo "<tr><td align=\"left\" class=\"row4\" colspan=\"2\"><B>{$lang['tasks_href']}</B>{$lang['capt_media']}&nbsp;<b><font color=\"red\">{$lang['task_types'][$media_type]}</font></b>.";
echo "&nbsp;&nbsp;{$lang['q_group']}\n<select name=\"q_level\">\n";
for($i = 1;$i <= $PARAM['MAX_LEVEL'];$i++) {
	echo "<option value=\"{$i}\">{$i}</option>\n";
}
echo "</select>\n</td></tr>\n<script language=\"JavaScript\">document.forms['reg_form'].q_level.value={$q_row[3]}</script>\n";

if(($media_type == SIMPLE_CHOICE_IMG)
	||($media_type == MULTI_CHOICE_IMG)
	||($media_type == INPUT_FIELD_IMG)
	||($media_type == TASK_FORM_IMG)
	||($media_type == NUMERICAL_IMG)) {
	echo "<tr><td width=\"100%\" align=\"left\" class=\"row1\" colspan=\"2\">{$lang['capt_multi_res']}&nbsp;<b>[{$lang['tasks_href']}]</b>&nbsp;\n<select name=\"qm_file\">\n";
	echo "<option value=\"\">{$lang['choose_file']}</option>\n";
	while($element = each($out)) {
		echo $element['value'];
	}
	echo "</select>\n<script language=\"JavaScript\">document.forms['reg_form'].qm_file.value='{$q_row[5]}'</script>\n";
	echo "<input type=\"button\" class=\"button\" value=\"{$lang['show_pic']}\" onClick=\"show_pic('{$TEST_DIR}',reg_form.qm_file.value)\">\n</td></tr>\n";
	reset($out);
}
echo "<tr><td align=\"left\" class=\"row1\" width=\"100%\" colspan=\"2\"><textarea rows=\"5\" cols=\"80\" name=\"q_text\">{$q_row[2]}</textarea></td></tr>\n";

$a_query = $DB->query("SELECT * FROM answers WHERE aq_id='$quest' ORDER BY ans_id ASC");
for($i = 0;$i < $ans_num;$i++) {
	$a_row = $DB->fetch_row($a_query);
	
	// replace special symbols to a code
	$a_row[4] = dt_preg_replaceb(stripslashes(stripslashes($a_row[4])));
	
	$a_name = "a_body_".$i;
	$a_bool_name = "a_true_".$i;
	$a_id = "aid_".$i;
	echo "<input type=\"hidden\" name=\"{$a_id}\" value=\"{$a_row[5]}\">\n";
	echo "<tr><td class=\"row3\" width=\"85%\" align=\"left\">";printf($lang['ans_favorite'],$i+1);
	echo "</td><td class=\"row3\" width=\"15%\" align=\"left\">{$lang['capt_bool_f']}</td></tr>\n";
	echo "<tr><td class=\"row1\" align=\"left\"><textarea rows=\"2\" cols=\"70\" name=\"{$a_name}\">{$a_row[4]}</textarea></td>";
	echo "<td class=\"row1\" align=\"left\">\n<select name=\"{$a_bool_name}\">\n<option value=\"2\">{$lang['sel_yes']}</option>\n<option value=\"1\">{$lang['sel_no']}</option>\n</select>\n</td></tr>\n";
	echo "<script language=\"JavaScript\">document.forms['reg_form'].{$a_bool_name}.value={$a_row[1]}</script>\n";
	if($amedia_type > 0) {
		$amd_name="amedia_file_".$i;
		echo "<tr><td align=\"left\" colspan=\"2\" class=\"row3\">{$lang['capt_multi_res']}&nbsp;[";printf($lang['ans_favorite'],$i+1);
		echo "]&nbsp;<select name=\"{$amd_name}\">\n<option value=\"\">{$lang['choose_file']}</option>\n";
		while($element=each($out))
		{
			echo $element['value'];
		}
		echo "</select>\n<script language=\"JavaScript\">document.forms['reg_form'].{$amd_name}.value='{$a_row[3]}'</script>\n";
		echo "<input type=\"button\" class=\"button\" value=\"{$lang['show_pic']}\" onClick=\"show_pic('{$TEST_DIR}',reg_form.{$amd_name}.value)\">\n</td></tr>\n";
		reset($out);
	}
	echo "\n";
}
$DB->free_result();
unset($out);
$button_name = $lang['reg_button']." ".$lang['tasks_href'];
echo "<tr><td align=\"center\" class=\"darkrow2\" width=\"100%\" colspan=\"2\"><input type=\"submit\" class=\"button\" name=\"register\" value=\"{$button_name}\"></td></tr>\n";
echo "</table>\n</form>\n";

?>