<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>
<?php
  // $delete_id = delete_by_id('sidebar',(int)$_GET['id']);
  
  $id = find_by_id('sidebar',(int)$_GET['id']);
  if(!$id){
    $session->msg("d","Missing Menu id.");
    redirect('add_menu.php');
  }

  // $query = "delete from sidebar where id='$id[id]'";
  $query = "DELETE a.*, b.* FROM sidebar a JOIN sub_sidebar b ON a.id = b.id_menu WHERE a.id_menu = '$id[id]'";

  $delete_id=$db->query($query) or die(mysql_error());

  if($delete_id){
      $session->msg("s","Menu deleted.");
      redirect('add_menu.php');
  } else {
      $session->msg("d","Menu deletion failed Or Missing Prm.");
      redirect('add_menu.php');
  }
?>
