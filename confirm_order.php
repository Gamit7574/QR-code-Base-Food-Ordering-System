<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food_ordering");

$name = $_SESSION['customer_name'];
$phone = $_SESSION['customer_phone'];
$table_number = $_SESSION['customer_table'];
$cart = $_SESSION['cart'] ?? [];

if (!empty($cart)) {
    $stmt = $conn->prepare("INSERT INTO orders (product_name, price, description, name, phone, table_number) VALUES (?, ?, ?, ?, ?, ?)");
   $conn->query("UPDATE order_updates SET last_update = NOW() WHERE id = 1");

    foreach ($cart as $item) {
        $stmt->bind_param("sdsssi", $item['product'], $item['price'], $item['description'], $name, $phone, $table_number);
        $stmt->execute();
    }

    unset($_SESSION['cart']); // clear cart after confirming
}

header("Location: index.php");
exit();
?>
