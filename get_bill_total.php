<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "food_ordering");

if ($conn->connect_error) {
    echo json_encode(['total' => 0]);
    exit;
}

$table = isset($_GET['table']) ? intval($_GET['table']) : 0;
$total = 0;

$result = $conn->query("SELECT SUM(price) AS total FROM orders WHERE table_number = $table AND status != 'completed'");
if ($result && $row = $result->fetch_assoc()) {
    $total = number_format($row['total'], 2, '.', '');
}

echo json_encode(['total' => $total]);
