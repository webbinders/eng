<?php

//создаем объект формы
$my_form=new HtmlForm(array(
    'class'=>'autorisation',
    'action'=>'forgotOK.php',
));

//создаем текстовое поле и добавляем его на форму
$email=new TextElement(array(
    'label'=>'email<br>',
    'name'=>'email',
    'size'=>15
));
$my_form->addInputForm($email);

//создаем текстовый абзац и добавляем его на форму
$msg=new pElement(array(
    'text'=>'Не верно введен адрес.'
));
$my_form->addInputForm($msg);

$btnOK=new ButtonElement(array(
    'type'=>'submit',
    'name'=>'btnOK',
    'value'=>'Пароль'
));
$my_form->addInputForm($btnOK);    

$btnReset=new ButtonElement(array(
    'type'=>'reset',
    'name'=>'btnCansel',
    'value'=>'Сброс'
));
$my_form->addInputForm($btnReset);



echo $my_form->toString();
        
?>
