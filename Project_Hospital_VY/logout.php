<?php
session_start();
//drop sesion and redirect to login.php"
$_SESSION = array();
 
session_destroy();
 
header("location: login.php");
exit;
?>