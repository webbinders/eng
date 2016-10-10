<?php

  //переменная содержащая адрес текущей страницы
            $_SESSION['urlForButton']="http://".$_SERVER['HTTP_HOST'].$_SERVER ['PHP_SELF']."?".SID;
            $my_form=new HtmlForm(array(
                'class'=>'vhod',
                'action'=>'vhodOK.php',
            ));

            $email=new TextElement(array(
                'label'=>'email<br>',
                'name'=>'email',
                'size'=>15
            ));
            $my_form->addInputForm($email);



            $psw=new TextPswElement(array(
                'name'=>'psw',
                'size'=>15,
                'label'=>'Пароль<br>'
            ));
            $my_form->addInputForm($psw);

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
                'href'=>"http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/registration/registration.php',
                'text'=>'Регистрация'
            ));
            $my_form->addInputForm($reg);

            echo $my_form->toString();
?>
