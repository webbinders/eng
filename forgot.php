<?php

        session_start();
        
        
        
        //если пользователь вошел дать ему знать
        if(isset($_SESSION['access']) && $_SESSION['access'] == 1){
            header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
        }
        include './classes/pageClass.php'; //подключаем файл класса страницы
        include_once 'forms/forgot_form_handler.php';//подключаем файл-обработчик формы восстановления пароля
        include_once 'forms/forgot_form.php';//подключаем файл формы восстановления пароля

        if (!isset($_SESSION['msg'])) $_SESSION['msg'] = '';
        $pageObj = new pageClass($_SESSION['msg'].$my_form->toString());//создаем объект страницы, содержащий форму
        $_SESSION['msg'] = '';//очищаем сообщение (последобавления его на страницу)
        echo $pageObj->build_Page();
        
?>
