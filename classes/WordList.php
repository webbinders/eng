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
class StudList{
    var $studList;
    var $repeteStudListID;//массив содержащий id слов, входящих в список для изучения  на следующий раз
    /*
     * Параметр $countNewWords представляет собой число новых слов добавляемых в список для изучения
     */
    function  __construct($countNewWords){
        //выбираем список id из таблицы пользователей в массив id  для изучения
        $tableName = 'u'.$_SESSION['user_id'];
        $user_id = $_SESSION['user_id'];
        //строим новую составляющую списка для изучения
        
        $strNewStudList = $this->getNewStudList($user_id, $countNewWords);
        
        //считываем старую составляющую списка для изучения            
        $strOldStudList = $this->getOldStudList($user_id);
        if ($strOldStudList != '' && $strNewStudList != ''){
            
            
            $strOldStudList = trim($strOldStudList);
            
             $strList = $strOldStudList.','.$strNewStudList;
            
        }
        elseif ($strNewStudList != '') {//значит пустая старая составляющая
            
            $strList = $strNewStudList;
        }
        else{//значит пустая новая составляющая
            $strList = $strOldStudList;
        }
        if($strList !=''){
             $query = "UPDATE users SET `studList` = '$strList' WHERE id = $user_id;";

            $result = queryRun($query, "ошибка при обновлении списка для изучения (таблица users");

            $query = "UPDATE $tableName SET stud = '1' WHERE id IN ($strList);";
            //echo $query;

            $result = queryRun($query, "ошибка  при обновлении списка для изучения (таблица $tableName)");

            //Создаем запрос на выборку всех слов списка для изучения
            $query = "SELECT $tableName.*,thesaurus.* FROM $tableName INNER JOIN thesaurus ON  $tableName.id = thesaurus.id AND $tableName.id IN ($strList) ;" ;

            $result = queryRun($query, "ошибка 333 при составлении списка для изучения");

                while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                    //var_dump($row);
                    $word = new Word($row);
                    $this->addWord($word);
                }           

               /* $this->studListID = $strList;
            echo'studListID in class';
                var_dump($this->studListID);   
                echo '-------------end studListID --------------';*/
            /* echo '--------constructor-----------<br>';
            
             var_dump($this->studList);
             echo '---------------------<br>';*/
            //формируем массив id слов, которые надо будет повторить в следующий раз
            //Изначально это весь список для изучения
            foreach ($this->studList as  $value) {
                $this->repeteStudListID[$value->id] = $value->id;
            }
             //var_dump($this->repeteStudListID);
            //echo '--------end constructor-------------<br>';
        }
       
    }
    function getNewStudList($user_id, $count){
        $strNewStudList = '';
        $remainingWords = $count;
        $step = 0.01;
        $tableName = 'u'.$user_id;
        for($level = 0; $level <= 1 ; $level = $level + $step){
            $top = $level + $step ;
            //создаем запрос на определение заданного количества записей с наименьшим уровнем освоения
            $query = "SELECT * FROM $tableName WHERE stud = 0 AND level BETWEEN $level  AND $top  LIMIT 0, $remainingWords";        
            //выполняем запрос
            //echo $query.'<br>';
            $result = queryRun($query, "Сбой  при чтении из личной таблицы при составлении списка для изучения");
            //echo 'mysql_num_rows($result)'.mysql_num_rows($result).'<br>';
            if (mysql_num_rows($result)){
                //создаем элементы массива с id полученных записей в качестве ключа
                while ($word = mysql_fetch_assoc($result)){
                    $studList[$word['id']] = $word['id'];   
                } 
                /*/устанавливаем для каждого элемента массива значение равное его ключу 
                foreach ($studList as $key => $value) {
                    $studList[$key] = $key;
                } */
                $sizeStudList = sizeof($studList);
                if( $sizeStudList < $count){
                    $remainingWords = $count - $sizeStudList;
                }
                else {
                    $strNewStudList = implode(',', $studList);//строка содержащая id изучаемых слов разделенные запятыми
                    //удаляем первую запятую(implode че-то вставляет разделитель в начало строки)
                    $strNewStudList = trim(($strNewStudList)) ;
                    break;
                }
            }
        }  
        //echo $strNewStudList;
        return $strNewStudList;
    }
    /*
     * Возвращает массив id слов, которые на данный момент находятся в списке для изучения
     * Список id слов у которых stud = 1 дублируется в таблице users в виде строки содержащей id разделенные запятыми 
     */
    function getOldStudList($user_id){
        $query = "SELECT studList FROM users WHERE id = $user_id;";
        $result = queryRun($query, "error in time reading table users");
        $strOldStudList = mysql_result($result,0,0);
             
        return $strOldStudList;
    }
    
     /*
     принимает в качестве параметра объект-слово и добавляет его в словарь текста (массив слов)
     */
    function addWord($word){
        
        //если слово не принадлежит списку слов
        if(!isset($studList[$word->foreign])){            
            $this->studList[$word->foreign] = $word;
        }
        
    }
    /*
     * Перемещает слово в конец списка для изучения
     */
    function toBack($word){
         if (isset($this->studList[$word->foreign])){ 
            
            unset ($this->studList[$word->foreign]);
         }
         $this->addWord($word);
    }


    /*
     * удаляет элемент-слово из массива
     * параметр $word - 
     */
    function delWord($word){
        $id = $word->id;//id - удаляемого  из списка для изучения слова
        
        //echo"id - $id<br>";
        /*echo 'studListID in delword<br>';
        var_dump($this->studListID);
        echo 'end studListID in delword<br>';*/
        
        //если слово находится в списке, то удаляем его
        if (isset($this->studList[$word->foreign])){ 
            
            unset ($this->studList[$word->foreign]);
            
            
            /*/устанавливаем для каждого элемента массива значение равное его ключу             
            foreach ($this->studList as  $value) {
                $studList[$value->id] = $value->id;
            }   */
            if(sizeof($this->repeteStudListID)>0){
                $strNewStudList = implode(',', $this->repeteStudListID);//строка содержащая id изучаемых слов разделенные запятыми
            }
            else{
                $strNewStudList = '';
            }
            
            //перезаписываем $strNewStudList в таблицу пользователей для текущего пльзователя
            
            $user_id = $_SESSION['user_id'];
            $query = "UPDATE `users` "
                    . "SET `studList`= '$strNewStudList' "
                    . "WHERE `id`= $user_id;";
           // echo '<br>+++++++++++'.$query.'+++++++++++<br>';
            $result = queryRun($query, "Erroor  update users table in time delete word");
            
            //устанавливаем stud=0 в личной таблице пользователя
            $tableName = 'u'.$_SESSION['user_id'];
            
            
            $query = "UPDATE $tableName "
                    . "SET `stud`= $word->stud "
                    . "WHERE `id`= $id;";
            //echo '<br>'.$query.'<br>';
            $result = queryRun($query, "Erroor  update $tableName table in time delete word from studList");
        }

    }
    

}

