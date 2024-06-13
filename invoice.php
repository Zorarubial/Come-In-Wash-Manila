<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

require 'custconxpdo.php';

$user_id = $_SESSION['user_id'];
$user_mobile = '';

try {
    $stmt = $pdo->prepare("SELECT Umobile FROM tbl_user WHERE User_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User not found.");
    }

    $user_mobile = $user['Umobile'];
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['category'];
    $cost = $_POST['cost'];
    $carSize = $_POST['car_size'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notesBefore = $_POST['notes_before'];
    $addons = isset($_POST['addons']) ? implode(', ', $_POST['addons']) : '';

    // Store service details in session
    $_SESSION['service_details'] = [
        'category' => $category,
        'cost' => $cost,
        'carSize' => $carSize,
        'date' => $date,
        'time' => $time,
        'notesBefore' => $notesBefore,
        'addons' => $addons
    ];
} else {
    if (!isset($_SESSION['service_details'])) {
        echo "No service details found.";
        exit();
    }
}

$serviceDetails = $_SESSION['service_details'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Payment</title>
    <style>
        .wrap {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            display: flex;
            justify-content: space-between;
        }
        .payment-form {
            display: none;
            padding: 10px;
            border-radius: 5px;
        }
        /* Styling for GCash payment form */
        #gcash_payment_form {
            background-color: skyblue;
            color: navy;
        }
        #gcash_payment_form input[type="submit"] {
            background-color: navy;
            color: white;
            border-radius: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            margin-left: 40px;
            margin-top: 20px;
        }

        /* Styling for Maya payment form */
        #maya_payment_form {
            background-color: black;
            color: lightgreen;
        }
        #maya_payment_form input[type="submit"] {
            background-color: black;
            color: lightgreen;
            border-radius: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            margin-left: 40px;
            margin-top: 20px;
        }

        select {
            font-family: 'Poppins', Arial, sans-serif;
        }
    </style>
</head>
<body>
    <h1>Payment</h1>
    <div class="wrap">
        <div class="container">
            <div class="left-container" style="padding: 30px; background-color: rosybrown; border-radius: 5px; margin-right: 30px;">
                <img src="assets/img/widelogo.jpg" style="max-height: 100px; max-width: 1000px;">
                <p><b>Category:</b> <?php echo htmlspecialchars($serviceDetails['category']); ?></p>
                <p><b>Cost:</b> <?php echo htmlspecialchars($serviceDetails['cost']); ?></p>
                <p><b>Car Size:</b> <?php echo htmlspecialchars($serviceDetails['carSize']); ?></p>
                <p><b>Date:</b> <?php echo htmlspecialchars($serviceDetails['date']); ?></p>
                <p><b>Time:</b> <?php echo htmlspecialchars($serviceDetails['time']); ?></p>
                <p><b>Notes:</b> <?php echo htmlspecialchars($serviceDetails['notesBefore']); ?></p>
                <p style="max-width: 400px;"><b>Add-ons: </b><?php echo htmlspecialchars($serviceDetails['addons']); ?></p>

                <label for="payment_method"><b>Payment Options:</b></label>
                <select id="payment_method" name="payment_method" required onchange="togglePaymentForm()">
                    <option value=""></option>
                    <option value="gcash">GCash</option>
                    <option value="maya">Maya</option>
                </select>
                <br>
            </div>

            <div class="right-container">
                <div id="gcash_payment_form" class="payment-form">
                    <div id="paymentlogo">
                        <img src="assets/img/gcash.png" style="max-height: 80px; max-width: 200px;">
                        <h2>GCash Payment</h2>
                    </div>
                    <form method="post" action="process_payment.php" onsubmit="return validatePaymentAmount()">
                        <div class="payment-fields">
                            <label for="payment_amount">Payment Amount:</label><br>
                            <input type="text" id="payment_amount" name="payment_amount" required min="<?php echo htmlspecialchars($serviceDetails['cost']); ?>" value="<?php echo htmlspecialchars($serviceDetails['cost']); ?>"><br>
                            <i> include service fee </i>
                        </div>
                        <label for="gcash_number">GCash Number:</label><br>
                        <input type="text" id="gcash_number" name="gcash_number" required value="<?php echo htmlspecialchars($user_mobile); ?>"><br>
                        <input type="submit" value="Pay via GCash">
                    </form>
                </div>

                <div id="maya_payment_form" class="payment-form">
                    <div id="paymentlogo">
                        <img src="assets/img/maya.png" style="max-height: 80px; max-width: 200px;">
                        <h2>Maya Payment</h2>
                    </div>
                    <form method="post" action="process_payment.php" onsubmit="return validatePaymentAmount()">
                        <div class="payment-fields">
                            <label for="payment_amount">Payment Amount:</label><br>
                            <input type="text" id="payment_amount" name="payment_amount" required min="<?php echo htmlspecialchars($serviceDetails['cost']); ?>" value="<?php echo htmlspecialchars($serviceDetails['cost']); ?>"><br>
                            <i> include service fee </i>
                        </div>
                        <label for="maya_account">Maya Account:</label><br>
                        <input type="text" id="maya_account" name="maya_account" required value="<?php echo htmlspecialchars($user_mobile); ?>"><br>
                        <input type="submit" value="Pay via Maya">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <a href="cancel_payment.php">Cancel</a>

    <script>
        function togglePaymentForm() {
            var paymentMethod = document.getElementById("payment_method").value;
            var gcashForm = document.getElementById("gcash_payment_form");
            var mayaForm = document.getElementById("maya_payment_form");
            
            // Hide all forms
            var forms = document.querySelectorAll(".payment-form");
            forms.forEach(function(form) {
                form.style.display = "none";
            });

            // Show the selected form
            if (paymentMethod === "gcash") {
                gcashForm.style.display = "block";
            } else if (paymentMethod === "maya") {
                mayaForm.style.display = "block";
            }
        }

        function validatePaymentAmount() {
            var paymentAmountInput = document.getElementById("payment_amount");
            var minAmount = parseFloat(paymentAmountInput.min);
            var enteredAmount = parseFloat(paymentAmountInput.value);

            if (enteredAmount < minAmount) {
                alert("Payment amount cannot be less than " + minAmount);
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
