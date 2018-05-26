<?php
  $page_title = 'Add Package';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_package = find_all1('package')
?>

<!-- INSERT PACKAGE -->
<?php
 if(isset($_POST['add_package'])){
   $req_field = array('packagename');
   validate_fields($req_field);
   $packagename = remove_junk($db->escape($_POST['packagename']));
   $height = remove_junk($db->escape($_POST['height']));
   $weight = remove_junk($db->escape($_POST['weight']));
   $lenght = remove_junk($db->escape($_POST['lenght']));
   $width = remove_junk($db->escape($_POST['width']));
   $stock = remove_junk($db->escape($_POST['stock']));
   if(empty($errors)){
      $sql  = "INSERT INTO package (nm_package,height,weight,lenght,width,jml_stock)";
      $sql .= " VALUES ('{$packagename}','{$height}','{$weight}','{$lenght}','{$width}','{$stock}')";

      $getAllPackageName = "SELECT nm_package FROM package where nm_package = '$packagename'";
      $ada=$db->query($getAllPackageName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Package Already Exist");
        redirect('add_package.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Package");
          redirect('add_package.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_package.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_package.php',false);
   }
 }
?>
<!-- END INSERT PACKAGE -->

<!-- UPDATE PACKAGE -->
<?php
if(isset($_POST['update_package'])){
  $req_field = array('packagename','height','weight','lenght','width','stock','idpackage');
  validate_fields($req_field);
  $packagename = remove_junk($db->escape($_POST['packagename']));
  $height = remove_junk($db->escape($_POST['height']));
  $weight = remove_junk($db->escape($_POST['weight']));
  $lenght = remove_junk($db->escape($_POST['lenght']));
  $width = remove_junk($db->escape($_POST['width']));
  $stock = remove_junk($db->escape($_POST['stock']));
  $idpackage = remove_junk($db->escape($_POST['idpackage']));
  if(empty($errors)){
        $sql = "UPDATE package SET nm_package='{$packagename}',height='{$height}',weight='{$weight}',lenght='{$lenght}',width='{$width}',jml_stock='{$stock}'";
       $sql .= " WHERE id_package='{$idpackage}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Package");
       redirect('add_package.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_package.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_package.php',false);
  }
}
?>
<!-- END UPDATE PACKAGE -->

<!-- DELETE PACKAGE -->
<?php
  if(isset($_POST['delete_package'])){
  $req_field = array('idpackage');
  validate_fields($req_field);
  $idpackage = remove_junk($db->escape($_POST['idpackage']));
  if(empty($errors)){
        $sql = "DELETE FROM package WHERE id_package='{$idpackage}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully delete Package");
       redirect('add_package.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Delete");
       redirect('add_package.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_package.php',false);
  }
}
?>
<!-- END DELETE PACKAGE -->

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
        <span>PACKAGE</span>
     </strong>
       <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addPackage"><span class="glyphicon glyphicon-plus"></span> Add New Package
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 50px;">Package Name</th>
            <th class="text-center" style="width: 50px;">Height</th>
            <th class="text-center" style="width: 50px;">Weight</th>
            <th class="text-center" style="width: 50px;">Lenght</th>
            <th class="text-center" style="width: 50px;">Width</th>
            <th class="text-center" style="width: 50px;">Stock</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_package as $a_package): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['nm_package']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['height']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['weight']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['lenght']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['width']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['jml_stock']))?></td>
           <td class="text-center">
                <button data-target="#updatePackage<?php echo (int)$a_package['id_package'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button data-target="#deletePackage<?php echo (int)$a_package['id_package'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Hapus">
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

<!-- MODAL ADD NEW PACKAGE -->
<div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Entry New Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Package</label>
          <input type="text" class="form-control" name="packagename">
        </div>
        <div class="form-group">
          <label class="control-label">Height</label>
          <input type="number" class="form-control" name="height">
        </div>  
        <div class="form-group">
          <label class="control-label">Weight</label>
          <input type="number" class="form-control" name="weight">
        </div>  
        <div class="form-group">
          <label class="control-label">Lenght</label>
          <input type="number" class="form-control" name="lenght">
        </div>
        <div class="form-group">
          <label class="control-label">Width</label>
          <input type="number" class="form-control" name="width">
        </div>  
        <div class="form-group">
          <label class="control-label">Stock</label>
          <input type="number" class="form-control" name="stock">
        </div>      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="add_package" class="btn btn-primary">Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
  </div>
</div>
<!-- END MODAL ADD NEW PACKAGE -->

<!-- MODAL UPDATE PACKAGE -->
<?php foreach($all_package as $a_package): ?> 
<div class="modal fade" id="updatePackage<?php echo (int)$a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Package</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_package['id_package'])); ?>" name="idpackage">
          <input type="text" class="form-control" value="<?php echo remove_junk(ucwords($a_package['nm_package'])); ?>" name="packagename">
        </div>
        <div class="form-group">
          <label class="control-label">Height</label>
          <input type="number" class="form-control" value="<?php echo remove_junk(ucwords($a_package['height'])); ?>" name="height">
        </div>  
        <div class="form-group">
          <label class="control-label">Weight</label>
          <input type="number" class="form-control" name="weight" value="<?php echo remove_junk(ucwords($a_package['weight'])); ?>">
        </div>  
        <div class="form-group">
          <label class="control-label">Lenght</label>
          <input type="number" class="form-control" name="lenght" value="<?php echo remove_junk(ucwords($a_package['lenght'])); ?>">
        </div>
        <div class="form-group">
          <label class="control-label">Width</label>
          <input type="number" class="form-control" value="<?php echo remove_junk(ucwords($a_package['width'])); ?>" name="width">
        </div>  
        <div class="form-group">
          <label class="control-label">Stock</label>
          <input type="number" class="form-control" value="<?php echo remove_junk(ucwords($a_package['jml_stock'])); ?>" name="stock">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_package" class="btn btn-primary">Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL UPDATE PACKAGE -->

<!-- MODAL DELETE PACKAGE -->
<?php foreach($all_package as $a_package): ?>
<div class="modal fade" id="deletePackage<?php echo (int)$a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_package['id_package'])); ?>" name="idpackage">
        <p>Are You Sure to Delete Package <b><?php echo remove_junk(ucwords($a_package['nm_package'])); ?></b>?</p>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="delete_package" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE PACKAGE -->

<?php include_once('layouts/footer.php'); ?>
