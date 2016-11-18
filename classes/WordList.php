<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WordList
 *
 * @author Ser
 */


class WordList{
    var $wordsList;
    /*
     * param $$text представляет собой исходный тест, из которого надо создать список слов
     */
     function  __construct($text){
         
        //разбиваем текст на слова и помещаем их в массив-карту частот
        $word_map = $this->splitText($text);
        
        //вносим слова в БД с одновременным собиранием их id в массив
        $words_arr = $this->toThesaurus($word_map);
        $this->toPersonalTab($words_arr);
        
        //создаем объект словаря по id-шникам слов
        //для этого для каждого id создаем объект-слово
        //и добавляем его в словарь текста            
            
        //формируем один запрос для выборки сразу всех слов текста из БД
        $queryToThesaurus = "SELECT * FROM thesaurus WHERE id IN (";
        $midlePartQuery = '';
        foreach($words_arr as $id => $frequency){
            $midlePartQuery .= "'$id',";
            
        }
        //удаляем последнюю запятую
        $midlePartQuery =  substr( $midlePartQuery, 0, -1);
        $queryToThesaurus .= $midlePartQuery .");";
        
        $resFromThesaurus = queryRun($queryToThesaurus, "Ошибка соединения: ");
           
        
        while ($row = mysql_fetch_array($resFromThesaurus,MYSQL_ASSOC)) {
            $word = new Word($row);
            $this->addWord($word);
        }
            
        
     }
    /*
     * Разбивает текст на слова и подсчитывает частоту слов в тексте
     * Возвращает массив $word_map[$word]=$frequency
     */
    function splitText($text){
         //разбиваем текст на слова и помещаем их в массив $words_arr
        $text=preg_replace('/(^\s*\')|(\s\')|(\'\W)|(\'\s)|\d+|[^(\w\’\')]|(\'*$)/',',',$text);
        $words_arr = explode(',',$text);
        
        //для каждого слова подсчитывем его частоту в тексте
        //т.е. создаем массив $word_map[$word]=частота
        //и при этом все слова переводим в верхний регистр
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
        return $word_map;
    }
     
    /*
     * Добавляет массив слов в таблицу thesaurus
     * Слову присваивается id и частота
     * возвращаемое значение: массив типа $word_id[$id]= $frequency;
     * где $frequency - частота слова в тексте.
     */
    function toThesaurus($word_map){
        include_once './server_connect.php';
        foreach($word_map as $foreign => $frequency){
            
            //Добавляем слово в тезарус
            $query = "INSERT INTO thesaurus(`foreign`,`frequency`) VALUES". "('".addslashes($foreign)."','$frequency')" . " on duplicate key update  frequency = frequency + values(frequency);";
            //echo $query;
            //Выполняем запрос
            $result = queryRun($query,'Error connect toThesaurus');
            
            //Определям id добавленной или существующей записи
            $id = mysql_insert_id();
            
            //формируем элемент массива массивов 
            //(массив id слов, каждый элемент которого, представляет собой массив свойств слова)
            $words_arr[$id][$frequency] = $frequency;
            $words_arr[$id][$foreign] = $foreign;
            
            //добавляем id в массив $word_id
            $word_id[$id]= $frequency;
        } 
        return $word_id;
    }
    
    /*
     * Добавить массив слов в личную таблицу
     */
    static function toPersonalTab($word_id) {
       
        //Определяем имя личной таблицы для запроса на добавление в нее записи
        $table_name = 'u'.$_SESSION['user_id'];
        
        //Формируем единичный запрос для добавление сразу всего массива данных
        $query = "INSERT INTO `$table_name` (`id`,`shows`,`answers`) VALUES ";
        $words_arr = array();
        foreach($word_id as $id => $frequency){
            $query .= "('$id','$frequency',1),";
            $words_arr[$id]=$id;
        }
        $query = substr( $query, 0, -1);//убираем последнюю запятую
        $query .= " on duplicate key update  shows = shows + values(shows) and answers = answers + values(answers);";
        
        //Выполняем запрос
        $result = queryRun($query,'<br>error toPersonalTab');


    }
    /*
     принимает в качестве параметра объект-слово и добавляет его в словарь текста (массив слов)
     */
    function addWord($word){
        //если слово не принадлежит списку слов
        if(!isset($wordsList[$word])){
            $this->wordsList[$word->id] = $word;
        }
        
    }


}
class Word{
    var $id;
    private $foreign;
    private $native;
    private $frequency;
    private $lastData;
    private $stud;
    private $studyingLevel;
    private $exampleList;
    private $transcription;
    private $theme;
    function setForeign($string){$this->foreign = $string;}
    function setNative($string){$this->native = $string;}
    function setFrequency($var){$this->frequency = $var;}
    
