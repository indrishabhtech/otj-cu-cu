<?php
include('config.php');
session_start();

if (!isset($_SESSION['manager_id'])) {
    header("Location: login_pmpt.php");
    exit;
}

$sql = "SELECT student_name, student_class, issue, created_at FROM student_queries ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Queries</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 12px; text-align: left; }
    </style>
  <link rel="stylesheet" href="css/navbar2.css">
    
</head>
<body>
<div class="navbar">
    <a href="dashboard_pmpt.php">Dashboard</a>

    <a href="notice_board.php">Notice Board</a>
    <a href="contact_us.php">Contact Us</a>
    <a href="logout.php">Logout</a>
</div>
    <div class="container">
        <h2>Student Queries</h2>
        <table>
            <tr>
                <th>Student Name</th>
                <th>Class</th>
                <th>Issue/Query</th>
                <th>Queried At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                <td><?php echo htmlspecialchars($row['student_class']); ?></td>
                <td><?php echo htmlspecialchars($row['issue']); ?></td>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
