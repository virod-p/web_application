<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require 'connection.php';
    if(isset($_SESSION['otpCheck'])){
        if($_SESSION['otpCheck'] == true){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $url_safe_email = $_GET['email'];
                $decoded_email = urldecode($url_safe_email); 
                $email = base64_decode($decoded_email);
                $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $query_change_password = "UPDATE users SET user_password = '$hashed_password' WHERE user_email = '$email';";
                if (mysqli_query($conn, $query_change_password)) {
                    $_SESSION['otpCheck'] = false;
                    header("Location: login.php?changePassword=success&e=$email&p=$password");
                    exit();
                }
            }
        } else {
            header("Location: index.php");
        }
    } else {
        header("Location: index.php");
    }
?>
