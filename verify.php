<?php
$stored_hash = '$2y$10$hpv8YPt9EoQEBXPlIUSme.8g9vdc3APAC64CJ8IIakl'; // Replace with actual hash from database
$entered_password = "dileep"; // Replace with the password you used in signup

if (password_verify($entered_password, $stored_hash)) {
    echo "✅ Password verification successful!";
} else {
    echo "❌ Password verification failed!";
}
?>
