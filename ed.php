<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

// Delete product
if (isset($_GET['delete_product'])) {
    $id = intval($_GET['delete_product']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin.php");
    exit;
}

// Fetch products
$products = $conn->query("SELECT * FROM products");
?>

<h2>🛠️ Admin Panel - Manage Products</h2>
<p><a href="add.php"><button>➕ Add New Product</button></a></p>

<table border="1" cellpadding="10">
  <tr>
    <th>Image</th><th>Name</th><th>Price</th><th>Description</th><th>Action</th>
  </tr>
  <?php while($row = $products->fetch_assoc()): ?>
    <tr>
      <td><img src="<?= htmlspecialchars($row['image_url']) ?>" width="80" height="60"></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td>$<?= $row['price'] ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><a href="?delete_product=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">🗑️ Delete</a></td>
    </tr>
  <?php endwhile; ?>
</table>
