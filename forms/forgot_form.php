<?php
include_once __DIR__ . '/../classes/class_form.php';
//создаем объект формы
$my_form=new HtmlForm(array(
    'class'=>'autorisation',
    'action'=>'forgotOK.php',
));

            $title = new pElement(array(
                'class' => 'formtitle',
                'text' => "Форма восстановления пароля",
            ));
            $my_form->addInputForm($title);

//создаем текстовое поле и добавляем его на форму
$email=new TextElement(array(
    'label'=>'email<br>',
    'name'=>'email',
    'value' => isset($_POST['email']) ? $_POST['email'] : '',
    'size'=>15
));
$my_form->addInputForm($email);

//создаем текстовый абзац и добавляем его на форму
$msg=new pElement(array(
    'text'=>  isset($_POST['msg']['email']) ? $_POST['msg']['email'] : '',
));
$my_form->addInputForm($msg);

$btnOK=new ButtonElement(array(
    'type'=>'submit',
    'name'=>'btnOK',
    'value'=>'Получить пароль'
));
$my_form->addInputForm($btnOK);    

$btnReset=new ButtonElement(array(
    'type'=>'reset',
    'name'=>'btnCansel',
    'value'=>'Сброс'
));
$my_form->addInputForm($btnReset);




        
?>
