<?php
    $host = 'localhost'; // MySQL host name
    $user = 'root';      // MySQL username
    $pass = '';          // MySQL password
    $db   = 'khawpad';   // MySQL database
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    try {
        // พยายามเชื่อมต่อ MySQL
        $conn = mysqli_connect($host, $user, $pass, $db);

        // ถ้าเชื่อมต่อสำเร็จ
        if ($conn) {
            echo "<span></span>";
            if (isset($_SESSION['user_id'])) {
                // echo "Welcome {$_SESSION['user_name']}!<br>";
                // echo "ID: {$_SESSION['user_id']}<br>";
                // echo "Role: {$_SESSION['user_role']}<br>";
            } else {
                // echo "Welcome guest<br>";
            }
            // ทำงานอื่นๆ ที่ต้องการทำต่อไป
        } else {
            // ถ้าเชื่อมต่อไม่สำเร็จ
            echo "Connection failed!<br>";
            // ทำงานอื่นๆ ที่ต้องการทำต่อไป
        }
    } catch (mysqli_sql_exception $e) {
        // จัดการข้อผิดพลาดเมื่อเกิดข้อผิดพลาดในการเชื่อมต่อ MySQL
        echo "Connection failed: " . $e->getMessage() . "<br>";
        // ทำงานอื่นๆ ที่ต้องการทำต่อไป
    }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .goog-te-gadget-simple {
            padding: 1px;
            width: 250px; height: 40px;
            font-size: 25px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-------------------------[ navbar ]------------------------------->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php"><img src='static/icon/fried-rice.png' alt="fried-rice" style='width: 10vw; height: auto;margin-right: 30px;' /><span style='font-size: 3vw'>Fried Rice!</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style='width: 10vw; height: 10vw;'></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"> <!-------------------------[ Google Translate ]------------------------------->
                    <div style="margin: 15px 7px 0px 0px;">
                        <div id="google_translate_element"></div>
                        <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                        <script type="text/javascript">
                            function googleTranslateElementInit() {
                                new google.translate.TranslateElement({
                                    pageLanguage: 'en',
                                    includedLanguages: 'th,ja,zh-CN,zh-TW,ko',
                                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                                }, 'google_translate_element');
                            };
                            googleTranslateElementInit();
                        </script>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orderList.php">Orders</a>
                </li>
                <?php
                    echo "<li class='nav-item'>";
                    if (isset($_SESSION["user_role"])) {
                        if ($_SESSION['user_role'] == 'chef') {
                            echo "<a class='nav-link' href='kitchen.php'>Kitchen</a>";
                        }
                        echo "</li>";
                        echo "<li class='nav-item'>";
                        if ($_SESSION['user_role'] == 'admin') {
                            echo "<a class='nav-link' href='kitchen.php'>Kitchen</a>";
                        }
                        echo "</li>";
                        echo "<li class='nav-item'>";
                        if ($_SESSION['user_role'] == 'admin') {
                            echo "<a class='nav-link' href='admin.php'>Admin</a>";
                        }
                        echo "</li>";
                    }
                ?>
                <li class="nav-item">
                    <?php
                        if (isset($_SESSION['user_id'])) {
                            echo "<a class='nav-link' href='logout.php'>Logout</a>";
                        } else {
                            echo "<a class='nav-link' href='login.php'>Login</a>";
                        }
                    ?>
                </li>
                <li class="nav-item">
                    <?php
                        if (isset($_SESSION['user_id'])) {
                            echo "<a style='color: white;' class='nav-link'>[" . $_SESSION['user_name'] . ":" . $_SESSION['user_id'] . "]</a>";
                        }
                    ?>
                </li>
            </ul>
        </div>
    </nav>
    <!-------------------------[ end navbar ]------------------------------->
    <!-- html, body end tag have on other file -->
