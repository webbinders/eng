<?php
session_start();
$_SESSION = array();
$_COOKIE = array();
header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/joining.php');
?>

