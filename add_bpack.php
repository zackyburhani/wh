<?php
  $page_title = 'Combine Package';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $user             = current_user();
  $id               = $user['id_warehouse'];
  $all_package      = find_all_bpack($id);
  $all_package2     = find_all_package('package',$id);
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
if(isset($_POST['update_bpack'])){
  $req_field   = array('qty','id_package','id_item');
  validate_fields($req_field);
  $id_bpack    = remove_junk($db->escape($_POST['id_bpack']));
  $id_item     = remove_junk($db->escape($_POST['id_item']));
  $id_package  = remove_junk($db->escape($_POST['id_package']));
  $qty         = remove_junk($db->escape($_POST['qty']));
  
  $bpack_fetch = find_bpack_fetch($id_bpack);
  $package     = find_weight_package($id_package);
  $item        = find_weight_item($id_item);
  //reduce area consumed
  $total_fetch      = $bpack_fetch['total'];
  $consumed         = $all_warehouse_id['heavy_consumed']; 
  $heavy_max        = $all_warehouse_id['heavy_max'];
  $id_warehouse     = $all_warehouse_id['id_warehouse']; 
  $min              = $consumed-($total_fetch);
  $count            = ($package['w_package']+$item['w_item'])*$qty;
  $reduced          = $min+$count;

  if($reduced > $heavy_max){
    $session->msg('d',"You Do Not Have Enough Storage Space !");
    redirect('add_bpack.php', false);
  }

  if(empty($errors)){
        $sql = "UPDATE bpack SET id_package='{$id_package}',id_item='{$id_item}',qty='{$qty}',total='{$count}'";
       $sql .= " WHERE id_bpack='{$id_bpack}'";

       $query2  = "UPDATE warehouse SET ";
       $query2 .= "heavy_consumed = '{$reduced}' ";
       $query2 .= "WHERE id_warehouse = '{$id_warehouse}'";
    
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $db->query($query2);
       $session->msg("s", "Successfully Updated Package");
       redirect('add_bpack.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_bpack.php',false);
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
  if(isset($_POST['delete_bpack'])){
  $req_field = array('id_bpack');
  validate_fields($req_field);
  $id_bpack  = remove_junk($db->escape($_POST['id_bpack']));
  $qty       = remove_junk($db->escape($_POST['qty']));
  $total     = remove_junk($db->escape($_POST['total']));
  $id_item   = remove_junk($db->escape($_POST['id_item']));

  //reduce area consumed
    $consumed     = $all_warehouse_id['heavy_consumed']; 
    $heavy_max    = $all_warehouse_id['heavy_max'];
    $id_warehouse = $all_warehouse_id['id_warehouse']; 
    $reduced      = $consumed-($total);

    if($reduced < 0){
      $session->msg("d","Could Not Delete The Product.");
      redirect('add_bpack.php');
    }

    $query  = "UPDATE warehouse SET ";
    $query .= "heavy_consumed='{$reduced}'";
    $query .= " WHERE id_warehouse = '{$id_warehouse}'";

  if(empty($errors)){
        $sql = "DELETE FROM bpack WHERE id_bpack = '{$id_bpack}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       //delete item
       $delete_id2   = delete('id_item','item',$id_item);
       $db->query($query);
       $session->msg("s", "Successfully delete Package");
       redirect('add_bpack.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Delete");
       redirect('add_bpack.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_bpack.php',false);
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
        <!-- <button type="button" title="Combine Package" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addPackage"><span class="glyphicon glyphicon-plus"></span> Combine Package
        </button> -->
      <?php } ?>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePackage">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No</th>
            <th class="text-center" style="width: 50px;">ID Package</th>
            <th class="text-center" style="width: 50px;">ID Item</th>
            <th class="text-center" style="width: 50px;">QTY</th>
            <th class="text-center" style="width: 50px;">Total Weight</th>
            <!-- <th class="text-center" style="width: 100px;">Actions</th> -->
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($all_package as $a_package): ?>
          <tr>
           <td class="text-center"><?php echo $no++."."?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['id_package']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_package['id_item']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords(number_format($a_package['qty'])))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords(number_format($a_package['total'])))?></td>
           
    <!--        <?php
                if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) 
                  { ?>
           <td class="text-center">
              <button data-target="#deletebpack<?php echo $a_package['id_bpack'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete">
                <i class="glyphicon glyphicon-trash"></i>
              </button>
           </td>
           <?php } ?> -->
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
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive"></i> Entry New Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_bpack.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Package</label>
          <select class="form-control" id="sub_category" name="id_package">
            <?php if($all_package2 == null) { ?>
              <option value="">-</option>
                <?php } else { ?>
                  <?php foreach($all_package2 as $row2){ ?>
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
          <input type="number" min="1" class="form-control" name="qty">
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


<!-- MODAL ADD UPDATE PACKAGE -->
<?php foreach($all_package as $a_package): ?>
<div class="modal fade" id="updatebpack<?php echo $a_package['id_package'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-archive"></i> Update Package</h4>
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
                    <option class="<?php echo $row2['id_package']; ?>" value="<?php echo remove_junk($row2['id_package']); ?>" <?php if($row2['id_package'] === $a_package['id_package']): echo "selected"; endif; ?> ><?php echo remove_junk(ucwords($row2['nm_package'])); ?>
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
                    <option class="<?php echo $row3['id_item']; ?>" <?php if($row3['id_item'] === $a_package['id_item']): echo "selected"; endif; ?> value="<?php echo remove_junk($row3['id_item']); ?>"><?php echo remove_junk(ucwords($row3['nm_item'])); ?>
                    </option>
                  <?php } ?>
                <?php } ?>  
          </select>
        </div>
        
        <div class="form-group">
          <label class="control-label">QTY</label>
          <input type="number" min="1" value="<?php echo $a_package['qty'] ?>" class="form-control" name="qty">
          <input type="hidden" value="<?php echo $a_package['id_bpack'] ?>" name="id_bpack">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" title="Save" name="update_bpack" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
      </div>
    </form>
    </div>
  </div>
</div>
  </div>
</div>
<?php endforeach;?>
<!-- END MODAL UPDATE PACKAGE -->

<!-- MODAL DELETE PACKAGE -->
<?php foreach($all_package as $a_package): ?>
<div class="modal fade" id="deletebpack<?php echo $a_package['id_bpack'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Delete Package</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_bpack.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_package['id_bpack'])); ?>" name="id_bpack">
        <p>Are You Sure to Delete Package ?</p>  
        <input type="hidden" name="total" value="<?php echo $a_package['total']; ?>">
        <input type="hidden" name="qty" value="<?php echo $a_package['qty']; ?>">
        <input type="hidden" name="id_item" value="<?php echo $a_package['id_item']; ?>">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
        <button type="submit" name="delete_bpack" title="Delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>
<!-- END MODAL DELETE PACKAGE -->

<?php include_once('layouts/footer.php'); ?>
