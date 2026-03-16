<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");

if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

?>
        <div class="col-md-4">
            <label class="form-label required">Fiscal Year</label>
            <select name="fiscal_year" class="form-select" required>
                <option value="">Select Fiscal Year</option>
                <?php  
                /* ===============================
                   GET ACTIVE FISCAL YEAR
                =================================*/
                $recent_query = "
                    SELECT fiscal_year 
                    FROM tbl_innovation                     
                ";

                $recent_result = mysqli_query($conn, $recent_query);

                if ($recent_result && mysqli_num_rows($recent_result) > 0) {
                    $row_fiscal_year = mysqli_fetch_assoc($recent_result);
                    $fiscal_year = $row_fiscal_year['fiscal_year'];

                    // If you want to mark it as selected
                    $selected = "selected";

                    echo "<option value='{$fiscal_year}' {$selected}>{$fiscal_year}</option>";
                }
                ?>
            </select>   
        </div>
<div class="col-md-4">
<label>Status</label>
<select name="status" class="form-select">
<option value="submitted idea">Submitted Idea</option>
<option value="primarily selected">Primarily Selected</option>
<option value="final selected" >Final Selected</option>
</select>
</div>
<button type="submit" name="submit" class="btn btn-success">Search</button>

