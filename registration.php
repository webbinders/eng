<?php

        session_start();

        
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        
        //если пользователь вошел дать ему знать
        if(isset($_SESSION['access']) && $_SESSION['access'] == 1){
            $_SESSION['msg'] = "<div class = 'msg'>Вы успешно зарегистрировались</div>";
            
            header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
            
            
        }
        include './classes/pageClass.php'; //подключаем файл класса страницы
        include_once 'forms/registration_form_handler.php';//подключаем файл-обработчик формы регистрации
        include_once 'forms/registration_form.php';//подключаем файл формы регистрации
      

        //создаем объект веб-страницы
        $pageObj = new pageClass($my_form->toString());

        echo $pageObj->build_Page();
?>
