<?php

/**
 * Generating simple calendar table for month
 *
 */
class calendar {

	/**
	 * Calendar class
	 *
	 * @author Christian Lescuyer
	 * @author Yuriy Bezgachnyuk (modified)
	 * @copyright Christian Lescuyer
	 * @version 1.1 release 18.05.2007
	 */

	var $date;
	var $callback;
	var $day_name;
	var $month_name;
	var $date_format;
	var $content = array();

	// Constructor
	// Set defaults
	function calendar()
	{
		# Use strtotime() format.
		$this->date = date("j M Y");
		# Display format
		$this->date_format="j";
		$this->day_name = array ("Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "<font color=\"red\">Нд</font>");
		$this->month_name = array("Січень","Лютий","Березень","Квітень","Травень","Червень","Липень","Серпень","Вересень","Жовтень","Листопад","Грудень");
	}


	/**
	 * Setting calendar date
	 *
	 * @param string $text_date
	 */
	function set_date($text_date) {
		$this->date = $text_date;
	}

	function set_date_format($f) {
		$this->date_format = $f;
	}

	function set_daynames($names) {
		$this->day_name = $names;
	}

	function set_callback($cb) {
		$this->callback = $cb;
	}

	function get_id() {
		return "CLASS calendar v1.1";
	}

	function get_date() {
		return($this->date);
	}

	function get_date_format() {
		return($this->date_format);
	}

	function get_daynames() {
		return($this->day_name);
	}

	function get_callback() {
		return($this->callback);
	}

	/**
	 * This function return the $content class variable
	 *
	 * @return array with HTML code
	 */
	function get_content() {
		return $this->content;
	}

	function output_calendar() // Generating calendar HTML content
	{
		# Relay variable for callback()
		# Apparently, it's not possible to dereference $this->callback()
		$cb = $this->callback;

		# Preliminary calculations
		$t = getdate(strtotime($this->date));
		$today = $t["mday"];
		$year = $t["year"];
		$month = $t["mon"];
		# Get first day of the month (monday = 0)
		$first_wday = ((int) date("w", mktime(0, 0, 0, $month, 1, $year))+6)%7;
		# Last day of the month
		$last_mday = (int) date("d", mktime(0, 0, 0, $month+1, 0, $year));

		# Anchor
		//$this->content[].="<a name=\"calendar\">";

		# Table
		$this->content[].="<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"2\" class=\"calendar\">\n";

		# Table head
		$this->content[].="<tr><td colspan=\"7\" class=\"calendar_header\">{$this->month_name[$month - 1]}&nbsp;&nbsp;&nbsp;{$t['year']}</td></tr>\n<tr>\n";
		for ($j = 0;$j <= 6;$j++) {
			$this->content[].="<th class=\"calendar_title\">";
			$this->content[].="{$this->day_name[$j]}";
			$this->content[].="</th>\n";
		}
		$this->content[].="</tr>\n";

		// Day row
		// A month is displayed on 6 rows
		// except for a 28 days month starting on Monday
		if (($last_mday == 28) and ($first_wday == 0))
		$jmax = 27;
		else
		$jmax = 41;

		for ($j = 0;$j <= $jmax;$j++) {
			# Start new row on Monday
			if ($j % 7 == 0)
			$this->content[].="<tr>\n";

			// Title colour for current day and checking week end days

			if (($j == $today+$first_wday-1) and ($j % 7 == 6)) // if today and weekend day
			$this->content[].="<td class=\"calendar_title_we\">";

			if (($j % 7 == 6) and ($j != $today+$first_wday-1)) // if weekend day and not today
			$this->content[].="<td class=\"calendar_day_we\">";


			if (($j == $today+$first_wday-1) and ($j % 7 != 6)) // if today and not weekend day
			$this->content[].="<td class=\"calendar_title\">";

			if (($j % 7 != 6) and ($j != $today+$first_wday-1)) // if not today and not weekend day
			$this->content[].="<td class=\"calendar_day\">";

			if (($j<$first_wday) or ($j>=$last_mday + $first_wday)) {

				# Empty boxes
				$this->content[].="&nbsp;";
			} else {
				if (isset($cb))
				echo $cb(mktime(0, 0, 0, $month, $j - $first_wday + 1, $year));
				$this->content[].=date($this->date_format, mktime(0, 0, 0, $month, $j - $first_wday + 1, $year));
				if (isset($cb))
				$this->content[].="</a>\n";
			}
			$this->content[].="</td>\n";

			# End of row on Sunday
			if ($j % 7 == 6)
			$this->content[].="</tr>\n";
		}
		$this->content[].="</table>\n";
	}
} // end class calendar

?>	