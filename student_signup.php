<?php
include('config.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendMail($email, $verification_code, $first_name) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';
        
        ';
        $mail->Password   = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('', 'PMPTM Library');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Email verification from PMPTM Library";
        $mail->Body    = "Thank you, $first_name, for email verification!<br>Click the link below to verify your email address:<br>
                          <a href='http://localhost/LMS/otj-cu-cu/librarian_verify.php?email=$email&verification_code=$verification_code'>Click to Verify</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }
}

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

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($first_name) || empty($last_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $verification_code = bin2hex(random_bytes(16));
        $not_verified = 0;

        $sql = "INSERT INTO users (first_name, last_name, username, email, password, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssssssi', $first_name, $last_name, $username, $email, $password_hash, $verification_code, $not_verified);

            if ($stmt->execute()) {
                if (sendMail($email, $verification_code, $first_name)) {
                    header("Location: login_pmpt.php?message=verification_sent");
                    exit;
                } else {
                    $errors[] = "Error: Verification email could not be sent.";
                }
            } else {
                $errors[] = "Username or email already taken.";
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
    <title>Signup Form</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/navbar2.css">
</head>
<body>
<div class="container">
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="student_login.php">Student</a>
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
        <h2 style="padding: 10px">Register</h2>

        <div class="form-group">
            <label for="first_name">First Name</label>
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="first_name" name="first_name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name</label>
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="last_name" name="last_name" required>
            </div>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" id="username" name="username" required>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" required>
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
            <a href="student_login.php" class="sign-in">Sign In</a>
        </div>
    </form>
</div>
</body>
</html>
