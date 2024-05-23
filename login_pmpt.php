<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT id, password FROM managers WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($id, $hashed_password);
        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['manager_id'] = $id;
            $_SESSION['manager_username'] = $username; // Store the username correctly
            header("Location: dashboard_pmpt.php"); // Redirect to the manager dashboard
            exit;
        } else {
            echo "Invalid username or password.";
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
        input[type=text], input[type=password] { width: 100%; padding: 12px; margin: 8px 0; }
        button { width: 100%; padding: 12px; background-color: #333; color: white; border: none; }
    </style>
      <style>
        /* Navbar */
        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 17px;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Footer */
        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
    
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    
    <a href="login_pmpt.php">Manager</a>

    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
    <div class="container">
        <h2>Login Now !</h2>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
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
