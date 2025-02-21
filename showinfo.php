<?php
require 'connection.php';
$sql="SELECT * FROM PersonInfo;";
$result = $conn->query($sql);

if($result->num_rows > 0){
    echo "<table border=1>";
    echo "<tr><td>no.</td><td>name</td><td>age</td><td>country</td><td>gender</td><td>language</td></tr>";
    while($row=$result->fetch_assoc()){
        echo "<tr><td>".$row['ID']."</td><td>".$row['Name']."</td><td>".$row['Age']."</td><td>".$row['Country']."</td><td>".$row['Gender']."</td><td>".$row['Language']."</td></tr>";
}
    echo "</table>";
}else{
    echo "0 rows available";
}
$conn->close();
?>