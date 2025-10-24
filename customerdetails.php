<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $table_number = $_POST['table_number'];

    $otp = rand(100000, 999999);

    $api_url = "https://2factor.in/API/V1/" . API_KEY . "/SMS/" . $phone . "/" . $otp;
    $response = file_get_contents($api_url);

    if ($response) {
        $_SESSION['pending_name'] = $name;
        $_SESSION['pending_phone'] = $phone;
        $_SESSION['pending_table'] = $table_number;
        $_SESSION['pending_otp'] = $otp;

        header("Location: verify.php");
        exit();
    } else {
        echo "Failed to send OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('your-background.jpg'); /* Replace with your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            overflow: hidden;
        }

        /* Gradient animation overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("cust_wall_1.jpg");
            background-size: 100% 100%;
            /*animation: gradient 15s ease infinite;
            z-index: 0;*/
            background-repeat: no-repeat;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-container {
            position: relative;
            z-index: 1;
            background-color:transparent;
            backdrop-filter: blur(20px);
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: white;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border-radius: 6px;
            font-size: 16px;
            background-color: black;
        }

        button {
            background-color: red;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 6px;
            margin-top: 10px;
        }

        button:hover {
            background-color: black;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Enter Your Details</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Enter Name" required><br>
        <input type="text" name="phone" placeholder="Enter Mobile Number" required><br>
        <input type="number" name="table_number" placeholder="Enter Table Number" required><br>
        <button type="submit">Send OTP</button>
    </form>
</div>

</body>
</html>
