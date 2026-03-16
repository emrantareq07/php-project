<?php
session_name('innovation_db');
session_start();
session_destroy();
header("Location: login.php");
exit();