<script language="JavaScript">
function check(form)
{
	if (confirm('<?php echo "{$lang['cpform_conf']}"?>')) return true;
	else return false;
}
</script>
<form onsubmit="return check(this)" action="copy_test.php?action=copy" method="POST" name="copy_form">
<!-- Copy Form Structure -->
<table align="center" width="100%" border="0" cellpadding="3" cellspacing="3" class="tbl_view_frame">
<tr><td align="center" width="100%" colspan="2" class="maintitle"><?php echo $lang['cpform_title']?></td></tr>
<tr><td align="left" width="100%" colspan="2" class="row3"><b><?php echo $lang['cpform_FROM']?></b></td></tr>

<tr>
<th width="20%" align="left" class="row4"><?php echo $lang['test_parametr']?></th>
<th width="80%" align="left" class="row4"><?php echo $lang['test_parametr_value']?></th>
</tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['capt_test']?></b></td>
<td align="left" class="row1">
<select name="from_test_id">
<?php
$tests = $DB->query("SELECT test_name, test_id FROM tests ORDER BY test_id ASC");
while($DB->fetch_row($tests))
{
	$test_name = $DB->record_row[0]." [".$DB->record_row[1]."]";
?>
<option value="<?php echo "{$DB->record_row[1]}"?>"><?php echo "{$test_name}"?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['level_id']?></b></td>
<td align="left" class="row1">
<select name="from_level_id">
<?php
for($i = 1;$i <= $PARAM['MAX_LEVEL'];$i++)
{
?>
<option value="<?php echo $i?>"><?php echo $i?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr><td align="center" class="row2" colspan="2" width="100%">&nbsp;</td></tr>
<tr><td align="left" width="100%" colspan="2" class="row3"><b><?php echo $lang['cpform_TO']?></b></td></tr>

<tr>
<th width="20%" align="left" class="row4"><?php echo $lang['test_parametr']?></th>
<th width="80%" align="left" class="row4"><?php echo $lang['test_parametr_value']?></th>
</tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['capt_test']?></b></td>
<td align="left" class="row1">
<select name="to_test_id">
<?php
$tests = $DB->query("SELECT test_name, test_id FROM tests ORDER BY test_id ASC");
while($DB->fetch_row($tests))
{
	$test_name = $DB->record_row[0]." [".$DB->record_row[1]."]";
?>
<option value="<?php echo "{$DB->record_row[1]}"?>"><?php echo "{$test_name}"?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['level_id']?></b></td>
<td align="left" class="row1">
<select name="to_level_id">
<?php
for($i = 1;$i <= $PARAM['MAX_LEVEL'];$i++)
{
?>
<option value="<?php echo $i?>"><?php echo $i?></option>
<?php
}
?>
</select>
</td>
</tr>

<tr><td width="100%" align="center" colspan="2" class="row2">&nbsp;</td></tr>
<tr><td width="100%" align="center" colspan="2" class="darkrow2"><input type="submit" class="button" name="submit" value="<?php echo $lang['reg_button']?>"></td></tr>

</table>
</form>