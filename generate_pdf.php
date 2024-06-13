<?php
require 'custconxpdo.php';
require_once('tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Employee Performance Review');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Fetch employee records
try {
    $employeesStmt = $pdo->query("SELECT * FROM employee");
    $employees = $employeesStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Create HTML content for the PDF
$html = '<h1>Employee Performance Review</h1>';
$html .= '<table border="1" cellpadding="4">
            <thead>
                <tr>
                    <th>Employee ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Attendance</th>
                    <th>Work Quality</th>
                    <th>Communication</th>
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
            <tbody>';

foreach ($employees as $employee) {
    $html .= '<tr>
                <td>' . htmlspecialchars($employee['employee_id']) . '</td>
                <td>' . htmlspecialchars($employee['employee_fname']) . '</td>
                <td>' . htmlspecialchars($employee['employee_sname']) . '</td>
                <td>' . htmlspecialchars($employee['attendance']) . '</td>
                <td>' . htmlspecialchars($employee['work_quality']) . '</td>
                <td>' . htmlspecialchars($employee['communication']) . '</td>
                <td>' . htmlspecialchars($employee['teamwork']) . '</td>
                <td>' . htmlspecialchars($employee['individual_work']) . '</td>
                <td>' . htmlspecialchars($employee['productivity']) . '</td>
                <td>' . htmlspecialchars($employee['professionalism']) . '</td>
                <td>' . htmlspecialchars($employee['courtesy']) . '</td>
                <td>' . htmlspecialchars($employee['cleanliness']) . '</td>
                <td>' . htmlspecialchars($employee['attitude']) . '</td>
                <td>' . htmlspecialchars(
                    $employee['attendance'] +
                    $employee['work_quality'] +
                    $employee['communication'] +
                    $employee['teamwork'] +
                    $employee['individual_work'] +
                    $employee['productivity'] +
                    $employee['professionalism'] +
                    $employee['courtesy'] +
                    $employee['cleanliness'] +
                    $employee['attitude']
                ) . '</td>
              </tr>';
}

$html .= '</tbody></table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('employee_performance_review.pdf', 'I');
