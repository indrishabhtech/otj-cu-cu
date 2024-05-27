<?php
include ('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please login to see the library";
    header("Location: index.php");
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
  <link rel="stylesheet" href="css/books_library.css">

<link rel="stylesheet" href="css/navbar.css">

</head>

<body>
<div class="navbar">
    <a href="index.php">Home</a>

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