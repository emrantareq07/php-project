<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
    exit;
}
?>
<?php include('includes/header.php');?>
<?php //include('includes/navbar.php');?> 
<div class="container mt-5">
    <!-- Book Search Section -->
    <div class="row my-4">
        <!-- Search Input -->
        <div class="col-md-6 position-relative">
            <label for="searchInput" class="form-label fw-bold">Search By Category, Author & Book</label>
            <div class="input-wrapper">
                <input list="bookList" id="searchInput" name="searchInput" class="form-control with-clear" placeholder="Search..." required>
                <span class="clear-btn" onclick="clearSearchInput()">×</span>
            </div>
            <datalist id="bookList">
                <?php
                $sql = "SELECT DISTINCT b.edition, b.book_name, c.CategoryName, a.AuthorName, b.author_id, b.category_id, b.remarks
                        FROM tblbooks b
                        JOIN tblcategory c ON b.category_id = c.id
                        JOIN tblauthors a ON b.author_id = a.id
                        order by c.categoryParent ASC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_array($result)) {
                    // echo "<option value='" . htmlspecialchars("{$row['CategoryName']} - {$row['book_name']} - {$row['AuthorName']} ({$row['edition']}),({$row['remarks']})", ENT_QUOTES) . "' 
                    //     data-book='" . htmlspecialchars($row['book_name'], ENT_QUOTES) . "' 
                    //     data-author='" . $row['author_id'] . "' 
                    //     data-category='" . $row['category_id'] . "'>";
                    $remarks = trim($row['remarks']);
                    $edition = $row['edition'];
                    $bookLabel = "{$row['CategoryName']} - {$row['book_name']} - {$row['AuthorName']} ({$edition})";
                    if (!empty($remarks)) {
                        $bookLabel .= ",({$remarks})";
                    }

                    echo "<option value='" . htmlspecialchars($bookLabel, ENT_QUOTES) . "' 
                            data-book='" . htmlspecialchars($row['book_name'], ENT_QUOTES) . "' 
                            data-author='" . $row['author_id'] . "' 
                            data-category='" . $row['category_id'] . "'>";
                }
                ?>
            </datalist>
        </div>

        <!-- Accession Numbers Output -->
        <div class="col-md-6 position-relative">
            <label class="fw-bold">Find Books</label>
            <div class="input-wrapper">
                <textarea class="form-control with-clear" id="accessionOutput" rows="4" readonly></textarea>
                <span class="clear-btn" onclick="clearAccessionTextarea()">×</span>
            </div>
        </div>
    </div>
    <hr>
    
    <!-- Dashboard Summary Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h4 class="header-line">ADMIN DASHBOARD</h4>
        </div>
    </div>

    <?php
    function getCount($query, $params = []) {
        global $dbh;
        $stmt = $dbh->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

        $bookCount = getCount("SELECT id FROM tblbooks");
        $issuedCount = getCount("SELECT id FROM tblissuedbookdetails");
        $returnedCount = getCount("SELECT id FROM tblissuedbookdetails WHERE RetrunStatus = :status", [':status' => 1]);
        $studentCount = getCount("SELECT id FROM tblstudents");
        $authorCount = getCount("SELECT id FROM tblauthors");
        $categoryCount = getCount("SELECT id FROM tblcategory");
    ?>

    <!-- Stats Cards -->
    <div class="row g-4">
        <div class="col-md-4 col-sm-6">
            <div class="alert alert-success alert-stat text-center">
                <i class="fa fa-book fa-4x mb-2"></i>
                <h3><?= htmlentities($bookCount); ?></h3>
                Books Listed
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="alert alert-info alert-stat text-center">
                <i class="fa fa-bars fa-4x mb-2"></i>
                <h3><?= htmlentities($issuedCount); ?></h3>
                Times Book Issued
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="alert alert-warning alert-stat text-center">
                <i class="fa fa-recycle fa-4x mb-2"></i>
                <h3><?= htmlentities($returnedCount); ?></h3>
                Times Books Returned
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="alert alert-danger alert-stat text-center">
                <i class="fa fa-users fa-4x mb-2"></i>
                <h3><?= htmlentities($studentCount); ?></h3>
                Registered Users
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="alert alert-primary alert-stat text-center">
                <i class="fa fa-user fa-4x mb-2"></i>
                <h3><?= htmlentities($authorCount); ?></h3>
                Authors Listed
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="alert alert-secondary alert-stat text-center">
                <i class="fa fa-file-archive fa-4x mb-2"></i>
                <h3><?= htmlentities($categoryCount); ?></h3>
                Listed Categories
            </div>
        </div>
    </div>

    <!-- Carousel Section -->
    <div class="row my-5">
        <div class="col-md-10 mx-auto">
            <div id="libraryCarousel" class="carousel slide slide-bdr" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="assets/img/1.jpg" class="d-block w-100" alt="Library Image 1">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/2.jpg" class="d-block w-100" alt="Library Image 2">
                    </div>
                    <div class="carousel-item">
                        <img src="assets/img/3.jpg" class="d-block w-100" alt="Library Image 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#libraryCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#libraryCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#libraryCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#libraryCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#libraryCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

function clearSearchInput() {
    const input = document.getElementById('searchInput');
    input.value = '';
    input.dispatchEvent(new Event('input'));
    document.getElementById('accessionOutput').value = '';
}

function clearAccessionTextarea() {
    document.getElementById('accessionOutput').value = '';
}

document.getElementById('searchInput').addEventListener('input', function() {
    const input = this.value;
    const options = document.getElementById('bookList').options;
    let selectedOption = null;
    
    for (let option of options) {
        if (option.value === input) {
            selectedOption = option;
            break;
        }
    }
    
    if (selectedOption) {
        fetchAccessionNumbers(
            selectedOption.getAttribute('data-book'),
            selectedOption.getAttribute('data-author'),
            selectedOption.getAttribute('data-category')
        );
    } else {
        document.getElementById('accessionOutput').value = '';
    }

    this.nextElementSibling.style.display = this.value ? 'block' : 'none';
});

document.getElementById('accessionOutput').addEventListener('input', function() {
    this.nextElementSibling.style.display = this.value ? 'block' : 'none';
});

function fetchAccessionNumbers(bookName, authorId, categoryId) {
    fetch('get_accession_numbers.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `book_name=${encodeURIComponent(bookName)}&author_id=${authorId}&category_id=${categoryId}`
    })
    .then(res => res.json())
    .then(data => {
        const output = document.getElementById('accessionOutput');
        output.value = data.success ? data.accession_numbers.join('') : 'No Books Available Now!!!';
    })
    .catch(err => {
        console.error(err);
        document.getElementById('accessionOutput').value = 'Error fetching data';
    });
}

// Show/hide clear buttons based on input
document.querySelectorAll('.with-clear').forEach(input => {
    input.addEventListener('input', function() {
        this.nextElementSibling.style.display = this.value ? 'block' : 'none';
    });
});
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>