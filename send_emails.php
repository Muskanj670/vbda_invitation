<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// SMTP Configuration
define('BREVO_SMTP_USERNAME', '8c1bc0002@smtp-brevo.com');
define('BREVO_SMTP_PASSWORD', 'vVZ1YNpCHMfkrnjW');
define('FROM_EMAIL', 'muskanj8642@gmail.com');
define('FROM_NAME', 'VBDA Invitations');
define('UNSUB_FILE', 'unsubscribed_list.txt');

// Check if CSV file was uploaded
if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    die('❌ Error: No valid CSV file uploaded.');
}

$csvPath = $_FILES['csv_file']['tmp_name'];
$csvFile = fopen($csvPath, 'r');
if (!$csvFile) {
    die('❌ Failed to open uploaded CSV file.');
}

// Load unsubscribed emails into an array
$unsubscribed = file_exists(UNSUB_FILE)
    ? array_map('trim', file(UNSUB_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
    : [];

$headers = fgetcsv($csvFile); // Read header row
echo "<h2>📤 Sending Emails...</h2><ul>";

// Batch size and delay (5 minutes)
$batchSize = 10; // Send 10 emails per batch
$sleepMinutes = 5; // 5-minute delay between batches

$counter = 0; // Email counter
while (($data = fgetcsv($csvFile)) !== false) {
    $recipientInfo = array_combine($headers, $data);
    $recipientEmail = $recipientInfo['Email'] ?? '';
    $recipientName = $recipientInfo['FirstName'] ?? 'Guest';

    // Skip if invalid or unsubscribed
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        echo "<li>⚠️ Invalid email: $recipientEmail</li>";
        continue;
    }
    if (in_array($recipientEmail, $unsubscribed)) {
        echo "<li>🚫 Skipped unsubscribed: $recipientEmail</li>";
        continue;
    }

    $mail = new PHPMailer(true);

    try {
        // Setup SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp-relay.brevo.com';
        $mail->SMTPAuth = true;
        $mail->Username = BREVO_SMTP_USERNAME;
        $mail->Password = BREVO_SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Set headers
        $mail->setFrom(FROM_EMAIL, FROM_NAME);
        $mail->addAddress($recipientEmail, $recipientName);
        $mail->isHTML(true);
        $mail->Subject = 'VBDA 2025 Invitation';

        // Email Body
        $unsubscribeLink = 'http://yourdomain.com/unsubscribe.php?email=' . urlencode($recipientEmail);
        $mail->Body = "
            <h3>Dear {$recipientName},</h3>
            <p>You are cordially invited to the <strong>Viksit Bharat Dialogues & Awards (VBDA) 2025</strong>.</p>
            <p><strong>Date:</strong> 25th July 2025<br><strong>Location:</strong> Bharat Mandapam, New Delhi</p>
            <p>We hope to see you there!</p>
            <p style='font-size:12px;color:gray;'>If you wish to unsubscribe, click <a href='{$unsubscribeLink}'>here</a>.</p>
            <p>Warm regards,<br><strong>VBDA Team</strong></p>
        ";

        $mail->send();
        echo "<li>✅ Sent to: <strong>{$recipientEmail}</strong></li>";
    } catch (Exception $e) {
        echo "<li>❌ Failed to send to {$recipientEmail}: {$mail->ErrorInfo}</li>";
    }

    flush(); // Show progress
    $counter++;

    // Check if batch size is reached
    if ($counter >= $batchSize) {
        echo "<li>⏳ Pausing for 5 minutes...</li>";
        sleep($sleepMinutes * 60); // Sleep for 5 minutes
        $counter = 0; // Reset counter for the next batch
    }
}

fclose($csvFile);
echo "</ul><p>✅ All emails processed.</p>";
?>