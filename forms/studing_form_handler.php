<?php

/* 
 * функция добавления заданного количества слов заданного уровня
 * Должна использоваться при подключенном 'server_connect.php'
 */
include_once 'server_connect.php';
function build_stud_list ($countWords, $level){
    $studList = array();
    //создаем запрос на выборку слов текущего уровня отсутствующих в списке для изучения
    $query = "SELECT * FROM '$tableName' WHERE level <= $level AND stud = 0 ORDER BY level, studDate LIMIT 0, $countWords";
    //выполняем запрос
    $result = queryRun($query, "Сбой при чтении из личной таблицы при составлении списка для изучения");
    //добавляем все слова полученные в результате запроса в список для изучения
     
    while ($properties = mysql_fetch_assoc($result)) {
        //дополняем недостающие свойства слова данными из таблицы thesaurus
        $id = $properties['id'];
        $query = "SELECT * FROM thesaurus WHERE id = $id ;";
        $properties_thesaurus = mysql_fetch_assoc(queryRun($query, "Сбой при чтении из  таблицы thesaurus при составлении списка для изучения"));
        $properties['foreign'] = $properties_thesaurus['foreign'];
        $properties['native'] = $properties_thesaurus['native'];
        //создаем объект-слово и добавляем его в массив слов для изучения
        $word = new Word($properties);
        $studList[$word->id] = $word;  
        
        
    }
    return $studList;
   
    
    
    
}


