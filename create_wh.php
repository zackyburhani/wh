<?php 

$koneksi = mysqli_connect("localhost","root","","");
mysqli_select_db($koneksi,"inventory");

$id_warehouse = 0010;
$nm_warehouse = $_POST['nm_warehouse'];
$country = $_POST['country'];
$address = $_POST['address'];
$status = $_POST['status'];
$heavy_max = $_POST['heavy_max'];
$heavy_consumed = $_POST['heavy_consumed'];

mysqli_query($koneksi,"INSERT INTO warehouse id_warehouse,nm_warehouse,country,address,status,heavy_max,heavy_consumed VALUES ('$id_warehouse','$nm_warehouse','$country','$address','$status','$heavy_max','$heavy_consumed')");


?>