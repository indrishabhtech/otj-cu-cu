<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_name = $_SESSION['username'];  // Assuming you store the username in the session

// Fetch all notices in descending order
$sql = "SELECT notice, created_at FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);

$notices = [];
if ($result) {
    $notices = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .navbar a { margin: 0 10px; text-decoration: none; color: #333; }
        .navbar { background-color: #f1f1f1; padding: 10px; text-align: center; }
        .footer { background-color: #333; color: white; text-align: center; padding: 20px; position: fixed; left: 0; bottom: 0; width: 100%; }
        .notice { margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; }
        .notice h3 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="student_dashboard.php">Dashboard</a>
        <a href="books_library.php">Library</a>
        <a href="about_us.php">About Us</a>
        <a href="contact_us.php">Contact Us</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p>This is your sir's message dashboard. You can view and manage notices & messages here.</p>
        
        <?php if ($notices): ?>
            <div class="notice">
                <h3>Latest Notice</h3>
                <p><?php echo htmlspecialchars($notices[0]['notice']); ?></p>
                <p><em>Posted on: <?php echo htmlspecialchars($notices[0]['created_at']); ?></em></p>
            </div>
            <h3>Past Notices</h3>
            <?php foreach (array_slice($notices, 1) as $notice): ?>
                <div class="notice">
                    <p><?php echo htmlspecialchars($notice['notice']); ?></p>
                    <p><em>Posted on: <?php echo htmlspecialchars($notice['created_at']); ?></em></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No notices available.</p>
        <?php endif; ?>
        
        <!-- Rest of the dashboard content -->
    </div>
    <div class="footer">
        <p>&copy; 2024 Library Management System</p>
    </div>
</body>
</html>
