<?php
require 'connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// cancel order
echo "<center style='padding: 25px 0px 0px 0px;'><div class='container l-form'>";
if (isset($_POST['cancel'])) { // > delete order id ////////////////////////////////////////////////////////////////////////////////////////
	$sql = "DELETE FROM `order` WHERE order_token = '" . $_POST['order_token'] . "';";
	echo $sql . "<br>";
	$result = $conn->query($sql);
	header("location: orderList.php");
} else
	// type pay
	if (isset($_POST['allpay'])) { // > cash || qr ////////////////////////////////////////////////////////////////////////////////////////
		echo "<h1>Please select your payment method</h1>";

		echo "<form action='payment.php' method='post'>";
		echo "<input type='hidden' name='all' value='all'>";
		echo "<input type='hidden' name='user_id' value='" . $_POST['user_id'] . "'>";
		echo "<button style='padding: 20px 3vw; width: 200px; height: 100px;' type='submit' name='cash'>Cash</button>";
		echo "</form>";

		echo "<form action='payment.php' method='post'>";
		echo "<input type='hidden' name='all' value='all'>";
		echo "<input type='hidden' name='user_id' value='" . $_POST['user_id'] . "'>";
		echo "<button style='padding: 20px 3vw; width: 200px; height: 100px;' type='submit' name='qr'>QR</button>";
		echo "</form>";
	} else
if (isset($_POST['pay'])) { // > cash || qr ////////////////////////////////////////////////////////////////////////////////////////
		echo "<h1>Please select your payment method</h1>";

		echo "<form action='payment.php' method='post'>";
		echo "<input type='hidden' name='order_token' id='" . $_POST["order_token"] . "' value='" . $_POST["order_token"] . "'>";
		echo "<button style='padding: 20px 3vw; width: 200px; height: 100px;' type='submit' name='cash'>Cash</button>";
		echo "</form>";

		echo "<form action='payment.php' method='post'>";
		echo "<input type='hidden' name='order_token' id='" . $_POST["order_token"] . "' value='" . $_POST["order_token"] . "'>";
		echo "<button style='padding: 20px 3vw; width: 200px; height: 100px;' type='submit' name='qr'>QR</button>";
		echo "</form>";
	} else
		// type payment
		if (isset($_POST['cash'])) { // > confirm //////////////////////////////////////////// cash ////////////////////////////////////////////
			echo "<h1>Cash Payment</h1>";

			echo "<form action='payment.php' method='post'>";
			if (isset($_POST['order_token'])) {
				echo "<input type='hidden' name='order_token' id='" . $_POST["order_token"] . "' value='" . $_POST["order_token"] . "'>";
			}
			if (isset($_POST['all'])) {
				echo "<input type='hidden' name='user_id' value='" . $_POST['user_id'] . "'>";
				echo "<input type='hidden' name='all' id='all' value='all'>";
			}
			echo "<button style='padding: 20px 3vw;' type='submit' name='confirm'>Comfirm</button>";
			echo "</form>";
		} else
