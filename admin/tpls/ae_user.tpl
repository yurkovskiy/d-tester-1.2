<!-- ADD/EDIT user information [Form Structure] -->
<?php

if (!isset($value_1)) $value_1 = "";

?>
<div align="center">

<script language="JavaScript">
// generating random number
function rand() {
	var num = Math.floor(9 * Math.random() + 1);
	return num;
}

// generating random password
function gen_pass() {
	var pass_w
	pass_w = Array(<?php echo $PARAM['UPASS_LENGTH']?>);
		
	for(var i = 0;i < <?php echo $PARAM['UPASS_LENGTH']?>;i++) {
		pass_w[i] = rand();
	}
	document.forms[0].pass_word.value = pass_w.join('');
	document.forms[0].conf_pass.value = pass_w.join('');
}
</script>

<table width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<tr><td align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_user']?></td></tr>
<tr>
<td width="20%" class="row3"><b><?php echo $lang['user_name'];?></b></td>
<td class="row1"><input type="text" name="user_name" size="35" maxlength="35" value="<?php echo $value_1?>"></td>
</tr>
<tr>
<td width="20%" class="row3"><b><?php echo $lang['user_order_num'];?></b></td>
<td class="row1"><input type="text" name="user_order_num" size="10" maxlength="10" value="<?php echo $value_2?>"></td>
</tr>
<tr>
<td width="20%" class="row3"><b><?php echo $lang['user_pass'];?></b></td>
<td class="row1"><input type="text" name="pass_word" size="20" maxlength="20"></td>
</tr>
<tr>
<td width="20%" class="row3"><b><?php echo $lang['user_conf_pass'];?></b></td>
<td class="row1"><INPUT type="text" name="conf_pass" size="20" maxlength="20"></td>
</tr>

<tr>
<td width="20%" class="row3"><b><?php echo $lang['group_name'];?></b></TD>
<td class="row1">
<select name="group_id">
<?php
while($DB->fetch_row()) {
?>
<option value="<?php echo $DB->record_row[0]?>"><?php echo $DB->record_row[1]?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td class="row3">&nbsp;</td>
<td class="row1"><input type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>">
<input type="button" value="<?php echo $lang['gen_pass_button'];?>" class="button" onclick="gen_pass();">
</td>
</tr>
<tr><td width="100%" colspan="2" class="darkrow2">&nbsp;</td></tr>
</table>
</div>