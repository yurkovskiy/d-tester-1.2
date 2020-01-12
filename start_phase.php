<?php

if(!isset($_SESSION['user_name']))
{
    $us_name = addslashes(trim($_POST['us_name']));
    $us_pass = md5(trim($_POST['us_pass']));
    $admin_name = addslashes(trim($_POST['admin_name']));
    $admin_pass = md5(trim($_POST['admin_pass']));
    $group_id = $_POST['group_id'];
    $test_id = intval($_POST['test_id']);

    // 1. �������� ������������ ����� �����������
    if($PARAM['ADMIN_PASS_CHECK'] == 1) // ����� �� ���� �� �����������
    {
        $DB->query("SELECT users.user_name, users.user_pass, users.user_group, users.user_id, admins.admin_id, admins.admin_name, admins.password
					FROM users, admins 
					WHERE users.user_name='".$us_name."' AND users.user_pass='".$us_pass."' 
					AND users.user_group='".$group_id."' AND admins.admin_name='".$admin_name."' AND admins.password='".$admin_pass."'");
    }

    if($PARAM['ADMIN_PASS_CHECK'] == 0) // ���������� ���� ��� ��������� �������� ������������
    {
        $DB->query("SELECT users.user_name, users.user_pass, users.user_group, users.user_id,admins.admin_id
			   		FROM users,admins 
					WHERE users.user_name='".$us_name."' AND users.user_pass='".$us_pass."' AND users.user_group='".$group_id."' 
					AND admins.admin_name='".$PARAM['ADM_AN_NAME']."'");
    }

    $DB->fetch_row();
    if(!$DB->record_row) {
        session_destroy();
        Show_Message("DB_ERROR_ACCESS");
    }
    $us_id = $DB->record_row[3]; // User ID
    $ad_id = $DB->record_row[4]; // Admin ID

    // 2. �������� �������� �������������� ��� ������ ����������� ������
    $stmt = "SELECT * FROM active_sess WHERE user_id={$us_id}";
    $DB->query($stmt);    
    //$DB->query("SELECT * FROM active_sess WHERE user_id='".$us_id."' {$via_proxy} remote_ip='".$_SERVER['REMOTE_ADDR']."'");
    if($DB->get_num_rows() != 0) {
        session_destroy();
        Show_Message("DB_ERROR_ACCESS_SESSION");
    }

    // 3. �������� � ���� ����� �������� ���������� ��� ��������� �����
    $DB->query("SELECT * FROM tests WHERE test_id='".$test_id."'");
    $row_test_info = $DB->fetch_row();
    
    // added 19.05.2009 [modified 18.08.2009] by Yuriy Bezgachnyuk
    if ($row_test_info[6] == 1) { // exam test
    	$DB->query("SELECT id 
    				FROM rating_results 
    				WHERE user_id = {$us_id}
    				AND test_id = {$test_id}
    				AND rating >= {$PARAM['MIN_RATING_VALUE']}
    				AND status = 0");
    	if ($DB->get_num_rows() == 0) {
    		session_destroy();
    		$DB->free_result();
    		Show_Message("DB_ERROR_NO_RATING_VALUE");
    	}
    	$DB->free_result();
    }

    // 4. �������� �������� ���������� ����� ����������� ��������� �����
    $DB->query("SELECT * FROM session_results WHERE user_id='".$us_id."' AND test_id='".$test_id."'");
    if($DB->get_num_rows() >= $row_test_info[5]) {
        session_destroy();
        Show_Message("DB_ERROR_TEST");
    }

    // 4.1 �� �������� ��� ����������� ������� ������ �������� ���������� �� dt_enter_logs
    $status = 1;
    $DB->query("INSERT INTO dt_enter_logs (id, admin_id, user_id, remote_ip, status, test_id, e_date, e_time)
			 	VALUES(null,'".$ad_id."','".$us_id."','".$_SERVER['REMOTE_ADDR']."','".$status."','".$test_id."',CURDATE(),CURTIME())");

    // added 12.11.2005 17:30 (GMT+02:00)

    global $test_rate, $count;

    // 5. �������� ��������� ���������� ��� �����
    $DB->query("SELECT count(*) FROM test_details WHERE test_id='".$test_id."'");
    $DB->fetch_row();

    if($DB->record_row[0] == 0) {
        Show_Message("DB_ERROR_TEST_DETAIL_NOT_FOUND");
    }

    else {
        // 5.1 ��������� ������� �������, �� ��������� ������
        $DB->query("SELECT SUM(level_tasks) FROM test_details WHERE test_id='".$test_id."'");
        $DB->fetch_row();
        if($DB->record_row[0] != $row_test_info[2]) {
            // ������������ ���������� � �������� tests � test_details
            session_destroy();
            Show_Message("DB_ERROR_QUEST_BUG");
        }

        else {
            $DB->query("SELECT level_id, level_tasks, level_rate FROM test_details WHERE test_id='".$test_id."' ORDER BY id");
            $count = 0;
            $test_rate = 0;
            while($DB->fetch_row()) {
                $q_level_rate[$DB->record_row[0]] = $DB->record_row[2]; // ʳ������ ���� �� ���� �������� ������ ����
                $q_level_tasks[$DB->record_row[0]] = $DB->record_row[1]; // ʳ������ ������� ������ ����, �� ������ �����
                $test_rate = $test_rate + ($q_level_rate[$DB->record_row[0]] * $q_level_tasks[$DB->record_row[0]]); // ���������� ������� ���� �� ����� ����
                $q_levels_used[$count] = $DB->record_row[0];
                $count++;
            }

            $DB->free_result();
            // 5.1.1 ��������� �������� ��������� ���������� � ������� questions
            for($i = 0;$i <= $count;$i++) {
                $DB->query("SELECT COUNT(question_id) FROM questions WHERE q_test_id='".$test_id."' AND q_level='".$q_levels_used[$i]."'");
                $DB->fetch_row();
                if($DB->record_row[0] < $q_level_tasks[$q_levels_used[$i]]) {
                    // � ��� ����� �� �������� �������� ��������� ������� ������� ��� ������ ����
                    session_destroy();
                    Show_Message("DB_ERROR_QUEST_BUG");
                }
            }
            $count_ql = 0;
            for($i = 0;$i <= $count;$i++) {
                // 5.2.3 ��������� �������������� ������� ��� ����
                $limit_level_tasks = intval($q_level_tasks[$q_levels_used[$i]]);
                $DB->query("SELECT question_id FROM questions WHERE q_test_id='".$test_id."' AND q_level='".$q_levels_used[$i]."'
							ORDER BY RAND() LIMIT $limit_level_tasks");
                while($DB->fetch_row()) {
                    $quest_l[$count_ql] = $DB->record_row[0];
                    $count_ql++;
                }
                $DB->free_result();
            }
        }
        $_SESSION['quests'] = $quest_l;
    }

    // 6. ������� ������ ���� ������
    $DB->query("SELECT CURTIME()"); // ��������� �������� ��� �� ������
    $DB->fetch_row();
    $_SESSION['start_time'] = $DB->record_row[0];
    $_SESSION['REMOTE_IP'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['rate'] = 0; // ������ �� ����������� �����
    $_SESSION['test_rate'] = $test_rate; // ����������� ������� ���� �� ����
    $_SESSION['q_level_rate'] = $q_level_rate;
    $_SESSION['total_rate'] = array();
    $_SESSION['user_answers'] = array();
    $_SESSION['user_name'] = $us_name; // ��'� �����������
    $_SESSION['user_id'] = $us_id; // ID ����������� � ��� �����
    $_SESSION['group'] = $group_id; // ID ����� �����������
    $_SESSION['test_id'] = $test_id; // ID ��������� �����
    $_SESSION['test_type'] = $row_test_info[6]; // ��� �����
    $_SESSION['test_name'] = $row_test_info[0]; // ����� �����
    $_SESSION['how_q'] = $row_test_info[2]; // ʳ������ �������, �� ��������� ������ �����������
    $_SESSION['end_time'] = TimeToStr(add_time($_SESSION['start_time'], $row_test_info[3])); // ��� ��������� �����
    $_SESSION['sess_id'] = session_id();

    for($ri = 1;$ri <= $_SESSION['how_q'];$ri++) {
        /*
        7. �������� � ���� ������ ���������� ��������� 0 ���� ��� ��� �������,
        � ����� ������� ����� �������� �����������. �� ������� $_SESSION['user_answers']=array(-1,-1,...,-1)
        */
        $_SESSION['total_rate'][$ri] = 0;
        $_SESSION['user_answers'][$ri] =- 1;
    }
    // 8. �������� ���������� ��� �������� ����� �� ���� �����
    $DB->query("INSERT INTO active_sess (user_id, test_id, start_time, start_date, remote_ip, session_id)
				VALUES('".$_SESSION['user_id']."', '".$_SESSION['test_id']."', CURTIME(), CURDATE(), '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['sess_id']."')");

    $q_sess = $_SESSION['quests'];

    // 9. ���������� ������ ��� ������ ���������� ������� � ������ �� (������) � ���� ������
    $query_test_ques = "SELECT * FROM questions WHERE q_test_id='".$test_id."' AND question_id='".$q_sess[0]." '";
    for($i = 1;$i < $_SESSION['how_q'];$i++) {
        $query_test_ques.="OR question_id='".$q_sess[$i]." '";
    }
    $query_test_ques.="ORDER BY question_id ASC";
    $res_test_ques = $DB->query($query_test_ques);

    $ka = 1;
    $q_count = 0;
    while($row = $DB->fetch_row($res_test_ques)) {
        $row[2] = stripslashes(stripslashes($row[2]));
        $v_name = "ques_".$row[0];
        $_SESSION[$v_name] = $row;
        $DB->query("SELECT * FROM answers WHERE aq_id='".$row[0]."' ORDER BY ans_id");
        while ($a_row = $DB->fetch_row()) {
            $a_row[4] = stripslashes($a_row[4]);
            $a_name = "ans_".$row[0]."_".$ka;
            $_SESSION[$a_name] = $a_row;
            $ka++;
        }
        $ka = 1;
        $q_count++;
    }
} // ʳ���� ���� ������ ���� �� ���������� ����� ������

?>