<?php
    require 'connection.php';
?>

<head>
	<style>
		th,
		td {
			width: 20%;
		}
	</style>
</head>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_GET['token'])) {
        echo "<form action='index.php?token=" . $_GET['token'] . "' method='post'>";
        echo "<input type='hidden' name='token' value='" . $_GET['token'] . "'>";
    } else if (isset($_POST['token'])) {
        echo "<form action='index.php?token=" . $_GET['token'] . "' method='post'>";
        echo "<input type='hidden' name='token' value='" . $_GET['token'] . "'>";
    } else {
        echo "<form action='index.php' method='post'>";
    }
    echo "<button style='margin: 30px;' type='submit' name='back'>BACK</button>";
    echo "</form>";
    echo "<center>";
    echo "<div class='container l-form'>";
    if (isset($_SESSION['user_id'])) { // logged in
                                           // echo "<a class='nav-link' href='logout.php'>Logout</a>";
                                           // แสดงข้อมูลในตะกร้า ของลูกค้าที่ login
        $id  = $_SESSION['user_id'];
        $sql = "SELECT * FROM `order` WHERE user_id = $id ORDER BY order_id DESC;";
        //echo "$sql<br>";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) { // order overiew vvvvvvvv //
            $qUnpaid   = "SELECT count(*) as unpaid FROM `order` WHERE user_id = $id AND status = 'unpaid';";
            $qPaid     = "SELECT count(*) as paid FROM `order` WHERE user_id = $id AND (status = 'confirming' OR status = 'cooking' OR status = 'success');";
            $rUnpaid   = $conn->query($qUnpaid);
            $rPaid     = $conn->query($qPaid);
            $rowUnpaid = $rUnpaid->fetch_assoc();
            $rowPaid   = $rPaid->fetch_assoc();
            $rowOrder  = $rowUnpaid['unpaid'] + $rowPaid['paid'];
            echo "<table><tr>";
            echo "<th>Not yet paid</th><th>Paid</th><th>Total</th></tr><tr>";
            echo "<td>" . $rowUnpaid['unpaid'] . "</td><td>" . $rowPaid['paid'] . "</td><td>" . $rowOrder . "</td>";
            if ($rowUnpaid['unpaid'] > 0) {
                echo "  <tr><td colspan='3'>
                            <center style='padding: 25px 0px 0px 0px;'>
                                <form action='payment.php' method='post'>
                                    <input type='hidden' name='user_id' value='" . $id . "'>
                                    <button style='padding: 20px 3vw;' title='Make payment this order.' type='submit' name='allpay'>Make payment for all order</button>
                                </form>
                            </center>
                        </td></tr>";
            }
            echo "</tr></table>";

            // order part vvvvvvvv //

            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<hr><br>";
                echo "<table>";
                echo "<tr> <th>Order ID</th> <th>Order Token</th> <th>Order Date</th> <th>Status</th>";
                if ($row["status"] == 'unpaid') {
                    echo "<th>Cancel Order</th>";
                }
                echo "</tr>";
                echo "<tr>";
                echo "<td>" . $row["order_id"] . "</td>";
                echo "<td>" . $row["order_token"] . "</td>";
                echo "<td>" . $row["order_date"] . "</td>";
                if ($row["status"] == 'unpaid') {
                    echo "<td style='background-color: red;color: white;'>Not paid</td>";
                    echo "  <td>
                                <center style='padding: 25px 0px 0px 0px;'>
                                    <form action='payment.php' method='post'>
                                        <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                        <button style='padding: 20px 3vw;' title='Cancel this order.' type='submit' name='cancel'>Cancel</button>
                                    </form>
                                </center>
                            </td>";
                } else if ($row["status"] == 'confirming') { // this mean paid // waiting for confirm
                    echo "<td style='background-color: orange;color: white;'>Confirming</td>";
                } else if ($row["status"] == 'cooking') { // this mean paid // confirmed
                    echo "<td style='background-color: yellow;color: black;'>Cooking</td>";
                } else if ($row["status"] == 'success') {
                    echo "<td style='background-color: Green;color: white;'>Success</td>";
                } else {
                    echo "<td style='background-color: black;color: white;'>Unknow<br>[" . $row["status"] . "]</td>";
                }
                echo "</tr>";
                echo "</table>";

                $veg_orders     = explode(",", $row["veg_order"]);
                $meat_orders    = explode(",", $row["meat_order"]);
                $topping_orders = explode(",", $row["topping_order"]);
                $egg_orders     = explode(",", $row["egg_order"]);

                echo "<table>";
                echo "<tr> <th colspan='5'>Order Details</th></tr>";
                echo "<tr>";
                echo "<th>rice</th>";
                echo "<th>veg";
                if ($veg_orders[0] != null) {
                    echo " (" . count($veg_orders) . ")";
                }
                echo "</th>";
                echo "<th>meat";
                if ($meat_orders[0] != null) {
                    echo " (" . count($meat_orders) . ")";
                }
                echo "</th>";
                echo "<th>topping";
                if ($topping_orders[0] != null) {
                    echo " (" . count($topping_orders) . ")";
                }
                echo "</th>";
                echo "<th>egg";
                if ($egg_orders[0] != null) {
                    echo " (" . count($egg_orders) . ")";
                }
                echo "</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<td>";
                $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $row["rice_name"] . "'";
                $qname = $conn->query($query);
                $rname = $qname->fetch_assoc();
                echo "<img src='static/rice/" . $row["rice_name"] . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                echo "</td>";
                echo "<td>";
                if ($veg_orders[0] != null) {
                    foreach ($veg_orders as $var) {
                        $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                        $qname = $conn->query($query);
                        $rname = $qname->fetch_assoc();
                        echo "<img src='static/veg/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                    }
                } else {
                    echo "No item";
                }
                echo "</td>";
                echo "<td>";
                if ($meat_orders[0] != null) {
                    foreach ($meat_orders as $var) {
                        $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                        $qname = $conn->query($query);
                        $rname = $qname->fetch_assoc();
                        echo "<img src='static/meat/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                    }
                } else {
                    echo "No item";
                }
                echo "</td>";
                echo "<td>";
                if ($topping_orders[0] != null) {
                    foreach ($topping_orders as $var) {
                        $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                        $qname = $conn->query($query);
                        $rname = $qname->fetch_assoc();
                        echo "<img src='static/topping/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                    }
                } else {
                    echo "No item";
                }
                echo "</td>";
                echo "<td>";
                if ($egg_orders[0] != null) {
                    foreach ($egg_orders as $var) {
                        $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                        $qname = $conn->query($query);
                        $rname = $qname->fetch_assoc();
                        echo "<img src='static/egg/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                    }
                } else {
                    echo "No item";
                }
                echo "</td>";
                echo "</tr>";
                echo "<tr>";
                if ($row["status"] == 'unpaid') {
                    echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
                    echo "  <th>
                        <center style='padding: 25px 0px 0px 0px;'>
                            <form action='payment.php' method='post'>
                                <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                <button style='padding: 20px 5vw;' title='Make payment this order.' type='submit' name='pay'>Pay</button>
                            </form>
                        </center>
                        </th>";
                } else {
                    echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
                    echo "  <th>
                        <center style='padding: 25px 0px;'>
                                <button style='background-color: gray;padding: 20px 5vw;' type='submit'>Paid</button>
                        </center>
                        </th>";
                }
                echo "</tr>";
                echo "</table>";
                echo "<br>";
            }
        } else {
            echo "No order";
        }
    } else {                                              // not login /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                                              // echo "<p>";
        $token = isset($_GET['token']) ? $_GET['token'] : ''; // ถ้าไม่มี token ให้ใช้ค่าเป็น ''
        echo "<form action='orderList.php?token=" . htmlspecialchars($token) . "' method='post'>";
        echo "<p>Search your order by token.</p>";
        echo "<input type='text' name='token' id='myInput' value='" . htmlspecialchars($token) . "'>";
        echo "<button style='padding: 3px 20px;' type='submit' name='search'>Search</button>";
        echo "</form>";
        // echo "</p>";
        if (isset($_POST['search'])) {
            $sql = "SELECT * FROM `order` WHERE order_token = '" . $_POST['token'] . "';";
            //echo "$sql<br>";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<hr><br>";
                    echo "<table>";
                    echo "<tr> <th>Order ID</th> <th>Order Token</th> <th>Order Date</th> <th>Status</th>";
                    if ($row["status"] == 'unpaid') {
                        echo "<th>Cancel Order</th>";
                    }
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["order_token"] . "</td>";
                    echo "<td>" . $row["order_date"] . "</td>";
                    if ($row["status"] == 'unpaid') {
                        echo "<td style='background-color: red;color: white;'>Not paid</td>";
                        echo "  <td>
                                <center style='padding: 25px 0px 0px 0px;'>
                                    <form action='payment.php' method='post'>
                                        <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                        <button style='padding: 20px 3vw;' title='Cancel this order.' type='submit' name='cancel'>Cancel</button>
                                    </form>
                                </center>
                            </td>";
                    } else if ($row["status"] == 'confirming') { // this mean paid // waiting for confirm
                        echo "<td style='background-color: orange;color: white;'>Confirming</td>";
                    } else if ($row["status"] == 'cooking') { // this mean paid // confirmed
                        echo "<td style='background-color: yellow;color: black;'>Cooking</td>";
                    } else if ($row["status"] == 'success') {
                        echo "<td style='background-color: Green;color: white;'>Success</td>";
                    } else {
                        echo "<td style='background-color: black;color: white;'>Unknow<br>[" . $row["status"] . "]</td>";
                    }
                    echo "</tr>";
                    echo "</table>";

                    $veg_orders     = explode(",", $row["veg_order"]);
                    $meat_orders    = explode(",", $row["meat_order"]);
                    $topping_orders = explode(",", $row["topping_order"]);
                    $egg_orders     = explode(",", $row["egg_order"]);

                    echo "<table>";
                    echo "<tr> <th colspan='5'>Order Details</th></tr>";
                    echo "<tr>";
                    echo "<th>rice</th>";
                    echo "<th>veg";
                    if ($veg_orders[0] != null) {
                        echo " (" . count($veg_orders) . ")";
                    }
                    echo "</th>";
                    echo "<th>meat";
                    if ($meat_orders[0] != null) {
                        echo " (" . count($meat_orders) . ")";
                    }
                    echo "</th>";
                    echo "<th>topping";
                    if ($topping_orders[0] != null) {
                        echo " (" . count($topping_orders) . ")";
                    }
                    echo "</th>";
                    echo "<th>egg";
                    if ($egg_orders[0] != null) {
                        echo " (" . count($egg_orders) . ")";
                    }
                    echo "</th>";
                    echo "</tr>";
                    echo "<tr>";
                    echo "<td>";
                    $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $row["rice_name"] . "'";
                    $qname = $conn->query($query);
                    $rname = $qname->fetch_assoc();
                    echo "<img src='static/rice/" . $row["rice_name"] . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                    echo "</td>";
                    echo "<td>";
                    if ($veg_orders[0] != null) {
                        foreach ($veg_orders as $var) {
                            $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                            $qname = $conn->query($query);
                            $rname = $qname->fetch_assoc();
                            echo "<img src='static/veg/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                        }
                    } else {
                        echo "No item";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($meat_orders[0] != null) {
                        foreach ($meat_orders as $var) {
                            $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                            $qname = $conn->query($query);
                            $rname = $qname->fetch_assoc();
                            echo "<img src='static/meat/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                        }
                    } else {
                        echo "No item";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($topping_orders[0] != null) {
                        foreach ($topping_orders as $var) {
                            $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                            $qname = $conn->query($query);
                            $rname = $qname->fetch_assoc();
                            echo "<img src='static/topping/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                        }
                    } else {
                        echo "No item";
                    }
                    echo "</td>";
                    echo "<td>";
                    if ($egg_orders[0] != null) {
                        foreach ($egg_orders as $var) {
                            $query = "SELECT s_name, price  FROM `ingredients` WHERE name ='" . $var . "'";
                            $qname = $conn->query($query);
                            $rname = $qname->fetch_assoc();
                            echo "<img src='static/egg/" . $var . ".jpg' style='width:50px; height:50px;'/>" . $rname["s_name"] . " [฿" . $rname["price"] . "]<br>";
                        }
                    } else {
                        echo "No item";
                    }
                    echo "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    if ($row["status"] == 'unpaid') {
                        echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
                        echo "  <th>
                        <center style='padding: 25px 0px 0px 0px;'>
                            <form action='payment.php' method='post'>
                                <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                <button style='padding: 20px 5vw;' title='Make payment this order.' type='submit' name='pay'>Pay</button>
                            </form>
                        </center>
                        </th>";
                    } else {
                        echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
                        echo "  <th>
                        <center style='padding: 25px 0px;'>
                                <button style='background-color: gray;padding: 20px 5vw;' type='submit'>Paid</button>
                        </center>
                        </th>";
                    }
                    echo "</tr>";
                    echo "</table>";
                    echo "<br>";
                }
            } else {
                echo "Order not found.";
            }
        } else {
            echo "Please enter your order token.";
        }
    }
    echo "</div>";
    echo "</center>";
?>

</body>

</html>