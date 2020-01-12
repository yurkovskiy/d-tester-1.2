<!-- Order Form STEP 2 -->
<script type="text/javascript">
function isChecked(cb) {
	var arr;
	var i;
	
	arr = ["st_id[", "st_rate[", "st_check["];
	
	for (i = 0;i < arr.length;i++) {
		arr[i] = arr[i] + cb.value + "]";
	}
	
	// checked
	if (cb.checked == true) {
		for (i = 0;i < arr.length;i++) {
			document.getElementById(arr[i]).className = "yrow";
		}
	}
	
	else {
		for (i = 0;i < arr.length;i++) {
			document.getElementById(arr[i]).className = "row1"
		}
	}
	
}
</script>

<?php

$g_query = $DB->query("SELECT group_name FROM groups WHERE group_id = {$group_id}");
$g_row = $DB->fetch_row($g_query);
$group_name = $g_row[0];
$DB->free_result($q_query);

?>

<form onsubmit="return checkedForm(this)" action="orders.php?step=3" method="POST" id="order_form" name="order_form" enctype="multipart/form-data">
<input type="hidden" name="test_id" value="<?php echo $test_id?>">
<input type="hidden" name="order_present" id="order_present" value="0">
<table width="100%" border="0" class="tbl_view_frame" cellpadding="3" cellspacing="3">
<tr><td align="center" class="maintitle" colspan="3"><?php echo $lang['orders_title']?></td></tr>

<tr>
<td colspan="3" class="row4"><b><?php echo "{$lang['capt_group']}&nbsp;{$group_name}&nbsp;&nbsp;&nbsp;{$subject_name}&nbsp;==>&nbsp;{$test_name}"?></b></td>
</tr>

<tr>
<td align="left" class="row3" width="30%"><b><?php echo $lang['capt_student']?></b></td>
<td align="left" class="row3" width="25%"><b><?php echo $lang['capt_omark']?></b></td>
<td align="left" class="row3" width="45%"><b><?php echo $lang['capt_ostatus']?></b></td>
</tr>

<?php

$students = $DB->query("SELECT user_name, user_id FROM users WHERE user_group ={$group_id} ORDER BY user_id ASC");
while ($s_row = $DB->fetch_row($students)) {
	echo "<tr>\n";
	
	echo "<td class=\"row1\" id=\"st_id[{$s_row[1]}]\">{$s_row[0]}</td>\n";
	echo "<td class=\"row1\" id=\"st_rate[{$s_row[1]}]\"><input type=\"text\" name=\"st_r[{$s_row[1]}]\" id=\"st_r[{$s_row[1]}]\" size=\"3\" maxlength=\"3\" onfocus=\"tFocus({$s_row[1]})\" onblur = \"tBlur({$s_row[1]})\"></td>\n";
	echo "<td class=\"row1\" id=\"st_check[{$s_row[1]}]\"><input type=\"checkbox\" name=\"cb[{$s_row[1]}]\" id=\"cb[{$s_row[1]}]\" value=\"{$s_row[1]}\" onclick=\"isChecked(this)\"></td>\n";
		
	echo "</tr>\n\n";
}

$DB->free_result($students);

if ($order_present) {
	echo "<!-- JS Code if rating is present [for next editing] -->\n";
	echo "<script type=\"text/javascript\">\n";
	while (list($key, $val) = each($order_rating)) {
		echo "document.getElementById(\"st_r[{$key}]\").value = {$val};\n";
		echo "document.getElementById(\"cb[{$key}]\").checked = {$order_status[$key]};\n";
		echo "isChecked(document.getElementById(\"cb[{$key}]\"));\n";
	}
	// changing hidden parameter
	echo "document.getElementById(\"order_present\").value = 1;\n";
	echo "</script>\n";
}

?>

<script type="text/javascript">
// Focus/Blur Handlers
var es = ["st_id", "st_rate", "st_check"];
function tFocus(cb) {
	if (!document.getElementById("cb["+cb+"]").checked) {
		for (var i = 0;i < es.length;i++) {
			var temp_td = es[i] + "[" + cb + "]";
			document.getElementById(temp_td).className = 'mrow';
		}
	}
}

function tBlur(cb) {
	if (!document.getElementById("cb["+cb+"]").checked) {
		for (var i = 0;i < es.length;i++) {
			var temp_td = es[i] + "[" + cb + "]";
			document.getElementById(temp_td).className = 'row1';
		}
	}
}

</script>

<tr>
<td class="row1" colspan="3" align="center"><input type="submit" class="button" name="submit" value="<?php echo $lang['reg_button'];?>"></td>
</tr>
<tr><td width="100%" colspan="3" class="darkrow2">&nbsp;</td></tr>
</table>
</form>
<!-- /Order Form STEP 2 -->