<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Upload image
    $imageName = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];
    $imagePath = "uploads/" . basename($imageName);

    // Move image to uploads folder
    if (move_uploaded_file($imageTmp, $imagePath)) {
        $stmt = $conn->prepare("INSERT INTO products (name, price, image_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $name, $price, $imagePath);
        $stmt->execute();
        header("Location: admin.php");
        exit;
    } else {
        echo "❌ Image upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Product</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      padding: 40px;
    }

    h2 {
      color: #333;
      text-align: center;
      margin-bottom: 30px;
    }

    form {
      background-color: white;
      padding: 25px;
      border-radius: 10px;
      max-width: 500px;
      margin: 0 auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
      width: 100%;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
      margin-bottom: 20px;
    }

    button {
      background-color: #28a745;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
    }

    button:hover {
      background-color: #218838;
    }

    a {
      display: inline-block;
      margin-top: 20px;
      text-align: center;
      width: 100%;
      color: #007bff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<h2>➕ Add New Product</h2>
<form method="POST" action="add.php" enctype="multipart/form-data">
  <label>Product Name:</label>
  <input type="text" name="name" required>

  <label>Price:</label>
  <input type="number" step="0.01" name="price" required>

  <label>Product Image:</label>
  <input type="file" name="image" accept="image/*" required>

  <button type="submit">Add Product</button>
</form>

<p><a href="admin.php">🔙 Back to Admin Panel</a></p>

</body>
</html>
