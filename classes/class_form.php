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
        $return_string   .=  "\n";
      }	     
     $return_string .= "</FORM>\n"; 
     return $return_string ;
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
    var $id;
    var $str_attr;
    var $child_arr;
    
    function __construct($arr_param) {
        $str_attr = '';
        foreach ($arr_param as $key => $value) {
            $this->$key=$value;
            if($key !='text')
                $this->str_attr .= "$key = '$value'";
        }
        
    }
    
       function addChild ($child) {
   if (!isSet($child) ||
       !is_object($child) || 
       !is_subclass_of($child, 'HtmlFormElement')){
           die("Argument to HtmlForm::addlnputForm ". 
               "must be instance of HtmlFormElement.". 
               "  Given argument is of class ".
               get_class($child)
   );
    }
    else {
        //добавляем элемент в массив элементов формы
        $this->child_arr[]=$child;
        
        
    }
}
    
    function isparametr($parametr,$msg){
	 if (!isSet($parametr)) die($msg);
	 return $parametr;
  		 	 	
}
/*
 * Устанавливает значение атрибута элемента формы
 */
function setAttr($key, $value){
    $this->$key=$value;
}
    
    
}
class HiddenElement extends HtmlFormElement{
    var $name;
    var $value;
    function __construct($arr_param){
        parent::__construct($arr_param);
        
        $this->htmlString="<input type='hidden' name='$this->name' value='$this->value'>";
        
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
        var $disabled;
        var $readonly;
	
    function __construct($arr_param){
    parent::__construct($arr_param);

    $this->type='textArea';
    if(isset($this->disabled) && $this->disabled != ''){
        $this->disabled = 'disabled';
    }
    else{
        $this->disabled = '';
    }
    if(isset($this->readonly) && $this->readonly !=''){
        $this->readonly = 'readonly';
    }
    else{
        $this->readonly = '';
    }


    $this->htmlString=
        $this->label.
        "<TEXTAREA ".
        "id = '$this->id' ".   
        "class='$this->class'  ".
        "NAME='$this->name'  ".
        "ROWS='$this->rows'   ".
        "COLS='$this->cols'   ".
        "placeholder = '$this->placeholder' ".
        //"VALUE= '$this->value' ".
        $this->disabled.
        $this->readonly.
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
                "ID='$this->id' ".
                "formaction='$this->formaction' ".
                "CLASS='$this->class' ".
                "NAME='$this->name'>". 
                
                "$this->value".
                "</BUTTON>";
     }
     else{
         die('Неверно задан тип кнопки');
     }
     }
}
class pElement extends HtmlFormElement{
    var $text;
    function __construct($arr_param){
    parent::__construct($arr_param);
    if (isset($this->class)) 
        $class="class='$this->class' " ;
            else 
                $class='';
    if (isset($this->name)){
        $name = "name='$this->name'";
    }else{
        $name ='';
    }
    $this->htmlString=  
                 "<p $class $name> $this->text</p>";
    }
}

class divElement extends HtmlFormElement{
    var $text;
    function __construct($arr_param){
    parent::__construct($arr_param);

    $this->htmlString =  "<div $this->str_attr> $this->text</div>";
                
    }
    function addChild($child) {
        parent::addChild($child);
        $this->text .= $child->htmlString;
        $this->htmlString =  "<div $this->str_attr> $this->text</div>";
    }
}

class ChbxElement extends HtmlFormElement{
    var $checked;
     function __construct($arr_param){
         parent::__construct($arr_param);
         if ($this->type='checkbox'){
             $checkbox='checkbox';
         }
         else{
             $checkbox='';
         }
         if(isset($this->checked) || $this->value == 1){
             $checked = "checked ";
         }else{
             $checked ='';
         }
        //$this->checked=$_checked;
         $this->htmlString =
                 
                 "<input type='checkbox' ".
                 "name='$this->name' ".
                 $checked.
                 "value='$this->value' $checkbox>$this->label";
     }
}
?>
