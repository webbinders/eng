<?php

function findBtnHandler(){
    $resultFind = NULL;
    //read values of fields
    $foreign_box = trim($_POST['foreign_box']);
    $native_box = trim($_POST['native_box']);
    //если хотябы одно поле заполнено
    if(strlen($foreign_box) || strlen($native_box)){
        //если заполнены оба поля
        if(strlen($foreign_box) && strlen($native_box)){
            $resultFind = findForeingNativeRecordset($foreign_box, $native_box);
        }
        else{
            if(strlen($foreign_box)){
                $resultFind = findForeingRecordset($foreign_box);
            }else{
                $resultFind = findNativeRecordset($native_box);
            }
        }
    }
    return $resultFind;
}
function findForeingNativeRecordset($strForeing, $strNative){
    $query = "SELECT * FROM thesaurus WHERE `foreign` LIKE '%$strForeing%' AND native LIKE '%$strNative%'"; 
    return findRecordSet($query);
}

function findRecordSet($query){
    $result = queryRun($query,"Error for find  records in function findRecordSet \n $query");
    return $result;

}

function findForeingRecordset($str){
    $query = "SELECT * FROM thesaurus WHERE `foreign` LIKE '%$str%'"; 
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