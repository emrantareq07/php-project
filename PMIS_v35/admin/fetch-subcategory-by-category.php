<?php
require_once "db/db.php";
$category_id = $_POST["category_id"];
// $result = mysqli_query($conn,"SELECT * FROM categories where parent_id = $category_id");
$result = mysqli_query($conn,"SELECT * FROM subcategories where parent_id = $category_id");
?>
<option value="">Select SubCategory</option>
<?php
while($row = mysqli_fetch_array($result)) {
?>
<option value="<?php echo $row["id"];?>"><?php echo $row["subcategory_name"];?></option>
<!-- <option value="<?php //echo $row["id"];?>"><?php //echo $row["category"];?></option> -->
<?php
}
?>