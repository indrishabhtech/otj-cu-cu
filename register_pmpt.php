<?php
include('config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendMail($email, $verification_code) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'shyamjimishra4017@gmail.com'; // Replace with your email address
        $mail->Password   = 'lbjvlwjzeimufwko'; // Replace with your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('shyamjimishra4017@gmail.com', 'PMPTM Library'); // Replace with your email address
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true);
        $mail->Subject = "Email verification from PMPTM Library";
        $mail->Body    = "Thanks You Sir for Email Verification !<br>Click on the below link to verify your email address:<br>
                          <a href='http://localhost/LMS/otj-cu-cu/verify.php?email=$email&verification_code=$verification_code'>Click to Verify</a><br><br> Have a Good day Sir!";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $email = trim($_POST['email']);
    $verification_code = bin2hex(random_bytes(16));
    $not_verified = 0; // Use 0 for false, 1 for true in SQL BOOLEAN field

    $sql = "INSERT INTO managers (username, password, email, verification_code, is_verified) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ssssi', $username, $password, $email, $verification_code, $not_verified);
        if ($stmt->execute()) {
            if (sendMail($email, $verification_code)) {
                header("Location: login_pmpt.php?message=verification_sent");
                exit;
            } else {
                echo "Error: Verification email could not be sent.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/register_pmpt.css">
    <style>
        input[type=text], input[type=password], input[type=email] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="books_library.php">Library</a>
    <a href="login_pmpt.php">Manager</a>
    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>

<div class="container">
    <h2>Manager</h2>
    <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Register</button>
        <div class="form-links">
            <a href="#" class="forgot-password">Forgot Password?</a>
            <a href="login_pmpt.php" class="sign-in">Login Here</a>
        </div>
    </form>
</div>
</body>
</html>
