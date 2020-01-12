<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2 RC1
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2012 Yuriy Bezgachnyuk, IF, Ukraine
 */

if (isset($_GET['sess_id'])) {
	// display logout page
	require_once("tpls/adm_logout.tpl");	
}

else {
	header("Location: index.php");
}

?>