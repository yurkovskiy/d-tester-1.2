<?php

/**
 * @package d-tester
 * @version 1.2 RC1
 * @subpackage tester subsystem
 * @name Security Image Block
 * @author Yuriy V. Bezgachnyuk
 * @copyright 2008 by Yuriy Bezgachnyuk, IF, Ukraine
 * 
 * Start date: 07/09/2008
 * Last update:
 */

$s = strval($_GET['s']); // md5 hash - $_SESSION array index

session_start();

$file = $_SESSION[$s];
$ext = strtoupper($file);

if (strstr($ext, "JPG") != null) {
	$image = imagecreatefromjpeg($file);
	header("Content-type: image/jpeg");
	imagejpeg($image);
}

if (strstr($ext, "JPEG") != null) {
	$image = imagecreatefromjpeg($file);
	header("Content-type: image/jpeg");
	imagejpeg($image);
}

if (strstr($ext, "PNG") != null) {
	$image = imagecreatefrompng($file);
	header("Content-type: image/png");
	imagepng($image);
}

if (strstr($ext, "GIF") != null) {
	$image = imagecreatefromgif($file);
	header("Content-type: image/gif");
	imagegif($image);
}

?>