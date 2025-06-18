<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sign_up";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = "dileepnarni16@gmail.com"; // Replace with an actual registered email
$sql = "SELECT password FROM customers WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "Stored Hashed Password: " . $row['password'];
} else {
    echo "❌ No account found!";
}

$conn->close();
?>
