<?php
//инициализируем начальные значение переменных
  if(!isset($povtor)) $povtor=0;
  if(!isset($messing)) $messing="";
  if(!isset($succs)) $succs=0;
  if(!isset($_POST['msg'])) $_POST['msg']=array('email'=>'','login'=>'','psw'=>'','avatar'=>'');
  $_SESSION['access'] = 0;
  
//подгрузка словаря
//$word_arr= parse_ini_file($_SESSION['lang'].".ini");
//подгрузка словаря
$word_arr= parse_ini_file("ru.ini");
  
//если форма отображается повторно
if(isset($_POST['btnOK'])){
    
    //если поле 'email' не заполнено
    if(!isset($_POST['email'])||!strlen($_POST['email'])){
        $povtor="1";
        //устанавливаем в массив ошибок сообщение что поле не заполнено
        $_POST['msg']['email']= $word_arr['empty_email'];
    }
    else{
    //поле заполнено
    $email=($_POST['email']);
    //если некорректный email    
    if($_POST["email"] && !preg_match("/^\w+([\.\w]+)*\w@\w((\.\w)*\w+)*\.\w{2,3}$/",$_POST["email"])){
         $povtor="1";
         //устанавливаем в массив ошибок сообщение что поле имеет некорректный адрес
         $_POST['msg']['email']=$word_arr['incorrect_email'];
    }

}    

    //если поле "пароль" не заполнено
    if(!isset($_POST['psw'])||!strlen($_POST['psw'])){
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
    //если надо повторно заполнить форму (поля были не заполнены или неправильно заполнены)
 if($povtor == 1){
     echo' <br>что-то заполнено не правильно';
     
 }
 //Если все поля заполнены правильно
 else{
     
    //Подключаемся к серверу
     include "server_connect.php";
     
    //устанавливаем кодировку utf-8
     mysql_query("SET NAMES utf8"); 
     
    //выбираем базу данных
     $db_name='eng';
     
    //если не удалось выбрать базу 
     if (!mysql_select_db($db_name)) {
          die ('Не удалось выбрать базу  '.$db_name.'<br>' . mysql_error());
     }
     
    //Ищем пользователя в БД
    
    //$email = $email_box->value;
    //var_dump($my_form);
    $result2 = mysql_query("SELECT * FROM users WHERE email = '$email'");
     
    //если произошла ошибка соединения
    if (!$result2) {
        die('Ошибка соединения: ' . mysql_error());
    }


    //если пользователь найден, значит пользователь зарегистрирован ранее и возможно он забыл пароль.
    if ( mysql_num_rows ($result2) && $email !=''){
        $_POST['messing2']=$word_arr['existing_email'].'<a href="forgot.php">'.$word_arr['forgot'].'</a>';
        if (isset($_POST['msg'])){
           $_POST['msg']['email']=$_POST['messing2']."<br>";
        }
        echo 'Пользователь найден';
        //echo $my_form->toString();
    }
     
     else{
         //добавляем нового пользователя в БД
         $result = mysql_query("INSERT INTO users(email,psw) VALUES ('$email','$pass');");
         //если регистрация прошла успешно
         if(mysql_affected_rows()>0){
             //отображаем личный кабинет
             print 'отображаем личный кабинет';
             //устанавливаем переменные сессии
             $_SESSION['access'] = 1;
                //переходим в личный кабинет
            // header('Location: http://'.$_SERVER['HTTP_HOST'].'/'. $_SERVER['DOCUMENT_ROOT'] .'/office.php');
         }
         else{//если во время добавления пользователя произошел сбой
              if(!$result) die('Ошибка соединения: ' . mysql_error());
         }
        
         
     }
     

 }
}
 

       
?>
