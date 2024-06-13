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
    $stmt = $pdo->query('SELECT * FROM tbl_user WHERE Utype = 1 ORDER BY User_id DESC');
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Customer Accounts</title>
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
            color: black;
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
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button[type="submit"]:hover {
            background-color: #45a049; /* Darker green */
        }

    </style>
</head>
<body>
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
        <a href="admin.php" class="back-to-dashboard">Back to Dashboard</a>
    </div>
    <div class="container">
        <h2>Customer Accounts</h2>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Full name</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for actions -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($account['User_id']); ?></td>
                        <td><?php echo htmlspecialchars($account['Ufname'] . " " . $account['Usname']); ?></td>
                        <td><?php echo htmlspecialchars($account['Ucreation']); ?></td>
                        <td><?php echo htmlspecialchars($account['Ustatus']); ?></td>
                        <td >
                            <?php if ($account['Ustatus'] != 'active'): ?>
                                <form action="reactivate_user.php" method="POST">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($account['User_id']); ?>">
                                    <button type="submit">re/activate</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
