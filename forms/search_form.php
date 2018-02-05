<?php

include_once __DIR__ . '/../classes/class_form.php';

 //создаем объект формы
$my_form=new HtmlForm(array(
   'class'=>'search',
   'action'=>'./search_form_handling.php',
   'method'=>'POST'
));

//Секция атрибутов поиска по английскому слову
$foreign_section = new divElement(array(
    'class' => 'foreign_section',
    //'text' => $foreign_box1->htmlString.$foreign_box2->htmlString.$foreign_box3->htmlString,
));
    $word_head = new divElement(array(
        'text'=>'На английском ' 
    ));
    //блок слов
    $div_word1 = new divElement(array(
        'class' => 'div_word',
    ));
    $div_word2 = new divElement(array(
        'class' => 'div_word',
    ));
    $div_word3 = new divElement(array(
        'class' => 'div_word',
    ));
        //поле для ввода иностранного слова
        $foreign_box1 = new TextElement(array(
            
            'name' => 'foreign_box[1]',
            'class' => 'foreign_box'

        ));
        $foreign_box2 = new TextElement(array(

            'name' => 'foreign_box[2]',
            'class' => 'foreign_box'
        ));

        $foreign_box3 = new TextElement(array(

            'name' => 'foreign_box[3]',
            'class' => 'foreign_box'
        ));



        $chb_head = new divElement(array(
            
            'text' => '<br> Искать как фрагмент',
        ));
        $asPart_chb1 = new ChbxElement(array(
            'name' => 'asPart_chb[1]',
            'checked' => 'checked'
            

        ));
        $asPart_chb2 = new ChbxElement(array(
            'name' => 'asPart_chb[2]',
            'checked' => 'checked'
            
        ));
        $asPart_chb3 = new ChbxElement(array(
            'name' => 'asPart_chb[3]',
            'checked' => 'checked'
            
        ));
    
    $div_word1 ->addChild($foreign_box1);
    $div_word1 ->addChild($asPart_chb1);

    $div_word2 ->addChild($foreign_box2);
    $div_word2 ->addChild($asPart_chb2);
    
    $div_word3 ->addChild($foreign_box3);
    $div_word3 ->addChild($asPart_chb3);
    $div_head = new divElement(array(
        'class'=>'head',
    ));
    $div_head->addChild($word_head);
    $div_head->addChild($chb_head);
$foreign_section->addChild($div_head);



$foreign_section->addChild($div_word1);
$foreign_section->addChild($div_word2);
$foreign_section->addChild($div_word3);

$div_order = new divElement(array(
    'class' => 'div_order',
    'content' => 'Учитывать порядок'
));
$div_order_head = new divElement(array(
       'class' => 'div_order_head',
    'text' => 'Учитывать порядок'
));
$chb_order = new ChbxElement(array(
    'name'=>'chb_order'
));
$div_order->addChild($div_order_head);
$div_order->addChild($chb_order);
$foreign_section->addChild($div_order);
$my_form->addInputForm($foreign_section);


//поле для ввода русского слова
$native_box = new TextElement(array(
    'label' => 'На русском',
    'name' => 'native_box',
    ''
));

$my_form->addInputForm($native_box);

//Кнопка "Найти"
$btnFind=new ButtonElement(array(
    'type'=>'submit',
    'name'=>'btnFind',
    'value'=>'Найти'
));
$my_form->addInputForm($btnFind);
//var_dump($my_form) ;
