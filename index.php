<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
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
    <a href="login_librarian.php">Librarian</a>
    <a href="login_pmpt.php">Manager</a>

    <a href="about_us.php">About Us</a>
    <a href="contact_us.php">Contact Us</a>
</div>

<div style="padding: 20px">
    <h2>Welcome to Our Library Management System</h2>
    <p>This is a platform to manage books and users efficiently.</p>
</div>

<div class="footer">
    <p>&copy; 2024 Library Management System</p>
</div>

</body>
</html>
