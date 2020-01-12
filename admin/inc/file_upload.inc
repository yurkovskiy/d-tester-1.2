<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @name file upload functions
 * @author Yuriy Bezgachnyuk
 * @copyright 2008 Yuriy Bezgachnyuk
 * 
 * Start date: 14/02/2008 21:40 GMT +02:00
 * Last update: 21/02/2008 06:46 GMT +02:00
 * 
 * Main part (and idea) of this unit code are taked from PHP Manual [thanks!!!]
 */

// $userfile is where file went on webserver
$userfile = $_FILES['im_file']['tmp_name'];

// $userfile_name is original file name
$userfile_name = $_FILES['im_file']['name'];

// $userfile_size is size in bytes
$userfile_size = $_FILES['im_file']['size'];

// $userfile_type is mime type e.g. image/gif
$userfile_type = $_FILES['im_file']['type'];

// $userfile_error is any error encountered
$userfile_error = $_FILES['im_file']['error'];
// userfile_error was introduced at PHP 4.2.0
// use this code with newer versions
if ($userfile_error > 0) {
	echo "{$lang['file_problem']} ";
	switch ($userfile_error) {
		case 1:  echo "{$lang['file_upload_max_size']}";  break;
		case 2:  echo "{$lang['file_ini_max_size']}";  break;
		case 3:  echo "{$lang['file_partialy_uploaded']}";  break;
		case 4:  echo "{$lang['no_file_uploaded']}";  break;
	}
	exit;
}

// Checking file type

$file_ext = explode(".", $userfile_name); // extract file extension

$new_file_name = $userfile_name;

//echo "{$new_file_name}<br>\n";

// put the file where we'd like it
$upfile = UPLOAD_FILE_DIR.$new_file_name;

//echo "{$upfile}<br>\n";

// is_uploaded_file and move_uploaded_file added at version 4.0.3
if (is_uploaded_file($userfile)) {
	if (!move_uploaded_file($userfile, $upfile)) {
		echo 'Problem: Could not move file to destination directory';
		exit;
	}
}
else {
	echo 'Problem: Possible file upload attack. Filename: '.$userfile_name;
	exit;
}

?>