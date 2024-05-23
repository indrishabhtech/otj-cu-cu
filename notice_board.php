<?php
include('config.php');
session_start();

if (!isset($_SESSION['manager_id'])) {
    header("Location: login_pmpt.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $notice = trim($_POST['notice']);

    if (!empty($notice)) {
        $sql = "INSERT INTO notices (notice) VALUES (?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $notice);
            if ($stmt->execute()) {
                $success = "Notice posted successfully.";
            } else {
                $error = "Error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Please enter a notice.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notice Board</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .navbar a { margin: 0 10px; text-decoration: none; color: #333; }
        .navbar { background-color: #f1f1f1; padding: 10px; text-align: center; }
        .footer { background-color: #333; color: white; text-align: center; padding: 20px; position: fixed; left: 0; bottom: 0; width: 100%; }
        textarea, button { width: 100%; padding: 12px; margin: 8px 0; }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="student_queries.php">Student Queries</a>
        <a href="notice_board.php">Notice Board</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h2>Post a Notice</h2>
        <?php if (isset($success)): ?>
            <p style="color:green;"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="notice">Notice</label>
            <textarea id="notice" name="notice" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="footer">
        <p>&copy; 2024 Library Management System</p>
    </div>
</body>
</html>
