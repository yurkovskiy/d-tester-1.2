<!-- Order Form STEP 1 -->
<form action="orders.php?step=2" method="POST" id="order_form" name="order_form" enctype="multipart/form-data">
<table width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<tr><td align="center" class="maintitle" colspan="2"><?php echo $lang['orders_title']?></td></tr>
<tr>
<td align="left" class="row3" width="20%"><b><?php echo $lang['capt_group']?></b></td>
<td align="left" class="row1">
<select name="group_id" id="group_id">
<?php
$g_query = $DB->query("SELECT group_name, group_id FROM groups ORDER BY group_id ASC");
while ($row = $DB->fetch_row($g_query)) {
	echo "<option value=\"{$row[1]}\">{$row[0]}</option>\n";
}
$DB->free_result($g_query);
?>
</select>
</td>
</tr>
<tr>
<td align="left" class="row3" width="20%"><b><?php echo $lang['capt_test']?></b></td>
<td align="left" class="row1">
<select name="test_id" id="test_id">
<?php

$s_query = $DB->query("SELECT subject_id, subject_name FROM subjects ORDER BY subject_id ASC");
while ($s_row = $DB->fetch_row($s_query)) {
	echo "<optgroup label=\"{$s_row[1]}\">\n";
	$t_query = $DB->query("SELECT tests.test_id, tests.test_name
						FROM tests, subjects WHERE tests.test_type = 1 AND tests.test_subject_id = '$s_row[0]' AND subjects.subject_id = tests.test_subject_id ORDER BY tests.test_id ASC");
	while ($trow = $DB->fetch_row($t_query)) {
		echo "<option value=\"{$trow[0]}\">{$trow[1]}</option>\n";
	}
	echo "</optgroup>\n\n";
	$DB->free_result($t_query);
}

$DB->free_result($s_query);
?>
</select>
</td>
</tr>
<tr>
<td class="row3">&nbsp;</td>
<td class="row1">
<input type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>">
<input type="button" class="button" name="print_ver" value="<?php echo $lang['print_ver_button'];?>" onclick="print_order();">
<input type="button" class="button" name="print_ver" value="<?php echo $lang['print_ver_button_up_10'];?>" onclick="print_full_order();">
</td>
</tr>
<tr><td width="100%" colspan="2" class="darkrow2">&nbsp;</td></tr>
</table>
</form>
<script type="text/javascript">
function print_order() {
	var group_id, test_id;
	
	group_id = parseInt(document.getElementById("group_id").value);
	test_id = parseInt(document.getElementById("test_id").value);
	
	//alert("group_id = "+group_id+" test_id = "+test_id);
	window.open("print_order.php?group="+group_id+"&test="+test_id, "newWindow");
	
}

function print_full_order() {
	var group_id, test_id;
	
	group_id = parseInt(document.getElementById("group_id").value);
	test_id = parseInt(document.getElementById("test_id").value);
	
	//alert("group_id = "+group_id+" test_id = "+test_id);
	window.open("print_results_up10.php?group="+group_id+"&test="+test_id, "newWindow");
	
}

</script>
<!-- /Order Form STEP 1 -->