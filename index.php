
        <?php
        session_start();
        $_SESSION['lang'] = 'ru';
        include './classes/pageClass.php'; //подключаем файл класса страницы
        include 'functions.php';
        //include 'socbuttons.php';//подключаем файл скодами социальных кнопок
        
        //если пользователь авторизирвался
        if (isset($_SESSION['access']) && $_SESSION['access']){
            //переходим в личный кабинет
             header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/office.php');
        }
        else{
            /*/если пользователь еще не авторизирвался
            //отображаем интерфейс авторизации
             */
        }
        $content = about();
        $socButtonsArr = array('facebook');//массив добавляемых соцсетей
        //$content = facebookButton($content,$socButtonsArr);
        $pageObj = new pageClass($content, $socButtonsArr);
        echo $pageObj->build_Page(); 
        ?>
  
