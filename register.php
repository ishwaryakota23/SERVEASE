<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sign_up"; // Database where customers and freelancers are stored

// ✅ Connect to the `sign_up` database
$conn = new mysqli($servername, $username, $password, $dbname);

// ✅ Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password

    // ✅ Identify user type and extra fields
    if (isset($_POST['user_type']) && $_POST['user_type'] == "customer") {
        $table_name = "customers";
        $extra_columns = "phone, address";
        $extra_values = "'" . mysqli_real_escape_string($conn, $_POST['phone']) . "', '" . mysqli_real_escape_string($conn, $_POST['address']) . "'";
    } elseif (isset($_POST['user_type']) && $_POST['user_type'] == "freelancer") {
        $table_name = "freelancers";
        $extra_columns = "phone, service_category, experience";
        $extra_values = "'" . mysqli_real_escape_string($conn, $_POST['phone']) . "', '" . mysqli_real_escape_string($conn, $_POST['service_category']) . "', '" . mysqli_real_escape_string($conn, $_POST['experience']) . "'";
    } else {
        echo "<script>alert('Invalid user type!'); window.location.href='register.html';</script>";
        exit();
    }

    // ✅ Check if the email already exists in the chosen table
    $check_email_sql = "SELECT email FROM $table_name WHERE email='$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered! Try logging in.'); window.location.href='login.html';</script>";
        exit();
    }

    // ✅ Insert new user into the correct table
    $sql = "INSERT INTO $table_name (name, email, password, $extra_columns) VALUES ('$name', '$email', '$password', $extra_values)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Signup successful! Redirecting to log in.');
                window.location.href='login.html';
              </script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

// ✅ Close the database connection
$conn->close();
?>


