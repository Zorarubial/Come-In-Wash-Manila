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
    $stmt = $pdo->query('SELECT * FROM user_activity_audit WHERE acttype = "flag" ORDER BY activity_time DESC');
    $auditTrails = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Notifications</title>
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
        <h2>Notifications</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                    <th>Type</th>
                    <th>Mark as Read</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditTrails as $trail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($trail['id']); ?></td>
                        <td><?php echo htmlspecialchars($trail['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($trail['activity']); ?></td>
                        <td><?php echo htmlspecialchars($trail['activity_time']); ?></td>
                        <td><?php echo htmlspecialchars($trail['acttype']); ?></td>

                        <!-- mark as read button with, if else -->
                        <td style="text-align: center;">
                            <?php if ($trail['readstat'] != 'read'): ?>
                                <form action="mark_as_read.php" method="post" style="display:inline;">
                                    <input type="hidden" name="audit_id" value="<?php echo $trail['id']; ?>">
                                    <button type="submit" class="mark-read">Mark as read</button>
                                </form>
                            <?php else: ?>
                                ✅
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
</body>
</html>
