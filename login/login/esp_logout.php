<?php
// Start session
session_start();

// Unset user data and destroy the session
unset($_SESSION['user']);
session_destroy();

// Redirect to login page
header('Location: login.html');
exit();
?>