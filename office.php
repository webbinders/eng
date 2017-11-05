<?php
    session_start();
    //подгрузка словаря
    $word_arr= parse_ini_file($_SESSION['lang'].".ini");
    include './classes/pageClass.php'; //подключаем файл класса страницы
    include_once 'server_connect.php'; //соединяемся с сервером БД
    include './classes/WordList.php';
    include 'socbuttons.php';//подключаем файл скодами социальніх кнопок
    
    
    $CRITERION_OF_REPETITION = 10*60*60;//10 часов
         

        
    $content = '';
    if (!isset($_SESSION['mode'])) {
        $_SESSION['mode']='';
    }

    if (isset($_SESSION['msg'])){
        $content = $_SESSION['msg'] . $content;
    }
    if (!isset($_SESSION['access']) || $_SESSION['access'] == 0 ){//если пользователь не вошел
        $_SESSION['msg'] = $word_arr['invitation_to_enter'];

        //перенаправляем его на страницу входа
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/joining.php');

    }
    if (isset($_POST['btn_read']) || !isset($_SESSION['mode'])) $_SESSION['mode'] = 'mode_read';
    if (isset($_POST['btn_stud']) || isset($_POST['btn_start_stud'])){
        
        $_SESSION['mode'] = 'mode_stud';   
        //определяем количество слов в старом списке для изучения
        $query = "SELECT `studList` FROM `users` WHERE `id` = $_SESSION[user_id];";
        $result = queryRun($query, "Error in time reading from 'users'<br> $query <br>");    
        $strStudList = mysql_fetch_array($result);
        if(strlen($strStudList['studList']) ){
            $questionNumber = sizeof(explode(',',$strStudList['studList']));

           
        }
        else{
            $questionNumber =0;
        }
         $questionNumberMsg = "Уже в списке для изучения на сегодня имеется слов : $questionNumber <br>"
                    . "Добавьте к ним некоторое количество новых вопросов<br>";

        
        
    }
        
    
    /*include_once 'forms/menu_form.php';//подключаем файл формы меню
    $content .= $menu_form -> toString();*/
    
        
        
