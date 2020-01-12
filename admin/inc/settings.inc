<?php

/**
 * @package d-tester
 * @subpackage administration subsystem
 * @version 1.2 RC1
 * @name configuraton parameters
 * @author Yuriy Bezgachnyuk
 * @copyright 2005-2008 Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * All rights reserved
 */

$PARAM['LANGUAGE'] = "uk"; // Специфікатор мови інтерфейсу програми (uk, ru, en)

$PARAM['CODE_BASE'] = "_win1251";

$PARAM['ROOT_LANG'] = "./lang/"; // Базова директорія файлів мови інтерфейсу

// No change user information below. Language calculation parameters

$PARAM['LANG_DB_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE']."_db.inc";

$PARAM['LANG_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE'].".inc";

$PARAM['FJS_FILE'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE']."_forms.js";

$PARAM['HELP_FILE'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/"."hlp_".$PARAM['LANGUAGE'].$PARAM['CODE_BASE'].".inc";

//--------------------

$PARAM['TEST_DIR_MASK'] = 0777; // Unix only

$PARAM['TEST_BASE_URL'] = "./../tests/test_";

$PARAM['TEST_BASE'] = "./../tests/test_"; 

$PARAM['SESS_DIR'] = "/var/lib/php5/sess_"; // Директорія розміщення файлів сеансу

$PARAM['MAX_LEVEL'] = 50; // Максимальний рівень групування завдань [Максимальне число 255]

$PARAM['MAX_LOGS_RECORDS_IN_PAGE'] = 30;

$PARAM['MAX_SUBJECTS_IN_PAGE'] = 10;

$PARAM['MAX_QUESTIONS_IN_PAGE'] = 20;

$PARAM['MAX_ANS'] = 10;

$PARAM['DEF_ANS'] = 3;

$PARAM['UPASS_LENGTH'] = 4; // User password length (for automatical password generator [4..8 Recommended])

$PARAM['UPASS_REP'] = true; // user password consist of only one symbol (ex: 1111)

// Export Parameters
$PARAM['EXFILE_PREFIX'] = "ex_";

// Diagram Section

$PARAM['dia_max_width'] = 400;

$PARAM['dia_max_heigth'] = 10;

// _Stat_Table section

$PARAM['st_fine'] = 2.19;

$PARAM['st_good'] = 1.09;

$PARAM['st_satisf'] = 0.4;

// DB Connection Parameters

$PARAM['DB_HOST'] = "localhost"; // Сервер бази даних

$PARAM['DB_USER'] = "tester"; // Ім'я користувача

$PARAM['DB_PASSWORD'] = "1a_3araza"; // Пароль

$PARAM['DB_DBNAME'] = "d_test"; // Назва бази даних

// PHP ini changed parameters
$PARAM['TIME_LIMIT'] = 600; // time limit for db_backup.php script

// MAX Upload File Size [Form]
$PARAM['MAX_UPLOAD_FILE_SIZE'] = 2 * 1024 * 1024; // 2 MB

// OS Windows
//define("UPLOAD_FILE_DIR", "d:\\usr\local\\projects\\d_tester\\admin\\temp\\");

// OS Unix/Linux etc
define("UPLOAD_FILE_DIR", "/var/www/d-tester.au.nung.if.ua/admin/temp/");
?>