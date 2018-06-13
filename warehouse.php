<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $user = current_user();
  $warehouse = find_warehouse_id($user['id_warehouse']);
  $get_product = get_item_condition($user['id_warehouse']);
  $get_package = get_package_condition($user['id_warehouse']);
  $all_warehouse_id = find_warehouse_id($user['id_warehouse']);

  $page_title = "Warehouse ".$warehouse["nm_warehouse"];
?>


<!-- UPDATE PACKAGE -->
<?php
if(isset($_POST['update_package'])){
  $req_field = array('stock');
  validate_fields($req_field);
  $stock        = remove_junk($db->escape($_POST['stock']));
  $idpackage    = remove_junk($db->escape($_POST['idpackage']));
  $safety_stock = remove_junk($db->escape($_POST['safety_stock']));
  $weight       = remove_junk($db->escape($_POST['weight']));
  

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
        $sql = "UPDATE package SET weight='{$weight}',jml_stock='{$stock}'";
       $sql .= " WHERE id_package='{$idpackage}'";

       $query2  = "UPDATE warehouse SET ";
       $query2 .= "heavy_consumed='{$reduced}' ";
       $query2 .= "WHERE id_warehouse = '{$id_warehouse}'";
    
     $result = $db->query($sql);
     if($result) {
       $db->query($query2);
       $session->msg("s", "Successfully Updated Stock Package");
       redirect('warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('warehouse.php',false);
  }
}
?>
<!-- END UPDATE PACKAGE -->


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
            <span>DETAIL Warehouse</span>
         </strong>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">ID Warehouse</label>
              </div>
              <div class="col-md-4">
                <input type="text" value="<?php echo $warehouse['id_warehouse'] ?>" class="form-control" name="id_po" readonly>
              </div>

              <div class="col-md-2">
                <label class="control-label">Status</label>
              </div>
              <div class="col-md-4">
                <?php if($warehouse['status'] == 0) { ?>
                  <input type="text" value="Not Produce" class="form-control" name="id_po" readonly>
                <?php } else { ?>
                  <input type="text" value="Produce" class="form-control" name="id_po" readonly>
                <?php } ?>
              </div>

            </div>
          </div>

          <div class="form-group">
            <div class="row">
              <div class="col-md-2">
                <label class="control-label">Heavy Maximum / Kg</label>
              </div>
              <div class="col-md-4">
                <input type="text" value="<?php echo number_format($warehouse['heavy_max']) ?>" class="form-control" name="id_po" readonly>
              </div>

              <div class="col-md-2">
                <label class="control-label">Heavy Consumed / Kg</label>
              </div>
              <div class="col-md-4">
                <input type="text" value="<?php echo $warehouse['heavy_consumed'] ?>" class="form-control" name="id_po" readonly>
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Item Stock Out</span>
       </strong>
      </div>
       <div class="panel-body">
         <table class="table table-bordered table-striped" id="tableWarehouse">
        <thead>
          <tr>
            <th class="text-center" style="width: 5%;">No.</th>
            <th class="text-center" style="width: 10%;">Item </th>
            <th class="text-center" style="width: 10%;">Stock</th>
            <th class="text-center" style="width: 10%;">Safety Stock / Kg</th>
            <th class="text-center" style="width: 10%;">Consumed Area / Kg</th>
            <?php if($warehouse['status'] != 0) { ?>
            <th class="text-center" style="width: 5%;">Produce</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($get_product as $product): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td><?php echo remove_junk(ucwords($product['nm_item']))?></td>
           <td class="text-center"><?php echo remove_junk($product['stock'])?></td>
           <td class="text-center"><?php echo remove_junk($product['safety_stock'])?></td>
           <td class="text-center"><?php echo $product['weight']*$product['stock']?></td>
           <?php if($warehouse['status'] != 0) { ?>
           <td class="text-center"><a class="btn btn-primary" href="product.php"><i class="fa fa-gears"></i></a></td>
           <?php } ?>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
       </div>
      </div>
    </div>

    <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Package Stock Out</span>
       </strong>
      </div>
       <div class="panel-body">
         <table class="table table-bordered table-striped" id="tableUser">
        <thead>
          <tr>
            <th class="text-center" style="width: 5%;">No.</th>
            <th class="text-center" style="width: 10%;">Package </th>
            <th class="text-center" style="width: 10%;">Stock</th>
            <th class="text-center" style="width: 10%;">Safety Stock</th>
            <th class="text-center" style="width: 10%;">Consumed Area / Kg</th>
            <th class="text-center" style="width: 5%;">Update</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($get_package as $product): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td><?php echo remove_junk(ucwords($product['nm_package']))?></td>
           <td class="text-center"><?php echo remove_junk($product['jml_stock'])?></td>
           <td class="text-center"><?php echo remove_junk($product['safety_stock'])?></td>
           <td class="text-center"><?php echo $product['weight']*$product['jml_stock']?></td>
           <td class="text-center">
            <button data-target="#updatePackage<?php echo $product['id_package'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
              <i class="glyphicon glyphicon-pencil"></i>
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

<!-- MODAL UPDATE PACKAGE -->
<?php foreach($get_package as $a_package): ?> 
<div class="modal fade" id="updatePackage<?php echo $a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive"></i> Update Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="warehouse.php" class="clearfix">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="control-label">Stock / Unit</label>
              <input type="number" min="0" class="form-control" value="<?php echo remove_junk(ucwords($a_package['jml_stock'])); ?>" name="stock">
              <input type="hidden" value="<?php echo remove_junk(ucwords($a_package['id_package'])); ?>" name="idpackage">
               <input type="hidden" value="<?php echo remove_junk(ucwords($a_package['weight'])); ?>" name="weight">
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




<?php include_once('layouts/footer.php'); ?>

