<?php

//$string = htmlspecialchars($_POST['text_area']);
$string = <<<STR
Головне

    Посадковий модуль Schiaparelli втратив зв'язок із Землею під час посадки на Марс (Уніан)
    «Динамо» проиграло «Бенфике» и опустилось на последнее место в группе (Xsport.ua)
    Переговоры нормандской четверки в Берлине. Хроника событий (Страна.ua)

Documentation <for PHP 4>  has been removed from the manual, but there is archived version still available. For more informations, please read Documentation for PHP 4.

Formats Destination's


STR;

//$string=utf8_encode($string);
/*
$words_arr = preg_split("/[\s,.;:\"]+/", $string);
print_r($words_arr);*/

$tok=strtok($string,":; ()[]{}.,?!\n\t");
echo "$tok<br/>";
//для каждого токена
while($tok!==false){
	
	$tok=strtok(":; ()[]{}.,?!\n\t");
	
        $tok_ = lTok($tok);
        if ($tok_ !=''){
            $tok_ = rTok($tok_);
        }
        
        
        echo "$tok_<br/>";
}
//echo $_POST['word'];
function lTok($string) {
    if (!preg_match("/^[a-zA-Z]/", $string)){
        //находим позицию первого вхождения буквенного символа
        //echo $string.'<br>'; //echo $string[1].'<br>';
        if(preg_match("/[a-zA-Z]/", $string,$matches, PREG_OFFSET_CAPTURE)){
            $pos = $matches[0][1];
        
            //создаем новую строку из подстроки, начинающейся с этого символа.
            $string = substr( $string, $pos);
        }
        else{
            $string = '';
        }
        
        
    }
    return $string;
}

function rTok($string) {
    if (preg_match("/[^a-zA-Z]$/u", $string)){
        //находим позицию первого вхождения не буквенного символа
        if(preg_match("/[^a-zA-Z]$/", $string, $matches, PREG_OFFSET_CAPTURE)){
                    //print_r($matches);
        $pos = $matches[0][1];
        //создаем новую строку из подстроки, начинающейся с этого символа.
        $string = substr( $string, 0,$pos);
        }
        else{
            $string = '';
        }
            

    }
    return $string;
}