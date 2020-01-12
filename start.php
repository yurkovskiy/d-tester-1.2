<?PHP

/**
* @package d-tester 
* @version 1.2 RC1
* @subpackage tester subsystem
* @name subsystem core [kernel]
* @author Yuriy V. Bezgachnyuk
* @copyright 2005-2009 Yuriy V. Bezgachnyuk
*  
* Start date: 23/03/2005
* Last update: 26/08/2009 21:32 GMT +02:00
*
* All Rights Reserved
*/

require_once("inc/settings.inc");
require_once("inc/db_mes.inc");
require_once("inc/header.inc");
require_once("inc/mysql.inc");
require_once("inc/timer.inc");
require_once("inc/results.inc");
require_once("inc/q_types.inc");
require_once($PARAM['LANG_SET']);

session_start(); // Запускаємо сеанс (або перевіряємо його наявність)

$cur_level = $_POST['cur_level']; // current question level
$action = $_GET['action'];
$ID = $_GET['ID']; // current question id (in session)
$q_type = $_POST['q_type']; // current question type
$time = $_GET['time'];
$type = $_GET['type'];

$DB = new db_driver();
$DB->obj['sql_database'] = $PARAM['DB_DBNAME'];
$DB->obj['sql_host'] = $PARAM['DB_HOST'];
$DB->obj['sql_user'] = $PARAM['DB_USER'];
$DB->obj['sql_pass'] = $PARAM['DB_PASSWORD'];
$DB->connect(); // Connect to database

require_once("check_ans.php");

// Фаза 1: Вхід в систему: Перевірка персональних даних, формування блоку тестових завдань
global $quest_l, $count_ql, $quests, $q_level_tasks, $q_level_rate, $q_levels_used;

require_once("start_phase.php");

// Генерація web-сторінки

$_SESSION['time_value'] = (strtotime($_SESSION['end_time']) - time()); // Визначаємо час, що залишився на тестування

page_begin($lang['tester_title']);
srand((float)microtime() * 1000000);

$question = $_SESSION['quests'][0];
if($ID == $_SESSION['how_q']) unset($ID);
if(($ID != 0) && ($ID != $_SESSION['how_q'])) {
	$question = $_SESSION['quests'][$ID]; 
	$qs = $ID;
}
$qs++;// questions counter
//echo "{$question}\n";
if(sizeof($_SESSION['ans_amount']) != $_SESSION['how_q']) {
    $aa = $DB->query("SELECT COUNT(ans_id) FROM answers WHERE aq_id='".$_SESSION['quests'][$qs-1]."'");
    $DB->fetch_row($aa);
    $_SESSION['ans_amount'][$qs - 1] = $DB->record_row[0];
    $DB->free_result();
}
$ans_num = range(1, $_SESSION['ans_amount'][$qs - 1]);
$v_name = "ques_".$question;
?>

<!-- Form Structure -->
<form action="start.php?action=answer&ID=<?php echo $qs?>" name="test_form" method="POST" enctype="multipart/form-data">
<input type="hidden" name="PHPSESSID" value="<?php echo $_SESSION['sess_id']?>">
<input type="hidden" name="q_type" value="<?php echo $_SESSION[$v_name][4]?>">
<table align="center" width="95%" border="1" cellpadding="3" cellspacing="1" class="header_info">
<tr>
<td align="right" class="darkrow2" colspan="2">
<div style="float:left;"><b><i><?php echo "{$_SESSION['user_name']}"?></i></b></div>
<div style="float:right;"><b><?php echo $lang['time_ream']?></b>&nbsp;
<input type="edit" name="time" size="8" value="1000" class="timer">&nbsp;
<?php 
printf($lang['task'],$qs,$_SESSION['how_q']);printf($lang['task_level'],$_SESSION[$v_name][3]);
?>
&nbsp;</div></td>
</tr>
<input type="hidden" name="cur_level" value="<?php echo $_SESSION[$v_name][3]?>">

<!-- Timer function added 10.07.2005 [updated: 17.04.2006] -->
<script language="JavaScript">
var timevalue=<?php echo $_SESSION['time_value']?> * 1000; 
function pre_zero(t) {
    return ((t * 1 < 10)?'0':'') + t;
}

function check_time(timeval) {
    if(timeval <= 0) window.location="start.php?time=end";
    timeval -= 1000;
    var d = new Date;
    d.setTime(timeval);
    var h = "", m = "", s = "";
    m = d.getMinutes();
    s = d.getSeconds();
    h = (((timeval / 1000) - m * 60 - s) / 3600);
    h = pre_zero(h);
    m = pre_zero(m);
    s = pre_zero(s);
    document.forms['test_form'].elements['time'].value=h + ":" + m + ":" + s;
    window.setTimeout('check_time(' + timeval + ')', 1000);
}
check_time(timevalue);
</script>

<?php
// Question Navigator

