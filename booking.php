<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require 'custconxpdo.php';

$userId = $_SESSION['user_id'];

// Check the user's cancel count
$cancelCountStmt = $pdo->prepare("SELECT Ucancel FROM tbl_user WHERE user_id = ?");
$cancelCountStmt->execute([$userId]);
$cancelCount = $cancelCountStmt->fetchColumn();
$cancelLimitReached = $cancelCount >= 3;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Car Wash Reservation</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-size: cover;
            background-color: #b09b9e;
            color: #3c3c34; /* Armadillo */
            margin-left: 0;
            padding: 0;
        }
        .container {
            background-color: rgba(60, 60, 52, 0.5);
            padding: 20px;
            border-radius: 10px;
            width: 390px;
            margin: 50px auto;
            box-shadow: 0px 0px 10px rgba(255,255,255,0.1);
        }
        .b-label {
            color: black;
        }
    </style>
    <script>
        function updateCost() {
            var category = document.getElementById("category").value;
            var cost;

            if (category === "deluxe") {
                cost = 150;
            } else if (category === "premium") {
                cost = 200;
            } else if (category === "ultimate") {
                cost = 250;
            }

            var addOnsCost = 0;
            var checkboxes = document.querySelectorAll('input[name="addons[]"]:checked');
            checkboxes.forEach((checkbox) => {
                addOnsCost += 50;
            });

            document.getElementById("cost").value = cost + addOnsCost;
        }

        function calculateFinishTime() {
            var carSize = document.getElementById("car_size").value;
            var timeInput = document.getElementById("time").value;

            if (!timeInput) {
                return; // Exit if no time is selected
            }

            var time = timeInput.split(':');
            var hours = parseInt(time[0]);
            var minutes = parseInt(time[1]);

            if (carSize === "small") {
                minutes += 5;
            } else if (carSize === "regular") {
                minutes += 8;
            } else if (carSize === "large") {
                minutes += 10;
            }

            while (minutes >= 60) {
                minutes -= 60;
                hours += 1;
            }

            var finishTime = ('0' + hours).slice(-2) + ':' + ('0' + minutes).slice(-2);
            document.getElementById("finish_time").value = finishTime;
        }

        document.getElementById("date").min = new Date().toISOString().split("T")[0];


    function disablePastTimes() {
        var now = new Date();
        var currentHour = now.getHours();
        var currentMinute = now.getMinutes();

        // Convert current time to 24-hour format
        currentHour = ('0' + currentHour).slice(-2);
        currentMinute = ('0' + currentMinute).slice(-2);

        // Set the minimum time to the current time
        document.getElementById("time").min = currentHour + ':' + currentMinute;
        }

        window.onload = disablePastTimes;


    </script>
</head>
<body>
    <h1>Car Wash Reservation</h1>
    <div class="container">
        <?php if ($cancelLimitReached): ?>
            <p class="alert">You have reached the maximum cancellation limit. You cannot make a new booking at this time.</p>
        <?php endif; ?>
        <form method="post" action="invoice.php" >
            <label for="category" class="b-label">Wash Category:</label>
            <select id="category" name="category" onchange="updateCost()" required style="font-family: 'Poppins', Arial, sans-serif;">
                <option value="deluxe">Deluxe</option>
                <option value="premium">Premium</option>
                <option value="ultimate">Ultimate</option>
            </select>
            <br>

            <label class="b-label">Add-ons:</label><br>
            <input type="checkbox" id="helmet_cleaning" name="addons[]" value="helmet_cleaning" onchange="updateCost()">
            <label for="helmet_cleaning">Helmet Cleaning</label><br>
            <input type="checkbox" id="armor_wax" name="addons[]" value="armor_wax" onchange="updateCost()">
            <label for="armor_wax">Armor Wax</label><br>
            <input type="checkbox" id="ultimate_wax" name="addons[]" value="ultimate_wax" onchange="updateCost()">
            <label for="ultimate_wax">Ultimate Wax</label><br>

            <label class="b-label" for="cost">Cost:</label>
            <input type="number" id="cost" name="cost" readonly style="font-family: 'Poppins', Arial, sans-serif;" value="150">
            <br>

            <label class="b-label" for="car_size">Car Size:</label>
            <select id="car_size" name="car_size" onchange="calculateFinishTime()" required style="font-family: 'Poppins', Arial, sans-serif;">
                <option value="small">Small</option>
                <option value="regular">Regular</option>
                <option value="large">Large</option>
            </select>
            
            <br>

            <label class="b-label" for="date">Preferred Date:</label>
            <input type="date" id="date" name="date" required style="font-family: 'Poppins', Arial, sans-serif;" min="<?php echo date('Y-m-d'); ?>">

            <br>

            <label class="b-label" for="time">Preferred Time:</label>
            <input type="time" id="time" name="time" min="08:00" max="22:00" onchange="calculateFinishTime()" required style="font-family: 'Poppins', Arial, sans-serif;">

            <br>

            <label class="b-label" for="notes_before">Notes:</label>
            <br>
            <textarea id="notes_before" name="notes_before" rows="4" cols="50"></textarea>
            <br>

            <input type="hidden" id="finish_time" name="finish_time">

            <button type="submit" style="font-family: 'Poppins', Arial, sans-serif;" <?php if ($cancelLimitReached) echo 'disabled'; ?>>Reserve</button>
        </form>
    </div>
    <a href="home.php" style="font-family: 'Poppins', Arial, sans-serif;">Back</a>
</body>
</html>
