<?php

include_once __DIR__ . '/../classes/class_form.php';

 //создаем объект формы
$my_form=new HtmlForm(array(
   'class'=>'result_search',
   'action'=>'./search_form_handling.php',
   'method'=>'POST'
));

//поле для ввода иностранного слова
$foreign_box = new TextElement(array(
    'label' => 'На английском',
    'name' => 'foreign_box',
    ''
));
$my_form->addInputForm($foreign_box);

//поле для ввода русского слова
$native_box = new TextElement(array(
    'label' => 'На русском',
    'name' => 'native_box',
    ''
));
$my_form->addInputForm($native_box);

//Кнопка "Найти"
$btnFind=new ButtonElement(array(
    'type'=>'submit',
    'name'=>'btnFind',
    'value'=>'Найти'
));
$my_form->addInputForm($btnFind);

