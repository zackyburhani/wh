<?php 
require_once('includes/load.php');
page_require_level(1);

 
$id_warehouse   = remove_junk($db->escape($_POST['id_warehouse']));

if(empty($id_warehouse)):
    redirect('home.php',false);
  else:
  	$warehousename  = remove_junk($db->escape($_POST['warehousename']));
$country        = remove_junk($db->escape($_POST['country2']));
$status         = remove_junk($db->escape($_POST['status']));
$heavymax       = remove_junk($db->escape($_POST['heavymax']));
$address_AJX    = remove_junk($db->escape($_POST['address_AJX']));
$latt_AJX       = remove_junk($db->escape($_POST['latt_AJX']));
$long_AJX       = remove_junk($db->escape($_POST['long_AJX']));
$convert_max    = remove_junk($db->escape($_POST['convert_max']));

//convert weight
if($convert_max == "max_kilograms"){
   $heavymax = $heavymax;
} else if($convert_max == "max_ton") {
  $heavymax = $heavymax*1000;
} 
  $sql = "UPDATE warehouse SET nm_warehouse='{$warehousename}',country='{$country}',address='{$address_AJX}',status='{$status}',heavy_max='{$heavymax}',latitude='{$latt_AJX}',longitude='{$long_AJX}' WHERE id_warehouse='{$id_warehouse}'";

  $result = $db->query($sql);
  endif;

?>