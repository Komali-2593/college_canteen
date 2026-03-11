<?php
include 'connect.php';

$sql = "SELECT * FROM orders";
$result = $conn->query($sql);

echo "<h1>Orders</h1>";

while($row = $result->fetch_assoc()) {

echo "Name: " . $row["name"]. " - Item: " . $row["item"]. " - Quantity: " . $row["quantity"]. "<br>";

}

$conn->close();
?>