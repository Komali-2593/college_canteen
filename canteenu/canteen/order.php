<?php

include 'connect.php';

$name = $_POST['name'];
$item = $_POST['item'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO orders (name,item,quantity)
VALUES ('$name','$item','$quantity')";

if ($conn->query($sql) === TRUE) {
echo "Order placed successfully!";
} else {
echo "Error: " . $conn->error;
}

$conn->close();

?>