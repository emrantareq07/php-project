<?php
require_once("includes/header.php");
?>


  <main id="main">
    <!-- ======= Photo Gallery Section ======= -->
    <section class="scrolling/photo_gallery">
        <div class="container mt-3" data-aos="fade-up">
            <div class="row">
                <div class="section-title mt-4 pt-4 mb-0">
                    <h2>Photo Gallery Categories</h2>
                </div>
            </div>

            <div class="gallery">
                <?php 
                require_once 'db/db.php';
                
                // Fetch all distinct categories
                $categoryQuery = "SELECT DISTINCT category FROM photo_gallary ORDER BY category";
                $categoryResult = mysqli_query($conn, $categoryQuery);
                
                if(mysqli_num_rows($categoryResult) > 0) {
                    while ($category = mysqli_fetch_assoc($categoryResult)) {
                        echo '<div class="category-section mb-5">';
                        echo '<h3 class="category-title mb-3">' . htmlspecialchars($category['category']) . '</h3>';
                        
                        // Fetch subcategories for this category
                        $subcatQuery = "SELECT DISTINCT sub_category FROM photo_gallary 
                                       WHERE category = '" . mysqli_real_escape_string($conn, $category['category']) . "'
                                       ORDER BY sub_category";
                        $subcatResult = mysqli_query($conn, $subcatQuery);
                        
                        echo '<div class="list-group">';
                        while ($subcat = mysqli_fetch_assoc($subcatResult)) {
                            echo '<a href="subcategory_gallery.php?category=' . urlencode($category['category']) . 
                                 '&subcategory=' . urlencode($subcat['sub_category']) . '" 
                                 class="list-group-item list-group-item-action">';
                            echo htmlspecialchars($subcat['sub_category']);
                            echo '</a>';
                        }
                        echo '</div></div>';
                    }
                } else {
                    echo '<div class="alert alert-info">No categories found.</div>';
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
require_once("includes/footer.php");
?>