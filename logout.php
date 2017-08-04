<?php
session_start();

if(!$_SESSION['user']=='admin' || !$_SESSION['user']=='staff'){
        
header("Location: index.php");



}

session_destroy(); /* Destroy started session */

header("location:index.php");
exit;
?>
