<?php
  $page_title = 'Approved History';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_warehouse = find_all1('warehouse');
   $user = current_user();
   $all_history_shipment  = find_all_history_shipment($user['id_warehouse']);

   
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
        <span class="fa fa-truck"></span>
        <span> Approved History</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 10px;">No. </th>
            <th class="text-center">ID Shipment</th>
            <th class="text-center">Date Shipment</th>
            <th class="text-center">ID Purchase Order</th>
            <th class="text-center">ID Warehouse</th>
            <th class="text-center" style="width: 150px;">ID Employer</th>
          </tr>
        </thead>
        <tbody>
        <?php echo $no=1; ?>
        <?php foreach($all_history_shipment as $a_shipment_history): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_shipment_history['id_shipment']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_shipment_history['date_shipment']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_shipment_history['id_po']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_shipment_history['id_warehouse']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_shipment_history['id_employer']))?></td>
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
<!-- <?php foreach($all_po as $a_po): ?> 
  <div class="modal fade" id="detailPo<?php echo $a_po['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-th"></span> Detail Purchase Order</h4>
        </div>
        <div class="modal-body">
          <?php $detailPo = find_all_detailPO($user['id_warehouse'],$a_po['id_po']); ?> 
          <table class="table table-bordered" id="tablePosition">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">No. </th>
                <th class="text-center">ID PO</th>
                <th class="text-center">ID Item</th>
                <th class="text-center">Date Sent</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Status</th>
                <th class="text-center">From Warehouse</th>
                <th class="text-center">Total Weight</th>
              </tr>
            </thead>
            <tbody>
            <?php $no=1; ?>
            <?php foreach($detailPo as $detail): ?>
              <tr>
               <td class="text-center"><?php echo $no++; ?></td>
               <td align="center"><?php echo remove_junk(ucwords($detail['id_po']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($detail['id_item']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($detail['date_po']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($detail['qty']))?></td>
               <?php if($detail['status'] == "On Process" || $detail['status'] == "Approved") { ?>
                <td align="center"><label class="label label-danger"><?php echo remove_junk(ucwords($detail['status']))?></label></td>
               <?php } else if($detail['status'] == "On Destination") { ?>
                <td align="center"><label class="label label-warning"><?php echo remove_junk(ucwords($detail['status']))?></label></td>
               <?php } else { ?>
                <td align="center"><label class="label label-success"><?php echo remove_junk(ucwords($detail['status']))?></label></td>
               <?php } ?>
               <td align="center"><?php echo remove_junk(ucwords($detail['from_wh']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($detail['total_weight']))?></td>
              </tr>
            <?php endforeach;?>
               <?php $data = countDetail($a_po['id_po']); ?>
               <tr>
                  <td colspan="7" align="right"><b>SUM WEIGHT</b></td>
                  <td colspan="1" align="center"><b><?php echo $data['total']; ?></b></td>
               </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?> -->
 
<?php include_once('layouts/footer.php'); ?>
