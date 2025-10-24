<?php
session_start();
if (!isset($_SESSION['customer_name'])) {
    header("Location: customerdetails.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "food_ordering");
$products = [];
$result = $conn->query("SELECT * FROM products");
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food Ordering</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

  <style>
    #searchInput {
  padding: 12px 20px;
  font-size: 16px;
  border-radius: 25px;
  border: 2px solid #ff7e5f;
  outline: none;
  transition: 0.3s;
}

#searchInput:focus {
  border-color: #feb47b;
  box-shadow: 0 0 10px rgba(255, 126, 95, 0.3);
}

    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #fff8f0;
    }

    .navbar {
      background: linear-gradient(to right, #ff7e5f, #feb47b);
      padding: 15px 0;
    }

    .navbar-brand, .nav-link, .navbar-text {
      color: white !important;
      font-weight: 600;
    }

    .navbar .btn {
      background-color: #fff;
      color: #ff7e5f;
      border: none;
    }

    .navbar .btn:hover {
      background-color: #ffe1d2;
    }

    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: none;
      border-radius: 15px;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .card-title {
      font-weight: 600;
      color: #333;
    }

    .price-badge {
      background: #ff7e5f;
      color: white;
      padding: 5px 10px;
      border-radius: 20px;
      font-weight: bold;
    }

    .order-note {
      resize: vertical;
      min-height: 60px;
    }

    .btn-success {
      background-color: #28a745;
      border-radius: 25px;
      font-weight: 600;
    }

    #order-status-message {
      display: none;
      padding: 15px;
      font-weight: bold;
      border-radius: 8px;
      margin-top: 20px;
    }

    footer {
      margin-top: 40px;
      background-color: #f1f1f1;
      padding: 20px 0;
      text-align: center;
      color: #888;
      font-size: 14px;
    }
  </style>
</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="#">🍽️ FoodieOrder</a>
    <div class="ms-auto d-flex align-items-center gap-3">

      <span class="navbar-text">
        Welcome, <?= htmlspecialchars($_SESSION['customer_name']) ?> (<?= htmlspecialchars($_SESSION['customer_phone']) ?>)
      </span>
      <a href="cart.php" class="btn btn-light shadow-sm">🛒 View Cart</a>
    </div>
  </div>
</nav>



<!-- Order Status -->
<div class="container mt-4">
  <div id="order-status-message" class="text-center"></div>

  <div class="text-center mb-4">
    <div class="row justify-content-center mb-4">
  <div class="col-md-6">
    <input type="text" id="searchInput" class="form-control shadow-sm" placeholder="🔍 Search for dishes...">
  </div>
</div>

    <h2 class="fw-bold">Our Menu</h2>
    <p class="text-muted">Pick your favorites and let us know any preferences!</p>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($products as $product): ?>
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 200px; object-fit: cover; border-top-left-radius: 15px; border-top-right-radius: 15px;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
            <span class="price-badge mb-2">₹<?= number_format($product['price'], 2) ?></span>
            <textarea class="form-control order-note mb-3" id="desc-<?= $product['id'] ?>" placeholder="Add special notes (e.g., less spicy, no onions)"></textarea>
            <button onclick="order('<?= htmlspecialchars($product['name']) ?>', <?= $product['price'] ?>, 'desc-<?= $product['id'] ?>')" class="btn btn-success mt-auto">➕ Add to Cart</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<div id="toast-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999;"></div>
<!-- Footer -->
<footer>
  <div class="container">
    © <?= date("Y") ?> FoodieOrder. Made with ❤️ for food lovers.
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function order(name, price, descId) {
  const description = document.getElementById(descId).value.trim();

  fetch("add_to_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ product: name, price: price, description: description })
  })
  .then(res => res.text())
  .then(msg => {
    alert("✅ Added to cart: " + name);
  })
  .catch(err => {
    alert("❌ Error adding to cart.");
    console.error(err);
  });
}

