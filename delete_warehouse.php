<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $warehouse = find_by_idwarehouse('warehouse',(int)$_GET['id_warehouse']);
  if(!$warehouse){
    $session->msg("d","Missing Warehouse id.");
    redirect('add_warehouse.php');
  }
?>
<?php
  $delete_id = delete_by_id_warehouse('warehouse',(int)$warehouse['id_warehouse']);
  if($delete_id){
      $session->msg("s","Warehouse deleted.");
      redirect('add_warehouse.php');
  } else {
      $session->msg("d","Warehouse deletion failed.");
      redirect('add_warehouse.php');
  }
?>
