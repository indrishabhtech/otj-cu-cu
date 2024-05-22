<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$sql_issued_books = "SELECT b.title, b.author, i.issued_at FROM issued_books i JOIN books b ON i.book_id = b.id WHERE i.user_id = ?";
$stmt = $conn->prepare($sql_issued_books);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$issued_books = $stmt->get_result();

$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Library</title>
</head>
<body>
    <h2>Your Issued Books</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Issued At</th>
        </tr>
        <?php while($row = $issued_books->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars($row['author']); ?></td>
            <td><?php echo htmlspecialchars($row['issued_at']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Books by Category</h2>
    <?php while($category = $result_categories->fetch_assoc()): ?>
        <h3><?php echo htmlspecialchars($category['name']); ?></h3>
        <?php
        $sql_books = "SELECT * FROM books WHERE category_id = ?";
        $stmt = $conn->prepare($sql_books);
        $stmt->bind_param('i', $category['id']);
        $stmt->execute();
        $books = $stmt->get_result();
        ?>
        <ul>
            <?php while($book = $books->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($book['title']) . " by " . htmlspecialchars($book['author']); ?></li>
            <?php endwhile; ?>
        </ul>
    <?php endwhile; ?>
</body>
</html>
