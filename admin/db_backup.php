<?php

/**
 * @package d-tester
 * @subpackage admin subsystem 
 * @version 1.2 RC1
 * @name database backup unit
 * @author Yuriy V. Bezgachnyuk
 * @copyright 2007-2008 Yuriy V. Bezgachnyuk
 *
 * Start date:  12/12/2007 19:45 GMT +02:00
 * Last update: 15/01/2008 19:06 GMT +02:00 
 *
 * All Rights Reserved
 */

require_once("req.inc");

$out = array(); // file data array [for table structure]
$out_data = array(); // file data array [for table data]
$ext = "sql"; // file extension
$sql = ""; // temporary sql script

$counter = 0;
$d_counter = 0;

// File constants
define("MAIN_PART_FILE_NAME", "db_backup_");
define("PHP_DATE_FORMAT", "d_m_Y_H-i");

$filename = MAIN_PART_FILE_NAME.date(PHP_DATE_FORMAT);

// what tables will be backup

//$db_backup_tbl = array("admins", "admins_ip", "admin_priv", "answers", "dt_enter_logs", "groups", "index_page", "questions", "session_results", "subjects", "test_details", "tests", "time_table", "users");
$db_backup_tbl = $DB->get_table_names();

// main process

@set_time_limit($PARAM['TIME_LIMIT']); // setting new time limit for this script

// extract tables structures

for ($i = 0;$i < sizeof($db_backup_tbl);$i++) {
	$sql = "CREATE TABLE {$db_backup_tbl[$i]}\n(\n";
	$DB->query("SELECT * FROM {$db_backup_tbl[$i]}");
	$num_fields = $DB->get_fields_num();
	$num_rows = $DB->get_num_rows();
	
	$fl = "";

	for ($nf = 0;$nf < $num_fields;$nf++) {
		$field_name = $DB->get_field_name($nf);
		$field_type = $DB->get_field_type($nf);
		$field_len = $DB->get_field_len($nf);
		$field_flags = $DB->get_field_flags($nf);
		
		$sql.=" $field_name ";
		
		$is_numeric = false;
		
		switch (strtolower($field_type)) {
			case "int": {
				$sql.="int";
				$is_numeric = true;
				break;
			}
			
			case "real": {
				$sql.="real";
				$is_numeric = true;
				break;
			}
			
			case "string": {
				$sql.="varchar({$field_len})";
				$is_numeric = false;
				break;
			}
			
			case "unknown": {
				switch ($field_len) {
					default: {
						$sql.="int";
						$is_numeric = true;
						break;
					}
				} // end of switch
				break;
			} // end of unknown
			
			case "timestamp": {
				$sql.="timestamp";
				$is_numeric = true;
				break;
			}
			
			case "date": {
				$sql.="date";
				$is_numeric = false;
				break;
			}
			
			case "datetime": {
				$sql.="datetime";
				$is_numeric = false;
				break;
			}
			
			case "time": {
				$sql.="time";
				$is_numeric = false;
				break;
			}
		} // end of switch
		
		// Field Flags Parsing
		if (strstr($field_flags, "unsigned") == true) {
			if ($field_type != "timestamp") $sql.=" unsigned";
		}
		
		if (strstr($field_flags, "zerofill") == true) {
			if ($field_type != "timestamp") $sql.=" zerofill";
		}
		
		if (strstr($field_flags, "not_null") == true) $sql.=" not null";
		if (strstr($field_flags, "auto_increment") == true) $sql.=" auto_increment";
		if (strstr($field_flags, "primary_key") == true) $sql.=" primary key";
		// End of field flags parser
		
		if ($nf < ($num_fields - 1)) {
			$sql.=",\n";
			$fl.=$field_name.", ";
		}
		
		else {
			$sql.="\n);\n\n";
			$fl.=$field_name;
		}
		
		$fna[$nf] = $field_name;
		$ina[$nf] = $is_numeric;
		
	} // end of for [field parser]
	
	// Extract table data
	for ($nr = 0;$nr < $num_rows;$nr++) {
		$sql_data = "INSERT INTO {$db_backup_tbl[$i]} ({$fl}) VALUES (";
		$row = $DB->fetch_row();
		for ($df = 0;$df < $num_fields;$df++) {
			$data = strval($row[$df]);
			if ($ina[$df] == true) $sql_data.=intval($data);
			else $sql_data.="\"".$DB->escape_string($data)."\"";
			
			if ($df < ($num_fields - 1)) $sql_data.=", ";
		}
		$sql_data.=");";
		$out_data[$d_counter] = $sql_data;
		$d_counter++;
	}
	
	$out[$counter] = $sql;
	$counter++;
	$DB->free_result();
}

// Saving file
header("Content-type: plain/text");
header("Content-Disposition: attachment; filename={$filename}.{$ext}");

for ($i = 0;$i < sizeof($out);$i++) {
	echo "{$out[$i]}\r\n";
}

for ($i = 0;$i < sizeof($out_data);$i++) {
	echo "{$out_data[$i]}\r\n";
}

?>