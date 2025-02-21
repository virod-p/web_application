<?php
require 'connection.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
echo "<center><div class='container-fluid l-form'>";
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] != 'admin') {
        header('location: index.php');
    }

    // เพิ่มข้อมูลใหม่ใน ingredients
    if (isset($_POST['add_ingredient'])) {
        // ตรวจสอบว่ามีข้อมูลที่ส่งมาจากฟอร์มหรือไม่
        if (isset($_POST['type']) && isset($_POST['name']) && isset($_POST['s_name']) && isset($_POST['price']) && isset($_POST['stock'])) {
            // ดึงค่าจากฟอร์ม
            $type = $_POST['type'];
            $name = $_POST['name'];
            $s_name = $_POST['s_name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            // เพิ่มข้อมูลใหม่ลงในตาราง ingredients
            $sql = "INSERT INTO `ingredients` (type, name, s_name, price, stock) VALUES ('$type', '$name', '$s_name', '$price', '$stock')";
            $conn->query($sql);
        }
    }

    // ลบข้อมูลใหม่ใน ingredients
    if (isset($_POST['remove_ingredient'])) {
        // ตรวจสอบว่ามีข้อมูลที่ส่งมาจากฟอร์มหรือไม่
        if (isset($_POST['name'])) {
            // ดึงค่าจากฟอร์ม
            $name = $_POST['name'];
            // เพิ่มข้อมูลใหม่ลงในตาราง ingredients
            $sql = "DELETE FROM `ingredients` WHERE name = '" . $name . "'";
            $conn->query($sql);
        }
    }

    // เพิ่มจำนวนสินค้าใน stock ของสินค้าที่มีอยู่
    if (isset($_POST['change_stock'])) {
        // ตรวจสอบว่ามีข้อมูลที่ส่งมาจากฟอร์มหรือไม่
        if (isset($_POST['ingredient_id']) && isset($_POST['additional_stock'])) {
            // ดึงค่าจากฟอร์ม
            $ingredient_id = $_POST['ingredient_id'];
            $additional_stock = $_POST['additional_stock'];

            // เพิ่มจำนวนสินค้าใน stock ของสินค้าที่มีอยู่
            $sql = "UPDATE `ingredients` SET stock = $additional_stock WHERE ingredient_id = $ingredient_id";
            $conn->query($sql);
        }
    }

    // เปลี่ยนราคาสินค้า
    if (isset($_POST['change_price'])) {
        // ตรวจสอบว่ามีข้อมูลที่ส่งมาจากฟอร์มหรือไม่
        if (isset($_POST['ingredient_id']) && isset($_POST['new_price'])) {
            // ดึงค่าจากฟอร์ม
            $ingredient_id = $_POST['ingredient_id'];
            $new_price = $_POST['new_price'];

            // เปลี่ยนราคาของสินค้า
            $sql = "UPDATE `ingredients` SET price = $new_price WHERE ingredient_id = $ingredient_id";
            $conn->query($sql);
        }
    }

    // ดึงรายการ order ทั้งหมด
    $order_query = "SELECT * FROM `order` ORDER BY order_id DESC";
    $order_result = $conn->query($order_query);
    ?>

    <style>
        th {
            background-color: transparent;
            text-align: right;
        }
        tr:hover {
            background-color: transparent;
        }
        td {
            width: 400px;
        }
        /* table,th,td{border: 1px solid black} */
    </style>

    <form class="container l-form" action="" method="post"> <!-- change stock -->
        <h1>Change Stock</h1>
        <table>
            <tr>
                <th>
                    <label for="ingredient_id">Ingredient:</label>
                </th>
                <td>
                    <select name="ingredient_id" id="ingredient_id" required>
                    <?php
                    // ดึงรายการวัตถุดิบทั้งหมดจากฐานข้อมูล
                    $ingredient_query = "SELECT * FROM `ingredients` ORDER BY type";
                    $ingredient_result = $conn->query($ingredient_query);
                    // แสดงตัวเลือกสำหรับเลือกวัตถุดิบ
                    while ($ingredient = $ingredient_result->fetch_assoc()) {
                        echo "<option value='" . $ingredient['ingredient_id'] . "'>[" . $ingredient['type'] . "] " . $ingredient['name'] . " [Stock: " . $ingredient['stock'] . "]</option>";
                    }
                    ?>
                    </select><br>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="change_stock">Change Stock:</label>
                </th>
                <td>
                    <input type="number" id="change_stock" name="change_stock" required><br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit" name="change_stock">Change</button>
                </td>
            </tr>
        </table>
    </form>
    
    <form class="container l-form" action="" method="post"> <!-- change price -->
        <h1>Change Price</h1>
        <table>
        <tr>
        <th>
        <label for="ingredient_id">Ingredient:</label>
        </th>
        <td>
        <select name="ingredient_id" id="ingredient_id" required>
        <?php
        // ดึงรายการวัตถุดิบทั้งหมดจากฐานข้อมูล
        $ingredient_query = "SELECT * FROM `ingredients` ORDER BY type";
        $ingredient_result = $conn->query($ingredient_query);
        // แสดงตัวเลือกสำหรับเลือกวัตถุดิบ
        while ($ingredient = $ingredient_result->fetch_assoc()) {
            echo "<option value='" . $ingredient['ingredient_id'] . "'>[" . $ingredient['type'] . "] " . $ingredient['name'] . " ฿" . $ingredient['price'] . "</option>";
        }
        ?>
        </select><br>
        </td>
        </tr>
        <tr>
        <th>
        <label for="new_price">New Price:</label>
        </th>
        <td>
        <input type="number" id="new_price" name="new_price" required><br>
        </td>
        </tr>
        <tr>
        <td colspan="2" style="text-align: center;">
        <button type="submit" name="change_price">Change</button>
        </td>
        </tr>
        </table>
    </form>

    <form class="container l-form" action="" method="post"> <!-- add ingredient -->
        <h1>Add New Ingredient</h1>
        <table>
        <tr>
        <th>
        <label for="type">Type:</label>
        </th>
        <td>
        <input type="text" id="type" name="type" required><br>
        </td>
        </tr>
        <tr>
        <th>
        <label for="name">Name:</label>
        </th>
        <td>
        <input type="text" id="name" name="name" required><br>
        </td>
        </tr>
        <tr>
        <th>
        <label for="s_name">Display Name:</label>
        </th>
        <td>
        <input type="text" id="s_name" name="s_name" required><br>
        </td>
        </tr>
        <tr>
        <th>
        <label for="price">Price:</label>
        </th>
        <td>
        <input type="number" id="price" name="price" required><br>
        </td>
        </tr>
        <tr>
        <th>
        <label for="stock">Stock:</label>
        </th>
        <td>
        <input type="number" id="stock" name="stock" required><br>
        </td>
        </tr>
        <tr>
        <td colspan="2" style="text-align: center;">
            <button type="submit" name="add_ingredient">Add</button>
        </td>
        </tr>
        </table>
    </form>

    <form class="container l-form" action="" method="post"> <!-- change price -->
        <h1>Remove Ingredient</h1>
        <table>
            <tr>
                <th>
                    <label for="ingredient_id">Ingredient:</label>
                </th>
                <td>
                    <select name="ingredient_id" id="ingredient_id" required>
                        <?php
                        // ดึงรายการวัตถุดิบทั้งหมดจากฐานข้อมูล
                        $ingredient_query = "SELECT * FROM `ingredients` ORDER BY type";
                        $ingredient_result = $conn->query($ingredient_query);
                        // แสดงตัวเลือกสำหรับเลือกวัตถุดิบ
                        while ($ingredient = $ingredient_result->fetch_assoc()) {
                            echo "<option value='" . $ingredient['ingredient_id'] . "'>[" . $ingredient['type'] . "] " . $ingredient['name'] . " [id: " . $ingredient['ingredient_id'] . "]</option>";
                        }
                    ?>
                    </select><br>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <button type="submit" name="remove_ingredient">Remove</button>
                </td>
            </tr>
        </table>
    </form>

    <div class="container l-form" style="text-align: center;"> <!-- all order -->
        <table border="1">
        <h1>All Orders</h1>
        <tr>
            <th style="text-align: left; width: auto">Order ID</th>
            <th style="text-align: left; width: auto">User ID</th>
            <th style="text-align: left; width: auto">Order Token</th>
            <th style="text-align: left; width: auto">Order Date</th>
            <th style="text-align: left; width: auto">Status</th>
        <!--<th style="text-align: left; width: auto">Rice Name</th>
            <th style="text-align: left; width: auto">Veg Order</th>
            <th style="text-align: left; width: auto">Meat Order</th>
            <th style="text-align: left; width: auto">Topping Order</th>
            <th style="text-align: left; width: auto">Egg Order</th>-->
            <th style="text-align: left; width: auto">Total Price</th>
        </tr>
        <?php
        // แสดงรายการ order ทั้งหมด
        while ($order = $order_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td style='text-align: left; width: auto'>" . $order['order_id'] . "</td>";
        echo "<td style='text-align: left; width: auto'>" . $order['user_id'] . "</td>";
        echo "<td style='text-align: left; width: auto'>" . $order['order_token'] . "</td>";
        echo "<td style='text-align: left; width: auto'>" . $order['order_date'] . "</td>";
        echo "<td style='text-align: left; width: auto'>" . $order['status'] . "</td>";
        // echo "<td style='text-align: left; width: auto'>" . $order['rice_name'] . "</td>";
        // echo "<td style='text-align: left; width: auto'>" . $order['veg_order'] . "</td>";
        // echo "<td style='text-align: left; width: auto'>" . $order['meat_order'] . "</td>";
        // echo "<td style='text-align: left; width: auto'>" . $order['topping_order'] . "</td>";
        // echo "<td style='text-align: left; width: auto'>" . $order['egg_order'] . "</td>";
        echo "<td style='text-align: left; width: auto'>" . $order['total_price'] . "</td>";
        echo "</tr>";
        }
        ?>
        </table>
    </div>

    </body>

    </html>

    <?php
} else {
    header('location: index.php');
}
echo "</div></center>";
?>
