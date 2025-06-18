<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != "freelancer") {
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

// ✅ Fetch Freelancer Details
$sql = "SELECT name, phone, service_category, experience FROM freelancers WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone = $row['phone'];
    $service_category = $row['service_category'];
    $experience = $row['experience'];
} else {
    echo "<script>alert('User data not found!');</script>";
}

// ✅ Fetch Service Requests
$request_sql = "SELECT id, customer_email, service_name, date, status FROM service_requests WHERE freelancer_email = ? ORDER BY date DESC";
$stmt = $conn->prepare($request_sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$request_result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard - ServEase</title>
    <link rel="stylesheet" href="ss.css">
    <style>
        body {
            font-family: Arial, sans-serif;
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
            font-weight: bold;
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
            font-weight: bold;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #f5a623;
            text-decoration: underline;
        }

        .dashboard-container {
            max-width: 700px;
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

        /* ✅ Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 16px;
        }

        th {
            background-color: #f5a623;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* ✅ Button Styling */
        .btn {
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
            margin: 2px;
        }

        .accept {
            background-color: green;
            color: white;
        }

        .reject {
            background-color: red;
            color: white;
        }
    </style>
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
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Service Category:</strong> <?php echo htmlspecialchars($service_category); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($experience); ?> years</p>
        
        <h3>Service Requests</h3>
        <table>
            <tr>
                <th>Customer Email</th>
                <th>Service</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php 
            if ($request_result->num_rows > 0) {
                while ($request = $request_result->fetch_assoc()) {
                    echo "<tr>
                            <td>".htmlspecialchars($request['customer_email'])."</td>
                            <td>".htmlspecialchars($request['service_name'])."</td>
                            <td>".htmlspecialchars($request['date'])."</td>
                            <td>".htmlspecialchars($request['status'])."</td>
                            <td>
                                <form action='update_status.php' method='post'>
                                    <input type='hidden' name='request_id' value='".$request['id']."'>
                                    <button type='submit' name='accept' class='btn accept'>Accept</button>
                                    <button type='submit' name='reject' class='btn reject'>Reject</button>
                                </form>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No service requests found</td></tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>
