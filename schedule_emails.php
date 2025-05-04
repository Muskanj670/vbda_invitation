<?php
// Show errors for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure Composer autoload is available

// SMTP CONFIGURATION
define('BREVO_SMTP_USERNAME', '8c1bc0002@smtp-brevo.com');
define('BREVO_SMTP_PASSWORD', 'vVZ1YNpCHMfkrnjW');
define('FROM_EMAIL', 'muskanj8642@gmail.com');
define('FROM_NAME', 'VBDA Invitations');

// Check if CSV was uploaded
if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    echo "<h2>❌ Error: CSV file not uploaded.</h2>";
    exit;
}

// Open the uploaded CSV file
$csvPath = $_FILES['csv_file']['tmp_name'];
$csvFile = fopen($csvPath, 'r');

if (!$csvFile) {
    echo "<h2>❌ Error: Cannot open uploaded CSV file.</h2>";
    exit;
}

$headers = fgetcsv($csvFile); // Read header row
echo "<h2>⏱ Sending VBDA Invitations...</h2><ul>";

// Delay between emails (seconds)
$scheduleDelay = 2;

while (($row = fgetcsv($csvFile)) !== false) {
    $recipientInfo = array_combine($headers, $row);
    $recipientEmail = $recipientInfo['Email'] ?? '';
    $recipientName = $recipientInfo['FirstName'] ?? 'Guest';

    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<li>⚠️ Invalid email skipped: $recipientEmail</li>";
        continue;
    }

    // Create new PHPMailer instance
    $mail = new PHPMailer(true);
    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = BREVO_SMTP_USERNAME;
        $mail->Password = BREVO_SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($recipientEmail, $recipientName);
        $mail->isHTML(true);
        $mail->Subject = '🌟 You are Invited to VBDA 2025!';
        $mail->Body = "
            <h3>Dear {$recipientName},</h3>
            <p>We are thrilled to invite you to the <strong>Viksit Bharat Dialogues & Awards (VBDA) 2025</strong>.</p>
            <p><strong>Date:</strong> 25th July 2025<br>
            <strong>Venue:</strong> Bharat Mandapam, New Delhi</p>
            <p>We hope to see you there!</p>
            <p>Warm regards,<br><strong>VBDA Team</strong></p>
        ";

        $mail->send();
        echo "<li>✅ Email sent to <strong>{$recipientEmail}</strong></li>";
    } catch (Exception $e) {
        echo "<li>❌ Failed to send to {$recipientEmail}. Error: {$mail->ErrorInfo}</li>";
    }

    // Delay to simulate scheduling
    sleep($scheduleDelay);
}

fclose($csvFile);
echo "</ul><p>🎉 All emails processed.</p>";
?>