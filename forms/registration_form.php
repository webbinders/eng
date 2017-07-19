<?php
 //создаем объект формы
    include_once __DIR__ . '/../classes/class_form.php';
    
    $my_form=new HtmlForm(array(
        'class'=>'registration',
        'action'=>'./registration.php',
        'method'=>'POST'
    ));
    
                $title = new pElement(array(
                'text' => "Форма регистрации",
            ));
            $my_form->addInputForm($title);
    
    //создаем текстовое поле и добавляем его на форму
    $email_box=new TextElement(array(
        'label'=>'email<br>',
        'name'=>'email',
        'size'=>15,
        'required'=>1,
        'value'=>isset($_POST['email'])?$_POST['email']:''
    ));
    $my_form->addInputForm($email_box);

    //создаем  поле для ввода пароля и добавляем его на форму
    $psw_box=new TextPswElement(array(
        'name'=>'psw',
        'size'=>15,
        'label'=>'Пароль<br>',
        'required'=>0
    ));
    $my_form->addInputForm($psw_box);
    
    //сообщение об ошибке отображаемое на форме
    if (isset($messing)) $msg=$messing;
    if (isset($_POST['messing2'])) $msg=$_POST['messing2'];
    $msgemail=new pElement(array(
        'text'=>isset($msg)?$msg:''
        ));
    $my_form->addInputForm($msgemail);
    
    $btnOK=new ButtonElement(array(
        'type'=>'submit',
        'name'=>'btnOK',
        'value'=>'Зарегистрироваться'
    ));
    $my_form->addInputForm($btnOK); 
    
    $btnReset=new ButtonElement(array(
        'type'=>'reset',
        'name'=>'btnCansel',
        'value'=>'Сброс'
    ));   
    



