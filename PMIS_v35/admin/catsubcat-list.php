 <?php

include('catsubcat-script.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<title>Category and SubCategory in PHP</title>

</head>
<body>
<!--=====category subcategory list=====-->
    
<?php

  foreach ($catData as $cat) {
      
?>

     <ul>
      <li>
          
<?php echo $cat['category_name']; ?>

              </li>
       <ul>
              
<?php
$subcatData=$cat['subcategories'];
        foreach ($subcatData as $subcat) {
            
?> <li><?php echo $subcat['subcategory_name']; ?></li> <?php

        }
      
?>

       </ul>
       </ul>
          


</body>
</html>