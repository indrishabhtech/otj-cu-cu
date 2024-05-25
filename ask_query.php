<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_name = trim($_POST['student_name']);
    $student_class = trim($_POST['student_class']);
    $issue = trim($_POST['issue']);

    $sql = "INSERT INTO student_queries (student_name, student_class, issue) VALUES (?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sss', $student_name, $student_class, $issue);
        $stmt->execute();
        $stmt->close();
        $success = "Query sent successfully.";
    } else {
        $error = "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="ask_query.css">
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .navbar a { margin: 0 10px; text-decoration: none; color: #333; }
        .navbar { background-color: #f1f1f1; padding: 10px; text-align: center; }
        .footer { background-color: #333; color: white; text-align: center; padding: 20px; position: fixed; left: 0; bottom: 0; width: 100%; }
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
        <h2>Contact Us</h2>
        <?php if (isset($success)): ?>
            <p style="color:green;"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="student_name">Name</label>
            <input type="text" id="student_name" name="student_name" required>
            <label for="student_class">Class</label>
            <input type="text" id="student_class" name="student_class" required>
            <label for="issue">Issue/Query</label>
            <textarea id="issue" name="issue" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="footer">
        <p>&copy; 2024 Library Management System</p>
    </div>
</body>
</html>
