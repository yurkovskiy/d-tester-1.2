<?PHP
require_once("req.inc");

if (isset($_POST['adm_name'])) {
	$adm_name = addslashes(trim($_POST['adm_name']));
	$adm_pass = $_POST['adm_pass'];
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];

	if ($action == "logout") {
		$sess_id = session_id();
		session_destroy();
		header("Location: index_end.php?sess_id={$sess_id}");
	}
}

if(isset($adm_pass)) {
	$adm_pass = md5($adm_pass);
	$DB->query("SELECT * FROM admins WHERE admin_name='{$adm_name}' AND password='{$adm_pass}'");
	if($DB->get_num_rows() != 0) {
		$DB->fetch_row();
		$_SESSION['adm_name'] = $adm_name;
		$_SESSION['adm_priv'] = intval($DB->record_row[2]);
		$_SESSION['adm_id'] = $DB->record_row[4];
		$_SESSION['user_priv'] = "admin";
		$_SESSION['real_name'] = $DB->record_row[3];
		$_SESSION['login_stage'] = 0;
		$_SESSION['sess_id'] = session_id();
		$DB->free_result();
		$DB->query("INSERT INTO admin_logs (id,admin_id,remote_ip,e_date,e_time,user_agent,session_id)
					VALUES(null,'".$_SESSION['adm_id']."','".$_SERVER['REMOTE_ADDR']."',CURDATE(), CURTIME(),'".$_SERVER['HTTP_USER_AGENT']."','".$_SESSION['sess_id']."')");
		// SELECT admin permissions from database if adm_priv=2
		if($_SESSION['adm_priv'] == SUBJECT_MAN) {
			$DB->query("SELECT * FROM admin_priv WHERE admin_id='".$_SESSION['adm_id']."' ORDER BY id LIMIT 1");
			if($DB->get_num_rows() == 0) {
				session_destroy();
				Show_Message("DB_ERROR_ACCESS_DENIED_IP");
				exit;
			}
			$DB->fetch_row();
			$_SESSION['SUBJ_ID'] = intval($DB->record_row[2]);
			$_SESSION['SB_READ'] = $DB->record_row[3];
			$_SESSION['SB_WRITE'] = $DB->record_row[4];
			$_SESSION['SB_DELETE'] = $DB->record_row[5];
			$_SESSION['RES_READ'] = $DB->record_row[6];
			$_SESSION['RES_DELETE'] = $DB->record_row[7];
			$DB->free_result();
		}
	}
}

if (isset($_SESSION['user_priv'])) {

	if($_SESSION['user_priv'] == "admin") {
		if($_SESSION['login_stage'] == 0) {
			$_SESSION['enter_time'] = time();
			require_once("tpls/login_wait.tpl");
		}

		if (($_SESSION['login_stage'] == 1) && ($_SESSION['enter_time_offset'] <= time())) {
			require_once("tpls/admin.tpl");
		}
	}
}

else {
	page_begin("Login");
	$mes = "";
	if (isset($_POST['adm_pass'])) $mes = $lang['login_error'];
	echo "<script src=\"".$PARAM['FJS_FILE']."\">\n</script>\n";
	require_once("tpls/adm_login.tpl");
	//echo "\n<br>\n<div align=\"center\" class=\"copyright\">{$lang['logout_warning']}\n<a href=\"\" onClick=\"if (confirm('{$lang['logout_confirm']}')){window.open('index.php?action=logout','_self');return false}else{return false}\">{$lang['logout']}</a>\n</div>\n";
	//require_once("tpls/swf_.tpl");
	page_end();
}

?>