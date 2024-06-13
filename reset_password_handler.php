<?php
require 'mailerconx.php'; // Include PDO connection
require 'send_email.php'; // Include email sending functionality

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
    $email = $_POST['email'];

    try {
        // Check if email exists in the database
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_user WHERE Uemail = ?");
        $checkStmt->execute([$email]);
        $emailExists = $checkStmt->fetchColumn();

        if ($emailExists) {
            // Generate a unique verification code
            $verificationCode = uniqid();

            // Update the verification code in the database
            $stmt = $pdo->prepare("UPDATE tbl_user SET UverCode = ? WHERE Uemail = ?");
            $stmt->execute([$verificationCode, $email]);

            // Verify that the update was successful
            if ($stmt->rowCount() > 0) {
                // Send email with password reset link
                $subject = 'Password Reset Request';
                $body = 'Click the link to reset your password: <a href="http://yourwebsite.com/reset_password.php?code='.$verificationCode.'">Reset Password</a>';
                $result = sendEmail($email, $subject, $body);

                if ($result) {
                    echo 'Password reset link sent to your email address.';
                } else {
                    echo 'Failed to send password reset email. Email sending function returned false.';
                }
            } else {
                echo 'Failed to update verification code in the database.';
            }
        } else {
            echo 'Email address not found in our records.';
        }
    } catch (PDOException $e) {
        echo 'Database error: ' . $e->getMessage();
    }
}
?>
