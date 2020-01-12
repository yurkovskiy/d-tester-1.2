<?php

// K-Zone Block File

$days = array("<font color=\"red\">Неділя</font>","Понеділок","Вівторок","Середа","Четвер","П&acuteятниця","Субота");
$month = array("Січня","Лютого","Березня","Квітня","Травня","Червня","Липня","Серпня","Вересня","Жовтня","Листопада","Грудня");

$to_page[].="<!-- Today inf TPL -->\n";
$to_page[].="<style type=\"text/css\">\n";
$to_page[].=".cld
{
border: 2px solid #000000;
font-size: 12px;
font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
}";
$to_page[].="</style>\n";

$today = getdate();
$today['yday']++;

$to_page[].="<table width=\"100%\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"cld\">\n";
$to_page[].="<tr><td align=\"left\" width=\"100%\" class=\"darkrow3\" nowrap>&nbsp;<b>Сьогодні:</b></td></tr>\n";
$to_page[].="<tr><td align=\"center\" width=\"100%\" class=\"row1\" nowrap>&nbsp;<b>{$today['mday']}&nbsp;{$month[($today['mon']-1)]}&nbsp;{$today['year']}</b></td></tr>\n";
$to_page[].="<tr><td align=\"center\" width=\"100%\" class=\"row1\" nowrap>&nbsp;<b>{$days[$today['wday']]}&nbsp;</b></td></tr>\n";
$to_page[].="<tr><td align=\"center\" width=\"100%\" class=\"row1\" nowrap>&nbsp;<b>{$today['yday']}&nbsp;день року</b></td></tr>\n";
$to_page[].="<!-- Clock SWF TPL -->\n";
$to_page[].="<tr><td align=\"center\" width=\"100%\" class=\"row1\">\n";
$to_page[].="<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' width='150' height='150' id='clock' align='middle'>\n";
$to_page[].="<param name='movie' value='images/clock.swf' />\n";
$to_page[].="<param name='quality' value='high' />\n";
$to_page[].="<param name='wmode' value='transparent' />\n";
$to_page[].="<embed src='images/clock.swf' quality='high' wmode='transparent' width='150' height='150' name='clock' align='middle' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />\n";
$to_page[].="</object>\n";
$to_page[].="</td></tr>\n</table>\n<!-- End of Clock SWF TPL -->\n";
//$to_page[].="</table>\n<!-- End of Today inf TPL -->\n";
?>