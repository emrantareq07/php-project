<?php
require '../db/db.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

/* Prepare query */
if ($q !== '') {
    $like = "%{$q}%";
    $sql = "SELECT * FROM friends 
            WHERE status='approved' 
              AND (name LIKE ? OR address LIKE ? OR occupation LIKE ? OR email LIKE ? OR jobplace LIKE ? OR mobile LIKE ? OR alt_mobile LIKE ?)
            ORDER BY name ASC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssii", $like, $like, $like, $like, $like, $like, $like, $limit, $offset);
} else {
    $sql = "SELECT * FROM friends 
            WHERE status='approved' 
            ORDER BY name ASC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

/* No rows */
if (!$result || $result->num_rows === 0) {
    if ($offset === 0) {
        echo '<div class="alert alert-warning text-center">No contacts found.</div>';
    }
    exit;
}

/* Render cards */
while ($r = $result->fetch_assoc()):
    $primary   = htmlspecialchars($r['mobile']);
    $alt       = htmlspecialchars($r['alt_mobile']);
    $smsNumber = $primary ?: $alt;
    $name      = htmlspecialchars($r['name']);
    $occupation = htmlspecialchars($r['occupation']);
    $jobplace  = htmlspecialchars($r['jobplace']);
    $address   = nl2br(htmlspecialchars($r['address']));
    $email     = htmlspecialchars($r['email']);
    $blood     = htmlspecialchars($r['blood_group']);
    $image     = !empty($r['image']) ? $r['image'] : 'default-profile.png'; // default image
?>
    <div class="card mb-3 shadow-sm contact-card" onclick="window.location.href='tel:<?= $primary ?>'">
        <div class="card-body d-flex justify-content-between align-items-center">
            <img src="<?= $image ?>" alt="Profile" class="rounded-circle me-3" width="60" height="60" style="object-fit:cover;">
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1 text-primary"><i class="fa fa-user-circle"></i> <?= $name ?></h6>
                <p class="mb-1 small text-muted">
                    <i class="fa fa-briefcase"></i> <?= $occupation ?>
                    <span class="text-secondary"> @ <?= $jobplace ?></span>
                </p>
                <p class="mb-1 small"><i class="fa fa-map-marker text-danger"></i> <?= $address ?></p>
                <p class="mb-1 small"><i class="fa fa-tint text-info"></i> Blood Group: <?= $blood ?></p>
                <?php if (!empty($email)): ?>
                    <p class="mb-0 small"><i class="fa fa-envelope text-info"></i> <?= $email ?></p>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <a href="tel:<?= $primary ?>" class="btn btn-sm btn-success mb-1"><i class="fa fa-phone"></i> Call</a><br>
                <a href="sms:<?= $smsNumber ?>" class="btn btn-sm btn-primary"><i class="fa fa-comment"></i> Message</a>
            </div>
        </div>
    </div>
<?php endwhile; ?>
