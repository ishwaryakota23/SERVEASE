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

// Get service category and max price from browse_services.php
$category = isset($_GET['category']) ? $_GET['category'] : '';
$max_price = isset($_GET['price']) ? (float)$_GET['price'] : 0;

// Fetch freelancers offering the selected service
$sql = "SELECT id, name, email, phone, category, price, experience, ratings, description 
        FROM freelancers 
        WHERE category = ? AND price <= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sd", $category, $max_price);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Freelancers - ServEase</title>
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

        .dashboard-container {
            max-width: 900px;
            margin: 100px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #332f33;
            color: white;
        }

        .request-btn {
            padding: 8px 12px;
            background-color: #f5a623;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .request-btn:hover {
            background-color: #ff8c00;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="dashboard-container">
        <h2>Available Freelancers for "<?php echo htmlspecialchars($category); ?>"</h2>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Price ($)</th>
                <th>Experience</th>
                <th>Ratings</th>
                <th>Description</th>
                <th>Request Service</th>
            </tr>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><?php echo htmlspecialchars($row['experience']); ?> years</td>
                        <td><?php echo htmlspecialchars($row['ratings']); ?>/5</td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>
                            <form action="request_service.php" method="POST">
                                <input type="hidden" name="freelancer_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="service_category" value="<?php echo htmlspecialchars($category); ?>">
                                <input type="hidden" name="customer_email" value="<?php echo $_SESSION['email']; ?>">
                                <button type="submit" class="request-btn">Request</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr><td colspan="8">No freelancers found for this category.</td></tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
