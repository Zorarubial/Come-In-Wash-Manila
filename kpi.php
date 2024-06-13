<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit();
}

// Include database connection
require 'custconxpdo.php';

// Fetch audit trail data
try {
    $stmt = $pdo->query('SELECT * FROM tbl_user WHERE Utype = 2');
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
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
    </div>
    <div class="container">
        <h2>Audit Trail</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First name</th>
                    <th>Second name</th>
                    <th>User type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($crews as $crew): ?>
                    <tr>
                        <td><?php echo ($crew['User_id']); ?></td>
                        <td><?php echo ($crew['Ufname']); ?></td>
                        <td><?php echo ($crew['Usname']); ?></td>
                        <td><?php echo ($crew['Utype']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="admin.php">Back to Dashboard</a>
</body>
</html>
    