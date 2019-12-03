<?php

include_once __DIR__ . '/../classes/class_form.php';
echo 'hello admin'; 
//создаем объект формы
$menu_form=new HtmlForm(array(
   'class'=>'menu',
   'action'=>'./handling_admin_menu.php',
   'method'=>'POST'
));

$btn_search = new ButtonElement(array(
    'id'=>'btn_search',
    'formaction' => './admin.php',
    'value' => 'Поиск',
    'name' => 'btn_search',
    'type' => 'button'
));
$menu_form ->addInputForm($btn_search);

$btn_search_empty = new ButtonElement(array(
    'id'=>'btn_search_empty',
    'formaction' => './admin.php',
    'value' => 'Поиск слов без перевода',
    'name' => 'btn_search_empty',
    'type' => 'button'
));
$menu_form ->addInputForm($btn_search_empty);


$menu_form_str = $menu_form ->toString();
