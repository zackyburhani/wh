<?php
error_reporting(0);
  $page_title = 'Receive Shipment';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
 // $po = find_all1('detil_po');
 $user   = current_user();
$status  = status_shipment($user['id_warehouse']);
 ?>
<?php
 if (isset($_GET['search_po'])) {
    $no = $_POST['no_po'];
    
   }
?>
<?php
if(isset($_POST['update_po'])){

  $status = "Success";
  $idpo = remove_junk($db->escape($_POST['id_po']));
  $iditem = remove_junk($db->escape($_POST['id_item']));

  //inserttable shipment 
  $idshipment = autonumber('id_shipment','shipment');
  $dateshipment = date("Y-m-d");
  $idpo = remove_junk($db->escape($_POST['id_po']));
  $idwarehouse = $user["id_warehouse"];
  $idemployer = $user["id_employer"];

        $query2  = "INSERT INTO shipment (";
        $query2 .=" id_shipment,date_shipment,id_po,id_warehouse,id_employer";
        $query2 .=") VALUES (";
        $query2 .=" '{$idshipment}', '{$dateshipment}', '{$idpo}', '{$idwarehouse}', '{$idemployer}'";
        $query2 .=")";


  if(empty($errors)){
        $sql = "UPDATE detil_po SET status='{$status}'";
       $sql .= " WHERE id_item='{$iditem}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows()) {
      $db->query($query2);
       $session->msg("s", "Successfully Approved");
       redirect('move_product.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Approved");
       redirect('move_product.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('move_product.php',false);
  }
}
?>

<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
  <div class="col-md-13">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
            <i class="fa fa-truck"></i>
            <span>Receive Shipment</span>
          </strong>
        </div>
        
        <div class="panel-body">
          <table class="table table-bordered" id="datatableProduct">
           <thead>
              <tr>
               <th class="text-center" style="width: 1px;">No.</th>
                <th class="text-center"> Id Purchase Order</th>
                <th class="text-center"> Date Shipment </th>
                <th class="text-center"> ID Item </th>
                <th class="text-center"> Quantity </th>
                <th class="text-center"> Status </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($status as $po1):?>     
                <input type="hidden" name="idpo" value="<?php echo remove_junk ($po1["id_po"])?>">
               <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_item']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"><span class="label label-danger"> <?php echo remove_junk($po1['status']); ?></span></td>
                <td class="text-center">
                    <button   class="btn btn-md btn-success" name="update_status" data-toggle="modal" data-target="#status<?php echo $po1['id_po']?>" title="Update">
                    <i class="glyphicon glyphicon-ok"></i>
                  </button>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


<?php foreach($status as $a_location): ?>
<div class="modal fade" id="status<?php echo $a_location['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-truck"></i> Approve </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="move_product.php" >
        <input type="hidden" value="<?php echo $a_location['id_po'];?>" name="id_po">
         <input type="hidden" value="<?php echo $a_location['id_item'];?>" name="id_item">
        Are You Sure to Accept this?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
          <button type="submit" name="update_po" class="btn btn-success"><i class="fa fa-check"></i> Accept</button>
        </div>

        </form>
    </div>
  </div>
</div>
<?php endforeach;?>



<script src="jquery-1.10.2.min.js"></script>
<script src="jquery.chained.min.js"></script>
<script>
  $("#sub_category").chained("#category");
</script>


  <script>
    $(".hapus").click(function () {
        var jawab = confirm("Press a button!");
        if (jawab === true) {
            var hapus = false;
            if (!hapus) {
                hapus = true;
                $.post('hapus.php', {id: $(this).attr('data-id')},
                function (data) {
                    alert(data);
                });
                hapus = false;
            }
        } else {
            return false;
        }
    });
</script>

  <?php include_once('layouts/footer.php'); ?>