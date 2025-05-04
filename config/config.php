<?php
// === VBDA Invitation System Configuration ===

// Brevo SMTP
define('BREVO_SMTP_USERNAME', '8c1bc0002@smtp-brevo.com');
define('BREVO_SMTP_PASSWORD', 'vVZ1YNpCHMfkrnjW');

// Email settings
define('FROM_EMAIL', 'muskanj8642@gmail.com');
define('FROM_NAME', 'VBDA Invitations');

// Base URL (used in unsubscribe links)
define('BASE_URL', 'http://localhost/vbda_invitation_updated');

// Enable logging
define('ENABLE_LOGGING', true);
define('LOG_FILE', __DIR__ . '/../logs/system.log');
