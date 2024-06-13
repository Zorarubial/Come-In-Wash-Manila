<?php
require 'custconxpdo.php';
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    
    // Fetch the user's type
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT Utype FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Log logout activity
    $activity_time = date("Y-m-d H:i:s");
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, 'logout', ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $activity_time, $ip_address]);
    
    // Determine the redirect target based on Utype
    if ($user) {
        if ($user['Utype'] == 2) {
            $redirect_url = 'adminlogin.php';
        } else {
            $redirect_url = 'login.php';
        }
    } else {
        // Default redirect if user type is not found
        $redirect_url = 'login.php';
    }

    // Destroy the session
    session_destroy();

    // Redirect the user to the appropriate login page
    header("Location: $redirect_url");
    exit();
} else {
    // If the user is not logged in, redirect them to the login page
    header("Location: login.php");
    exit();
}
?>
