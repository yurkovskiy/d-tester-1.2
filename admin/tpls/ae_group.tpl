<!-- ADD/EDIT group information [Form Structure] -->
<?php

if (!isset($value_1)) $value_1 = "";

?>
<DIV align="center">
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_group']?></TD></TR>
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['group_name'];?></b></TD>
<TD class="row1"><INPUT type="text" name="group_name" size="10" maxlength="10" value="<?php echo $value_1?>"></TD>
</TR>
<!-- Spec List -->
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['spec_name'];?></b></TD>
<TD class="row1">
<select name="spec_id">
<?php

while ($DB->fetch_row()) {
	$spec_id = $DB->record_row[0];
	$spec_name = $DB->record_row[1]." - ".$DB->record_row[2];
	echo "<option value=\"{$spec_id}\">{$spec_name}</option>\n";
}
$DB->free_result();

?>
</select>
</TD>
</TR>
<!-- /Spec List -->
<TR>
<TD width="20%" class="row3">&nbsp;</TD>
<TD class="row1"><INPUT type="submit" class="button" name="submit" value="<?php echo $lang['reg_button']?>"></TD>
</TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
</DIV>