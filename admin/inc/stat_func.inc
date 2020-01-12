<?php

// stat_func.inc - stat table functions
// Copyright (c)2006 by Yuriy Bezgachnyuk, IF, Ukraine
// Last Update: 07.12.2006 07:36 GMT +02:00

function avg_array_value($in_array)
{
	$avg_val=array_sum($in_array)/(sizeof($in_array));
	return $avg_val;
}

function w_sqr($in_array)
{
	$tmp_array=array();
	$avg_ina=avg_array_value($in_array);
	while(list($key,$val)=each($in_array))
	{
		$tmp_array[$key]=pow(($in_array[$key]-$avg_ina),2);
	}
		
	return (array_sum($tmp_array))/sizeof($tmp_array);
}

?>