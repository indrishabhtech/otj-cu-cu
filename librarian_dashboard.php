<?php
include ('config.php');
session_start();

if (!isset($_SESSION['librarian_id'])) {
    header("Location: login_librarian.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);

    if ($action == 'add') {
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
        $book_id = intval($_POST['book_id']);
        if (empty($title) || empty($author) || empty($category_id) || empty($description) || empty($book_id)) {
            $errors[] = "All fields are required.";
        } else {
            $sql = "UPDATE books SET title = ?, author = ?, category_id = ?, description = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('ssisi', $title, $author, $category_id, $description, $book_id);

                if ($stmt->execute()) {
                    $success = "Book updated successfully.";
                } else {
                    $errors[] = "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    } elseif ($action == 'delete') {
        $book_id = intval($_POST['book_id']);
        if (empty($book_id)) {
            $errors[] = "Book ID is required.";
        } else {
            $sql = "DELETE FROM books WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('i', $book_id);

                if ($stmt->execute()) {
                    $success = "Book deleted successfully.";
                } else {
                    $errors[] = "Database error: " . $conn->error;
                }
                $stmt->close();
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Librarian Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 800px;
            margin: 20px auto;
        }

        h2, h3 {
            text-align: center;
        }

        .error, .success {
            color: red;
            margin-bottom: 20px;
        }

        .success {
            color: green;
        }
        .alert {
            color: red;
        }

        form {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        form input[type="text"],
        form input[type="number"],
        form select,
        form textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
        }

        form button:hover {
            background-color: #45a049;
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
            form {
                padding: 15px;
            }

            form input[type="text"],
            form input[type="number"],
            form select,
            form textarea {
                width: calc(100% - 20px);
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

    
        <h2>Your Librarian Dashboard</h2>
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
            <select name="category_id" required>
                <option value="1">Science</option>
                <option value="2">Computer</option>
                <option value="3">BA</option>
                <option value="4">BSc</option>
                <option value="5">BCA</option>
                <option value="6">BBA</option>
                <option value="7">BEd</option>
            </select><br>
            Description: <textarea name="description" required></textarea><br>
            <button type="submit">Add Book</button>
        </form>

        <h3>Update Book</h3>
        <form method="post" action="">
            <input type="hidden" name="action" value="update">
            Book ID: <input type="number" name="book_id" required><br>
            Title: <input type="text" name="title" required><br>
            Author: <input type="text" name="author" required><br>
            Category:
            <select name="category_id" required>
                <option value="1">Science</option>
                <option value="2">Computer</option>
                <option value="3">BA</option>
                <option value="4">BSc</option>
                <option value="5">BCA</option>
                <option value="6">BBA</option>
                <option value="7">BEd</option>
            </select><br>
            Description: <textarea name="description" required></textarea><br>
            <button type="submit">Update Book</button>
        </form>

        <h3>Delete Book</h3>
        <form method="post" action="">
            <input type="hidden" name="action" value="delete">
            Book ID: <input type="number" name="book_id" required><br>
            <button type="submit">Delete Book</button>
        </form>
        <a href="requests_librarian.php">Requests_librarian </a>
</body>

</html>