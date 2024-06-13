<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Admin Login Page</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-image: url('assets/img/checker.jpg'); 
            color: #ffffff;
            margin: 0;
            padding: 0;
        }
        .header {
            padding: 20px;
            background-color: #3c3c34; /* Armadillo */
            color: #ffffff; 
            width: 100%;
            text-align: left;
        }
        .header img {
            max-width: 100px; /* Reduce size of logo */
            height: auto;
        }
        .container {
            background-color: #3c3c34;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            margin: 50px auto;
            box-shadow: 0px 0px 10px rgba(255,255,255,0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #ffffff;
        }
        input {
            width: calc(100% - 12px); /* Adjust for input padding */
            margin-bottom: 10px;
            padding: 6px;
            border: 1px solid #ffffff; /* White border */
            border-radius: 3px;
            background-color: #3c3c34;
            color: #ffffff;
        }
        a {
            display: block;
            margin-bottom: 5px;
            color: #000000;
        }
        input[type="submit"] {
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff;
            cursor: pointer;
        }
        .password-wrapper {
            position: relative;
        }
        .password-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            cursor: pointer;
            width: 20px; 
            height: auto; 
            fill: #ffffff;
        }
        .car-wash-info {
            background-color: #3c3c34;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .car-wash-info h3 {
            color: #ffffff;
            margin-top: 0;
        }
        .car-wash-info p {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/img/ciwlogosquare.jpg" alt="CIW Logo">
    </div>
    <div class="container">
        <h2>Admin Log in</h2>
        <form action="login_handleradmin.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required>
                <img src="assets/img/eye.svg" class="password-icon" onclick="togglePasswordVisibility('password')" alt="Show Password">
            </div>
            <p><a href="reset_password.php">Forgot your password?</a></p>
            <input type="submit" value="Login" style="font-family: 'Poppins', Arial, sans-serif;">
            <label style="color: orange;">Invalid credentials!</label>
            <label style="color: orange;">Incorrect email or password</label>            
            <p>Don't have an account yet? <a href="regpage.html" title="Goes to Registration Form">Register now!</a></p>
        </form>
    </div>
    <div class="car-wash-info">
        <h3>Welcome to Come In Wash Manila Online Platform!</h3>
        <p>First in the Philippines! 🚘 컴인워시 마닐라점 ✨ Car & Bike Wash and Detailing 8:00AM to 10:00 PM, Sundays - Saturdays 📍</p>
    </div>
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
</body>
</html>
