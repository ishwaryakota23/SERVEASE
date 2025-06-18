<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "freelancer") {
    header("Location: login.html");
    exit();
}

require 'db_connect.php';

$email = $_SESSION['email'];
$sql = "SELECT * FROM freelancers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$freelancer = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - ServEase</title>
    <link rel="stylesheet" href="rr.css">
</head>
<body>
<nav>
        <div class="logo">ServEase</div>
        <ul>
        <li><a href="profilef.php">Profile</a></li>
            <li><a href="service-request.php">Service Requests</a></li>
            <li><a href="manage_services.php">Manage Services</a></li>
            <li><a href="supportf.php">support</a></li>
            <li><a href="ui.html">Logout</a></li>
        </ul>
    </nav>
    <div class="dashboard-container">
        <h2>My Profile</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($freelancer['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($freelancer['phone']); ?></p>
        <p><strong>Service:</strong> <?php echo htmlspecialchars($freelancer['service_category']); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($freelancer['experience']); ?> years</p>
    </div>
</body>
</html>
