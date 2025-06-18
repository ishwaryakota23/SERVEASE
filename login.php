<?php
$servername = "localhost";
$username = "root";
$password = "";

// Connect to login_db database
$conn = new mysqli($servername, $username, $password, "login_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $user_type = $_POST['user_type']; // Identify if it's a customer or freelancer login

    if ($user_type == "customer") {
        $sql = "SELECT * FROM customer_login WHERE email='$email'";
    } else {
        $sql = "SELECT * FROM freelancer_login WHERE email='$email'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['user_type'] = $user_type;
            echo "Login successful! Redirecting...";
            
            // Redirect user to respective dashboard
            if ($user_type == "customer") {
                header("Location: customer_dashboard.php");
            } else {
                header("Location: freelancer_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password! <a href='login.html'>Try Again</a>";
        }
    } else {
        echo "No account found with this email! <a href='signup.html'>Sign Up</a>";
    }
}

$conn->close();
?>
