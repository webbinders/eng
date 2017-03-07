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
    if (isset($_POST['btn_stud'])) $_SESSION['mode'] = 'mode_stud';
    
    include_once 'forms/menu_form.php';//подключаем файл формы для чтения
    $content .= $menu_form -> toString();
    
    //echo '$_SESSION'."['mode'] = ". $_SESSION['mode'];
    //var_dump($btn_read);
    //var_dump($btn_stud);
        
        
switch ($_SESSION['mode']) {
    case 'mode_read':
        
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
    case 'mode_stud':
        if(isset($_POST['btn_start_stud'])){
            //если нажата кнопка начать изучение
            //загружаем список для изучения
            $studList = new StudList($_POST['newQuestions']); 
             //var_dump($studList);
            //и запускаем процедуру тестирования
            testing($studList);
        }
        include_once 'forms/studing_form.php';//подключаем файл формы для изучения
        if(isset($_POST['btn_start_stud'])){
           // $stud_form -> delInputForm($btn_start_stud);          
        }        
        $content .= $stud_form -> toString();
       // $content .= var_dump($_POST);
        //if(isset($studList)) $content .= var_dump($studList);
        
        break;

    default:
        break;
}
/*
 * процедура тестирования
 */
function testing($studList){
    $currentWordIndex = 0;
    //пока список для изучения не пустой
    if  (count($studList)){
        foreach ($studList->studList as  $value) {
            $keyArr[]=$value;
        }
        //echo '$keyArr<br>';
        //var_dump($keyArr);
        $currentWordIndex = 0;
        $currentWord = $keyArr[$currentWordIndex]; 
        //заполняем текстовую область "Вопрос" свойством foreign
        $_POST['question_text_area'] = $currentWord->getForeign();
    }
}
    
        $pageObj = new pageClass($content);
        //если пользователь входит впервые, предложить войти или зарегистрироваться
        //если пользователь вошел дать ему знать
        echo $pageObj->build_Page();
?>
