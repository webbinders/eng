<?php
//создаем объект формы
$my_form=new HtmlForm(array(
    'class'=>'msg',
    'action'=>'office.php',
));



//создаем текстовый абзац и добавляем его на форму
$msg=new pElement(array(
    'text'=>'Вы успешно зарегистрировались.<br>Для продолжения нажмите кнопку [Далее]'
));
$my_form->addInputForm($msg);

$btnOK=new ButtonElement(array(
    'type'=>'submit',
    'name'=>'btnOK',
    'value'=>'Далее >'
));




echo $my_form->toString();

?>
