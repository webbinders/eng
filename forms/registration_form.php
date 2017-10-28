<?php
 //создаем объект формы
    include_once __DIR__ . '/../classes/class_form.php';
    
    $my_form=new HtmlForm(array(
        'class'=>'autorisation',
        'action'=>'./registration.php',
        'method'=>'POST'
    ));
    
            $title = new pElement(array(
                'class' => 'formtitle',
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
    
     $msg_email = new pElement(array(
        'text' => isset($_POST['msg']['email']) ? $_POST['msg']['email'] : '',
    ));
    $my_form->addInputForm($msg_email);

    //создаем  поле для ввода пароля и добавляем его на форму
    $psw_box=new TextPswElement(array(
        'name'=>'psw',
        'size'=>15,
        'label'=>'Пароль<br>',
        'required'=>0
    ));
    $my_form->addInputForm($psw_box);
    
    $msg_psw = new pElement(array(
        'text' => isset($_POST['msg']['psw']) ? $_POST['msg']['psw'] : '',
    ));
    $my_form->addInputForm($msg_psw);
    
    //сообщение об ошибке отображаемое на форме
    if (isset($messing)) $msg=$messing;
    if (isset($_POST['messing2'])) $msg=$_POST['messing2'];
    $msgemail=new pElement(array(
        'text'=>isset($msg)?$msg:''
        ));
    $my_form->addInputForm($msgemail);
    
    $btnOK=new ButtonElement(array(
        'type'=>'submit',
        'class'=>'btn-autoriz',
        'name'=>'btnOK',
        'value'=>'Зарегистрироваться'
    ));
    $my_form->addInputForm($btnOK); 
    
    $btnReset=new ButtonElement(array(
        'type'=>'reset',
        'class'=>'btn-autoriz',
        'name'=>'btnCansel',
        'value'=>'Сброс'
    ));   
    $my_form->addInputForm($btnReset);
    

    



