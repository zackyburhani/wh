<?php
  $page_title = 'Add Location';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all1('location');
?>

<!-- INSERT WAREHOUSE -->
<?php
 if(isset($_POST['add_location'])){
   $req_field = array('unit','floor','room','id_warehouse');
   validate_fields($req_field);
   $id_loc   = autonumber('id_location','location');
   $cat_unit = remove_junk($db->escape($_POST['unit']));
   $floor = remove_junk($db->escape($_POST['floor']));
   $room = remove_junk($db->escape($_POST['room']));
   $id_wh = remove_junk($db->escape($_POST['id_warehouse']));
   if(empty($errors)){
      $sql  = "INSERT INTO location (id_location,unit,floor,room,id_warehouse)";
      $sql .= " VALUES ('{$id_loc}','{$cat_unit}','{$floor}','{$room}','{$id_wh}')";

       if($db->query($sql)){
        $session->msg('s',"success ");
        redirect('add_location.php', false);
      } else {
        $session->msg('d',' Sorry failed to added!');
        redirect('add_location.php', false);
      }

    } else{
       $session->msg("d", $errors);
       redirect('add_location.php',false);
     }

      /*$getAllLocationName = "SELECT unit FROM location where unit = '$cat_unit'";
      $ada=$db->query($getAllLocationName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Location Is Exist");
        redirect('add_location.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added location");
          redirect('add_location.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_location.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_location.php',false);
   }*/
 }
?>
<!-- END INSERT WAREHOUSE -->

<!-- UPDATE WAREHOUSE -->
<?php
if(isset($_POST['update_location'])){
  $req_field = array('unit','floor','room','id_location');
  validate_fields($req_field);
  $cat_unit = remove_junk($db->escape($_POST['unit']));
  $floor = remove_junk($db->escape($_POST['floor']));
  $room = remove_junk($db->escape($_POST['room']));
  $id_wh = remove_junk($db->escape($_POST['id_warehouse']));
  $idlocation = remove_junk($db->escape($_POST['id_location']));
  if(empty($errors)){
        $sql = "UPDATE location SET unit='{$cat_unit}',floor='{$floor}',room='{$room}',id_warehouse='{$id_wh}'";
       $sql .= " WHERE id_location='{$idlocation}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated location");
       redirect('add_location.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_location.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_location.php',false);
  }
}
?>
<!-- END UPDATE WAREHOUSE -->

<!-- DELETE WAREHOUSE -->
<?php
  if(isset($_POST['delete_location'])){
  $req_field = array('idlocation');
  validate_fields($req_field);
  $id_location = remove_junk($db->escape($_POST['idlocation']));



  if(empty($errors)){
        $sql = "DELETE FROM location WHERE id_location='{$id_location}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully delete location");
       redirect('add_location.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Delete");
       redirect('add_location.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_location.php',false);
  }
}
?>
<!-- END DELETE WAREHOUSE -->

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Location</span>
     </strong>
       <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addLocation"><span class="glyphicon glyphicon-plus"></span> Add location
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 50px;">Location Unit</th>
            <th class="text-center" style="width: 50px;">Floor</th>
            <th class="text-center" style="width: 50px;">Room</th>
            <th class="text-center" style="width: 50px;">WareHouse</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_categories as $a_location): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><a href="#detilItem<?php echo $a_location['id_location'];?>" data-toggle="modal" title="Detail"><?php echo remove_junk(ucwords($a_location['unit']))?></a></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_location['floor']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_location['room']))?></td>
            <td class="text-center"><?php echo remove_junk(ucwords($a_location['id_warehouse']))?></td>
           <td class="text-center">
                <button data-target="#updateLocation<?php echo (int)$a_location['id_location'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deletelocation<?php echo (int)$a_location['id_location'];?>" title="Delete"><i class="glyphicon glyphicon-trash"></i>
                </button>

           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>

<!-- MODAL ADD NEW WAREHOUSE -->
<div class="modal fade" id="addLocation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Entry New Location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="clearfix">
        <div class="form-group">
          <!--<label class="control-label">Location Unit</label>-->
          <input type="hidden" class="form-control" name="location">
        </div>
        <div class="form-group">
          <label class="control-label">Location Unit</label>
          <input type="name" class="form-control" name="unit">
        </div>
        <div class="form-group">
          <label class="control-label">Floor</label>
          <input type="name" class="form-control" name="floor">
        </div>  
        <div class="form-group">
          <label class="control-label">Room</label>
          <input type="name" class="form-control" name="room">
          
        </div>
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="name" class="form-control" name="id_warehouse">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="add_location" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
  </div>
</div>
<!-- END MODAL ADD NEW WAREHOUSE -->

<!-- MODAL UPDATE WAREHOUSE -->
<?php foreach($all_categories as $a_location): ?> 
<div class="modal fade" id="updateLocation<?php echo (int)$a_location['id_location'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Data location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Location Unit</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['id_location'])); ?>" name="idlocation">
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['unit'])); ?>" name="unit">
        </div>
        <div class="form-group">
          <label class="control-label">Floor</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['floor'])); ?>" name="floor">
        </div>  
        <div class="form-group">
          <label class="control-label">Room</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['room'])); ?>" name="room">
        </div> 
        <div class="form-group">
          <label clas
          s="control-label"></label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['room'])); ?>" name="room">
        </div> 
    
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        z<button type="submit" name="update_location" class="btn btn-primary">Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL UPDATE WAREHOUSE -->

<!-- MODAL DELETE WAREHOUSE -->
<?php foreach($all_categories as $a_location): ?>
<div class="modal fade" id="deletelocation<?php echo (int)$a_location['id_location'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="cl?php echo remove_junk(ucwords($a_location['unit'])); ?></b> From <b><?php echo remove_junk(ucwords($a_location['floor'])); ?></b>?</p>  
      </div>earfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['id_location'])); ?>" name="idlocation">
        <p>Are You Sure to Delete Warehouse <b><
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="delete_location" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE WAREHOUSE -->

<?php include_once('layouts/footer.php'); ?>
