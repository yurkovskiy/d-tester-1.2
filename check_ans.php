<?php
// Перевірка вибраного (введеного) варіанту, якщо активний сеанс
if(isset($_SESSION['user_name']))
{
    if($type != "nav") // Якщо перехід було зроблено з допомогою навігатора непотрібно перевіряти відповідь
    {
        if($action == "answer")
        {
            switch($q_type)
            {
                // 0,1 => Radio Button [Simple Choice]
                case SIMPLE_CHOICE:
                case SIMPLE_CHOICE_IMG:
                {
                    $ans = intval($_POST['ans']); // current question answer array ID
                    $ans_var = "ans_".$_SESSION['quests'][$ID - 1]."_".$_SESSION['ans_num'][$ans - 1];
                    $user_answer = $_SESSION[$ans_var][5];
                    unset($_SESSION['ans_num']);
                    break;
                }

                // 2,3 => Check Box [Multi Choice]
                case MULTI_CHOICE:
                case MULTI_CHOICE_IMG:
                {
                    $ans = $_POST['ans'];
                    for($i = 0;$i < sizeof($_SESSION['ans_num']);$i++)
                    {
                        if(isset($ans[$i]))
                        {
                            $ans_var="ans_".$_SESSION['quests'][$ID-1]."_".$_SESSION['ans_num'][$ans[$i] - 1];
                            $user_answer.="<".$_SESSION[$ans_var][5]; // Визначаємо вибрані користувачем відповіді
                        }
                    }
                    unset($_SESSION['ans_num']);
                    break;
                }

                // 4,5 => Input Field [Fill in blank]
                case INPUT_FIELD:
                case INPUT_FIELD_IMG:
                {
                    $answer = trim($_POST['answer']);
                    $answer_md5 = md5($answer);

                    for($i = 0;$i < sizeof($_SESSION['ans_num']);$i++)
                    {
                        $ans_var = "a_true_".$i;
                        if($answer_md5 == $_POST[$ans_var])
                        {
                            // Правильна відповідь
                            $user_answer = get_true_answer($answer, $_SESSION['quests'][$ID - 1]);
                            break;
                        }
                    }
                    unset($_SESSION['ans_num']);
                    break;
                }

                case TASK_FORM:
                case TASK_FORM_IMG:
                {
                    // not realized
                    break;
                }

                // 8,9 => Numerical
                case NUMERICAL:
                case NUMERICAL_IMG:
                {
                    unset($_SESSION['ans_num']);
                    $answer = $_POST['answer'];
                    $a_true_0 = $_SESSION['a_true_0'];
                    $a_true_1 = $_SESSION['a_true_1'];
                    unset($_SESSION['a_true_0']);
                    unset($_SESSION['a_true_1']);
                    // Перетворюємо текстові змінні в числові
                    $answer_f = doubleval($answer);
                    $a_true_0_f = doubleval($a_true_0);
                    $a_true_1_f = doubleval($a_true_1);
                    if (($answer_f >= $a_true_0_f) && ($answer_f <= $a_true_1_f))
                    {
                        $user_answer = get_true_answer($a_true_0_f, $_SESSION['quests'][$ID-1]);
                    }
                    break;
                }
            }

            // Реєстрація відповіді користувача
            $_SESSION['user_answers'][$ID] = $user_answer;
        } // Кінець секції перевірки відповідей
    }

    if (($action == "man_end") || ($time == "end")) // Тест закінчився: Примусово [$action=man_end], автоматично [$time=end]
    {
        $_SESSION['true_answers'] = array();
        $_SESSION['total_rate'] = array();
        $true_ans = 0; // Кількість правильних відповідей
        // Show Test Results

        $user_answers = $_SESSION['user_answers'];

        for($i = 1;$i <= sizeof($user_answers);$i++)
        {
            $DB->query("SELECT q_media FROM questions WHERE (q_media = 2 OR q_media = 3) AND question_id='".$_SESSION['quests'][$i - 1]."'");
            if($DB->get_num_rows() != 0) // Якщо завдання типу Мультивибір
            {
                $DB->query("SELECT count(answers.ans_id) FROM answers WHERE ans_true=2 AND aq_id='".$_SESSION['quests'][$i - 1]."'");
                $DB->fetch_row();
                $how_answers = $DB->record_row[0];
                $DB->free_result();
                $res = explode("<",$_SESSION['user_answers'][$i]);
                if((sizeof($res) - 1) != $how_answers) // Кількість відповідей менша за необхідну
                {
                    $_SESSION['true_answers'][$i] = 0;
                }

                else
                {
                    $query = "SELECT ans_true FROM answers WHERE ans_id='".$res[1]."' ";
                    for($k = 2;$k < sizeof($res);$k++) $query.="OR ans_id='".$res[$k]."' ";
                    $DB->query($query);
                    $c = 1;
                    $true_res = 0;
                    while($DB->fetch_row())
                    {
                        if($DB->record_row[0] != 2) break;

                        else
                        {
                            if($c == $DB->get_num_rows()) {
                            	$true_res = 1; 
                            	break;
                            }
                        }
                        $c++;
                    }
                    unset($c);

                    if($true_res == 1)
                    {
                        $true_ans++;
                        $_SESSION['true_answers'][$i] = 1;
                    }
                    else $_SESSION['true_answers'][$i] = 0;
                }
                unset($true_res);
            }

            else
            {
                $query="SELECT ans_true FROM answers WHERE ans_id='".$user_answers[$i]."' AND ans_true=2";
                $DB->query($query);
                if($DB->get_num_rows()!=0)
                {
                    $true_ans++;
                    $_SESSION['true_answers'][$i] = 1;
                }
                else {
                    $_SESSION['true_answers'][$i] = 0;
                }
            }

            // Generate mark for question
            if($_SESSION['true_answers'][$i] != 0)
            {
                $query = "SELECT q_level FROM questions WHERE question_id='".$_SESSION['quests'][$i - 1]."'";
                $DB->query($query);
                $DB->fetch_row();
                $level_id = $DB->record_row[0];
                $DB->free_result();

                // Обчислюємо оцінку за правильну відповідь

                $_SESSION['total_rate'][$i] = $_SESSION['q_level_rate'][$level_id];
            }
            else {
                $_SESSION['total_rate'][$i] = 0; // Оцінка за неправильну відповідь
            }

            $_SESSION['rate'] += $_SESSION['total_rate'][$i]; // Вираховуємо остаточний результат
        }

        $_SESSION['rate'] = round($_SESSION['rate']);

        page_begin($lang['tester_title']);
        while($q_s_ = each($_SESSION['quests']))
        {
            $q_s.="||".$q_s_['value']; // Фіксуємо ID завдань, що були задані
        }

        while($ans_t_ = each($_SESSION['true_answers']))
        {
            $ans_t.="||".$ans_t_['value']; // Фіксуємо на які питання була дана правильна відповідь: 1-так 0-ні
        }

        while($user_ans = each($_SESSION['user_answers']))
        {
            $user_an.="||".$user_ans['value']; // Фіксуємо ID відповідей
        }

        if((!isset($_SESSION['user_id'])) || ($_SESSION['user_id'] == 0)) exit;

        
        // added 27/04/2009 by Yuriy Bezgachnyuk
        // Full rating results
        if ($_SESSION['test_type'] == 1) {
        	
        	$DB->query("SELECT rating FROM rating_results 
        				WHERE user_id = '".$_SESSION['user_id']."' 
        				AND test_id = '".$_SESSION['test_id']."'");
        	
        	$DB->fetch_row();
        	$user_rating = $DB->record_row[0];
        	$_SESSION['user_rating'] = $user_rating;
          
			$DB->free_result();
        }
        
        $res_quality = round(($_SESSION['rate'] / $_SESSION['test_rate']), 2); 
        $res_percent = 100 * $res_quality;
        
        // end of added
        
        // redirected from res_tbl.tpl
        $full_rating = 0;
        if ($_SESSION['test_type'] == 1) {
        	
        	// added 11/01/2010
        	$mega_koeff = 1.1;

        	$full_rating = ($_SESSION['user_rating'] + ($mega_koeff * $res_percent)) / 2;
        	if (round(($full_rating - floor($full_rating))) < 0.5) {
        		$full_rating = round($full_rating);
        	}
        	else {
        		$full_rating = ceil($full_rating);
        	}
        	//echo "FR = {$full_rating}<br>";
        }

        else {
        	$full_rating = $res_percent;
        }
        
        //$full_rating = ceil($full_rating);
        //echo "FR = {$full_rating}";
               
        // Записуємо результат до бази даних
        $DB->query("INSERT INTO session_results (user_id, test_id, date_ses, start_time, time_ses, result, full_res, questions, true_answers, user_ans, sess_id)
					VALUES ('".$_SESSION['user_id']."','".$_SESSION['test_id']."',CURDATE(),'".$_SESSION['start_time']."',CURTIME(),'".$_SESSION['rate']."','".$full_rating."','".$q_s."','".$ans_t."','".$user_an."',null)");

        require_once("tpls/res_tbl.tpl"); // Завантажуємо шаблон із таблицею результатів

        $DB->query("DELETE FROM active_sess WHERE user_id='".$_SESSION['user_id']."'");
        @session_destroy();
        page_end();
        exit;
    }
}
?>