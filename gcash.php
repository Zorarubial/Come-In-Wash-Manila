
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment</title>
    <link rel="stylesheet" href="../css/gcash.css">
    <script src="https://kit.fontawesome.com/1fd0899690.js" crossorigin="anonymous"></script>

</head>
<body>
    <div class="container">
        <div id="paymentlogo">
            <img src="../../../assets/images/GCash_logo.svg.png">
            <h2>GCash Payment</h2>
        </div>

        <button><i class="fa-solid fa-chevron-left"></i>  Back to Payment Methods</button>
            <div class="payment-fields">
                <label for="total_amount">Total Cost:</label><br>
                <input type="text" id="total_amount" disabled><br>
                <label for="payment_amount">Payment Amount:</label><br>
                <input type="text" id="payment_amount" name="payment_amount"  required min="150"><br>
                
                
            </div>
            <label for="gcash_number">GCash Number:</label><br>
            <input type="text" id="gcash_number" name="gcash_number" required><br>
            
        </form>
    </div>
</body>
</html>
