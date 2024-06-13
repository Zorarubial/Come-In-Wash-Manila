<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointmentId = $_POST['appointment_id'];

    try {
        // Update the Wstatus to 'finished'
        $stmt = $pdo->prepare("UPDATE washing SET Wstatus = 'finished' WHERE Wash_id = ?");
        $stmt->execute([$appointmentId]);

        header("Location: admin.php"); // Redirect back to the dashboard
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: admin.php");
    exit();
}
?>
