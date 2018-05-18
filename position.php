<?php
  $page_title = 'All Group';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>

<!-- ADD NEW POSITION -->
<?php
  if(isset($_POST['add_position'])){

   $req_fields = array('nm_position');
   validate_fields($req_fields);

   if(find_by_positionName($_POST['nm_position']) === false ){
     $session->msg('d','<b>Sorry!</b> Entered Position Name Already In Database!');
     redirect('position.php', false);
   }
   if(empty($errors)){
        $nm_position = remove_junk($db->escape($_POST['nm_position']));
        $id_position = 2;
        $query  = "INSERT INTO position (";
        $query .="id_position,nm_position";
        $query .=") VALUES (";
        $query .=" '{$id_position}','{$nm_position}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"Position has been creted! ");
          redirect('position.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create Position!');
          redirect('position.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('position.php',false);
   }
 }
 $all_position = find_all_position('position');
?>
<!-- END NEW POSITION -->

<!-- UPDATE POSITION -->
<?php
  if(isset($_POST['update_position'])){

   $req_fields = array('nm_position','id_position');
   validate_fields($req_fields);
   if(empty($errors)){
         $nm_position = remove_junk($db->escape($_POST['nm_position']));
         $id_position = remove_junk($db->escape($_POST['id_position']));
        $query  = "UPDATE position SET ";
        $query .= "nm_position='{$nm_position}',id_position='{$id_position}'";
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
       <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addPosition"><span class="glyphicon glyphicon-plus"></span> Add New Positiion
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No. </th>
            <th>Position Name</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_position as $a_position): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_position['nm_position']))?></td>
           <td class="text-center">
                <button data-target="#updatePosition<?php echo (int)$a_position['id_position'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <a href="delete_group.php<?php echo (int)$a_position['id_position'];?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Remove">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
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
        <h4 class="modal-title" id="exampleModalLabel">Entry New Position</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="position.php" class="clearfix">
        <div class="form-group">
          <label for="name" class="control-label">Name Position</label>
          <input type="name" class="form-control" name="nm_position">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="add_position" class="btn btn-primary">Save</button>
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
        <h4 class="modal-title" id="exampleModalLabel">Update New Position</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="position.php" class="clearfix">
        <div class="form-group">
          <label for="name" class="control-label">Name Position</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_position['id_position'])); ?>" name="id_position">
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_position['nm_position'])); ?>" name="nm_position">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_position" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>
</div>
<?php endforeach;?>
 
<?php include_once('layouts/footer.php'); ?>
