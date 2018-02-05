<?php
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
    //var_dump($foreignArray);
    //var_dump($_POST);
    
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
            return $resultFind;
            exit();
        }
        
    }


    return $resultFind;
}
function findForeignNativeRecordset($arrParam, $order, $strNative){
    $queryForeign = buildQueryForForeign($arrParam, $order);
    $query = $queryForeign . " AND `native` LIKE '%$strNative%'"; 
    echo $query;
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
                $arrLike[] = $value['foreign_box'];
            }
            else{
                $arrLike[] ="[[:<:]]".$value['foreign_box']."[[:>:]]";
            }
        }
        $query = "SELECT * FROM `thesaurus` WHERE `foreign` RLIKE '.*{$arrLike[0]} .*{$arrLike[1]}.*{$arrLike[2]}.*'";
    }else{
        //если не надо соблюдать последовательность
        foreach ($arrParam as $key => $value){
            if($value['asPart_chb']){
                $arrLike[] ="LIKE '%{$value['foreign_box']}%'";
            }else{
                $arrLike[] ="RLIKE '[[:<:]]".$value['foreign_box']."[[:>:]]'";
            }
        }
        $query = "SELECT * FROM `thesaurus` WHERE `foreign` {$arrLike[0]} AND `foreign`  {$arrLike[1]} AND `foreign`  {$arrLike[2]}";
    }
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
 * Преобразовывает ссылку на ресурс, содержащий результаты поиска в строку HTML
 */
function resultToHTML($result){
    $htmlStr = '';
    if(mysql_num_rows($result)){
        while ($row = mysql_fetch_array($result)){
            //в соответствии с переданными параметрами устанавливаем свойства слова
            $foreign = $row['foreign'];
            $frequency = $row['frequency'];
            $examples = $row['examples'];    
            $native = $row['native'];                
            $htmlStr .= word_as_html($foreign, $native, $examples);
        
        }
        return $htmlStr;
    }
 else {
        return '<p>Поиск не дал результатов</p>';
    }
}
function word_as_html($foreign, $native, $examples){
    $html = "<div class = 'word'>";
    $html .= "<div class = 'foreign' >$foreign</div><br>";
    $html .= "<div class = 'native' >$native</div><br>";
    $html .= "</div>";
    return $html;
    
    
}