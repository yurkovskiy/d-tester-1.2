<?php
require_once("inc/functions.inc");

$cash = 0.0;

if (intval($_SESSION['adm_id']) <= 20) $refresh = "1";
if (intval($_SESSION['adm_id']) > 20) {
	$refresh = "45";
	//$startDate = mktime(6, 30, 0, 3, 23, 2005); // start of d-tester epoch
	$startDate = mktime(6, 30, 0, 11, 12, 2005); // start of d-tester epoch [enterprise]
	$currentDate = mktime(); // current date (timestamp in seconds)
	$differenceDate = $currentDate - $startDate;
	$hourPrice = 7.0; // hour price 2.2 UAH smile)))
	$cash = round(($hourPrice * ($differenceDate / 3600 / 3 )), 2);
	$refresh = round(($differenceDate / 3600 / 3 / 24 / 8)) + 50;
	
}
$_SESSION['refresh'] = $refresh;
$_SESSION['enter_time_offset'] = $_SESSION['enter_time'] + intval($refresh);

?>
<html>
<head>
<title>Please stand by...</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<meta http-equiv='refresh' content='<?php echo $refresh ?>; url=' />
<style type="text/css">
.tablefill   { border:1px solid #345487;background-color:#F5F9FD;padding:2px;  }
</style>
</head>
<body>
<table width="620" height="85%" align="center">
<tr>
  <td valign="middle" align="center">
	  <table align="center" cellpadding="1" class="tablefill">
	  <tr> 
	    <td width="100%" align="center" nowrap="nowrap">
		<img align="middle" src="images/splash.jpg">
	    </td>
	  </tr>
	  
	  <tr>
	      
		<td width="100%" align="center" nowrap="nowrap">
		  <b>Адміністратор: <font color="Red"><?php echo "{$_SESSION['real_name']}&nbsp;({$_SESSION['adm_name']})";?></font><br>Чекайте і дочекаєтесь...</b>
	    </td>
	  </tr>
	  
	  <tr>
	  <td width="100%" align="center" nowrap="nowrap">
	  <b style="color:red;">Price for Support at present moment: <?php echo $cash?>&nbsp;UAH</b>
	  </td>
	  </tr>
	  
	  <?php

	  if (!check_php_version()) {
	  	echo "<tr>\n<td align=\"center\">";
	  	echo "<font color=\"red\"><b>Note: Some system functions require PHP 5.1.x or later.<br>d-tester XML using DOMDocument class (PHP 5.1.x >)</b></font>\n";
	  	echo "</td>\n</tr>\n";
	  }

	  ?>
	  
	</table>
  </td>
</tr>
</table>
<?php $_SESSION['login_stage'] = 1;?>
</body>
</html>