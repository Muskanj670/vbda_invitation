<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>

<head>
    <title>VBDA Invitation System</title>
</head>

<body>
    <h1>Send Invitations via CSV</h1>
    <form method="POST" action="send_emails.php" enctype="multipart/form-data">
        <label>Select CSV file:</label><br>
        <input type="file" name="csv_file" accept=".csv" required><br><br>
        <input type="submit" value="Send Invitations">
    </form>
</body>

</html>