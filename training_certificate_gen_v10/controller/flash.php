<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Set flash message
 * @param string $type success | error | warning | info
 * @param string $message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Show flash message using SweetAlert2
 */
function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $message = addslashes($_SESSION['flash']['message']);
        unset($_SESSION['flash']);

        echo <<<HTML
<script>
Swal.fire({
    icon: '$type',
    text: '$message',
    confirmButtonColor: '#0d6efd'
});
</script>
HTML;
    }
}
