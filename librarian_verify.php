<?php
include('config.php');

if (isset($_GET['email']) && isset($_GET['verification_code'])) {
    $email = $_GET['email'];
    $verification_code = $_GET['verification_code'];

    $sql = "SELECT * FROM librarians WHERE email = ? AND verification_code = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ss', $email, $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $sql_update = "UPDATE librarians SET is_verified = 1 WHERE email = ?";
            if ($stmt_update = $conn->prepare($sql_update)) {
                $stmt_update->bind_param('s', $email);
                $stmt_update->execute();
                echo "Email verification successful. You can now <a href='login_librarian.php'>login</a>.";
            } else {
                echo "Database error: " . $conn->error;
            }
        } else {
            echo "Invalid verification link or email already verified.";
        }
    } else {
        echo "Database error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
