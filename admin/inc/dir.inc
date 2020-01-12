<?php

/*
 * @(#)dir.inc 	1.1	08/04/2007
 *
 * d-tester admin subsystem [definition special file system functions]
 *
 * Last update: 25/07/2006 06:55 GMT +02:00 
 *
 * Copyright (c) 2005-2007 Yuriy Bezgachnyuk
 * 3/33, Respublicansca St., Ivano-Frankivsk (IF), 76005, Ukraine
 * All Rights Reserved
 */

function get_images_from_dir($path)
{
    // Global function variables
    $out = array();
    $files = array();
    $dir = @opendir($path); // pointer to dir
    if(!$dir) die("Directory read error");
    while($file = @readdir($dir))
    {
        if(($file ==".")
        ||($file =="..")) continue;

        $info = pathinfo($file);
        $ext = $info['extension']; // file extension => can be type;
        $ext = strtoupper($ext); // ext => EXT

        if((strstr($ext,"JPG") != null)
        ||(strstr($ext,"JPEG") != null)
        ||(strstr($ext,"GIF") != null)
        ||(strstr($ext,"PNG") != null))
        $files[] = $file;
        //$out[].="<option value=\"{$file}\">{$file}</option>\n"; // $out[] => it's select container element
    }
    sort($files);
    foreach($files as $file) {
     $out[].="<option value=\"{$file}\">{$file}</option>\n";
    }
    @closedir($dir);
    return $out;
}

/**
 * get images file names from dir
 *
 * @param string $path
 * @return array $out
 */

function get_images($path) {
	$out = array();
	$dir = @opendir($path);
	if (!$dir) die("Directory Read Error");
	while ($file = @readdir($dir)) {
		if (($file == ".") || ($file == "..")) continue;
		array_push($out, $file);
	}
	return $out;
}

?>