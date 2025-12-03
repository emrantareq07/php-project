<?php
// config.php - edit these values for your environment

// Admin login (change immediately)
define('ADMIN_USER','admin');
define('ADMIN_PASS','123');

// MySQL (used to list databases and run mysqldump)
define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASS',''); // usually empty on XAMPP

// Where backups are stored (writable)
define('BACKUP_DIR', __DIR__ . '/backups/');

// Email notification after backup (true/false)
define('EMAIL_NOTIFY', true);
define('NOTIFY_TO', 'pmisict@gmail.com'); // change to your email

// If you want SMTP instead of mail(), set these and leave USE_SMTP true.
// PHPMailer is recommended for SMTP; see notes below.
define('USE_SMTP', false);
define('SMTP_HOST','smtp.example.com');
define('SMTP_PORT',587);
define('SMTP_USER','smtp_user');
define('SMTP_PASS','smtp_pass');
define('SMTP_FROM','pmisict@gmail.com');
define('SMTP_FROM_NAME','DB Backup');

// Keep last N backups (auto-delete older ones)
define('KEEP_LAST', 10);

// Session name
session_name('db_backup_session_v1');
?>
