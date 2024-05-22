<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    $sql = "INSERT INTO managers (username, password) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ss', $username, $password);
        if ($stmt->execute()) {
            header("Location: login_pmpt.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Registration</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 500px; margin: auto; padding: 20px; }
        input[type=text], input[type=password] { width: 100%; padding: 12px; margin: 8px 0; }
        button { width: 100%; padding: 12px; background-color: #333; color: white; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manager Registration</h2>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
