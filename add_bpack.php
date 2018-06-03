<?php
  $page_title = 'Combine Package';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $user             = current_user();
  $id               = $user['id_warehouse'];
  $all_package      = find_all_bpack($id);
  $all_warehouse_id = find_warehouse_id($id);
  $all_product      = find_all_product($id);
?>

<!-- INSERT PACKAGE -->
<?php
 if(isset($_POST['add_bpack'])){
   $req_field   = array('qty','id_package','id_item');
   validate_fields($req_field);
   $id_bpack    = autonumber('id_bpack','bpack');
   $id          = $user['id_warehouse'];
   $id_item     = remove_junk($db->escape($_POST['id_item']));
   $id_package  = remove_junk($db->escape($_POST['id_package']));
   $qty         = remove_junk($db->escape($_POST['qty']));
   
   $package = find_weight_package($id_package);
   $item    = find_weight_item($id_item);

   //reduce area consumed 
   $consumed     = $all_warehouse_id['heavy_consumed'];
   $heavy_max    = $all_warehouse_id['heavy_max'];
   $id_warehouse = $all_warehouse_id['id_warehouse'];
   $count        = ($package['w_package']+$item['w_item'])*$qty; 
   $reduced      = $count+$consumed;

    if($reduced > $heavy_max){
      $session->msg('d',"You Do Not Have Enough Storage Space !");
      redirect('add_package.php', false);
    }

      $query  = "UPDATE warehouse SET ";
      $query .= "heavy_consumed='{$reduced}' ";
      $query .= "WHERE id_warehouse = '{$id_warehouse}'";


   if(empty($errors)){
      $sql  = "INSERT INTO bpack (id_bpack,id_package,id_item,qty,total)";
      $sql .= " VALUES ('{$id_bpack}','{$id_package}','{$id_item}','{$qty}','{$count}')";

      if($db->query($sql)){
        $db->query($query);
        $session->msg("s", "Successfully Added Package");
        redirect('add_bpack.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('add_bpack.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_bpack.php',false);
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
  $height      = remove_junk($db->escape($_POST['height']));
  $weight      = remove_junk($db->escape($_POST['weight']));
  $lenght      = remove_junk($db->escape($_POST['lenght']));
  $width       = remove_junk($db->escape($_POST['width']));
  $stock       = remove_junk($db->escape($_POST['stock']));
  $idpackage   = remove_junk($db->escape($_POST['idpackage']));
  
  $product_fetch    = find_package_fetch($idpackage);
  //reduce area consumed
  $stock_fetch      = $product_fetch['jml_stock'];
  $weight_fetch     = $product_fetch['weight'];
  $consumed         = $all_warehouse_id['heavy_consumed']; 
  $heavy_max        = $all_warehouse_id['heavy_max'];
  $id_warehouse     = $all_warehouse_id['id_warehouse']; 
  $count            = $consumed-($stock_fetch*$weight_fetch);
  $reduced          = $count+($weight*$stock);

  if($reduced > $heavy_max){
    $session->msg('d',"You Do Not Have Enough Storage Space !");
    redirect('add_package.php', false);
  }


  if(empty($errors)){
        $sql = "UPDATE package SET nm_package='{$packagename}',height='{$height}',weight='{$weight}',lenght='{$lenght}',width='{$width}',jml_stock='{$stock}'";
       $sql .= " WHERE id_package='{$idpackage}'";

       $query2  = "UPDATE warehouse SET ";
       $query2 .= "heavy_consumed='{$reduced}' ";
       $query2 .= "WHERE id_warehouse = '{$id_warehouse}'";
    
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
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

    if($reduced < 0){
      $session->msg("d","Can not Delete The Product.");
      redirect('add_package.php');
    }

    $query  = "UPDATE warehouse SET ";
    $query .= "heavy_consumed='{$reduced}'";
    $query .= " WHERE id_warehouse = '{$id_warehouse}'";

  if(empty($errors)){
        $sql = "DELETE FROM package WHERE id_package = '{$idpackage}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
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
        <span class="glyphicon glyphicon-th"></span>
        <span>PACKAGE</span>
     </strong>
       <button type="button" title="Add New Package" class="btn btn-info pull-right" data-toggle="modal" data-target="#addPackage"><span class="glyphicon glyphicon-plus"></span> Combine Package
        </button>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePackage">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 50px;">Name Package</th>
            <th class="text-center" style="width: 50px;">Name Item</th>
            <th class="text-center" style="width: 50px;">QTY</th>
            <th class="text-center" style="width: 50px;">Total</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_package as $a_package): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['nm_package']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['nm_item']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['qty']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['total']))?></td>
           <td class="text-center">
                <button data-target="#updatePackage<?php echo $a_package['id_package'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
                </button>
                <button data-target="#deletePackage<?php echo $a_package['id_package'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete">
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
      <form method="post" action="add_bpack.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Package</label>
          <select class="form-control" id="sub_category" name="id_package">
            <?php if($all_package == null) { ?>
              <option value="">-</option>
                <?php } else { ?>
                  <?php foreach($all_package as $row2){ ?>
                    <option class="<?php echo $row2['id_package']; ?>" value="<?php echo remove_junk($row2['id_package']); ?>"><?php echo remove_junk(ucwords($row2['nm_package'])); ?>
                    </option>
                  <?php } ?>
                <?php } ?>  
          </select>
        </div>

        <div class="form-group">
          <label class="control-label">Item</label>
          <select class="form-control" id="sub_category" name="id_item">
            <?php if($all_product == null) { ?>
              <option value="">-</option>
                <?php } else { ?>
                  <?php foreach($all_product as $row3){ ?>
                    <option class="<?php echo $row3['id_item']; ?>" value="<?php echo remove_junk($row3['id_item']); ?>"><?php echo remove_junk(ucwords($row3['nm_item'])); ?>
                    </option>
                  <?php } ?>
                <?php } ?>  
          </select>
        </div>
        
        <div class="form-group">
          <label class="control-label">QTY</label>
          <input type="text" class="form-control" name="qty">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" title="Save" name="add_bpack" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
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
