<?php
include_once __DIR__ . '/../classes/class_form.php';

  //переменная содержащая адрес текущей страницы
            $_SESSION['urlForButton']="http://".$_SERVER['HTTP_HOST'].$_SERVER ['PHP_SELF']."?".SID;
            $my_form=new HtmlForm(array(
                'class'=>'autorisation',
                'action'=>'./joining.php',
                'method'=>'POST',
            ));
            
            $title = new pElement(array(
                'class' => 'formtitle',
                'text' => "Форма входа",
            ));
            $my_form->addInputForm($title);

            $email=new TextElement(array(
                'label'=>'email<br>',
                'name'=>'email',
                'value' => isset($_POST['email']) ? $_POST['email'] : '',
                'size'=>15
            ));
            $my_form->addInputForm($email);
            
            $msg_email = new pElement(array(
                'text' => isset($_POST['msg']['email']) ? $_POST['msg']['email'] : '',
            ));
            $my_form->addInputForm($msg_email);



            $psw=new TextPswElement(array(
                'name'=>'psw',
                'size'=>15,
                'value' => isset($_POST['psw']) ? $_POST['psw'] : '',
                'label'=>'Пароль<br>'
            ));
            $my_form->addInputForm($psw);
            
            $msg_psw = new pElement(array(
                'text' => isset($_POST['msg']['psw']) ? $_POST['msg']['psw'] : '',
            ));
            $my_form->addInputForm($msg_psw);

            $btnOK=new ButtonElement(array(
                'type'=>'submit',
                'name'=>'btnOK',
                'value'=>'Войти'
            ));
            $my_form->addInputForm($btnOK);    

            $btnReset=new ButtonElement(array(
                'type'=>'reset',
                'name'=>'btnCansel',
                'value'=>'Сброс'
            ));
            $my_form->addInputForm($btnReset);

            $forgot=new linkElement(array(
                'href'=>'forgot.php',
                'text'=>'Забыли пароль?'
            ));
            $my_form->addInputForm($forgot);

            $reg=new linkElement(array(
                'href'=>"http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/registration.php',
                'text'=>'Регистрация'
            ));
            $my_form->addInputForm($reg);

            
?>
