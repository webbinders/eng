<?php

session_start();
$_SESSION['lang'] = 'ru';
include './classes/pageClass.php'; //подключаем файл класса страницы
include 'functions.php';
$content = about();
$socButtonsArr = array('facebook');//массив добавляемых соцсетей
//$content = facebookButton($content,$socButtonsArr);
$pageObj = new pageClass($content, $socButtonsArr);
echo $pageObj->build_Page(); 