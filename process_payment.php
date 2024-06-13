<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require 'custconxpdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    
    // Retrieve service details from the session
    $serviceDetails = $_SESSION['service_details'];
    
    // Extract service details
    $category = $serviceDetails['category'];
    $cost = $serviceDetails['cost'];
    $carSize = $serviceDetails['carSize'];
    $date = $serviceDetails['date'];
    $time = $serviceDetails['time'];
    $notesBefore = $serviceDetails['notesBefore'];
    $addons = $serviceDetails['addons'];

    // Calculate finish time based on car size and time
    $finishTime = calculateFinishTime($carSize, $time);

    // Check for overlapping bookings
    if (isOverlap($pdo, $date, $time, $finishTime)) {
        // If there's an overlap, notify the user and exit
        echo "<script>alert('The selected time slot is not available. Please choose a different time.'); window.location.href='booking.php';</script>";
        exit();
    }

    // Set status to 'pending'
    $status = 'pending';

    try {
        $stmt = $pdo->prepare("INSERT INTO washing (User_id, Wcategory, Wcost, Wcarsize, Wdate, Wtime, Wfinish_time, Wnotesbefore, Waddons, Wstatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $category, $cost, $carSize, $date, $time, $finishTime, $notesBefore, $addons, $status]);

        $activity = 'book';
        $activity_time = date("Y-m-d H:i:s");
        $ip_address = $_SERVER['REMOTE_ADDR'];

        $audit_stmt = $pdo->prepare("INSERT INTO user_activity_audit (user_id, activity, activity_time, ip_address) VALUES (?, ?, ?, ?)");
        $audit_stmt->execute([$userId, $activity, $activity_time, $ip_address]);

        // Redirect to appointment_success.php
        header("Location: appointment_success.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}

// Function to calculate finish time based on car size and time
function calculateFinishTime($carSize, $time) {
    $hours = intval(substr($time, 0, 2));
    $minutes = intval(substr($time, 3, 2));

    if ($carSize === "small") {
        $minutes += 5;
    } else if ($carSize === "regular") {
        $minutes += 8;
    } else if ($carSize === "large") {
        $minutes += 10;
    }

    while ($minutes >= 60) {
        $minutes -= 60;
        $hours++;
    }

    return sprintf("%02d:%02d", $hours, $minutes);
}

// Function to check for overlapping bookings
function isOverlap($pdo, $date, $time, $finishTime) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM washing WHERE Wdate = ? AND ((Wtime < ? AND Wfinish_time > ?) OR (Wtime < ? AND Wfinish_time > ?))");
    $stmt->execute([$date, $time, $time, $finishTime, $finishTime]);
    $count = $stmt->fetchColumn();
    return $count > 0;
}
?>
