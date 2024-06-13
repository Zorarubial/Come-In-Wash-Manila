<?php
// Check if the file parameter is set in the URL
if (isset($_GET['file'])) {
    $pdfFile = urldecode($_GET['file']);
    // Check if the file exists
    if (file_exists($pdfFile)) {
        // Set appropriate headers
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($pdfFile) . '"');
        header('Content-Length: ' . filesize($pdfFile));
        // Output the PDF file
        readfile($pdfFile);
        exit;
    } else {
        echo 'PDF file not found.';
    }
} else {
    echo 'PDF file not specified.';
}
?>
