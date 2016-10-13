<?php
        session_start();
        //подгрузка словаря
        $word_arr= parse_ini_file($_SESSION['lang'].".ini");
        include './classes/pageClass.php'; //подключаем файл класса страницы
        $content = 'выводим код офиса';
        if (isset($_SESSION['msg'])){
            $content = $_SESSION['msg'] . $content;
        }
        if (!isset($_SESSION['access']) || $_SESSION['access'] == 0 ){//если пользователь не вошел
            $_SESSION['msg'] = $word_arr['invitation_to_enter'];
            
            //перенаправляем его на страницу входа
            header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/joining.php');
            
        }
        if (!isset($_SESSION['mode'])) $_SESSION['mode'] = 'mode_start';
switch ($_SESSION['mode']) {
    case 'mode_start':
        echo 'mode_start';

     break;

    default:
        break;
}
     
        $pageObj = new pageClass($content);
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        //если пользователь вошел дать ему знать
        echo $pageObj->build_Page();
?>
