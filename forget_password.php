<?php
$conn = new mysqli("localhost", "root", "", "food_ordering");
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        // Simulate sending email with reset link (in real app, send actual email)
        $token = bin2hex(random_bytes(16));
        $reset_link = "http://localhost/reset_password.php?email=$email&token=$token";

        // Store token to validate (for real app, store in DB)
        file_put_contents("reset_tokens/$email.txt", $token);

        $success = "Password reset link sent to your email. <br><a href='$reset_link'>Click here to reset</a>";
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            width: 400px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 20px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
        }
        .message {
            text-align: center;
            margin-top: 15px;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
<div class="box">
    <h2>Forgot Password</h2>
    <?php if ($success) echo "<div class='message success'>$success</div>"; ?>
    <?php if ($error) echo "<div class='message error'>$error</div>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</div>
</body>
</html>