function checkOrderStatus() {
  fetch("order_status.php")
    .then(response => response.json())
    .then(data => {
      const messageBox = document.getElementById("order-status-message");

      if (data.status === "prepared") {
        messageBox.style.display = "block";
        messageBox.className = "alert alert-warning";
        messageBox.innerText = "🟡 Your order has been prepared. It will be served soon!";
      } else if (data.status === "ready") {
        messageBox.style.display = "block";
        messageBox.className = "alert alert-success";
        messageBox.innerText = "🟢 Your order is ready! Please collect it or wait for serving.";
      } else {
        messageBox.style.display = "none";
      }
    })
    .catch(err => console.error("Status check failed", err));
}
// Speak a custom message
function speakStatus(text) {
  const utterance = new SpeechSynthesisUtterance(text);
  utterance.rate = 1;
  utterance.pitch = 1.1;
  speechSynthesis.speak(utterance);
}

// Vibrate the device
function vibrateNotice() {
  if (navigator.vibrate) {
    navigator.vibrate([200, 100, 200]);
  }
}

// Show popup (like Instagram-style)
function showPopup(message, color = "#28a745") {
  const toast = document.createElement("div");
  toast.className = "toast-notification";
  toast.innerText = message;
  toast.style.cssText = `
    background: ${color};
    color: white;
    padding: 12px 20px;
    border-radius: 20px;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-weight: bold;
    font-size: 15px;
    animation: fadeInOut 3s ease-in-out;
  `;
  document.getElementById("toast-container").appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Track last notified status
let lastStatusNotified = localStorage.getItem("lastOrderStatus");

function checkOrderStatus() {
  fetch("order_status.php")
    .then(res => res.json())
    .then(data => {
      const status = data.status;
      const messageBox = document.getElementById("order-status-message");

      if (status === "prepared" && lastStatusNotified !== "prepared") {
        const msg = "🟡 Your order has been prepared. It will be served soon!";
        messageBox.className = "alert alert-warning";
        messageBox.innerText = msg;
        messageBox.style.display = "block";

        showPopup("Your food is prepared.", "#ffc107");
        speakStatus("Hey there! Your food is prepared. It will be served shortly.");
        vibrateNotice();
        localStorage.setItem("lastOrderStatus", "prepared");
        lastStatusNotified = "prepared";

      } else if (status === "ready" && lastStatusNotified !== "ready") {
        const msg = "🟢 Your order is ready! Please collect it or wait for serving.";
        messageBox.className = "alert alert-success";
        messageBox.innerText = msg;
        messageBox.style.display = "block";

        showPopup("Your order is ready to serve!", "#28a745");
        speakStatus("Hello! Your order is ready now. Please collect it or wait to be served.");
        vibrateNotice();
        localStorage.setItem("lastOrderStatus", "ready");
        lastStatusNotified = "ready";

      } else if (!["prepared", "ready"].includes(status)) {
        messageBox.style.display = "none";
        localStorage.removeItem("lastOrderStatus");
        lastStatusNotified = null;
      }
    })
    .catch(err => console.error("Status check failed", err));
}

checkOrderStatus();
setInterval(checkOrderStatus, 5000);

// Fade animation
const style = document.createElement("style");
style.innerHTML = `
@keyframes fadeInOut {
  0% { opacity: 0; transform: translateY(-20px); }
  10%, 90% { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(-20px); }
}
`;
document.head.appendChild(style);



document.getElementById("searchInput").addEventListener("input", function () {
  const query = this.value.toLowerCase();
  const cards = document.querySelectorAll(".card");

  cards.forEach(card => {
    const name = card.querySelector(".card-title").textContent.toLowerCase();
    if (name.includes(query)) {
      card.parentElement.style.display = "block";
    } else {
      card.parentElement.style.display = "none";
    }
  });
});

</script>



</body>
</html>
