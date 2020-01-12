<?php

// d-tester news unit

$to_page[].="<!-- d-tester news unit TPL -->\n";
$to_page[].="<style type=\"text/css\">\n";
$to_page[].=".dtnews
{
border: 2px solid #000000;
font-size: 12px;
font-family: Verdana, Arial, Helvetica, sans-serif;
}\n.dtnews a:link, .dtnews a:visited { text-decoration: none; color: #0000FF; }\n.dtnews a:hover { text-decoration: underline; color: #000000; }\n
.end_news {
 border-bottom: 1px solid #000;\n
 background-color: #F5F9FD;\n
}\n
";
$to_page[].="</style>\n";

//$to_page[].="<br>\n";

$to_page[].="<table width=\"100%\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\" class=\"dtnews\">\n";
$to_page[].="<tr><td align=\"left\" width=\"100%\" class=\"NavItem\" nowrap>&nbsp;<b>Latest News</b></td></tr>\n";

$DB->query("SELECT id, title, author, DATE_FORMAT(pub_date, '%d-%m-%Y'), info 
	    FROM news
	    WHERE visible = 1 
	    ORDER BY id DESC 
	    LIMIT {$PARAM['NEWS_PER_PAGE']}");

if ($DB->get_num_rows() != 0) {

	while ($row = $DB->fetch_row()) {
		$to_page[].= "<tr>\n";
		$to_page[].= "<td class=\"row1\">&nbsp;<i>{$row[3]}</i>
		<br>&nbsp;<b>{$row[1]}</b>
		<br>&nbsp;Published by:&nbsp;<b>{$row[2]}</b>
		<br>&nbsp;{$row[4]}</td>\n";
		$to_page[].= "</tr>\n";
		$to_page[].="<tr><td class=\"darkrow2\">&nbsp;</td></tr>\n";
	}
}

else {
	$to_page[].="<tr><td width=\"100%\" align=\"center\" class=\"row1\">NO NEWS ((((</td></tr>\n";
}

$to_page[].="</table>\n";

$DB->free_result();

?>