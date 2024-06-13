<?php
// Start the session to get the current user's ID
session_start();

// Assuming the user's ID is stored in the session as 'user_id'
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Include the database connection file
require 'custconxpdo.php';

try {
    // SQL query to fetch the wash history for the current user
    $sql = "SELECT * FROM washing WHERE User_id = :user_id AND Wstatus != 'ongoing' ORDER BY Wdate DESC, Wtime DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wash History</title>
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        tr:nth-child(even) {
            background-color: mintcream;
        }
        #maya_payment_form input[type="submit"] {
            background-color: yellow;
            color: black;
            border-radius: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            margin-left: 40px;
            margin-top: 20px;
        }

    </style>
    <script>
        function confirmRating() {
            return confirm('Once the rating is saved, it cannot be changed. Do you want to proceed?');
        }
    </script>
</head>
<body>
    <h1>My Wash History</h1>
    <table border="1">
        <tr>
            <th>Wash Date</th>
            <th>Wash Time</th>
            <th>Reference #</th>
            <th>Wash Category</th>
            <!-- <th>Add ons</th> -->
            <th>Wash Cost</th>
            <th>Wash Status</th>
            <th>Rate</th>
        </tr>
        <?php
        // Check if there are any records
        if (!empty($result)) {
            // Output data of each row
            foreach ($result as $wash) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($wash['Wdate']) . "</td>";
                echo "<td>" . htmlspecialchars($wash['Wtime']) . "</td>";
                echo "<td>" . htmlspecialchars($wash['Wash_id']) . "</td>";
                echo "<td>" . htmlspecialchars($wash['Wcategory']) . "</td>";
                //echo "<td>" . htmlspecialchars($wash['Waddons']) . "</td>";
                echo "<td>" . htmlspecialchars($wash['Wcost']) . "</td>";
                echo "<td>" . htmlspecialchars($wash['Wstatus']) . "</td>";
                echo "<td>";
                if ($wash['Wnotesafter']) {
                    // Disable rating if it already exists
                    echo htmlspecialchars($wash['Wnotesafter']);
                } else {
                    // Allow rating if not yet rated
                    echo "<form action='rate_wash.php' method='post' onsubmit='return confirmRating();'>";
                    echo "<select name='rating'>";
                    for ($i = 0; $i <= 5; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    echo "</select>";
                    echo "<input type='hidden' name='wash_id' value='" . htmlspecialchars($wash['Wash_id']) . "'>";
                    echo "<input type='submit' value='Rate'>";
                    echo "</form>";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No wash history found.</td></tr>";
        }
        ?>
    </table>
    <a href="home.php">Back to Home</a>
</body>
</html>



