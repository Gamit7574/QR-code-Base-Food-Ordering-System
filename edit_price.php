<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$updated = false;

// Update price logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPrice = (float) $_POST['price'];
    $conn->query("UPDATE products SET price = $newPrice WHERE id = $id");
    $updated = true;
}

// Fetch product
$result = $conn->query("SELECT * FROM products WHERE id = $id");
$product = $result->fetch_assoc();
if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Price</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <a href="remove.php" class="btn btn-outline-primary mb-4">🔙 Back to Items List</a>

    <div class="card p-4">
        <h3>Edit Price for: <?php echo htmlspecialchars($product['name']); ?></h3>
        <?php if ($updated): ?>
            <div class="alert alert-success">Price updated successfully!</div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label>New Price (₹):</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
            </div>
            <button type="submit" class="btn btn-success">💾 Save</button>
        </form>
    </div>
</div>
</body>
</html>
