<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
}
$_SESSION = [];
$_SESSION['token'] = $token;
header("location: index.php");
?>