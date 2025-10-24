<?php
session_start();

$data = json_decode(file_get_contents("php://input"));

$product = $data->product;
$price = $data->price;
$description = $data->description;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'][] = [
    'product' => $product,
    'price' => $price,
    'description' => $description
];

echo "Item added to cart.";
?>
