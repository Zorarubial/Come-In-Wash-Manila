<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Account Locked</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background-size: cover;
            background-position: center;
            animation: changeBackground 15s infinite;
        }

        @keyframes changeBackground {
            0% { background-image: url('assets/img/carwash_vip.jpg'); }
            33% { background-image: url('assets/img/carwash_basic.jpg'); }
            66% { background-image: url('assets/img/bikewash.jpg'); }
            100% { background-image: url('assets/img/carwash_vip.jpg'); }
        }

        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 50px;
            background: rgba(0, 0, 0, 0.9);
            border-radius: 10px;
            margin: 10% auto;
            width: 80%;
            max-width: 600px;
        }

        #countdown {
            font-size: 2em;
            font-weight: bold;
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
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var countdown = <?php echo isset($_GET['remaining_lockout']) ? intval($_GET['remaining_lockout']) : 0; ?>;
            var countdownElement = document.getElementById('countdown');

            function updateCountdown() {
                var minutes = Math.floor(countdown / 60);
                var seconds = countdown % 60;

                if (seconds < 10) {
                    seconds = '0' + seconds;
                }

                countdownElement.textContent = minutes + ':' + seconds;

                if (countdown > 0) {
                    countdown--;
                    setTimeout(updateCountdown, 1000);
                } else {
                    location.reload(); // Refresh the page when the countdown is over
                }
            }

            updateCountdown();
        });
    </script>
</head>
<body>
    <div class="background"></div>
    <div class="content">
        <h1>Your account is locked.</h1>
        <p>Please wait for the cooldown period to expire before trying again.</p>
        <p>Time remaining: <span id="countdown"></span></p>
    </div>
    <a href="login.php" style="text-align:center;">Go back</a>
</body>
</html>
