<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, username, password, is_verified FROM managers WHERE email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $username, $hashed_password, $is_verified);
        if ($stmt->fetch()) {
            if ($is_verified == 1) {
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['manager_id'] = $id;
                    $_SESSION['manager_email'] = $email;
                    $_SESSION['manager_username'] = $username;
                    header("Location: dashboard_pmpt.php"); // Redirect to the manager dashboard
                    exit;
                } else {
                    echo "Invalid email or password.";
                }
            } else {
                echo "Email not verified. Please check your email to verify your account.";
            }
        } else {
            echo "Invalid email or password.";
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
    <title>Manager Login</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: auto; padding: 20px; }
        input[type=email], input[type=password] { width: 100%; padding: 12px; margin: 8px 0; }
        button { width: 100%; padding: 12px; background-color: #333; color: white; border: none; }
    </style>
    <link rel="stylesheet" href="css/navbar.css">
    
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="login_pmpt.php">Manager</a>
    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
<div class="container">
    <h2>Login Now!</h2>
    <form method="post" action="">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
        <div style="padding: 20px" class="form-links">
            <a href="#" class="forgot-password">Forgot Password?</a>
            <a href="register_pmpt.php" class="sign-in">Sign Up Here</a>
        </div>
    </form>
</div>
</body>
</html>
