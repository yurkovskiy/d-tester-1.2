<?php

// results.inc - definition results manager functions

function get_true_answer($ans_text, $question_id)
{
	global $DB;
	$DB->query("SELECT ans_id FROM answers WHERE ans_text='".$ans_text."' AND aq_id='".$question_id."'");
	$DB->fetch_row();
	$DB->free_result();
	return $DB->record_row[0];
}

?>