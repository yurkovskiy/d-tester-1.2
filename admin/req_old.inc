<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage d-tester admin subsystem
 * @name global unit
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2006-2008 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 11/08/2007 13:29 GMT +02:00
 */

require_once("inc/settings.inc");
require_once("../inc/header.inc");
require_once("inc/db_mes.inc");
require_once("../inc/mysql.inc");
require_once("inc/adm_priv.inc");
require_once($PARAM['LANG_SET']);

$DB = new db_driver();
$DB->obj['sql_database'] = $PARAM['DB_DBNAME'];
$DB->obj['sql_host'] = $PARAM['DB_HOST'];
$DB->obj['sql_user'] = $PARAM['DB_USER'];
$DB->obj['sql_pass'] = $PARAM['DB_PASSWORD'];
$DB->connect();

// Remote IP Address
$IP = $_SERVER['REMOTE_ADDR'];

// Create short server variable
$PHP_SELF = $_SERVER['PHP_SELF'];

// Remote IP simple control

/*$DB->query("SELECT * FROM admins_ip WHERE ip_addr='$IP'");
if($DB->get_num_rows() == 0) {
	Show_Message("DB_ERROR_ACCESS_DENIED_IP");
	exit;
}*/

session_start();

if(strpos($_SERVER['SCRIPT_NAME'], "index.php") == false) {
	if($_SESSION['user_priv'] != "admin") {
		Show_Message("DB_ERROR_ACCESS_DENIED_IP");
		exit;
	}
}

?>