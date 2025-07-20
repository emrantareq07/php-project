<div class="container">  
 <div class="row my-2">  
    <div class="col-sm-3">
        <form class="form" action="" method="post" style="display: inline-block; width: auto;">
                <!-- <div class="col-sm-12"> -->
                    <div class="form-group" style="display: inline-block; width: auto;">
                        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" required>
                        
                    </div>
                    <div class="form-group" style="display: inline-block; width: auto;">
                        <input type="submit" value="Search" class="btn btn-primary" id="search-btn" name="hit">
                    </div>                
            </form>     
      
    </div>
    <div class="col-sm-6">
    <h4 class="text-success text-center my-1 text-uppercase"> <b>Urea Production Report [--All Factory--] </b></h4>
    </div>  
   <div class="col-sm-3" style="display: inline-block; width: auto;">     
  <span class="text-center  float-end">
    <a class="btn btn-primary text-center" id="login-btn" href="dashboard.php">Login</a>
    <button onclick="window.print();return false;" class="btn btn-danger text-center" id="print-btn"><i class="fa fa-print"></i> Print</button>
    <form class="form" action="download_report_pdf.php" method="post" style="display: inline-block; width: auto;">
       <button type="submit" name="submit" class="btn btn-danger text-center" id="download_pdf"><i class="fa fa-file-pdf-o"></i> Download</button> 
    </form>   
  </span>
</div>

 </div> 
 <?php error_reporting (E_ALL ^ E_NOTICE); ?>

<div class="row">
  <div class="col-sm-12">
  <div class="card shadow border border-success">
  <div class="card-header"> </div>
  <div class="card-body">
  <table class="table table-bordered table-striped" id="table_content">
    <thead>
        <tr>
          <th>Date</th>
            <th>Factory Name</th>            
            <th>Daily (MT)</th>
            <th>Monthly (MT)</th>
            <th>Yearly (MT)</th>
            <th>Production Target (MT)</th>
            <th>Due (MT)</th>
            <th>Plant Load (%)</th>
            <th>Causes of Shutdown</th>
      </tr>
    </thead>
    <tbody> 