<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage d-tester admin subsystem
 * @name diagram functions source
 * @author Yuriy Bezgachnyuk
 * @copyright (c) 2005-2011 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * 
 * Last update: 15/01/2011 19:10 GMT +02:00
 */

/**
 * Generating G. Rash quality matrix for all sessions or for all any user sessions
 *
 * @param int $group_id - group ID
 * @param int $test_id - test ID
 * @param int $user_id - user ID (default $user_id = 0 - generating matrix for all sessions)
 * @return array $out - HTML source
 */

function generate_res_matrix($group_id, $test_id, $user_id = 0) // CVS Edition
{
	global $DB, $lang, $PARAM;

	/**
	 * minimal float value: ex: 0/1 = 0 but log(0) = INF, then 0/1 = DIA_MIN_FLOAT_VALUE
	 */

	define("DIA_MIN_FLOAT_VALUE", 0.01);

	define("DIA_MAX_FLOAT_VALUE", 99.0);

	$DB->query("SELECT group_name FROM groups WHERE group_id={$group_id}");
	$DB->fetch_row();
	$RES['group_name'] = $DB->record_row[0];

	$tbl_row_main = array(array());
	$tbl_row_user = array();

	// New Arrays
	$tbl_row_main_n = array(array());
	$tbl_row_user_n = array();
		
	$DB->query("SELECT subjects.subject_name, tests.test_name
				FROM subjects, tests
				WHERE tests.test_id = {$test_id} 
				AND subjects.subject_id = tests.test_subject_id");
	$DB->fetch_row();
	$RES['subject_name'] = $DB->record_row[0];
	$RES['test_name'] = $DB->record_row[1];
	$DB->free_result();

	if($user_id == 0) {

		$DB->query("SELECT users.user_name, session_results.true_answers
					FROM session_results, users
					WHERE users.user_group = {$group_id} 
					AND session_results.user_id = users.user_id 
					AND session_results.test_id = {$test_id} 
					ORDER BY session_results.sess_id ASC");
	}

	$Xi = array();
	$Rj = array();

	$count = 0;
	while($DB->fetch_row()) {
		$tbl_row_user[$count] = $DB->record_row[0];
		$tbl_row_main[$count] = extract_aq($DB->record_row[1]);
		array_shift($tbl_row_main[$count]);
		$count++;
	}

	$DB->free_result();
	//print_r($tbl_row_main);
	?>
	<style type="text/css">
	* {
	  font-size: 16px;
	  font-family: Verdana;
	}
	.cell {
	  border-left: 1px solid #000;
	  border-top: 1px solid #000;
	  padding: 5px 5px 5px 5px;
	  width: 30px;
	  font-weight: bold;
	}
	.cell:last-of-type {
	  border-right: 1px solid #000;
	}
	
	cell:last-child {
	  border-bottom: 1px solid #000;
	}
	
	</style>
	
	<!-- Binary matrix [start] -->
	<script src="./js/jquery-1.6.2.min.js" type="text/javascript"></script>
	<script src="./js/hc/highcharts.js" type="text/javascript"></script>
		
	<table id="rowtable" cellpadding="0" cellspacing="0" width="85%">
	<tr>
	<?php
	for ($i = 0;$i <= sizeof($tbl_row_main[0]);$i++) {
		if ($i == 0) echo "<td width=\"20%\" class=\"cell\">Студент</td>\n";
		else echo "<td class=\"cell\">{$i}</td>\n";
	}
	?>
	<td class="cell">Xi</td>
	</tr>
	<?php
	$style = "";
	$count = 0;
	foreach ($tbl_row_main as $row) {
		$jcount = 0;
		echo "<!-- USER {$count} -->\n<tr>\n";
		echo "<td class=\"cell\">{$tbl_row_user[$count]}</td>\n";
		foreach ($row as $element) {
			if (intval($element) == 0) {
				$style = "style='background-color: yellow; color: black;'";
				$Xi[$count] += 0;
				$Rj[$jcount]+=0;
			}
			if (intval($element) == 1) {
				$style = "style='background-color: #8AED6C; color: black;'";
				$Xi[$count]++;
				$Rj[$jcount]++;
			}
			echo "<td class=\"cell\" {$style}>{$element}</td>\n";
			$jcount++;
		}
		echo "<td class=\"cell\">{$Xi[$count]}</td>\n";
		echo "</tr>\n";
		$count++;
	}
	$sum_Xi = array_sum($Xi);

	?>
	<tr>
	<td class="cell" align="right">Rj</td>
	<?php
	for ($i = 0;$i < sizeof($tbl_row_main[0]);$i++) {
		echo "<td class=\"cell\">{$Rj[$i]}</td>\n";
	}
	echo "<td class=\"cell\">{$sum_Xi}</td>\n";
	?>
	
	</tr>
	</table>
	<?php
	//print_r($Xi);
	//echo "{$sum_Xi}";
	/*echo "Xi<br>\n";
	print_r($Xi);
	echo "<br>\n";*/
	arsort($Xi);
	/*print_r($Xi);

	echo "<br>Rj<br>\n";
	print_r($Rj);
	echo "<br>\n";*/
	arsort($Rj);
	//print_r($Rj);

	echo "<br><br><br>\n";
	?>
	<!-- Matrix #2 -->
	<table id="rowtable_1" cellpadding="0" cellspacing="0" width="85%">
	<tr>
	<td width="20%" class="cell">Студент</td>
	<?php
	foreach ($Rj as $key => $value) {
		$c = $key + 1;
		echo "<td class=\"cell\">{$c}</td>\n";
	}
	?>
	<td class="cell">Xi</td>
	</tr>
	
	<?php

	foreach ($Xi as $xkey => $vx) {
		echo "<!-- USER -->\n<tr>\n";
		echo "<td class=\"cell\">{$tbl_row_user[$xkey]}</td>\n";
		foreach ($Rj as $rkey => $rx) {
			//echo "<td class=\"cell\">&nbsp;</td>\n";
			if (intval($tbl_row_main[$xkey][$rkey]) == 0) $style = "style='background-color: yellow; color: black;'";
			if (intval($tbl_row_main[$xkey][$rkey]) == 1) $style = "style='background-color: #8AED6C; color: black;'";
			echo "<td class=\"cell\" {$style}>{$tbl_row_main[$xkey][$rkey]}</td>\n";
		}
		echo "<td class=\"cell\">{$vx}</td>\n";
		echo "</tr>\n";
	}
	?>
	<tr>
	<td class="cell" align="right">Rj</td>
	
	<?php
	foreach ($Rj as $R) {
		echo "<td class=\"cell\">{$R}</td>\n";
	}
	echo "<td class=\"cell\">{$sum_Xi}</td>\n";
	?>
	
	</tr>
	</table>
	
	<?php
	
	// Drop if Xi = 0, Xi = full or Rj = 0, Rj = full
	//print_r($Xi);
	$icount = 0;
	foreach ($Xi as $xkey => $vx) {
		//echo "{$xkey}&nbsp;&nbsp;\n";
		$jcount = 0;
		foreach ($Rj as $rkey => $rx) {
			// Check user probability
			if (($vx == 0) || ($vx == sizeof($Rj))) {
				$icount--; // reduce array external counter
				break;
			}

			if (($rx == 0) || ($rx == sizeof($Xi))) {
				continue;
			}

			else {
				//$tbl_row_main_n[$xkey][$rkey] = $tbl_row_main[$xkey][$rkey];
				$tbl_row_user_n[$icount] = $tbl_row_user[$xkey];
				$tbl_row_main_n[$icount][$jcount] = $tbl_row_main[$xkey][$rkey];
				$jcount++;
				//echo "&nbsp;{$tbl_row_main_n[$xkey][$rkey]}&nbsp;\n";
			}
			// End of check user probability
		}
		//echo "<br>\n";
		$icount++;
	}
	

	$a = sizeof($tbl_row_main);
	$b = sizeof($tbl_row_main[0]);
	echo "{$a}x{$b}<br>\n";

	$a = sizeof($tbl_row_main_n);
	$b = sizeof($tbl_row_main_n[0]);
	echo "{$a}x{$b}<br>\n";
	
	$Xi_n = array();
	$Rj_n = array();
	$Wj_n = array();
	$Pj_n = array();
	$qj_n = array();
	$pjqj = array();
	$Xi_n_2 = array();
	// Show New Reduced matrix [Matrix #3]
	?>
	<table id="rowtable_2" cellpadding="0" cellspacing="0" width="105%">
	<tr>
	
	<td width="20%" class="cell">Студент</td>
	<?php
	for ($i = 0;$i < sizeof($tbl_row_main_n[0]);$i++) {
		$c = $i + 1;
		echo "<td class=\"cell\">{$c}</td>\n";
	}
	?>
	<td class="cell">Xi</td>
	<!--<td class="cell">Xi<sup>2</sup></td>-->
	
	</tr>
	
	<?php
		
	for ($i = 0;$i < sizeof($tbl_row_main_n);$i++) {
		echo "<tr>\n";
		echo "<!-- USER -->\n<tr>\n";
		echo "<td class=\"cell\">{$tbl_row_user_n[$i]}</td>\n";
		for ($j = 0;$j < sizeof($tbl_row_main_n[0]);$j++) {
			if (intval($tbl_row_main_n[$i][$j]) == 0) {
				$style = "style='background-color: yellow; color: black;'";
				$Xi_n[$i] += 0;
				$Rj_n[$j]+=0;
			}
			if (intval($tbl_row_main_n[$i][$j]) == 1) {
				$style = "style='background-color: #8AED6C; color: black;'";
				$Xi_n[$i]++;
				$Rj_n[$j]++;
			}
			echo "<td class=\"cell\" {$style}>{$tbl_row_main_n[$i][$j]}</td>";
		}
		echo "<td class=\"cell\">{$Xi_n[$i]}</td>\n";
		//$Xi_n_2[$i] = pow($Xi_n[$i], 2);
		//echo "<td class=\"cell\">{$Xi_n_2[$i]}</td>\n";
		echo "</tr>\n";
	}
	
	$sum_Xi_n = array_sum($Xi_n);
	$sum_Xi_n_2 = array_sum($Xi_n_2);
	
	?>
	<!-- Rj -->
	<tr>
	<td class="cell" align="right">Rj</td>
	<?php
	for ($i = 0;$i < sizeof($Rj_n);$i++) {
		echo "<td class=\"cell\">{$Rj_n[$i]}</td>\n";
	}
	echo "<td class=\"cell\">{$sum_Xi_n}</td>\n";
	//echo "<td class=\"cell\">{$sum_Xi_n_2}</td>\n";
	?>
	
	</tr>
	
	<!-- Wj -->
	<tr>
	<td class="cell" align="right">Wj</td>
	<?php
	for ($i = 0;$i < sizeof($Rj_n);$i++) {
		$Wj_n[$i] = sizeof($tbl_row_user_n) - $Rj_n[$i];
		echo "<td class=\"cell\">{$Wj_n[$i]}</td>\n";
	}
	?>
	</tr>
	
	<!-- Pj -->
	<tr>
	<td class="cell" align="right">Pj</td>
	<?php
	for ($i = 0;$i < sizeof($Rj_n);$i++) {
		$Pj_n[$i] = round(($Rj_n[$i] / sizeof($tbl_row_user_n)), 3);
		echo "<td class=\"cell\">{$Pj_n[$i]}</td>\n";
	}
	?>
	</tr>
	
	<!-- qj -->
	<tr>
	<td class="cell" align="right">qj</td>
	<?php
	for ($i = 0;$i < sizeof($Rj_n);$i++) {
		$qj_n[$i] = 1 - $Pj_n[$i];
		echo "<td class=\"cell\">{$qj_n[$i]}</td>\n";
	}
	?>
	</tr>
	
	<!-- pj*qj -->
	<tr>
	<td class="cell" align="right">pj*qj</td>
	<?php
	for ($i = 0;$i < sizeof($Rj_n);$i++) {
		$pjqj[$i] = round(($Pj_n[$i] * $qj_n[$i]), 3);
		echo "<td class=\"cell\">{$pjqj[$i]}</td>\n";
	}
	?>
	</tr>
	
	</table>
	
	<?php
	
	// Convert array to string
	
	$pjqj_of_qj_str = "[";
	$pjqj_of_pj_str = "[";
	for ($i = 0;$i < sizeof($pjqj);$i++) {
		$pjqj_of_qj_str .= "[".$qj_n[$i].", ".$pjqj[$i]."],";
		$pjqj_of_pj_str .= "[".$Pj_n[$i].", ".$pjqj[$i]."],";
	}
	
	$pjqj_of_qj_str .= "]";
	$pjqj_of_pj_str .= "]";
	
	//echo "{$pjqj_of_qj_str}";
	
	?>
	
	<!-- Highcharts showing graphics -->
	<script type="text/javascript">
	var chart;
	$(document).ready(function() {
		chart = new Highcharts.Chart({
			chart: {
				renderTo: 'container',
				defaultSeriesType: 'line',
				marginRight: 130,
				marginBottom: 25
			},
			
			title: {
				text: 'Графіки залежностей pj*qj = f(qj) і pj*qj = f(pj)',
				x: -20
			},
			
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				x: -10,
				y: 100,
				borderWidth: 0
			},
						
			yAxis: {
				title: {
					text: 'Параметр pj*qj'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}]
			},
			
			series: [{
				name: 'pj*qj = f(qj)',
				data: <?php echo "{$pjqj_of_qj_str}"?>
				/*data: [[5, 2], [6, 3], [8, 2]]*/
			}, {
				name: 'pj*qj = f(pj)',
				data: <?php echo "{$pjqj_of_pj_str}"?>
			}]
			
		});
	});
	</script>
	<!-- /Highcharts showing graphics -->
	
	
	<div id="container" style="width: 800px; height: 500px; margin: 0 auto"></div>
	
	<?php
	
	page_end();
}

?>