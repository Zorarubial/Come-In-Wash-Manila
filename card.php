<?php 
session_start();
$package_info = $_SESSION['chosen_package'];
$total_reservation_fee = 0;

// Calculate total reservation fee
foreach ($package_info as $res_info) {
    $total_reservation_fee += $res_info['package_res_fee'];
}

$_SESSION['paymeth'] = "Card";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment</title>
    <link rel="stylesheet" href="../css/gcash.css">
    <script src="https://kit.fontawesome.com/1fd0899690.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <style>
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        h2 {
            margin-top: 0;
            color: #333;
        }
        .payment-fields {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-link {
            display: block;
            margin-bottom: 15px;
            color: #007BFF;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .note {
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Card Payment</h2>
        <a class="back-link" href="../payment.php"><i class="fa-solid fa-chevron-left"></i>  Back to Payment Methods</a>
        <form action="../record_transaction.php" method="post">
            <div class="payment-fields">
                <label for="total_amount">Total Reservation Fee:</label>
                <input type="text" id="total_amount" value="<?php echo $total_reservation_fee; ?>" readonly disabled>
                
                <label for="payment_amount">Payment Amount:</label>
                <input type="number" id="payment_amount" value="<?php echo $total_reservation_fee; ?>" name="payment_amount" required min="<?php echo $total_reservation_fee; ?>">
                <span class="note">Please note that reservation fee is non-refundable.</span>
            </div>
            <label for="card_number">Card Number:</label>
            <input type="text" id="card_number" name="card_number" required placeholder="0000 0000 0000 0000">

            <label for="expiry_date">Expiry Date:</label>
            <input type="text" id="expiry_date" name="expiry_date" required placeholder="MM/YY">

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" required placeholder="000">

            <button type="submit">Pay via Card</button>
        </form>
    </div>
    <script>
        $(document).ready(function(){
            $('#card_number').mask('0000 0000 0000 0000');
            $('#expiry_date').mask('00/00');
            $('#cvv').mask('000');
        });
    </script>
</body>
</html>
