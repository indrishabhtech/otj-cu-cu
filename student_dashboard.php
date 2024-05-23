<?php
include ('config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username']; // Retrieve the username from session


// Fetch the latest notice
// $sql = "SELECT notice, created_at FROM notices ORDER BY created_at DESC LIMIT 1";
// $result = $conn->query($sql);
// $latest_notice = $result->fetch_assoc();




$sql_issued_books = "SELECT b.title, b.author, i.issued_at 
                     FROM issued_books i 
                     JOIN books b ON i.book_id = b.id 
                     WHERE i.user_id = ?";
$stmt = $conn->prepare($sql_issued_books);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$issued_books = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 800px;
            margin: 20px auto;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 600px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                display: none;
            }

            tr {
                margin-bottom: 15px;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                padding-right: 10px;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>

    <style>
        /* Navbar */

        .form-group{
            text-align: center;
            background-color: rgba(0, 0, 0, 0.1);
        }
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
       
        <a href="books_library.php">Library</a>
        <a href="ask_query.php">Ask Query</a>
        <a href="sir_notice.php">Sir's Message</a>
        
        <!-- <a href="about_us.php">About Us</a> -->
        <a href="contact_us.php">Contact Us</a>
    </div>
    <h2>Welcome, <?php echo htmlspecialchars($user_name); ?> !</h2>
    <p>This is your student dashboard. You can view and manage your books and queries here.</p>
    <h2>Your Issued Books</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Issued At</th>
        </tr>
        <?php while ($row = $issued_books->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo htmlspecialchars($row['issued_at']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
    <div class="form-group">
        <button type="submit"><a href="books_library.php">Go to Library To See all Books</a></button>
    </div>
    <!-- <a href="books_library.php">Go to Library To See all Books</a> -->
</body>

</html>