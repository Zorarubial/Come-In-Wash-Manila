<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

try {
    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch ongoing and pending washing appointments
    $statusStmt = $pdo->prepare("SELECT * FROM washing WHERE User_id = ? AND (Wstatus = 'ongoing' OR Wstatus = 'pending')");
    $statusStmt->execute([$userId]);
    $appointments = $statusStmt->fetchAll(PDO::FETCH_ASSOC);
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
            text-align: center;
            background-image: url('assets/img/backdrop.jpg');
        }

        .tabs {
            background-color: yellow;
            font-family: 'Poppins', Arial, sans-serif;
            color: black;
            padding: 10px 20px;
            border-radius: 5px;
            border-color: black;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 300px;
        }

        .tabs:disabled {
            background-color: gray;
            cursor: not-allowed;
        }

        .tabs:hover {
            background-color: #ffd700;
        }

        .flex-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            margin: 50px auto;
            width: 90%;
            gap: 20px;
        }

        .container, .recent {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .container {
            width: 300px;
        }

        .recent {
            flex-grow: 1;
        }

        .recent h2 {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #b91b2d;
            color: #ffffff;
            padding: 10px;
            border-radius: 5px;
        }

        .recent table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .recent table, .recent th, .recent td {
            border: 1px solid #000000;
        }

        .recent th, .recent td {
            padding: 8px;
            text-align: left;
        }

        .recent th {
            background-color: #b91b2d;
            color: #ffffff;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #c10c23; /* Shiraz Red */
            text-decoration: none;
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff; /* White text */
            padding: 10px;
            border-radius: 5px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($row['Ufname'] . ' ' . $row['Usname']); ?></h1>

    <div class="flex-container">
        <div class="container">
            <img src="<?php echo htmlspecialchars($row['Upicture']); ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">

            <form action="user_profile.php" method="post">
                <button type="submit" class="tabs">User Profile</button>
            </form>

            <form action="clienthistory.php" method="post">
                <button type="submit" class="tabs">My Wash History</button>
            </form>

            <form id="bookAppointmentForm" action="booking.php" method="post">
                <button id="bookAppointmentButton" type="submit" class="tabs">Book appointment!</button>
            </form>
        </div>

        <div class="recent">
            <h2>Ongoing Appointment</h2>
            <?php if (!empty($appointments)) { ?>
                <table>
                    <tr>
                        <th>Wash ID</th>
                        <th>Category</th>
                        <th>Cost</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>Finish Time</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach ($appointments as $appointment) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($appointment['Wash_id']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wcategory']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wcost']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wdate']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wtime']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wfinish_time']); ?></td>
                            <td><?php echo htmlspecialchars($appointment['Wstatus']); ?></td>
                        </tr>
                    <?php } ?>
                    
                </table>
                <form action="cancel_wash.php" method="post">
                    
                        <button type="submit" class="tabs">Cancel</button>
                        <br>
                        <label> Allowed Cancellations left: 
                        <?php 
                        $leftcancel = 3 - htmlspecialchars($row['Ucancel']); 
                        echo htmlspecialchars($leftcancel); 
                        ?>   
                        </label>

                </form>
            <?php } else { ?>
                <p>No ongoing or pending appointments.</p>
            <?php } ?>      
        </div>
        


    </div>

    <a style="background-color: black;" href="logout.php" onclick="return confirmLogout()">Logout</a>

<script>
        // Fetch ongoing and pending appointments from PHP and convert to JavaScript array
        var appointments = <?php echo json_encode($appointments); ?>;

        // Function to check if there are any ongoing or pending appointments
        function hasOngoingOrPendingAppointments(appointments) {
            for (var i = 0; i < appointments.length; i++) {
                if (appointments[i].Wstatus === 'ongoing' || appointments[i].Wstatus === 'pending') {
                    return true; // Return true if there is an ongoing or pending appointment
                }
            }
            return false; // Return false if there are no ongoing or pending appointments
        }

        // Disable the "Book Appointment" button if there are ongoing or pending appointments
        document.addEventListener('DOMContentLoaded', function() {
            var bookAppointmentButton = document.getElementById('bookAppointmentButton');
            if (hasOngoingOrPendingAppointments(appointments)) {
                bookAppointmentButton.disabled = true;
                bookAppointmentButton.title = "You have an ongoing or pending appointment.";
                alert("You have an ongoing or pending appointment. You cannot book a new appointment until it is completed.");
            }
        });

         function confirmLogout() {
            return confirm("Are you sure you want to log out?");
        }
    </script>
</body>
</html>
<?php
} else {
    echo "User not found.";
}
?>
