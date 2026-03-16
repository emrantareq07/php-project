<?php
include "../../db.php";
$data=json_decode(file_get_contents('php://input'),true);
$ids=$data['ids']??[];
foreach($ids as $i=>$id){
$conn->query("UPDATE projects SET sort_order=$i WHERE id=$id");
}
echo json_encode(['status'=>'ok']);