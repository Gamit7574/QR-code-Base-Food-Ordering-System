<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp_entered = $_POST['otp'];

    if ($otp_entered == $_SESSION['pending_otp']) {
        $name = $_SESSION['pending_name'];
        $phone = $_SESSION['pending_phone'];
        $table_number = $_SESSION['pending_table'];

        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO otp_verify (name, phone, table_number) VALUES (?, ?, ?) 
                                ON DUPLICATE KEY UPDATE name=?, table_number=?");
        $stmt->bind_param("ssiss", $name, $phone, $table_number, $name, $table_number);
        $stmt->execute();

        $_SESSION['customer_name'] = $name;
        $_SESSION['customer_phone'] = $phone;
        $_SESSION['customer_table'] = $table_number;

        // Clean up
        unset($_SESSION['pending_otp'], $_SESSION['pending_name'], $_SESSION['pending_phone'], $_SESSION['pending_table']);

        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid OTP!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('real_back.jpeg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            color: black;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
    <h2>Verify OTP</h2>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter OTP" required><br>
        <button type="submit">Verify</button>
    </form>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>
