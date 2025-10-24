<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

$table = intval($_GET['table']);
$result = $conn->query("SELECT * FROM orders WHERE table_number = $table");

$items = [];
$info = ['name' => '', 'phone' => '', 'table' => $table];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $info['name'] = $row['name'];
    $info['phone'] = $row['phone'];
    $total += $row['price'];
}
$gst = $total * 0.05;
$cst = $total * 0.02;
$grand = $total + $gst + $cst;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print Bill - Table <?= $table ?></title>
    <style>
        body { font-family: Arial; padding: 30px; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #343a40; color: white; }
        .totals { font-weight: bold; background: #f8f9fa; }
    </style>
</head>
<body onload="window.print()">

<h2>🧾 Bill for Table <?= $info['table'] ?></h2>
<p><strong>Name:</strong> <?= htmlspecialchars($info['name']) ?><br>
   <strong>Phone:</strong> <?= htmlspecialchars($info['phone']) ?></p>

<table>
    <tr><th>Item</th><th>Price</th><th>Note</th></tr>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td>₹<?= number_format($item['price'], 2) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
        </tr>
    <?php endforeach; ?>
    <tr class="totals"><td>Subtotal</td><td colspan="2">₹<?= number_format($total, 2) ?></td></tr>
    <tr class="totals"><td>GST (5%)</td><td colspan="2">₹<?= number_format($gst, 2) ?></td></tr>
    <tr class="totals"><td>CST (2%)</td><td colspan="2">₹<?= number_format($cst, 2) ?></td></tr>
    <tr class="totals"><td>Grand Total</td><td colspan="2">₹<?= number_format($grand, 2) ?></td></tr>
</table>

</body>
</html>
