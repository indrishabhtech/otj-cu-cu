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

    <a href="about_us.php">About Us</a>
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
