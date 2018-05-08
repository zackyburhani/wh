<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  $warehouse = find_by_id('warehouse',(int)$_GET['id']);
  if(!$warehouse){
    $session->msg("d","Missing Warehouse id.");
    redirect('add_warehouse.php');
  }
?>
<?php
  $delete_id = delete_by_id('warehouse',(int)$warehouse['id']);
  if($delete_id){
      $session->msg("s","Warehouse deleted.");
      redirect('add_warehouse.php');
  } else {
      $session->msg("d","Warehouse deletion failed.");
      redirect('add_warehouse.php');
  }
?>
