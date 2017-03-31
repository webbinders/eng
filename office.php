<?php
    session_start();
    //подгрузка словаря
    $word_arr= parse_ini_file($_SESSION['lang'].".ini");
    include './classes/pageClass.php'; //подключаем файл класса страницы
    include_once 'server_connect.php'; //соединяемся с сервером БД
    include './classes/WordList.php';
    
         

        
    $content = 'выводим код офиса';

    if (isset($_SESSION['msg'])){
        $content = $_SESSION['msg'] . $content;
    }
    if (!isset($_SESSION['access']) || $_SESSION['access'] == 0 ){//если пользователь не вошел
        $_SESSION['msg'] = $word_arr['invitation_to_enter'];

        //перенаправляем его на страницу входа
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/joining.php');

    }
    if (isset($_POST['btn_read']) || !isset($_SESSION['mode'])) $_SESSION['mode'] = 'mode_read';
    if (isset($_POST['btn_stud'])){
        
        $_SESSION['mode'] = 'mode_stud';   
        //определяем количество слов в старом списке для изучения
        $query = "SELECT `studList` FROM `users` WHERE `id` = $_SESSION[user_id];";
        $result = queryRun($query, "Error in time reading from 'users'<br> $query <br>");    
        $strStudList = mysql_fetch_array($result);
        if($strStudList['studList']){
            $questionNumber = sizeof(explode(',',$strStudList['studList']));

            $questionNumberMsg = "Уже в списке для изучения на сегодня имеется слов : $questionNumber <br>"
                    . "Добавте к ним некоторое количество новых вопросов<br>";
        }

        
        
    }
        
    
    include_once 'forms/menu_form.php';//подключаем файл формы меню
    $content .= $menu_form -> toString();
    if($_SESSION['mode'] == 'mode_stud'){
        

    }
    
    //echo '$_SESSION'."['mode'] = ". $_SESSION['mode'];
    //var_dump($btn_read);
    //var_dump($btn_stud);
        
        
switch ($_SESSION['mode']) {
    //----------------------
    case 'mode_read':
    //---------------------
        
        //echo 'mode_start';
        //нажата кнопка обработать текст
        if (isset($_POST['btn_handling_text']) && ($_POST['text_area'] != '')){
            //include 'handling_text.php';
            
                
                   $dictionary = new WordList($_POST['text_area']);
                   //var_dump($dictionary);
        }
        //нажата кнопка перевести
        if (isset($_POST['btn_find']) && ($_POST['word'] != '')){
            //include 'find_word.php';
            //если слово содержится в словаре теста
            if (isset($dictionary->wordsList[$_POST['word']])){
                $translate = $dictionary->wordsList[$_POST['word']]-> getNative();
            }  
            else {
                $property_arr['foreign'] = $_POST['word'];
                
                $word = new Word ($property_arr);
                //var_dump($word);
                $translate = $word->getNative();
                if (trim($translate)  == '') {
                    $_POST['trans_area'] = " Перевод не найден\nВы можете ввести сюда свой перевод";
                    //добавляем кнопку "Сохранить перевод"
                    echo $_POST['trans_area'].'+++++++';
                }
                   
                
            }
            
             
        }
        include_once 'forms/reading_form.php';//подключаем файл формы для чтения
        $content .= $my_form ->toString();
        /*foreach ($dictionary->wordsList as $w){
            /*$ww = $dictionary->wordsList[$k]->
            $content .= $dictionary[$w].'<br>';
            $ww=$w->getForeign();
                    
            $content .= "<br>$ww";
        }*/

        break;
    //---------------------
    case 'mode_stud':
    //---------------------
        
        if(isset($_POST['btn_start_stud'])){
            //если нажата кнопка начать изучение
            //загружаем список для изучения
            $studList = new StudList($_POST['newQuestions']); 

            $button = 'btn_start_stud';
            //
        }
        else{
            if (!isset($_POST['btn_stud']))
            $studList =  unserialize($_SESSION['studList']);
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
        //если список для изучения не пуст
        if(isset($studList) && $studList->studList) {
            testing($studList,$button);
        
            

            
       // $content .= var_dump($_POST);
        //if(isset($studList)) $content .= var_dump($studList);
        }
        else{// если список для изучения пуст
            if(!$strStudList['studList']){//и при этом в таблице users у пользователя пустой список
                $content .= "<h1>Список для изучения пуст.</h1>"
                            ."<p>Чтобы начать поцедуру изучения-повторения новых слов введите число новых вопросов.<br>"
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
            include_once 'forms/studing_form.php';//подключаем файл формы для изучения
            $content .= $stud_form -> toString();
        break;

    default:
        break;
}
/*
 * процедура тестирования
 */
function testing($studList, $button){
    echo '(count($studList))'.count($studList->studList).'<br>';
    var_dump($studList);
    
    //если список для изучения не пустой
    if  (count($studList->studList)){
        $currentWord = current($studList->studList);
        switch ($button) {
            
            //Если нажата "Готово"
            case 'btn_ready':
                 //Заполняем текстовую область "Ответ" свойством native
                $native = $currentWord->getNative();
                $currentWord->shows++;
                if($native!=''){
                    $_POST['answer_text_area'] = $native;
                }
                else{
                    $_POST['answer_text_area'] = "Перевод отсутствует, введите перевод.";
                }

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
                    
                    
                    
                    echo sizeof($studList->studList);
                    //Усстанавливаем текущую дату в свойство объекта-слово
                    $currentWord->lastData = date("Y-m-d H:i:s",  time());
                    var_dump($studList);
                }
                //копируем значения текстовых областей в свойства
                $currentWord->foreign = $_POST['question_text_area'];  
                $currentWord->native = $_POST['answer_text_area']; 
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
                        . "`examples`='$currentWord->examples',"
                        . "`stud`=$currentWord->stud "
                        . "WHERE `id`=$currentWord->id;";
                
                $result = queryRun($query,'Error in time update '.$tableName);
                //тезарус
                $query = "UPDATE `thesaurus` SET `foreign`='$currentWord->foreign',`native`='$currentWord->native' WHERE `id`=$currentWord->id";
                $result = queryRun($query,'Error in time update thesaurus');
                
                //удаляем слово из списка для изучения на сегодня
                $studList->delWord($currentWord);
                if($studList->studList){
                    $currentWord = reset($studList->studList);
                    $_POST['question_text_area']=$currentWord->foreign;
                    $_POST['answer_text_area']='';
                }
                else{
                    echo 'Learning is ended for today';
                    $_SESSION['mode'] = 'mode_read';
                }

                break;
            case 'btn_wrong'://нажата кнопка "Неправильно"
                //если дата не сегодняшняя
                //Усстанавливаем текущую дату в свойство объекта-слово
                
                
                break;

            default:
                //если не нажата ни одна из кнопок, значит процедура запускается впервые
                foreach ($studList->studList as  $value) {
                    $wordsArr[]=$value;
                }
                //echo '$keyArr<br>';
                //var_dump($keyArr);
                $currentWordIndex = 0;
                $currentWord = $wordsArr[$currentWordIndex]; 
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
    $dateAndTime = explode(' ', $strDate);
    $dateArr = explode('-', $dateAndTime[0]);
    $timeArr = explode(':', $dateAndTime[1]);
    return mktime($timeArr[0], $timeArr[1], $timeArr[2], $dateArr[1], $dateArr[2], $dateArr[0]);
}
    
        $pageObj = new pageClass($content);
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        //если пользователь вошел дать ему знать
        echo $pageObj->build_Page();
?>
