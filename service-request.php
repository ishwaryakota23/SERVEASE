<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "freelancer") {
    header("Location: login.html");
    exit();
}

require 'db_connect.php';

$email = $_SESSION['email'];
$sql = "SELECT id, customer_email, service_name, date, status FROM service_requests WHERE freelancer_email = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Requests - ServEase</title>
    <link rel="stylesheet" href="rr.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- ✅ jQuery for AJAX -->
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
    <h2>Service Requests</h2>
    <table>
        <tr>
            <th>Customer Email</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($request = $result->fetch_assoc()) : ?>
            <tr id="row_<?php echo $request['id']; ?>">
                <td><?php echo htmlspecialchars($request['customer_email']); ?></td>
                <td><?php echo htmlspecialchars($request['service_name']); ?></td>
                <td><?php echo htmlspecialchars($request['date']); ?></td>
                <td id="status_<?php echo $request['id']; ?>">
                    <?php echo htmlspecialchars($request['status']); ?>
                </td>
                <td>
                    <?php if ($request['status'] == 'pending') : ?>  <!-- ✅ Only show buttons for pending requests -->
                        <button class="btn accept" onclick="updateStatus(<?php echo $request['id']; ?>, 'Accepted')">Accept</button>
                        <button class="btn reject" onclick="updateStatus(<?php echo $request['id']; ?>, 'Rejected')">Reject</button>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<script>
    function updateStatus(requestId, newStatus) {
    $.ajax({
        url: "update_status.php",
        type: "POST",
        data: { request_id: requestId, status: newStatus }, // ✅ Ensure 'status' is sent
        success: function(response) {
            if (response.trim() === "success") {
                alert("Request " + newStatus + "!");
                if (newStatus === "Rejected") {
                    $("#row_" + requestId).fadeOut(); // ✅ Remove rejected requests
                } else {
                    $("#status_" + requestId).text("Accepted"); // ✅ Update status text
                }
            } else {
                alert("Action failed. Try again.");
            }
        },
        error: function() {
            alert("Error processing request.");
        }
    });
}

</script>

</body>
</html>
