<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.html');
    exit();
}

// Retrieve user data
$user = $_SESSION['user'];

// Display user data
echo "<h2>Welcome, " . htmlspecialchars($user['iName']) . "!</h2>";
echo "<p>Email: " . htmlspecialchars($user['iEmail']) . "</p>";
echo "<p>Password (hashed): " . htmlspecialchars($user['iPass']) . "</p>";

// Display all fields in the 'sam' table
echo "<h3>Your Information:</h3>";
echo "<ul>";
echo "<li>Name: " . htmlspecialchars($user['iName']) . "</li>";
echo "<li>Email: " . htmlspecialchars($user['iEmail']) . "</li>";
echo "<li>Password (hashed): " . htmlspecialchars($user['iPass']) . "</li>";
echo "</ul>";

// Add a logout button
echo '<a href="logout.php" class="btn btn-secondary">Logout</a>';
?>