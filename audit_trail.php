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
    // Initialize the WHERE clause
    $whereClause = '';

    // Prepare an array to hold parameter values for prepared statement
    $params = array();

    // Check if form is submitted and filters are selected
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['user_id']) && isset($_GET['activity'])) {
        // Check if user selected specific user ID
        if (!empty($_GET['user_id'])) {
            $whereClause .= " WHERE a.user_id = ?";
            $params[] = $_GET['user_id'];
        }

        // Check if user selected specific activity
        if (!empty($_GET['activity'])) {
            // Check if WHERE clause already has conditions
            if (!empty($whereClause)) {
                $whereClause .= " AND a.activity = ?";
            } else {
                $whereClause .= " WHERE a.activity = ?";
            }
            $params[] = $_GET['activity'];
        }
    }

    // Prepare SQL query with dynamic WHERE clause
    $sql = "SELECT a.id, a.user_id, a.activity, a.activity_time, u.Ufname, u.Usname
            FROM user_activity_audit AS a
            LEFT JOIN tbl_user AS u ON a.user_id = u.User_id";
    if (!empty($whereClause)) {
        $sql .= $whereClause;
    }
    $sql .= " ORDER BY a.id DESC";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Fetch audit trail data
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
    <title>Audit Trail</title>
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

         button[type="submit"] {
            background-color: black;
            color: red;
            box-shadow: whitesmoke;
            border-radius: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- HTML content here -->
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
        <a class="back-to-dashboard" href="admin.php">Back to Dashboard</a>
    </div>
    <div class="container">
        <h2>User Activity Log</h2>

        <!-- Filter form -->
        <form action="" method="get">
            <label for="user_id">Filter by User ID:</label>
            <input type="text" name="user_id" id="user_id">
                
            <label for="activity">Filter by Activity:</label>
            <select name="activity" id="activity">
                <option value="">All</option>
                <option value="login">Login</option>
                <option value="failed attempt">Failed Log In</option>
                <option value="login locked">Log In Locked</option>
                <option value="logout">Logout</option>
                <option value="profile update">Profile Update</option>
                <option value="upload pfp">Profile Upload</option>
                <option value="User registered">Registration</option>
                <option value="book">Reserve</option>
                <option value="cancelled">Admin Cancelled</option>
                <option value="user cancelled">User Cancelled</option>
                <option value="cancel">Limit</option>
            </select>
            
            <button type="submit">Apply Filter</button>
        </form>

        <!-- Audit trail table -->
        <table>
            <thead>
                <tr>
                    <th>Activity ID</th>
                    <th>User ID</th>
                    <th>Client Name</th> <!-- New column for user name -->
                    <th>Action</th>
                    <th>Date and Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditTrails as $trail): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($trail['id']); ?></td>
                        <td><?php echo htmlspecialchars($trail['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($trail['Ufname'] . ' ' . $trail['Usname']); ?></td> <!-- Display user's full name -->
                        <td><?php echo htmlspecialchars($trail['activity']); ?></td>
                        <td><?php echo htmlspecialchars($trail['activity_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
