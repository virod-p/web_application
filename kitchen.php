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

// confirm order
if(isset($_POST['confirm'])){
    $sql = "UPDATE `order` SET status = 'cooking' WHERE order_token = '" . $_POST["order_token"] . "'";
	$conn->query($sql);
    header("location: kitchen.php");
}
// serve order
if(isset($_POST['serve'])){
    $sql = "UPDATE `order` SET status = 'success' WHERE order_token = '" . $_POST["order_token"] . "'";
	$conn->query($sql);
    header("location: kitchen.php");
}

echo "<center><div class='container l-form'>";
if(isset($_SESSION['user_id'])){
    if(!($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'chef')){
        header('location: index.php');
    }

    // แสดงข้อมูลในตะกร้า ของลูกค้าที่ login
	$id = $_SESSION['user_id'];
	$sql = "SELECT * FROM `order` WHERE (status = 'confirming' OR status = 'cooking') ORDER BY order_id ASC;";
	//echo "$sql<br>";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) { // order overiew vvvvvvvv //
		$qConfirming = "SELECT count(*) as confirming FROM `order` WHERE status = 'confirming';";
		$qCooking = "SELECT count(*) as cooking FROM `order` WHERE status = 'cooking';";
		$rConfirming = $conn->query($qConfirming);
		$rCooking = $conn->query($qCooking);
		$rowConfirming = $rConfirming->fetch_assoc();
		$rowCooking = $rCooking->fetch_assoc();
		$rowOrder = $rowConfirming['confirming'] + $rowCooking['cooking'];
		echo "<table><tr>";
		echo "<th>Confirming</th><th>Cooking</th><th>Total</th></tr><tr>";
		echo "<td>" . $rowConfirming['confirming'] . "</td><td>" . $rowCooking['cooking'] . "</td><td>" . $rowOrder . "</td>";
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

			$veg_orders = explode(",", $row["veg_order"]);
			$meat_orders = explode(",", $row["meat_order"]);
			$topping_orders = explode(",", $row["topping_order"]);
			$egg_orders = explode(",", $row["egg_order"]);

			echo "<table>";
			echo "<tr> <th colspan='5'>Order Details</th></tr>";
			echo "<tr>";
			echo "<th>rice</th>";
			echo "<th>veg";
			if ($veg_orders[0] != null) {
				echo  " (" . count($veg_orders) . ")";
			}
			echo "</th>";
			echo "<th>meat";
			if ($meat_orders[0] != null) {
				echo  " (" . count($meat_orders) . ")";
			}
			echo "</th>";
			echo "<th>topping";
			if ($topping_orders[0] != null) {
				echo  " (" . count($topping_orders) . ")";
			}
			echo "</th>";
			echo "<th>egg";
			if ($egg_orders[0] != null) {
				echo  " (" . count($egg_orders) . ")";
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
	if ($row["status"] == 'confirming') {
				echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
				echo "  <th>
                        <center style='padding: 25px 0px 0px 0px;'>
                            <form action='kitchen.php' method='post'>
                                <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                <button style='padding: 20px 5vw;' title='Confirm this order.' type='submit' name='confirm'>Confirm</button>
                            </form>
                        </center>
                        </th>";
			} else {
				echo "<th colspan='4'>Total Price: ฿ " . $row['total_price'] . "</th>";
				echo "  <th>
                        <center style='padding: 25px 0px 0px 0px;'>
                            <form action='kitchen.php' method='post'>
                                <input type='hidden' name='order_token' id='" . $row["order_token"] . "' value='" . $row["order_token"] . "'>
                                <button style='padding: 20px 5vw;' title='Serve this order.' type='submit' name='serve'>Serve</button>
                            </form>
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

} else {
    header('location: index.php');
}
echo "</div></center>";
?>

</body>

</html>