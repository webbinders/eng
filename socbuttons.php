<?php

/*
 * Функция для добавления соцальных кнопок фейсбука на переданный контент
 */
function facebookButton($content){
    
    $part1 =
<<<PART1
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.10";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
            
PART1;
    
    $part2 =
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

    return $part1.$content.$part2;
}