switch ($_SESSION['mode']) {
    
    //----------------------
    case 'mode_read':
    //---------------------
        if (isset($_SESSION['dictionary'])){
            $dictionary = unserialize( $_SESSION['dictionary']);
            /*
echo'<br> --- '.$_SESSION['dictionary'].' ---<br>';
echo'<br> --- dictionary---<br>';
var_dump($dictionary);echo '<br>';*/
        }


        
        //нажата кнопка обработать текст
        if (isset($_POST['btn_handling_text']) && ($_POST['text_area'] != '')){
            //include 'handling_text.php';
            
                
                   $dictionary = new WordList($_POST['text_area']);
                   $_SESSION['dictionary'] = serialize ($dictionary);
                   //var_dump($dictionary);
        }
        //если нажата кнопка сбросить
        if (isset($_POST['btn_reset_text'])  && ($_POST['text_area'] != '')){
            $table_name = 'u'.$_SESSION['user_id'];
            //var_dump($dictionary);
               
            foreach ($dictionary->wordsList as $word) {
                //частота слова в тексте                
                $frequency=$dictionary->wordFrequencyMap[htmlentities($word->foreign)];
               
                //если пользователь не смотрел перевод слова, считается что он его знает
                if($word->stud ==0 ||( $word->stud == 1 && time() -$word->lastData > $CRITERION_OF_REPETITION)){
                    
                    //$word->shows++;
                }
                $word->answers+=$frequency;
                $answers = $word->answers;
                $shows = $word->shows;
                $level = $word->level = $answers / $shows;
                
                $stud = $word->stud;
                $studDate = $word->lastData;
                $id = $word->id;
                $query = "UPDATE $table_name SET `answers` = $answers, `level` = $level, `shows` = $shows, `stud` = $stud, `studDate` = '$studDate' WHERE `id` = $id;";
                
                 $result = queryRun($query,'Ошибка при обновлении личной таблицы ');
            }
            $_POST['text_area'] = '';
            unset($_SESSION['dictionary']);
            //var_dump($dictionary);
        }
        
        //нажата кнопка перевести
        if (isset($_POST['btn_find']) && ($_POST['word'] != '')){
            //include 'find_word.php';
            
            //получаем слово как объект из строки текстового поля
            @$word = getWordObjFromString($_POST['word'], $dictionary);
            //получаем перевод слова
            $translate = $word->getNative();
            //добавляем слово в список для изучения
            $studList = $word->addToStudList();
            //Вставляем перевод в тестовое поле
            $_POST['trans_area'] = $translate;  
            
            $wordList = new WordList($_POST['word']);
            
                            //Находим список примеров содержащих текущее слово                   
                $exampleList = $word->findExamples();  

                
                //Сериализируем список
                if (sizeof($exampleList)) $_SESSION['exampleList'] = serialize($exampleList);
            
            //Добавляем id примера в поле "Примеры" слов, входящих в пример
            if(sizeof($wordList->wordsList) > 1){
                foreach ($wordList->wordsList as $key => $subword) {
                    //из строки поля "примеры" создаем массив идентификаторов
                    ///создаем запрос на чтение поля "примеры"
                    $id = $subword->id;
                    $query = "SELECT `examples` FROM `thesaurus` WHERE id = $id";
                    $result = queryRun($query,'Ошибка при чтении поля examples');
                    ///получаем строку
                    $row = mysql_fetch_row($result);
                    $exampleStr = $row[0];
                    ///делаем из нее массив
                    $idArr = array();
                    if (strlen($exampleStr)>0){

                        $idArr = explode(',', $exampleStr);
                    }
                    //вставляем в этот массив идентификатор примера
                    if (!in_array($word->id, $idArr))
                    $idArr[$id]=$word->id;  
                    //преобразуем массив в строку
                    $exampleStr = implode(',', $idArr);
                    //обновляем тезарус
                    $query = "UPDATE `thesaurus` SET `examples`= '$exampleStr' WHERE `id` = $id;";
                   
                    $result = queryRun($query,'Ошибка при обновлении ( UPDATE ) поля examples');
                }

            }
            


        }
        //если нажата кнопка добавить перевод
        if (isset($_POST['btn_add']) && ($_POST['word'] != '') && ($_POST['trans_area'] != '')){
            $word = getWordObjFromString($_POST['word'], $dictionary);
            
            $word->updateNative($_POST['trans_area']);
            $_POST['word']='';
            unset($_SESSION['exampleList']);
        }
        include_once 'forms/reading_form.php';//подключаем файл формы для чтения
       /* if (isset($_POST['btn_add']) || isset($_POST['btn_handling_text'])){
            $my_form->delInputForm($trans_area);
            $my_form->delInputForm($btn_add);
            
        }*/
        if (isset($dictionary))
        $_SESSION['dictionary'] = serialize ($dictionary);
                
        $content .= $my_form ->toString();
        /*foreach ($dictionary->wordsList as $w){
            /*$ww = $dictionary->wordsList[$k]->
            $content .= $dictionary[$w].'<br>';
            $ww=$w->getForeign();
                    
            $content .= "<br>$ww";
        }*/
        //Нажата кнопка показать примеры

        break;
    //---------------------
    case 'mode_stud':
    //---------------------
        $button = "non_button";
        if(isset($_POST['btn_start_stud'])){
            //если нажата кнопка начать изучение
            //загружаем список для изучения
            if ($_POST['newQuestions']=='') $_POST['newQuestions']=0;
            if (preg_match('/\D/', $_POST['newQuestions'])) $_POST['newQuestions']=7;
            
            //определяем общее количество записей в личной таблице
            $query = "SELECT COUNT(*) FROM u".$_SESSION['user_id'].";";            
            $res = queryRun($query, "Error in time counting");
            $row = mysql_fetch_row($res);
            $records =$row[0];
            
            if($records < $_POST['newQuestions'] + $questionNumber){
                $content .= "<h1>Недостаточно записей в вашем словаре. Сначала зайдите в режим чтения и проработайте текст</h1>";
            }
            else{
                $studList = new StudList($_POST['newQuestions']); 
                //$button = 'btn_start_stud';
            }
            

            //
        }
        else{
            if (!isset($_POST['btn_stud'])){
                if (isset($_SESSION['studList'])){
                    $studList =  unserialize($_SESSION['studList']);
                }
                else{
                    $studList = new StudList(0);
                }
            
            }
        }
        if(isset($_POST['btn_ready'])){//если нажата кнопка "Готово"
            //
            $button = 'btn_ready';
            
            
        }
        if(isset($_POST['btn_right'])){
            $button = 'btn_right';
        }
        if(isset($_POST['btn_wrong'])){
            $button = 'btn_wrong';
        }
        if(isset($_POST['btn_view_example'])) {
            $button = 'btn_view_example';
        }
        if (isset($_POST['btn_show_native'])){
            $button = 'btn_show_native';
        }
        //если список для изучения не пуст
        if(isset($studList) && sizeof($studList->studList)) {
            testing($studList,$button);
        
            

            
       // $content .= var_dump($_POST);
        //if(isset($studList)) $content .= var_dump($studList);
        }
        else{// если список для изучения пуст
            if(strlen($strStudList['studList'])==0){//но при этом в таблице users за пользователем закреплен непустой список
                /*
                 * не понятно, как такая ситуация вообще может возникать?
                 * Если в таблице users за пользователем закреплен непустой список, то список должен быть не пустым.
                 */
                $content .= "<h1>Список для изучения пуст.</h1>"
                            ."<p>Чтобы начать процедуру изучения-повторения новых слов, введите число новых вопросов.<br>"
                            ."и нажмите кнопку [Начать изучение]</p>";
                unset($_POST['btn_start_stud']);
                unset($_POST['btn_ready']);
                unset($_POST['btn_wrong']);
                unset($_POST['btn_right']);
            }
            else{
                $content .= $questionNumberMsg;
            }


        }
        if ($_SESSION['mode']=='mode_stud' ){
            include_once 'forms/studing_form.php';//подключаем файл формы для изучения
            $content .= $stud_form -> toString();
        }
        else{
            
                //выводим сообщение, что изучение закончено
                $content .= '<h1>Learning is ended for today</h1>';
                $content = facebookButton($content);//добавляем социальные кнопки
                //уничтожаем переменную сессии содержащую список примеров, если он существует
                if (isset($_SESSION['exampleList'])) unset($_SESSION['exampleList']);
            }
            
        
        
 
    default:
        
        break;
}
        
        $pageObj = new pageClass($content);
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        //если пользователь вошел дать ему знать
        echo $pageObj->build_Page();
        
