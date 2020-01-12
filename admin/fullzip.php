<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage admin subsystem [Export Unit]
 * @name Full ZIP test export unit [dt-XML + all test's images]
 * @author Yuriy Bezgachnyuk
 * @copyright 2009 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * 
 */

require_once("inc/settings.inc");
require_once("inc/dir.inc");

$test_id = 40;

$files_dir = $PARAM['TEST_BASE'].$test_id; // directory with test files

$ZIP_FILE_NAME = UPLOAD_FILE_DIR."test_".$test_id.".zip";

$zip = new ZipArchive();

$res = $zip->open($ZIP_FILE_NAME, ZipArchive::CREATE);

//$res = true;

if ($res == true) {
	// adding files to archive
	$files = get_images($files_dir);
	foreach ($files as $file) {
		echo $files_dir."/{$file}\n";
		$full_path_file = $files_dir."/".$file;
		$zip->addFile($full_path_file, $file);
	}
	$zip->close();
	echo "ok";
}
else {
	echo "Full Tuhes";
}

?>