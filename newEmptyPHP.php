<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*
 * Преобразует строку вида '2016-12-16 22:45:53'  в метку времени
 */
$d1 = '2016-12-17 02:00:00';
$d2 = '2016-12-18 00:00:00';
echo getTimestamp($d1);
echo '<br>';
echo getTimestamp($d2);
echo '<br>';
echo getTimestamp($d2)-getTimestamp($d1);
echo '<br>';
echo date('Y-m-d',getTimestamp($d2)-getTimestamp($d1));
function getTimestamp($strDate) {
    $dateAndTime = explode(' ', $strDate);
    $dateArr = explode('-', $dateAndTime[0]);
    $timeArr = explode(':', $dateAndTime[1]);
    return mktime($timeArr[0], $timeArr[1], $timeArr[2], $dateArr[1], $dateArr[2], $dateArr[0]);
}
