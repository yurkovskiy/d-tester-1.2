<?php

// reg_admin.php - register admin with 2 level of priviledges only

require_once("req.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: main.php");
	exit;
}

$what = $_GET['what'];

$ADMIN['LOGIN_NAME'] = addslashes(trim($_POST['login_name']));
$ADMIN['USER_NAME'] = addslashes(trim($_POST['user_name']));
$ADMIN['PASSWORD'] = trim($_POST['pass_word']);
$ADMIN['CONF_PASS'] = trim($_POST['conf_pass']);
$ADMIN['PRIV'] = $_POST['apriv'];
$ADMIN['SUBJ_ID'] = $_POST['subj_id'];
$ADMIN['SB_READ'] = $_POST['SB_READ'];
$ADMIN['SB_WRITE'] = $_POST['SB_WRITE'];
$ADMIN['SB_DELETE'] = $_POST['SB_DELETE'];
$ADMIN['RES_READ'] = $_POST['RES_READ'];
$ADMIN['RES_DELETE'] = $_POST['RES_DELETE'];

if($ADMIN['SB_READ']=="on") $ADMIN['SB_READ']="Y"; else $ADMIN['SB_READ']="N";
if($ADMIN['SB_WRITE']=="on") $ADMIN['SB_WRITE']="Y"; else $ADMIN['SB_WRITE']="N";
if($ADMIN['SB_DELETE']=="on") $ADMIN['SB_DELETE']="Y"; else $ADMIN['SB_DELETE']="N";
if($ADMIN['RES_READ']=="on") $ADMIN['RES_READ']="Y"; else $ADMIN['RES_READ']="N";
if($ADMIN['RES_DELETE']=="on") $ADMIN['RES_DELETE']="Y"; else $ADMIN['RES_DELETE']="N";

switch($what)
{
	case "new_admin":
	{
		$DB->query("SELECT admin_name FROM admins WHERE admin_name='".$ADMIN['LOGIN_NAME']."'");
		if($DB->get_num_rows() != 0) {
			Show_Message("DB_ERROR_INPUT_DATA");
		}
		$DB->free_result();
		if(strstr($ADMIN['PASSWORD'], $ADMIN['CONF_PASS'])==null) {
			Show_Message("DB_ERROR_INPUT_PASSWORDS");
		}

		$ADMIN['PASSWORD'] = md5($ADMIN['PASSWORD']);

		$query_tbl_admins="INSERT INTO admins (admin_name, password, priv, real_name, admin_id)
					VALUES('".$ADMIN['LOGIN_NAME']."','".$ADMIN['PASSWORD']."','".$ADMIN['PRIV']."','".$ADMIN['USER_NAME']."',null)";

		//echo "{$query_tbl_admins}<br>";

		$DB->query($query_tbl_admins);

		$DB->query("SELECT MAX(admin_id) FROM admins");
		$DB->fetch_row();
		$ADMIN['ADMIN_ID'] = $DB->record_row[0];
		$DB->free_result();

		$query_tbl_admin_priv="INSERT INTO admin_priv (id, admin_id, subject_id, sb_read, sb_write, sb_delete, res_read, res_delete)
					VALUES(null,'".$ADMIN['ADMIN_ID']."','".$ADMIN['SUBJ_ID']."','".$ADMIN['SB_READ']."','".$ADMIN['SB_WRITE']."','".$ADMIN['SB_DELETE']."','".$ADMIN['RES_READ']."','".$ADMIN['RES_DELETE']."')";

		//echo "{$query_tbl_admin_priv}<br>";

		$DB->query($query_tbl_admin_priv);

		require_once("admins.php");

		break;
	}
}

?>