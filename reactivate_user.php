<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Include database connection
require 'mngrconx.php';

// Check if user_id is provided
if (!isset($_POST['user_id'])) {
    echo "User ID not provided.";
    exit();
}

$user_id = $_POST['user_id'];

// Update user status to active
try {
    $stmt = $pdo->prepare("UPDATE tbl_user SET Ustatus = 'active' WHERE User_id = :user_id");
    $stmt->execute(['user_id' => $user_id]);
    header('Location: viewclients.php'); // Redirect back to customer_accounts.php after reactivation
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
