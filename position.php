<?php
  $page_title = 'All Group';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_warehouse = find_all1('warehouse');
   $user = current_user();

?>

<!-- ADD NEW POSITION -->
<?php
  if(isset($_POST['add_position'])){

   $req_fields = array('nm_position','level_user');
   validate_fields($req_fields);

   // if(find_by_positionName($_POST['nm_position']) === false ){
   //   $session->msg('d','<b>Sorry!</b> Entered Position Name Already In Database!');
   //   redirect('position.php', false);
   // }
   if(empty($errors)){
        $level_user   = remove_junk($db->escape($_POST['level_user']));
        $nm_position  = remove_junk($db->escape($_POST['nm_position']));
        $id_position  = autonumber('id_position','position');
        $id_warehouse = $user['id_warehouse'];
        $query  = "INSERT INTO position (";
        $query .="id_position,nm_position,level_user,id_warehouse";
        $query .=") VALUES (";
        $query .=" '{$id_position}','{$nm_position}','{$level_user}','{$id_warehouse}'";
        $query .=")"; 
        if($db->query($query)){
          //sucess
          $session->msg('s',"Position Has Been Created! ");
          redirect('position.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Create Position!');
          redirect('position.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('position.php',false);
   }
 }

 $all_position = find_all_position_admin2($user['id_warehouse']);
?>
<!-- END NEW POSITION -->

<!-- UPDATE POSITION -->
<?php
  if(isset($_POST['update_position'])){

   $req_fields = array('nm_position','id_position');
   validate_fields($req_fields);
   if(empty($errors)){
        $level_user   = remove_junk($db->escape($_POST['level_user']));
        $nm_position  = remove_junk($db->escape($_POST['nm_position']));
        $id_position  = remove_junk($db->escape($_POST['id_position']));
        $id_warehouse = $user['id_warehouse'];
        $query  = "UPDATE position SET ";
        $query .= "nm_position='{$nm_position}',id_position='{$id_position}',id_warehouse='{$id_warehouse}',level_user='{$level_user}'";
        $query .= "WHERE id_position='{$id_position}'";
        $result = $db->query($query);
         if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"Position Has Been Updated! ");
          redirect('position.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated Position!');
          redirect('position.php', false);
        }
   } else {
     $session->msg("d", $errors);
    redirect('position.php', false);
   }
 }
?>
<!-- END UPDATE POSITION -->

<!-- DELETE POSITION -->
<?php
  if(isset($_POST['delete_position'])){
    $id_position = remove_junk($db->escape($_POST['id_position']));
    
    //validation connected foreign key
    $employer = find_all_idPosition($id_position);
    foreach ($employer as $data) {
      $id_position2 = $data['id_position'];  
    }
    if($id_position == $id_position2){
      $session->msg("d","The Field Connected To Other Key.");
      redirect('position.php');
    }

    //delete function
    $delete_id   = delete('id_position','position',$id_position);
    if($delete_id){
      $session->msg("s","Position has been deleted.");
      redirect('position.php');
    } else {
      $session->msg("d","Position deletion failed");
      redirect('position.php');
    }  
  }
?>
<!-- END DELETE POSITION -->

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
        <span>Position</span>
     </strong>
       <button title="Add New Position" type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addPosition"><span class="glyphicon glyphicon-plus"></span> Add New Positiion
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No. </th>
            <th class="text-center">Position Name</th>
            <th class="text-center">Level User</th>
            <th class="text-center" style="width: 150px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_position as $a_position): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_position['nm_position']))?></td>
           <td align="center"><?php echo remove_junk(ucwords($a_position['level_user']))?></td>
           <td class="text-center">
              <button data-target="#updatePosition<?php echo (int)$a_position['id_position'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                <i class="glyphicon glyphicon-pencil"></i>
              </button>
              <button data-target="#deletePosition<?php echo (int)$a_position['id_position'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Remove">
                <i class="glyphicon glyphicon-trash"></i>
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


<!-- Entry Modal -->
<div class="modal fade" id="addPosition" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-user"></span> Add New Position</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="position.php" class="clearfix">
        <div class="form-group">
          <label for="name" class="control-label">Name Position</label>
          <input type="name" class="form-control" placeholder="New Position" name="nm_position" required> 
        </div>
        <div class="form-group">
          <label for="name" class="control-label">Level User</label>
          <select class="form-control" name="level_user">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
          </select>  
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="add_position" title="Save" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
      </div>
    </form>
  </div>
</div>


  </div>
</div>

<!-- Update Modal -->
<?php foreach($all_position as $a_position): ?> 
  <div class="modal fade" id="updatePosition<?php echo (int)$a_position['id_position'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-user"></span> Update New Position</h4>
        </div>
        <div class="modal-body">
        <form method="post" action="position.php" class="clearfix">
          <div class="form-group">
            <label for="name" class="control-label">Name Position</label>
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_position['id_position'])); ?>" name="id_position">
            <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_position['nm_position'])); ?>" name="nm_position" required>
          </div>
          <div class="form-group">
          <label for="name" class="control-label">Level User</label>
          <select class="form-control" name="level_user">
            <?php if($all_position == null) { ?>
              <option value="">-</option>
                <?php } else { ?>
                   <option value="1">1</option>
                   <option value="2">2</option>
                   <option value="3">3</option>
                <?php } ?> 
          </select>  
        </div>     
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="update_position" title="Update" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>

<!-- Delete Modal -->
<?php foreach($all_position as $a_position): ?> 
  <div class="modal fade" id="deletePosition<?php echo (int)$a_position['id_position'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Confirm Delete</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete <b><u><?php echo remove_junk(ucwords($a_position['nm_position'])); ?></u></b> ?
        <form method="post" action="position.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_position['id_position'])); ?>" name="id_position">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_position" title="Delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
 
<?php include_once('layouts/footer.php'); ?>
