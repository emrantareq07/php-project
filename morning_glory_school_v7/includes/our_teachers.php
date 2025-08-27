<section>
  <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Testimonials</h2>
          <p>Our Teachers</p>
        </div>

  
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleControls" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

<?php
// Database connection
$host = "localhost";
$user = "root";   // change if needed
$pass = "";       // change if needed
$db   = "morning_glory_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch teacher data
$sql = "SELECT id, name, designation, image FROM teacher_info";
$result = $conn->query($sql);
?>

<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">

    <?php
    if ($result->num_rows > 0) {
        $count = 0;
        $active = "active";

        while ($row = $result->fetch_assoc()) {
            // Open a new carousel-item every 3 cards
            if ($count % 3 == 0) {
                echo '<div class="carousel-item ' . $active . '">';
                echo '<div class="cards-wrapper d-flex justify-content-center">';
                $active = ""; // only first item is active
            }
            ?>

            <div class="card mx-2" style="width: 18rem;">
              <img src="includes/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text"><?php echo htmlspecialchars($row['designation']); ?></p>
              </div>
            </div>

            <?php
            $count++;

            // Close carousel-item after 3 cards OR at the last record
            if ($count % 3 == 0 || $count == $result->num_rows) {
                echo '</div></div>';
            }
        }
    } else {
        echo "<div class='carousel-item active'><p class='text-center'>No teacher found</p></div>";
    }
    ?>
  </div>

  <!-- Carousel controls -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<?php $conn->close(); ?>

</div>
</div>
</section>