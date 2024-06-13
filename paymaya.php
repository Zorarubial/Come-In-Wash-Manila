<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paymaya Gateway</title>
    <link rel="stylesheet" href="maya.css">
</head>
<body>
    <div class="container">
        <img id="paymentlogo" src="logo.png" alt="Payment Logo">
        <h2>Payment Information</h2>
        <form>
            <label for="name">Name on Card</label>
            <input type="text" id="name" name="name" required>

            <label for="card-number">Card Number</label>
            <input type="text" id="card-number" name="card-number" required>

            <label for="expiry-date">Expiry Date</label>
            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>

            <label for="cvv">CVV</label>
            <input type="number" id="cvv" name="cvv" required>

            <button type="submit">Pay Now</button>
        </form>
        <span>By clicking "Pay Now", you agree to our <a href="#">Terms and Conditions</a>.</span>
    </div>
</body>
</html>
