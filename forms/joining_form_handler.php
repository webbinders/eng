<?php
$povtor=0;
$messing="";
$succs=0;
$_POST['msg']=array('email'=>'','login'=>'','psw'=>'','avatar'=>'');

if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'ru';

//подгрузка словаря
$word_arr= parse_ini_file($_SESSION['lang'].".ini");

//если форма отображается повторно
if(isset($_POST['btnOK'])){
    
    //обрабатываем поле email
    if(!isset($_POST['email'])||!strlen($_POST['email'] )){//если поле 'email' не заполнено
        $povtor="1";
        //устанавливаем в массив ошибок сообщение что поле не заполнено
        $_POST['msg']['email']= $word_arr['empty_email'];
    }
    else{//если поле 'email'  заполнено
        $email=($_POST['email']);
        //если некорректный email
        if($_POST["email"] && !preg_match("/^\\w+([\\.\\w]+)*\\w@\\w((\\.\\w)*\\w+)*\\.\\w{2,3}$/",$_POST["email"])){
             $povtor="1";
             //устанавливаем в массив ошибок сообщение что поле имеет некорректный адрес             
        }
    }
    
    //обрабатываем поле psw
     if(!isset($_POST['psw'])||!strlen($_POST['psw'])){//если поле "пароль" не заполнено
         $povtor="1";
         //устанавливаем в массив ошибок сообщение что поле не заполнено
         if (isset($_POST['msg']['psw'])){
                $_POST['msg']['psw']=$word_arr['empty_psw'];
            }
    }
    else{//поле заполнено, то проверяем корректность заполнения
    
        //если пароль имеет недопустимые символы
        $pass =($_POST['psw']);
        if($pass && !preg_match( "/^[-_\w\.]+$/i",$pass)){
             $povtor="1";
             //устанавливаем в массив ошибок сообщение что поле поле имеет недопустимые символы
             $_POST['msg']['psw']=$word_arr['incorect_psw'];
        }     
    }
    
     if($povtor){//если надо повторно заполнить форму
     echo' <br>что-то заполнено не правильно';
     
 }
 else{//все заполнено корректно
     //Подключаемся к серверу
     include "server_connect.php";
     /*/устанавливаем кодировку utf-8
     mysql_query("SET NAMES utf8"); 
     //выбираем базу данных
     $db_name = 'eng';
     
     if (!mysql_select_db($db_name)) {
              die ('Не удалось выбрать базу  '.$db_name.'<br>' . mysql_error());
     }
     */
     //выполняем поиск по полю email
     $email = $_POST['email'];
      $result2 = mysql_query("SELECT * FROM users WHERE email = '$email'");
      if ( !mysql_num_rows ($result2)){
          //устанавливаем в массив ошибок сообщение что такой адрес не зарегистрирован в БД
          $_POST['msg']['email'] = $word_arr['not_found_email'];
          $povtor = 1;
      }
      else{
          $row = mysql_fetch_array($result2,MYSQL_ASSOC);
          if ($_POST['psw'] == $row['psw']){//если пароль совпадает
              //устанавливаем переменные сессии
              $_SESSION['access'] = 1;
              $_SESSION['user_id'] = $row['id'];
              include 'handler_functions.php';
              $_SESSION['login'] = getLogin($row['email']);
              //перенаправляемся в офис  
          }
          else{
              $povtor = 1;
          }

      }



 }
}

