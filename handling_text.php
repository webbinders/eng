<?php
    //session_start();
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

$string = $_POST['text_area'];
/*$string = <<<STR
'Documentation' <for 'PHP'. 4>  has been removed from the "manual", but there is archived version still available. For more informations, please read Documentation for PHP 4.

Formats Destination's
----
yytgg



STR;

//$string=utf8_encode($string);
/*
$words_arr = preg_split("/[\s,.;:\"]+/", $string);
print_r($words_arr);*/
//echo htmlspecialchars($string).'<br><br>';

$string=preg_replace('/(^\s*\')|(\s\')|(\'\W)|(\'\s)|\d+|[^\w\']+/',',',$string);

//echo $string.'<br>';

$words_arr = explode(',',$string);

//echo '<br>';
//print_r($words_arr);
foreach($words_arr as $word){
    $word = strtoupper($word);
	if (isset($word_map[$word])){
		
	 $word_map[$word] += 1;
	}else{
            if ($word != ''){
                $word_map[$word] = 1;
            }
		 
	}
	
	
}
//print_r($word_map);
$query_midle_part='';
//определяем id-шники для всех слов
foreach($word_map as $foreign => $frequency){
    $query = "INSERT INTO thesaurus(`foreign`,`frequency`) VALUES". "('".addslashes($foreign)."','$frequency')" . " on duplicate key update  frequency = frequency + values(frequency);";
    //Определям id добавленной или существующей записи
    $result = mysql_query($query);
     if (!$result) {
        die('Ошибка соединения: ' . mysql_error());
    }
    $id = mysql_insert_id();
    $word_id[$id]= $frequency;
    if($id){
        
    }
    //Формируем часть sql-запроса на обновление или добавления данных в личной таблице
    $query_midle_part .= "('$id','$frequency'),";
    
}
//echo '<br>';
//print_r($word_id);

//формируем запрос
$table_name = 'u'.$_SESSION['user_id'];
$query = "INSERT INTO `$table_name` (`id`,`shows`,`answers`) VALUES ";
foreach($word_id as $id => $frequency){
	 $query .= "('$id','$frequency',1),";      
         

}
$query = substr( $query, 0, -1);
$query .= " on duplicate key update  shows = shows + values(shows) and answers = answers + values(answers);";
         echo '<br>';
//echo $query;
//echo '<br>';
         $result = mysql_query($query);
         //если произошла ошибка соединения
    if (!$result) {
        die('Ошибка соединения: ' . mysql_error());
    }



          
      
     
    
    //echo 'Данные обновлены';