<?php
include_once('../db/db.php');
include_once('header.php'); 
// Calculate fiscal year range
$date = date('Y-m-d');
$month11 = date('m', strtotime($date));
$year11 = date('Y', strtotime($date));

if ($month11 >= 7) {
    $year22 = $year11;
} else {
    $year22 = $year11 - 1;
}

$yearrange12 = "$year22-07-01";
$year22++;
$yearrange13 = "$year22-06-30";

if (isset($_POST['submit'])) {      
    $name = $_POST['name'];
    $code = $_POST['code'];  
    $specification = $_POST['specification'];
    $price = $_POST['price'];
    $warranty = $_POST['warranty'];


    $fiscal_start = $_POST['fiscal_start'];
    $fiscal_end = $_POST['fiscal_end'];

    $sql = "INSERT INTO product_tbl (name, code, specification, price, warranty, fiscal_start, fiscal_end) 
            VALUES ('{$name}', '{$code}', '{$specification}', '{$price}', '{$warranty}', '{$fiscal_start}', '{$fiscal_end}')";

    if (mysqli_query($conn, $sql)) {
        header("Location: add_products.php?submitted=successfully");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>


<div class="container mt-3 ">
  
  <div class="row">
    <div class="col-sm-3"> <a href="#" class="btn btn-outline-info float-start mt-5"><i class="fa fa-list" style="font-size:16px"></i> Old Products</a>
    </div>
    <div class="col-sm-6 border rounded shadow-lg border-primary"><h4 class="text-uppercase text-muted text-center">Add Products</h4>
     <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully
    </div>
    <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <div class="row g-2">
        <div class="col-md">
        <div class="form-floating mb-3 mt-3">
          <input type="text" class="form-control" id="name" placeholder="Enter University/Institute" name="name" required="">
          <label for="name ">Name</label>
        </div>
      </div>
      <div class="col-md">
      <div class="form-floating mt-3 mb-3">
        <input type="text" class="form-control" id="specification" placeholder="Enter password" name="specification">
        <label for="code">Specification</label>
      </div>
    </div>   
  </div>
  <div class="row g-2">
     <div class="col-md">
      <div class="form-floating mt-3 mb-3">
        <input type="text" class="form-control" id="code" placeholder="Enter password" name="code">
        <label for="code">Code</label>
      </div></div>
      <div class="col-md">
      <div class="form-floating mt-3 mb-3">
        <input type="text" class="form-control" id="price" placeholder="Enter password" name="price">
        <label for="price">Price</label>
      </div></div>
      <div class="col-md">
      <div class="form-floating mt-3 mb-3">
        <input type="text" class="form-control" id="warranty" placeholder="Enter password" name="warranty">
        <label for="warranty">Warranty (Years)</label>
      </div></div>
    </div>
    <div class="row g-2">
     <div class="col-md">
    <div class="form-floating mt-3 mb-3">
      <input type="date" class="form-control" id="fiscal_start" placeholder="Enter password" name="fiscal_start" value="<?= $yearrange12; ?>" >
      <label for="fiscal_start">Fiscal Start</label>
    </div></div>
    <div class="col-md">
    <div class="form-floating mt-3 mb-3">
      <input type="date" class="form-control" id="fiscal_end" placeholder="Enter password" name="fiscal_end" value="<?= $yearrange13; ?>" >
      <label for="fiscal_end">Fiscal End</label>
    </div></div></div>
    <button type="submit" name ="submit" class="btn btn-primary"><i class="fa fa-save" style="font-size:16px"></i> Submit</button>
  </form>
    <br>
    </div>


    <div class="col-sm-3">
      <a href="../dashboard.php" class="btn btn-outline-info float-end mt-5"><i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="table-wrapper"><h5 class="text-uppercase text-muted text-center"> Product List</h5>
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
         <th>ID NO</th>
        <th>Name</th>
        <th>Specification</th>
        <th>Code</th>
        <th>Price</th>
        <th>Warranty (Years)</th>
        <th>Fiscal Start</th>
        <th>Fiscal End</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `product_tbl`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['specification']; ?></td>
              <td><?php echo $row['code']; ?></td>
              <td><?php echo $row['price']; ?></td>
              <td><?php echo $row['warranty']; ?></td>
              <td><?php echo $row['fiscal_start']; ?></td>
              <td><?php echo $row['fiscal_end']; ?></td>

              <td>
                <a href="edit_products.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_products.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
  </div>
  </div>


</div>
<?php 
 include_once('footer.php');
?>


