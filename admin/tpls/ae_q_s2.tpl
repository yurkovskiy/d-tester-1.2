<!-- ADD new task Form structure -->
<?php

/**
 * @name ae_q_s2.tpl - question registration unit [step 2]. Register: answers structure & all multimedia resources if needed
 * @author Yuriy Bezgachnyuk
 * @copyright Yuriy Bezgachnyuk, IF, Ukraine
 * @version 1.1 RC1
 */

require_once("inc/dir.inc"); // directory special functions [get_images_from_dir]
require_once("../inc/q_types.inc"); // question types constant
require_once("inc/functions.inc"); // special 'preg_replace' functions

// Generating Short Variables
$test_id = intval($_POST['test_id']);
$media_type = intval($_POST['media_type']);
$q_level = intval($_POST['q_level']);

// preg_replace special symbols
$q_text = dt_preg_replaceb(dt_preg_replace(stripslashes($_POST['q_text'])));

$ans_num = intval($_POST['ans_num']);
$amedia_type = intval($_POST['amedia_type']);

$TEST_DIR = $PARAM['TEST_BASE'].$test_id;

if(($media_type == SIMPLE_CHOICE_IMG)
	||($media_type == MULTI_CHOICE_IMG)
	||($media_type == INPUT_FIELD_IMG)
	||($media_type == TASK_FORM_IMG)
	||($media_type == NUMERICAL_IMG)
	||($amedia_type > 0))

	$out = get_images_from_dir($TEST_DIR);

if(($media_type >= INPUT_FIELD_IMG)
	&&($media_type<=NUMERICAL_IMG)) $amedia_type = 0;
if(($media_type >= NUMERICAL)
	&&($media_type<=NUMERICAL_IMG)) $ans_num = 2;
?>
<form onsubmit="return checkedForm(this)" action="reg_task.php?action=new&step=2" name="q2" method="POST">
<input type="hidden" name="test_id" value="<?php echo $test_id?>">
<input type="hidden" name="q_type" value="<?php echo $media_type?>">
<input type="hidden" name="ans_num" value="<?php echo $ans_num?>">
<input type="hidden" name="amedia_type" value="<?php echo $amedia_type?>">
<?php
echo "<input type=\"hidden\" name=\"q_level\" value=\"{$q_level}\">\n<input type=\"hidden\" name=\"q_text\" value=\"{$q_text}\">\n";
?>
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="tbl_view_frame">
<tr><td align="center" class="maintitle" width="100%" colspan="2"><?php echo $lang['capt_reg_task']?></td></tr>
<script src="js/pic.js"></script>

<?php
if(($media_type == SIMPLE_CHOICE_IMG)
	||($media_type == MULTI_CHOICE_IMG)
	||($media_type == INPUT_FIELD_IMG)
	||($media_type == TASK_FORM_IMG)
	||($media_type == NUMERICAL_IMG)) {
	echo "<tr><td width=\"100%\" align=\"left\" class=\"row1\" colspan=\"2\">{$lang['capt_multi_res']}&nbsp;<b>[{$lang['tasks_href']}]</b>&nbsp;\n<select name=\"qm_file\">\n";
	echo "<option value=\"\">{$lang['choose_file']}</option>\n";
	
	/**
	 * Showing image selector
	 */
	while($element = each($out)) {
		echo $element['value'];
	}
	echo "</select>\n<input type=\"button\" class=\"button\" value=\"{$lang['show_pic']}\" onClick=\"show_pic('{$TEST_DIR}',document.forms[0].qm_file.value)\">\n</td></tr>";
	reset($out);
	}

for($i = 0;$i < $ans_num;$i++) {
	// Creating form variable names and JS functions names
	
	$a_name = "a_body_".$i;
	$a_bool_name = "a_true_".$i;
	$add_f_name = "add_space_".$i;
	$add_bt_name = "space_btn_".$i;
	echo "<script language=\"JavaScript\">\nfunction {$add_f_name}()\n{\ndocument.forms[0].{$a_name}.value+='&nbsp;'\n}\n</script>\n";
	echo "<tr><td class=\"row3\" width=\"85%\" align=\"left\">";printf($lang['ans_favorite'],$i+1);
	echo "</td><td class=\"row3\" width=\"15%\" align=\"left\">{$lang['capt_bool_f']}</td></tr>\n";
	echo "<tr><td class=\"row1\" align=\"left\"><textarea rows=\"2\" cols=\"70\" name=\"{$a_name}\"></textarea>&nbsp;<input type=\"button\" name=\"{$add_bt_name}\" value=\"SPACE\" class=\"button\" onclick=\"{$add_f_name}();\"></td>";
	echo "<td class=\"row1\" align=\"left\">\n<select name=\"{$a_bool_name}\">\n<option value=\"2\">{$lang['sel_yes']}</option>\n<option value=\"1\">{$lang['sel_no']}</option>\n</select>\n</td></tr>\n";

	if($i != 0) {
		echo "<script language=\"JavaScript\">document.forms[0].{$a_bool_name}.value=1</script>\n";
	}

	if($amedia_type > 0) {
		$amd_name = "amedia_file_".$i;
		echo "<tr><td align=\"left\" colspan=\"2\" class=\"row3\">{$lang['capt_multi_res']}&nbsp;[";printf($lang['ans_favorite'],$i+1);
		echo "]&nbsp;<select name=\"{$amd_name}\">\n<option value=\"\">{$lang['choose_file']}</option>\n";
		
		/**
		 * Showing image selector
		 */
		while($element = each($out)) {
			echo $element['value'];
		}
		echo "</select>\n<input type=\"button\" class=\"button\" value=\"{$lang['show_pic']}\" onClick=\"show_pic('{$TEST_DIR}',document.forms[0].{$amd_name}.value)\">\n</td></tr>\n";
		reset($out);
	}
	echo "\n";
}
$button_name = $lang['reg_button']." ".$lang['tasks_href'];
echo "<tr><td align=\"center\" class=\"darkrow2\" width=\"100%\" colspan=\"2\"><input type=\"submit\" class=\"button\" name=\"register\" value=\"{$button_name}\"></td></tr>\n";
echo "</table>\n</form>\n";