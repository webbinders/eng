<?php
//если нажата кнопка "Найти"
if(isset($_POST['btnFind'])){
    if(isset($_SESSION['find'])) unset($_SESSION['find']);
    $result = findBtnHandler();
    if($result){
        $arrWords = resToArr($result);
        //$strFindHTML = arrayToHTML($arrWords);
        $_SESSION['find'] = serialize($arrWords);
    }
}
//если нажата кнопка "Добавить в список для изучения"
if(isset($_POST['btnAdd'])){
    //определяем id нажатой кнопки
    $btnId = key($_POST['btnAdd']);
    
    $arrWords = unserialize($_SESSION['find']);
    //var_dump($arrWords);
    
    $arrWords[$btnId]->addToStudList();
    $_SESSION['find'] = serialize($arrWords);
}
/*
 * Проверяет заполнение полей foreign формы
 */
function ForeignBoxToArray(){
    $resArr = array();
    foreach ($_POST['foreign_box'] as $key => $value) {
        
        if(strlen(trim($value))) {
            $resArr[$key]['foreign_box'] = $value; 
        }else{
            $resArr[$key]['foreign_box'] = "";
        }
        
        if(isset($_POST['asPart_chb'][$key])){
            $resArr[$key]['asPart_chb']= 1;
        }
        else{
            $resArr[$key]['asPart_chb']= 0;
        }
            
        
    }
    
    return $resArr;
}
function findBtnHandler(){
    $resultFind = NULL;
    $foreignArray = ForeignBoxToArray();

    
    $native = trim($_POST['native_box']);
    //проверяем заполнение полей для английского
    $foreign='';
    foreach ($foreignArray as $key => $value) {
        $foreign .= $value['foreign_box'];
        
    }
    
    //проверяем значения флажка "Учитывать порядок, чтобы передать его значение в функцию поиска
    if (isset($_POST['chb_order'])) {
        $order = 1;
    }else {
        $order = 0;

    }
    
    if (strlen($foreign) || strlen($native)){
        if (strlen($foreign) && strlen($native)){
            //если заполнены оба поля
             $resultFind = findForeignNativeRecordset($foreignArray,$order, $native);
            
        }else{
            //если поля для английского заполнены (а для русского - нет)
            if (strlen($foreign)){

                $resultFind = findForeignRecordset($foreignArray,$order);
                
            }
            //Если поле с переводом заполнено, с английским не заполнено
            else {
                $resultFind = findNativeRecordset($native);
            }           
           
        }
        
    }


    return $resultFind;
}
function findForeignNativeRecordset($arrForeign, $order, $strNative){
    $queryForeign = buildQueryForForeign($arrForeign, $order);
    $query = $queryForeign . " AND `native` LIKE '%$strNative%'";     
    return findRecordSet($query);
}

function findRecordSet($query){
    $result = queryRun($query,"Error for find  records in function findRecordSet \n $query");
    return $result;

}

function buildQueryForForeign($arrParam, $order) {
    $arrLike = array();
    //если надо соблюдать последовательность
    if ($order){
        foreach ($arrParam as $key => $value) {
            if($value['asPart_chb']){
                $arrLike[] = '.*'.$value['foreign_box'].'.*';
            }
            else{
                if(strlen($value['foreign_box'])>0){
                    $arrLike[] ='.*' . "[[:<:]]".$value['foreign_box']."[[:>:]]";
                }
                else{
                    $arrLike[] = "";
                }
                
            }
        }
        $query = "SELECT * FROM `thesaurus` WHERE `foreign` RLIKE '{$arrLike[0]} {$arrLike[1]}{$arrLike[2]}.*'";
    }else{
        //если не надо соблюдать последовательность
        foreach ($arrParam as $key => $value){
            if($value['asPart_chb']){
                $arrLike[] ="LIKE '%{$value['foreign_box']}%'";
            }else{
                if(strlen($value['foreign_box'])>0){
                    $arrLike[] ="RLIKE '[[:<:]]".$value['foreign_box']."[[:>:]]'";
                }
                else{
                    $arrLike[] = "LIKE '%%'";
                }
                
            }
        }
        $query = "SELECT * FROM `thesaurus` WHERE `foreign` {$arrLike[0]} AND `foreign`  {$arrLike[1]} AND `foreign`  {$arrLike[2]}";
    }
    echo $query;
    return $query;
}

function findForeignRecordset($arrParam, $order ){
    
    $query = buildQueryForForeign($arrParam, $order);

    return findRecordSet($query);

}

function findNativeRecordset($str){    
    $query = "SELECT * FROM thesaurus WHERE native LIKE '%$str%'";    
    return findRecordSet($query);

}
/*
 * Преобразует ссылку на ресурс в массив объектов-слов
 */
function resToArr($res){
    $resArr = array();
    if(mysql_num_rows($res)){
        while ($row = mysql_fetch_array($res)){
            $objWord = new Word($row);
            $resArr[$objWord->id] =$objWord;
        
        }
        
        return $resArr;
    }

}
/*
 * Преобразовывает ссылку на ресурс, содержащий результаты поиска в строку HTML
 */
function arrayToHTML($wordArr){
    $htmlStr = '';
    if(sizeof($wordArr) > 0){
        foreach ($wordArr as $key => $value) {
            $id = $value->id;
            $foreign = $value->foreign;
            $native = $value->native;
            $htmlStr .= word_as_html($id, $foreign, $native);
        }
    }
    return $htmlStr;
}
function word_as_html($id, $foreign, $native, $examples){
    $html = "<div class = 'word'>";
    $html .= "<div class = 'foreign' >$foreign</div><br>";
    $html .= "<div class = 'native' >$native</div><br>";
    $html .= "<button type = 'submit' name = 'stud_btn[{$id}]'> Добавить в список для изучения </button>";
    $html .= "</div>";
    return $html;
    
    
}