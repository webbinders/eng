 <!DOCTYPE html>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <link rel="stylesheet" type="text/css" href="./normalize.css">
                     <link rel="stylesheet" type="text/css" href="./fstyle.css">
                    <title></title>
                </head>
<?php
$s = "THIS LECTURE SERIES USED TO BE CALLED &QUOT;THE LAST LECTURE.&QUOT;";
$s ='Humans were created &ldquo;in God&rsquo;s image.&rdquo; ';
$s = 'фуры йогурт';
echo $s.'<br>';
$s=  strtoupper($s);
echo $s.'<br>';
$s= qou($s);
$s= htmlentities($s);
echo $s;
function qou($str){
    $start = 0;
    $array_chang = array();
    while(preg_match('/(&\w{3,5};)/', $str, $matches,PREG_OFFSET_CAPTURE,$start)){
        //var_dump($matches);
        $pos_apersand = $matches[1][1];
        $lenentitie = strlen($matches[1][0]);
                
        //echo 'pos_apersand ='.$pos_apersand.'<br>';
        $array_chang[$pos_apersand]= strtolower(substr($str, $pos_apersand,$lenentitie)) ;
        $start =$pos_apersand+6;
        //str2=substr($str, $pos_apersand+7);
        
        
       
    }
    //var_dump($array_chang);
    
    foreach ($array_chang as $pos_apersand => $substr) {
        
        $str=substr_replace($str, $substr, $pos_apersand, strlen($substr));
        
    }
    return $str;
}



?>