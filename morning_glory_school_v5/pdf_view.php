<?php
require_once("db/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize input

    $res = mysqli_query($conn, "SELECT * FROM notice_board WHERE id = $id");

    if ($row = mysqli_fetch_assoc($res)) {
        $filename = $row['filename'];
        $file = 'pdf/' . $filename;

        if (file_exists($file)) {
            // Clean output buffer
            if (ob_get_length()) ob_end_clean();

            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . basename($filename) . "\"");
            header("Content-Transfer-Encoding: binary");
            header("Accept-Ranges: bytes");
            header("Content-Length: " . filesize($file));

            readfile($file);
            exit;
        } else {
            echo "File not found.";
        }
    } else {
        echo "Invalid ID.";
    }
} else {
    echo "ID not provided.";
}
?>
