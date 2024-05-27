<?php
include('config.php');

if (isset($_GET['email']) && isset($_GET['verification_code'])) {
    $email = $_GET['email'];
    $verification_code = $_GET['verification_code'];

    $query = "SELECT * FROM managers WHERE email = ? AND verification_code = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('ss', $email, $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $result_fetch = $result->fetch_assoc();
            if ($result_fetch['is_verified'] == 0) {
                $update = "UPDATE managers SET is_verified = 1 WHERE email = ?";
                if ($update_stmt = $conn->prepare($update)) {
                    $update_stmt->bind_param('s', $email);
                    if ($update_stmt->execute()) {
                        echo "Email verification successful. You can now <a href='login_pmpt.php'>login</a>.";
                    } else {
                        echo "Some internal error occurred.";
                    }
                }
            } else {
                echo "Email is already verified.";
            }
        } else {
            echo "Invalid verification link.";
        }
    } else {
        echo "Some internal error occurred.";
    }
} else {
    echo "Invalid request.";
}
?>
