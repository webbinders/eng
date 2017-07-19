<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//инициализируем начальные значение переменных
if(!isset($povtor)) $povtor=0;
if(!isset($messing)) $messing="";
if(!isset($succs)) $succs=0;
if(!isset($_POST['msg'])) $_POST['msg']=array('email'=>'');
$_SESSION['sucsess'] = 0;

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ru';

//подгрузка словаря
$word_arr= parse_ini_file($_SESSION['lang'].".ini");

//если скрипт формы загружается повторно
if(isset($_POST['btnOK'])){
    //если поле 'email' не заполнено
    if(!isset($_POST['email'])||!strlen($_POST['email'] )){
        $povtor="1";
            $_POST['msg']['email']= $word_arr['empty_email'];
    }
else{//если поле 'email'  заполнено
    $email=($_POST['email']);
    //если некорректный email
    if($_POST["email"] && !preg_match("/^\\w+([\\.\\w]+)*\\w@\\w((\\.\\w)*\\w+)*\\.\\w{2,3}$/",$_POST["email"])){
         $povtor="1";
         $_POST['msg']['email']=$word_arr['incorrect_email'];
    }

}
//если надо повторно заполнить форму
if($povtor){
    echo' <br>что-то заполнено не правильно';
}
else{
    //Подключаемся к серверу
    include "server_connect.php";
    //Ищем пользователя в БД
    $result2 = mysql_query("SELECT * FROM users WHERE email = '$email'");
    //если произошла ошибка соединения
    if(!$result2) {
        die('Ошибка соединения: ' . mysql_error());
        
    }
    //если пользователь найден
    if ( mysql_num_rows ($result2)){
        //отправляем письмо с паролем
        ///адрес получателя
        $row = mysql_fetch_array($result2,MYSQL_ASSOC);
        $to = $row['email'];
        ///Тема
        $subject = "Воостановление пароля";
        ///Сообщение
        ////Пароль
        $psw = $row['psw'];
        ////Сообщение
        $message = "Вы получили пароль по вашему запросу: \n $psw";
        ////Заголовок From
        $header = 'From: galsergey@eng.zzz.com.ua';
        ///Отправка
        if(mail($to, $subject, $message,$header)){
            
            $_SESSION['msg'] = 'The psw has been send to '.$to;
            //echo $_SESSION['msg'];
        }
        else{
            $_SESSION['msg'] = 'The psw hasn\'t been send';
            //echo $_SESSION['msg'];
            
        }
    }
    else{
        $_POST['msg']['email'] = 'User with email '. $_POST['email'].' not found';
        //echo $_SESSION['msg'];
    }

}
}