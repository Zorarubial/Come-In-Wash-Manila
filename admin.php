<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: adminlogin.php");
    exit();
}

require 'mngrconx.php';

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT tbl_user.*, CONCAT(tbl_user.Ufname, ' ', tbl_user.Usname) AS full_name FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Query to count the number of notifications
    $notifStmt = $pdo->query("SELECT COUNT(*) AS notif_count FROM user_activity_audit WHERE acttype = 'flag' AND readstat != 'read'");
    $notifCount = $notifStmt->fetch(PDO::FETCH_ASSOC)['notif_count'];

    // Query to fetch appointments on the current date with user full name
    $currentDate = date('Y-m-d');
    $appointmentsStmt = $pdo->prepare("SELECT washing.*, CONCAT(tbl_user.Ufname, ' ', tbl_user.Usname) AS full_name 
                                       FROM washing 
                                       INNER JOIN tbl_user ON washing.User_id = tbl_user.User_id
                                       WHERE Wdate = ? 
                                       ORDER BY Wtime DESC");
    $appointmentsStmt->execute([$currentDate]);
    $appointments = $appointmentsStmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        body {
            background-image: url('assets/img/backdrop.jpg');
        }

        #dashboard {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            max-width: 400px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        #captiondate {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        #user-profile, #reports, #audit-trail, #logout {
            background-color: #b09b9e;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }

        #user-profile:hover, #reports:hover, #audit-trail:hover, #logout:hover {
            background-color: #ffd700;
        }

        .badge {
            position: relative;
        }

        .badge::after {
            content: attr(data-count);
            position: absolute;
            top: -10px;
            right: -10px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
        }
        .flex-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin: 50px auto;
            width: 90%;
            gap: 20px;
        }
        .daily table {
            width: 100%;
            border-collapse: collapse;
        }

        .daily th, .daily td {
            border: 1px solid #ddd; /* Add border */
            padding: 8px;
            text-align: left;
        }

        .daily th {
            background-color: #32f2f2;
            color: black;
            border-color: black;
        }
        .daily td {  
            color: black;
            border-color: black;
        }
        .no-appointments {
            background-color: yellow; /* Yellow background */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-top: 10px; /* Add some top margin */
        }
        .action-buttons button {
            background-color: #42f2f2;
            color: black;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .action-buttons button:hover {
            background-color: #ffd700;
        }

        .action-buttons button:disabled {
            background-color: #008080; /* Dark teal color */
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <h1>Dashboard</h1>
    <div class="flex-container">

        <div id="dashboard">
            <img src="<?php echo htmlspecialchars($row['Upicture']); ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; box-shadow: 8px 0 12px rgba(0, 0, 0, 0.5); margin-left: 90px;">
            <h2>Manager: <?php echo htmlspecialchars($row['Ufname'] . ' ' . $row['Usname']); ?></h2>
            
            <a id="user-profile" href="adminprofile.php">Admin Profile</a>          
            <a id="employee-monitor" href="monitor_crew.php">View Employee Status</a>
            <a id="reports" href="reports.php">Go to Reports</a>
            <a id="viewclients" href="viewclients.php">View Clients</a>
            <a id="audit-trail" href="audit_trail.php">Users Activity Audit</a>
            <a id="notifs" href="notifs.php" class="badge" data-count="<?php echo $notifCount; ?>"><img src="assets/img/i.png" height="25px"></a>
            <a id="epr" href="epr.php">Employee Performance Review</a>

            <a id="logout" href="logout.php">Logout</a>
        </div>

        <div class="daily">
            <h2 id="captiondate">Appointments today: <?php echo date('Y-m-d'); ?></h2>
            <?php if (!empty($appointments)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th>Wash ID</th>
                            <th>Full Name</th>
                            <th>Category</th>
                            <th>Cost</th>
                            <th>Car Size</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($appointment['Wash_id']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wcategory']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wcost']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wcarsize']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wtime']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wfinish_time']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['Wstatus']); ?></td>
                                <td class="action-buttons">
                                    <form action="mngr_start.php" method="post" style="display:inline;">
                                    <form action="mngr_start.php" method="post" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['Wash_id']); ?>">
                                        <button type="submit" <?php if ($appointment['Wstatus'] === 'cancelled' || $appointment['Wstatus'] === 'finished' || $appointment['Wstatus'] === 'ongoing') echo 'disabled'; ?>>Start</button>
                                    </form>
                                    <form action="mngr_cancel.php" method="post" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['Wash_id']); ?>">
                                        <button type="submit" <?php if ($appointment['Wstatus'] === 'cancelled' || $appointment['Wstatus'] === 'finished' || $appointment['Wstatus'] === 'ongoing') echo 'disabled'; ?>>Cancel</button>
                                    </form>
                                    <form action="mngr_finish.php" method="post" style="display:inline;">
                                        <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['Wash_id']); ?>">
                                        <button type="submit" <?php if ($appointment['Wstatus'] === 'cancelled' || $appointment['Wstatus'] === 'finished') echo 'disabled'; ?>>Finish</button>
                                    </form>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="no-appointments">No appointments scheduled for today.</p>
            <?php } ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var notifsButton = document.getElementById('notifs');
            var notifCount = notifsButton.getAttribute('data-count');
            if (notifCount == 0) {
                notifsButton.classList.remove('badge');
            }

            // Disable buttons for finished appointments
            document.querySelectorAll('tr').forEach(function(row) {
                var statusCell = row.querySelector('td:nth-child(8)'); //  keep status in the 8th column
                if (statusCell && statusCell.textContent.trim() === 'finished') {
                    row.querySelectorAll('.action-buttons button').forEach(function(button) {
                        button.disabled = true;
                    });
                }
            });
        });
    </script>
</body>
</html>

<?php
} else {
    echo "User not found.";
}
?>
