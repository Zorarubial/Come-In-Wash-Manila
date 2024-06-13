<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user confirms cancellation
    if (isset($_POST['confirm_cancel'])) {
        try {
            // Update the status of ongoing or pending appointments to cancelled
            $cancelStmt = $pdo->prepare("UPDATE washing SET Wstatus = 'cancelled' WHERE User_id = ? AND Wstatus = 'pending'");
            $cancelStmt->execute([$userId]);
            
            // Increment the U_cancel in tbl_user
            $updateCancelCountStmt = $pdo->prepare("UPDATE tbl_user SET Ucancel = Ucancel + 1 WHERE user_id = ?");
            $updateCancelCountStmt->execute([$userId]);

            // Check the updated cancel count
            $getCancelCountStmt = $pdo->prepare("SELECT Ucancel FROM tbl_user WHERE user_id = ?");
            $getCancelCountStmt->execute([$userId]);
            $cancelCount = $getCancelCountStmt->fetchColumn();

            // Log cancellation activity
            logActivity($pdo, $userId, 'user cancelled');

            // If cancel count reaches 3, log it as a flag in user_activity_audit
            if ($cancelCount >= 3) {
                logActivity($pdo, $userId, 'cancel limit');
            }

            // Redirect to home page after successful cancellation
            header("Location: home.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Function to log user activity
function logActivity($pdo, $user_id, $activity) {
    $activity_time = date("Y-m-d H:i:s");
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO user_activity_audit (user_id, acttype, activity, activity_time, ip_address) VALUES (?, 'flag', ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $activity, $activity_time, $ip_address]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Cancel Appointment</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        button {
            font-family: 'Poppins', Arial, sans-serif;
            width: 1265px;
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #c10c23; /* Shiraz Red */
            text-decoration: none;
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff; /* White text */
            padding: 10px;
        }
        button:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body style="text-align: center;">
    <div class="container" style="margin-left: 50px; margin-right: 50px; ">

        <h2>Cancel Appointment</h2>
        <p>Are you sure you want to cancel your ongoing or pending appointment? Cancelling appointments too many times may result in account restrictions.</p>
        <form action="cancel_wash.php" method="post">
            <button type="submit" name="confirm_cancel">Yes, Cancel Appointment</button>
        </form>
        <a href="home.php">No, Go Back</a>
    </div>
</body>
</html>
