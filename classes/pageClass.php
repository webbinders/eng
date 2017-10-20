<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pageClass
 * Этот класс описывает страницу.
 * Структура страницы следующая:
 * - шапка
 * - - лого
 * - - меню
 * - контент
 * - - область читаемого текста
 * - - инструментальная область
 * - подвал
 * @author Ser
 */
class pageClass {


    private $roof ;
    private $content ;
    private $footer ;
    /*
     * Конструктор
     */
    function  __construct($_content = ''){
        $hat = '';//'Шапка сайта';
        $_roof  = 
<<<BEGINPAGE
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <link rel="stylesheet" type="text/css" href="./normalize.css">
                     <link rel="stylesheet" type="text/css" href="./fstyle.css">
                    <title></title>
                </head>
                <body><div class="roof">$hat </div>
BEGINPAGE;
        //если пользователь вошел
        if(isset($_SESSION['access']) && ($_SESSION['access'] == 1)){
             $_roof .= "<a href='exit.php'>Выход</a><br>";
            //то приветствуем его 
            $_roof .= "<div>Hello ". $_SESSION['login'] ."</div>";
            //и подключаем его БД
            
           
        }
        else{//если пользователь еще не авторизирвался
            //отображаем интерфейс авторизации
            $log_in = 
<<<LOGIN
            <div class = 'authorization'>
                <a href='joining.php'>Вход</a><br>
                <a href='registration.php'>Регистрация</a><br>
                <a href='forgot.php'>Забыли пароль</a><br>
            </div>
LOGIN;
            $_roof .= $log_in;
        }
        
        $this->roof = $_roof;
        $this-> content = $_content;
        $footer_content = '';//содержимое подвала сайта
        $this-> footer = '<div class="footer">'.$footer_content.'</div></body></html>';//контейнер для подвала и завершающие теги для страницы
    }
    
 
    /*
     * Функция формирующая контент (среднюю часть)страницы
     */
     private function build_Content($content_){
         include_once 'class_form.php';
         $this -> content = $content_;
         
    }
    
 
     function build_Page(){
        return $this -> roof . $this -> content . $this -> footer;
    }
}

?>
