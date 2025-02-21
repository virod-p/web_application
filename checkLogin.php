<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลที่จำเป็นส่งมาหรือไม่
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        // เก็บค่าที่รับมาจากฟอร์ม
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = $_POST["password"];
        
        // ค้นหาข้อมูลของผู้ใช้จากฐานข้อมูล
        $query = "SELECT * FROM `users` WHERE `user_email` = '$email'"; //หา email ของผู้ใช้ใน database
        $result = mysqli_query($conn, $query); // เก็บ email ไว้ใน $result
        
        if(mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            // ตรวจสอบรหัสผ่าน
            if(password_verify($password, $row['user_password'])) {
                // การเข้าสู่ระบบสำเร็จ
                session_start();
                $_SESSION['user_name'] = $row['user_name'];
                $_SESSION['user_id'] = $row['user_id']; // ตั้งค่า session สำหรับแสดงว่าผู้ใช้ได้เข้าสู่ระบบแล้ว
                $_SESSION['user_role'] = $row['user_role'];
                header("Location: index.php"); // และเปลี่ยนเส้นทางไปยังหน้า dashboard หรือหน้าที่ต้องการหลังจาก login
                exit();
            } else {
                // รหัสผ่านไม่ถูกต้อง
                header("Location: login.php?msg=password_error");
                exit();
            }
        } else {
            // ไม่พบผู้ใช้งานในระบบ
            header("Location: login.php?msg=user_not_found");
            exit();
        }
    } else {
        // กรอกข้อมูลไม่ครบ
        header("Location: login.php?msg=missing_data");
        exit();
    }
}
?>
