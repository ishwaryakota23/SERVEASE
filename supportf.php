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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue = trim($_POST['issue']);

    if (!empty($issue)) {
        $stmt = $conn->prepare("INSERT INTO support_requests (freelancer_email, issue) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $issue);
        if ($stmt->execute()) {
            echo "<script>alert('Issue submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error submitting issue.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Please enter your issue.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Support - ServEase</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('subg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding-top: 70px;
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
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #332f33;
        }

        textarea {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            margin-top: 10px;
            background: #f5a623;
            border: none;
            color: white;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #e08e00;
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
        <h2>Submit an Issue</h2>
        <form method="POST" action="support.php">
            <textarea name="issue" rows="5" placeholder="Describe your issue..."></textarea><br>
            <button type="submit">Submit Issue</button>
        </form>
    </div>

</body>
</html>
