<?php
// Include TCPDF library
require_once('tcpdf/tcpdf.php');
require('custconxpdo.php');

// Pagination
$limit = 30;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Retrieve data from the washing with pagination
$sql = "SELECT washing.*, tbl_user.*
        FROM washing
        LEFT JOIN tbl_user ON washing.User_id = tbl_user.User_id
        ORDER BY washing.Wdate ASC
        LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Initialize TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Bazil M.');
$pdf->SetTitle('Wash History Report');
$pdf->SetSubject('Wash History Report');
$pdf->SetKeywords('TCPDF, PDF, report');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Include Bootstrap CSS
$html = '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">';

// Header
$html .= '<h1 class="text-center mb-4">Wash History Report</h1>';
$html .= '<p class="text-center">Report generated on ' . date('Y-m-d') . '</p>';

// Table
$html .= '<table class="table table-bordered">';
$html .= '<thead class="thead-dark">';
$html .= '<tr>';
$html .= '<th>Wash ID</th>';
$html .= '<th>User ID</th>';
$html .= '<th>Wash Category</th>';
$html .= '<th>Wash Cost</th>';
$html .= '<th>Wash Date</th>';
$html .= '<th>User First Name</th>';
$html .= '<th>User Last Name</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

// Initialize variables for tracking month
$currentMonth = '';

// Loop through the results
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Extract month and year from the wash date
    $washDate = date('F Y', strtotime($row['Wdate']));
    
    // Check if a new month is encountered
    if ($currentMonth != $washDate) {
        // Add a new row for the month header
        $currentMonth = $washDate;
        $html .= '<tr><td colspan="7"><strong>' . $currentMonth . '</strong></td></tr>';
    }

    // Add data for each record
    $html .= '<tr>';
    $html .= '<td>' . $row['Wash_id'] . '</td>';
    $html .= '<td>' . $row['User_id'] . '</td>';
    $html .= '<td>' . $row['Wcategory'] . '</td>';
    $html .= '<td>' . $row['Wcost'] . '</td>';
    $html .= '<td>' . $row['Wdate'] . '</td>';
    $html .= '<td>' . $row['Ufname'] . '</td>';
    $html .= '<td>' . $row['Usname'] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '</table>';

// Output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Close and output PDF document
$pdf->Output('wash_history_report.pdf', 'D');
?>
