<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class HtmlForm {
    
var $action;//Адрес получателя данных
var $elementsForm;//Массив элементов  формы  
var $hiddenVariables;//Ассоциативный массив со структурой "имя/значение"
var $class;
var $id;

function  __construct ($arr_param) {
    foreach ($arr_param as $key => $value) {
        $this->$key=$value;
    }
    
    $this->elementsForm = array();//массив содержащий элементы управления формы. Каждый элемент массива имеет тип object
    $this->hiddenVariables = array() ;
}

   function addInputForm ($input_form) {
   if (!isSet($input_form) ||
       !is_object($input_form) || 
       !is_subclass_of($input_form, 'HtmlFormElement')){
           die("Argument to HtmlForm::addlnputForm ". 
               "must be instance of HtmlFormElement.". 
               "  Given argument is of class ".
               get_class($input_form)
   );
    }
    else {
        //добавляем элемент в массив элементов формы
        $this->elementsForm[]=$input_form;
        //array_push($this->inputForms, $input_form);
    }
}
//удаляет элемент $input_form из формы еще не проверял
function delInputForm($input_form) {
    $form_array = $this->elementsForm;
    foreach ($form_array as $key => $value) {
        if($value == $input_form){
            unset($this->elementsForm[$key]);
        }
    }
}
 function toString () {
     $form_array = $this->elementsForm;
     $return_string = "";
     $return_string .=
      "<FORM   METHOD=\"POST\" " .
       "CLASS=\"$this->class\" ".      
      "ACTION=\"$this->action\">\n";
      
       foreach ($form_array as $input_form) {

        $return_string .=   $input_form->htmlString;
        $return_string   .=  "<BR>\n";
      }	     
     $return_string .= "</FORM>\n"; 
     return($return_string) ;
    }
}
//***************************************************
abstract class HtmlFormElement{
    var $type;
    var $name;
    var $form;
    var $value;
    var $htmlString;
    var $label;
    var $class;
    
    function __construct($arr_param) {
        //проверяем соответствие параметра допустимому значению
        
        //если параметр допустим, создаем соответствующий ему элемент
        //$this->type=$_type;
        //$this->name=  $this->value=  $this->htmlString="";
        foreach ($arr_param as $key => $value) {
            $this->$key=$value;
        }
        
    }
    
    function isparametr($parametr,$msg){
	 if (!isSet($parametr)) die($msg);
	 return $parametr;
  		 	 	
}
    
    
}

class linkElement extends HtmlFormElement{
    var $text;
    var $href;
    
    function __construct($arr_param){
        parent::__construct($arr_param);
        //$this->type='link';
        $this->htmlString="<p><a href='$this->href'>$this->text</a></p>";
        
    }
    
}

