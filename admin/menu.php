<?php
require_once("req.inc");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html version="4.01">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title><?php echo $lang['system_version'];?></title>
<LINK rel="stylesheet" href="styles/base.css" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<TABLE width="100%" border="0" cellpadding="3" cellspacing="4" class="tbl_view_frame">
<TR><TD align="center" class="maintitle"><?php echo $lang['menu_href']?></TD></TR>
<TR><TD class="row1" align="center"><a href="main.php" target="mainFrame"><?php echo $lang['sys_stat_href'];?></a></TD></TR>
<?php
if(($_SESSION['adm_priv'] == ROOT)
    ||($_SESSION['adm_priv'] == ADMIN)) {
	echo "<TR><TD class=\"row1\" align=\"center\"><a href=\"groups.php\" target=\"mainFrame\">{$lang['groups_students_href']}</a></TD></TR>\n";
}
if ($_SESSION['adm_priv'] == ROOT) {
	echo "<TR><TD class=\"row1\" align=\"center\"><a href=\"orders.php\" target=\"mainFrame\">{$lang['ORDERS_href']}</a></TD></TR>\n";
}
?>
<TR><TD class="row1" align="center"><a href="subjects.php" target="mainFrame"><?php echo $lang['subjects_href'];?></a></TD></TR>
<TR><TD class="row1" align="center"><a href="results.php" target="mainFrame"><?php echo $lang['results_href'];?></a></TD></TR>
<?php
if($_SESSION['adm_priv'] == ROOT) {
	echo "<TR><TD class=\"row1\" align=\"center\"><a href=\"root_menu.php\" target=\"mainFrame\">{$lang['root_section']}</a></TD></TR>\n";
}
?>
<TR><TD class="row1" align="center"><a href="sess.php" target="mainFrame"><?php echo $lang['SESS_href'];?></a></TD></TR>
<TR><TD class="row1" align="center"><a href="logs.php" target="mainFrame"><?php echo $lang['logs'];?></a></TD></TR>
<TR><TD class="row1" align="center"><a href="help.php" target="mainFrame"><?php echo $lang['HELP_href'];?></a></TD></TR>
<?php
echo "<TR><TD class=\"row1\" align=\"center\">\n<a href=\"\" onClick=\"if (confirm('{$lang['logout_confirm']}')){window.open('index.php?action=logout','_top');return false}else{return false}\">{$lang['logout']}</a>\n</TD></TR>\n";
?>
</TABLE>
</div>
</body>
</html>