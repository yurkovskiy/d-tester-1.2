<?php

// db_mes.inc - definitions Database Error Messages
// (c) 2005 by Yuriy Bezgachnyuk, IF, Ukraine

require_once("inc/settings.inc");
require_once($PARAM['LANG_DB_SET']);

function Show_Message($ERR_CONST)
{
	$color="#FF0000";
	switch ($ERR_CONST)
	{
		case "DB_REG_GOOD": 
		{
			$color="0000FF";
			break;
		}
	}
	?>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="cache-control" content="no-cache">
	<meta name="Author" content="Юрій Безгачнюк">
	<style>
	.tablefill   { border:1px solid #345487;background-color:#F5F9FD;padding:6px;  }
	</style>
	</head>
	<body>
	<table width='100%' height='85%' align='center'>
	<tr>
  	<td valign='middle'>
	<table align='center' cellpadding="4" class="tablefill">
	  <tr> 
		<td width="100%" align="center" nowrap="nowrap">
		<br />
		  <font color="<?php echo $color?>"><b><?php echo constant($ERR_CONST)?></b></font>
		  <br /><br />
	      
	    </td>
	  </tr>
	</table>

  </td>
</tr>
</table>
</body>
</html>
	<?PHP
	exit;
}

?>