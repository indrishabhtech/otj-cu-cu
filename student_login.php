<?php
include('config.php');
session_start();

function generate_csrf_token() {
    return bin2hex(random_bytes(32));
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generate_csrf_token();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: student_dashboard.php");
                exit;


            } else {
                $errors[] = "Invalid credentials.";
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
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
<body>
<div class="container">

    <div class="navbar">
    <a href="index.php">Home</a>
    <a href="student_login.php">Student</a>
    <a href="books_library.php">Library</a>
    <a href="login_librarian.php">Librarian</a>
    <a href="login_pmpt.php.php">Manager</a>

    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
        <form class="signup-form" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <h2>Login Here</h2>
            
            <div class="form-group">
                <label for="email">Username</label>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="text" id="username" name="username" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Login</button>
            </div>
            <div class="form-links">
                <a href="#" class="forgot-password">Forgot Password?</a>
                <a href="student_signup.php" class="sign-in">New User ! Sign Up Here</a>
            </div>
        </form>
    </div>
</body>
</html>
