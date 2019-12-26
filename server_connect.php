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
        //выбираем базу данных
        $db_name='studentk_eng';

        //если не удалось выбрать базу 
        if (!mysql_select_db($db_name)) {
             die ('Не удалось выбрать базу  '.$db_name.'<br>' . mysql_error());
        }
        mysql_query("SET NAMES utf8"); 
        /*
         * Выполняет sql-запрос к БД $query или выводит сообщение об ошибке $msgErr.
         */
        function queryRun($query ,$msgErr){
            $result = mysql_query($query);
            if (!$result) {
                die($msgErr . ' : ' . mysql_error());
                }
            return $result;
            }
        
 ?>