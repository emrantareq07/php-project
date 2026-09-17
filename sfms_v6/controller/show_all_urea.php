<?php
// include('include/header.php');
session_start();
$table=$_SESSION['username'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

$i=0;
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Datepicker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.css" />

    <!-- <title > <center> <h4>[--বিসিআইসি মামলার ডাটাবেস--] </h4></center></title> -->
    <title > </title>
     <style type="text/css">
    body{
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box{    
    width:auto;
    padding:10px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin:10px 10px;
  box-shadow: 5px 10px 18px #888888;
/*box-shadow: 5px 5px 8px blue, 10px 10px 8px red, 15px 15px 8px green;
  width:1270px;
margin:10px;
  */
}

</style>
<link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
</head>

<body>
     <div class="container-fluid box border border-rounded shadow ">
        <div class="row">
            <div class="col-md-12 mt-1">
                   <h3 align="center" class="text-center text-muted text-uppercase">   
                    DFMS [--Search with Date--] 
                    <span  class="float-right"><a href="urea_form.php" class="btn btn-primary ">Previous Page</a></span>
                    
                    
                   </h3>
                <h1 class="text-center"></h1>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                        class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" id="start_date" placeholder="Start Date" >
                          <!--   <input type="text" class="form-control" id="start_date" placeholder="Start Date" readonly> -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white" id="basic-addon1"><i
                                        class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" id="end_date" placeholder="End Date" >
                           <!--  <input type="text" class="form-control" id="end_date" placeholder="End Date" readonly> -->
                        </div>
                    </div>
                </div>
                <div>
                    <button id="filter" class="btn btn-outline-info btn-sm">Filter</button>
                    <button id="reset" class="btn btn-outline-warning btn-sm">Reset</button>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table display nowrap border table-hover table-stripped " id="records" style="width:100%">
                         <!-- <table class="table table-borderless display nowrap" id="records" style="width:100%"> -->
                                <thead>
                                    <tr>
                                    	<!-- <th class="text-center">#</th> -->
                                       <th class="">Date</th>  
                                        <th class="">Daily</th>  
                                        <th class="">Monthly</th>  
                                        <th class="">Yearly</th>
                                        <!-- <th class="text-center">Target</th>  
                                        <th class="text-center">Due</th> -->
                                        <th class="">Remarks</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
    <!-- Datepicker -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    

    <!-- Datatables -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/r-2.2.3/datatables.min.js">
    </script>
    <!-- Momentjs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

    <script>
    $(function() {
        $("#start_date").datepicker({
            "dateFormat": "yy-mm-dd"
        });
        $("#end_date").datepicker({
            "dateFormat": "yy-mm-dd"
        });
    });

    // Fetch records
    function fetch(start_date, end_date) {
    	var table1 = "<?php echo $table; ?>";
        $.ajax({
            url: "records_promotion_due.php",
            type: "POST",
            data: {
                start_date: start_date,
                end_date: end_date,
                 table1: table1
            },
            
            dataType: "json",
            success: function(data) {
                // Datatables
                var i = "1";

                $('#records').DataTable({
                    "data": data,
                    dom: 'Bfrtip',
                    // buttons
                    "dom": "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'B><'col-sm-12 col-md-4'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    "buttons": [
                        'copy', 'csv', 'excel', 'pdf', {
                extend: 'print',
                text: 'Print',
                customize: function(win) {
                    $(win.document.body)
                        .prepend('<div style="text-align:center;margin-bottom:10px;"><h3>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন (বিসিআইসি)</h3><h6>৩০-৩১ দিলকুশা, বা/এ ঢাকা-১০০০।</h6><h5 class="text-decoration-underline"">Fertililzer Production Information</h5></div>');
                    $(win.document.body).addClass('landscape');
                    $(win.document).find('title').remove(); // Remove title tag
                }
            },
                    ],
                    // responsive
                    "responsive": true,
                    "columns": [
                    // {
                    //         "data": "id",
                    //         // "render": function(data, type, row, meta) {
                    //         //     return i++;
                    //         // }
                    //     },
                        {
                            "data": "date"
                        },
                        {
                            "data": "daily"
                        },
                        {                          
                            "data": "monthly",
                            
                        },
                        {                            
                             "data": "yearly",
                            
                        },
                        {
                            "data": "remarks"
                        }                       

                    ],
                    "order": [[0, "desc"]]
                });
            }
        });
    }
    fetch();

    // Filter
    $(document).on("click", "#filter", function(e) {
        e.preventDefault();

        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        if (start_date == "" || end_date == "") {
            alert("both date required");
        } else {
            $('#records').DataTable().destroy();
            fetch(start_date, end_date);
        }
    });

    // Reset
    $(document).on("click", "#reset", function(e) {
        e.preventDefault();

        $("#start_date").val(''); // empty value
        $("#end_date").val('');

        $('#records').DataTable().destroy();
        fetch();
    });
    </script>
</body>

</html>