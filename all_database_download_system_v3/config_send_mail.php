<?php
// config.php

/* ==========================
   ADMIN LOGIN
========================== */
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '123');

/* ==========================
   DATABASE
========================== */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');

/* ==========================
   BACKUP STORAGE
========================== */
define('BACKUP_DIR', __DIR__ . '/backups/');

/* ==========================
   BACKUP RETENTION
========================== */
define('KEEP_LAST', 10);

/* ==========================
   EMAIL SETTINGS
========================== */
define('EMAIL_NOTIFY', true);

define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'myallphpproject@gmail.com');
define('MAIL_PASSWORD', 'wjmz djla zicg asoz'); // App password
define('MAIL_FROM', 'myallphpproject@gmail.com');
define('MAIL_FROM_NAME', 'Database Backup System');
define('MAIL_TO', 'pmisict@gmail.com');

/* ❌ REMOVED session_name() FROM HERE */
/* ❌ NO PHP CLOSING TAG */
