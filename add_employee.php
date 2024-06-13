<?php
require 'custconxpdo.php';

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Function to add a new employee
function addEmployee($pdo, $fname, $sname) {
    $stmt = $pdo->prepare("INSERT INTO employee (employee_fname, employee_sname) VALUES (?, ?)");
    $stmt->execute([$fname, $sname]);
}

// Check if the form is submitted to add a new employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Get form data
    $fname = sanitize($_POST["employee_fname"]);
    $sname = sanitize($_POST["employee_sname"]);

    // Add the new employee
    addEmployee($pdo, $fname, $sname);

    // Redirect back to the main page after adding the employee
    header("Location: epr.php");
    exit();
}
?>
