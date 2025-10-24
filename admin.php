<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");

if (isset($_GET['cancel_id'])) {
    $id = intval($_GET['cancel_id']);
    $conn->query("DELETE FROM orders WHERE id = $id");
    header("Location: admin.php");
    exit;
}

if (isset($_GET['confirm_bill'])) {
    $table = intval($_GET['confirm_bill']);
    $conn->query("UPDATE orders SET status='completed' WHERE table_number = $table");
    header("Location: print.php?table=$table");
    exit;
}

$sql = "SELECT * FROM orders WHERE status != 'completed' ORDER BY table_number, created_at ASC";
$result = $conn->query($sql);

$grouped = [];
while ($row = $result->fetch_assoc()) {
    $grouped[$row['table_number']]['info'] = [
        'name' => $row['name'],
        'phone' => $row['phone'],
        'table' => $row['table_number']
    ];
    $grouped[$row['table_number']]['items'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .order-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🍽 Admin Panel - Customer Orders</h2>
        <a href="?logout=true" class="btn btn-outline-danger">🚪 Logout</a>
    </div>

    <div class="mb-4 d-flex gap-2">
    <a href="add.php" class="btn btn-primary">➕ Add Items</a>
    <a href="report.php" class="btn btn-outline-dark">📊 Today's Report</a>
    <a href="report_history.php" class="btn btn-outline-success">🗂️ View All Reports</a>
</div>



    <?php if (empty($grouped)): ?>
        <div class="alert alert-info">No active orders at the moment.</div>
    <?php else: ?>
        <?php foreach ($grouped as $table => $group): ?>
            <div class="order-card">
                <h4 class="mb-3">🪑 Table <?= $table ?> — <?= htmlspecialchars($group['info']['name']) ?> (<?= $group['info']['phone'] ?>)</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Note</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subtotal = 0;
                            foreach ($group['items'] as $item): 
                                $subtotal += $item['price'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td>₹<?= number_format($item['price'], 2) ?></td>
                                <td><?= htmlspecialchars($item['description']) ?></td>
                                <td><span class="badge bg-secondary"><?= ucfirst($item['status']) ?></span></td>
                                <td>
                                    <a href="admin.php?cancel_id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this item?')">❌ Cancel</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <?php 
                                $gst = $subtotal * 0.05;
                                $cst = $subtotal * 0.02;
                                $grandTotal = $subtotal + $gst + $cst;
                            ?>
                            <tr><th>Subtotal</th><td colspan="4">₹<?= number_format($subtotal, 2) ?></td></tr>
                            <tr><th>GST (5%)</th><td colspan="4">₹<?= number_format($gst, 2) ?></td></tr>
                            <tr><th>CST (2%)</th><td colspan="4">₹<?= number_format($cst, 2) ?></td></tr>
                            <tr class="table-success fw-bold"><th>Grand Total</th><td colspan="4">₹<?= number_format($grandTotal, 2) ?></td></tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a href="print.php?table=<?= $table ?>" class="btn btn-outline-info" target="_blank">🖨️ Print Bill</a>
                    <a href="admin.php?confirm_bill=<?= $table ?>" class="btn btn-success" onclick="return confirm('Confirm and mark as completed?')">✅ Confirm Bill</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
let lastUpdate = 0;
setInterval(() => {
    fetch('check_update.php')
        .then(res => res.json())
        .then(data => {
            if (data.timestamp > lastUpdate) {
                lastUpdate = data.timestamp;
                location.reload();
            }
        });
}, 5000);
</script>

</body>
</html>
