<?php
session_start();
include_once 'server_connect.php'; //соединяемся с сервером БД
 
if(md5($_SERVER['PHP_AUTH_PW'])==="6cf32f4cca135e47a01d8e706bd17f8f" and md5($_SERVER['PHP_AUTH_USER'])==="0408f3c997f309c03b08bf3a4bc7b730"){
    include './classes/pageClass.php'; //подключаем файл класса страницы
    include_once 'server_connect.php'; //соединяемся с сервером БД
    include './classes/WordList.php';
    
    //$_SESSION['access']=1;
    $_content='';
    if(isset($_POST['btnFind'])){
        include './forms/search_form.php';
        $_content = $my_form ->toString();;
    }
    if(isset($_POST['btn_search'])){
        include './forms/search_form.php';
        $_content = $my_form->toString();

    }
    if(isset($_POST['btn_search_empty'])){
        echo 'start search  empty';
        $_content = "Результат поиска";
    }
    
    if(isset($_POST['btnDel'])){
        include './forms/search_form.php';
          //$_content='Запустить процедуру удаления єлемента - '.$wordId;
          $_content .= $my_form->toString();
    }
     
     include_once 'template_admin.php';
        
}

else{
    header('WWW-Authenticate: Basic realm="administration region"');
    header('HTTP/1.0 401 Unauthorized');
}