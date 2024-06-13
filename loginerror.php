<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user type
    if ($_SESSION['user_type'] == 1) {
        header('Location: home.php');
    } else if ($_SESSION['user_type'] == 2) {
        header('Location: admin.php');
    } else if ($_SESSION['user_type'] == 3) {
        header('Location: crew.php');
    } else {
        header('Location: unknown_role.php'); // In case of an unknown user type
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Login Page</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #000000;
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
        <h2>Log in</h2>
        <form action="login_handlerpdo.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <span class="eye-icon" onclick="togglePassword()">
                <i class="fa fa-eye"></i>
            </span>
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
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.querySelector(".eye-icon i");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
