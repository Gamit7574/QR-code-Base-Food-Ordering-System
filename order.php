<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food_ordering");

$data = json_decode(file_get_contents("php://input"));

$product = $data->product;
$price = $data->price;
$description = $data->description;

// Add customer session data
$name = $_SESSION['customer_name'];
$phone = $_SESSION['customer_phone'];
$table_number = $_SESSION['customer_table'];

$stmt = $conn->prepare("INSERT INTO orders (product_name, price, description, name, phone, table_number) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdsssi", $product, $price, $description, $name, $phone, $table_number);
$stmt->execute();

echo "Order added successfully.";
?>
