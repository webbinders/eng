<?php
/*
 * Этот файл должен подключаться к файлам форм *_form.php
 */
 
 /*
  * добавляет на форму текст, кнопку для его очистки и поле для ввода непонятного слово.
  */
 
 function dysplay_text(){
 
 }
 function dysplay_translate(){

     
 }
 function dysplay_examples($form){
    
            
            $exampleList = unserialize($_SESSION['exampleList']);
            // var_dump($exampleList);
            // если список примеров не пуст
            if (sizeof($exampleList)) {
                foreach ($exampleList as $value) {

                    $p_foreign = new pElement(array(
                        'name' => "example_foreign[{$value->id}]",
                        'text' => $value->foreign,
                        'class' => 'foreign',
                    ));
                    $form->addInputForm($p_foreign);

                    //Создаем кнопку "Показать перевод"
                    $btn_show_native = new ButtonElement(array(
                        'id' => 'btn_show_native',
                        'formaction' => './office.php',
                        'value' => 'Показать перевод',
                        'name' => 'btn_show_native' . "[{$value->id}]",
                        'type' => 'button',
                    ));
                    $form->addInputForm($btn_show_native);
                }
                $_SESSION['exampleList'] = serialize($exampleList);
                
            }
         
 }
 function dysplay_example($form){
//получаем из переменной список примеров
        $exampleList = unserialize($_SESSION['exampleList']);
       //var_dump($exampleList);
        //определяем нажатую кнопку
        $btnId = key($_POST['btn_show_native']);
        if (isset($_POST['shown'])) $shownExample = unserialize ($_POST['shown']);
        //запоминаем нажатую кнопку в массиве
        $shownExample[] = $btnId;
        //print_r($shownExample);
        
        //Для каждого элемента списка примеров
        foreach ($exampleList as  $value) {
            //создаем параграф содержащий пример
            $p_foreign = new pElement(array(
                'name' => "example_foreign[{$value->id}]",
                'text' => $value->foreign,
                'class' => 'foreign',
            ));
            $form->addInputForm($p_foreign);
            //если он (параграф содержащий пример) не относится к массиву нажатых кнопок "Показать перевод примера"
            if (!in_array($value->id, $shownExample)) {
                //Создаем кнопку "Показать перевод"
                $btn_show_native = new ButtonElement(array(
                    'id' => 'btn_show_native',
                    'formaction' => './office.php',
                    'value' => 'Показать перевод',
                    'name' => 'btn_show_native' . "[{$value->id}]",
                    'type' => 'button',
                ));
                $form->addInputForm($btn_show_native);
            } else {
                //создаем параграф содержащий перевод
                $p_native = new pElement(array(
                    'name' => "example_native[{$value->id}]",
                    'text' => $value->native,
                    'class' => 'native',
                ));
                $form->addInputForm($p_native);
            }
        }
        $serShownExample=serialize($shownExample);
        $shownArr = new HiddenElement(array(
            'name' => 'shown',
            'value' => $serShownExample,
        ));
        $form->addInputForm($shownArr);
        $_SESSION['exampleList'] = serialize($exampleList);
 }
