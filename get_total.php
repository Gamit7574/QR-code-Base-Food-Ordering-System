<?php
session_start();
if (!isset($_SESSION['customer_table'])) {
    echo json_encode(['success' => false]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");
$table = intval($_SESSION['customer_table']);
$result = $conn->query("SELECT SUM(price) AS total FROM orders WHERE table_number = $table AND status != 'completed'");

$row = $result->fetch_assoc();
$total = $row['total'] ?? 0;

echo json_encode(['success' => true, 'total' => floatval($total)]);
