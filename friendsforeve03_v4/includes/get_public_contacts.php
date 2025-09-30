<?php
require '../db/db.php';

$q = $_GET['q'] ?? '';
$q = trim($q);

$limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

/* if search present, use LIKE with 7 placeholders and two ints (limit, offset) */
if ($q !== '') {
    $like = "%{$q}%";
    $sql = "SELECT * FROM friends 
            WHERE status='approved' 
              AND (name LIKE ? OR address LIKE ? OR occupation LIKE ? OR email LIKE ? OR jobplace LIKE ? OR mobile LIKE ? OR alt_mobile LIKE ?)
            ORDER BY name ASC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        // fail silently for infinite scroll: return empty so front-end treats as "no more"
        http_response_code(500);
        echo '';
        exit;
    }
    /* 7 strings + 2 integers => 'sssssssii' */
    $stmt->bind_param("sssssssii", $like, $like, $like, $like, $like, $like, $like, $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = "SELECT * FROM friends 
            WHERE status='approved' 
            ORDER BY name ASC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo '';
        exit;
    }
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
}

/* If no rows */
if (!$result || $result->num_rows === 0) {
    if ($offset === 0) {
        // first page: show "no contacts" (front-end relies on non-empty result for first page)
        echo '<div class="alert alert-warning text-center">No contacts found.</div>';
    }
    // for subsequent pages, return empty response so frontend marks "allLoaded"
    exit;
}

/* render cards (same markup as page expects) */
while ($r = $result->fetch_assoc()):
    $primary   = htmlspecialchars($r['mobile']);
    $alt       = htmlspecialchars($r['alt_mobile']);
    $smsNumber = $primary ?: $alt;
?>
    <div class="card mb-3 shadow-sm contact-card" onclick="window.location.href='tel:<?= $primary ?>'">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold mb-1 text-primary">
                    <i class="fa fa-user-circle"></i> <?= htmlspecialchars($r['name']) ?>
                </h6>
                <p class="mb-1 text-muted">
                    <i class="fa fa-briefcase"></i> <?= htmlspecialchars($r['occupation']) ?>
                    <span class="text-secondary"> @ <?= htmlspecialchars($r['jobplace']) ?></span>
                </p>
                <p class="mb-1 small">
                    <i class="fa fa-map-marker text-danger"></i> <?= nl2br(htmlspecialchars($r['address'])) ?>
                </p>
                <?php if (!empty($r['email'])): ?>
                    <p class="mb-0 small"><i class="fa fa-envelope text-info"></i> <?= htmlspecialchars($r['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="text-end">
                <a href="tel:<?= $primary ?>" class="btn btn-sm btn-success mb-1">
                    <i class="fa fa-phone"></i> Call
                </a><br>
                <a href="sms:<?= $smsNumber ?>" class="btn btn-sm btn-primary">
                    <i class="fa fa-comment"></i> Message
                </a>
            </div>
        </div>
    </div>
<?php
endwhile;
?>
