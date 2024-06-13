<?php
// Database credentials
$host = 'localhost';
$dbname = 'espino';
$username = 'root';  // Update with your MySQL username
$password = '';

// Connect to the MySQL database
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iEmail = $_POST['iEmail'];
    $iPass = $_POST['iPass'];

    // Prepare a query to fetch user by email
    $stmt = $pdo->prepare("SELECT * FROM sam WHERE iEmail = ?");
    $stmt->execute([$iEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password
    if ($user && password_verify($iPass, $user['iPass'])) {
        // Successful login
        // Start a session and store user data
        session_start();
        $_SESSION['user'] = $user;

        // Redirect to landing page
        header('Location: landing1.php');
        exit();
    } else {
        // Invalid credentials
        echo "<div class='alert alert-danger text-center mt-4'>Incorrect email or password. Please try again.</div>";
        echo "<div class='text-center mt-2'><a href='login.html' class='btn btn-primary'>Back to Login</a></div>";
    }
}
?>