class WordList{
    var $wordsList;
    var $wordFrequencyMap; /*карта частот слов списка 
     * (список слов строится из текста. Каждое слово имеет свою частоту. 
     * Частоту слова надо знать для определения количества ответов, если пользователь не смотрел ответ
     */
    var $idFrequencyMap;//массив id слов входящих в список
    
    /*
     * param $$text представляет собой исходный тест, из которого надо создать список слов
     */
     function  __construct($text){
         
        //разбиваем текст на слова и помещаем их в массив-карту частот
        $word_map = $this->splitText($text);
        
        $this->wordFrequencyMap = $word_map;


        //вносим слова в БД с одновременным собиранием их id в массив
        $this->idFrequencyMap = $this->toThesaurus($word_map);

        if (count($this->idFrequencyMap)>0){
            $this->toPersonalTab($this->idFrequencyMap);

            //создаем объект словаря по id-шникам слов
            //для этого для каждого id создаем объект-слово
            //и добавляем его в словарь текста            

            //формируем один запрос для выборки сразу всех слов текста из БД
            $queryToThesaurus = "SELECT * FROM thesaurus WHERE id IN (";
            $midlePartQuery = '';
            foreach($this->idFrequencyMap as $id => $frequency){
                $midlePartQuery .= "'$id',";

            }
            //удаляем последнюю запятую
            $midlePartQuery =  substr( $midlePartQuery, 0, -1);
            $queryToThesaurus .= $midlePartQuery .");";


            $resFromThesaurus = queryRun($queryToThesaurus, "WordList Ошибка соединения: ");


            while ($row = mysql_fetch_array($resFromThesaurus,MYSQL_ASSOC)) {


                $word = new Word($row);

                $this->addWord($word);
            }
            $n=0;
        }
        
        
        /*
        echo '<br>WordList<br>';
        var_dump($this);
        echo '<br>end constructor WordList <br>';*/
            
        
     }
     



