<?php 
require_once('includes/load.php');
page_require_level(1);

$id_warehouse   = autonumber('id_warehouse','warehouse');
$warehousename  = remove_junk($db->escape($_POST['warehousename']));
$country        = remove_junk($db->escape($_POST['country2']));
$status         = remove_junk($db->escape($_POST['status']));
$heavymax       = remove_junk($db->escape($_POST['heavymax']));
$heavy_consumed = 0; 
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

$query = $db->query("INSERT INTO warehouse (id_warehouse,nm_warehouse,country, address, status, heavy_max, heavy_consumed, latitude, longitude) VALUES ('$id_warehouse','$warehousename','$country','$address_AJX','$status','$heavymax','$heavy_consumed',$latt_AJX, $long_AJX)");

//delete duplicate warehouse in ajax
$query = $db->query("DELETE wh1 FROM warehouse wh1, warehouse wh2 WHERE wh1.nm_warehouse = wh2.nm_warehouse AND wh1.id_warehouse < wh2.id_warehouse");

?>