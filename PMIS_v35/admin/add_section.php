<?php
include_once('../includes/header-utilities.php');  
?>

<div class="container mt-3">
  
  <div class="row">
    <div class="col-sm-4">
        <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully!!!
    </div>
    <?php }?>
        
    </div>
    <div class="col-sm-4 border rounded shadow-lg border-primary">
        <h2 class="text-uppercase text-center">Add Section</h2>
    <form action="insert_section.php" method="post">

    <div class="form-floating mb-3">
      <input type="text" class="form-control" id="name"  name="name" placeholder="" required>
      <label for="name">Enter Section</label>
    </div>

 <div class="mb-3">
    <div class="form-group"><label > Choose Division: </label>
        
        <select name="division_id" class="form-control" required>
            <?php
            include_once('../db/dbconn.php');
           

            // Fetch the cadre values from the database
            $sql = "SELECT id, division FROM division";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['division'] . "</option>";
                }
            } else {
                echo "<option value=''>No division available</option>";
            }

            // Close the database connection
            $conn->close();
            ?>
        </select>
    </div>
</div>
        <input type="submit" value="Insert Data" class="btn btn-primary">
    </form>
    <br>
    </div>
    <div class="col-sm-4">
        <a href="add_division.php" class="btn btn-outline-info float-start mt-5">Previous Page</a>
    </div>
    
</div>
</div>
</div>
<?php 
 include_once('../includes/footer.php');
?>
