<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login']) == 0) { 
    header('location:index.php');
    exit();
}

$sid = $_SESSION['stdid'];

$sql = "SELECT password_changed, Image FROM tblstudents WHERE StudentId = :sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$student = $query->fetch(PDO::FETCH_OBJ);

if(!$student || $student->password_changed == 0) {
    header("Location: user_change_password.php");
    exit();
}

// Fix starts here 👇
$imageFile = $student->Image ?? '';
$imagePath = "admin/" . $imageFile;

if (!empty($imageFile)) {
    $userImage = file_exists($imagePath) ? $imagePath : "admin/student_images/default.png";
} else {
    $userImage = "admin/student_images/default.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard</title>
     <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-size: 16px;
            padding-top: 70px;
        }
        .card-stat {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-stat .card-body {
            padding: 2rem;
            text-align: center;
        }
        .card-stat i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .user-image {
            width: 35px;
            height: 35px;
            object-fit: cover;
        }
        .input-wrapper {
            position: relative;
        }
        .clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #999;
            cursor: pointer;
            display: none;
            background: none;
            border: none;
            z-index: 10;
        }
        .clear-btn:hover { 
            color: #555; 
        }
        textarea.with-clear + .clear-btn { 
            top: 20px; 
            right: 15px; 
            transform: none;
        }
        .student-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .student-img:hover {
            transform: scale(2);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .student-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .student-img-preview {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ccc;
            margin-top: 0px;
        }
        .alert-stat {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand bg-white rounded" href="dashboard.php">
            <img src="assets/img/logocollege.png" height="40" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse text-uppercase justify-content-end" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="issued-books.php"><i class="fas fa-book me-1"></i> Issued Books</a>
                </li>
                <li class="nav-item d-flex align-items-center me-3">
                    <img src="<?php echo htmlentities($userImage); ?>" 
                         class="rounded-circle border border-white user-image" 
                         alt="User Image">
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger fw-bold" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container py-3">
    <div class="row mb-4">
        <?php

$currentDate = date('Y-m-d');
//$sid = $_SESSION['stdid'];

// Use proper quotes around the date and validate variables
// $sql = "SELECT * FROM tblissuedbookdetails 
//         WHERE StudentID = '$sid' 
//           AND ReturnDate < '$currentDate' 
//           AND RetrunStatus = 0";

// $result = mysqli_query($conn, $sql);

    // Now run the detailed query
$sql = "SELECT  
            tblbooks.book_name,
            tblbooks.isbn,
            tblissuedbookdetails.IssuesDate,
            tblissuedbookdetails.ReturnDate,
            tblissuedbookdetails.RetrunStatus,
            tblissuedbookdetails.fine
        FROM tblissuedbookdetails
        JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId
        JOIN tblbooks ON tblbooks.accession_no = tblissuedbookdetails.accession_no
        WHERE tblstudents.StudentId = '$sid' 
          AND tblissuedbookdetails.RetrunStatus = 0
          AND tblissuedbookdetails.ReturnDate < '$currentDate'
        ORDER BY tblissuedbookdetails.id DESC";


    $notifResult = mysqli_query($conn, $sql);

    if (mysqli_num_rows($notifResult) > 0) {
        while ($row = mysqli_fetch_assoc($notifResult)) {
    echo "<li class='text-danger'><strong>{$row['book_name']}</strong> is overdue (Due Date: {$row['ReturnDate']}). Please return it immediately.</li>";
        }
    } 
    // else {
    //     echo "No overdue books found.";
    // }

            // $sql="SELECT RetrunStatus FROM tblissuedbookdetails WHERE StudentID='$studentId'";
            // $Result = mysqli_query($conn, $sql);
            // $row = mysqli_fetch_assoc($Result);
            // $RetrunStatus_db=$row['RetrunStatus'];

            // if($RetrunStatus_db==0){
            //      $notifQuery = "SELECT * FROM student_notifications 
            //                WHERE student_id = '$studentId' 
            //                ORDER BY created_at DESC 
            //                LIMIT 5";
            // $notifResult = mysqli_query($conn, $notifQuery);

            // if (mysqli_num_rows($notifResult) > 0) {
            //     echo '<div class="notifications-container mb-4">';
            //     echo '<h4 class="text-danger fw-bold">Your Notifications</h4>';
            //     echo '<ul class="list-group">';
            //     while ($notif = mysqli_fetch_assoc($notifResult)) {
            //         $readClass = $notif['is_read'] ? '' : 'unread';
            //         echo '<li class="list-group-item '.$readClass.'">';
            //         echo htmlspecialchars($notif['message']);
            //         echo '<small class="text-muted float-end">'.date('d M Y', strtotime($notif['created_at'])).'</small>';
            //         echo '</li>';
            //     }
            //     echo '</ul></div>';
            //     }
            // }     



            // ?>
             <!-- Book Search Section -->
    <div class="row my-4">
        <!-- Search Input -->
        <div class="col-md-6">
            <label for="searchInput" class="form-label fw-bold">Search By Category, Author & Book</label>
            <div class="input-wrapper">
                <input list="bookList" id="searchInput" name="searchInput" class="form-control with-clear" placeholder="Search..." required>
                <button type="button" class="clear-btn" onclick="clearSearchInput()">×</button>
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
        <div class="col-md-6">
            <label class="fw-bold">Find Books</label>
            <div class="input-wrapper">
                <textarea class="form-control with-clear" id="accessionOutput" rows="4" readonly></textarea>
                <button type="button" class="clear-btn" onclick="clearAccessionTextarea()">×</button>
            </div>
        </div>
    </div>
    <hr>
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-uppercase">USER DASHBOARD</h4>
            <h6 class="fw-bold text-uppercase text-primary text-end">
                [ Student ID: <?php echo htmlentities($_SESSION['stdid']); ?> ]
            </h6>
        </div>
    </div>
    
    <hr>

    <div class="row g-4">
        <?php 
        // Book Issued Count
        $sql1 = "SELECT id FROM tblissuedbookdetails WHERE StudentID = :sid";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query1->execute();
        $issuedbooks = $query1->rowCount();

        // Books Not Returned
        $sql2 = "SELECT id FROM tblissuedbookdetails WHERE StudentID = :sid AND RetrunStatus = 0";
        $query2 = $dbh->prepare($sql2);
        $query2->bindParam(':sid', $sid, PDO::PARAM_STR);
        $query2->execute();
        $returnedbooks = $query2->rowCount();
        ?>

        <div class="col-md-6">
            <div class="card card-stat border-0 bg-info text-white">
                <div class="card-body">
                    <i class="fas fa-book-open"></i>
                    <h3 class="card-title"><?php echo htmlentities($issuedbooks); ?></h3>
                    <p class="card-text">Books Issued</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-stat border-0 bg-warning text-dark">
                <div class="card-body">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3 class="card-title"><?php echo htmlentities($returnedbooks); ?></h3>
                    <p class="card-text">Books Not Returned Yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/custom.js"></script>
<!-- JavaScript Libraries -->

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});

