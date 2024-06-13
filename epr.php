<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'mngrconx.php';

$userId = $_SESSION['user_id'];

// Fetch all employees from the 'employee' table with their total ratings
$employeesStmt = $pdo->query("SELECT employee_fname, employee_sname, attendance, work_quality, communication, teamwork, individual_work, productivity, professionalism, courtesy, cleanliness, attitude FROM employee");

$employeeData = [];
foreach ($employeesStmt as $employee) {
    $total = $employee['attendance'] + $employee['work_quality'] + $employee['communication'] + $employee['teamwork'] + $employee['individual_work'] + $employee['productivity'] + $employee['professionalism'] + $employee['courtesy'] + $employee['cleanliness'] + $employee['attitude'];
    $employeeData[] = [
        'name' => $employee['employee_fname'] . ' ' . $employee['employee_sname'],
        'total' => $total
    ];
}

// Convert PHP array to JSON for JavaScript
$employeeDataJSON = json_encode($employeeData);

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Function to validate ratings
function validateRating($rating) {
    return ($rating >= 0 && $rating <= 10);
}

// Function to add a new employee

function addEmployee($pdo, $fname, $sname, $status) {
    
    $stmt = $pdo->prepare("INSERT INTO employee (employee_fname, employee_sname, Estatus) VALUES (?, ?, ?)");
    $stmt->execute([$fname, $sname, $status]);
}

// Check if the form is submitted to add a new employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $fname = sanitize($_POST["employee_fname"]);
    $sname = sanitize($_POST["employee_sname"]);
    $status = "active";

    // Add validation for empty fields or other constraints if necessary

    // Add the new employee
    addEmployee($pdo, $fname, $sname, $status);
}

// Check if the form is submitted to save ratings
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_ratings"])) {
    foreach ($_POST['ratings'] as $employee_id => $ratings) {
        // Validate and sanitize each rating
        foreach ($ratings as $key => $rating) {
            if (validateRating($rating)) {
                $ratings[$key] = sanitize($rating);
            } else {
                $ratings[$key] = 0; // Default to 0 if invalid
            }
        }

        // Update the ratings in the database
        $stmt = $pdo->prepare("UPDATE employee SET
            attendance = ?,
            work_quality = ?,
            communication = ?,
            teamwork = ?,
            individual_work = ?,
            productivity = ?,
            professionalism = ?,
            courtesy = ?,
            cleanliness = ?,
            attitude = ?
            WHERE employee_id = ?");
        $stmt->execute([
            $ratings['attendance'],
            $ratings['work_quality'],
            $ratings['communication'],
            $ratings['teamwork'],
            $ratings['individual_work'],
            $ratings['productivity'],
            $ratings['professionalism'],
            $ratings['courtesy'],
            $ratings['cleanliness'],
            $ratings['attitude'],
            $employee_id
        ]);
    }
}

try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE User_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch all employees from the 'employee' table
    $employeesStmt = $pdo->query("SELECT * FROM employee");
    $employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Performance Review</title>
    <link rel="icon" type="image/jpg" href="assets/img/ciwlogosquare.jpg">
    <link rel="stylesheet" href="assets/css/mainstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        body {
            margin-left: 30px;
            background-color: #000000;

        }
        button {
            font-family: 'Poppins', Arial, sans-serif;
            width: 1300px;
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #c10c23; /* Shiraz Red */
            text-decoration: none;
            background-color: #b91b2d; /* Cardinal Red */
            color: #ffffff; /* White text */
            padding: 10px;
        }
        button:hover {
            text-decoration: underline;
        }

        .grading-system {
            background-color: rgba(60, 60, 52, 0.5);
            padding: 20px;
            border-radius: 10px;
            width: 350px;
            margin: 50px auto;
            box-shadow: 0px 0px 10px rgba(255,255,255,0.1);
            color: whitesmoke;
            display: block;
        }
        .minimized {
            display: none;
        }
        button {

        }
        h2, label {
            color: floralwhite;
        }
    </style>
