<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['customer_name']) || !isset($_SESSION['customer_table'])) {
    echo json_encode(['sent' => false]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");

$table = intval($_SESSION['customer_table']);
$result = $conn->query("SELECT bill_sent FROM bill_flags WHERE table_number = $table");

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(['sent' => (bool)$row['bill_sent']]);
} else {
    echo json_encode(['sent' => false]);
}