// Show/hide clear buttons based on input
document.querySelectorAll('.with-clear').forEach(input => {
    // Initial check
    const clearBtn = input.nextElementSibling;
    if (clearBtn && clearBtn.classList.contains('clear-btn')) {
        clearBtn.style.display = input.value ? 'block' : 'none';
    }
    
    // Event listener for changes
    input.addEventListener('input', function() {
        const clearBtn = this.nextElementSibling;
        if (clearBtn && clearBtn.classList.contains('clear-btn')) {
            clearBtn.style.display = this.value ? 'block' : 'none';
        }
    });
});

// Clear functions
function clearSearchInput() {
    const input = document.getElementById('searchInput');
    input.value = '';
    input.dispatchEvent(new Event('input'));
    document.getElementById('accessionOutput').value = '';
    input.nextElementSibling.style.display = 'none';
}

function clearAccessionTextarea() {
    const textarea = document.getElementById('accessionOutput');
    textarea.value = '';
    textarea.nextElementSibling.style.display = 'none';
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
});

function fetchAccessionNumbers(bookName, authorId, categoryId) {
    fetch('admin/get_accession_numbers.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `book_name=${encodeURIComponent(bookName)}&author_id=${authorId}&category_id=${categoryId}`
    })
    .then(res => res.json())
    .then(data => {
        const output = document.getElementById('accessionOutput');
        output.value = data.success ? data.accession_numbers.join('') : 'Book Already issue. Plz Try Again Later!!!';
    })
    .catch(err => {
        console.error(err);
        document.getElementById('accessionOutput').value = 'Error fetching data';
    });
}
</script>

</body>
</html>