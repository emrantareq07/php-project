<?php
require_once("../db/db.php");
// include('db/db.php');
if(isset($_POST['addcat'])){
   $msg=create_category($conn);     
}
if(isset($_POST['addsubcat'])){
    $msg=create_subcategory($conn);     
}

function create_category($conn){
      $cadre= legal_input($_POST['category_name']);
      $query=$conn->prepare("INSERT INTO cadre (cadre) VALUES (?)");
      $query->bind_param('s',$cadre);
      $exec= $query->execute();
      if($exec){
         $msg=" Cadre was created successfully!!!";
         return $msg;
      }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($conn);
      }
}


function create_subcategory($conn){
      $cadre_id= legal_input($_POST['parent_id']);
      $sub_cadre= legal_input($_POST['subcategory_name']);
      $query=$conn->prepare("INSERT INTO sub_cadre (cadre_id,sub_cadre) VALUES (?,?)");
      $query->bind_param('is',$cadre_id,$sub_cadre);
      $exec= $query->execute();
      if($exec){
        $msg="Sub-Cadre was created sucessfully!!!";
        return $msg;
      }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($conn);
      }
}

// fetch query

$catData=fetch_categories($conn);

function fetch_categories($conn){ 
  //$cadre_id=0;
  // $query = $conn->prepare('SELECT * FROM cadre WHERE cadre_id=?');
  $query = $conn->prepare('SELECT * FROM cadre ');
  // $query->bind_param('i',$cadre_id); 
  $query->execute();
  $exec=$query->get_result();

  $catData=[];
  if($exec->num_rows>0){
    while($row= $exec->fetch_assoc())
    {
        $catData[]=[
          'id'=>$row['id'],
          // 'cadre_id'=>$row['cadre_id'],
          'cadre'=>$row['cadre'],
          'sub_cadre'=>fetch_subcategories($conn,$row['id'])
        ];  
   }
   return $catData;
        
  }else{
    return $catData=[];
  }
}

// fetch query

function fetch_subcategories($conn,$cadre_id){
  $query = $conn->prepare('SELECT * FROM sub_cadre WHERE cadre_id=?');
  $query->bind_param('i',$cadre_id); 
  $query->execute();
  $exec=$query->get_result();

  $subcatData=[];
if($exec->num_rows>0){
    while($row= $exec->fetch_assoc())
    {
        $subcatData[]=[
          'id'=>$row['id'],
          'cadre_id'=>$row['cadre_id'],
          'sub_cadre'=>$row['sub_cadre'],
          
        ];  
   }
   return $subcatData;
        
  }else{
    return $subcatData=[];
  }
}
// convert illegal input to legal input
function legal_input($value) {
  $value = trim($value);
  $value = stripslashes($value);
  $value = htmlspecialchars($value);
  return $value;
}
?>