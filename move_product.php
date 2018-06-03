<?php
error_reporting(0);
  $page_title = 'Move Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
 $po = find_all1('detil_po');
 ?>
<?php
 if (isset($_POST['ganti'])) {
    
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
               <tr>
                <td class="text-center"><?php echo count_id();?></td>
               <td class="text-center"> <input type="hidden" name="no_po" value="<?php echo remove_junk($po1['id_po']); ?>"><?php echo remove_junk($po1['id_po']); ?></td>
                <td> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_warehouse']); ?></td>
                 <td class="text-center">
                    <?php if($a_po1['status']==true): ?>
                    <span class="label label-success" id="stat"><?php echo "Success"; ?></span>
                    <?php else: ?>
                    <span class="label label-danger"><?php echo "On Destination"; ?></span>
                    <?php endif;?>
                </td>
                <td class="text-center">
                  <button class="btn btn-md btn-success" title="Change" name="ganti" >
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