<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2
 * @name orders.php - pre-exam results unit
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2009 Yuriy Bezgachnyuk
 * 
 * Start date: 27/04/2009
 * Last Update: 24/08/2009 20:53 GMT +02:00
 * 
 * All rights reserved 
 */

require_once("req.inc");

if ($_SESSION['adm_priv'] != ROOT) {
	//echo $_SESSION['adm_priv'];
	header("Location: main.php");
}
	
$step = "";

if (isset($_GET['step'])) $step = $_GET['step'];

if (!isset($step) || ($step == "")) {

	page_begin($lang['orders_title']);
	echo "<script src=\"".$PARAM['FJS_FILE']."\">\n</script>\n";

	require_once("tpls/order_s1.tpl");

	page_end();

}

if ($step == 2) {
	$order_present = false;
	$order_rating = array();
	$order_status = array();
	// if some results present we'll must turn on update profile
	// Showing group

	$group_id = intval($_POST['group_id']);
	$test_id = intval($_POST['test_id']);

	$DB->query("SELECT subjects.subject_name, tests.test_name
				FROM tests, subjects 
				WHERE tests.test_id={$test_id} 
				AND subjects.subject_id = tests.test_subject_id");

	$DB->fetch_row();
	$subject_name = $DB->record_row[0];
	$test_name = $DB->record_row[1];
	$DB->free_result();

	$DB->query("SELECT rating_results.rating, rating_results.status, rating_results.user_id
				FROM rating_results, users
				WHERE rating_results.test_id = {$test_id} 
				AND users.user_group = {$group_id}
				AND rating_results.user_id = users.user_id 
				ORDER BY rating_results.user_id");

	if ($DB->get_num_rows() != 0) {
		$order_present = true;
		while ($row = $DB->fetch_row()) {
			$order_rating[$row[2]] = $row[0];
			$order_status[$row[2]] = $row[1];
		}
	}
	$DB->free_result();

	page_begin($lang['orders_title']);
	echo "<script src=\"".$PARAM['FJS_FILE']."\">\n</script>\n";

	require_once("tpls/order_s2.tpl");

	page_end();
}

if ($step == 3) {
	// Register
	$st_r = $_POST['st_r'];
	$cb = $_POST['cb'];
	$test_id = intval($_POST['test_id']);

	if (isset($st_r)) {
		while (list($user_id, $rating) = each($st_r)) {

			if ($cb[$user_id] != null) $cb[$user_id] = 1;
			else $cb[$user_id] = 0;

			if ($_POST['order_present'] == 0) {
				// INSERT SECTION
				$DB->query("INSERT INTO rating_results(id, user_id, test_id, rating, status, reg_date)
						VALUES(null, '".$user_id."', '".$test_id."', '".$rating."', '".$cb[$user_id]."', NOW())");
			}

			if ($_POST['order_present'] == 1) {
				// UPDATE SECTION
				$DB->query("UPDATE rating_results SET rating = '".$rating."', status = '".$cb[$user_id]."', reg_date = NOW()
							WHERE user_id = {$user_id} AND test_id = {$test_id}");
			}
		}
	}
	header("Location: orders.php");
}

?>