<?php
include ('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT b.id, b.title, b.author, c.name AS category, b.description
        FROM books b
        LEFT JOIN categories c ON b.category_id = c.id";
$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'])) {
    $book_id = intval($_POST['book_id']);

    // Insert the book request into the database
    $sql_request = "INSERT INTO book_requests (user_id, book_id) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql_request)) {
        $stmt->bind_param('ii', $user_id, $book_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>All Books in Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
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

            button {
                width: 100%;
                padding: 12px;
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
    <h2>All Books in Library</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                        <button type="submit">Request this Book</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>