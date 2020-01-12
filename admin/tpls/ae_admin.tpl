<!-- ADD/EDIT admin information [Form Structure] -->
<?php
if(isset($disabled)) $tbl_capt = $lang['priv_sch'];
else $tbl_capt = $lang['capt_reg_admin'];
?>
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $tbl_capt?></TD></TR>
<TR><TD width="20%" class="row3"><?php echo $lang['login_name'];?></TD>
<TD class="row1"><INPUT <?php echo "{$disabled}";?> type="text" name="login_name" size="35" maxlength="35" value="<?php echo $value_1?>"></TD></TR>
<TR><TD width="20%" class="row3"><b><?php echo $lang['user_name'];?></b></TD>
<TD class="row1"><INPUT <?php echo "{$disabled}";?> type="text" name="user_name" size="35" maxlength="35" value="<?php echo $value_3?>"></TD></TR>
<TR><TD width="20%" class="row3"><?php echo $lang['login_pass'];?></TD>
<TD class="row1"><INPUT <?php echo "{$disabled}";?> type="text" name="pass_word" size="20" maxlength="20"></TD></TR>
<TR><TD width="20%" class="row3"><b><?php echo $lang['user_conf_pass'];?></b></TD>
<TD class="row1"><INPUT <?php echo "{$disabled}";?> type="text" name="conf_pass" size="20" maxlength="20"></TD></TR>
<TR><TD width="20%" class="row3"><?php echo $lang['adm_level_priv'];?></TD>
<TD class="row1"><select name="apriv" <?php echo "{$disabled}";?>><option value="2">2</option></select></TD></TR>
<?php if(!$value_2) $value_2=2?>
<script language="JavaScript">
document.forms[0].apriv.value=<?php echo $value_2;?>
</script>

<TR><TD class="row3"><b>SUBJECT_ID</b></TD><TD class="row1"><select name="subj_id" <?php echo "{$disabled}";?>>
<?php
$DB->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_id ASC");
while($DB->fetch_row())
{
?>
<OPTION value="<?php echo $DB->record_row[0]?>"><?php echo $DB->record_row[1]?></OPTION>
<?PHP
}

if(!$value_4) $value_4 = 0;

?>
</TR>
<script language="JavaScript">
document.forms[0].subj_id.value=<?php echo $value_4;?>
</script>

<?php

for($i=0;$i<sizeof($lang['ROOT_PRIV']);$i++)
{
	$out.="<TR>\n<TD class=\"row3\"><b>{$lang['ROOT_PRIV'][$i]}</b></TD>\n";
	if($priv_values[$i]=="checked") $out.="<TD class=\"row1\"><INPUT {$disabled} type=\"checkbox\" name=\"{$lang['ROOT_PRIV'][$i]}\" {$priv_values[$i]}></TD>\n</TR>\n";
	else $out.="<TD class=\"row1\"><INPUT {$disabled} type=\"checkbox\" name=\"{$lang['ROOT_PRIV'][$i]}\"></TD>\n</TR>\n";
}
echo $out;
?>

<TR><TD class="row3">&nbsp;</TD><TD class="row1"><INPUT <?php echo "{$disabled}";?> type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>"></TD></TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>