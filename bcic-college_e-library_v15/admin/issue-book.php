<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Dhaka'); // Or your appropriate timezone
include('includes/config.php');
//include('includes/db.php');

if(strlen($_SESSION['alogin'])==0){   
    header('location:../index.php');
} else {

if(isset($_POST['submit'])) {
    $studentid = strtoupper($_POST['StudentId']);
    $IssuesDate = $_POST['IssuesDate'];
    $accession_no_arr = $_POST['accession_no'];
    $ReturnDate_arr = $_POST['ReturnDate'];
    $RetrunStatus = 0;

    if (!empty($accession_no_arr) && !empty($ReturnDate_arr)) {
        for ($i = 0; $i < count($accession_no_arr); $i++) {
            $accession_no = $accession_no_arr[$i];
            $ReturnDate = $ReturnDate_arr[$i];

            // First SQL Query - Inserting issued book details
            $sql = "INSERT INTO tblissuedbookdetails(StudentID, IssuesDate, accession_no, ReturnDate, RetrunStatus)
                    VALUES(:studentid, :IssuesDate, :accession_no, :ReturnDate, :RetrunStatus)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':IssuesDate', $IssuesDate, PDO::PARAM_STR);
            $query->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
            $query->bindParam(':ReturnDate', $ReturnDate, PDO::PARAM_STR);
            $query->bindParam(':RetrunStatus', $RetrunStatus, PDO::PARAM_INT);
            $query->execute();

            // Second SQL Query - Updating book status
            $sql_2 = "UPDATE tblbooks SET status=1 WHERE accession_no=:accession_no";       
            $query_2 = $dbh->prepare($sql_2);
            $query_2->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
            $query_2->execute();
        }

        $_SESSION['msg'] = "Books issued successfully";
        header('location:manage-issued-books.php');
        exit();
    } else {
        $_SESSION['error'] = "No book data provided.";
        header('location:manage-issued-books.php');
        exit();
    }
}
?>


<?php include('includes/header.php'); ?> 
<?php //include('includes/navbar.php');?> 

<div class="container">    
    <div class="card mt-2">
      <div class="card-header text-uppercase bg-primary fw-bold text-white">
        <i class="fa fa-tag"></i> Issue Books
      </div>
      <div class="card-body"> 
    <div class="form-group">
        <form method="POST" id="add_name">
            <div class="row">
                <div class="col-md-6">
                    <label for="StudentId">Student ID</label>
                    <input list="StudentIdList" type="text" class="form-control StudentId" name="StudentId" placeholder="Enter Student ID No" required oninput="fetchStudentDetails(this)">
                    <datalist id="StudentIdList">
                        <?php
                            $sql = "SELECT StudentId FROM tblstudents WHERE Status = '1'";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='" . htmlspecialchars($row['StudentId'], ENT_QUOTES, 'UTF-8') . "'>";
                            }
                        ?>
                    </datalist>
                </div>
                <div class="col-md-6">
                    <label for="IssuesDate">Issue Date</label>
                    <input type="date" class="form-control" name="IssuesDate" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <span id="get_student_name" style="font-size:16px;"></span>

            </div>
            <!--  <div class="form-group">
                <span id="get_student_image" style="font-size:16px;"></span>
                
            </div>
 -->
            <div class="table-responsive mt-3">
                <table class="table table-bordered" id="dynamic_field">
                    <thead>
                        <tr>
                            <td colspan="4" style="text-align: right;">
                                <button type="button" name="add" id="add" class="btn btn-success"><i class="fa fa-plus"></i> Add Book</button>
                            </td>
                        </tr>
                        <tr>
                            <th>ACCESSION NO</th>
                            <th>BOOK DETAILS</th>
                            <th>RETURN DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="row1">
                            <td>
                                <input list="accession_nos" type="text" class="form-control accession_no" name="accession_no[]" placeholder="Enter Accession No" required onchange="fetchBookDetails(this)">
                                <datalist id="accession_nos">
                                    <?php
                                        $sql = "SELECT accession_no FROM tblbooks WHERE status='0'";
                                        $result = mysqli_query($conn, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                            echo "<option value='" . htmlspecialchars($row['accession_no'], ENT_QUOTES, 'UTF-8') . "'>";
                                        }
                                    ?>
                                </datalist>
                            </td>
                            <td><input type="text" name="bookname[]" class="form-control bookname" readonly /></td>
                            <td><input type="date" name="ReturnDate[]" class="form-control return_date" required /></td>
                            <td><button type="button" name="remove" id="1" class="btn btn-danger btn_remove">X</button></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" name="submit" class="btn btn-primary" value="Submit" />
            </div>
        </form>
    </div>
</div>
 </div>
 </div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function addDays(dateStr, days) {
    const date = new Date(dateStr);
    date.setDate(date.getDate() + days);
    return date.toISOString().split('T')[0];
}

document.addEventListener("DOMContentLoaded", function() {
    const issueDateInput = document.querySelector('input[name="IssuesDate"]');

    // Set return date on load
    const initialReturnDate = addDays(issueDateInput.value, 15);
    document.querySelectorAll('.return_date').forEach(function(input) {
        input.value = initialReturnDate;
    });

    issueDateInput.addEventListener("change", function() {
        const returnDate = addDays(this.value, 15);
        document.querySelectorAll('.return_date').forEach(function(input) {
            input.value = returnDate;
        });
    });
});

$(document).ready(function(){
    var i = 1;
    $('#add').click(function(){
        i++;
        const issueDate = $('input[name="IssuesDate"]').val();
        const returnDate = addDays(issueDate, 15);

        var newRow = '<tr id="row'+i+'">\
            <td><input list="accession_nos" type="text" class="form-control accession_no" name="accession_no[]" placeholder="Enter Accession No" required onchange="fetchBookDetails(this)"></td>\
            <td><input type="text" name="bookname[]" class="form-control bookname" readonly /></td>\
            <td><input type="date" name="ReturnDate[]" class="form-control return_date" value="'+returnDate+'" required /></td>\
            <td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td>\
        </tr>';

        $('#dynamic_field tbody').append(newRow);
    });

    $(document).on('click', '.btn_remove', function(){
        $(this).closest('tr').remove();
    });
});

function fetchStudentDetails(inputElement) {
    var studentid = inputElement.value.trim();
    if (studentid !== "") {
        $.ajax({
            type: "POST",
            url: "get_student_new.php",
            data: { studentid: studentid },
            success: function(response) {
                $("#get_student_name").html(response);
            }
        });
    } else {
        $("#get_student_name").html('');
    }
}

function fetchBookDetails(element) {
    var accession_no = $(element).val();
    var row = $(element).closest('tr');
    var isDuplicate = false;

    $('.accession_no').not(element).each(function() {
        if ($(this).val() === accession_no) {
            isDuplicate = true;
            return false;
        }
    });

    if (isDuplicate) {
        alert('This Book already been selected.');
        $(element).val('');
        row.find('.bookname').val('');
        return;
    }

    if (accession_no !== '') {
        $.ajax({
            url:"fetch_book.php",
            method:"POST",
            data:{accession_no:accession_no},
            success:function(data) {
                var result = JSON.parse(data);
                if(result.success) {
                    row.find('.bookname').val(result.book_name);
                } else {
                    alert(result.message);
                    row.find('.bookname').val('');
                }
            }
        });
    } else {
        row.find('.bookname').val('');
    }
}
</script>

<?php include('includes/footer.php'); ?>
</body>
</html>
<?php } ?>
