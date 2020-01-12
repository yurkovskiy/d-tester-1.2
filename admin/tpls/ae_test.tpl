<!-- ADD/EDIT Test Information [Form Structure] -->
<?php

if (!isset($value_1)) $value_1 = "";
if (!isset($value_2)) $value_2 = 1;
if (!isset($value_3)) $value_3 = "00:01:00";
if (!isset($value_4)) $value_4 = 0;
if (!isset($value_5)) $value_5 = 0;
if (!isset($value_6)) $value_6 = 0;

?>

<DIV align="center">
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_test']?></TD></TR>
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['test_name'];?></b></TD>
<TD class="row1"><INPUT type="text" name="test_name" size="70" maxlength="100" value="<?php echo $value_1?>"></TD>
</TR>
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['test_how_tasks'];?></b></TD>
<TD class="row1"><INPUT type="text" name="tasks" size="2" maxlength="2" value="<?php echo $value_2?>"></TD>
</TR>
<TR>
<TD class="row3"><b><?php echo $lang['test_time'].=$lang['test_time_spec']?></b></TD>
<TD class="row1"><INPUT type="text" name="test_time" size="8" maxlength="8" value="<?php echo $value_3?>"></TD>
</TR>
<TR>
<TD class="row3"><b><?php echo $lang['show_test_client']?></b></TD>
<TD class="row1">
<select name="show_test">
<option value="0"><?php echo $lang['test_disable']?></option>
<option value="1"><?php echo $lang['test_enable']?></option>
</select>
<script language="JavaScript">
document.forms['register_form'].show_test.value="<?php echo $value_4?>";
</script>
</TD>
</TR>
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['how_chances'];?></b></TD>
<TD class="row1"><INPUT type="text" name="h_chan" size="2" maxlength="2" value="<?php echo $value_5?>"></TD>
</TR>

<!-- Test Type -->
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['test_type'];?></b></TD>
<TD class="row1">
<SELECT name="test_type">
<?PHP
for ($i = 0;$i < sizeof($lang['test_types']);$i++)
{
?>
<OPTION value="<?php echo $i?>"><?php echo $lang['test_types'][$i]?></OPTION>
<?PHP
}
?>
</SELECT>
</TD>
</TR>
<!-- /Test Type -->
<script language="JavaScript">
document.forms['register_form'].test_type.value="<?php echo $value_6?>";
</script>

<TR>
<TD width="20%" class="row3"><b><?php echo $lang['test_subject'];?></b></TD>
<TD class="row1">
<SELECT name="subjects">
<?PHP
while($DB->fetch_row())
{
?>
<OPTION value="<?php echo $DB->record_row[0]?>"><?php echo $DB->record_row[1]?></OPTION>
<?PHP
}
?>
</SELECT>
</TD>
</TR>
<TR>
<TD class="row3"></TD>
<TD class="row1"><INPUT type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>"></TD>
</TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
</DIV>