<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "customer") {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sign_up";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customer_email = $_SESSION['email'];

// ✅ Fetch freelancer and booking details using JOIN
$sql = "SELECT f.name AS freelancer_name, f.email AS freelancer_email, sr.service_name, sr.status
        FROM service_requests sr
        JOIN freelancers f ON sr.freelancer_email = f.email
        WHERE sr.customer_email = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - ServEase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding-top: 70px;
            background-image: url('subg.jpg');
            background-size: cover;
            background-position: center;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        nav .logo {
            font-size: 1.8rem;
            color: white;
            margin-left: 20px;
        }

        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 1.2rem;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #f5a623;
            text-decoration: underline;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
    <h2>My Bookings</h2>
    <table>
        <tr>
            <th>Freelancer Name</th>
            <th>Email</th>
            <th>Service</th>
            <th>Status</th>
        </tr>
        <?php 
        if ($result->num_rows > 0) {
            while ($booking = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".htmlspecialchars($booking['freelancer_name'])."</td>
                        <td>".htmlspecialchars($booking['freelancer_email'])."</td>
                        <td>".htmlspecialchars($booking['service_name'])."</td>
                        <td>".htmlspecialchars($booking['status'])."</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No bookings found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
