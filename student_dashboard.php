<?php
include('config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username']; 

// Fetch issued books
$sql_issued_books = "SELECT i.id AS issue_id, b.title, b.author, i.issued_at
                     FROM issued_books i
                     JOIN books b ON i.book_id = b.id
                     WHERE i.user_id = ?";
$stmt = $conn->prepare($sql_issued_books);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$issued_books = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['return_issue_id'])) {
    $issue_id = intval($_POST['return_issue_id']);
    $sql_return_request = "INSERT INTO return_requests (issue_id, user_id, request_at) VALUES (?, ?, NOW())";
    if ($stmt = $conn->prepare($sql_return_request)) {
        $stmt->bind_param('ii', $issue_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $success = "Return request submitted.";
    } else {
        $errors[] = "Error preparing statement: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="student_dashboard.css">
</head>
<body>
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="books_library.php">Library</a>
        <a href="ask_query.php">Ask Query</a>
        <a href="sir_notice.php">Sir's Message</a>
        <a href="contact_us.php">Contact Us</a>
    </div>
    <h2>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
    <p>This is your student dashboard. You can view and manage your books and queries here.</p>
    <h2>Your Issued Books</h2>
    <table border="1">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Issued At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $issued_books->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo htmlspecialchars($row['issued_at']); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="return_issue_id" value="<?php echo $row['issue_id']; ?>">
                        <button type="submit">Return Book</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <?php if (!empty($errors)): ?>
        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div style="color: green;">
            <p><?php echo htmlspecialchars($success); ?></p>
        </div>
    <?php endif; ?>
</body>
</html>
