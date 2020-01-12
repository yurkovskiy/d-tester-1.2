<?php

/**
 * @package d-tester
 * @subpackage admin subsystem
 * @version 1.2 RC1
 * @name print_test_simple.php - Test package print version
 * @author Yuriy V. Bezgachnyuk
 * @copyright 2008 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date: 16/12/2008 20:41 CMT +02:00
 * Last update: 17/12/2008 21:30 GMT +02:00
 */

define("MAX_IMG_WIDTH", 512);

require_once("req.inc");
require_once("inc/functions.inc");
require_once("../inc/q_types.inc");

$test_id = $_GET['test_id'];
$cur_date = date("d/m/Y H:i:s");

page_begin($lang['capt_tasks']);

?>

<style type="text/css">

.level_info {
	text-align		: left;
	font-size		: 12px;
	font-family		: Verdana;
	font-weight		: bold;
	padding-left	: 10px;
}

.pq_table {
	width			: 98%;
	/*border			: 1px solid #000;*/
	margin			: 0px auto;
	font-size		: 12px;
	font-family		: Verdana, Tahoma;
}

.ans_table {
	width			: 98%;
	/*border			: 1px solid #000;*/
	margin			: 0px auto;
}

.ans_cell {
	width			: 100%;
	border			: 1px solid #000;
	font-family		: Tahoma;
	font-size		: 11px;
	text-align		: center;
	vertical-align	: top;
}

.pq_cell {
	width			: 50%;
	border			: 1px solid #000;
	text-align		: center;
	vertical-align	: top;
}
</style>

<?php

$DB->query("SELECT subjects.subject_name FROM subjects, tests
			WHERE tests.test_id = {$test_id} 
			AND subjects.subject_id = tests.test_subject_id");

$DB->fetch_row();
$subject_name = $DB->record_row[0];
$DB->free_result();

$DB->query("SELECT test_name FROM tests WHERE test_id = {$test_id}");
$DB->fetch_row();
$test_name = $DB->record_row[0];
$DB->free_result();

echo "<!-- Order Structure -->\n";
echo "<div align=\"center\" class=\"tbl_view_frame\"><b>{$lang['capt_tasks']}</b></div><br>\n";
echo "<table width=\"95%\" align=\"center\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" class=\"tbl_view_frame\">\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['test_subject']}:</b>&nbsp;&nbsp;{$subject_name}</td></tr>\n";
echo "<tr><td width=\"50%\" align=\"left\"><b>{$lang['capt_test']}:</b>&nbsp;&nbsp;{$test_name}</td></tr>\n";
echo "</table><br>\n\n";

echo "<!-- Questions Information -->\n";

$test_details = $DB->query("SELECT level_id, level_rate FROM test_details WHERE test_id = {$test_id} ORDER BY level_id");

$tq_count = 0;

if ($DB->get_num_rows() != 0) {

	// Level cycle
	while ($td_row = $DB->fetch_row($test_details)) {

		$level = $td_row[0]; // level number
		$level_rate = $td_row[1]; // level rate
		
		echo "<div class=\"level_info\">{$lang['q_group']}&nbsp;{$level}&nbsp;[{$lang['level_rate']}]:&nbsp;{$level_rate}\n</div>\n<br>\n\n";

		$questions = $DB->query("SELECT * FROM questions WHERE q_test_id={$test_id} AND q_level={$level} ORDER BY question_id");

		// questions cycle
		?>
		<table class="pq_table" cellpadding="3" cellspacing="3">
		<?php
		while ($q_row = $DB->fetch_row($questions)) {

			for ($k = 0;$k < $DB->get_fields_num($questions);$k++) $q_row[$k] = stripslashes(stripslashes($q_row[$k]));

			if ($tq_count == 0)
			echo "<tr>\n";

			?>
						
			<td class="pq_cell">
			<?php
			if(($q_row[4] == SIMPLE_CHOICE_IMG)
			||($q_row[4] == MULTI_CHOICE_IMG)
			||($q_row[4] == INPUT_FIELD_IMG)
			||($q_row[4] == 7)
			||($q_row[4] == NUMERICAL_IMG)) {
				$source = $PARAM['TEST_BASE_URL'].$test_id."/".$q_row[5];
				$im_size = getimagesize($source);
				
				$width = reduce_img_width($im_size[0], MAX_IMG_WIDTH);
								
				echo "<b>[{$q_row[0]}]&nbsp;&nbsp;<font color=red>{$q_row[2]}</font></b>\n<br>\n<img src=\"{$source}\" width=\"{$width}\" border=\"0\">\n";
			}
			else {
				echo "<b>[{$q_row[0]}]&nbsp;&nbsp;<font color=red>{$q_row[2]}</font></b>\n";
			}
				$tq_count++;
			?>
			
			<!-- Answers information -->
			<table class="ans_table" cellpadding="3" cellspacing="3">
			<?php
			$ans_query = $DB->query("SELECT * FROM answers WHERE aq_id={$q_row[0]} ORDER BY ans_id");
			
			while ($a_row = $DB->fetch_row($ans_query)) {
				$a_row[4] = stripslashes($a_row[4]);
				
				echo "<tr>\n";
				
				if ($a_row[2] != "0") {
					$a_media_file = $PARAM['TEST_BASE_URL'].$test_id."/".$a_row[3];
					$im_size = getimagesize($a_media_file);
					
					$width = reduce_img_width($im_size[0], MAX_IMG_WIDTH);
					echo "<td class=\"ans_cell\"><img src=\"{$a_media_file}\" width=\"{$width}\" border=\"0\">\n<br>\n{$a_row[4]}\n</td>\n";
				}
				
				else {
					echo "<td class=\"ans_cell\">{$a_row[4]}\n</td>\n";
				}
				echo "</tr>\n";
			}
			?>
			</table>
			<!-- /Answers information -->
			
			</td>
			
			<?php
			if ($tq_count == 2) {
				$tq_count = 0;
				echo "</tr>\n";
			}

			} // questions cycle
		?>
		</table>
		<br><br>
		<?php

		} // level cycle

	}

	echo "<div align=\"center\" class=\"copyright\">{$lang['print_footer']}<br><b>{$cur_date}</b></div>\n";

	page_end();

?>