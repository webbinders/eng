<?php

//создаем объект формы
include_once __DIR__ . '/../classes/class_form.php';

$stud_form=new HtmlForm(array(
   'class'=>'studing',
   'action'=>'./office.php',
   'method'=>'POST'
));



if (isset($_POST['btn_start_stud']) || isset($_POST['btn_ready']) || isset($_POST['btn_wrong']) || isset($_POST['btn_right'])){
  //если нажата кнопка начать изучение
    //создаем текстовую область "Вопрос"
    $question = new TextAreaElement(array(
    'id' => 'question_text_area',
    'label' => "",
    'cols' => 50,
    'rows' => 2,
    'name' => 'question_text_area',
    'value'=>isset($_POST['question_text_area'])?$_POST['question_text_area']:'',
    
    ));
    $stud_form ->addInputForm($question);
    
    //создаем текстовую область "Ответ"
    $answer = new TextAreaElement(array(
    'id' => 'answer_text_area',
    'label' => "",
    'cols' => 50,
    'rows' => 10,
    'name' => 'answer_text_area',
    'value'=>isset($_POST['answer_text_area'])?$_POST['answer_text_area']:'',
    'class'=>  isset($_POST['btn_right']) || isset($_POST['btn_wrong']) ? 'hidden':'visible',
    'placeholder'=>'Перевод отсутствует, введите сюда перевод.',
    
    ));
    $stud_form ->addInputForm($answer);
    
    //создаем кнопку готово
    $btn_ready = new ButtonElement(array(
        'id'=>'btn_ready',
        'formaction' => './office.php',
        'value' => 'Готово',
        'name' => 'btn_ready',
        'type' => 'button',
    ));
    $stud_form ->addInputForm($btn_ready);
    
    if (isset($_POST['btn_ready'])){
        //если нажата кнопка "Готово", то скрыть кнопку "ГОТОВО" и показать кнопки правильно и неправильно
        $stud_form ->delInputForm($btn_ready);
        
         //создаем кнопку "Неправильно" 
        $btn_wrong = new ButtonElement(array(
            'id'=>'btn_wrong',
            'formaction' => './office.php',
            'value' => 'Неправильно',
            'name' => 'btn_wrong',
            'type' => 'button',
        ));
        $stud_form ->addInputForm($btn_wrong);
        
        //создаем кнопку  "Правильно"
        $btn_right = new ButtonElement(array(
            'id'=>'btn_right',
            'formaction' => './office.php',
            'value' => 'Правильно',
            'name' => 'btn_right',
            'type' => 'button',
        ));
        $stud_form ->addInputForm($btn_right);
        
    }
    
}
else{
  //если еше не нажата кнопка "Начать изучение"
    
    //создаем текстовое поле задающее количество новых вопросов
    $newQuestions = new TextElement(array(
        'id' => 'newQuestions',
        'size' => 3,
        'label' => 'Количество новых вопросов',
        'name' => 'newQuestions',
        'value'=>isset($_POST['newQuestions'])?$_POST['newQuestions']:'7',
    ));
    $stud_form ->addInputForm($newQuestions);

    $btn_start_stud = new ButtonElement(array(
        'id'=>'btn_start_stud',
        'formaction' => './office.php',
        'value' => 'Начать изучение',
        'name' => 'btn_start_stud',
        'type' => 'button',
    ));
    $stud_form ->addInputForm($btn_start_stud);

}

