<?PHP

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @name diagram interface unit
 * @version 1.1
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2007 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Last update: 20/06/2007 16:12 GMT +02:00 
 * All rights reserved
 */

require_once("req.inc");
require_once("inc/dia_types.inc");
require_once("inc/dia_func.inc");
require_once("inc/dia_stat.inc");

if(($_SESSION['adm_priv'] == SUBJECT_MAN)
&&($_SESSION['RES_READ'] == "N")) {
	header("Location: main.php");
}

page_begin($lang['diagram_capt']);

if (isset($_POST['dia_type'])) $dia_type = $_POST['dia_type'];

if(!isset($dia_type)) $dia_type = $_GET['dia_type'];

//echo "{$dia_type}\n";

switch($dia_type)
{
	case DIA_GROUPS_QUALITY: {
		$test_id = $_POST['test'];
		$output = dia_g_quality($test_id);
		break;
	}

	case DIA_USERS_GROUP_QUALITY: {
		$test_id = $_GET['test'];
		$group = $_GET['group'];
		$sort = $_GET['sort'];
		$sort_order = $_GET['sort_order'];
		$output = dia_ug_quality($test_id, $group, $sort, $sort_order);
		break;
	}

	case DIA_USER_TEST_DETAILS: {
		$sess_id = $_GET['sess_id'];
		$output = dia_ut_quality($sess_id);
		break;
	}

	case DIA_STAT_MATRIX_ALL_USERS: {
		$group_id = $_GET['group'];
		$test_id = $_GET['test'];
		$output = generate_stable($group_id, $test_id);
		break;
	}

	case DIA_STAT_MATRIX_USER: {
		$group_id = $_GET['group_id'];
		$test_id = $_GET['test_id'];
		$user_id = $_GET['user_id'];
		$output = generate_stable($group_id, $test_id, $user_id);
		break;
	}
	
	case DIA_STAT_MATRIX_NEW: {
		$group_id = $_GET['group'];
		$test_id = $_GET['test'];
		generate_res_matrix($group_id, $test_id);
		exit();
	}
}

// Output Web-page

foreach ($output as $element) {
	echo $element;
}

page_end();

?>