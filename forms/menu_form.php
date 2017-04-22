<?php

//создаем объект формы
include_once __DIR__ . '/../classes/class_form.php';

$menu_form=new HtmlForm(array(
   'class'=>'reading',
   'action'=>'./handling_text.php',
   'method'=>'POST'
));

$btn_read = new ButtonElement(array(
    'id'=>'btn_read',
    'formaction' => './office.php',
    'value' => 'Режим чтения',
    'name' => 'btn_read',
    'type' => 'button',
    'class' => ($_SESSION['mode'] == 'mode_read') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку
));
$menu_form ->addInputForm($btn_read);

$btn_stud = new ButtonElement(array(
    'id'=>'btn_stud',
    'formaction' => './office.php',
    'value' => 'Режим изучения',
    'name' => 'btn_stud',
    'type' => 'button',
    'class' => ($_SESSION['mode'] == 'mode_stud') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку
));
$menu_form ->addInputForm($btn_stud);
