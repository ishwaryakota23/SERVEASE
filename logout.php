<?php
session_start();
session_destroy();
header("Location: ui.html"); // Redirect to home
exit();
?>
