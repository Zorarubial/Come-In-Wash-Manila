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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Landing Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <!-- User Greeting -->
        <h2>Welcome, <?php echo htmlspecialchars($user['iName']); ?>!</h2>
        <div class="row">
            <div class="col-md-6">
                <!-- User Information -->
                <h3>Your Information:</h3>
                <ul>
                    <li>Email: <?php echo htmlspecialchars($user['iEmail']); ?></li>
                    <li>Password (hashed): <?php echo htmlspecialchars($user['iPass']); ?></li>
                </ul>
            </div>
        </div>
        <!-- Logout Button -->
        <div class="row">
            <div class="col-md-6">
                <a href="logout.php" class="btn btn-secondary">Logout</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>