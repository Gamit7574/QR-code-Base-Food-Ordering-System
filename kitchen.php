<?php
session_start();
if (!isset($_SESSION['kitchen_logged_in']) || $_SESSION['kitchen_logged_in'] !== true) {
    header("Location: kitchen_login.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: kitchen_login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "food_ordering");

// Handle status changes
if (isset($_GET['prepared']) && is_numeric($_GET['prepared'])) {
    $orderId = intval($_GET['prepared']);
    $conn->query("UPDATE orders SET status='prepared' WHERE id=$orderId");
}

if (isset($_GET['ready']) && is_numeric($_GET['ready'])) {
    $orderId = intval($_GET['ready']);
    $conn->query("UPDATE orders SET status='ready' WHERE id=$orderId");
}

// Fetch all non-completed orders
$sql = "SELECT * FROM orders WHERE status IN ('pending', 'prepared') ORDER BY created_at ASC";
$result = $conn->query($sql);

$orders = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['table_number'] . "_" . $row['phone'];
    $orders[$key]['info'] = [
        'name' => $row['name'],
        'phone' => $row['phone'],
        'table' => $row['table_number']
    ];
    $orders[$key]['items'][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 30px;
        }
        .order-card {
            margin-bottom: 30px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn-prepared {
            background-color: #ffc107;
            color: black;
        }
        .btn-ready {
            background-color: #28a745;
            color: white;
        }
        .btn-prepared:hover {
            background-color: #e0a800;
        }
        .btn-ready:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>🍽 Kitchen Panel – Active Orders</h2>
        <a href="?logout=true" class="btn btn-danger">🚪 Logout</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">No active orders.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($orders as $group): ?>
                <div class="col-md-6 order-card">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            🪑 Table <?= $group['info']['table'] ?> — <?= htmlspecialchars($group['info']['name']) ?> (<?= $group['info']['phone'] ?>)
                        </div>
                        <div class="card-body p-0">
                            <table class="table mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($group['items'] as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td>₹<?= number_format($item['price'], 2) ?></td>
                                        <td><?= htmlspecialchars($item['description']) ?></td>
                                        <td>
                                            <span class="badge <?= $item['status'] == 'pending' ? 'bg-warning text-dark' : 'bg-success' ?>">
                                                <?= ucfirst($item['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($item['status'] == 'pending'): ?>
                                                <a href="?prepared=<?= $item['id'] ?>" class="btn btn-sm btn-prepared">Prepared</a>
                                            <?php elseif ($item['status'] == 'prepared'): ?>
                                                <a href="?ready=<?= $item['id'] ?>" class="btn btn-sm btn-ready">Ready</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
}, 5000); // every 5 seconds
</script>

</body>
</html>
