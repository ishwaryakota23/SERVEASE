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

$email = $_SESSION['email'];

// ✅ Fetch customer details
$sql = "SELECT name, phone, address FROM customers WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'] ?? "N/A";
    $phone = $row['phone'] ?? "N/A";
    $address = $row['address'] ?? "N/A";
} else {
    $name = "N/A";
    $phone = "N/A";
    $address = "N/A";
}

// ✅ Fetch booking history
$booking_sql = "SELECT f.name AS freelancer_name, f.email AS freelancer_email, sr.service_name, sr.status
        FROM service_requests sr
        JOIN freelancers f ON sr.freelancer_email = f.email
        WHERE sr.customer_email = ?";
$booking_stmt = $conn->prepare($booking_sql);
$booking_stmt->bind_param("s", $email);
$booking_stmt->execute();
$booking_result = $booking_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - ServEase</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding-top: 70px;
            background-image: url(subg.jpg);
            background-size: cover;
            background-position: center;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 10px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        nav .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
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
            font-weight: bold;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #f5a623;
            text-decoration: underline;
        }

        .dashboard-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            color: #332f33;
        }

        p {
            font-size: 1.1rem;
            color: #666;
        }

        table {
            width: 100%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        /* ✅ Status Color Coding */
        .status-pending {
            color: #f39c12; /* Orange */
            font-weight: bold;
        }

        .status-completed {
            color: #28a745; /* Green */
            font-weight: bold;
        }

        .status-cancelled {
            color: #e74c3c; /* Red */
            font-weight: bold;
        }

        /* ✅ Pop-up Notification Styling */
        .popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            font-weight: bold;
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo">ServEase</div>
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="browse_services.php">Browse Services</a></li>
            <li><a href="support.php">Support</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
        
        <h3>Your Bookings</h3>
        <table>
            <tr>
                <th>freelancer name</th>
                <th>service</th>
                <th>Status</th>
            </tr>
            <?php 
            if ($booking_result->num_rows > 0) {
                while ($booking = $booking_result->fetch_assoc()) {
                    $statusClass = strtolower($booking['status']) == 'pending' ? "status-pending" :
                                   (strtolower($booking['status']) == 'completed' ? "status-completed" : "status-cancelled");
                    echo "<tr>
                           <td>".htmlspecialchars($booking['freelancer_name'])."</td>s
                            <td>".htmlspecialchars($booking['service_name'])."</td>
                            <td class='$statusClass'>".htmlspecialchars($booking['status'])."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No bookings found</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- ✅ Pop-up Notification -->
    <div id="supportPopup" class="popup">✅ Support request sent successfully!</div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (localStorage.getItem('support_success') === 'true') {
                let popup = document.getElementById('supportPopup');
                popup.style.display = "block";

                setTimeout(() => {
                    popup.style.opacity = "1"; 
                }, 100);

                setTimeout(() => {
                    popup.style.opacity = "0";
                    setTimeout(() => popup.style.display = "none", 500);
                    localStorage.removeItem('support_success');
                }, 3000);
            }
        });
    </script>

</body>
</html>
