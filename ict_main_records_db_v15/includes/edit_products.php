<?php
include_once('../db/db.php');
include_once('header.php'); 

// Get product details based on ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM product_tbl WHERE id = '$id'");
    $product = mysqli_fetch_array($query);
}

// Update product details
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $code = $_POST['code'];
    $specification = $_POST['specification'];
    $price = $_POST['price'];
    $warranty = $_POST['warranty'];
    $fiscal_start = $_POST['fiscal_start'];
    $fiscal_end = $_POST['fiscal_end'];

    $updateQuery = "UPDATE product_tbl 
                    SET name = '$name', 
                        code = '$code', 
                        specification = '$specification', 
                        price = '$price', 
                        warranty = '$warranty', 
                        fiscal_start = '$fiscal_start', 
                        fiscal_end = '$fiscal_end' 
                    WHERE id = '$id'";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: add_products.php?updated=successfully");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<div class="container mt-3">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 border rounded shadow-lg border-primary">
            <h4 class="text-uppercase text-muted text-center">Edit Product</h4>

            <?php if (isset($_GET['updated'])) { ?>
                <div class="alert alert-success" role="alert">
                    Product Updated Successfully
                </div>
            <?php } ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                    <label for="name">Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="code" name="code" value="<?php echo $product['code']; ?>">
                    <label for="code">Code</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="specification" name="specification" value="<?php echo $product['specification']; ?>">
                    <label for="specification">Specification</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>">
                    <label for="price">Price</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="warranty" name="warranty" value="<?php echo $product['warranty']; ?>">
                    <label for="warranty">Warranty (Years)</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fiscal_start" name="fiscal_start" value="<?php echo $product['fiscal_start']; ?>">
                    <label for="fiscal_start">Fiscal Start</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="date" class="form-control" id="fiscal_end" name="fiscal_end" value="<?php echo $product['fiscal_end']; ?>">
                    <label for="fiscal_end">Fiscal End</label>
                </div>

                <button type="submit" name="update" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
            </form>
            <br>
        </div>
        <div class="col-sm-3">
             <a href="add_products.php" class="btn btn-outline-info float-end mt-5"><i class="fa fa-arrow-left" style="font-size:16px"></i> Previous Page</a>
        </div>
    </div>

</div>

<?php include_once('footer.php'); ?>
