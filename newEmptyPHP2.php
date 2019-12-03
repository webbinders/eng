<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$d='1997-10-04 22:23:00';
$t=strtotime($d);
echo $t.'<br>';
echo '<br>'.date('m/d/Y H:i:s', $t).'<br>';
$t = time();
echo '<br>'.  $t;
echo '<br>'.date('m/d/Y H:i:s', $t).'<br>';
echo '<br>'.'-------------'.'<br>';

echo '<br>'.'-------------'.'<br>';
$localtime = localtime();
$localtime_assoc = localtime(time(), true);
print_r($localtime).'<br>';
print_r($localtime_assoc).'<br>';
echo '<br>'.'-------------'.'<br>';
if (date_default_timezone_get()) {
    $tz = date_default_timezone_get() ;
}
echo '<br>'.'-------------'.'<br>';
if (ini_get('date.timezone')) {
    $tz = date_default_timezone_get() ;
}
echo '<br>'.'-------------'.'<br>';
$timeInDB = $d;
$datetime = new DateTime($timeInDB);
$timezone = new DateTimeZone($tz);
$datetime->setTimeZone($timezone);
print $datetime->format('Y-m-d H:i:s').'<br>';

print_r ($datetime);

$timeInDB = '2013-12-24 12:43:28';
$datetime = new DateTime($timeInDB);
$timezone = new DateTimeZone('Europe/Paris');
$datetime->setTimeZone($timezone);
print $datetime->format('Y-m-d H:i:s');
print_r ($datetime);

function getTimestamp($strDate) {
//echo $strDate;
    $dateAndTime = explode(' ', $strDate);
    $dateArr = explode('-', $dateAndTime[0]);
    $timeArr = explode(':', $dateAndTime[1]);
    return mktime($timeArr[0], $timeArr[1], $timeArr[2], $dateArr[1], $dateArr[2], $dateArr[0]);
}