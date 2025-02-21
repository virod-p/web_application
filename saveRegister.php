<?php
    require 'connection.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ตรวจสอบรูปแบบของอีเมล
        $email = $_POST["email"];
        $email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/"; // กำหนดรูปแบบของอีเมล

        if (!preg_match($email_pattern, $email)) {
            // หากอีเมลไม่ตรงกับรูปแบบที่กำหนด
            header("Location: register.php?msg=invalid_email_format");
            exit();
        }


        // ตรวจสอบว่าอีเมลถูกใช้งานแล้วหรือไม่
        $email = $_POST["email"];
        $query_check_email = "SELECT * FROM `users` WHERE `user_email` = '$email'";
        $result_check_email = mysqli_query($conn, $query_check_email);
        if (mysqli_num_rows($result_check_email) > 0) {
            // หากพบว่าอีเมลถูกใช้งานแล้ว
            header("Location: register.php?msg=email_exist");
            exit();
        } else {
            // หากไม่พบว่าอีเมลถูกใช้งานแล้ว คุณสามารถดำเนินการ INSERT ข้อมูลลงในฐานข้อมูลต่อไปได้
            $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $query_insert_user = "INSERT INTO `users`(`user_name`, `user_password`, `user_email`) VALUES ('{$_POST["name"]}', '$hashed_password', '$email')";
            if (mysqli_query($conn, $query_insert_user)) {

                $query = "SELECT * FROM `users` WHERE `user_email` = '$email'"; //หา email ของผู้ใช้ใน database
                $result = mysqli_query($conn, $query); // เก็บผลลัพธ์จากคำสั่ง query ไว้ใน $result
        
                if(mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_assoc($result);
                    // ตรวจสอบรหัสผ่าน
                    if(password_verify($_POST["password"], $row['user_password'])) {
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
                }
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['user_role'] = $row['user_role'];
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $query_insert_user . "<br>" . mysqli_error($conn);
            }
        }
    }
?>