    /*
     * Разбивает текст на слова и подсчитывает частоту слов в тексте
     * Возвращает массив $word_map[$word]=$frequency
     */
    function splitText($text){
        //заменяем альтернативные апострофы на правильный
        $text=preg_replace('/\’/','\'',$text);
        
        $text=preg_replace('/\“/','"',$text);
        $text=preg_replace('/\”/','"',$text);
        //удаляем все небуквенные символы кроме апострофа
        
        
        // Заменяем кирилические символы пробелами
        $text=preg_replace('/[а-яА-ЯёЁ"]/','',$text);
         //разбиваем текст на слова и помещаем их в массив $words_arr
        $text=preg_replace('/(^\s*\')|(\s\')|(\'\W)|(\'\s)|\d+|[^(\w\’\')]|(\'*$)|[^\'\w]/',',',$text);
        $words_arr = explode(',',$text);
        
        //для каждого слова подсчитывем его частоту в тексте
        //т.е. создаем массив $word_map[$word]=частота
        //и при этом все слова переводим в верхний регистр
        $word_map = array();
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
        $word_id = array();
        foreach($word_map as $foreign => $frequency){
            
            //Добавляем слово в тезарус
            $query = "INSERT INTO thesaurus(`foreign`,`frequency`) VALUES". "('".  addslashes($foreign)."','$frequency')" . " on duplicate key update  frequency = frequency + values(frequency);";
            
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
        //если массив $word_id непустой
        if (count($word_id)>0){
            //Определяем имя личной таблицы для запроса на добавление в нее записи
            $table_name = 'u'.$_SESSION['user_id'];

            //Формируем единичный запрос для добавление сразу всего массива данных
            $query = "INSERT INTO `$table_name` (`id`,`shows`,`answers`,`level`) VALUES ";
            $words_arr = array();
            foreach($word_id as $id => $frequency){
            $query .= "($id,$frequency,0,0),";
            $words_arr[$id]=$id;
            }
            $query = substr( $query, 0, -1);//убираем последнюю запятую
            $query .= " on duplicate key update  shows = shows + values(shows), answers = answers + values(answers), level = answers/shows ;";
            //echo '<br>'.$query.'<br>';

            //Выполняем запрос
            $result = queryRun($query,'<br>error toPersonalTab');
        }



    }
    /*
     принимает в качестве параметра объект-слово и добавляет его в словарь текста (массив слов)
     */
    function addWord($word){
        //если слово не принадлежит списку слов
        if(!isset($wordsList[$word])){
            $this->wordsList[$word->foreign] = $word;
        }

        
        
    }


}
class Word{
    var $id;
    var $foreign;
    var $native;
    var $frequency;//частота слова взятая из тезаруса (примерная общая частотность использования слова)
    var $shows;//показы слова конкретному пользователю, который запустил этот скрипт
    var $answers;
    var $lastData;
    var $stud;
    var $level;//уровень освоения слова
    var $examples;//список id примеров
    var $transcription;
    var $theme;
    function setForeign($string){$this->foreign = $string;}
    function setNative($string){$this->native = $string;}
    function setFrequency($var){$this->frequency = $var;}
    
   
    /*
     * Функция создания массива объектов-слов в соответствии с переданным массивом id слов
     */
    private function buildArrWords($arrId){
        $exampleList =  array();
        if(sizeof($arrId)){
            
            //Для каждого элемента массива содержащего id  примеров
            foreach ($arrId as $valueId) {
                //Создаем объект exampleObj класса Word
                $property_arr['id'] = $valueId;
                $exampleObj = new Word($property_arr);
                //Добавить его в список примеров
                $exampleList[$valueId]=$exampleObj;
            }
            return $exampleList;
        }       
    }
     /*
     * Найти массив id примеров в которых используются слова текста $text
     */
     function findExamplesId($textAsWord){
        $resArr = array();
        $wordList = new WordList($textAsWord->foreign);

        if(sizeof($wordList->wordsList) > 0){
            $word = reset($wordList->wordsList);
            if(strlen($word->examples))
                $resArr = explode(',', $word->examples) ;
            foreach ($wordList->wordsList as $key => $word) {
                //находим схождение или пересечение результирующего массива и текущего
                $currentArr = explode(',', $word->examples) ;
                $resArr = array_intersect($resArr, $currentArr);
                if(sizeof($resArr)==0) 
                    break;//дальше можно не продолжать одно из слов не используется совместно с рассмотренными словами
            }
            //удаляем из списка примеров id текущего слова, т.к. метод findExamples включает и само слово в спимок примеров
            if ($key = array_search($textAsWord->id, $resArr)) { 
                unset($resArr[$key]);
            }
        }
        return $resArr;
    }