class TextElement extends HtmlFormElement{
    var $size;
    function __construct($arr_param){
        parent::__construct($arr_param);
        if  (isset($this->required))
            $req='required';
        else
            $req='';
        $this->htmlString=
                "<p class='$this->class'>".$this->label.
                   " <input class='$this->class' name='$this->name' size='$this->size' value='$this->value' type='$this->type' $req></p>";
        }           
       
                                                                
                                                                
    } 
    
    class TextPswElement extends HtmlFormElement{
    var $size;
    
    function __construct($arr_param){
        parent::__construct($arr_param);
        $this->type='password';
        if  (isset($this->required))
            $req='required';
        else
            $req='';
        $this->htmlString=
                '<p>'.$this->label.
                
                "<input class='$this->class' name='$this->name' size='$this->size' type='$this->type' $req ></p>";
        }
       
                                                                
                                                                
    } 
    
    class SelectElement extends HtmlFormElement{
        var $size;
        var $multiple;
        var $autofocus;
        function __construct($arr_param){
            parent::__construct($arr_param);
            if (isset($this -> multiple)){
                $mult = 'multiple';
            }
            else {            
                $mult = '';
            }
            if (isset($this -> autofocus)){            
                $autofoc = autofocus;
            }
            else{
                $autofoc = '';
            }
            
            $this->htmlString = 
                '<p>'.$this->label.
                "<SELECT ".
                "class = '$this->class'  ".
                "NAME = '$this->name'  ".
                "VALUE = '$this->value' ".
                "SIZE = '$this->size' ".
                "$mult $autofoc>".
                "</SELECT>";             
                    
        
        }
        
        /*
         * Добаляет опцию в конец списка
         */
        function addOption($option) {
            //изменяем свойство htmlString списка
            //для этого разделяем строку содержащую htmlString на две части
            //между которыми вставляем добавляем вставляемую опцию.            
            $this->htmlString = substr( $this->htmlString, 0, -strlen("</SELECT>")) . $option->htmlString . "</SELECT>";
        }
        
    }
    
    class OptionElement extends HtmlFormElement{
        var $selected;
        function __construct($arr_param){
            parent::__construct($arr_param);
            if (isset($this -> selected)){            
                $selected = 'selected';
            }
            else{
                $selected = '';
            }
            $this->htmlString =
                '<p>'.$this->label.
                "<OPTION ".
                "class = '$this->class'  ".
                "NAME = '$this->name'  ".
                "VALUE = '$this->value' ".
                "</OPTION>";    
        }            
    }
    
class TextAreaElement extends HtmlFormElement{
 //устанавливаем недостающие параметры
 	var $rows;
	var $cols;
	var $class;
        var $placeholder;
	
    function __construct($arr_param){
    parent::__construct($arr_param);

    $this->type='textArea';


    $this->htmlString=
        $this->label.
        "<TEXTAREA ".
        "class='$this->class'  ".
        "NAME='$this->name'  ".
        "ROWS='$this->rows'   ".
        "COLS='$this->cols'   ".
        "placeholder = '$this->placeholder' ".
        "VALUE= '$this->value' ".
        ">".
      $this->value.
        "</TEXTAREA>";


    }//end construct


    }//end class TextArea
    
/*
 * Кнопка создваямая с помощью тега INPUT
 */
class InpButtonElement extends HtmlFormElement{
    function __construct($arr_param){
    parent::__construct($arr_param);
    $_type=  $this->type;
    if ( ($_type=='button')||($_type=='reset')||($_type=='submit')){
        $this->htmlString=
                "<INPUT TYPE='$this->type' " .
                " VALUE='$this->value' ".
                "NAME='$this->name'>".                    
                "\n";
     }
     else{
         die('Неверно задан тип кнопки');
     }
     }
}
/*
 * Кнопка создваемая с помощью тега BUTTON
 */
class ButtonElement extends HtmlFormElement{
    var $formaction;
    function __construct($arr_param){
    parent::__construct($arr_param);
    $_type=  $this->type;
    if ( ($_type=='button')||($_type=='reset')||($_type=='submit')){
        $this->htmlString=
                "<BUTTON  " .
                "formaction='$this->formaction' ".
                "CLASS='$this->class' ".
                "NAME='$this->name'>". 
                
                "$this->value".
                "</BUTTON>".
                "\n";
     }
     else{
         die('Неверно задан тип кнопки');
     }
     }
}
class pElement extends HtmlFormElement{
    function __construct($arr_param){
    parent::__construct($arr_param);
    if (isset($this->class)) 
        $class="class='$this->class' " ;
            else 
                $class='';
    $this->htmlString=  
                 "<p $class > $this->text</p>";
    }
}

class ChbxElement extends HtmlFormElement{
    var $checked;
     function __construct($arr_param){
         parent::__construct($arr_param);
         $this->type='checkbox';
        //$this->checked=$_checked;
         $this->htmlString=
                 "<p>".
                 "<input type='checkbox' ".
                 "name='$this->name' ".
                 
                 "value='$this->value'>$this->label</p>";
     }
}
?>
