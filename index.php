<?php
    // if (! empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    //     $uri = 'https://';
    // } else {
    //     $uri = 'http://';
    // }
    // $uri .= $_SERVER['HTTP_HOST'];
    // header('Location: ' . $uri . '/dashboard/');
    // exit;
?>
<!-- Something is wrong with the XAMPP installation :-( -->

<?php
    require 'connection.php';
?>
    <div class="container-fluid l-form">
            <center>
            <?php
                if (isset($_POST['remove_token'])) {
                    if (isset($_SESSION['token'])) {
                        $_SESSION['token'] = null;
                    }
                    header("location: index.php");
                }
                if (isset($_POST['save_token']) && isset($_POST['token'])) {
                    $_SESSION['token'] = $_POST['token'];
                    header("location: index.php");
                }
                if (isset($_GET["msg"])) {
                    if ($_GET['msg'] == "") {
                        echo "<div class='alert alert-info'>";
                        echo "<strong>Please select your order!</strong>";
                        if (isset($_POST['back']) && $_POST['token'] != "") {
                            echo " Your last order token: <strong><a href=orderList.php?token=" . $_POST['token'] . ">" . $_POST['token'] . "</a></strong>";
                        } else if (isset($_SESSION['token'])) {
                            $token = $_SESSION['token'];
                            echo "<form style='margin: 0px;' action='index.php' name='remove_token' method='post'> Your last order token on this session: <strong><a href=orderList.php?token=" . $token . ">" . $token . "</a> </strong>";
                            echo "<button style='padding: 2px 10px;' type='submit' name='remove_token'>Remove</button></form>";
                        }
                        echo "</div>";
                    }
                    if ($_GET['msg'] == "success") {
                        echo "<div class='alert alert-success'>";
                        if (isset($_SESSION['user_id'])) {
                            echo "<strong>Submit Success! </strong>Your order token: <strong><a href=orderList.php?token=" . $_POST['token'] . ">" . $_POST['token'] . "</a></strong>";
                            $_SESSION['token'] = $_POST['token'];
                        } else {
                            echo "<strong>Submit Success! </strong>Your order token: <strong><a href=orderList.php?token=" . $_POST['token'] . ">" . $_POST['token'] . "</a></strong>";
                            $_SESSION['token'] = $_POST['token'];
                        }
                        echo "</div>";
                    }
                    if ($_GET['msg'] == "failed") {
                        echo "<div class='alert alert-danger'>";
                        echo "<strong>Submit Failed!</strong>";
                        echo "</div>";
                    }
                }
            ?>
            <form action="confirmOrder.php" method="post" autocomplete="off">
            </center>
            <div class="container-fluid">
                <div class="l-form">
                    <table>
                        <tr>
                            <th class="th-head" colspan="5">
                                <h1>Rice type</h1>
                            </th>
                        </tr>
                        <tr>
                            <th class='pi-o'>Picture</th>
                            <th class='na-o'>Name</th>
                            <th class='pr-o'>Price</th>
                            <th class='st-o'>Stock</th>
                            <th class='ch-o'></th>
                        </tr>
                        <?php
                            if ($conn->connect_error) {
                                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
                            }
                            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าที่ถูกเลือก
                            $sql    = "SELECT * FROM ingredients WHERE type = 'rice'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr onclick='document.getElementById(\"" . $row["name"] . "\").click();'>";
                                    echo "<td class='pi-o'><img src='static/" . $row["type"] . "/" . $row["name"] . ".jpg' style = 'width:100px; height:100px;'/></td>";
                                    echo "<td class='na-o'>" . $row["s_name"] . "</td>";
                                    echo "<td class='pr-o'>฿" . $row["price"] . "</td>";
                                    echo "<td class='st-o'>" . $row["stock"] . "</td>";
                                    echo "<td class='ch-o'><input type='radio' name='" . $row["type"] . "_name' value='" . $row["name"] . "' id='" . $row["name"] . "' style='width: 50px; height: 50px;padding: 0px 50px 0px;' onclick='event.stopPropagation();' required></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No item</td></tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>

            <div class="container-fluid">
                <div class="l-form">
                    <table>
                        <tr>
                            <th class="th-head" colspan="5">
                                <h1>Veggies</h1>
                            </th>
                        </tr>
                        <tr>
                            <th class='pi-o'>Picture</th>
                            <th class='na-o'>Name</th>
                            <th class='pr-o'>Price</th>
                            <th class='st-o'>Stock</th>
                            <th class='ch-o'></th>
                        </tr>
                        <?php
                            if ($conn->connect_error) {
                                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
                            }
                            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าที่ถูกเลือก
                            $sql    = "SELECT * FROM ingredients WHERE type = 'veg'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr onclick='document.getElementById(\"" . $row["name"] . "\").click();'>";
                                    echo "<td class='pi-o'><img src='static/" . $row["type"] . "/" . $row["name"] . ".jpg' style = 'width:100px; height:100px;'/></td>";
                                    echo "<td class='na-o'>" . $row["s_name"] . "</td>";
                                    echo "<td class='pr-o'>฿" . $row["price"] . "</td>";
                                    echo "<td class='st-o'>" . $row["stock"] . "</td>";
                                    echo "<td class='ch-o'><input type='checkbox' name='" . $row["type"] . "_orders[]' value='" . $row["name"] . "' id='" . $row["name"] . "' style='width: 50px; height: 50px;' onclick='event.stopPropagation();'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No item</td></tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>

            <div class="container-fluid">
                <div class="l-form">
                    <table>
                        <tr>
                            <th class="th-head" colspan="5">
                                <h1>Meats</h1>
                            </th>
                        </tr>
                        <tr>
                            <th class='pi-o'>Picture</th>
                            <th class='pa-o'>Name</th>
                            <th class='pr-o'>Price</th>
                            <th class='st-o'>Stock</th>
                            <th class='ch-o'></th>
                        </tr>
                        <?php
                            if ($conn->connect_error) {
                                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
                            }
                            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าที่ถูกเลือก
                            $sql    = "SELECT * FROM ingredients WHERE type = 'meat'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr onclick='document.getElementById(\"" . $row["name"] . "\").click();'>";
                                    echo "<td class='pi-o'><img src='static/" . $row["type"] . "/" . $row["name"] . ".jpg' style = 'width:100px; height:100px;'/></td>";
                                    echo "<td class='na-o'>" . $row["s_name"] . "</td>";
                                    echo "<td class='pr-o'>฿" . $row["price"] . "</td>";
                                    echo "<td class='st-o'>" . $row["stock"] . "</td>";
                                    echo "<td class='ch-o'><input type='checkbox' name='" . $row["type"] . "_orders[]' value='" . $row["name"] . "' id='" . $row["name"] . "' style='width: 50px; height: 50px;' onclick='event.stopPropagation();'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No item</td></tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>

            <div class="container-fluid">
                <div class="l-form">
                    <table>
                        <tr>
                            <th class="th-head" colspan="5">
                                <h1>Toppings</h1>
                            </th>
                        </tr>
                        <tr>
                            <th class='pi-o'>Picture</th>
                            <th class='na-o'>Name</th>
                            <th class='pr-o'>Price</th>
                            <th class='st-o'>Stock</th>
                            <th class='ch-o'></th>
                        </tr>
                        <?php
                            if ($conn->connect_error) {
                                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
                            }
                            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าที่ถูกเลือก
                            $sql    = "SELECT * FROM ingredients WHERE type = 'topping'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr onclick='document.getElementById(\"" . $row["name"] . "\").click();'>";
                                    echo "<td class='pi-o'><img src='static/" . $row["type"] . "/" . $row["name"] . ".jpg' style = 'width:100px; height:100px;'/></td>";
                                    echo "<td class='na-o'>" . $row["s_name"] . "</td>";
                                    echo "<td class='pr-o'>฿" . $row["price"] . "</td>";
                                    echo "<td class='st-o'>" . $row["stock"] . "</td>";
                                    echo "<td class='ch-o'><input type='checkbox' name='" . $row["type"] . "_orders[]' value='" . $row["name"] . "' id='" . $row["name"] . "' style='width: 50px; height: 50px;' onclick='event.stopPropagation();'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No item</td></tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>

            <div class="container-fluid">
                <div class="l-form">
                    <table>
                        <tr>
                            <th class="th-head" colspan="5">
                                <h1>Egg</h1>
                            </th>
                        </tr>
                        <tr>
                            <th class='pi-o'>Picture</th>
                            <th class='na-o'>Name</th>
                            <th class='pr-o'>Price</th>
                            <th class='st-o'>Stock</th>
                            <th class='ch-o'></th>
                        </tr>
                        <?php
                            if ($conn->connect_error) {
                                die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
                            }
                            // สร้างคำสั่ง SQL เพื่อดึงข้อมูลสินค้าที่ถูกเลือก
                            $sql    = "SELECT * FROM ingredients WHERE type = 'egg'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr onclick='document.getElementById(\"" . $row["name"] . "\").click();'>";
                                    echo "<td class='pi-o'><img src='static/" . $row["type"] . "/" . $row["name"] . ".jpg' style = 'width:100px; height:100px;'/></td>";
                                    echo "<td class='na-o'>" . $row["s_name"] . "</td>";
                                    echo "<td class='pr-o'>฿" . $row["price"] . "</td>";
                                    echo "<td class='st-o'>" . $row["stock"] . "</td>";
                                    echo "<td class='ch-o'><input type='checkbox' name='" . $row["type"] . "_orders[]' value='" . $row["name"] . "' id='" . $row["name"] . "' style='width: 50px; height: 50px;' onclick='event.stopPropagation();'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No item</td></tr>";
                            }
                        ?>
                    </table>
                </div>
            </div>
            <br>
            <center>
                <button type="submit" name="submit">Submit</button>
            </center>
        </form>
    </div>
    <hr>
</body>
</html>