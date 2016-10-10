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
    function  __construct($_content){
        $_roof  = 
<<<BEGINPAGE
            <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <title></title>
                </head>
                <body><div class="roof">Шапка сайта</div>
BEGINPAGE;
        //если пользователь вошел
        if(isset($_SESSION['login_fl']) && ($_SESSION['login_fl'] == TRUE)){
            //то приветствуем его
            $_roof .= "<div>Hello </div>";
            //и подключаем его БД
            
           
        }
        else{//если пользователь еще не авторизирвался
            //отображаем интерфейс авторизации
            $log_in = 
<<<LOGIN
            <div>Интерфейс входа / регистрации </div>
            <a href='joining.php'>Вход</a><br>
            <a href='registration.php'>Регистрация</a><br>
            <a href='forgot.php'>Забыли пароль</a><br>
LOGIN;
            $_roof .= $log_in;
        }
        
        $this->roof = $_roof;
        $this-> content = $_content;
        $this-> footer = '<div class="footer">Подвал сайта</div></body>
</html>';
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
