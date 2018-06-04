<?php
  $page_title = 'History Purchase Order';
  require_once('includes/load.php');
  $all_warehouse = find_all1('warehouse');
  $user = current_user();

  $warehouse = find_by_id_warehouse('warehouse',$user['id_warehouse']);;
    if($warehouse['status'] != 0){
      page_require_level(2);
    } else {
      $session->msg('d','Sorry! you dont have permission to view the page. !');
          redirect('home.php', false);
    }

   $list_po          = find_all_PO_destination($user['id_warehouse']); 
   $all_warehouse_id = find_warehouse_id($user['id_warehouse']);

?>

<!-- Approve PO -->
<?php
  if(isset($_POST['approve_po'])){

    $req_fields = array('id_item');
    validate_fields($req_fields);
 
      if(empty($errors)){
        $id_item = remove_junk($db->escape($_POST['id_item']));
        $status  = 'On Destination';

        $query  = "UPDATE detil_po SET ";
        $query .= "status = '{$status}' ";
        $query .= "WHERE id_item = '{$id_item}'";

        //INSERT INTO SHIPMENT
        $id_shipment       = autonumber('id_shipment','shipment');
        $date_shipment     = date('Y-m-d');

        foreach ($list_po as $shipment) {
          $id_po         = $shipment['id_po'];
          $id_warehouse  = $shipment['for_wh'];
          $id_employer   = $shipment['id_emp'];
          
          $query2  = "INSERT INTO shipment (";
          $query2 .=" id_shipment,date_shipment,id_po,id_warehouse,id_employer";
          $query2 .=") VALUES (";
          $query2 .=" '{$id_shipment}', '{$date_shipment}', '{$id_po}', '{$id_warehouse}', '{$id_employer}'";
          $query2 .=")";
        }

        $move_stock   = move_stock($id_item);
        $id_warehouse = $user['id_warehouse'];
        $consumed     = $all_warehouse_id['heavy_consumed'];
        $max          = $all_warehouse_id['heavy_max'];
        $count        = $consumed-$move_stock['total_weight'];

        $query3  = "UPDATE warehouse SET ";
        $query3.= "heavy_consumed = '{$count}' ";
        $query3 .= "WHERE id_warehouse = '{$id_warehouse}'";

        $result = $db->query($query);
         if($result){
          //sucess
          $db->query($query2);
          $db->query($query3);
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
   <div class="col-md-13">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-13">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>list Purchase Order</span>
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
            <th class="text-center" style="width: 10px">Approve</th>
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
               <?php } else if($list['status'] == "Approved") { ?>
                <td align="center"><label class="label label-success"><?php echo remove_junk(ucwords($list['status']))?></label></td>
               <?php } ?>
               <td align="center"><?php echo remove_junk(ucwords($list['for_wh']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['id_item']))?></td>
               <td align="center"><?php echo remove_junk(ucwords($list['qty']))?></td>
               </td>
               <td align="center">
                 <button data-target="#approvePO<?php echo $list['id_po'];?>" class="btn btn-md btn-success" data-toggle="modal" title="Detail">
                    <i class="glyphicon glyphicon-ok"></i>
                  </button>
               </td>
               <td align="center">
                 <button data-target="#detailPO<?php echo $list['id_po'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Detail">
                    <i class="glyphicon glyphicon-print"></i>
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
          <?php $detailPo = find_all_detailPO($user['id_warehouse'],$a_po['id_po']); ?>
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
                   <?php if($detail['status'] == "On Process") { ?>
                    <td align="center"><span class="label label-danger"><?php echo remove_junk(ucwords($detail['status']))?></span></td>
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
