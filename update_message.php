<?php 
require_once('includes/load.php');
page_require_level(2);

$id_message   = remove_junk($db->escape($_POST['id_message']));
$id_warehouse = remove_junk($db->escape($_POST['id_warehouse']));

$query = $db->query("UPDATE message SET status = '1' WHERE id_message = '$id_message'");

$query2 = $db->query("SELECT id_message,subject,message,date,to_warehouse, from_warehouse,nm_warehouse FROM message,warehouse where warehouse.id_warehouse = message.from_warehouse and to_warehouse = '$id_warehouse' order by 1 desc");

?>