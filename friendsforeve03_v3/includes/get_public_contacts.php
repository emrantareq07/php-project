<?php
require '../db/db.php';

// // Fetch approved contacts
// $result = $conn->query("SELECT * FROM friends WHERE status='approved' ORDER BY name ASC");

$q = $_GET['q'] ?? '';
$q = trim($q);

if ($q !== '') {
    $q = "%{$q}%";
    $stmt = $conn->prepare("SELECT * FROM friends WHERE status='approved' 
        AND (name LIKE ? OR address LIKE ? OR occupation LIKE ? OR email LIKE ? OR jobplace LIKE ? OR mobile LIKE ? OR alt_mobile LIKE ?)
        ORDER BY name ASC");
    $stmt->bind_param("sssssss", $q, $q, $q, $q, $q, $q, $q);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM friends WHERE status='approved' ORDER BY name ASC");
}

if ($result->num_rows === 0): ?>
    <div class="list-group-item text-muted">No contacts found.</div>
<?php else:

while ($r = $result->fetch_assoc()):
    $primary = htmlspecialchars($r['mobile']);
    $alt     = htmlspecialchars($r['alt_mobile']);
    $smsNumber = $primary ?: $alt;
?>
    <div class="list-group-item list-group-item-action contact-row d-flex justify-content-between align-items-center " onclick="window.location.href='tel:<?= $primary ?>'">
      <div>
        <div class="fw-semibold "><?= htmlspecialchars($r['name']) ?></div>
        <small class="text-muted d-block"><?= htmlspecialchars($r['occupation']) ?> — <?= htmlspecialchars($r['jobplace']) ?></small>
        <small class="d-block"><?= nl2br(htmlspecialchars($r['address'])) ?></small>
      </div>
      <div class="text-end">
        <div><a href="tel:<?= $primary ?>" class="btn btn-sm btn-outline-success mb-1"> <i class="fa fa-phone"></i> Call</a></div>
        <div><a href="sms:<?= $smsNumber ?>" class="btn btn-sm btn-outline-primary"><i class="fa fa-comments-o"></i> Message</a></div>
      </div>
    </div>
<?php endwhile; endif; ?>
<?php
// require 'db.php';

// $q = $_GET['q'] ?? '';
// $q = trim($q);

// if ($q !== '') {
//     $q = "%{$q}%";
//     $stmt = $conn->prepare("SELECT * FROM friends WHERE status='approved' 
//         AND (name LIKE ? OR address LIKE ? OR occupation LIKE ? OR email LIKE ? OR jobplace LIKE ? OR mobile LIKE ? OR alt_mobile LIKE ?)
//         ORDER BY name ASC");
//     $stmt->bind_param("sssssss", $q, $q, $q, $q, $q, $q, $q);
//     $stmt->execute();
//     $result = $stmt->get_result();
// } else {
//     $result = $conn->query("SELECT * FROM friends WHERE status='approved' ORDER BY name ASC");
// }

//if ($result->num_rows === 0): ?>
    <!-- <div class="list-group-item text-muted">No contacts found.</div> -->
<?php //else:
    // while ($r = $result->fetch_assoc()):
    //     $primary    = htmlspecialchars($r['mobile']);
    //     $alt        = htmlspecialchars($r['alt_mobile']);
    //     $smsNumber  = $primary ?: $alt; ?>
<!--         
        <div class="list-group-item list-group-item-action contact-row d-flex justify-content-between align-items-center" onclick="window.location.href='tel:<?= $primary ?>'">
          <div>
            <div class="fw-semibold"><?= htmlspecialchars($r['name']) ?></div>
            <small class="text-muted d-block"><?= htmlspecialchars($r['occupation']) ?> — <?= htmlspecialchars($r['jobplace']) ?></small>
            <small class="d-block"><?= nl2br(htmlspecialchars($r['address'])) ?></small>
            <small class="d-block text-muted"><?= htmlspecialchars($r['email']) ?></small>
          </div>
          <div class="text-end">
            <?php if ($primary): ?>
              <div><a href="tel:<?= $primary ?>" class="btn btn-sm btn-outline-success mb-1">Call</a></div>
            <?php endif; ?>
            <?php if ($smsNumber): ?>
              <div><a href="sms:<?= $smsNumber ?>" class="btn btn-sm btn-outline-primary">Message</a></div>
            <?php endif; ?>
          </div>
        </div> -->
<?php // endwhile; endif; ?>

