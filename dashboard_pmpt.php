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
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        .navbar a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
        }

        .navbar {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
<div class="navbar">
    <a href="index.php">Home</a>
    <a href="notice_board.php">Notice Messages For Students</a>
    <a href="student_queries.php">Student Queries</a>
    <a href="contact_us.php">Contact Us</a>
    <a href="pmpt_logout.php">Logout</a>
</div>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($manager_username); ?>!</h2>
    <p>This is your dashboard. From here, you can manage student queries.</p><br>
    Want to post a notice for student <a href="notice_board.php">click here </a>to post a new notice
</div>
</body>

</html>
