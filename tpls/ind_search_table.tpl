<?php
$to_page[].="<!-- Searcher TPL -->\n";
$to_page[].="<script language=\"JavaScript\">
function checkedSearch(form)
{
	for(var i=0;i<form.elements.length;i++)
	{
		if(form.elements[i].value==\"\")
		{
			alert(\"Не заповнені всі поля\");
			return false;
		}
	}
	var keyword=form.word.value;
	if(keyword.length<4)
	{
		alert(\"Слово для пошуку повинно містити мінімум ЧОТИРИ символи\");
		return false;
	}
	return true;
}
</script>\n";

$to_page[].="<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" class=\"tbl_index_stat\">
<form onsubmit=\"return checkedSearch(this)\" action=\"search.php\" method=\"get\" name=\"search\">
<tr><td align=\"left\" class=\"darkrow3\" colspan=\"2\">&nbsp;<b>ПОШУК</b></td></tr>
<tr><td align=\"center\" class=\"row2\" width=\"35%\"><b>Слово</b></td>
<td align=\"left\" class=\"row1\" width=\"55%\"><input name=\"word\" type=\"text\" size=\"16\" maxlength=\"100\"></td></tr>
<tr><td align=\"center\" class=\"row2\"><b>Шукати:</b></td><td align=\"left\" class=\"row1\">
<select name=\"location\">;
<option value=\"2\">Тести</option>
<option value=\"3\">Користувачі</option>
</select></td></tr>
<tr><td class=\"darkrow2\" width=\"100%\" align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"Search\" class=\"button\" value=\"Пошук\"></td></tr> 		
</form></table>\n";
$to_page[].="<!-- End of Searcher TPL -->\n";

?>