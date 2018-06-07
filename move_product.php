<?php
error_reporting(0);
  $page_title = 'Move Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
 // $po = find_all1('detil_po');
 $status= status_shipment();
 $user = current_user();
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
       $sql .= " WHERE id_po='{$idpo}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
      $db->query($query2);
       $session->msg("s", "Successfully updated Package");
       redirect('move_product.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
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

        </div>
        
        <div class="panel-body">
          <table class="table table-bordered" id="datatableProduct">
        <div class="col-md-4">
          <form method="post" action="move_product.php">
          <!-- <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="no_po" placeholder="Input Number of Purchase Order">
                   </div> -->
          
          <!-- </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-search" name="search_po"></span>&nbsp;&nbsp;&nbsp;Search</button>
          </div> -->
            </form>
          <br><br><br>
          
        
             <thead>
              <tr>
               <th class="text-center" style="width: 1px;">No.</th>
                <th class="text-center"> Id Purchase Order</th>
                <th class="text-center"> Date Shipment </th>
                <th class="text-center"> Quantity </th>
                <th class="text-center"> Id Warehouse </th>
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
                <td> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_warehouse']); ?></td>
                <td class="text-center"><span class="label label-danger"> <?php echo remove_junk($po1['status']); ?></span></td>
                <td class="text-center">
                    <button   class="btn btn-md btn-success" name="update_status" data-toggle="modal" data-target="#status<?php echo $po1['id_po']?>" title="Update">
                    <i class="glyphicon glyphicon-ok"></i>
                  </button>
               
                  
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
</table>
<?php foreach($status as $a_location): ?>
<div class="modal fade" id="status<?php echo $a_location['id_po'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Location</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="move_product.php" >
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_location['id_po'])); ?>" name="id_po">
        <p>Are You Sure to Accept this Purchase? <b>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_po" class="btn btn-success">Accept</button>
      </div>
    </form>
    </div>
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