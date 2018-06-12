<?php
  $page_title = 'Add Location';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $user = current_user();

  $all_location = find_all_location('location',$user['id_warehouse']);

?>

<!-- INSERT LOCATION -->
<?php
  if(isset($_POST['add_location'])){
    $req_field = array('unit','floor','room');
    validate_fields($req_field);
    $id_loc   = autonumber('id_location','location');
    $cat_unit = remove_junk($db->escape($_POST['unit']));
    $floor    = remove_junk($db->escape($_POST['floor']));
    $room     = remove_junk($db->escape($_POST['room']));
    $id_wh    =  $user['id_warehouse'];  
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
 }
?>
<!-- END INSERT LOCATION -->

<!-- UPDATE LOCATION -->
<?php
if(isset($_POST['update_location'])){
  $req_field = array('unit','floor','room','id_location');
  validate_fields($req_field);
  $id_loc   = remove_junk($db->escape($_POST['id_location']));
  $cat_unit = remove_junk($db->escape($_POST['unit']));
  $floor    = remove_junk($db->escape($_POST['floor']));
  $room     = remove_junk($db->escape($_POST['room']));
  $id_wh1    =  $user['id_warehouse'];  
  if(empty($errors)){
        $sql = "UPDATE location SET unit='{$cat_unit}',floor='{$floor}',room='{$room}',id_warehouse='{$id_wh1}'";
       $sql .= " WHERE id_location='{$id_loc}'";
     $result = $db->query($sql);
     if($result) {
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
<!-- END UPDATE LOCATION -->

<!-- DELETE LOCATION-->
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
<!-- END DELETE LOCATION -->

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
     <?php
      if ($user['level_user']==0 || $user['level_user']==1) { ?>
       <button type="button" title="Add Location" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addLocation"><span class="glyphicon glyphicon-plus"></span> Add location
        </button>
      <?php } ?>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablelocation">
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
        <?php $no=1; ?>
        <?php foreach($all_location as $a_location): ?>
      
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_location['unit']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_location['floor']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_location['room']))?></td>
            <td class="text-center"><?php echo remove_junk(ucwords($a_location['id_warehouse']))?></td>
           <td class="text-center">
                <button data-target="#updateLocation<?php echo $a_location['id_location'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
              <?php
                if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) 
                  { ?>
                <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deletelocation<?php echo $a_location['id_location'];?>" title="Delete"><i class="glyphicon glyphicon-trash"></i>
                </button>
              <?php } ?>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>

<!-- MODAL ADD NEW LOCATION -->
<br>
<div class="modal fade" id="addLocation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="glyphicon glyphicon-th"></i> Entry New Location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="clearfix">
          <input type="hidden" class="form-control" name="location" required>
        <div class="form-group">
          <label class="control-label">Location Unit</label>
          <input type="name" class="form-control" name="unit" required>
        </div>
        <div class="form-group">
          <label class="control-label">Floor</label>
          <input type="name" class="form-control" name="floor" required>
        </div>  
        <div class="form-group">
          <label class="control-label">Room</label>
          <input type="name" class="form-control" name="room" required>
        </div>
      </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
      <button type="submit" name="add_location" title="Save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
    </div>
  
  </form>
</div>
</div>
</div>
<!-- END MODAL ADD NEW LOCATION -->

<!-- MODAL UPDATE LOCATION -->
<?php foreach($all_location as $a_location): ?> 
<div class="modal fade" id="updateLocation<?php echo $a_location['id_location'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="glyphicon glyphicon-th"></i> Update Data location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="clearfix">
        <div class="form-group" required>
          <label class="control-label">Location Unit</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['id_location'])); ?>" name="id_location" required>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['unit'])); ?>" name="unit" required>
        </div>
        <div class="form-group">
          <label class="control-label">Floor</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['floor'])); ?>" name="floor" required>
        </div>  
        <div class="form-group">
          <label class="control-label">Room</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_location['room'])); ?>" name="room" required>
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
        <button type="submit" title="Update" name="update_location" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL UPDATE LOCATION -->

<!-- MODAL DELETE LOCATION -->
<?php foreach($all_location as $a_location): ?>
<div class="modal fade" id="deletelocation<?php echo $a_location['id_location'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Delete Location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_location.php" class="cl?php echo remove_junk(ucwords($a_location['unit'])); ?></b> From <b><?php echo remove_junk(ucwords($a_location['floor'])); ?></b>?</p>  
      </div>earfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['id_location'])); ?>" name="idlocation">
        Are You Sure to Delete Location <b><?php echo $a_location['unit']; ?></b> ?
      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i>Close</button>
        <button type="submit" title="Delete" name="delete_location" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
      </div>
    </form>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE LOCATION -->

<?php include_once('layouts/footer.php'); ?>
