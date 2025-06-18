<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "customer") {
    header("Location: login.html");
    exit();
}

require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_email = $_SESSION['email'];
    $freelancer_email = $_POST['freelancer_email'];
    $service_name = $_POST['service_name'];
    $status = "Pending"; // Default status

    // ✅ Insert into service_requests (Freelancer's request table)
    $stmt = $conn->prepare("INSERT INTO service_requests (customer_email, freelancer_email, service_name, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $customer_email, $freelancer_email, $service_name, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Booking Successful! The freelancer has been notified.'); window.location.href='bookings.php';</script>";
    } else {
        echo "<script>alert('Booking Failed. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>
