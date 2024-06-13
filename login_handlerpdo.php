<?php
date_default_timezone_set('Asia/Manila');
require 'custconxpdo.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$email = $_POST['email'];
$pass = $_POST['password'];

// Prepare a query to fetch user by email
$stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE Uemail = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $hashed_pass = $user['Upass'];
    $lockout_time = strtotime($user['Ulock_time']);
    $current_time = time();
    $lockout_duration = 30 * 60; // 30 minutes in seconds

    // Check if the account is locked and if the lockout period has expired
    if ($user['Ustatus'] == 'locked' && ($current_time - $lockout_time) < $lockout_duration) {
        $remaining_lockout = $lockout_duration - ($current_time - $lockout_time);
        header("Location: locked.php?remaining_lockout=$remaining_lockout");
        exit();
    } elseif ($user['Ustatus'] == 'locked' && ($current_time - $lockout_time) >= $lockout_duration) {
        // Unlock account if the lockout period has expired
        unlockUserAccount($pdo, $user['User_id']);
        $user['Ustatus'] = 'active';
        $user['Ulog_attempt'] = 0;
    }

    if (password_verify($pass, $hashed_pass)) {
        $_SESSION['user_id'] = $user['User_id'];
        $_SESSION['user_type'] = $user['Utype'];
        $_SESSION['user_fname'] = $user['Ufname'];
        $_SESSION['user_sname'] = $user['Usname'];

        // Reset login attempt count and status if login successful
        if ($user['Ustatus'] == 'locked') {
            $sql_reset_attempts = "UPDATE tbl_user SET Ustatus = 'active', Ulog_attempt = 0 WHERE User_id = ?";
            $stmt_reset_attempts = $pdo->prepare($sql_reset_attempts);
            $stmt_reset_attempts->execute([$user['User_id']]);
        }

        // Log successful login activity
        logActivity($pdo, $user['User_id'], 'login', 'success');

        if ($user['Utype'] == 1) {
            header("Location: home.php");
        } else if ($user['Utype'] == 2) {
            header("Location: admin.php");
        } else if ($user['Utype'] == 3) {
            header("Location: crew.php");
        }
    } else {
        // Log failed login attempt
        logActivity($pdo, $user['User_id'], 'login', 'failed');

        // Increment login attempt count
        incrementLoginAttempts($pdo, $user['User_id']);

        // Lock user account if attempts reach 3
        if ($user['Ulog_attempt'] >= 3) {
            lockUserAccount($pdo, $user['User_id']);
            header("Location: locked.php?remaining_lockout=1800"); // Redirect immediately after locking the account
            exit();
        }

        header("Location: loginerror.php");
    }
} else {
    header("Location: loginerror.php");
}

// Function to log user activity
function logActivity($pdo, $user_id, $activity, $acttype) {
    $activity_time = date("Y-m-d H:i:s");
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO user_activity_audit (user_id, activity, acttype, activity_time, ip_address) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $activity, $acttype, $activity_time, $ip_address]);
}

// Function to increment login attempts
function incrementLoginAttempts($pdo, $user_id) {
    $sql = "UPDATE tbl_user SET Ulog_attempt = Ulog_attempt + 1 WHERE User_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
}

// Function to lock user account
function lockUserAccount($pdo, $user_id) {
    $lock_time = date("Y-m-d H:i:s");
    $sql = "UPDATE tbl_user SET Ustatus = 'locked', Ulock_time = ? WHERE User_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lock_time, $user_id]);

    // Log the lockout event
    logActivity($pdo, $user_id, 'login locked', 'flag');
}

// Function to unlock user account
function unlockUserAccount($pdo, $user_id) {
    $sql = "UPDATE tbl_user SET Ustatus = 'active', Ulog_attempt = 0 WHERE User_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
}
?>
