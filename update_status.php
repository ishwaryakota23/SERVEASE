<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "freelancer") {
    echo "error";
    exit();
}

require 'db_connect.php';

// ✅ Check if request_id and status exist in POST data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE service_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $request_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
} else {
    echo "error"; // ✅ If status or request_id is missing
}
$conn->close();
?>
