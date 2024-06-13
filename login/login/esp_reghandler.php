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

// Function to validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password
function validate_password($password) {
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";
    return preg_match($pattern, $password);
}

// Function to validate name
function validate_name($name) {
    return preg_match("/^[a-zA-Z\s]+$/", $name);
}

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $iName = $_POST['iName'];
    $iEmail = $_POST['iEmail'];
    $iPass = $_POST['iPass'];

    // Validate name, email, and password
    if (!validate_name($iName)) {
        die("Invalid name.");
    }
    if (!validate_email($iEmail)) {
        die("Invalid email.");
    }
    if (!validate_password($iPass)) {
        die("Invalid password.");
    }

    // Hash the password for security
    $hashedPass = password_hash($iPass, PASSWORD_BCRYPT);

    // Prepare an SQL statement to insert the form data into the 'sam' table
    $stmt = $pdo->prepare("INSERT INTO sam (iName, iEmail, iPass) VALUES (?, ?, ?)");

    // Execute the statement
    $stmt->execute([$iName, $iEmail, $hashedPass]);

    // Redirect to a confirmation page or show a success message
    echo "Registration successful! Thank you for registering, " . htmlspecialchars($iName) . ".";
}
?>