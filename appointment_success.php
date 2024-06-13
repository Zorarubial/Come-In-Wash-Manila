<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Appointment Success</title>
    <style>
        /* CSS styles */
        body {
            font-family: 'Poppins', Arial, sans-serif; /* Poppins font */
            background-color: #b09b9e; /* Dusty Gray */
            color: #3c3c34; /* Armadillo */
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 20px;
            background-color: #000000; /* black */
            color: #fff; /* White */
            width: 100%;
            text-align: left; /* Align left */
        }
        .header img {
            max-width: 100px; /* Reduce size of logo */
            height: auto;
            margin-right: 20px; /* Add right margin */
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: 50px auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }
        p.success-message {
            color: #008000; /* Green */
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
    </div>
    <div class="container">
        <h2>Appointment Successful!</h2>
        <p class="success-message">Your appointment has been successfully created.</p>
        <p>Now redirecting to home page in a few seconds...</p>
    </div>

    <script>
        // JavaScript to redirect back to home page after 5 seconds
        setTimeout(function() {
            window.location.href = "home.php"; // Replace with your home page URL
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
</body>
</html>
