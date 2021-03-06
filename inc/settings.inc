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

$PARAM['LANGUAGE'] = "uk"; // ������������ ���� ���������� �������� (uk, ru, en)

$PARAM['CODE_BASE'] = "_win1251";

$PARAM['ROOT_LANG'] = "./lang/"; // ������ ��������� ����� ���� ����������

// No change user information below

$PARAM['LANG_DB_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE']."_db.inc";

$PARAM['LANG_SET'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE'].$PARAM['CODE_BASE'].".inc";

$PARAM['FJS_FILE'] = $PARAM['ROOT_LANG'].$PARAM['LANGUAGE']."/".$PARAM['LANGUAGE']."_forms.js";

//--------------------

$PARAM['TEST_BASE'] = "./tests/test_";

/**
 * @deprecated from d-tester build 26/08/2009
 */
//$PARAM['SHOW_TEST_HEADER'] = 0; // 1 - ���������� ��������� �������� �� ��� ����������� ����������

$PARAM['ADMIN_PASS_CHECK'] = 0; // ������������ ��������� ����� �� ����� �����. 0 - ��, 1 - ���

$PARAM['ADM_AN_NAME'] = "Anonymos"; // �������� ��'� �������������. ���������� ��� ����� �� �������� � ��� �����

$PARAM['DIV_SUBJ'] = 25; // 1 - ������� ��������, �� ������������� �� �������

$PARAM['DIV_QNAV'] = 25; // ʳ������ �������� � ����� Q_Navigator 

$PARAM['NEWS_PER_PAGE'] = 5;


// Database parameters
$PARAM['DB_HOST'] = "localhost"; // ������ ���� �����

$PARAM['DB_USER'] = "tester"; // ��'� �����������

$PARAM['DB_PASSWORD'] = "1a_3araza"; // ������

$PARAM['DB_DBNAME'] = "d_test"; // ����� ���� �����

// REMOTE IP Address Control
// ϳ��������� ����� �����-������. ��� ��������� �������� ����������� �������� ��-������ �볺���.
// ���� ������ ���� �� ������ �� ���������.
$PARAM['VIA_PROXY'] = false; 

// Index page

$INDEX['BASE_TPL_DIR'] = "tpls/";

$PARAM['MIN_RATING_VALUE'] = 35;

?>