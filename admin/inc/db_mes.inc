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
	<HTML>
	<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<META http-equiv="cache-control" content="no-cache">
	<META name="Author" content="Юрій Безгачнюк">
	<STYLE>
	.tablefill   { border:1px solid #345487;background-color:#F5F9FD;padding:6px;  }
	</STYLE>
	</HEAD>
	<BODY>
	<table width='100%' height='85%' align='center'>
	<tr>
  	<td valign='middle'>
	<table align='center' cellpadding="4" class="tablefill">
	  <tr> 
		<td width="100%" align="center" nowrap="nowrap">
		<br />
		  <FONT color="<?php echo $color?>"><b><?php echo constant($ERR_CONST)?></b></FONT>
		  <br /><br />
	      
	    </td>
	  </tr>
	</table>

  </td>
</tr>
</table>
</BODY>
</HTML>
	<?PHP
	exit;
}

?>