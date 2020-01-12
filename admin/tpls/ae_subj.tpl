<!-- ADD/EDIT Subject Information [Form Structure] -->
<?php

if (!isset($value_1)) $value_1 = "";

?>

<DIV align="center">
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_subj']?></TD></TR>
<TR>
<TD width="20%" class="row3"><b><?php echo $lang['subj_name'];?></b></TD>
<TD class="row1"><INPUT type="text" name="subject_name" size="100" maxlength="100" value="<?php echo $value_1?>"></TD>
</TR>
<TR>
<TD width="20%" class="row3"></TD>
<TD class="row1"><INPUT type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>"></TD>
</TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
</DIV>