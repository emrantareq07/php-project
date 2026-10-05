<?php
require_once('../db/db.php');
$defaultPassword = password_hash('123456', PASSWORD_DEFAULT);

$sql = "
    INSERT INTO users
    (
        office_tbl_id,
        username,
        password,
        user_type,
        office_type,
        office_name
    )
    SELECT
        o.id,
        o.buffer_name,
        ?,
        'user',
        o.office_type,
        o.office_name
    FROM office_tbl o
    WHERE o.buffer_name IS NOT NULL
      AND TRIM(o.buffer_name) <> ''
";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "s", $defaultPassword);

if (mysqli_stmt_execute($stmt)) {
    echo "Users created successfully.";
} else {
    echo "Insert failed: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
?>