<?php
//require_once("includes/header.php");

// Check if category and subcategory parameters are provided
if (!isset($_GET['category']) || !isset($_GET['subcategory'])) {
    header("Location: photo_gallery.php");
    exit();
}

$category = $_GET['category'];
$subcategory = $_GET['subcategory'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($category); ?> - Gallery</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSS Dependencies -->
  <!-- Use only one Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
  
  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  
  <!-- Lightbox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css">
  
  <!-- Template CSS Files -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/carosoul.css" rel="stylesheet">

  <style>
    .gallery-image {
        cursor: pointer;
        height: 250px;
        object-fit: cover;
    }

    /* Transparent nav buttons */
    #prevBtn, #nextBtn {
        opacity: 0.5;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1052;
        color: white;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
    }

    #prevBtn:hover, #nextBtn:hover {
        opacity: 1;
        background: rgba(0, 0, 0, 0.6);
    }

    #prevBtn { left: 10px; }
    #nextBtn { right: 10px; }

    .modal-backdrop {
        opacity: 0.9 !important;
    }
    
    .modal-close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        z-index: 1051;
        font-size: 1.8rem;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        line-height: 36px;
        text-align: center;
        cursor: pointer;
        transition: background 0.3s;
    }
    .modal-close-btn:hover {
        background: rgba(0, 0, 0, 0.8);
        color: white;
    }
    
    .image-viewer-container {
        position: relative;
    }
  </style>
</head>
<body>
<?php 
require_once("includes/navbar.php");
require_once 'db/db.php';

// Fetch images for this specific subcategory
$query = "SELECT * FROM photo_gallary 
          WHERE category = '" . mysqli_real_escape_string($conn, $category) . "'
          AND sub_category = '" . mysqli_real_escape_string($conn, $subcategory) . "'
          ORDER BY publish_date DESC";
$result = mysqli_query($conn, $query);
$images = [];
while ($image = mysqli_fetch_assoc($result)) {
    $images[] = $image;
}
?>

<main id="main">
  <section class="scrolling/photo_gallery">
    <div class="container mt-3" data-aos="fade-up">
      <div class="row">
        <div class="section-title mt-4 pt-4 mb-0">
          <h2><?php echo htmlspecialchars($category); ?> Gallery</h2>
          <h4 class="mt-2 pt-3 mb-0"><?php echo htmlspecialchars($subcategory); ?></h4>
          <a href="photo_gallery.php" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left"></i> Back
          </a>
        </div>
      </div>

      <div class="gallery mt-4">
        <?php if (count($images) > 0): ?>
            <div class="row">
                <?php foreach ($images as $index => $image): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="uploads/<?php echo htmlspecialchars($image['photo']); ?>" 
                                 class="card-img-top img-fluid gallery-image" 
                                 data-index="<?php echo $index; ?>" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                                <p class="card-text text-muted">
                                    Published: <?php echo htmlspecialchars($image['publish_date']); ?> 
                                    (<?php echo htmlspecialchars($image['publish_month']); ?>)
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">No images found in this subcategory.</div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content bg-dark">
      <div class="modal-body p-0">
        <div class="image-viewer-container position-relative">
          <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">&times;</button>
          <img src="" id="fullsizeImage" class="img-fluid w-100">
          
          <!-- Navigation Buttons -->
          <button id="prevBtn" class="btn btn-outline-light btn-lg">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button id="nextBtn" class="btn btn-outline-light btn-lg">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once("includes/footer.php"); ?>

<!-- JavaScript Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {
    var images = <?php echo json_encode($images); ?>;
    var currentIndex = 0;

    // Handle image click
    $('.gallery-image').on('click', function () {
        currentIndex = parseInt($(this).data('index'));
        showImage();
    });

    // Previous button
    $('#prevBtn').click(function (e) {
        e.stopPropagation();
        if (currentIndex > 0) {
            currentIndex--;
            showImage();
        }
    });

    // Next button
    $('#nextBtn').click(function (e) {
        e.stopPropagation();
        if (currentIndex < images.length - 1) {
            currentIndex++;
            showImage();
        }
    });

    // Keyboard navigation
    $(document).keydown(function(e) {
        if ($('#imageModal').hasClass('show')) {
            if (e.keyCode == 37) { // Left arrow
                $('#prevBtn').click();
            } else if (e.keyCode == 39) { // Right arrow
                $('#nextBtn').click();
            } else if (e.keyCode == 27) { // ESC key
                $('#imageModal').modal('hide');
            }
        }
    });

    function showImage() {
        $('#fullsizeImage').attr('src', 'uploads/' + images[currentIndex].photo);
        $('#imageModal').modal('show');
    }
});
</script>
</body>
</html>