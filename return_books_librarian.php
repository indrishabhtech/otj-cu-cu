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
        // Get the issue_id from the return request
        $sql = "SELECT issue_id FROM return_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->bind_result($issue_id);
            $stmt->fetch();
            $stmt->close();
        } else {
            $errors[] = "Error preparing statement: " . $conn->error;
        }

        // Delete the issued book entry
        $sql_delete_issue = "DELETE FROM issued_books WHERE id = ?";
        if ($stmt = $conn->prepare($sql_delete_issue)) {
            $stmt->bind_param('i', $issue_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $errors[] = "Error preparing statement: " . $conn->error;
        }

        // Delete the return request after approval
        $sql_delete_request = "DELETE FROM return_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql_delete_request)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $errors[] = "Error preparing statement: " . $conn->error;
        }

        if (empty($errors)) {
            $success = "Return request accepted and book returned.";
        }
    } elseif ($action == 'reject') {
        $sql_delete_request = "DELETE FROM return_requests WHERE id = ?";
        if ($stmt = $conn->prepare($sql_delete_request)) {
            $stmt->bind_param('i', $request_id);
            $stmt->execute();
            $stmt->close();
        } else {
            $errors[] = "Error preparing statement: " . $conn->error;
        }

        if (empty($errors)) {
            $success = "Return request rejected.";
        }
    }
}

// Fetch all pending return requests
$sql_requests = "SELECT r.id, u.username, b.title, b.author FROM return_requests r
                 JOIN users u ON r.user_id = u.id
                 JOIN issued_books i ON r.issue_id = i.id
                 JOIN books b ON i.book_id = b.id";
$result_requests = $conn->query($sql_requests);

if ($result_requests === false) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Requests</title>
    <link rel="stylesheet" href="return_books_librarian.css">
</head>
<body>
    <h2>Return Requests</h2>
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
