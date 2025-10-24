<?php
session_start();
if (!isset($_SESSION['customer_table'])) {
    echo "Unauthorized";
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");
$table = intval($_SESSION['customer_table']);

// Update order status
$conn->query("UPDATE orders SET status = 'completed' WHERE table_number = $table");

// Optional: Mark bill sent
$conn->query("REPLACE INTO bill_flags (table_number, bill_sent) VALUES ($table, 1)");

echo "Thank you! Your payment has been recorded.";