echo "<tr>\n<td align=\"center\" width=\"10%\" class=\"copyright\">";
echo "<a href=\"{$_SERVER['PHP_SELF']}?action=man_end\" title=\"{$lang['man_end']}\"><img src=\"styles/btn_stop_mgreen.gif\" onmouseover=\"this.src='styles/btn_stop_red.gif'\" onmouseout=\"this.src='styles/btn_stop_mgreen.gif'\" alt=\"{$lang['man_end']}\" border=\"0\"></a></td>\n";

echo "<!-- Question Navigator -->\n";
$count_qn = 1;
for($i = 1;$i <= sizeof($_SESSION['user_answers']);$i++) {

	if($i == ($count_qn * ($PARAM['DIV_QNAV']) + 1)) {$ans_q_reg.="<br>\n";$count_qn++;}

    $_ID = $i - 1;
    $href_title = $lang['nav_q'].$i;

    if($_SESSION['user_answers'][$i] != -1) {
        $href_color = "blue";
    }
    else {
        $href_color = "red";
    }

    if($_ID != ($qs - 1)) $ans_q_reg.="&nbsp;&lt;<a href=\"{$_SERVER['PHP_SELF']}?type=nav&ID={$_ID}\" title=\"{$href_title}\"><font color=\"{$href_color}\">{$i}</font></a>&gt;&nbsp;\n";
    else {
        $ans_q_reg.="&nbsp;&lt;<font color=\"{$href_color}\">{$i}</font>&gt;&nbsp;\n";
    }
}
echo "<td align=\"center\" id=\"nav_cell\">{$ans_q_reg}</td>\n</tr>\n</table>\n";

echo "<table align=\"center\" width=\"95%\" border=\"1\" cellpadding=\"3\" cellspacing=\"1\" class=\"tbl_task\">\n";
if(($_SESSION[$v_name][4] == SIMPLE_CHOICE_IMG)
    ||($_SESSION[$v_name][4] == MULTI_CHOICE_IMG)
    ||($_SESSION[$v_name][4] == INPUT_FIELD_IMG)
    ||($_SESSION[$v_name][4] == TASK_FORM_IMG)
    ||($_SESSION[$v_name][4] == NUMERICAL_IMG))
{
    // Завдання містить мультимедіа-ресурс [type/image]
    $source = $PARAM['TEST_BASE'].$_SESSION['test_id']."/".$_SESSION[$v_name][5]; // Шлях до файлу мультимедіа-ресурсу
    echo "<!-- Question image -->\n";
    echo "<tr>\n<td width=\"100%\" colspan=\"2\" valign=\"middle\" align=\"center\"><img src=\"{$source}\"></td>\n</tr>\n";
}
// Вивід тексту завдання
echo "<!-- Question body -->\n";
echo "<tr>\n<td width=\"100%\" valign=\"middle\" align=\"center\" class=\"darkrow3\" colspan=\"2\"><font color=\"Red\"><code><b>{$_SESSION[$v_name][2]}</b></code></font></td>\n</tr>\n\n";

//changed 10.01.2006
// Вивід варіантів відповіді (Якщо необхідно)
if(($_SESSION[$v_name][4] >= SIMPLE_CHOICE)
    &&($_SESSION[$v_name][4] <= MULTI_CHOICE_IMG)) // Radio Button and Check Box questions
{
    shuffle($ans_num);// Змінюємо послідовність варіантів відповідей випадковим чином
    $_SESSION['ans_num'] = $ans_num;
    echo "<tr>\n";
    $ans_name = "ans_".$question."_1";

    for($i = 0;$i < sizeof($ans_num);$i++) {
        if(($i != 0) && (fmod($i, 2) == 0)) {
            echo "</tr>\n<tr>\n";
        }
        $ans_name = "ans_".$question."_".$ans_num[$i];

        $count_ans = $i + 1;
        
        $td_id = "td[".$count_ans."]";
        $fe_id = "fe[".$count_ans."]";

        // Варіанти відповідей містять мультимедіа-ресурси
        if($_SESSION[$ans_name][2] != "0") {
            
        	$source = $PARAM['TEST_BASE'].$_SESSION['test_id']."/".$_SESSION[$ans_name][3];
            
        	/**
        	 * Security Image Block
        	 * added 07.09.2008 by Yuriy Bezgachnyuk
        	 */
        	
        	$u_str = strval($i * microtime());
        	$u_str .= $source;
            $md5_str = md5($u_str);
            
            $_SESSION[$md5_str] = $source; // assigment path to file into session variable
            
            echo "<td width=\"50%\" valign=\"middle\" align=\"center\" class=\"ans_row_norm\" id=\"{$td_id}\"><img src=\"img.php?s={$md5_str}\"></br>";
                        
            /**
             * End of Security Image Block
             */
        }
        else echo "<td width=\"50%\" valign=\"middle\" align=\"left\" class=\"ans_row_norm\" id=\"{$td_id}\">";
        
        // added by Yurkovskiy 23.11.2010
        //$answer_body = htmlentities($_SESSION[$ans_name][4], null, cp1251); // mus be fixed
        $answer_body = $_SESSION[$ans_name][4];
         
        if($_SESSION[$v_name][4] <= SIMPLE_CHOICE_IMG) // Simple Choice => Radio Button
        echo "<input type=\"radio\" id=\"{$fe_id}\" name=\"ans\" class=\"radiobutton\" value=\"{$count_ans}\" onClick=\"is_checked(this)\">
        <label for=\"{$fe_id}\" style=\"display:inline;\">{$answer_body}</label></td>\n";

        if(($_SESSION[$v_name][4] >= MULTI_CHOICE) && ($_SESSION[$v_name][4] <= MULTI_CHOICE_IMG)) // Multi-Choice => CBox
        echo "<input type=\"checkbox\" id=\"{$fe_id}\" name=\"ans[{$i}]\" class=\"radiobutton\" value=\"{$count_ans}\" onClick=\"is_checked(this)\">
        <label for=\"{$fe_id}\" style=\"display:inline;\">{$answer_body}</label></td>\n";
    }

    echo "</tr>\n";
}

