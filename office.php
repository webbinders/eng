<?php
        session_start();
        include './classes/pageClass.php'; //подключаем файл класса страницы
        $pageObj = new pageClass('выводим код офиса');
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        //если пользователь вошел дать ему знать
        echo $pageObj->build_Page();
?>
