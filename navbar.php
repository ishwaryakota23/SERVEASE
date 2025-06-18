<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
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
