<?php
  $page_title = 'Canceled PO';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_warehouse  = find_all1('warehouse');
  $user           = current_user();
  $all_po_cancel  = find_all_canceledPO($user['id_warehouse']);

?>

<?php
  if(isset($_POST['delete_po'])){
  $req_field = array('id_po');
  validate_fields($req_field);
  $id_po = remove_junk($db->escape($_POST['id_po']));
  $id_item = remove_junk($db->escape($_POST['id_item']));

  if(empty($errors)){
    $sql    = "DELETE FROM detil_po WHERE id_po='{$id_po}' and id_item ='{$id_item}' ";
    $result = $db->query($sql);
    if($result) {
      $session->msg("s", "Successfully Delete PO");
      redirect('canceled_po.php',false);
    } else {
      $session->msg("d", "Sorry! Failed to Delete");
      redirect('canceled_po.php',false);
    }
  } else {
    $session->msg("d", $errors);
    redirect('canceled_po.php',false);
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
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Canceled Purchase Order</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 10px;">No. </th>
            <th class="text-center">ID PO</th>
            <th class="text-center">Date PO</th>
            <th class="text-center">Date Send</th>
            <th class="text-center">From Warehouse</th>
            <th class="text-center" style="width: 1px;">Detail</th>
            <th class="text-center" style="width: 1px;">Delete</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($all_po_cancel as $a_po): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['id_po']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['date_po']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['date_send']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['from_wh']))?></td>
           <td class="text-center">
              <button data-target="#detailPo<?php echo $a_po['id_po'];?>" class="btn btn-md btn-primary" data-toggle="modal" title="Detail">
                <i class="glyphicon glyphicon-eye-open"></i>
              </button>
           </td>
           <td class="text-center">
              <button data-target="#deletePo<?php echo $a_po['id_po'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Remove">
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


  </div>
</div>

<?php foreach($all_po_cancel as $item) : ?>
  <div class="modal fade" id="deletePo<?php echo $item['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Delete Canceled Order</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete ?
        <form method="post" action="canceled_po.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['id_po'])); ?>" name="id_po">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['id_item'])); ?>" name="id_item">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_po" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>

<?php foreach($all_po_cancel as $item) : ?>
  <div class="modal fade" id="detailPo<?php echo $item['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-th"></span> Canceled Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 10px;">No. </th>
            <th class="text-center">ID PO</th>
            <th class="text-center">Date PO</th>
            <th class="text-center">Date Send</th>
            <th class="text-center">ID Item</th>
            <th class="text-center">QTY</th>
            <th class="text-center">From Warehouse</th>
            <th class="text-center">Status</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php $all_po_cancel_detail = find_all_canceledPO_detil($item['for_wh'],$item['id_po']); ?>
        <?php foreach($all_po_cancel_detail as $a_po): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['id_po']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['date_po']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['date_send']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['id_item']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['qty']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_po['from_wh']))?></td>
           <td class="text-center"><label class="label label-danger"><?php echo remove_junk(ucwords($a_po['status']))?></label></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>   
        </div>
    </div>
  </div>
</div>
<?php endforeach;?>

 
<?php include_once('layouts/footer.php'); ?>
