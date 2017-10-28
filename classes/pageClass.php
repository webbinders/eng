<?php
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
    function  __construct($_content = '', $socNets = array()){
        $hello ='';
        //если пользователь вошел 
        if(isset($_SESSION['access']) && ($_SESSION['access'] == 1)){
            $auto_button ="<button class='btn-autoriz' formaction='exit.php'>Выход</button>";
            $hello = "<div id='hello'>Hello <br>". $_SESSION['login'] ."</div>";            
        }
        else{
            $auto_button =<<<BUTTON
                        <button class="btn-autoriz" formaction='registration.php'>Регистрация</button>
                        <button class="btn-autoriz" formaction='joining.php'>Вход</button>
                        
BUTTON;
        }
        //формируем гоизонтальное меню
        $nav = <<<NAV
        <nav>
            <form>
                <button formaction = >О сайте</button>
                <button formaction = >Режим чтения</button>
                <button formaction = >Режим изучения</button>
            </form>

        </nav>
NAV;
        //формируем верхнюю часть страницы соответствующую свойству  $roof
        $_roof = <<<ROOF
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Read in english</title>
	
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<header>
<div class="center-block">
   <div class="logo">
       <img src="logo.svg" width="50">
       <p><i>Читайте с легкостью.</i></p>
   </div>
   <div class="menu">  
       <div class="autoriz">
          $hello
                <form>$auto_button</form>

       </div>
              $nav
       
       </div>
   </div>
</header>
ROOF;

        

        
        $this->roof = $_roof;
        $this-> content =<<<CONTENT
            <div class="center-block">
               <section>
                   $_content  
               </section>

            </div> 
CONTENT;
        
        $footer_content = '';//содержимое подвала сайта
        $socButtonFoot = $socButtonPart1 = $socButtonPart2 = '';
        foreach ($socNets as $value) {
            switch ($value){
            case 'facebook':
                //добавляем первую часть кода в начало контента
                $socButtonPart1  =
<<<PART1
    <div id="fb"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.10";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
            
PART1;
                   // $this->content = $socButtonPart1.$this->content;
                    
                    //формируем вторую часть кода, для добавления в подвал
                        $socButtonPart2 =
<<<PART2
    <div class="fb-like" 
        data-href="http://www.eng.zzz.com.ua" 
        data-layout="standard" 
        data-action="like" 
        data-size="small" 
        data-show-faces="true" 
        data-share="true">
    </div>            
PART2;
                        break;
                    
            }
        }
        //устанавливаем значение подвала
        $footer_content = <<<FOOTER
        <footer>
            <div class="center-block">
                $socButtonPart1
               $socButtonPart2 
            </div>     
        </footer>
    </body>
</html>
FOOTER;

        $this-> footer = $footer_content;
    }
    
 

    
 
     function build_Page(){
        return $this -> roof . $this -> content . $this -> footer;
    }
}

?>
