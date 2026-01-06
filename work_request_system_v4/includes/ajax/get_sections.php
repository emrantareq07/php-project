<?php
require_once '../../db/config.php';

if (!isset($_POST['division_id'])) {
    exit;
}

$division_id = intval($_POST['division_id']);

$sql = "SELECT id, name FROM section WHERE division_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $division_id);
$stmt->execute();
$result = $stmt->get_result();

echo '<option value="">Select Section</option>';
while ($row = $result->fetch_assoc()) {
    echo "<option value='{$row['id']}'>{$row['name']}</option>";
}
