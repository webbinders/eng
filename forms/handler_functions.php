<?php

/* 
 *
 */

 /*
  * Извлекает логин из эмейла (часть эмейла до символа @)
  */
 function getLogin($emal){
    $arr = explode('@', $emal);
    return $arr[0];
}
