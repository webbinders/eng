<?php

        session_start();
        include './classes/pageClass.php'; //подключаем файл класса страницы
        include_once 'forms/registration_form.php';//подключаем файл формы регистрации
        
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        
        //если пользователь вошел дать ему знать
        if(isset($_SESSION['sucsess'])){
            if ($_SESSION['sucsess'] == 1){
                header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
            }
            
        }
        //форма отображается впервые
        $pageObj = new pageClass($my_form->toString());

        echo $pageObj->build_Page();
?>
