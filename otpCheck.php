<?php
require 'connection.php';
echo "1<br>";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่จำเป็นส่งมาหรือไม่
    echo "2<br>";
    if(isset($_POST["otp"])) {
        // เก็บค่าที่รับมาจากฟอร์ม
        echo "3<br>";
        $otp = mysqli_real_escape_string($conn, $_POST["otp"]);
        echo "4<br>";
        // ค้นหาข้อมูลของผู้ใช้จากฐานข้อมูล
        $query = "SELECT * FROM `users` WHERE `otp` = '$otp'"; //หา email ของผู้ใช้ใน database
        echo "5<br>";
        $result = mysqli_query($conn, $query); // เก็บ email ไว้ใน $result
        echo "6<br>";
        // if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            echo "7<br>";
            // ตรวจสอบรหัสผ่าน
            if($otp == $row['otp']) {
                echo "8<br>";
                // การเข้าสู่ระบบสำเร็จ
                $url_safe_email = $_GET['email'];
                $decoded_email = urldecode($url_safe_email); 
                $email = base64_decode($decoded_email);
                echo "9<br>";
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                echo "10<br>";
                $_SESSION['otpCheck'] = true;
                $sql = "UPDATE users SET otp = NULL WHERE user_email = '$email'";
                mysqli_query($conn, $sql); // ลบ otp ใน database// ตั้งค่า session สำหรับแสดงว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
                echo "11<br>";
                header("Location: changePassword.php?email=$url_safe_email"); 
                exit();
            } else {
                // รหัสผ่านไม่ถูกต้อง
                header("Location: otpConfirm.php?email=$url_safe_email&msg=otp_error");
                exit();
            }
        // } else {
        //     // ไม่พบผู้ใช้งานในระบบ
        //     header("Location: login.php");
        //     exit();
        // }
    } else {
        // กรอกข้อมูลไม่ครบ
        header("Location: otpConfirm.php?email=$url_safe_email&msg=missing_otp");
        exit();
    }
}
echo "12<br>";
?>
