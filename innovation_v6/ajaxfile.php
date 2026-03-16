<?php
include('db/db.php');
 
$userid = $_POST['userid'];
 
$sql = "select * from innovation_tbl where id=".$userid;
$result = mysqli_query($conn,$sql);
while( $row = mysqli_fetch_array($result) ){
?>

<div class="table-responsive">  
    <table class="table table-bordered">
<tr>
    
    <td style="padding:4px;">
    উদ্ভাবনের শিরোনাম : <?php echo $row['title_of_invention']; ?>
    
    </td>
</tr>
<tr>
    
    <td style="padding:4px;">
    
    Details : <?php echo $row['des_of_invention']; ?>
    
    </td>
</tr>
</table>
</div>
<?php } ?>