<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require 'custconxpdo.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the wash ID and rating from the form
    $wash_id = $_POST['wash_id'];
    $rating = $_POST['rating'];

    try {
        // Check if a rating already exists
        $sql_check = "SELECT Wnotesafter FROM washing WHERE Wash_id = :wash_id AND User_id = :user_id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':wash_id', $wash_id, PDO::PARAM_INT);
        $stmt_check->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt_check->execute();
        $existing_rating = $stmt_check->fetchColumn();

        if ($existing_rating) {
            // Redirect back to the client history page if already rated
            header("Location: clienthistory.php");
            exit();
        }

        // SQL query to update the rating for the specified wash
        $sql = "UPDATE washing SET Wnotesafter = :rating WHERE Wash_id = :wash_id AND User_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':wash_id', $wash_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        // Redirect back to the client history page
        header("Location: clienthistory.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: clienthistory.php");
    exit();
}
?>
