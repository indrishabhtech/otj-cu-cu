<?php
include('config.php');
session_start();

if (!isset($_SESSION['librarian_id'])) {
    header("Location: login_pmpt.php");
    exit;
}

// Add book
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $author = isset($_POST['author']) ? trim($_POST['author']) : null;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;

    if ($title && $author && $category_id && $description) {
        $sql = "INSERT INTO books (title, author, category_id, description) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('ssis', $title, $author, $category_id, $description);
            if ($stmt->execute()) {
                $success = "Book added successfully.";
            } else {
                $error = "Error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "All fields are required.";
    }
}

// Delete book
if (isset($_GET['delete_book_id'])) {
    $book_id = intval($_GET['delete_book_id']);
    
    // First, delete any related records from book_requests
    $sql = "DELETE FROM book_requests WHERE book_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $stmt->close();
    }

    // Now delete the book
    $sql = "DELETE FROM books WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $book_id);
        if ($stmt->execute()) {
            $success = "Book deleted successfully.";
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Librarian Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .navbar a { margin: 0 10px; text-decoration: none; color: #333; }
        .navbar { background-color: #f1f1f1; padding: 10px; text-align: center; }
        .footer { background-color: #333; color: white; text-align: center; padding: 20px; position: fixed; left: 0; bottom: 0; width: 100%; }
    </style>
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
    
    <a href="books_library.php">Library</a>
    <a href="requests_librarian.php">Book Requests of Students</a>
   

    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>
    <div class="container">
        <h2>Your Librarian Dashboard</h2>
        <?php if (isset($success)): ?>
            <p style="color:green;"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="add_book" value="1">
            <label for="title">Book Title</label>
            <input type="text" id="title" name="title" required>
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required>
            <label for="category_id">Category</label>
            <select id="category_id" name="category_id" required>
                <option value="1">Science</option>
                <option value="2">Computer</option>
                <option value="3">BA</option>
                <option value="4">BSc</option>
                <option value="5">BCA</option>
                <option value="6">BBA</option>
                <option value="7">BEd</option>
            </select>
            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>
            <button type="submit">Add Book</button>
        </form>
        <h3>Existing Books</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php
            $sql = "SELECT books.id, books.title, books.author, categories.name AS category, books.description 
                    FROM books 
                    JOIN categories ON books.category_id = categories.id";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['author']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><a href="?delete_book_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <div class="footer">
        <p>&copy; 2024 Library Management System</p>
    </div>
</body>
</html>
