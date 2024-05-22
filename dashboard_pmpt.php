<?php
include('config.php');
session_start();

if (!isset($_SESSION['manager_id'])) {
    header("Location: login_pmpt.php");
    exit;
}

$manager_username = $_SESSION['manager_username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .navbar a { margin: 0 10px; text-decoration: none; color: #333; }
        .navbar { background-color: #f1f1f1; padding: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="message.php">Notice Messages for students</a>
        <a href="student_queries.php">Student Queries</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($manager_username); ?>!</h2>
        <p>This is your dashboard. From here, you can manage student queries.</p>
    </div>
</body>
</html>