<?php

 //создаем объект формы
 include_once __DIR__ . '/../classes/class_form.php';
 
$my_form=new HtmlForm(array(
   'class'=>'reading',
   'action'=>'./handling_text.php',
   'method'=>'POST'
));



//создаем текстовую область для читаемого текста и добавляем ее на форму
$text_area = new TextAreaElement(array(
    'id' => 'text_area',
    'label' => "",
    'cols' => 50,
    'rows' => 20,
    'name' => 'text_area',
    'value'=>isset($_POST['text_area'])?$_POST['text_area']:'',
    'placeholder' => "Скопируйте текст для чтения сюда<br>",
));
$my_form->addInputForm($text_area);
//echo '<code>'. htmlspecialchars($text_area -> htmlString). '</code>';

//добавляем кнопку
$btn_handling_text = new ButtonElement(array(
    'id'=>'btn_handling_text',
    'formaction' => './office.php',
    'value' => 'обработать текст',
    'name' => 'btn_handling_text',
    'type' => 'button',
));
$btn_reset_text = new ButtonElement(array(
    'id'=>'btn_reset_text',
    'formaction' => './office.php',
    'value' => 'очистить текст',
    'name' => 'btn_reset_text',
    'type' => 'button',
));
//если нажата кнопка "Обработать текст" и текст не пустой
if (isset($_POST['text_area']) && ($_POST['text_area']!='')){
    //отображаем кнопку "Сбросить текст"
    $my_form->addInputForm($btn_reset_text);
}
 else {
    //отображаем кнопку "Обработать текст"
     $my_form->addInputForm($btn_handling_text);
}


//создаем текстовое поле для слова из текста и текстовой области для перевода на это слово
//и добавляем их на форму

$word = new TextElement(array(
    'id' => 'word',
    'size' => 30,
    'label' => 'Слово<br>',
    'name' => 'word',
    'value'=>isset($_POST['word'])?$_POST['word']:'',
    'placeholder' => "Скопируйте слово для перевода сюда<br>",
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
    
));
$my_form->addInputForm($word);


//добавляем кнопку "Перевод"
$btn_find = new ButtonElement(array(
    'id'=>'btn_find',
    'formaction' => './office.php',
    'value' => 'Перевод',
    'name' => 'btn_find',
    'type' => 'button',
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
));
$my_form->addInputForm($btn_find);



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

$my_form->addInputForm($trans_area);

//добавляем кнопку "Добавить перевод"
$btn_add = new ButtonElement(array(
    'id'=>'btn_add',
    'formaction' => './office.php',
    'value' => 'Добавить перевод',
    'name' => 'btn_add',
    'type' => 'button',
    'class' => !isset($_SESSION['dictionary']) ? 'hidden' : 'visible',
));
$my_form->addInputForm($btn_add);

if (isset($_POST['btn_add']) || isset($_POST['btn_handling_text'])){
            $my_form->delInputForm($trans_area);
            $my_form->delInputForm($btn_add);
            
        }