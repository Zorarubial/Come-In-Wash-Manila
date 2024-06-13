<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

// Clear service details from session
unset($_SESSION['service_details']);

header("Location: home.php");
exit();
?>
