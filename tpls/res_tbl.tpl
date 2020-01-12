<!-- Results Table -->
<table width="100%" cellpadding="3" cellspacing="4" border="0" class="tbl_view_frame">
<tr>
<td align="center" class="maintitle"><?php echo $lang['tester_title']?></td>
</tr>
<tr>
<td align="center" class="row2"><?php echo $lang['results_capt']?></td>
</tr>
</table>
</br>
<table align="center" width="50%" border="0" cellpadding="4" cellspacing="1" class="tbl_view_frame">
<tr>
<td colspan="2" align="center" class="maintitle"><?php echo $lang['result']?></td>
</tr>
<tr>
<td width="25%" class="row2"><?php echo $lang['student']?></td>
<td width="75%" class="row1"><b><?php echo $_SESSION['user_name']?></b></td>
</tr>
<tr>
<td width="25%" class="row2"><?php echo $lang['test_name']?></td>
<td width="75%" class="row1"><?php echo $_SESSION['test_name']?></td>
</tr>
<tr>
<td width="25%" class="row2"><?php echo $lang['result']?></td>
<td width="75%" class="row1"><?php echo $_SESSION['rate']?>&nbsp;<?php echo $lang['of']?>&nbsp;<?php echo $_SESSION['test_rate']?></td>
</tr>

<tr>
<td align="center" colspan="2" class="row2"><?php echo $lang['res_details']?></td>
</tr>

<tr>
<td width="25%" class="row2"><?php echo $lang['res_quality']?></td>
<td width="75%" class="row1"><b><?php echo $res_percent?> %&nbsp;&nbsp;&nbsp;(<?php $tko = $mega_koeff * $res_percent; echo "{$mega_koeff} * {$res_percent} = {$tko}" ?>)</b></td>
</tr>

<?php
if (intval($full_rating) < 60) $res_status = $lang['res_bad'];
if (intval($full_rating) >= 60) $res_status = $lang['res_good'];
?>

<tr>
<td width="25%" class="row2"><?php echo $lang['full_rating']?></td>
<?php if ($_SESSION['test_type'] == 1) {?>
<td width="75%" class="row1"><b><?php echo "({$_SESSION['user_rating']} + {$tko})/2 = <font color=\"red\">{$full_rating}</font>" ?></b></td>
<?php } else {?>
<td width="75%" class="row1"><b><?php echo "<font color=\"red\">{$full_rating}</font>"?></b></td>
<?php }?>
</tr>

<tr>
<td width="25%" class="row2"><?php echo $lang['true_answers']?></td>
<td width="75%" class="row1"><?php echo $true_ans?>&nbsp;<?php echo  $lang['of']?>&nbsp;<?php echo $_SESSION['how_q']?></td>
</tr>

<tr>
<td width="25%" class="row2"><?php echo $lang['res_status']?></td>
<td width="35%" class="row1"><?php echo $res_status?></td>
</tr>

<tr>
<td align="center" colspan="2" class="darkrow2">&nbsp;</td>
</tr>
</table>