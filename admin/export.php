<?php

/**
 * @(#)export.php	1.1	04/04/2007
 *
 * @package d-tester 
 * @version 1.1
 * @subpackage d-tester administrative subsystem
 * @name export interface unit
 * @author Yuriy V. Bezgachnyuk
 * @copyright Yuriy V. Bezgachnyuk
 *
 * Start date  03/09/2006 08:01 GMT +02:00
 * Last Update 07/05/2007 15:36 GMT +02:00
 *
 * All Rights Reserved
 */

require_once("req.inc");
require_once("inc/ex_const.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}

$EX_FILE_C = array();
$EX_FILE_C_A = array();
$EX_FILE_C_Q = array();

if(!isset($action)) {
	page_begin($lang['export_href']);
	require_once("tpls/ex_form.tpl");
	page_end();
	exit;
}

if($action == "export") {
	$ex_format = $_POST['ex_format'];
	$cur_date = date("d/m/Y H:i:s");
	switch($ex_format) {
		/**
		 * @deprecated from d-tester R25_03_2009
		 */
		/*case EX_dtPHP_FORMAT: // [dt-PHP] Format
		{
			require_once("tpls/ex_format/ex_dtphp.tpl");
			break;
		}*/
		
		case EX_GIFT_FORMAT: // GIFT Format
		{
			require_once("tpls/ex_format/ex_gift.tpl");
			break;
		}
		
		case EX_dtXML_FORMAT: // d-tester XML
		{ 
		    require_once("tpls/ex_format/ex_dtXML.tpl");
		    break;
		}
		
		case EX_MOODLEXML_FORMAT: // Moodle XML
		{
			require_once("tpls/ex_format/ex_moodle_XML.tpl");
			break;
		}
		
		case EX_dtXMLv2_FORMAT: // d-tester XMLv2 for dtapi 2.1
		{
				require_once("tpls/ex_format/ex_dtXMLv2.tpl");
				break;
		}
	}
}

?>