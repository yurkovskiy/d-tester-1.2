<?php

/**
 * @package d-tester
 * @subpackage tester subsystem
 * @version 1.2 RC1
 * @name configuraton parameters
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2009 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * All rights reserved
 */

$PARAM['LANGUAGE'] = "uk"; // Специфікатор мови інтерфейсу програми (uk, ru, en)

$PARAM['CODE_BASE'] = "_win1251";

$PARAM['ROOT_LANG'] = "./lang/"; // Базова директорія файлів мови інтерфейсу

// No change user information below

$PARAM['LANG_DB_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE']."_db.inc";

$PARAM['LANG_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE'].".inc";

$PARAM['FJS_FILE'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE']."_forms.js";

//--------------------

$PARAM['TEST_BASE'] = "./tests/test_";

/**
 * @deprecated from d-tester build 26/08/2009
 */
//$PARAM['SHOW_TEST_HEADER'] = 0; // 1 - відображати заголовок програми під час проходження тестування

$PARAM['ADMIN_PASS_CHECK'] = 0; // Адміністратор підтвердужє дозвіл на здачу тестів. 0 - ні, 1 - так

$PARAM['ADM_AN_NAME'] = "Anonymos"; // Анонімне ім'я адміністратора. Інформація про нього має міститись в базі даних

$PARAM['DIV_SUBJ'] = 25; // 1 - кількість предметів, що відображаються на сторінці

$PARAM['DIV_QNAV'] = 25; // Кількість посилань у рядку Q_Navigator 

$PARAM['NEWS_PER_PAGE'] = 5;


// Database parameters
$PARAM['DB_HOST'] = "localhost"; // Сервер бази даних

$PARAM['DB_USER'] = "tester"; // Ім'я користувача

$PARAM['DB_PASSWORD'] = "1a_3araza"; // Пароль

$PARAM['DB_DBNAME'] = "d_test"; // Назва бази даних

// REMOTE IP Address Control
// Підключення через проксі-сервер. При істинному значенні відключається контроль ІР-адреси клієнта.
// Дане рішення ніяк не впливає на результат.
$PARAM['VIA_PROXY'] = false; 

// Index page

$INDEX['BASE_TPL_DIR'] = "tpls/";

$PARAM['MIN_RATING_VALUE'] = 35;

?>