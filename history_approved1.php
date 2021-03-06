<?php
  $page_title = 'Approved Purchase Order';
  require_once('includes/load.php');

   page_require_level(2);
   $all_warehouse = find_all1('warehouse');
   $user = current_user();

   $list_po   = find_all_PO_approved1(); 
?>

<!-- Approve PO -->
<?php
  if(isset($_POST['approve_po'])){

    $req_fields = array('id_item');
    validate_fields($req_fields);

      if(empty($errors)){
        $id_item = remove_junk($db->escape($_POST['id_item']));
        $status  = 'Success';

        $query  = "UPDATE detil_po SET ";
        $query .= "status = '{$status}' ";
        $query .= "WHERE id_item = '{$id_item}'";

        $result = $db->query($query);
         if($result){
          //sucess
          $session->msg('s',"Purchase Order Has Been Approved ! ");
          redirect('approve2_po.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Approve Purchase Order !');
          redirect('approve2_po.php', false);
        }
   } else {
     $session->msg("d", $errors);
     redirect('approve2_po.php', false);
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
  <div class="col-md-13">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Approved Purchase Order History</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 5px;">No. </th>
            <th class="text-center">ID PO</th>
            <th class="text-center">Date PO</th>
            <th class="text-center">Date Sent</th>
            <th class="text-center">Status</th>
            <th class="text-center" style="width: 10px">For Warehouse </th>
            <th class="text-center">ID Item </th>
            <th class="text-center" style="width: 10px">QTY</th>
            <th class="text-center" style="width: 10px">View</th>
            <th class="text-center" style="width: 10px">Print</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; ?>
          <?php foreach($list_po as $list): ?>
              <tr>
               <td class="text-center"><?php echo $no++."."; ?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['id_po']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['date_po']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['date_send']))?></td>
               <?php if($list['status'] == "Approved") { ?>
                <td align="center"><label class="label label-danger"><?php echo remove_junk(ucwords($list['status']))?></label></td>
               <?php } else if($list['status'] == "On Destination") { ?>
                <td align="center"><label class="label label-warning"><?php echo remove_junk(ucwords($list['status']))?></label></td>
               <?php } else { ?>
                <td align="center"><label class="label label-success"><?php echo remove_junk(ucwords($list['status']))?></label></td>
               <?php } ?>
               <td align="center"><?php echo remove_junk(ucwords($list['for_wh']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['id_item']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['qty']))?></td>
               <td align="center">
                 <button data-target="#detailPO<?php echo $list['id_po'];?>" class="btn btn-md btn-info" data-toggle="modal" title="Detail">
                    <i class="glyphicon glyphicon-eye-open"></i>
                  </button>
               </td>
               <td align="center">
                 <a href="report_po.php?id=<?php echo $list['id_po'] ?>" class="btn btn-danger" role="button" title="print PO">
                    <i class="glyphicon glyphicon-print"></i>
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


  </div>
</div>

<!-- Detail Modal -->
<?php foreach($list_po as $a_po): ?> 
  <div class="modal fade" id="detailPO<?php echo $a_po['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-th"></span> Detail Purchase Order</h4>
        </div>
        <div class="modal-body">
          <?php $detailPo = find_all_detailPO_admin($a_po['id_po']); ?>
          <table class="table table-bordered" id="tablePosition">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">No. </th>
                <th class="text-center">ID PO</th>
                <th class="text-center">From Warehouse</th>
                <th class="text-center">ID Item</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Status</th>
                <th class="text-center">Total Weight</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              <?php foreach($detailPo as $detail): ?>
                  <tr>
                   <td class="text-center"><?php echo $no++."."; ?></td>
                   <td align="center"><?php echo remove_junk(ucwords($detail['id_po']))?></td>
                   <td align="center"><?php echo remove_junk(ucwords($detail['From_wh']))?></td>
                   <td align="center"><?php echo remove_junk(ucwords($detail['id_item']))?></td>
                   <td align="center"><?php echo remove_junk(ucwords($detail['qty']))?></td>
                   <?php if($detail['status'] == "Approved") { ?>
                    <td align="center"><span class="label label-danger"><?php echo remove_junk(ucwords($detail['status']))?></span></td>
                   <?php } else if($detail['status'] == "On Destination") { ?>
                    <td align="center"><span class="label label-warning"><?php echo remove_junk(ucwords($detail['status']))?></span></td>
                   <?php } else { ?>
                     <td align="center"><span class="label label-success"><?php echo remove_junk(ucwords($detail['status']))?></span></td>
                   <?php } ?>
                   
                   <td align="center"><?php echo remove_junk(ucwords($detail['total_weight']))?></td>
                  </tr>
                <?php endforeach;?>
               <?php $data = countDetail($a_po['id_po']); ?>
               <tr>
                  <td colspan="6" align="right"><b>SUM WEIGHT</b></td>
                  <td colspan="1" align="center"><b><?php echo $data['total']; ?></b></td>
               </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>



<!-- Approve Modal -->
<?php foreach($list_po as $item) : ?>
  <div class="modal fade" id="approvePO<?php echo $item['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-th"></span> Approve Purchase Order</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Approve Item <b><u><?php echo remove_junk(ucwords($item['id_item'])); ?></u></b> ?
        <form method="post" action="approve2_po.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['id_item'])); ?>" name="id_item">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="approve_po" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Approve</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
 
<?php include_once('layouts/footer.php'); ?>
  