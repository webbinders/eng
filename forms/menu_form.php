<?php

//создаем объект формы
include_once __DIR__ . '/../classes/class_form.php';
if(!isset($_SESSION['mode'])) $_SESSION['mode'] = 'mode';
$menu_form=new HtmlForm(array(
   'class'=>'menu',
   'action'=>'./handling_text.php',
   'method'=>'POST'
));

$btn_about = new ButtonElement(array(
    'id'=>'btn_about',
    'formaction' => './about.php',
    'value' => 'О сайте',
    'name' => 'btn_about',
    'type' => 'button',
    'class' => (basename($_SERVER ['PHP_SELF'])  == 'about.php') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку
));
$menu_form ->addInputForm($btn_about);
$btn_read = new ButtonElement(array(
    'id'=>'btn_read',
    'formaction' => './office.php',
    'value' => 'Режим чтения',
    'name' => 'btn_read',
    'type' => 'button',
    'class' => ($_SESSION['mode'] == 'mode_read' && basename($_SERVER ['PHP_SELF'])  == 'office.php') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку
));
$menu_form ->addInputForm($btn_read);

$btn_stud = new ButtonElement(array(
    'id'=>'btn_stud',
    'formaction' => './office.php',
    'value' => 'Режим изучения',
    'name' => 'btn_stud',
    'type' => 'button',
    'class' => ($_SESSION['mode'] == 'mode_stud' && basename($_SERVER ['PHP_SELF'])  == 'office.php') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку
));
$menu_form ->addInputForm($btn_stud);

$btn_search = new ButtonElement(array(
    'id'=>'btn_search',
    'formaction' => './office.php',
    'value' => 'Поиск',
    'name' => 'btn_search',
    'type' => 'button',
    'class' => ($_SESSION['mode'] == 'mode_search' && basename($_SERVER ['PHP_SELF'])  == 'office.php') ? 'active_mode' : 'mode',//в зависимости от выбранного режима подсвечиваем кнопку

));
$menu_form ->addInputForm($btn_search);




$menu_form_str = $menu_form->toString();
