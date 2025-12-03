<?php
// config.php - edit these values for your environment

// -- Admin login (change these!)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '123'); // change to a strong password

// -- mysqldump / DB host for listing databases & dumping
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // your local MySQL root password (likely empty on XAMPP)

// -- Backups folder (writable)
define('BACKUP_DIR', __DIR__ . '/backups/');

// -- Email notification after backup (true/false)
define('EMAIL_NOTIFY', true);
define('NOTIFY_TO', 'pmisict@gmail.com'); // recipient
// If EMAIL_NOTIFY=true and you want SMTP, configure sendmail or php.ini. This script uses mail() by default.

// -- Keep backups (number) - old ones auto-deleted when creating new (keep last N)
define('KEEP_LAST', 10);

// -- Security: session name
session_name('db_backup_session');
?>