/*
 * процедура тестирования
 */
function testing($studList, $button){
   /* echo '(count($studList))'.count($studList->studList).'<br>';
    var_dump($studList);
    echo '++++++++++';*/
    //если список для изучения не пустой
    if  (count($studList->studList)){
        if (isset($_POST['question_text_area'])){
            if(correct_foreign($_POST['question_text_area']) != $_POST['question_text_area']){
                //если в БД необработанная строка, то свойство foreing не будет соответствовать correct_foreign($_POST['question_text_area'])
                //то берем свойство из текстового поля
                $question_text_area = correct_foreign($_POST['question_text_area']);
            }  else {
                
                $question_text_area = $_POST['question_text_area'];
            }
            
            
            $currentWord = $studList->studList[$question_text_area];
            /*echo '^^^^^^^^<br>';
            var_dump($currentWord);echo' '^^^^^^^^<br>';*/
        }else{
            $currentWord = reset($studList->studList);
            /*echo '*********<br>';
                        var_dump($currentWord);
                        echo '***********<br>';*/
        }
        
        switch ($button) {
            
            //--------------------------------------
            case 'btn_ready'://Если нажата "Готово"
            //---------------------------------------
                 //Заполняем текстовую область "Ответ" свойством native
                $native = $currentWord->getNative();
                $currentWord->shows++;
                if($native!=''){
                    $_POST['answer_text_area'] = $native;
                }
                
                //Находим список примеров содержащих текущее слово                   
                $exampleList = $currentWord->findExamples();  

                
                //Сериализируем список
                if (sizeof($exampleList)) $_SESSION['exampleList'] = serialize($exampleList);
                
             
                $_SESSION['currentWord'] = $currentWord->foreign;
                //echo '<br> examplesList :'.$currentWord->examples.'<br>';
                break;
                
            //---------------------------------------------   
            case 'btn_right'://нажата кнопка "Правильно"   |
            //---------------------------------------------
                //определяем временню метку текущего слова
                $dateСurrentWord = $currentWord->lastData;
                //определяем  временню метку слова
                $currentWordTimestamp = getTimestamp($dateСurrentWord);
                                
                $CRITERION_OF_REPETITION = 10*60*60;//10 часов
         
                //если дата не сегодняшняя
                $today =  time();
                if($today - $currentWordTimestamp > $CRITERION_OF_REPETITION){
                    //удаляем слово из списка для изучения в БД
                    $currentWord->stud = 0;
                    //echo '!!!!!!!!!!';
                    
                    
                    
                    //echo sizeof($studList->studList).'-------<br>';
                    //Усстанавливаем текущую дату в свойство объекта-слово
                    $currentWord->lastData = date("Y-m-d H:i:s",  time());
                    //var_dump($studList);
                    unset($studList->repeteStudListID[$currentWord->id]);
                    
                }
                //копируем значения текстовых областей в свойства
                $currentWord->foreign = correct_foreign($_POST['question_text_area']);  
                $currentWord->native = htmlentities($_POST['answer_text_area']); 
                //увеличиваем на 1 свойство answers
                $currentWord->answers++;
                
               
                //Обновляем запись для слова в БД
                //Личная таблица
                
                $tableName = 'u'.$_SESSION['user_id'];
                $query = "UPDATE $tableName SET "
                        . "`answers`=$currentWord->answers,"
                        . "`shows`=$currentWord->shows,"
                        . "`level`=$currentWord->answers/$currentWord->shows,"
                        . "`studDate` = NOW(),"
                        . "`stud`=$currentWord->stud "
                        . "WHERE `id`=$currentWord->id;";
                
                $result = queryRun($query,'Error in time update '.$tableName);
                //тезарус
                $foreign = addslashes($currentWord->foreign);
                $native = addslashes($currentWord->native);
                $query = "UPDATE `thesaurus` SET `foreign`='$foreign',`native`='$native' WHERE `id`=$currentWord->id";
                $result = queryRun($query,'Error in time update thesaurus');
                
                //удаляем слово из списка для изучения на сегодня
                $studList->delWord($currentWord);
                if(sizeof($studList->studList)>0){
                    $currentWord = reset($studList->studList);
                    $_POST['question_text_area']=$currentWord->foreign;
                    $_POST['answer_text_area']='';
                }
                else{
                    
                    $_SESSION['mode'] = 'mode_end_stud';
                }

                break;
            //-----------------------------------
            case 'btn_wrong'://нажата кнопка "Неправильно"
            //-------------------------------------
                //находим текущее слово по переменной сессии, хранящей ключ текущего слова
                if(!isset($_SESSION['currentWord'])) $currentWord = $studList->studList[$_SESSION['currentWord']];
                //если дата не сегодняшняя                
                //Устанавливаем текущую дату в свойство объекта-слово
                $currentWord->lastData = date("Y-m-d H:i:s",  time());
                //Увеличиваем на 1 свойство shows
                $currentWord->shows++;
                
                //копируем значения текстовых областей в свойства
                $currentWord->foreign = correct_foreign($_POST['question_text_area']);  
                $currentWord->native = htmlentities($_POST['answer_text_area']); 
                
                //Обновляем запись для слова в БД
                //Личная таблица
                
                $tableName = 'u'.$_SESSION['user_id'];
                $query = "UPDATE $tableName SET "
                        . "`answers`=$currentWord->answers,"
                        . "`shows`=$currentWord->shows,"
                        . "`level`=$currentWord->answers/$currentWord->shows,"
                        . "`studDate` = NOW(),"
                        . "`stud`=$currentWord->stud "
                        . "WHERE `id`=$currentWord->id;";
                
                $result = queryRun($query,'Error in time update '.$tableName);
                //тезарус
                $foreign = addslashes($currentWord->foreign);
                $native = addslashes($currentWord->native);
                $query = "UPDATE `thesaurus` SET `foreign`='$foreign',`native`='$native' WHERE `id`=$currentWord->id";
                //echo $query;
                $result = queryRun($query,'Error in time update thesaurus');
                
/*echo 'previos<br>';
                $prevWord = prev($studList->studList);
                var_dump($prevWord);
                echo 'current<br>';
                
                var_dump($currentWord);
                echo 'next<br>';
                $nextWord = each($studList->studList);
                var_dump($nextWord);
                echo '==============<br>';*/
               // var_dump($studList->studList);
               
                //переносим текущее слово в конец списка
                $studList->toBack($currentWord);
                
                //переходим к следующему слову
                $currentWord = reset($studList->studList);
                

                /* echo '<<<<<<<<<<<<br>';
                    var_dump($currentWord);
                    echo '<<<<<<<<<<<<br>';*/
                $_SESSION['currentWord'] = $currentWord->foreign;
                $_POST['question_text_area']=$currentWord->foreign;
                $_POST['answer_text_area']='';
                break;
            //-----------------------------------
            case 'btn_view_example'://Если нажата "Показать примеры"
            //-----------------------------------
                //копируем значения текстовых областей в свойства
                //$currentWord->foreign = $_POST['question_text_area'];  
                //$currentWord->native = $_POST['answer_text_area']; 
                
                //Саздать запрос на поиск примеров содержащих текущее слово
               /* $foreign = trim(strtoupper(addslashes($_POST['question_text_area'])));
                //echo $foreign;
                $query = "SELECT `examples` FROM `thesaurus` WHERE `foreign`= '$foreign';";
                $result = queryRun($query,'Error in time read  thesaurus');
                $row = mysql_fetch_array($result);*/
                
                /*/Если строка содержащая список примеров не пустая
                if(strlen($currentWord->examples)){
                    //Превращаем ее в массив содержащий id примеров                    
                    $idArr = explode(',', $currentWord->examples);
                    //Для каждого элемента массива содержащего id  примеров
                    foreach ($idArr as $valueId) {
                        //Создаем объект exampleObj класса Word
                        $property_arr['id'] = $valueId;
                        $exampleObj = new Word($property_arr);
                        //Добавить его в список примеров
                        $exampleList[$valueId]=$exampleObj;
                    }
                    //Сериализируем список
                    $exampleList = serialize($exampleList);
                    $_SESSION['exampleList'] = $exampleList;
                }
                 * 
                 */
                

                
                break;
            case 'btn_show_native': //Если была нажата "Показать перевод примера"
                
                //получаем из переменной список примеров
                 $exampleList = unserialize($_SESSION['exampleList']);      
                //определяем нажатую кнопку (пример с ее идентификатором надо добавить в список для изучения                
                $btnId = key($_POST['btn_show_native']);
                //Создаем объект exampleObj класса Word
                $property_arr['id'] = $btnId;
                $exampleObj = new Word($property_arr);
                //добавляем пример в список для изучения
                $exampleObj->addToStudList();
                
               /* if (isset($_POST['exampleList'])){
                    var_dump($_POST['exampleList']);
                $_POST['exampleList'] = unserialize($_POST['exampleList']);
                $_POST['exampleList'] = serialize($_POST['exampleList']);  
                }
                 
                if (isset($_POST['shown'])){
                    
                    $shownExample = unserialize ($_POST['shown']);
                    print_r($shownExample);
                }*/
                //$_POST['shown'] = serialize($shownExample);
                //создаем
                break;
                
                case 'btn_start_stud':
                
                break;

            default:
                /*/если не нажата ни одна из кнопок, значит процедура запускается впервые
                foreach ($studList->studList as  $value) {
                    $wordsArr[]=$value;
                }
                //echo '$keyArr<br>';
                //var_dump($keyArr);
                $currentWordIndex = 0;
                $currentWord = $wordsArr[$currentWordIndex]; */
                //заполняем текстовую область "Вопрос" свойством foreign
                $_POST['question_text_area'] = $currentWord->getForeign();
                //$_POST['question_text_area'] = $studList->studList[0]->getForeign();
                //Передаем список для изучения как переменную сессии
                $_SESSION['studList'] = serialize($studList);
                break;
        }
        //Передаем список для изучения как переменную сессии
        $_SESSION['studList'] = serialize($studList);
        
        
       
    }
    else{
        echo 'Testing is ended';
    }
}
/*
 * Преобразует строку вида '2016-12-16 22:45:53'  в метку времени
 */
