<?php

$str=str_repeat('foo ',10000);

//explode()
$time=microtime(TRUE);
$arr=explode($str,' ');
$time=microtime(TRUE)-$time;
echo "explode():$time sec.".PHP_EOL;

//strtok()
$time=microtime(TRUE);
$ret=strtok(' ',$str);
while($ret!==FALSE){
    $ret=strtok(' ');
}
$time=microtime(TRUE)-$time;
echo "strtok():$time sec.".PHP_EOL;

