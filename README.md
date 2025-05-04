# 📧 Automated Email Invitation System using PHPMailer

This project is a **PHP-based email invitation system** that allows you to send **personalized HTML invitations** via SMTP to a list of recipients from a CSV file using **PHPMailer**. It includes unsubscribe handling, error reporting, and support for sending timed emails (e.g., every 5 minutes for testing or follow-ups).

---

## 📂 Project Structure

```

.
├── index.php                 # Main script to handle email sending
├── unsubscribed\_list.txt     # List of unsubscribed email addresses
├── README.md                 # Project documentation (this file)
├── sample.csv                # Sample input CSV file (FirstName, Email)
└── vendor/                   # Composer dependencies (PHPMailer, etc.)

````

---

## ✅ Features

- 📤 Send **bulk personalized HTML emails** using a CSV upload.
- ⛔ Automatically skips **invalid or unsubscribed** email addresses.
- 🔗 Adds a **custom unsubscribe link** to each email.
- 🕒 Includes **email throttling** (default: 1 sec delay per email).
- 🔁 Supports **sending repeat emails every 5 minutes** to a recipient (for testing).
- 📜 Clean HTML email content with event details and branding.

---

## ⚙️ Requirements

- PHP 7.0+
- Composer (for installing PHPMailer)
- SMTP account (tested with **Brevo/Sendinblue**)

---

## 📦 Setup Instructions

### 1. Clone or Download the Repository

```bash
git clone https://github.com/yourusername/email-invitation-system.git
cd email-invitation-system
````

### 2. Install PHPMailer via Composer

```bash
composer install
```

### 3. Configure SMTP Settings

In `index.php`, update the following constants with your credentials:

```php
define('BREVO_SMTP_USERNAME', 'your-smtp-username@smtp-brevo.com');
define('BREVO_SMTP_PASSWORD', 'your-smtp-password');
define('FROM_EMAIL', 'you@example.com');
define('FROM_NAME', 'Your Name or Organization');
```

### 4. Prepare Your CSV File

Your CSV should have the following headers:

```
FirstName,Email
```

### 5. Run the Script

* Place the script on a PHP-enabled server (e.g., XAMPP, WAMP, or live server).
* Access the script via browser and upload your CSV file.
* Emails will be sent, and progress will be shown in real-time.

---

## 🔁 Optional: Send to Same Email Every 5 Minutes

To send the same email to a recipient 3 times with 5-minute intervals, use the `for` loop version in the extended script. This is ideal for testing automated follow-ups or reminders.

```php
$emailCount = 3;
$sleepMinutes = 5;

for ($i = 0; $i < $emailCount; $i++) {
    // Send email logic
    sleep($sleepMinutes * 60); // Wait 5 minutes
}
```

---

## 📥 Unsubscribe Mechanism

* Unsubscribed emails are stored in `unsubscribed_list.txt`.
* The system skips these emails automatically.
* Each email includes an unsubscribe link:

  ```
  http://yourdomain.com/unsubscribe.php?email=user@example.com
  ```

> You must create a corresponding `unsubscribe.php` script to handle unsubscribe requests and update the text file.

---

## 📧 Sample Email Output

```html
<h3>Dear John,</h3>
<p>You are cordially invited to the <strong>Viksit Bharat Dialogues & Awards (VBDA) 2025</strong>.</p>
<p><strong>Date:</strong> 25th July 2025<br><strong>Location:</strong> Bharat Mandapam, New Delhi</p>
<p>We hope to see you there!</p>
<p style='font-size:12px;color:gray;'>If you wish to unsubscribe, click <a href='...'>here</a>.</p>
<p>Warm regards,<br><strong>VBDA Team</strong></p>
```

---

## 🔐 Security Notes

* **Never hard-code SMTP credentials** in production. Use environment variables.
* Use HTTPS to protect unsubscribe links and form submissions.

---

## 🙋‍♂️ Author

**\[Your Full Name]**
Email: [you@example.com](mailto:you@example.com)
Course/Institute: \[Optional]

---

## 📄 License

This project is provided for educational purposes. Use it at your own discretion.

```