<?php

//$string = htmlspecialchars($_POST['text_area']);
$string = <<<STR

'Documentation' <for 'PHP' 4>  has been removed from the "manual", but there is archived version still available. For more informations, please read Documentation for PHP 4.

Formats Destination's
----
yytgg



STR;

//$string=utf8_encode($string);
/*
$words_arr = preg_split("/[\s,.;:\"]+/", $string);
print_r($words_arr);*/
echo htmlspecialchars($string).'<br><br>';

$string=preg_replace('/(\s\')|(\'\s)|\d+|[^\w\']+/',',',$string);

echo $string.'<br>';

$words_arr = explode(',',$string);

echo '<br>';
foreach($words_arr as $word){
	if (isset($word_map[$word]) && ($word_map[$word] != '') ){
		
	 $word_map[$word] += 1;
	}else{
		 $word_map[$word] = 1;
	}
	
	
}
print_r($word_map);

//формируем запрос
$query = "INSERT INTO thesaurus (foreign,frequency) VALUES ";
foreach($word_map as $foreign => $frequency){
	 $query .= "('$foreign' , $frequency),";
}
$query = substr( $query, 0, -1);
$query .= " on duplicate key update  frequency = frequency + values(frequency);";
echo '<br>';
echo $query;
echo '<br>';
    //Подключаемся к серверу
     include "server_connect.php";
     
    //устанавливаем кодировку utf-8
     mysql_query("SET NAMES utf8"); 
     
    //выбираем базу данных
     $db_name='eng';
     
    //если не удалось выбрать базу 
     if (!mysql_select_db($db_name)) {
          die ('Не удалось выбрать базу  '.$db_name.'<br>' . mysql_error());
     }
          
        $result = mysql_query($query);
     
    //если произошла ошибка соединения
    if (!$result) {
        die('Ошибка соединения: ' . mysql_error());
    }
    echo 'Данные обновлены';