    function  __construct($property_arr){
        //var_dump($property_arr);
        $table_name = 'u'.$_SESSION['user_id'];
        $id = $property_arr['id'];
        //Добавляем слово в тезарус, если его там еще нет
        if (isset($property_arr['id']) ){
            //то выборку свойств проводим по id
            $query = "SELECT thesaurus.*, $table_name.*  FROM thesaurus, $table_name WHERE thesaurus.id = '$id' AND $table_name.id ='$id';";
            //echo '<br>'.$query.'<br>';
            //Выполняем запрос
            $result = queryRun($query,'Ошибка соединения');  
            if (mysql_num_rows($result) == 0){
                die('Нет id = '.$id . mysql_error());
            }
            $row = mysql_fetch_array($result);
            if (isset($property_arr['native']))
                $this->native = $row['native'].'\n'.$property_arr['native'];
            
        }
        else{
            //то выборку производим по слову. Если его не находим, то добавляем
            $query = "SELECT * FROM thesaurus, `$table_name` WHERE `foreign` = '".$property_arr['foreign']."'";
            $result = queryRun($query,'error in constructor');

            //если слово не найдено
            if (mysql_num_rows($result) == 0){
                
                //то добавляем слово в бд
                
                ///Создаем запрос на добаление в тезарус
                if (isset ($property_arr['native']) || $property_arr['native'] != ''){
                    //если был передан перевод
                    $query = "INSERT INTO thesaurus(`foreign`, `native`, `frequency`) VALUES". "('".addslashes($foreign)."', '$native', '$frequency');";
                }
                else{
                    //если введено тоько слово без перевода
                    $query = "INSERT INTO thesaurus(`foreign`,`frequency`) VALUES". "('".addslashes($foreign)."','$frequency');";
                }
                    

                
                ///Выполняем запрос
                $result = queryRun($query,'Ошибка соединения');
                ///Определяем id добавленной записи
                $id = mysql_insert_id();
                ///Создаем запрос на добавление в личную таблицу
                $query = "INSERT INTO $tablename (`id`,`shows`,`answer`,`studlevel`,`example`) VALUES". "('$id','$frequency','1','1',,);";
                ///Выполняем запрос
                $result = queryRun($query,'Ошибка соединения');                
            }
            //если слово найдено
            else{
                //устанавливае свойство "перевод"
                $row = mysql_fetch_array($result);
                $property_arr['native'] = $row[native];
                
                //создаем запрос на изменение значения поля "частота" в тезарусе
                $new_frecancy = $row['frecancy'] + $frecancy;
                $id = $row['id'];
                $query = "UPDATE thesaurus SET `frecancy` = $frecancy WHERE `id` = $id;";
                $result = queryRun($query,'Ошибка при добавлении frecancy в thesaurus');
                
                
                
                
            }
            //создаем запрос на получение данных из личной таблицы
                $query = "SELECT * FROM $table_name WHERE `id` = $id;";
                $resFromPersonal = queryRun($query,'Ошибка при получении данных из персональной таблицы');
                //создаем запрос на изменение данных в личной таблице
                $rowPublic = mysql_fetch_array($resFromPersonal);
                $shows = $resFromPersonal['shows'] + $frecancy;
                $answers = $resFromPersonal['answers'];
                $studyingLevel = $answers/$shows;
                
                
                $query = "UPDATE $table_name SET `shows` = $shows, `studyingLevel` = $studyingLevel, `lastData` = NOW()  WHERE `id` = $id;";
                $result = queryRun($query,'Ошибка при обновлении личной таблицы');
                
            $fromThesaurus = mysql_fetch_array($result);
            

            

        }
        $this->id = $property_arr['id'];
        $this->foreign = $property_arr['foreign'];
        if(isset($property_arr['native'])) $this->native = $property_arr['native'];
        $this->frequency = $property_arr['frequency'];
        
    }

}
