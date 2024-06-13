<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

// Pagination
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

// Calculate offset for pagination
$offset = ($page - 1) * $limit;

$sql = "SELECT washing.*, tbl_user.*
        FROM washing
        LEFT JOIN tbl_user ON washing.User_id = tbl_user.User_id
        ORDER BY washing.Wdate DESC
        LIMIT :limit OFFSET :offset";

try {

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $wash_history = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        body {
            background-color: black;
            height: 100%;
            overflow-y: auto;
        }

         #download {
            background-color: blue;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease; 
            margin-left: 20px;
        }
        #download:hover {
            background-color: #ffd700;
        }
    </style>
    <title>Wash History</title>
</head>
<body>
    <h1>Wash History</h1>
    <?php if (!empty($wash_history)): ?>
        <table>
            <thead>
            <tr>
                <th>Wash Date</th>
                <th>Wash Time</th>
                <th>Wash ID</th>
                <th>Wash Category</th>
                <th>Wash Cost</th>
                <th>Wash Status</th>
                <th>Customer Name</th>
               
            </tr>
        </thead>
        <tbody>

            <?php $currentMonth = '';
            foreach ($wash_history as $wash):
                $washDate = date('F Y', strtotime($wash['Wdate']));
                if ($currentMonth != $washDate) {
                    $currentMonth = $washDate;
                    echo '<tr style="background-color: blue;"><td colspan="7"><strong>' . $currentMonth . '</strong></td></tr>';
            }
             ?>
                <tr>
                    <td><?php echo $wash['Wdate']; ?></td>
                    <td><?php echo $wash['Wtime']; ?></td>
                    <td><?php echo $wash['Wash_id']; ?></td>
                    <td><?php echo $wash['Wcategory']; ?></td>
                    <td><?php echo $wash['Wcost']; ?></td>                 
                    <td><?php echo $wash['Wstatus']; ?></td>
                    <td><?php echo $wash['Ufname'] . " " . $wash['Usname']; ?></td>
                    
                </tr>
            <?php endforeach; ?>
              </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
               <li class="page-item <?php echo ($page <= 1 ? 'disabled' : ''); ?>">
                  <a class="page-link" href="?page=<?php echo ($page - 1); ?>" tabindex="-1">Previous</a>
               </li>
               <li class="page-item">
                  <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
               </li>
            </ul>
        </nav>
        <a id="download" href="history_pdf.php" target="_blank" class="btn btn-primary">Download PDF</a>
    <?php else: ?>
        <p style="color: white;">No wash history available.</p>
    <?php endif; ?>
    <a href="reports.php">Back</a>
</body>
</html>
