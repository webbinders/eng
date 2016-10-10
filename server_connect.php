<?php
//Подключаемся к серверу
         
        //подключаемся к серверу
        $server='localhost';
        $username='root';
        $password='';//adminadmin для pws
        
        

        $link = mysql_connect($server, $username,$password);
        if (!$link) {
            die('Ошибка соединения: ' . mysql_error());
        }
        //echo 'Успешно соединились<br>';
        mysql_query("SET NAMES utf8"); 
 ?>   