function getTimestamp($strDate) {
//echo $strDate;
    $dateAndTime = explode(' ', $strDate);
    $dateArr = explode('-', $dateAndTime[0]);
    $timeArr = explode(':', $dateAndTime[1]);
    return mktime($timeArr[0], $timeArr[1], $timeArr[2], $dateArr[1], $dateArr[2], $dateArr[0]);
}
/*
 * Функция получения слова-объекта из текстового поля (из текстовой строки).
 * Если слова нет в текущем списке слов-объектов, получаем его из БД. При этом если слова нет в БД, то создаем его в БД
 */
function getWordObjFromString($str,$dictionary){
    $str = trim($str);
    $foreign = strtoupper($str);
    if (isset($dictionary->wordsList[$foreign])){
        $word = $dictionary->wordsList[$foreign];
    }
    else
        {//если слово взято не из текста (нет в списке слов)
        //echo preg_match('/[а-яА-ЯёЁ]+/u', $str);
        //не допускаем кириллических символов в поле для ввода иностранного слова        
        if (!preg_match('/[а-яА-ЯёЁ]+/u', $str)){//если кирилических символов нет
            //устанавливаем единственное известное свойство слова в массив свойств слова для создания объекта "слово"
            
            $property_arr['foreign'] = correct_foreign($str);
            //var_dump($property_arr);
            //echo $property_arr['foreign'];
            //создаем объект слово
            $word = new Word ($property_arr);
            //var_dump($word);
        }




    }
    //var_dump($word);
    return $word;    
}
/*
 * Предназначена для восстановления кавычек в строке, после ее преобразования к верхнему регистру.
 * Применение функций htmlentities или addslashes к строке вида 'Humans were created “in God’s image.”' ведет к непредсказуемым результататм.
 * Такую строку надо обработать функцией htmlentities. В результате кавычки в ней преобразуются в последовательность символов вида  &ldquo;
 * А вся строка вида 'Humans were created “in God’s image.”' примет вид Humans were created &ldquo;in God&rsquo;s image.&rdquo; * 
 *  Теперь ее можно привести к верхнему регистру. Но последовательность сиволов обозначающие кавычки надо вернуть в нижний регистр
 */
