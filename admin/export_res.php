<?php

/**
 * @package d-tester 
 * @version 1.2
 * @subpackage d-tester administrative subsystem
 * @name export interface unit [RESULTS]
 * @author Yuriy V. Bezgachnyuk
 * @copyright Yuriy V. Bezgachnyuk
 *
 * Start date  28/03/2008 19:19 GMT +02:00
 * Last update 
 *
 * All Rights Reserved
 */

require_once("req.inc");
require_once("inc/ex_const_res.inc");
require_once("inc/dia_func.inc");

define("EX_RES_FILENAME", "dtRES_");
define("FILE_EXTENSION", "txt");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

$ex_format = intval($_GET['ex_format']);

$file_name = EX_RES_FILENAME;

switch($ex_format) {
	case EX_RES_GRASH_TO_WINSTEPS: {
		$file_info = generate_stable_file($_GET['group_id'], $_GET['test_id'], $_GET['user_id']);

		if (!empty($file_info)) {
			$file_name.="RASH".".".FILE_EXTENSION; 
			
			header("Content-type: application/txt");
			header("Content-Disposition: attachment; filename={$file_name}");
			
			//print_r($file_info);

			for($i = 0;$i < sizeof($file_info);$i++) echo "{$file_info[$i]}\r\n";
			
			// End of saving
		}

		break;
	}
	
	case EX_RES_GRASH_TO_CKPLG_MOODLE: {
		generate_stable_XML_file($_GET['group_id'], $_GET['test_id'], $_GET['user_id']);
		
		
		
		break;
	}
}

?>