<?php
require_once('tcpdf/tcpdf.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chartImage'])) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Name');
    $pdf->SetTitle('Income Report');
    $pdf->SetSubject('Income Report');
    $pdf->SetKeywords('TCPDF, PDF, income, report');

    // Set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

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

    // Add content
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Write(0, 'Income Report', '', 0, 'L', true, 0, false, false, 0);

    // Decode the data URL
    $chartImage = $_POST['chartImage'];
    $chartImage = str_replace('data:image/png;base64,', '', $chartImage);
    $chartImage = str_replace(' ', '+', $chartImage);
    $data = base64_decode($chartImage);

    // Write the image to a file
    $file = 'chart.png';
    file_put_contents($file, $data);

    // Add the image to the PDF
    $pdf->Image($file, 15, 40, 180, 90, 'PNG');

    // Remove the image file
    unlink($file);

    // Output the PDF
    $pdf->Output('income_report.pdf', 'I');
}
?>
