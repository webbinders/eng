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


    private $pageHTML;
    private $head;
    private $header;
    private $content ;
    private $footer ;
    private $templatePath = "/template.php";
    /*
     * Конструктор
     */
    function  __construct($_content = '', $socNets = array()){
        
        
        $socButtons = '';        
        foreach ($socNets as $value) {
            switch ($value){
                case 'facebook':
                    $socButtons .= $this->socButtonCodeFacebook();

                            break;
                    
            }
        }
        include __DIR__."/../template.php";
        
        $this->head = $head;
        $this->header = $header;
        $this->content = $t_content;
        $this->footer = $footer;


        

        

        



    }
    
    function socButtonCodeFacebook(){
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
        return $socButtonPart1.$socButtonPart2;
    }
                
 
     function build_Page(){
        $pageHTML = <<<PAGE
            <!DOCTYPE html>
            <html>
                $this->head
                <body>
                    $this->header
                    $this->content
                    $this->footer
                </body>
            </html>
PAGE;
        return $pageHTML;
    }
    }
    

?>
