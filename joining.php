<?php
        session_start();
        
        if(isset($_SESSION['access']) && $_SESSION['access'] == 1){
            header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
        }
            
        include './classes/pageClass.php'; //подключаем файл класса страницы
        include_once 'forms/joining_form_handler.php';//подключаем файл-обработчик формы входа
        include_once 'forms/joining_form.php';//подключаем файл формы входа
        if(isset($_SESSION['access']) && $_SESSION['access'] == 1){
            header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
        }
        if (!isset($_SESSION['msg'])) $_SESSION['msg'] = '';
        $pageObj = new pageClass($_SESSION['msg'].$my_form->toString());//создаем объект страницы, содержащий форму
        $_SESSION['msg'] = '';//очищаем сообщение (последобавления его на страницу)
        echo $pageObj->build_Page();
?>
