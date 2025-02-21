<?php
    require 'connection.php';

echo "<center><h1><strong>processing please wait...</strong></h1></center>";

// ตรวจสอบว่ามีการส่งแบบฟอร์มหรือไม่
if(isset($_POST["submit"])){
    $rice_price = $_POST["rice_price"];
    $veg_priceArr = $_POST["veg_price"];
    $meat_priceArr = $_POST["meat_price"];
    $topping_priceArr = $_POST["topping_price"];
    $egg_priceArr = $_POST["egg_price"];

    $veg_price = null;
    $meat_price = null;
    $topping_price = null;
    $egg_price = null;

    // รับค่าจากฟอร์ม
    $rice_name = $_POST["rice_name"];
    $veg_orders = $_POST["veg_orders"];
    $meat_orders = $_POST["meat_orders"];
    $topping_orders = $_POST["topping_orders"];
    $egg_orders = $_POST["egg_orders"];

    // start debug part <-----------------------------

    $sql = "SELECT * FROM ingredients";
    $result = $conn->query($sql);

    // ตรวจสอบว่ามีข้อมูลในผลลัพธ์หรือไม่
    if ($result->num_rows > 0) {
        // แสดงผลรายการสินค้าตามหมวดหมู่
        echo "<h2>Rice</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Price</th></tr>";
        echo "<tr><td>$rice_name</td><td>";
        
        // ค้นหาราคาของข้าว
        while($row = $result->fetch_assoc()) {
            if($row["name"] == $rice_name) {
                echo $row["price"];
                $rice_price = $row["price"];
                break;
            }
        }
        echo "</td></tr></table>";
        
        // แสดงผลรายการสินค้าแต่ละหมวดหมู่
        $categories = ["Veggies", "Meats", "Toppings", "Egg"];
        $orders = [$veg_orders, $meat_orders, $topping_orders, $egg_orders];
        
        // วนลูปผ่านหมวดหมู่และแสดงผลสินค้า
        for($i = 0; $i < count($categories); $i++) {
            echo "<h2>$categories[$i]</h2>";
            echo "<table border='1'>";
            echo "<tr><th>Name</th><th>Price</th></tr>";
            
            // วนลูปผ่านรายการสินค้าในแต่ละหมวดหมู่
            foreach($orders[$i] as $order) {
                echo "<tr>";
                echo "<td>$order</td><td>";
                
                // ค้นหาราคาของสินค้า
                $result->data_seek(0);
                while($row = $result->fetch_assoc()) {
                    if($row["name"] == $order) {
                        echo $row["price"];
                        break;
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    $sql = "SELECT name, price FROM ingredients";
    $result = $conn->query($sql);
    // ตรวจสอบว่ามีข้อมูลในผลลัพธ์หรือไม่
    if ($result->num_rows > 0) {
        // วนลูปผ่านข้อมูลเพื่อเปรียบเทียบชื่อสินค้าและราคา
        while($row = $result->fetch_assoc()) {
            // เช็ครายการของผู้ใช้และราคาของสินค้าแต่ละรายการ
            if($rice_name == $row["name"]) {
                $total_price += $row["price"];
            }
            foreach($veg_orders as $veg) {
                if($veg == $row["name"]) {
                    $total_price += $row["price"];
                    $veg_price .= $veg_priceArr[$row["name"]];
                    $veg_price .= ",";
                }
            }
            foreach($meat_orders as $meat) {
                if($meat == $row["name"]) {
                    $total_price += $row["price"];
                    $meat_price .= $meat_priceArr[$row["name"]];
                    $meat_price .= ",";
                }
            }
            foreach($topping_orders as $topping) {
                if($topping == $row["name"]) {
                    $total_price += $row["price"];
                    $topping_price .= $topping_priceArr[$row["name"]];
                    $topping_price .= ",";
                }
            }
            foreach($egg_orders as $egg) {
                if($egg == $row["name"]) {
                    $total_price += $row["price"];
                    $egg_price .= $egg_priceArr[$row["name"]];
                    $egg_price .= ",";
                }
            }
        }
    }

    // แสดงผลราคารวม
    echo "<table border='1'>";
    echo "<tr><th>Total price</th></tr>";
    echo "<tr><td>" . $total_price ."</td></tr>";
    echo "</table>";

    // end debug part <-----------------------------

    // เพิ่มข้อมูลการสั่งซื้อลงในฐานข้อมูล สร้าง string โดยเอา array มา
    $veg_order = null;
    $meat_order = null;
    $topping_order = null;
    $egg_order = null;
    
    if($veg_orders != ""){
        $count = count($veg_orders); // นับจำนวนสมาชิกใน $veg_orders
        $i = 0; // เริ่มต้นค่า index ที่ 0
        foreach($veg_orders as $row){
            $veg_order .= $row; // เพิ่ม $row เข้าไปใน $veg_order
            if($i < $count - 1){ // ตรวจสอบว่าไม่ใช่ตัวสุดท้ายของลูป
                $veg_order .= ","; // เพิ่มเครื่องหมาย "," หากไม่ใช่ตัวสุดท้าย
            }
            $i++; // เพิ่มค่า index ขึ้นไป 1
        }
    }

    if($meat_orders != ""){
        $count = count($meat_orders); // นับจำนวนสมาชิกใน $meat_orders
        $i = 0; // เริ่มต้นค่า index ที่ 0
        foreach($meat_orders as $row){
            $meat_order .= $row; // เพิ่ม $row เข้าไปใน $meat_order
            if($i < $count - 1){ // ตรวจสอบว่าไม่ใช่ตัวสุดท้ายของลูป
                $meat_order .= ","; // เพิ่มเครื่องหมาย "," หากไม่ใช่ตัวสุดท้าย
            }
            $i++; // เพิ่มค่า index ขึ้นไป 1
        }
    }

    if($topping_orders != ""){
        $count = count($topping_orders); // นับจำนวนสมาชิกใน $topping_orders
        $i = 0; // เริ่มต้นค่า index ที่ 0
        foreach($topping_orders as $row){
            $topping_order .= $row; // เพิ่ม $row เข้าไปใน $topping_order
            if($i < $count - 1){ // ตรวจสอบว่าไม่ใช่ตัวสุดท้ายของลูป
                $topping_order .= ","; // เพิ่มเครื่องหมาย "," หากไม่ใช่ตัวสุดท้าย
            }
            $i++; // เพิ่มค่า index ขึ้นไป 1
        }
    }

    if($egg_orders != ""){
        $count = count($egg_orders); // นับจำนวนสมาชิกใน $egg_orders
        $i = 0; // เริ่มต้นค่า index ที่ 0
        foreach($egg_orders as $row){
            $egg_order .= $row; // เพิ่ม $row เข้าไปใน $egg_order
            if($i < $count - 1){ // ตรวจสอบว่าไม่ใช่ตัวสุดท้ายของลูป
                $egg_order .= ","; // เพิ่มเครื่องหมาย "," หากไม่ใช่ตัวสุดท้าย
            }
            $i++; // เพิ่มค่า index ขึ้นไป 1
        }
    }

    session_start();
    echo "0<br>";
    $order_token = genRandToken();
    if(isset($_SESSION['user_id'])){ // ตรวจสอบว่า login หรือไหม่
        echo "1<br>";
        $id = $_SESSION['user_id'];
        $query = "INSERT INTO `order` ( user_id, order_token, 
                                        rice_name, 
                                        veg_order, 
                                        meat_order, 
                                        topping_order, 
                                        egg_order, 
                                        total_price) 
                                VALUES (". $id .", '$order_token', 
                                        '$rice_name', 
                                        '" . $veg_order . "', 
                                        '" . $meat_order . "', 
                                        '" . $topping_order . "', 
                                        '" . $egg_order . "', 
                                        $total_price)";
        echo "<br>" . $query . "<br><br>1.5<br>";
        mysqli_query($conn, $query);
        echo "<form id='autoSubmit' action='index.php?msg=success' method='post'>";
            echo "<input type='hidden' name='token' value='$order_token'>";
        echo "</form>";
        echo "<script>window.onload = function() {document.getElementById('autoSubmit').submit();}</script>";
        // header("location: index.php?msg=success");
    } else {
        echo "2<br>";
        $query = "INSERT INTO `order` ( order_token, 
                                        rice_name, 
                                        veg_order, 
                                        meat_order, 
                                        topping_order, 
                                        egg_order, 
                                        total_price) 
                                VALUES ('$order_token', 
                                        '$rice_name', 
                                        '" . $veg_order . "', 
                                        '" . $meat_order . "', 
                                        '" . $topping_order . "', 
                                        '" . $egg_order . "', 
                                        $total_price)";
        echo "<br>" . $query . "<br><br>2.5<br>";
        mysqli_query($conn, $query);
        echo "<form id='autoSubmit' action='index.php?msg=success' method='post'>";
            echo "<input type='hidden' name='token' value='$order_token'>";
        echo "</form>";
        echo "<script>window.onload = function() {document.getElementById('autoSubmit').submit();}</script>";
        // header("location: index.php?msg=success");
    }
    // $rice_price = $_POST["rice_price"];
    // $veg_price = $_POST["veg_price"];
    // $meat_price = $_POST["meat_price"];
    // $topping_price = $_POST["topping_price"];
    // $egg_price = $_POST["egg_price"];
} else {
    header("location: index.php?msg=failed");
}
echo "3<br>";

// function
function genRandToken($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>
</body>
</html>
