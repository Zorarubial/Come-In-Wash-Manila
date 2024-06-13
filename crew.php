<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if ($row) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>

        body {
            
            background-image: url('assets/img/backdrop.jpg');
            
        }

        #reports {
            background-color: yellow;
            font-family: 'Poppins', Arial, sans-serif;
            color: black;
            padding: 10px 20px;
            
            border-radius: 5px;
            border-color: black;
            cursor: pointer;
            transition: background-color 0.3s ease; 
            margin-left: 20px;
        }

        #reports:hover {
            background-color: #ffd700;
        }
        
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $row['Ufname'] . ' ' . $row['Usname']; ?></h1>
    <table>
        <tr>
            <th>Field</th>
            <th>User Data</th>
        </tr>
        <tr>
            <td>User ID:</td>
            <td><?php echo $row['User_id']; ?></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td><?php echo $row['Uemail']; ?></td>
        </tr>
        <tr>
            <td>Mobile:</td>
            <td><?php echo $row['Umobile']; ?></td>
        </tr>
        <tr>
            <td>City:</td>
            <td><?php echo $row['Ucity']; ?></td>
        </tr>
        <tr>
            <td>Province:</td>
            <td><?php echo $row['Uprovince']; ?></td>
        </tr>
        <tr>
            <td>Account creation:</td>
            <td><?php echo $row['Ucreation']; ?></td>
        </tr>
 
    </table>

    <form action="reports.php" method="post">
        <button id="reports" type="submit">Go to Reports</button>
    </form>

    <form action="booking.php" method="post">
        <button id="reports" type="submit">Book appointment!</button>
    </form>


    <a href="logout.php">Logout</a>
</body>
</html>
<?php
} else {
    echo "User not found.";
}
?>