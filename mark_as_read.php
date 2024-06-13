<?php
session_start();
require 'custconxpdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['audit_id'])) {
    $auditId = $_POST['audit_id'];

    try {
        $stmt = $pdo->prepare("UPDATE user_activity_audit SET readstat = 'read' WHERE id = ?");
        $stmt->execute([$auditId]);
        header("Location: notifs.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
