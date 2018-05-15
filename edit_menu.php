<?php
  $page_title = 'Edit Menu';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
?>

<?php
  //Display all catgories.
  $sidebar = find_by_id('sidebar',(int)$_GET['id']);
  if(!$sidebar){
    $session->msg("d","Missing Menu.");
    redirect('add_menu.php');
  }
?>

<?php
if(isset($_POST['edit_menu'])){
  $req_field = array('menu-name');
  validate_fields($req_field);
  $menu_name = remove_junk($db->escape($_POST['menu-name']));
  if(empty($errors)){
        $sql = "UPDATE sidebar SET name = '{$menu_name}'";
       $sql .= " WHERE id='{$sidebar['id']}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Menu");
       redirect('add_menu.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_menu.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_menu.php',false);
  }
}
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
   <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Editing <?php echo remove_junk(ucfirst($sidebar['name']));?></span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="post" action="edit_menu.php?id=<?php echo (int)$sidebar['id'];?>">
           <div class="form-group">
               <input type="text" class="form-control" name="menu-name" value="<?php echo remove_junk(ucfirst($sidebar['name']));?>">
           </div>
           <button type="submit" name="edit_menu" class="btn btn-primary">Update Warehouse</button>
       </form>
       </div>
     </div>
   </div>
</div>



<?php include_once('layouts/footer.php'); ?>
