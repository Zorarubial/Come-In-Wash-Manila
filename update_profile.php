<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $field = $_POST['field'];
    $value = $_POST['value'];

    $validFields = ['Uemail', 'Umobile', 'Ucity', 'Uprovince', 'profile_pic'];

    if (in_array($field, $validFields)) {
        try {
            // Handle profile picture update
            if ($field === 'profile_pic') {
                // Your code to handle profile picture upload and update the database
            } else {
                // Update other fields
                $stmt = $pdo->prepare("UPDATE tbl_user SET $field = ?, last_updated = CURRENT_TIMESTAMP WHERE User_id = ?");
                $stmt->execute([$value, $userId]);
            }

            // Add entry to user activity audit trail
            $activity = 'profile update';
            $activity_time = date("Y-m-d H:i:s");
            $ip_address = $_SERVER['REMOTE_ADDR'];

            $sql = "INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId, $activity, $activity_time, $ip_address]);

            header("Location: user_profile.php"); // Redirect back to user profile page
            exit();
        } catch (PDOException $e) {
            echo "Error updating $field: " . $e->getMessage();
        }
    }
}
?>
