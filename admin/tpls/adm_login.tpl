<link rel="stylesheet" type="text/css" href="styles/login_form.css">
<link type="text/css" href="./../styles/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<!--<script type="text/javascript" src="js/script.js"></script>-->
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
<div id="dialog" title="ATTENTION PLEASE!!!">
  <p style="color:red;font-size: 1.0em;">Dear teachers!!! As you probably know our student's of 5 course were moved to other groups!!!</p>
  <p style="color:red;font-size: 1.0em;">If you would have exams on student's of 5 course, please select CY-08 groups (not CI-08!!!) from list on TIMETABLE section!!!</p>
  <!--<p style="color:red;font-size: 1.0em;">Who wants to move to the good list, please contact with your admin (if you know who is it)</p>-->
  <p style="color:red;font-size: 1.0em;">Best regards</p>
  
  <!--<p>UI changes (sections: Rating, Results)</p>-->
</div>
<!-- Login Form -->
<div id="main">
		<div id="header">
			<span class="text"><?php echo $lang['system_version']?></span></div>
		<div id="logo"></div>
		<div id="login_form">
			<span id="hello_text">Hello World</span>
			<form onsubmit="return checkedForm(this)" id="login_form_" name="login_form_" action="index.php" method="POST">
				<table cellspacing="0" cellpadding="5px">
					<tr><td><?php echo $lang['login_name']?></td> <td><input class="input_field" type="text" id="adm_name" name="adm_name" size="10"></td></tr>
					<tr><td><?php echo $lang['login_pass']?></td> <td><input class="input_field" type="password" id="adm_pass" name="adm_pass" size="10"></td></tr>
					<tr><td colspan="2" align="right"><input class="button" type="submit" name="submit" value="<?php echo $lang['login_submit']?>"></td></tr>
				</table>
			</form>
		</div>
		<div id="footer">
			<!-- Copyright Information -->
			<div align="center" class="adminformcopy"><?php echo $lang['aut_copy']?></div>
			<div align="center" class="adminformcopy"><?php echo $lang['logo_copy']?></div>
			<div align="center" class="adminformcopy"><?php echo $lang['form_copy']?></div>
			<!-- /Copyright Information -->
		</div>
	</div>

<!-- /Login Form -->