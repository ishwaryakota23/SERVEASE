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
$sql = "SELECT name, phone, address FROM customers WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $phone = $row['phone'];
    $address = $row['address'];
} else {
    echo "<script>alert('User data not found!');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - ServEase</title>
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
            max-width: 600px;
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
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
    </div>

</body>
</html>