if (isset($_POST['qr'])) { // > confirm //////////////////////////////////////////// qr ////////////////////////////////////////////
			echo "<h1>QR Payment</h1><br>";

			echo "<img style='width: 400px;' src='static/qr.jpg' alt='QR Payment'>";

			echo "<form action='payment.php' method='post'>";
			if (isset($_POST['order_token'])) {
				echo "<input type='hidden' name='order_token' id='" . $_POST["order_token"] . "' value='" . $_POST["order_token"] . "'>";
			}
			if (isset($_POST['all'])) {
				echo "<input type='hidden' name='user_id' value='" . $_POST['user_id'] . "'>";
				echo "<input type='hidden' name='all' id='all' value='all'>";
			}
			echo "<button style='padding: 20px 3vw;' type='submit' name='confirm'>Comfirm</button>";
			echo "</form>";
		} else
			// confirm
			if (isset($_POST['confirm'])) { // > change status >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> confirm <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
				echo "<h1>Thank you please wait for your order</h1>";
				if (isset($_POST['order_token'])) {
                    if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
                    $_SESSION['token'] = $_POST['order_token'];
					$sql = "UPDATE `order` SET status = 'confirming' WHERE order_token = '" . $_POST["order_token"] . "'";
					$conn->query($sql);
					// ดึงข้อมูลคำสั่งซื้อ
					$query = "SELECT * FROM `order` WHERE order_token = '" . $_POST["order_token"] . "'";
					$result = $conn->query($query);
					$row = $result->fetch_assoc();
					// แยกข้อมูลการสั่งซื้อของผู้ใช้
					$veg_orders = explode(",", $row["veg_order"]);
					$meat_orders = explode(",", $row["meat_order"]);
					$topping_orders = explode(",", $row["topping_order"]);
					$egg_orders = explode(",", $row["egg_order"]);
					// ลดจำนวนสินค้าในสต็อกของข้าว
					$update_rice_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '" . $row["rice_name"] . "'";
					// echo $update_rice_stock_sql . "<br>";
					$conn->query($update_rice_stock_sql);
					// ลดจำนวนสินค้าในสต็อกของผัก
					foreach ($veg_orders as $veg) {
						$update_veg_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$veg'";
						// echo $update_veg_stock_sql . "<br>";
						$conn->query($update_veg_stock_sql);
					}
					// ลดจำนวนสินค้าในสต็อกของเนื้อ
					foreach ($meat_orders as $meat) {
						$update_meat_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$meat'";
						// echo $update_veg_stock_sql . "<br>";
						$conn->query($update_meat_stock_sql);
					}
					// ลดจำนวนสินค้าในสต็อกของ topping
					foreach ($topping_orders as $topping) {
						$update_topping_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$topping'";
						// echo $update_veg_stock_sql . "<br>";
						$conn->query($update_topping_stock_sql);
					}
					// ลดจำนวนสินค้าในสต็อกของไข่
					foreach ($egg_orders as $egg) {
						$update_egg_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$egg'";
						// echo $update_veg_stock_sql . "<br>";
						$conn->query($update_egg_stock_sql);
					}
                    echo "<form action='orderList.php?token=" . $_POST['order_token'] . "' method='post'>";
					echo "<button style='padding: 20px 3vw;' type='submit' name='back'>Back</button>";
					echo "</form>";
				} else if (isset($_POST['all'])) {
					$unpaid_orders_query = "SELECT * FROM `order` WHERE status = 'unpaid' AND user_id = '" . $_POST['user_id'] . "'";
					// echo $unpaid_orders_query . "<br>";
					$unpaid_orders_result = $conn->query($unpaid_orders_query);

					$sql = "UPDATE `order` SET status = 'confirming' WHERE status = 'unpaid' AND user_id = '" . $_POST['user_id'] . "'";
					// echo $sql . "<br>";
					$conn->query($sql);
					// ดึงรายการคำสั่งซื้อทั้งหมดที่มีสถานะเป็น "unpaid"

					while ($row = $unpaid_orders_result->fetch_assoc()) {
						// ดึงข้อมูลการสั่งซื้อ
						$veg_orders = explode(",", $row["veg_order"]);
						$meat_orders = explode(",", $row["meat_order"]);
						$topping_orders = explode(",", $row["topping_order"]);
						$egg_orders = explode(",", $row["egg_order"]);

						// ลดจำนวนสินค้าในสต็อกของข้าว
						$update_rice_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '" . $row["rice_name"] . "'";
						// echo $update_rice_stock_sql . "<br>";
						$conn->query($update_rice_stock_sql);

						// ลดจำนวนสินค้าในสต็อกของผัก
						foreach ($veg_orders as $veg) {
							$update_veg_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$veg'";
							// echo $update_veg_stock_sql . "<br>";
							$conn->query($update_veg_stock_sql);
						}

						// ลดจำนวนสินค้าในสต็อกของเนื้อ
						foreach ($meat_orders as $meat) {
							$update_meat_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$meat'";
							// echo $update_meat_stock_sql . "<br>";
							$conn->query($update_meat_stock_sql);
						}

						// ลดจำนวนสินค้าในสต็อกของ topping
						foreach ($topping_orders as $topping) {
							$update_topping_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$topping'";
							// echo $update_topping_stock_sql . "<br>";
							$conn->query($update_topping_stock_sql);
						}

						// ลดจำนวนสินค้าในสต็อกของไข่
						foreach ($egg_orders as $egg) {
							$update_egg_stock_sql = "UPDATE `ingredients` SET stock = stock - 1 WHERE name = '$egg'";
							// echo $update_egg_stock_sql . "<br>";
							$conn->query($update_egg_stock_sql);
						}
					}
                    echo "<form action='payment.php' method='post'>";
					echo "<button style='padding: 20px 3vw;' type='submit' name='back'>Back</button>";
					echo "</form>";
				}
			} else if (isset($_POST['back'])) {
				header("location: orderList.php");
			} else {
				header("location: orderList.php");
			}
echo "</div></center>";
?>

</body>

</html>