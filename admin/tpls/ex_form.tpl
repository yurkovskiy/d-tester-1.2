<script language="JavaScript">
function check(form) {
	if (confirm('<?php echo "{$lang['exform_conf']}"?>')) return true;
	else return false;
}
</script>
<form onsubmit="return check(this)" action="export.php?action=export" method="POST" name="ex_form">
<!-- Export Form Structure -->
<table align="center" width="100%" border="0" cellpadding="3" cellspacing="3" class="tbl_view_frame">
<tr><td align="center" width="100%" colspan="2" class="maintitle"><?php echo $lang['exform_title']?></td></tr>

<tr>
<th width="20%" align="left" class="row4"><?php echo $lang['test_parametr']?></th>
<th width="80%" align="left" class="row4"><?php echo $lang['test_parametr_value']?></th>
</tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['capt_test']?></b></td>
<td align="left" class="row1">
<select name="test_id">
<?php

$s_query = $DB->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_id ASC");
while ($s_row = $DB->fetch_row($s_query)) {
	echo "<optgroup label=\"{$s_row[1]}\">\n";
	$t_query = $DB->query("SELECT tests.test_id, tests.test_name
						FROM tests, subjects WHERE tests.test_subject_id = '$s_row[0]' AND subjects.subject_id = tests.test_subject_id ORDER BY tests.test_id ASC");
	while ($trow = $DB->fetch_row($t_query)) {
		echo "<option value=\"{$trow[0]}\">{$trow[1]}</option>\n";
	}
	echo "</optgroup>\n\n";
	$DB->free_result($t_query);
}

$DB->free_result($s_query);

?>
</select>
</td></tr>

<tr>
<td align="left" class="row1"><b><?php echo $lang['exform_format']?></b></td>
<td align="left" class="row1">
<select name="ex_format">
<?php
for($i = 0;$i < sizeof($lang['ex_formats']);$i++)
{
?>
<option value="<?php echo "{$i}"?>"><?php echo "{$lang['ex_formats'][$i]}"?></option>
<?php
}
?>
</select>
</td></tr>


<tr><td align="center" class="row2" colspan="2" width="100%">&nbsp;</td></tr>

<tr><td width="100%" align="center" colspan="2" class="darkrow2"><input type="submit" class="button" name="submit" value="<?php echo $lang['reg_button']?>"></td></tr>
</table>
</form>