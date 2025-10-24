<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    // Get image path to delete from server
    $result = $conn->query("SELECT image_url FROM products WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        $imagePath = $row['image_url'];
        if (file_exists($imagePath)) {
            unlink($imagePath); // delete image file
        }
    }

    // Delete from database
    $conn->query("DELETE FROM products WHERE id = $id");

    // Redirect
    header("Location: remove.php");
    exit;
}

// Search query
$searchQuery = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Total products count
$totalResult = $conn->query(
    $searchQuery ?
    "SELECT COUNT(*) AS total FROM products WHERE name LIKE '%$searchQuery%'" :
    "SELECT COUNT(*) AS total FROM products"
);
$totalRow = $totalResult->fetch_assoc();
$totalCount = $totalRow['total'];

// Fetch products
$result = $conn->query(
    $searchQuery ?
    "SELECT * FROM products WHERE name LIKE '%$searchQuery%' ORDER BY id DESC" :
    "SELECT * FROM products ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Remove Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
    .product-card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      padding: 15px;
    }
    .product-image {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 10px;
    }
    .product-name {
      font-weight: 600;
      font-size: 18px;
    }
    .product-price { color: #6c757d; }
    .remove-btn { background-color: #dc3545; color: white; }
    .remove-btn:hover { background-color: #c82333; }
    .edit-btn { background-color: #ffc107; color: black; margin-left: 10px; }
    .edit-btn:hover { background-color: #e0a800; }
    .position-number {
      font-weight: bold;
      font-size: 20px;
      color: #6c757d;
    }
    .header-btn { position: absolute; top: 20px; right: 20px; }
  </style>
</head>
<body>
<div class="container mt-5">
  <!-- Back Button -->
  <div class="header-btn">
    <a href="admin.php" class="btn btn-outline-primary">🔙 Back to Admin Panel</a>
  </div>

  <!-- Search -->
  <form method="get" action="remove.php" class="mb-4 d-flex justify-content-center">
    <input type="text" name="search" class="form-control w-50" placeholder="Search products..." value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit" class="btn btn-outline-success ms-2">🔍 Search</button>
  </form>

  <h2 class="text-center mb-4">🗑️/✏️ Remove And Edit items <span class="text-muted">(Total: <?php echo $totalCount; ?>)</span></h2>

  <?php
  $counter = 1;
  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '
          <div class="product-card bg-white p-3 d-flex align-items-center">
              <div class="me-3 position-number">' . $counter++ . '.</div>
              <img src="' . $row["image_url"] . '" alt="Product Image" class="product-image me-3">
              <div class="flex-grow-1">
                  <div class="product-name">' . htmlspecialchars($row["name"]) . '</div>
                  <div class="product-price">₹' . number_format($row["price"], 2) . '</div>
              </div>
              <a href="remove.php?delete=' . $row["id"] . '" class="btn remove-btn" onclick="return confirm(\'Are you sure you want to remove this item?\')">🗑️ Remove</a>
              <a href="edit_price.php?id=' . $row["id"] . '" class="btn edit-btn">✏️ Edit Price</a>
          </div>';
      }
  } else {
      echo "<p class='text-center text-muted'>No products found.</p>";
  }
  ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
