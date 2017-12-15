<?php
include_once __DIR__ . '/../classes/class_form.php';

 //создаем объект формы

 
$my_form=new HtmlForm(array(
   'class'=>'reading',
   'action'=>'./handling_text.php',
   'method'=>'POST'
));

//создаем кнопку 'обработать текст'
$btn_handling_text = new ButtonElement(array(
    'id'=>'btn_handling_text',
    'formaction' => './office.php',
    'value' => 'Обработать текст',
    'name' => 'btn_handling_text',
    'type' => 'button',
));

//создаем кнопку 'очистить текст'
$btn_reset_text = new ButtonElement(array(
    'id'=>'btn_reset_text',
    'formaction' => './office.php',
    'value' => 'очистить текст',
    'name' => 'btn_reset_text',
    'type' => 'button',
));

    //создаем и отображаем объект абзаца для отображения читаемого текста'];
    $text_area = new TextAreaElement(array(
        'id' => 'text_area',
        'readonly' => 'readonly',
        'label' => "",
        'cols' => 50,
        'rows' => 10,
        'name' => 'text_area',
        'value'=>isset($_POST['text_area'])? htmlentities($_POST['text_area']):'',
        'placeholder' => "Скопируйте текст для чтения сюда<br>",
    ));
   
    
    //создаем скрытое поле для передачи читаемого текста в следующую загрузку страницы
    $hidden_text = new HiddenElement(array(
        'name'=>'text_area',
        'value'=> isset($_POST['text_area'])? htmlentities($_POST['text_area']):'',
    ));
    $my_form->addInputForm($hidden_text); 
    




//создаем текстовую область для переводимого слова
$word = new TextAreaElement(array(
    'id' => 'word',
    'cols' => 50,
    'rows' => 2,
    'label' => '<label for="word">Слово</label>',
    'name' => 'word',
    'value'=>isset($_POST['word'])?$_POST['word']:'',
    'placeholder' => "Скопируйте слово для перевода сюда<br>",
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
    'readonly' => isset($_POST['btn_add']) || isset($_POST['btn_handling_text']) ? '' : 'readonly',
    
));

//создаем кнопку "Перевод"
$btn_find = new ButtonElement(array(
    'id'=>'btn_find',
    'formaction' => './office.php',
    'value' => 'Перевод',
    'name' => 'btn_find',
    'type' => 'button',
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
));

//создаем  текстовую область для ввода перевода
$trans_area = new TextAreaElement(array(
    'id' => 'trans_area',
    'label' => !isset($_SESSION['dictionary']) ? '' : "Перевести<br>",
    'cols' => 30,
    'rows' => 7,
    'name' => 'trans_area',
    'placeholder' => "Скопируйте или введите перевод сюда<br>",
    'value'=>isset($_POST['trans_area'])?$_POST['trans_area']:'',
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
));


    //создаем и добавляем кнопку "Добавить перевод"
    $btn_add = new ButtonElement(array(
        'id'=>'btnAdd',
        'formaction' => './office.php',
        'value' => 'Добавить перевод',
        'name' => 'btn_add',
        'type' => 'button',
        'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
    ));
    
    
    //создаем кнопку показать примеры
    $btn_view_example = new ButtonElement(array(
        'id' => 'btn_view_example',
        'class'=>'btn_view_example',
        'formaction' => './office.php',
        'value' => 'Показать примеры',
        'name' => 'btn_view_example',
        'type' => 'button',
    ));


//если форма запускается впервые или текст отображается впервые
if (isset($_POST['btn_reset_text']) || isset($_POST['btn_read']) || isset($_POST['text_area']) && ($_POST['text_area']=='')){
    //создаем текстовую область для читаемого текста и добавляем ее на форму
    $text_area = new TextAreaElement(array(
        'id' => 'text_area',
        'class' => 'new',
        'label' => "",
        'cols' => 50,
        'rows' => 10,
        'name' => 'text_area',
        'value'=>isset($_POST['text_area'])?$_POST['text_area']:'',
        'placeholder' => "Скопируйте текст для чтения сюда<br>",
       
    ));
    $my_form->addInputForm($text_area);
    
    //отображаем кнопку 'обработать текст'
    $my_form->addInputForm($btn_handling_text);
}
else{
   /*include 'dysplay_text.inc'; 
    $my_form->addInputForm($btn_find);*/
}//end если форма запускается впервые или текст отображается впервые

//если нажата кнопка "Обработать текст" и текст не пустой или нажата кнопка добавить перевод
if (isset($_POST['text_area']) && ($_POST['text_area']!='') && isset($_POST['btn_handling_text']) || isset($_POST['btn_add'])){
    //
    $my_form->addInputForm($text_area);
    $my_form->addInputForm($btn_reset_text); 
    $my_form->addInputForm($word);
    $my_form->addInputForm($btn_find);
    
}


//если была нажата  кнопка Перевод
if (isset($_POST['btn_find'])){
    $my_form->addInputForm($text_area);
    include 'dysplay_translate.inc';
    if (isset($_SESSION['exampleList'])) $my_form->addInputForm($btn_view_example);
    

    
    //делаем недоступным ждя редактирвания поле $word     
}



    //если была нажата "Показать примеры
    if (isset($_POST['btn_view_example'])) {
        $my_form->addInputForm($text_area);
        
        if (isset($_SESSION['exampleList'])) {//возможно эта проверка и не нужна, т.к. кнопка не должна быть показана, если примеров нет
             include  __DIR__ . '/../forms/functions.php';
            dysplay_examples($my_form);
            
        }
        else{
            $p_msg = new pElement(array(
                        'name' => "example_msg",
                        'text' => "Примеров не найдено ",
                        'class' => 'msg',
                    ));
             $my_form->addInputForm($p_msg);
        }
    }
    //Если была нажата "Показать перевод примера"
    if (isset($_POST['btn_show_native'])) {
        
        $my_form->addInputForm($text_area);
                
        //получаем из переменной список примеров
         include  __DIR__ . '/../forms/functions.php';
        dysplay_example($my_form);
       
        
    }
    










