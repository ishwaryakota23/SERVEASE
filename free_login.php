<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$db_signup = "sign_up"; // ✅ Database where freelancers are registered
$db_login = "login_db"; // ✅ Database where login details are stored

// ✅ Connect to the `sign_up` database
$conn_signup = new mysqli($servername, $username, $password, $db_signup);
if ($conn_signup->connect_error) {
    die("Connection failed: " . $conn_signup->connect_error);
}

// ✅ Connect to the `login_db` database
$conn_login = new mysqli($servername, $username, $password, $db_login);
if ($conn_login->connect_error) {
    die("Connection failed: " . $conn_login->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn_signup, $_POST['email']);
    $password = $_POST['password'];

    // ✅ Check if the freelancer exists in `sign_up.freelancers`
    $sql = "SELECT * FROM freelancers WHERE email='$email'";
    $result = $conn_signup->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Debugging Output (Remove after testing)
        echo "Entered Password: " . $password . "<br>";
        echo "Stored Hashed Password: " . $row['password'] . "<br>";

        // ✅ Verify password from `sign_up.freelancers`
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = "freelancer";

            // ✅ Store last login in `login_db.freelancer_login`
            $update_sql = "INSERT INTO freelancer_login (email, password, last_login)
                           VALUES ('$email', '{$row['password']}', NOW())
                           ON DUPLICATE KEY UPDATE last_login = NOW()";

            $conn_login->query($update_sql);

            echo "<script>alert('Login successful! Redirecting...'); window.location.href='dbfree.php';</script>";
        } else {
            echo "<script>alert('Incorrect password! Please try again.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('No account found! Please sign up first.'); window.location.href='signup.html';</script>";
    }
}

// ✅ Close both connections
$conn_signup->close();
$conn_login->close();
?>
