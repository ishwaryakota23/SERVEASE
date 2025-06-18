
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];
    $description = trim($_POST['description']);

    if (!empty($description)) {
        // ✅ Debugging: Check if data is received
        echo "<script>console.log('Email: $email, Description: $description');</script>";

        // ✅ Insert into customer_support
        $sql = "INSERT INTO customer_support (email, description, status) VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Prepare failed: " . $conn->error); // Debugging SQL error
        }

        $stmt->bind_param("ss", $email, $description);
        
        if ($stmt->execute()) {
            // ✅ Check if insert was successful
            if ($stmt->affected_rows > 0) {
                echo "<script>
                        localStorage.setItem('support_success', 'true');
                        window.location.href = 'support.php'; 
                      </script>";
            } else {
                echo "<script>alert('Insertion failed: No rows affected.');</script>";
            }
        } else {
            die("Execution failed: " . $stmt->error); // Debugging SQL error
        }

        $stmt->close();
    } else {
        echo "<script>alert('Description cannot be empty!');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support - ServEase</title>
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
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #f5a623;
            text-decoration: underline;
        }

        .support-container {
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

        .support-form {
            margin-top: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            resize: none;
        }

        button {
            background: #28a745;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        button:hover {
            background: #218838;
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

    <div class="support-container">
        <h2>Customer Support</h2>
        <p>If you have any issues or complaints, let us know below.</p>

        <form class="support-form" action="dbcust.php" method="POST">
            <label for="description">Describe your issue:</label>
            <textarea id="description" name="description" placeholder="Enter your complaint..." required></textarea>
            <button type="submit">Submit Request</button>
        </form>
    </div>

    <!-- ✅ Pop-up Notification -->
    <div id="supportPopup" class="popup">✅ Support request submitted successfully!</div>

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
