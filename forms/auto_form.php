<?php


include_once __DIR__ . '/../classes/class_form.php';
//создаем объект формы
$auto_form = new HtmlForm(array(
   'class'=>'auto',
   'action'=>'#',
   
));


//если пользователь авторизирвался
if (isset($_SESSION['access']) && $_SESSION['access']){
    //Формируем приветствие
    $divHello = new divElement(array(
        'id'=>'hello',
        'class' => 'hello',
        'text' => "Hello ". $_SESSION['login']
    ));
    $auto_form->addInputForm($divHello);
    //кнопка Выход
    $btn_out = new ButtonElement(array(
        'id'=>'$btn_out',
        'formaction' => './exit.php',
        'value' => 'Выход',
        'name' => '$btn_out',
        'type' => 'button',
        'class' => 'btn-autoriz'

    ));
    $auto_form->addInputForm($btn_out);
    
}
else{
    //кнопка Вход
    $btn_in = new ButtonElement(array(
        'id'=>'btn_in',
        'formaction' => './joining.php',
        'value' => 'Вход',
        'name' => 'btn_in',
        'type' => 'button',
        'class' => 'btn-autoriz'

    ));

    $auto_form ->addInputForm($btn_in);
    //Кнопка Регистрация
    $btn_registration = new ButtonElement(array(
        'id'=>'$btn_registration',
        'formaction' => './registration.php',
        'value' => 'Регистрация',
        'name' => '$btn_registration',
        'type' => 'button',
        'class' => 'btn-autoriz'

    ));
    $auto_form ->addInputForm($btn_registration);
}
$auto_form_str = $auto_form->toString();
