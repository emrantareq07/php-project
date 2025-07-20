<?php
    if(isset($_SESSION['message'])) :
?>

  <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 

<?php 
    unset($_SESSION['message']);
    endif;
?>

<?php
    // if(isset($_SESSION['message'])) :
    //     $message_class = isset($_SESSION['message_class']) ? $_SESSION['message_class'] : 'alert-warning';
?>

<!--     <div class="alert alert-dismissible fade show <?= $message_class; ?>" role="alert">
        <strong>Hey!</strong> <?= $_SESSION['message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->

<?php 
    // unset($_SESSION['message']);
    // unset($_SESSION['message_class']);
    // endif;
?>
