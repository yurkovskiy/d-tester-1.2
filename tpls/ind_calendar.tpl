<?php

// K-Zone Block File

require_once("inc/class.Calendar.php");

$to_page['CALENDAR'].="<!-- Calendar class TPL -->\n";
$to_page['CALENDAR'].="<style type=\"text/css\">\n";
$to_page['CALENDAR'].="
.calendar {border: 2px solid #000000;}
TD.calendar_header {font-family: verdana, arial; font-size: 12px; font-weight: bold; color: #003333; background-color: #FFFF00; text-align: center; height: 25; width: 40}
TH.calendar_title {font-family: verdana, arial; font-size: 12px; font-weight: bold; color: #003333; background-color: #00CCFF; text-align: center; height: 25; width: 40}
TD.calendar_title {font-family: verdana; font-size: 12px; font-weight: bold; color: #000033; background-color: #FFFF00; text-align: center; height: 25; width: 40}
TD.calendar_title_we {font-family: verdana; font-size: 12px; font-weight: bold; color: #FF0000; background-color: #FFFF00; text-align: center; height: 25; width: 40}
TD.calendar_day {font-family: verdana; font-size: 12px; font-weight: bold; color: #000033; background-color: #00FFFF; text-align: center; height: 25; width: 40}
TD.calendar_day_we {font-family: verdana; font-size: 12px; font-weight: bold; color: #FF0000; background-color: #00FFFF; text-align: center; height: 25; width: 40}
";
$to_page['CALENDAR'].="</style>\n";

$calendar = new calendar();
$calendar->output_calendar();
$calendar_content = $calendar->get_content();

while ($calendar_element = each($calendar_content)) {
	$to_page['CALENDAR'].=$calendar_element['value'];
}

$to_page['CALENDAR'].="<!-- End of Calendar class TPL -->\n";
?>