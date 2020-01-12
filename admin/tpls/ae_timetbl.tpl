<!-- ADD/EDIT TimeTable Information [Form Structure] -->

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" media="all" href="js/calendar/css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<!-- main calendar program -->
<script type="text/javascript" src="js/calendar/calendar.js"></script>

<!-- language for the calendar -->
<!--<script type="text/javascript" src="js/calendar/calendar-ru_win_.js"></script>-->
<script type="text/javascript" src="js/calendar/calendar-en.js"></script>

<!-- the following script defines the Calendar.setup helper function, which makes
     adding a calendar a matter of 1 or 2 lines of code. -->
<script type="text/javascript" src="js/calendar/calendar-setup.js"></script>

<DIV align="center">
<TABLE width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<TR><TD align="center" class="maintitle" colspan="2"><?php echo $lang['capt_reg_timetable']?></TD></TR>
<TR>
<TH width="20%" align="left" class="row4"><?php echo $lang['test_parametr']?></TH>
<TH width="80%" align="left" class="row4"><?php echo $lang['test_parametr_value']?></TH>
</TR>
<TR>
<TD class="row1"><b><?php echo $lang['group_name'];?></b></TD>
<TD class="row1">
<?php 
if($type!="edit")
{
?>
<SELECT name="group">
<?PHP
while($DB->fetch_row())
{
?>
<OPTION value="<?php echo $DB->record_row[1]?>"><?php echo $DB->record_row[0]?></OPTION>
<?PHP
}
?>
</SELECT>
<?php
}
else 
{
	echo $value_1;
}
$DB->query("SELECT CURDATE()");
$DB->fetch_row();
if(!isset($value_2)) $value_2=$DB->record_row[0];
$DB->free_result();
?>
</TD>
</TR>
<TR>
<TD class="row1"><b><?php echo $lang['capt_date']?></b>&nbsp;<?php echo $lang['date_mask']?></TD>

<TD class="row1">
<INPUT type="text" name="e_date" id="e_date" size="10" maxlength="10" value="<?php echo $value_2?>">
<img src="js/calendar/css/img.gif" id="f_trigger_c" style="cursor: pointer; border: 1px solid red;" title="Date selector"
      onmouseover="this.style.background='red';" onmouseout="this.style.background=''">
</TD>

</TR>
<TR>
<TD class="row1"></TD>
<TD class="row1"><INPUT type="submit" class="button" name="submit" value="<?php echo $lang['reg_button']?>"></TD>
</TR>
<TR><TD width="100%" colspan="2" class="darkrow2">&nbsp;</TD></TR>
</TABLE>
<INPUT type="hidden" name="subject" value="<?php echo $subj?>">
</DIV>
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "e_date",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "f_trigger_c",  // trigger for the calendar (button ID)
        align          :    "",           // alignment (defaults to "Bl")
        singleClick    :    true
    });
</script>