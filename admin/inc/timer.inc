<?PHP

/*
 * @(#)timer.inc 	1.1	08/04/2007
 *
 * d-tester admin subsystem [definition special timer functions]
 *
 * Start date:  11/07/2005 21:25 GMT +02:00
 * Last update: 16/07/2005 09:45 GMT +02:00 
 *
 * Copyright (c) 2005-2007 Yuriy Bezgachnyuk
 * 3/33, Respublicansca St., Ivano-Frankivsk (IF), 76005, Ukraine
 * All Rights Reserved
 */

function add_time($start, $add)
{
    if((strlen($start) < 8)
    ||(strlen($add) < 8)) return false;

    $start_time_arr = explode(":",$start);
    $add_time_arr = explode(":",$add);

    $i = 2;

    while($i >= 0)
    {
        $sa_time_arr[$i] = $start_time_arr[$i] + $add_time_arr[$i];
        $i--;
    }

    $i = 2;

    while($i >= 0)
    {
        if($i != 0)
        {
            if($sa_time_arr[$i] >= 60)
            {
                $sa_time_arr[$i] = ($sa_time_arr[$i] - 60);
                $sa_time_arr[$i-1] = ($sa_time_arr[$i-1] + 1);
            }
        }
        $i--;
    }

    return $sa_time_arr;
}

function sub_time($start, $minus)
{
    if((strlen($start) < 8)
    ||(strlen($minus) < 8)) return false;

    $start_time_arr = explode(":",$start);
    $minus_time_arr = explode(":",$minus);

    $i = 0;

    while($i <= 2)
    {
        $ma_time_arr[$i] = $start_time_arr[$i] - $minus_time_arr[$i];
        $i++;
    }

    $i = 0;
    while($i <= 2)
    {
        if($i != 0)
        {
            if($ma_time_arr[$i] < 0)
            {
                $ma_time_arr[$i] = $ma_time_arr[$i] + 60;
                $ma_time_arr[$i-1] = ($ma_time_arr[$i-1] - 1);
            }
        }
        $i++;
    }
    return $ma_time_arr;
}

function TimeToStr($time)
{
    $str = "";
    for($i = 0;$i < 3;$i++)
    {
        if($i != 2)
        {
            if(strlen($time[$i]) < 2)	$str.="0";
            $str.=$time[$i];
            $str.=":";
        }
        else
        {
            if(strlen($time[$i]) < 2)	$str.="0";
            $str.=$time[$i];
        }
    }
    return $str;
}

?>