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
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO librarians (username, password) VALUES (?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ss', $username, $password_hash);

            if ($stmt->execute()) {
                header("Location: login_librarian.php");
                exit;
            } else {
                $errors[] = "Username already taken.";
            }
            $stmt->close();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Librarian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="register_librarian.css">
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    
    <a href="books_library.php">Library</a>
   
    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
<div class="container">
    <h2>Register as Librarian</h2>
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form class="signup-form" method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-icon">
                <i class="fas fa-user"></i>
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
            <label for="confirm_password">Confirm Password</label>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
        </div>
        <div class="form-group">
            <button type="submit">Signup</button>
        </div>
        <div class="form-links">
            <a href="#" class="forgot-password">Forgot Password?</a>
            <a href="login_librarian.php" class="sign-in">Login as Librarian</a>
        </div>
    </form>
</div>
</body>
</html>