</head>
<body>
    <h1 style=" margin-left: -30px;">Employee Performance Review</h1>
    <div class="container">
        <div class="grading-system">
            <h2>Grading System</h2>
            <ul>
                <li style=" color: aquamarine;">90-100: Excellent</li>
                <li style=" color: lightgreen;">85-89: Very Satisfactory</li>
                <li style=" color: yellowgreen;">80-84: Satisfactory</li>
                <li style=" color: yellow;">75-79: Fair</li>
                <li style=" color: orange;">Below 75: Did not meet expectations</li>
            </ul>

        </div>   
         <button id="toggleButton" onclick="toggleGradingSystem()">Hide Grading System</button>
        <?php if (!empty($employees)) : ?>
            <h2>Employee List</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <table style=" margin-right: 15px; margin-left: -15px; margin-top: -10px;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Attendance</th>
                            <th>Work Quality</th>
                            <th>Comm.</th>
                            <th>Teamwork</th>
                            <th>Individual Work</th>
                            <th>Productivity</th>
                            <th>Professionalism</th>
                            <th>Courtesy</th>
                            <th>Cleanliness</th>
                            <th>Attitude</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($employees as $employee) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_fname']); ?></td>
                                <td><?php echo htmlspecialchars($employee['employee_sname']); ?></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][attendance]" min="0" max="10" value="<?php echo htmlspecialchars($employee['attendance']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][work_quality]" min="0" max="10" value="<?php echo htmlspecialchars($employee['work_quality']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][communication]" min="0" max="10" value="<?php echo htmlspecialchars($employee['communication']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][teamwork]" min="0" max="10" value="<?php echo htmlspecialchars($employee['teamwork']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][individual_work]" min="0" max="10" value="<?php echo htmlspecialchars($employee['individual_work']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][productivity]" min="0" max="10" value="<?php echo htmlspecialchars($employee['productivity']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][professionalism]" min="0" max="10" value="<?php echo htmlspecialchars($employee['professionalism']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][courtesy]" min="0" max="10" value="<?php echo htmlspecialchars($employee['courtesy']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][cleanliness]" min="0" max="10" value="<?php echo htmlspecialchars($employee['cleanliness']); ?>"></td>
                                <td><input type="number" name="ratings[<?php echo $employee['employee_id']; ?>][attitude]" min="0" max="10" value="<?php echo htmlspecialchars($employee['attitude']); ?>"></td>
                                <td class="total-rating" style="background-color: lightpink;">
                                    <?php
                                    $total = $employee['attendance'] +
                                             $employee['work_quality'] +
                                             $employee['communication'] +
                                             $employee['teamwork'] +
                                             $employee['individual_work'] +
                                             $employee['productivity'] +
                                             $employee['professionalism'] +
                                             $employee['courtesy'] +
                                             $employee['cleanliness'] +
                                             $employee['attitude'];
                                    echo htmlspecialchars($total);
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="save_ratings">Save Ratings</button>
            </form>
        <?php else : ?>
            <p>No employees found.</p>
        <?php endif; ?>
            <h2>Add New Employee</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="employee_fname">First Name:</label>
                <input type="text" id="employee_fname" name="employee_fname" required>
                <label for="employee_sname">Last Name:</label>
                <input type="text" id="employee_sname" name="employee_sname" required>
                <button type="submit" name="submit">Add Employee</button>
            </form>
        
    </div>
    <form action="generate_pdf.php" method="post" style="margin-top: 20px;">
    <button style="background-color: cornflowerblue;" type="submit">Generate PDF</button>
</form>
<canvas id="ratingChart" width="800" height="400"></canvas>
    <a  href="admin.php" style="margin-right: 15px; margin-bottom: 20px;">Go Back</a>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all input fields
            var inputs = document.querySelectorAll('input[type="number"]');
            inputs.forEach(function(input) {
                // Add event listener for input change
                input.addEventListener('input', function() {
                    // Restrict input to a maximum value of 10
                    if (parseInt(this.value) > 10) {
                        this.value = 10;
                    }
                    // Calculate the total rating for the corresponding row
                    var total = 0;
                    var row = this.closest('tr');
                    var ratingInputs = row.querySelectorAll('input[type="number"]');
                    ratingInputs.forEach(function(ratingInput) {
                        total += parseInt(ratingInput.value);
                    });
                    // Update the total rating in the corresponding cell
                    row.querySelector('.total-rating').textContent = total;
                });
            });
        });
    
        function toggleGradingSystem() {
            var gradingSystem = document.querySelector('.grading-system');
            // Check if the minimized class is applied
            if (gradingSystem.classList.contains('minimized')) {
                // If minimized, remove the minimized class to show the grading system
                gradingSystem.classList.remove('minimized');
                 document.getElementById('toggleButton').textContent = 'Hide Grading System';
            } else {
                // If not minimized, add the minimized class to hide the grading system
                 document.getElementById('toggleButton').textContent = 'Show Grading System';
                gradingSystem.classList.add('minimized');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
        var employeeData = <?php echo $employeeDataJSON; ?>;
        
        var names = employeeData.map(function(employee) {
            return employee.name;
        });

        var totals = employeeData.map(function(employee) {
            return employee.total;
        });

        var ctx = document.getElementById('ratingChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: names,
                datasets: [{
                    label: 'Total Rating',
                    data: totals,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
    </script>
</body>
</html>