    /*
     * Найти примеры использования слова или нескольких слов
     * 
     */
    function findExamples($textAsWord){
        //echo $text;
        $arrId = $this->findExamplesId($textAsWord);
        $exampleList = $this->buildArrWords($arrId); 
        return $exampleList;
    }
    
    /*
     * Добавляет (обновляет) перевод 
     */
    function updateNative($translation){
        $this->native = $translation;
        $translation = htmlentities($translation, ENT_QUOTES);
        $query = "UPDATE `thesaurus` SET `native`= '$translation' WHERE `id` = $this->id; ";
        //echo $query;
        $result = queryRun($query,"Ошибка обновления таблицы 'thesaurus' во время выполнения метода updateNative()"); 
        
    }
    /*
     * Увеличивает число показов слова на 1 и устанавливает текущую дату
     */
    function addShow() {
        $table_name = 'u'.$_SESSION['user_id'];
        $this->shows++;
        $query = "UPDATE $table_name SET `shows`=$this->shows,`level`=$this->answers/$this->shows,`studDate`= NOW() WHERE `id` = $this->id;";
        $result = queryRun($query,"Ошибка обновления таблицы $table_name во время выполнения метода addShow()"); 
    }
    /*
     * Добавляет слово в список для изучения
     */
    function addToStudList(){
        
        {
            $table_name = 'u'.$_SESSION['user_id'];
            $this->shows++;
            $level=$this->answers/$this->shows;
            $this->stud =1;
            //создать запрос для установки stud =1 в личной таблице
            $query = "UPDATE $table_name SET `shows`=$this->shows,`level`= $level,`studDate`= NOW(), `stud`= 1 WHERE `id` = $this->id;";
            $result = queryRun($query,"Ошибка обновления таблицы $table_name во время выполнения метода addToStudList()"); 
            //считываем строку содержащую список для изучения из таблицы users для текущего пользователя
            $user_id = $_SESSION['user_id'];
            $query = "SELECT `studList` FROM `users` WHERE `id`= $user_id;"; 
            $result = queryRun($query,"Ошибка чтения таблицы `users`во время выполнения метода addToStudList()");
            
            $arrId=array();
            $row = mysql_fetch_row($result);
            $strId = $row[0];
            if ($strId!=''){/*без этой проверки в начале строки образуется запятая. 
             * Т.е мы не делаем элемент массива содержащий пустую строку, 
             * после которого потом вставится запятая и в итоге мы получим запятую а начале строки*/
                $arrId = explode(',', $strId);
            }
                
            

            if (!in_array($this->id, $arrId)){//только если слово не входит в список для изучения (страхуемся от дубликатов)
                $arrId[] = $this->id;
                $strId = implode(",", $arrId);
                $query ="UPDATE `users` SET `studList`='$strId' WHERE `id`= $user_id";
                $result = queryRun($query,"Ошибка обновления таблицы `users` во время выполнения метода addToStudList()"); 
            }
            

            
        }
        return $this;
       
    }
            
