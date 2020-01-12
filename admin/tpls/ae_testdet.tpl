<!-- ADD/EDIT Test Detail Infromation [Form Structure] -->
<DIV align="center">
<TABLE width="100%" border="0" cellpadding="3" cellspacing="3" class="tbl_view_frame">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_test_detail']?></TD></TR>
<TR>
<TH width="20%" align="left" class="row4"><?php echo $lang['test_parametr']?></TH>
<TH width="80%" align="left" class="row4"><?php echo $lang['test_parametr_value']?></TH>
</TR>
<TR>
<TD align="left" class="row1"><b><?php echo $lang['level_id']?></b></TD>
<TD align="left" class="row1">

<?php 
if($type!="edit")
{
?>
<SELECT name="level_id">
<?php
for($i=1;$i<=$PARAM['MAX_LEVEL'];$i++)
{
?>
<OPTION value="<?php echo $i?>"><?php echo $i?></OPTION>
<?php
}
?>
</SELECT>
<?php
}
else 
{
	echo $value_1;
}
?>
</TD>
</TR>
<TR>
<TD align="left" class="row1"><b><?php echo $lang['tasks_num']?></b></TD>
<TD align="left" class="row1"><INPUT type="text" size="2" maxlength="2" name="tasks_num"></TD>
</TR>
<TR>
<TD align="left" class="row1"><b><?php echo $lang['level_rate']?></b></TD>
<TD align="left" class="row1"><INPUT type="text" size="2" maxlength="2" name="level_rate"></TD>
</TR>
<TR>
<TD align="left" class="row1">&nbsp;</TD>
<TD align="left" class="row1"><INPUT type="submit" class="button" name="reg" value="<?php echo $lang['reg_button']?>"></TD>
</TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
</DIV>