<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <title>Reports</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif; /* Poppins font */
            background-size: cover;
            color: #3c3c34; /* Armadillo */
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff; /* White text */
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff; /* White text */
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c10c23; /* Shiraz Red */
        }

        #back {
            background-color: #b09b9e;
            color: black;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 20px auto;
            text-align: center;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1>Reports</h1>
    <div class="buttons-container">
        <form action="category_income.php" method="post">
            <button type="submit">Sales per Category</button>
        </form>
        <!-- <form action="monthly_expenses.php" method="post"> -->
            <!-- <button type="submit">Expenses</button> -->
        <!-- </form> -->
        <form action="income_report.php" method="post">
            <button type="submit">Income Report</button>
        </form>
        <form action="wash_history.php" method="post">
            <button type="submit">Wash History</button>
        </form>
    </div>
    <a id="back" href="admin.php">Back to Dashboard</a>
</body>
</html>