?>
<!-- is_checked - changer style function -->
<script type="text/javascript">
function is_checked(cb) {
	var td_id = 'td[';

	td_id = td_id + cb.value + ']';

	// Change bgcolor to 'ans_row_chec' class if cbox is checked

	// Radio Button [Simple Choice]
	if (cb.type == "radio") {
		var tdr_id;
	
		for (var i = 1;i <= cb.form.elements["ans"].length;i++) {
			tdr_id = 'td[' + i + ']';
	
			if (i == cb.value) {
				document.getElementById(tdr_id).className = "ans_row_chec"; // highlight checked answer
			}
			else {
				document.getElementById(tdr_id).className = "ans_row_norm"; // turn off light
			}
		}
	}

	// CheckBox [Multi Choice]
	else {

		if (cb.checked == true) {
			document.getElementById(td_id).className = "ans_row_chec";
		}

		// Change bgcolor to 'ans_row_norm' class if cbox is unchecked
		else {
			document.getElementById(td_id).className = "ans_row_norm";

		}
	}
}
</script>
<!-- /is_checked -->

<?php
// added 07.07.2005 07:30 (GMT+02:00)
// Використовується спеціальний тип завдань [поле для вводу відповіді][форма вводу]?
if(($_SESSION[$v_name][4] >= INPUT_FIELD)
    &&($_SESSION[$v_name][4] <= NUMERICAL_IMG)) {
    $_SESSION['ans_num'] = $ans_num;
    $q_type = $_SESSION[$v_name][4];
    echo "<tr>\n<td align=\"center\" class=\"row1\" colspan=\"2\">\n";
    global $ans_t, $ans_true;
    $ans_t = array();
    for($i = 0;$i < sizeof($ans_num);$i++)
    {
        /*
        Визначаємо ідентифікатор правильного варіанту відповіді
        і записуємо його у згенерований файл попередньо зашифрувавши
        */
        // modifed 03.12.2005 22:00 (GMT +02:00)
        $ans_name = "ans_".$question."_".$ans_num[$i];
        if(($_SESSION[$ans_name][1] == "2")
            &&(($_SESSION[$v_name][4] != NUMERICAL)
            ||($_SESSION[$v_name][4] != NUMERICAL_IMG))) {
            $ans_t[$i] = md5($_SESSION[$ans_name][4]); // Шифруємо правильний варіант згідно алгоритму MD5
        }
        if(($_SESSION[$ans_name][1] == "2")
            &&(($_SESSION[$v_name][4] == NUMERICAL)
            ||($_SESSION[$v_name][4] == NUMERICAL_IMG)))  // Numerical
        {
            $ans_t[$i] = $_SESSION[$ans_name][4];
        }
    }

    // added 03.12.2005 22:00 (GMT +02:00)
    for($i = 0;$i < sizeof($ans_num);$i++) {
        if(!$ans_t[$i]) continue;
        $ans_true = "a_true_".$i;

        if(($_SESSION[$v_name][4] == NUMERICAL)
            ||($_SESSION[$v_name][4] == NUMERICAL_IMG)) $_SESSION[$ans_true] = $ans_t[$i]; // Numerical
        else echo "<input type=\"hidden\" name=\"{$ans_true}\" value=\"{$ans_t[$i]}\">\n";
    }
    if(($_SESSION[$v_name][4] == TASK_FORM) ||($_SESSION[$v_name][4] == TASK_FORM_IMG)) {
        echo "<textarea rows=\"8\" cols=\"100\" name=\"memo_box_body\"></textarea>\n";
    }
    else {
        echo "<input type=\"text\" size=\"70\" maxlength=\"80\" name=\"answer\" class=\"forminput\">\n";
    }
    echo "</td>\n</tr>\n";
}
echo "\n<tr><td align=\"center\" class=\"darkrow2\" colspan=\"2\"><INPUT type=\"submit\" class=\"button\" name=\"OK\" value=\"{$lang['OK']}\"></td></tr>\n";
echo "</table>\n</form>\n<br>\n<div align=\"center\" class=\"copyright\">\n{$lang['a_warning']}\n</div>\n";
unset($ans_num);
page_end();
?>