<!-- Add new question [Form structure] -->
<form onsubmit="return checkedForm(this)" action="reg_task.php?action=new&step=1" name="q1" method="POST">
<input type="hidden" name="test_id" value="<?php echo $_GET['test']?>">
<table width="100%" border="0" cellpadding="3" cellspacing="2" class="tbl_view_frame">
<tr><td align="center" class="maintitle"><?php echo $lang['capt_reg_task']?></td></tr>
<tr><td align="left" class="row4"><B><?php echo $lang['tasks_href']?></B><?php echo $lang['capt_media']?>
<select name="media_type">
<?php
for($t=0;$t<sizeof($lang['task_types']);$t++)
{
	echo "<option value=\"{$t}\">{$lang['task_types'][$t]}</option>\n";
}
?>
</select>]
&nbsp;<?php echo $lang['q_group']?>
<select name="q_level">
<?php
for($i=1;$i<=$PARAM['MAX_LEVEL'];$i++)
{
	echo "<option value=\"{$i}\">{$i}</option>\n";
}
?>
</select>
<script language="JavaScript">
function add_space()
{
	document.forms[0].q_text.value+='&nbsp;';
}
</script> 
</td></tr>
<tr>
<td align="left" class="row1" width="100%"><textarea id="q_text" name="q_text" rows="5" cols="80"></textarea>&nbsp;
<input type="button" name="space_button" value="SPACE" class="button" onclick="add_space();">
</td></tr>
<tr><td align="left" class="row4"><?php echo $lang['ans_num']?>
<select name="ans_num">
<?php 
for($i = 2;$i <= $PARAM['MAX_ANS'];$i += 1)
{
	echo "<option value=\"{$i}\">{$i}</option>\n";
}
echo "</select>&nbsp;&nbsp;\n";
echo "<script language=\"JavaScript\">document.forms[0].ans_num.value={$PARAM['DEF_ANS']}</script>\n";
echo $lang['capt_amedia']?>
<select name="amedia_type">
<option value="0"><?php echo $lang['no_media']?></option>
<option value="1"><?php echo $lang['image_media']?></option>
</select>
</td></tr>
<tr><td align="center" class="darkrow2"><input type="submit" class="button" name="submit" value="<?php echo $lang['next_button']?>">&nbsp;<input type="reset" class="button" name="clear" value="<?php echo $lang['clear_button']?>"></td></tr>
</table>
</form>