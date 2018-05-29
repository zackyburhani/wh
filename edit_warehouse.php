<?php
  $page_title = 'Edit Warehouse';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>
<?php
  //Display all catgories.
  $categorie = find_by_id('warehouse',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","Missing warehouse id.");
    redirect('categorie.php');
  }
?>

<?php
if(isset($_POST['edit_warehouse'])){
  $req_field = array('warehouse-name');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['warehouse-name']));
  if(empty($errors)){
        $sql = "UPDATE warehouse SET nm_warehouse='{$cat_name}'";
       $sql .= " WHERE id='{$categorie['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Warehouse");
       redirect('add_warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_warehouse.php',false);
  }
}
?>
