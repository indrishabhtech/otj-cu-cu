<?php
include('config.php');
session_start();

if (!isset($_SESSION['librarian_id'])) {
    header("Location: login_librarian.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id'])) {
    $request_id = intval($_POST['request_id']);
    $action = $_POST['action'];

    if ($action == 'accept') {
        // Get the user_id and book_id from the request
        $sql = "SELECT user_id, book_id FROM book_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->bind_result($user_id, $book_id);
            $stmt->fetch();
            $stmt->close();
        }

        // Insert into issued_books
        $sql_issue = "INSERT INTO issued_books (user_id, book_id, issued_at) VALUES (?, ?, NOW())";
        if ($stmt = $conn->prepare($sql_issue)) {
            $stmt->bind_param('ii', $user_id, $book_id);
            $stmt->execute();
            $stmt->close();
        }

        // Delete the request after approval
        $sql_delete = "DELETE FROM book_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql_delete)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->close();
        }

        $success = "Book request accepted and issued.";
    } elseif ($action == 'reject') {
        $sql_delete = "DELETE FROM book_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql_delete)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->close();
        }

        $success = "Book request rejected.";
    }
}

// Fetch all pending book requests
$sql_requests = "SELECT r.id, u.username, b.title, b.author FROM book_requests r
                 JOIN users u ON r.user_id = u.id
                 JOIN books b ON r.book_id = b.id";
$result_requests = $conn->query($sql_requests);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            margin: 0 5px;
        }

        button[type="submit"][value="reject"] {
            background-color: #f44336;
        }

        button:hover {
            opacity: 0.9;
        }

        @media screen and (max-width: 600px) {
            table, thead, tbody, th, td, tr {
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

            button {
                width: 100%;
                padding: 12px;
                margin: 5px 0;
            }
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
    <a href="student_login.php">Student</a>
    <a href="books_library.php">Library</a>
    <a href="books_library.php">Librarian</a>
    <a href="books_library.php">Manager</a>

    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
    <h2>Book Requests</h2>
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div style="color: green;">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>
    <table border="1">
        <tr>
            <th>Username</th>
            <th>Book Title</th>
            <th>Author</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result_requests->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td>
                <form method="post" action="">
                    <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <button type="submit" name="action" value="accept">Accept</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