    function  __construct($property_arr){
        
        //var_dump($property_arr);
        $table_name = 'u'.$_SESSION['user_id'];
        
        //Добавляем слово в тезарус, если его там еще нет
        if (isset($property_arr['id'])){
            $id = $this->id = $property_arr['id'];
            //то выборку свойств проводим по id
            //составляем запрос на поиск слова тезарусе по id
            $query = "SELECT * FROM thesaurus  WHERE id = '$id'";
            //$query = "SELECT thesaurus.*, $table_name.*  FROM thesaurus, $table_name WHERE thesaurus.id = '$id' AND $table_name.id ='$id';";
            //echo $query;
            //Выполняем запрос
            $result = queryRun($query,'Ошибка соединения в конструкторе класса Word'); 
            
            //если запись не найдена сообщаем о проблеме
            if (mysql_num_rows($result) == 0){
                //echo 'error';
                die(' id is empty :'.$id . mysql_error());
            }
            
            $row = mysql_fetch_array($result);
            //в соответствии с переданными параметрами устанавливаем свойства слова
            $this->foreign = html_entity_decode($row['foreign']);
            $this->frequency = $row['frequency'];
            $this->examples = $row['examples'];
           
            if (isset($property_arr['native'])){
                $this->native = html_entity_decode($row['native']).'\n'.$property_arr['native'];
            }
            else {
                $this->native = html_entity_decode($row['native']);                
            }
            
            //создаем запрос на считывание свойств слова из личной таблицы
            $query = "SELECT * FROM $table_name  WHERE id = '$id'";
            $result = queryRun($query,"Ошибка соединения в конструкторе класса Word при стении из таблицы $table_name");  
            //если слова еще нет в личной таблице
            if (mysql_num_rows($result) == 0){
                //добавляем слово в личную таблицу с установкой свойств объекта-слово по умолчанию
                $this->addToPersonTab($id, $table_name);
            }
            else{
                $row = mysql_fetch_array($result);
                $this->lastData = $row['studDate'];
                $this->level = $row['level'];
                $this->answers = $row['answers'];
                $this->shows = $row['shows'];
                //$this->examples = $row['examples'];
                $this->stud = $row['stud'];
            }
        }
        else{
            //то выборку производим по слову. Если его не находим, то добавляем
            $foreign = addslashes(html_entity_decode($property_arr['foreign']));
           /* echo '$foreign'.$foreign.'<br>';
            $foreign = $property_arr['foreign'];
            $foreign = addslashes($property_arr['foreign']);
            $foreign = htmlentities($foreign);*/
            //echo '$property_arr[foreign]'.$property_arr['foreign'].'<br>';
            $query = "SELECT * FROM thesaurus WHERE `foreign` = '$foreign'";
            //echo $query.'<br>';
            $result = queryRun($query,'error in constructor');

            //если слово не найдено
            if (mysql_num_rows($result) == 0){
                
                //то добавляем слово в бд
                
                ///Создаем запрос на добаление в тезарус
                $foreign = addslashes(html_entity_decode($property_arr['foreign']));
                //echo $foreign.'-------<br>';
                if (isset ($property_arr['native']) && $property_arr['native'] != ''){
                    //если был передан перевод
                    $native = addslashes($property_arr['native']);
                    $query = "INSERT INTO `thesaurus` (`foreign`, `native`, `frequency`) VALUES ". "('$foreign', '$native', 1);";
                }
                else{
                    //если введено только слово без перевода
                    $query = "INSERT INTO `thesaurus`(`foreign`, `frequency`) VALUES ". "('$foreign',1);";
                    
                }
                //echo $query;
                
                
                ///Выполняем запрос
                $result = queryRun($query,'Error in time inserting in conctructor Word');
                ///Определяем id добавленной записи
                $id = mysql_insert_id();
                //добавляем слово в личную таблицу с установкой свойств объекта-слово
                $this->addToPersonTab($id, $table_name);
            }
            //если слово найдено
            else{
                //устанавливаем свойство "перевод"
                $row = mysql_fetch_array($result);
                $this->native = $row['native'];
                //устанавливае свойство "frequency"
                $this->frequency = $row['frequency'];
                //устанавливаем свойство id
                $this->id = $row['id'];
                
                //создаем запрос на изменение значения поля "частота" в тезарусе
                $new_frequency = $row['frequency'] + 1;
                $id = $this->id;
                $query = "UPDATE thesaurus SET `frequency` = $new_frequency WHERE `id` = $id;";
                $result = queryRun($query,'Ошибка при добавлении frecancy в thesaurus');
                
                
                //создаем запрос на получение данных из личной таблицы
                $query = "SELECT * FROM $table_name WHERE `id` = $id;";
                $resFromPersonal = queryRun($query,'Ошибка при получении данных из персональной таблицы');
                //если в личной таблице еще нет этого слова
                if (mysql_num_rows($resFromPersonal) == 0){
                    //добавляем слово в личную таблицу с установкой свойств объекта-слово
                    $this->addToPersonTab($id, $table_name);
                }
                //если слово уже есть в личной таблице
                else{
                    //создаем запрос на изменение данных в личной таблице
                    $rowPersonal = mysql_fetch_array($resFromPersonal);
                    $shows = $this->shows  = $rowPersonal['shows'] + 1;
                    $answers = $this->answers = $rowPersonal['answers'];
                    $studyingLevel = $this->level = $answers/$shows;

                    //создаем запрос на изменение данных в личной таблице
                    $query = "UPDATE $table_name SET `shows` = $shows, `level` = $studyingLevel, `studDate` = NOW()  WHERE `id` = $id;";
                    $result = queryRun($query,'Ошибка при обновлении личной таблицы');
                }
                
                
                
            }

            

            

        }
        //$this->id = $property_arr['id'];
        if(isset($property_arr['foreign'])) $this->foreign = $property_arr['foreign'];
        if(isset($property_arr['native'])) $this->native = $property_arr['native'];

        if(isset($property_arr['frequency'])) $this->frequency = $property_arr['frequency'];
        
    }
    /*
     * Добавляет новое слово в персональную таблицу пользователя.
     * Функция принимает в качестве параметра id слова из тезаруса
     * Для нового слова устанавливается текущая дата, один показ, нуль ответов и уровень освоения 0
     */
    function addToPersonTab($id, $table_name){
                ///Создаем запрос на добавление в личную таблицу
                $query = "INSERT INTO $table_name (`id`,`shows`,`answers`,`level`, `studDate`) VALUES ". "('$id','1','0','0', NOW());";
                //echo $query.'<br>';
                ///Выполняем запрос
                $result = queryRun($query,"Ошибка при добавлении слова в  таблицу $table_name в методе addToPersonTab");  
                $this->id = $id;
                $this->shows = 1;
                $this->frequency = 1;
                $this->answers = 0;
                $this->level = 0;
                $this->examples = '';   
    }
    
    function getNative(){
        //если свойство native для слова установлено
        if ($this->native != ''){
            return $this->native;
        }
        else{
            //формируем запрос на поиск перевода в БД
            $word = $this->foreign;
            $query = "SELECT * FROM thesaurus WHERE `foreign` = '".  addslashes($word)."' ;";
            //echo $query;
            
            //выполняем запрос
            $result = queryRun($query,'function getNative Ошибка доступа к таблице thesaurus');
            if(mysql_num_rows($result)){
                $row = mysql_fetch_array($result);
                $this->native = $row['native'];
                
                return $this->native;
            }
            else{
                return NULL;
            }
        }
    }
    function getForeign(){
        return $this->foreign;
    }

}
