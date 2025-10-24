<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "food_ordering";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2Factor API Key
define('API_KEY', '55b12a50-1183-11f0-8b17-0200cd936042');
?>
