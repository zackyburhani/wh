<?php
  $page_title = 'Add Package';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $user             = current_user();
  $id               = $user['id_warehouse'];
  $all_package      = find_all_package('package',$id);
  $all_warehouse_id = find_warehouse_id($user['id_warehouse']);

  //check qty package and item
  $get_product     = get_product('item',$user['id_warehouse']);
  $get_package     = find_all_package('package',$user['id_warehouse']);
  
  if($get_package == null && $get_package == null){
    $query2  = "UPDATE warehouse SET ";
    $query2 .= "heavy_consumed = 0.00";
    $query2 .= " WHERE id_warehouse = '{$id}'";
    $db->query($query2);
  }

?>

<!-- INSERT PACKAGE -->
<?php
  if(isset($_POST['add_package'])){
    $req_field    = array('packagename');
    validate_fields($req_field);
    $id_package   = autonumber('id_package','package');
    $id           = $user['id_warehouse'];
    $packagename  = remove_junk($db->escape($_POST['packagename']));
    $height       = remove_junk($db->escape($_POST['height']));
    $weight       = remove_junk($db->escape($_POST['weight']));
    $lenght       = remove_junk($db->escape($_POST['lenght']));
    $diameter     = remove_junk($db->escape($_POST['diameter']));
    $width        = remove_junk($db->escape($_POST['width']));
    $stock        = remove_junk($db->escape($_POST['stock']));
    $safety_stock = remove_junk($db->escape($_POST['safety_stock']));
   
    //convert
    $convert_weight = remove_junk($db->escape($_POST['convert_weight']));

    //convert weight
    if($convert_weight == "weight_kilograms"){
      $weight = $weight;
    } else if($convert_weight == "weight_pounds") {
      $weight = $weight/2.2046;
    } else if($convert_weight == "weight_ons"){
      $weight = $weight/35.274;
    } else if($convert_weight == "weight_grams"){
      $weight = $weight/1000;
    }

   //reduce area consumed 
   $consumed     = $all_warehouse_id['heavy_consumed'];
   $heavy_max    = $all_warehouse_id['heavy_max'];
   $id_warehouse = $all_warehouse_id['id_warehouse']; 
   $reduced      = ($weight*$stock)+$consumed;

    // if($reduced > $heavy_max){
    //   $session->msg('d',"You Do Not Have Enough Storage Space !");
    //   redirect('add_package.php', false);
    // }

      $query  = "UPDATE warehouse SET ";
      $query .= "heavy_consumed='{$reduced}' ";
      $query .= "WHERE id_warehouse = '{$id_warehouse}'";


   if(empty($errors)){
      $sql  = "INSERT INTO package (id_package,nm_package,height,weight,lenght,diameter,width,jml_stock,id_warehouse,safety_stock)";
      $sql .= " VALUES ('{$id_package}','{$packagename}','{$height}','{$weight}','{$lenght}','{$diameter}','{$width}','{$stock}','{$id}','{$safety_stock}')";

      $id_wh = $user['id_warehouse'];

      $getAllPackageName = "SELECT nm_package FROM package where nm_package = '$packagename' and package.id_warehouse = '$id_wh'";
      $ada=$db->query($getAllPackageName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Package Already Exist");
        redirect('add_package.php',false);
      } else {
          if($db->query($sql)){
            $db->query($query);
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
  $req_field = array('packagename','stock');
  validate_fields($req_field);
  $packagename  = remove_junk($db->escape($_POST['packagename']));
  $height       = remove_junk($db->escape($_POST['height']));
  $weight       = remove_junk($db->escape($_POST['weight']));
  $lenght       = remove_junk($db->escape($_POST['lenght']));
  $diameter     = remove_junk($db->escape($_POST['diameter']));
  $width        = remove_junk($db->escape($_POST['width']));
  $stock        = remove_junk($db->escape($_POST['stock']));
  $idpackage    = remove_junk($db->escape($_POST['idpackage']));
  $safety_stock = remove_junk($db->escape($_POST['safety_stock']));
  
  //convert
  $convert_weight   = remove_junk($db->escape($_POST['convert_weight']));

  //convert weight
  if($convert_weight == "weight_kilograms"){
    $weight = $weight;
  } else if($convert_weight == "weight_pounds") {
    $weight = $weight/2.2046;
  } else if($convert_weight == "weight_ons"){
    $weight = $weight/35.274;
  } else if($convert_weight == "weight_grams"){
    $weight = $weight/1000;
  }

  $product_fetch    = find_package_fetch($idpackage);
  //reduce area consumed
  $stock_fetch      = $product_fetch['jml_stock'];
  $weight_fetch     = $product_fetch['weight'];
  $consumed         = $all_warehouse_id['heavy_consumed']; 
  $heavy_max        = $all_warehouse_id['heavy_max'];
  $id_warehouse     = $all_warehouse_id['id_warehouse']; 
  $count            = $consumed-($stock_fetch*$weight_fetch);
  $reduced          = $count+($weight*$stock);

  // if($reduced > $heavy_max){
  //   $session->msg('d',"You Do Not Have Enough Storage Space !");
  //   redirect('add_package.php', false);
  // }

  if(empty($errors)){
        $sql = "UPDATE package SET nm_package='{$packagename}',height='{$height}',weight='{$weight}',lenght='{$lenght}',Diameter='{$diameter}',width='{$width}',jml_stock='{$stock}',safety_stock='{$safety_stock}'";
       $sql .= " WHERE id_package='{$idpackage}'";

       $query2  = "UPDATE warehouse SET ";
       $query2 .= "heavy_consumed='{$reduced}' ";
       $query2 .= "WHERE id_warehouse = '{$id_warehouse}'";
    
     $result = $db->query($sql);
     if($result) {
       $db->query($query2);
       $session->msg("s", "Successfully Updated Package");
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
  $stock     = remove_junk($db->escape($_POST['stock']));
  $weight    = remove_junk($db->escape($_POST['weight']));

  //validation connected foreign key
    $package = find_all_id('item',$idpackage,'id_package');
    foreach ($package as $data) {
      $idpackage2 = $data['id_package'];
    }
    if($idpackage == $idpackage2){
      $session->msg("d","The Field Connected To Other Data.");
      redirect('add_package.php');
    }

  //reduce area consumed
    $consumed     = $all_warehouse_id['heavy_consumed']; 
    $heavy_max    = $all_warehouse_id['heavy_max'];
    $id_warehouse = $all_warehouse_id['id_warehouse']; 
    $reduced      = $consumed-($weight*$stock);

    // if($reduced < 0){
    //   $session->msg("d","Can not Delete The Product.");
    //   redirect('add_package.php');
    // }

    $query  = "UPDATE warehouse SET ";
    $query .= "heavy_consumed='{$reduced}'";
    $query .= " WHERE id_warehouse = '{$id_warehouse}'";

  if(empty($errors)){
        $sql = "DELETE FROM package WHERE id_package = '{$idpackage}'";
     $result = $db->query($sql);
     if($result) {
       $db->query($query);
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
        <i class="fa fa-archive"></i>
        <span>PACKAGE</span>
     </strong>
     <?php
      if ($user['level_user']==0 || $user['level_user']==1) { ?>
       <button type="button" title="Add New Package" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addPackage"><span class="glyphicon glyphicon-plus"></span> Add New Package
       </button>
      <?php } ?>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePackage">
        <thead>
          <tr>
            <th class="text-center" style="width: 10px;">No</th>
            <th class="text-center" style="width: 50px;">Package Name</th>
            <th class="text-center" style="width: 50px;">Height / Cm</th>
            <th class="text-center" style="width: 50px;">Width / Cm</th>
            <th class="text-center" style="width: 50px;">Lenght / Cm</th>
            <th class="text-center" style="width: 50px;">Diameter / Cm</th>
            <th class="text-center" style="width: 50px;">Weight / Kg</th>
            <th class="text-center" style="width: 50px;">Stock / Unit</th>
            <th class="text-center" style="width: 30px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($all_package as $a_package): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['nm_package']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['height']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['width']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['lenght']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['diameter']))?></td>
           <td class="text-center"><?php echo remove_junk(round($a_package['weight'],4))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['jml_stock']))?></td>
           <td class="text-center">
                <button data-target="#updatePackage<?php echo $a_package['id_package'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
              <?php
                if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) 
                  { ?>
                <button data-target="#deletePackage<?php echo $a_package['id_package'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete">
                  <i class="glyphicon glyphicon-trash"></i>
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

<!-- MODAL ADD NEW PACKAGE -->
<div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive"></i> Entry New Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">
        
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Package</label>
              <input type="text" class="form-control" name="packagename" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Height / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="height">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Width / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="width">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Lenght / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="lenght">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Diameter / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="diameter">
          </div>  
        </div>
      </div>
      
        <hr>

        <div class="form-group">
          <div class="row">
            <div class="col-md-6">
              <label class="control-label">Weight</label>    
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="weight" required>
            </div>
            <div class="col-md-6">
              <label for="name" class="control-label">Convert Weight</label>
                <select class="form-control" name="convert_weight">
                  <option value="weight_kilograms">Kilograms</option>
                  <option value="weight_pounds">Pounds</option>
                  <option value="weight_ons">Ons</option>
                  <option value="weight_grams">Grams</option>
              </select>
            </div>
          </div>
        </div>  

        <hr>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Stock / Unit</label>
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="stock" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Safety Stock</label>
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="safety_stock" required>
            </div>
          </div>
  
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" title="Save" name="add_package" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
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
<div class="modal fade" id="updatePackage<?php echo $a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive"></i> Update Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Package</label>
              <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_package['id_package'])); ?>" name="idpackage">
              <input type="text" class="form-control" value="<?php echo remove_junk(ucwords($a_package['nm_package'])); ?>" name="packagename" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Height / cm</label>
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['height'])); ?>" name="height">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Width / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['width'])); ?>" name="width">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Lenght / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" name="lenght" value="<?php echo remove_junk(ucwords($a_package['lenght'])); ?>">
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Diameter / cm</label>
            <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['diameter'])); ?>" name="diameter">
          </div>  
        </div>
      </div>

        <hr>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Weight</label>
              <input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" name="weight" value="<?php echo remove_junk(round($a_package['weight'],4)); ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <label for="name" class="control-label">Convert Weight</label>
              <select class="form-control" name="convert_weight">
                <option value="weight_kilograms">Kilograms</option>
                <option value="weight_pounds">Pounds</option>
                <option value="weight_ons">Ons</option>
                <option value="weight_grams">Grams</option>
            </select>
          </div>
        </div>

        <hr>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Stock / Unit</label>
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['jml_stock'])); ?>" name="stock" required>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="control-label">Safety Stock</label>
              <input type="number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['safety_stock'])); ?>" name="safety_stock" required>
            </div>
          </div>
  
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" title="Update" name="update_package" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
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
<div class="modal fade" id="deletePackage<?php echo $a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Delete Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_package.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_package['id_package'])); ?>" name="idpackage">
        <p>Are You Sure to Delete Package <b><?php echo remove_junk(ucwords($a_package['nm_package'])); ?></b>?</p>  
        <input type="hidden" name="stock" value="<?php echo $a_package['jml_stock']; ?>">
        <input type="hidden" name="weight" value="<?php echo $a_package['weight']; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="delete_package" title="Delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE PACKAGE -->

<?php include_once('layouts/footer.php'); ?>
