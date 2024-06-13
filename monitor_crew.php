<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit();
}

// Include database connection
require 'mngrconx.php';

// Fetch audit trail data
try {
    $stmt = $pdo->query('SELECT * FROM employee');
    $crews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Employee Status</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #000000;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 20px;
            background-color: #3c3c34;
            color: #ffffff; 
            width: 100%;
            text-align: left;
        }
        .header img {
            max-width: 100px;
            height: auto;
        }
        .container {
            background-color: #3c3c34;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            margin: 50px auto;
            box-shadow: 0px 0px 10px rgba(255,255,255,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ffffff;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #b91b2d;
        }
        tr:nth-child(even) {
            background-color: #4d4d44;
        }
        tr:nth-child(odd) {
            color: #000000;
        }
        .back-to-dashboard {
            /* Float the element to the right */
            float: right;
            /* Set the width of the element */
            width: 300px;
            /* Ensure the element is displayed as an inline-block */
            display: inline-block;
            /* Add some padding if necessary */
            padding: 10px;
            /* Optional: add some styling */
            background-color: #c10c23;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
        <a href="admin.php" class="back-to-dashboard">Back to Dashboard</a>
    </div>
    <div class="container">
        <h2>Employee Status</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Given name</th>
                    <th>Last name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crews as $crew): ?>
                    <tr>
                        <td><?php echo ($crew['employee_id']); ?></td>
                        <td><?php echo ($crew['employee_fname']); ?></td>
                        <td><?php echo ($crew['employee_sname']); ?></td>
                        <td><?php echo ($crew['Estatus']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
