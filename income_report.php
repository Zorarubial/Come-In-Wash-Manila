<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'mngrconx.php'; // Include your database connection file

$view = isset($_GET['view']) ? $_GET['view'] : 'monthly';

switch ($view) {
    case 'daily':
        $date_format = '%Y-%m-%d';
        break;
    case 'weekly':
        $date_format = '%Y-%u';
        break;
    case 'yearly':
        $date_format = '%Y';
        break;
    case 'monthly':
    default:
        $date_format = '%Y-%m';
}

$sql = "SELECT 
            DATE_FORMAT(Wdate, '$date_format') AS period,
            SUM(Wcost) AS total_income
        FROM 
            washing
        WHERE 
            Wstatus != 'cancelled'
        GROUP BY 
            DATE_FORMAT(Wdate, '$date_format')
        ORDER BY 
            period ASC";

try {
    $stmt = $pdo->query($sql);
    $income_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$periods = [];
$total_incomes = [];

foreach ($income_results as $row) {
    $periods[] = $row['period'];
    $total_incomes[] = $row['total_income'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Report</title>
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: black;
        }
        button {
            background-color: blue;
            color: white;
            border-radius: 5px;
            font-family: 'Poppins', Arial, sans-serif;
            margin-left: 40px;
            margin-top: 20px;

        }
    </style>
</head>
<body>
    <h1>Income Report</h1>
    <form method="GET" action="">
        <label for="view" style="margin-left: 20px;">Select View:</label>
        <select name="view" id="view" onchange="this.form.submit()">
            <option value="daily" <?= $view == 'daily' ? 'selected' : '' ?>>Daily</option>
            <option value="weekly" <?= $view == 'weekly' ? 'selected' : '' ?>>Weekly</option>
            <option value="monthly" <?= $view == 'monthly' ? 'selected' : '' ?>>Monthly</option>
            <option value="yearly" <?= $view == 'yearly' ? 'selected' : '' ?>>Yearly</option>
        </select>
    </form>
    <canvas id="incomeChart" width="400" height="200" style="margin-left: 20px;"></canvas>
    <a href="reports.php">Back to Reports</a>
    <div style="display: flex; align-items: center; justify-content: center;">
        <button id="generatePDF" style="margin-left: 20px; margin-bottom: 20px;">Generate PDF</button>
    </div>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($periods); ?>,
                datasets: [{
                    label: 'Total Income',
                    data: <?php echo json_encode($total_incomes); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 3
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        document.getElementById('generatePDF').addEventListener('click', function() {
            const canvas = document.getElementById('incomeChart');
            const dataURL = canvas.toDataURL('image/png');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'income_pdf.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chartImage';
            input.value = dataURL;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        });
    </script>
</body>
</html>
