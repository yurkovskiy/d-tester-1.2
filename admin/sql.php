<?php

// sql.php - MySQL toolbox
// update: 18.06.2005 22:11 (GMT+02:00)

require_once("req.inc");

if($_SESSION['adm_priv'] != ROOT) {
	header("Location: index.php");
}

$buttons=array('delete'=>'DELETE','select'=>'SELECT','all'=>'*','from'=>'FROM','where'=>'WHERE',
			   'orderby'=>'ORDER BY','show'=>'SHOW','limit'=>'LIMIT', 'desc'=>'DESC',
			   'usr'=>'USR');

page_begin($lang['SQL_href']);

if (isset($_GET['action'])) $action = $_GET['action'];
else $action ="";
if (isset($_POST['query'])) $query = $_POST['query'];
else $query = "";

if(($action == "query")
&&(strstr($query,"drop") != null)
||(strstr($query,"alter") != null)) {
	Show_Message("DB_ERROR_QUERY");
	unset($query);
}

if(($action == "query")
&&(strstr($query,"delete") != null)
||(strstr($query,"DELETE") != null)) {
	$query = trim($query);
	$DB->query($query);
	unset($query);
	header("Location: main.php");
	exit;
}

if($action == "query") {
	$query = trim($query);
	$DB->query($query);
	$num_fields = $DB->get_fields_num();
	echo "<TABLE width=\"100%\" align=\"center\" class=\"tbl_view_frame\" border=\"1\" cellpadding=\"1\" cellspacing=\"2\">\n";
	echo "<TR><TD align=\"center\" class=\"maintitle\" colspan=\"{$num_fields}\">{$lang['query_results']}</TD></TR>\n<TR>\n";
	
	for($i = 0;$i < $num_fields;$i++)
	{
		$width = floor(100 / $num_fields);
		$field_name = $DB->get_field_name($i);
		echo "<TH class=\"titlemedium\" align=\"center\" width=\"{$width}%\"><B>{$field_name}</B></TH>\n";
	}
	echo "</TR>\n";
	while($row = $DB->fetch_row())
	{
		echo "<TR>\n";
		for($i = 0;$i < $DB->get_fields_num();$i++)
		{
			if($row[$i] == "") $row[$i] = "&nbsp;";
			echo "<TD align=\"left\" class=\"row1\">{$row[$i]}</TD>\n";
		}
	echo "</TR>\n";
	}
	echo "<TR><TD align=\"center\" class=\"darkrow2\" colspan=\"{$num_fields}\">&nbsp;</TD></TR>\n</TABLE>\n";
}
echo "<script src=\"{$PARAM['FJS_FILE']}\"></script>\n";
echo "<FORM onsubmit=\"return checkedForm(this)\" action=\"sql.php?action=query\" method=\"POST\" name=\"q_form\" id=\"q_form\">\n";
?>
<SCRIPT type="text/javascript">
function show_keyword(value){
    if (value == 'USR') value = 'UPDATE session_results SET result = , full_res = WHERE sess_id = ';
    document.forms[0].query.value+=value;
    document.forms[0].query.value+=' ';
}
</SCRIPT>
<?php
echo "<TABLE width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"4\" cellpadding=\"3\" class=\"tbl_view_frame\">";
echo "<TR><TD colspan=\"2\" align=\"center\" class=\"maintitle\">{$lang['sql_man']}</TD></TR>\n";
echo "<TR>\n<TD class=\"row1\" width=\"40%\">{$lang['manual_q']}<BR>{$lang['sql_tip']}</TD>\n";
echo "<TD class=\"row1\"><TEXTAREA rows=\"4\" cols=\"75\" name=\"query\"></TEXTAREA></TD>\n</TR>\n";
echo "<TR>\n<TD class=\"row1\">{$lang['table']}\n<SELECT name=\"tables\">\n";
$DB->query("SHOW TABLES FROM ".$PARAM['DB_DBNAME']."");
while($t_row=$DB->fetch_row())
{
	echo "<OPTION value=\"{$t_row[0]}\">{$t_row[0]}</OPTION>\n";
}
$DB->free_result();
echo "</SELECT>\n";
echo "<INPUT type=\"button\" class=\"button\" value=\"{$lang['add_to_query']}\" name=\"add_table_name\" onclick=\"show_keyword(tables.value)\"></TD>\n";
echo "<TD align=\"left\" class=\"row1\">\n";
while($element = each($buttons))
{
	echo "<INPUT type=\"button\" class=\"button\" value=\"{$element['value']}\" name=\"{$element['key']}\" onclick=\"show_keyword('{$element['value']}')\">\n";
}
echo "</TD>\n</TR>\n";
echo "<TR>\n<TD align=\"center\" colspan=\"2\" class=\"darkrow2\">\n";
echo "<INPUT type=\"submit\" class=\"button\" name=\"run_q\" value=\"{$lang['q_button']}\">&nbsp;";
echo "<INPUT type=\"reset\" class=\"button\" name=\"reset_q\" value=\"{$lang['r_button']}\">";
echo "</TD>\n</TR>\n</TABLE>\n</FORM>\n";
page_end();
?>