<?php
include('config.php');
session_start();

// Check if the user is an admin (assuming user role is stored in users table)
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id || !is_admin($user_id, $conn)) {
    header("Location: login.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    if ($action == 'add') {
        $title = trim($_POST['title']);
        $author = trim($_POST['author']);
        $category_id = intval($_POST['category_id']);
        $description = trim($_POST['description']);

        if (empty($title) || empty($author) || empty($category_id) || empty($description)) {
            $errors[] = "All fields are required.";
        } else {
            $sql = "INSERT INTO books (title, author, category_id, description) VALUES (?, ?, ?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('ssis', $title, $author, $category_id, $description);

                if ($stmt->execute()) {
                    $success = "Book added successfully.";
                } else {
                    $errors[] = "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    } elseif ($action == 'update') {
        // Code to update the book details...
    } elseif ($action == 'delete') {
        // Code to delete the book...
    }
}

function is_admin($user_id, $conn) {
    // Implement this function to check if the user is an admin
    // e.g., return true if user has an admin role
    return true; // Simplified for this example
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
</head>
<body>
    <h2>Admin Page</h2>
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

    <h3>Add Book</h3>
    <form method="post" action="">
        <input type="hidden" name="action" value="add">
        Title: <input type="text" name="title" required><br>
        Author: <input type="text" name="author" required><br>
        Category: 
        <select name="category_id" >
            <?php
            $result_categories = $conn->query("SELECT * FROM categories");
            while ($category = $result_categories->fetch_assoc()) {
                echo "<option value=\"" . htmlspecialchars($category['id']) . "\">" . htmlspecialchars($category['name']) . "</option>";
            }
            ?>
        </select><br>
        Description: <textarea name="description" required></textarea><br>
        <button type="submit">Add Book</button>
    </form>

    <h3>Update or Delete Books</h3>
 
</body>
</html>
