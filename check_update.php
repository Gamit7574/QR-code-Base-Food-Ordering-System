<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

$result = $conn->query("SELECT UNIX_TIMESTAMP(last_update) as updated FROM order_updates WHERE id = 1");
$row = $result->fetch_assoc();

echo json_encode(['timestamp' => $row['updated']]);
?>
