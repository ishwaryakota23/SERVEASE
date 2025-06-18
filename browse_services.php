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

// ✅ Fetch all service categories
$all_categories = [
    "Home Cleaning", "Electrician", "Plumbing", 
    "Salon & Beauty", "Fitness Training", "Tutoring", 
    "Photography", "Web Support"
];

// ✅ Get selected category from dropdown filter
$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

// ✅ Fetch freelancers based on selected category (or all if none selected)
$freelancer_sql = "SELECT name, email, phone, service_category, experience FROM freelancers";
if (!empty($selected_category)) {
    $freelancer_sql .= " WHERE service_category = ?";
}

$stmt = $conn->prepare($freelancer_sql);
if (!empty($selected_category)) {
    $stmt->bind_param("s", $selected_category);
}
$stmt->execute();
$freelancer_result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Services - ServEase</title>
    <style>
        /* ✅ Background Image Fix */
        body {
            font-family: Arial, sans-serif;
            background-image: url(subg.jpg);
            background-size: cover;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        /* ✅ Navigation Bar */
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
            color:white;
        }

        nav .logo {
            font-size: 1.8rem;
            margin-left: 20px;
            font-weight: bold;
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
            font-size: 1.2rem;
            transition: 0.3s;
            color:white;
        }

        /* ✅ Main Container */
        .dashboard-container {
            max-width: 900px;
            margin: 100px auto;
            padding: 30px;
        
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color:white;
        }

        form {
            margin-bottom: 20px;
        }

        select, button {
            padding: 10px;
            margin: 10px 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
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

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
    <h2>Find a Freelancer</h2>

    <!-- ✅ Filter by Category -->
    <form action="browse_services.php" method="GET">
        <label for="category">Filter by Category:</label>
        <select name="category" id="category">
            <option value="">All Categories</option>
            <?php foreach ($all_categories as $category) { ?>
                <option value="<?php echo htmlspecialchars($category); ?>" 
                    <?php echo ($category == $selected_category) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <!-- ✅ Freelancer List -->
    <h3>Available Freelancers</h3>
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service Category</th>
            <th>Experience</th>
            <th>Action</th> <!-- ✅ Added Column for Booking -->
        </tr>
        <?php 
        if ($freelancer_result->num_rows > 0) {
            while ($freelancer = $freelancer_result->fetch_assoc()) {
                echo "<tr>
                <td>".htmlspecialchars($freelancer['name'])."</td>
                <td>".htmlspecialchars($freelancer['email'])."</td>
                <td>".htmlspecialchars($freelancer['phone'])."</td>
                <td>".htmlspecialchars($freelancer['service_category'])."</td>
                <td>".htmlspecialchars($freelancer['experience'])." years</td>
                <td>
                    <form action='book_services.php' method='POST'>
                        <input type='hidden' name='freelancer_name' value='".htmlspecialchars($freelancer['name'])."'>
                        <input type='hidden' name='freelancer_email' value='".htmlspecialchars($freelancer['email'])."'>
                        <input type='hidden' name='service_name' value='".htmlspecialchars($freelancer['service_category'])."'>
                        <button type='submit'>Book Now</button>
                    </form>
                </td>
              </tr>";
        
            }
        } else {
            echo "<tr><td colspan='6'>No freelancers available</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
