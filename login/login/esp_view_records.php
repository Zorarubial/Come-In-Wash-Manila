<?php
// Database credentials
$host = 'localhost';
$dbname = 'espino';
$username = 'root'; // Update with your MySQL username
$password = '';

// Connect to the MySQL database
try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch all records from the sam table
$stmt = $pdo->query("SELECT * FROM sam");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Records</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">User's Credentials</h2>
        <!-- Table to display records -->
        <table class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password (Hashed)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display each record as a table row
                foreach ($records as $record) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($record['iName']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['iEmail']) . "</td>";
                    echo "<td>" . htmlspecialchars($record['iPass']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Button to go back to the landing page -->
        <a href="landing.php" class="btn btn-primary">Back to Landing Page</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
