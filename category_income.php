<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'mngrconx.php'; // Include your database connection file

// Get filter inputs
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// Prepare the SQL query
$sql = "SELECT 
            DATE_FORMAT(washing.Wdate, '%Y-%m') AS month,
            washing.Wcategory,
            SUM(washing.Wcost) AS total_income
        FROM 
            washing
        LEFT JOIN 
            tbl_user ON washing.User_id = tbl_user.User_id";

if ($category != 'all') {
    $sql .= " WHERE washing.Wcategory = :category";
}

$sql .= " GROUP BY 
            DATE_FORMAT(washing.Wdate, '%Y-%m'), 
            washing.Wcategory
        ORDER BY 
            month ASC, washing.Wcategory ASC";

try {
    $stmt = $pdo->prepare($sql);

    if ($category != 'all') {
        $stmt->bindParam(':category', $category);
    }

    $stmt->execute();
    $income_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Prepare data for the chart
$months = [];
$category_data = [];

foreach ($income_results as $row) {
    $month = $row['month'];
    $category = $row['Wcategory'];
    $income = $row['total_income'];

    if (!isset($months[$month])) {
        $months[$month] = [];
    }

    $months[$month][$category] = $income;
}

$all_categories = array_unique(array_column($income_results, 'Wcategory'));
$chart_labels = array_keys($months);
$chart_data = [];

foreach ($all_categories as $category) {
    $data = [];
    foreach ($chart_labels as $month) {
        $data[] = isset($months[$month][$category]) ? $months[$month][$category] : 0;
    }
    $chart_data[] = [
        'label' => $category,
        'data' => $data,
        'backgroundColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 0.8)', // Increased opacity to 0.5
        'borderColor' => 'rgba(' . rand(0, 255) . ',' . rand(0, 255) . ',' . rand(0, 255) . ', 1)',
        'borderWidth' => 1
    ];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            height: 100%;
            overflow-y: auto;
            margin-left: 10px;
            margin-right: 10px;
        }
    </style>
    <title>Income per Category</title>
</head>
<body>
    <h1>Income per Category</h1>
    <canvas id="categoryIncomeChart" width="400" height="200"></canvas>
    <a href="reports.php">Back to Reports</a>
    <script>
        const ctx = document.getElementById('categoryIncomeChart').getContext('2d');
        const categoryIncomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chart_labels); ?>,
                datasets: <?php echo json_encode($chart_data); ?>
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