function qou($str){
    $start = 0;
    $array_chang = array();
    while(preg_match('/(&\w{2,5};)/', $str, $matches,PREG_OFFSET_CAPTURE,$start)){
        //var_dump($matches);
        $pos_apersand = $matches[1][1];
        $lenentitie = strlen($matches[1][0]);
                
        //echo 'pos_apersand ='.$pos_apersand.'<br>';
        $array_chang[$pos_apersand]= strtolower(substr($str, $pos_apersand,$lenentitie)) ;
        $start =$pos_apersand+6;
        //str2=substr($str, $pos_apersand+7);
        
        
       
    }
    //var_dump($array_chang);
    
    foreach ($array_chang as $pos_apersand => $substr) {
        
        $str=substr_replace($str, $substr, $pos_apersand, strlen($substr));
        
    }
    return $str;
}

/*
 * преобразует строку к верхнему регистру
 * и заменяет кавычки и теги их строковыми эквивалентами
 */
function quo($str) {
    return strtoupper(htmlentities($str));
}
/*
 * из содержимого тестового поля создает строку пригодную для использования в качестве
 * свойсива foreign слова (переводит в верхний регистр, небуквенные символы ппеобразует
 * в сироку символов, 
 */
function correct_foreign($str) {
    $str = quo($str);
    $str = qou($str);
    return $str;
}
?>
