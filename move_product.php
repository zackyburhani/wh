<?php
error_reporting(0);
  $page_title = 'Move Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
 $po = find_all1('detil_po');
 ?>
<?php
 if (isset($_GET['search_po'])) {
    $no = $_POST['no_po'];
    
    
   }

  
?>
<?php
if(isset($_POST['update_status'])){
  $req_field = array('status');
  validate_fields($req_field);
  $status = "Success";
  $idpo = remove_junk($db->escape($_POST['id_po']));
  if(empty($errors)){
        $sql = "UPDATE detil_po SET status='{$status}'";
       $sql .= " WHERE id_po='{$idpo}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
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
             
              <?php foreach ($po as $po1):?>     
                <input type="text" name="idpo" value="<?php echo remove_junk ($po1["id_po"])?>">
               <tr>
                <td class="text-center"><?php echo count_id();?></td>
               <td class="text-center"> <?php echo remove_junk($po1['id_po']); ?></td>
                <td> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_warehouse']); ?></td>
                 <td class="text-center">
                    <?php if($po1['status'] == 'Success'): ?>
                    <span class="label label-success"><?php echo "Success"; ?></span>
                    <?php else: ?>
                    <span class="label label-danger"><?php echo "On Destination"; ?></span>
                    <?php endif;?>
                </td>
                <td class="text-center">
                  <?php if($po1['status'] == 'On Destination'):?>
                  <button   class="btn btn-md btn-success" name="update_status" title="Update">
                    <i class="glyphicon glyphicon-ok"></i>
                  </button>
                <?php else: ?>

                  <?php endif;?>
                  
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