<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");

$selected_date = $_GET['date'] ?? date('Y-m-d');
$sql = "SELECT product_name, price, created_at FROM orders 
        WHERE status = 'completed' AND DATE(created_at) = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
    $total += $row['price'];
}
// Store the report in `daily_reports` if not already stored
$check = $conn->prepare("SELECT id FROM daily_reports WHERE report_date = ?");
$check->bind_param("s", $selected_date);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO daily_reports (report_date, total_revenue) VALUES (?, ?)");
    $insert->bind_param("sd", $selected_date, $total);
    $insert->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📊 Daily Report</h2>
        <a href="admin.php" class="btn btn-secondary">⬅️ Back to Admin Panel</a>
    </div>

    <form class="row g-3 mb-4" method="get">
        <div class="col-auto">
            <label for="date" class="col-form-label">Select Date:</label>
        </div>
        <div class="col-auto">
            <input type="date" name="date" id="date" class="form-control" value="<?= $selected_date ?>" required>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit">📅 Show Report</button>
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-dark" onclick="window.print()">🖨️ Print</button>
        </div>
    </form>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">No completed orders found for <?= htmlspecialchars($selected_date) ?>.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Price (₹)</th>
                        <th>Ordered Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $index => $order): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= number_format($order['price'], 2) ?></td>
                        <td><?= date("h:i A", strtotime($order['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-dark fw-bold">
                        <td colspan="2">Total Revenue</td>
                        <td colspan="2">₹<?= number_format($total, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
