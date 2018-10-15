<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Подключаемся к серверу
include "server_connect.php";
$query = "SELECT * FROM thesaurus WHERE id IN (10,11,12,13,14,15,16,17,18,19,20,1,2,3,4,5,6,7,8,9)";
 $result = queryRun($query,'Error in $query');
 $i=1;
  while ($row = mysql_fetch_array($result,MYSQL_ASSOC)){
      if ($i != $row['id'])
      echo $row['id'].'<br>';
      $i++;
  }
  $for12 = "1363,1389,1502,1505,1914,2081,2259,2286,2385,2477";
  $querystr ="SELECT * FROM thesaurus WHERE id IN (1363,1389,1502,1505,1914,2081,2259,2286,2385,2477) ORDER BY `id` ASC";
  $query2 = "SELECT * FROM thesaurus WHERE examples LIKE '%1502%' ";
  function checkerId($examples){
      $checkedId = '';
      $query = "SELECT * FROM thesaurus WHERE id IN ($examples)";
      $result = queryRun($query,'Error in $query');
      while ($row = mysql_fetch_array($result,MYSQL_ASSOC)){
          $checkedId .= $row['id'].',';
      }
      $checkedId = substr($checkedId, 0,-1);
      return $checkedId;
  }