<?php
session_start();
$conn = new mysqli("localhost", "root", "", "food_ordering");

$name = $_SESSION['customer_name'];
$phone = $_SESSION['customer_phone'];
$table = $_SESSION['customer_table'];

// Get latest status from the most recent order
$sql = "SELECT status FROM orders WHERE name=? AND phone=? AND table_number=? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $name, $phone, $table);
$stmt->execute();
$result = $stmt->get_result();

$status = "";
if ($row = $result->fetch_assoc()) {
    $status = $row['status'];
}

echo json_encode(["status" => $status]);
?>
