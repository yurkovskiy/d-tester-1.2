<?php
require_once("req.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?php echo $lang['system_version'];?></title>
<LINK rel="stylesheet" href="styles/base.css" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<TABLE width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD align="center" class="maintitle"><?php echo $lang['root_section']?></TD></TR>
<?php
if($_SESSION['adm_priv'] == ROOT) {
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"sql.php\" target=\"mainFrame\">{$lang['SQL_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"ip_cont.php\" target=\"mainFrame\">{$lang['IP_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"adm_logs.php\" target=\"mainFrame\">{$lang['adm_logs_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"admins.php\" target=\"mainFrame\">{$lang['admins_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"copy_test.php\" target=\"mainFrame\">{$lang['cptest_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"export.php\" target=\"mainFrame\">{$lang['export_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"import.php\" target=\"mainFrame\">{$lang['import_href']}</a></TD></TR>\n";
	echo "<TR><TD class=\"row1\" align=\"left\"><a href=\"db_backup.php\" target=\"mainFrame\">{$lang['backup_href']}</a></TD></TR>\n";
}
?>
<tr><td width="100%" class="darkrow2">&nbsp;</td></tr>
</TABLE>
</div>
</body>
</html>