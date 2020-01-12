<div align="center">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center" width="100%"><img align="middle" src="./images/logo.jpg"></td></tr>
<tr><td align="center" width="100%"><b><i><?php echo $lang['system_version']?></i></b></td></tr>
</table>
<table align="center" width="50%" cellpadding="4" cellspacing="1" class="maintable">
<form onsubmit="return checkedForm(this)" action="index.php" name="aut_form" method="POST">
<tr><td align="center" class="tableh1" colspan="2" nowrap="nowrap"><b><?php echo $lang['login_act']?></b></td></tr>
<tr>
<td width="40%" class="tableb"><?php echo $lang['login_name']?></td>
<td width="60%" class="tableb"><input class="textinputb" type="text" name="adm_name" style="width: 85%"></td>
</tr>
<tr>
<td width="40%" class="tableb"><?php echo $lang['login_pass']?></td>
<td width="60%" class="tableb"><input class="textinputb" type="password" name="adm_pass" style="width: 85%"></td>
</tr>
<tr><td class="tableh1" align="center" colspan="2"><input type="submit" class="button" value="<?php echo $lang['login_submit']?>"></td></tr>
</form>
</table>
</div>
<div align="center" class="copyright"><?php echo $lang['aut_copy']?></div>
<div align="center" class="copyright"><?php echo $lang['logo_copy']?></div>