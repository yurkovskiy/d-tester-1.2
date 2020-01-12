<?php

// genpass.php - Automatical password generator

require_once("req.inc");
require_once("inc/functions.inc");

if($_SESSION['adm_priv'] == SUBJECT_MAN) {
    header("Location: index.php");
    break;
}

page_begin($lang['students_href']);

$password = array(); // real passwords
$password_md5 = array(); // md5 hash passwords

$group = $_GET['group']; // group_id

$DB->query("SELECT group_name FROM groups WHERE group_id=$group");
$DB->fetch_row();
$grp = $DB->record_row[0];
$DB->free_result();

echo "<table width=\"50%\" align=\"center\" border=\"0\" class=\"tbl_view_frame\" cellpadding=\"2\" cellspacing=\"2\">\n";
echo "<tr><th colspan=\"3\" align=\"center\" class=\"maintitle\">{$lang['capt_group']}{$grp}</th></tr>\n";
echo "<tr>\n<th class=\"row3\" align=\"center\" width=\"15%\"><b>{$lang['capt_reg_num']}</b></th>\n";
echo "<th class=\"row3\" align=\"left\" width=\"50%\"><b>{$lang['user_name']}</b></th>\n";
echo "<th class=\"row3\" align=\"center\" width=\"35%\"><b>{$lang['user_pass']}</b></th>\n</tr>\n";

$users = $DB->query("SELECT user_id, user_name FROM users WHERE user_group=$group ORDER BY user_id"); // select all user_id from group

while($row = $DB->fetch_row($users))
{
    echo "<tr>\n";

    for($i = 0;$i < $DB->get_fields_num($users);$i++) {
        $row[$i] = stripslashes($row[$i]);
        echo "<td align=\"left\" class=\"row1\">{$row[$i]}</td>\n";
    }
    $password[$row[0]] = generate_password($PARAM['UPASS_LENGTH'], $PARAM['UPASS_REP']);
    echo "<td align=\"left\" class=\"row1\">{$password[$row[0]]}</td>\n";

    $password_md5[$row[0]] = md5($password[$row[0]]);

    $current_password = $password_md5[$row[0]];

    $DB->query("UPDATE users SET user_pass='$current_password' WHERE user_id=$row[0]");

    echo "</tr>\n";
}
echo "<tr><td width=\"100%\" colspan=\"3\" class=\"darkrow2\">&nbsp;</tr>\n</table>\n";

$DB->free_result($users);

page_end();
?>