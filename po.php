<?php
  $page_title = 'Purchase Order Transaction';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $all_categories = find_all1('warehouse')
?>
<?php
 if(isset($_POST['add_warehouse'])){
   $req_field = array('warehousename');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['warehousename']));
   $country = remove_junk($db->escape($_POST['country']));
   $address = remove_junk($db->escape($_POST['address']));
   $status = remove_junk($db->escape($_POST['status']));
   $heavymax = remove_junk($db->escape($_POST['heavymax']));
   $consumed = remove_junk($db->escape($_POST['consumed']));
   if(empty($errors)){
      $sql  = "INSERT INTO warehouse (nm_warehouse,country,address,status,heavy_max,heavy_consumed)";
      $sql .= " VALUES ('{$cat_name}','{$country}','{$address}','{$status}','{$heavymax}','{$consumed}')";

      $getAllWarehouseName = "SELECT nm_warehouse FROM warehouse where nm_warehouse = '$cat_name'";
      $ada=$db->query($getAllWarehouseName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Warehouse Is Exist");
        redirect('add_warehouse.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Warehouse");
          redirect('add_warehouse.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_warehouse.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_warehouse.php',false);
   }
 }
?>

<!-- UPDATE WAREHOUSE -->
<?php
if(isset($_POST['update_warehouse'])){
  $req_field = array('warehousename','country','address','status','heavymax','consumed','idwarehouse');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['warehousename']));
  $country = remove_junk($db->escape($_POST['country']));
  $address = remove_junk($db->escape($_POST['address']));
  $status = remove_junk($db->escape($_POST['status']));
  $heavymax = remove_junk($db->escape($_POST['heavymax']));
  $consumed = remove_junk($db->escape($_POST['consumed']));
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));
  if(empty($errors)){
        $sql = "UPDATE warehouse SET nm_warehouse='{$cat_name}',country='{$country}',address='{$address}',status='{$status}',heavy_max='{$heavymax}',heavy_consumed='{$consumed}'";
       $sql .= " WHERE id_warehouse='{$idwarehouse}'";
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
<!-- END UPDATE WAREHOUSE -->

<?php
  if(isset($_POST['delete_warehouse'])){
  $req_field = array('idwarehouse');
  validate_fields($req_field);
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));
  if(empty($errors)){
        $sql = "DELETE FROM warehouse WHERE id_warehouse='{$idwarehouse}'";
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

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>PURCHASE ORDER</span>
     </strong>
    </div>
     <div class="panel-body">
      <form method="post" action="">
        <div class="form-group">
          <label class="control-label">No. PO</label>
          <input type="text" class="form-control" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="text" class="form-control" name="country">
        </div> 
      </form>
     </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
    </div>
     <div class="panel-body">
      <form method="post" action="">
        <div class="form-group">
          <label class="control-label">PO Date</label>
          <input type="date" class="form-control" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Send Date</label>
          <input type="date" class="form-control" name="country">
        </div>
        <div class="form-group">
          <label class="control-label">For Warehouse</label>
          <input type="text" class="form-control" name="country">
        </div>   
      </form>
     </div>
    </div>
  </div>
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>WAREHOUSE</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 50px;">Warehouse</th>
            <th class="text-center" style="width: 50px;">Country</th>
            <th class="text-center" style="width: 50px;">Address</th>
            <th class="text-center" style="width: 50px;">Status</th>
            <th class="text-center" style="width: 50px;">Heavy Max</th>
            <th class="text-center" style="width: 50px;">Area Consumed</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_categories as $a_warehouse): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['nm_warehouse']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['country']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['address']))?></td>
           <td class="text-center">
            <?php if($a_warehouse['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Produce"; ?></span>
            <?php else: ?>
            <span class="label label-danger"><?php echo "Not Produce"; ?></span>
            <?php endif;?>
           </td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['heavy_max']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_warehouse['heavy_consumed']))?></td>
           <td class="text-center">
                <button data-target="#updateWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deleteWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" title="Delete"><i class="glyphicon glyphicon-trash"></i>
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
<div class="modal fade" id="addWarehouse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Entry New Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="name" pattern="[A-Za-z]+" class="form-control" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Country</label>
          <input type="name" class="form-control" name="country">
        </div>  
        <div class="form-group">
          <label class="control-label">Address</label>
          <textarea type="name" class="form-control" name="address"></textarea>
        </div>  
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
              <option value="1">Produce</option>
              <option value="0">Not Produce</option>
            </select>
        </div>
        <div class="form-group">
          <label class="control-label">Heavy Max</label>
          <input type="name" class="form-control" name="heavymax">
        </div>  
        <div class="form-group">
          <label class="control-label">Area Consumed</label>
          <input type="name" class="form-control" name="consumed">
        </div>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="add_warehouse" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>


  </div>
</div>

<!-- Update Modal -->
<?php foreach($all_categories as $a_warehouse): ?> 
<div class="modal fade" id="updateWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Data Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['nm_warehouse'])); ?>" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Country</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['country'])); ?>" name="country">
        </div>  
        <div class="form-group">
          <label class="control-label">Address</label>
          <textarea type="name" class="form-control" name="address"><?php echo remove_junk(ucwords($a_warehouse['address'])); ?>"</textarea>
        </div>  
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
                <option <?php if($a_warehouse['status'] === '1') echo 'selected="selected"';?> value="1"> Produce </option>
                <option <?php if($a_warehouse['status'] === '0') echo 'selected="selected"';?> value="0"> Not Produce</option>
              </select>
        </div>
        <div class="form-group">
          <label class="control-label">Heavy Max</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['heavy_max'])); ?>" name="heavymax">
        </div>  
        <div class="form-group">
          <label class="control-label">Area Consumed</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['heavy_consumed'])); ?>" name="consumed">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_warehouse" class="btn btn-primary">Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>

<!-- Modal -->
<?php foreach($all_categories as $a_warehouse): ?> 
<div class="modal fade" id="deleteWarehouse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
        <p>Are you sure to delete this warehouse?</p>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="delete_warehouse" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>

<?php include_once('layouts/footer.php'); ?>
