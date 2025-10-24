<?php
session_start();

if (!isset($_SESSION['customer_name'])) {
    header("Location: customerdetails.php");
    exit();
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #007bff; color: white; }
        .remove-btn, .order-btn, .back-btn {
            padding: 10px 20px; border: none; color: #fff; cursor: pointer;
            margin-right: 10px; border-radius: 5px;
        }
        .remove-btn { background-color: #dc3545; }
        .order-btn { background-color: #28a745; }
        .back-btn { background-color: #17a2b8; }
    </style>
</head>
<body>

<h2>Your Cart</h2>

<?php if (empty($cart)): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <table>
    <tr><th>Product</th><th>Price (₹)</th><th>Description</th><th>Action</th></tr>
    <?php foreach ($cart as $index => $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['product']) ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= htmlspecialchars($item['description']) ?></td>
            <td><a href="remove_item.php?index=<?= $index ?>" class="remove-btn">Remove</a></td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td colspan="4" style="text-align:right; font-weight:bold;">Total: ₹<?= number_format(array_sum(array_column($cart, 'price')), 2) ?></td>
    </tr>
</table>


    <form method="POST" action="confirm_order.php">
        <button type="submit" class="order-btn">✅ Confirm Order</button>
    </form>
<?php endif; ?>

<br>
<a href="index.php"><button class="back-btn">⬅️ Back to Menu</button></a>

</body>
</html>
