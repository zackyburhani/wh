<?php 
require_once('includes/load.php');

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

$getAllWarehouseName = "SELECT nm_warehouse FROM warehouse where nm_warehouse = '$cat_name'";
    $ada=$db->query($getAllWarehouseName) or die(mysql_error());
    if(mysqli_num_rows($ada)>0)
    { 
    	$session->msg("d", "Warehouse Is Exist");
        redirect('add_warehouse_location.php',false);
    } else {
        if($db->query($sql)){
        	$session->msg("s", "Successfully Added Warehouse");
        	redirect('add_warehouse_location.php',false);
    	} else {
        	$session->msg("d", "Sorry Failed to insert.");
        	redirect('add_warehouse_location.php',false);
    	}
	}  


?>