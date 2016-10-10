
        <?php
        session_start();
        include './classes/pageClass.php'; //подключаем файл класса страницы
        
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        if (isset($_SESSION['succes']) && $_SESSION['succes']){
            
        }
        else{
            //если пользователь еще не авторизирвался
            //отображаем интерфейс авторизации
            $log_in = 
<<<LOGIN
            <div>Интерфейс входа / регистрации </div>
            <a href='joining.php'>Вход</a><br>
            <a href='registration.php'>Регистрация</a><br>
            <a href='forgot.php'>Забыли пароль</a><br>
LOGIN;
        }
        //если пользователь вошел дать ему знать
        $pageObj = new pageClass($log_in);
        echo $pageObj->build_Page();
        
        ?>
  
