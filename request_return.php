<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_id'])) {
    $issue_id = intval($_POST['issue_id']);
    $user_id = $_SESSION['user_id'];

    // Insert return request
    $sql = "INSERT INTO return_requests_2 (issue_id, user_id, request_at) VALUES (?, ?, NOW())";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $issue_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: student_dashboard.php");
    exit;
}
?>
