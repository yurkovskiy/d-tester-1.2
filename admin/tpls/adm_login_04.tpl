<link type="text/css" href="./../styles/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script src="./../js/jquery-1.6.2.min.js" type="text/javascript"></script>
<script src="./../js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
  $("#dialog").dialog({
  	hide: 'slide',
  	draggable: false,
  	resizable: false,
  	show: 'slide',
  	modal: true,
  	width: 500
  });
});
</script>
<div id="dialog" title="Важливі оновлення">
  <p style="color:red;font-size: 1.0em;">15.01.2012 Кожна група відтепер має привязку до спеціальності, яка в свою чергу відноситься до факультету, для &quot;правильної&quot; генерації відомостей результатів тестування</p>
  <p style="font-size: 0.9em">Наприклад: група СУм-07 спеціальність 8.05020101 - Комп&acute;ютеризовані системи управління та автоматики</p>
  <p style="font-size: 0.9em">група СУ-07-1 спеціальність 7.05020101 - Комп&acute;ютеризовані системи управління та автоматики</p>
  <p style="font-size: 0.9em">група СІ-08-1 спеціальність 6.050201 - Системна інженерія</p>
  <!--<p>UI changes (sections: Rating, Results)</p>-->
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<!--<div align="center" class="copyright">
<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width='200' height='100' id='3_year_logo' align='middle'>
<param name='movie' value='images/year_3.swf' />
<param name='quality' value='high' />
<param name='bgcolor' value='#ffffff' />
<embed src='images/year_3.swf' quality='high' bgcolor='#ffffff' width='200' height='100' name='3_year_logo' align='middle' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object>
</div>-->

<form onsubmit="return checkedForm(this)" action="index.php" name="aut_form" method="POST">
<table align="center" width="500" cellpadding="1" cellspacing="0" class="maintable">
<tr><td align="center" colspan="2" class="tableh1"><b><i><?php echo $lang['system_version']?></i></b></td></tr>
<tr>
<td rowspan="6" width="20%" align="center" valign="middle"><img align="middle" src="./images/logo.jpg"></td>
</tr>
<tr><td class="tableb"><?php echo $lang['login_name']?></td></tr>
<tr><td class="tableb"><input class="textinputb" type="text" id="adm_name" name="adm_name" style="width: 100%"></td></tr>
<tr><td class="tableb"><?php echo $lang['login_pass']?></td></tr>
<tr><td class="tableb"><input class="textinputb" type="password" id="adm_pass" name="adm_pass" style="width: 100%"></td></tr>
<tr><td class="tablef" align="center"><input type="submit" class="button" value="<?php echo $lang['login_submit']?>"></td></tr>
</table>
</form>
<!-- Copyright Information -->
<div align="center" class="copyright"><?php echo $lang['aut_copy']?></div>
<div align="center" class="copyright"><?php echo $lang['logo_copy']?></div>

<?php

if (strlen($mes) > 1) {
	echo "<br><br>\n<div class=\"copyright\" style = \"text-align:center;font-weight:bold;color:red;\">{$mes}</div>\n";
}

?>

<!-- B-Logo Position -->
<!--<div id="b_logo_pos">
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width='200' height='100' id='3_year_logo' align='middle'>
	<param name='movie' value='images/logo.swf' />
	<param name='quality' value='high' />
	<param name='bgcolor' value='#ffffff' />
	<embed src='images/logo.swf' quality='high' bgcolor='#ffffff' width='200' height='100' name='3_year_logo' align='middle' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />
</object>
</div>-->
<script type="text/javascript">
document.forms[0].elements["adm_name"].focus();
</script>