<?php

 require_once('includes/load.php');
  $user = current_user();
  $id = $user['id_warehouse'];

$sql = "SELECT po.id_po,po.date_po,po.id_warehouse as For_wh,detil_po.date_po,qty,status,detil_po.id_warehouse as From_wh,total_weight,id_item FROM po JOIN detil_po WHERE po.id_po = detil_po.id_po AND po.id_warehouse = '$id' GROUP by po.id_po";
   $array = $db->num_rows($sql);
       echo json_encode($array);
// $j = mysql_num_rows($pesan);
// if($array>0){
//     echo $array;
// }
?>