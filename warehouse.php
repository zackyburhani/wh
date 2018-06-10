<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  
  $user = current_user();
  $warehouse = find_warehouse_id($user['id_warehouse']);
  $get_product = get_item_condition($user['id_warehouse']);
  $get_package = get_package_condition($user['id_warehouse']);

  $page_title = "Warehouse ".$warehouse["nm_warehouse"];
?>
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
                <label class="control-label">Heavy Maximum /Kg</label>
              </div>
              <div class="col-md-4">
                <input type="text" value="<?php echo number_format($warehouse['heavy_max']) ?>" class="form-control" name="id_po" readonly>
              </div>

              <div class="col-md-2">
                <label class="control-label">Heavy Consumed</label>
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
          <span>Warehouse Condition</span>
       </strong>
      </div>
       <div class="panel-body">
         <table class="table table-bordered table-striped" id="tableWarehouse">
        <thead>
          <tr>
            <th class="text-center" style="width: 5%;">No.</th>
            <th class="text-center" style="width: 10%;">Item </th>
            <th class="text-center" style="width: 10%;">Stock</th>
            <th class="text-center" style="width: 10%;">Consumed Area / Kg</th>
            <th class="text-center" style="width: 5%;">Order</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($get_product as $product): ?>
          <tr>
           <td class="text-center"><?php echo $no++;?>.</td>
           <td><?php echo remove_junk(ucwords($product['nm_item']))?></td>
           <td class="text-center"><?php echo remove_junk($product['stock'])?></td>
           <td class="text-center"><?php echo $product['weight']*$product['stock']?></td>
           <td class="text-center"> 
            <form method="GET" action="po.php">
              <input type="hidden" name="id_item" value="<?php echo $product['id_item']; ?>">
              <button type="submit" title="Order" class="btn btn-md btn-success" name="add_item">
                  <i class="glyphicon glyphicon-new-window"></i>
              </button>
            </form>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
       </div>
      </div>
    </div>

<?php include_once('layouts/footer.php'); ?>

