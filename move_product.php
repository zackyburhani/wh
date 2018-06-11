<?php
error_reporting(0);
  $page_title = 'Receive Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
 // $po = find_all1('detil_po');
 $user               = current_user();
 $all_location       = find_all_location('location',$user['id_warehouse']);
 $status1            = status_shipment($user['id_warehouse']);
 $all_warehouse_id   = find_warehouse_id($user['id_warehouse']);
 $all_package        = find_all_package('package',$user['id_warehouse']);
 $all_categories     = find_all_categories('categories',$user['id_warehouse']);
 $join_subcategories = find_allSubcategories($user['id_warehouse']);
 
 if (isset($_GET['search_po'])) {
    $no = $_POST['no_po'];   
   }
?>
<?php
if(isset($_POST['update_po'])){

  $status           = "Success";
  $idpo             = remove_junk($db->escape($_POST['id_po']));
  $iditem           = remove_junk($db->escape($_POST['id_item']));
  $id_location      = remove_junk($db->escape($_POST['id_location']));
  $id_package       = remove_junk($db->escape($_POST['id_package']));
  $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
  $safety_stock     = remove_junk($db->escape($_POST['safety_stock']));


  //insert table shipment 
  $idshipment   = autonumber('id_shipment','shipment');
  $dateshipment = date("Y-m-d");
  $idpo         = remove_junk($db->escape($_POST['id_po']));
  $idwarehouse  = $user["id_warehouse"];
  $idemployer   = $user["id_employer"];

  $query2  = "INSERT INTO shipment (";
  $query2 .=" id_shipment,date_shipment,id_po,id_warehouse,id_employer";
  $query2 .=") VALUES (";
  $query2 .=" '{$idshipment}', '{$dateshipment}', '{$idpo}', '{$idwarehouse}', '{$idemployer}'";
  $query2 .=")";

  //insert table product
  $all_item = find_all_product_shipment($iditem);
  $qty_new  = insert_new_id($user['id_warehouse']);

  $id_item_new = autonumber('id_item','item');
  $nm_item_new = $all_item['nm_item'];
  $colour_new  = $all_item['colour'];
  $width_new   = $all_item['width'];
  $height_new  = $all_item['height'];
  $length_new  = $all_item['length'];
  $weight_new  = $all_item['weight'];
  $stock_new   = $qty_new['qty'];

  $query3  = "INSERT INTO item (";
  $query3 .=" id_item,nm_item,colour,width,height,length,weight,stock,safety_stock,id_package,id_subcategories,id_location";
  $query3 .=") VALUES (";
  $query3 .=" '{$id_item_new}', '{$nm_item_new}', '{$colour_new}', '{$width_new}', '{$height_new}', '{$length_new}', '{$weight_new}', '{$stock_new}','{$safety_stock}','{$id_package}','{$id_subcategories}','{$id_location}'";
  $query3 .=")";

  //reduce area consumed 
  $consumed     = $all_warehouse_id['heavy_consumed'];
  $heavy_max    = $all_warehouse_id['heavy_max'];
  $id_warehouse = $all_warehouse_id['id_warehouse']; 
  $reduced      = ($weight_new*$stock_new )+$consumed;

  $query4  = "UPDATE warehouse SET ";
  $query4 .= "heavy_consumed='{$reduced}' ";
  $query4 .= "WHERE id_warehouse = '{$id_warehouse}'";

  //insert table bpack
  $id_bpack = autonumber('id_bpack','bpack');
  $count    = $weight_new*$stock_new;
  $sql2  = "INSERT INTO bpack (id_bpack,id_package,id_item,qty,total)";
  $sql2 .= " VALUES ('{$id_bpack}','{$id_package}','{$iditem}','{$stock_new}','{$count}')";

  if(empty($errors)){
        $sql = "UPDATE detil_po SET status='{$status}'";
       $sql .= " WHERE id_item='{$iditem}' and id_po = '{$idpo}'";
     $result = $db->query($sql);
     if($result) {
      $db->query($query2);
      $db->query($query3);
      $db->query($query4);
      $db->query($sql2);
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
            <span>Receive Product</span>
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
              <?php $no=1; ?>
              <?php foreach ($status1 as $po1):?>     
                <input type="hidden" name="idpo" value="<?php echo remove_junk ($po1["id_po"])?>">
               <tr>
                <td class="text-center"><?php echo $no++.".";?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['date_po']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['id_item']); ?></td>
                <td class="text-center"> <?php echo remove_junk($po1['qty']); ?></td>
                <td class="text-center"><span class="label label-danger"> <?php echo remove_junk($po1['status']); ?></span></td>
                <td class="text-center">
                    <button   class="btn btn-md btn-success" name="update_status" data-toggle="modal" data-target="#status<?php echo $po1['id_item']?>" title="Approve">
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


<?php foreach($status1 as $a_location): ?>
<div class="modal fade" id="status<?php echo $a_location['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-truck"></i> Approve </h4>
      </div>
      <div class="modal-body">
      <form method="post" action="move_product.php">

        <div class="form-group">
          <label for="name" class="control-label">Select Category</label>
            <select class="form-control" id="category" name="id_categories">
              <?php if($all_categories == null) { ?>
                <option value="">-</option>
                  <?php } else { ?>
                  <?php foreach($all_categories as $row){ ?>
                  <option value="<?php echo remove_junk($row['id_categories']); ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                  <?php } ?>  
                  <?php } ?>
             </select>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">Select Subcategory</label>
            <select class="form-control" id="sub_category" name="id_subcategories">
              <?php if($join_subcategories== null) { ?>
                <option value=" ">-</option>
                  <?php } else { ?>
                  <?php foreach($join_subcategories as $row2){ ?>
                  <option class="<?php echo $row2['id_categories']; ?>" value="<?php echo remove_junk($row2['id_subcategories']); ?>"><?php echo remove_junk(ucwords($row2['nm_subcategories'])); ?>
                  </option>
                  <?php } ?>
                  <?php } ?>  
            </select>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">Select Package</label>
            <select class="form-control" name="id_package">
            <?php if($all_package == null) { ?>
              <option value="">-</option>
              <?php } else { ?>
              <?php foreach($all_package as $row3){ ?>
              <option value="<?php echo remove_junk($row3['id_package']); ?>"><?php echo remove_junk(ucwords($row3['nm_package'])); ?></option>
              <?php } ?> 
              <?php } ?> 
            </select>
        </div>
        <div class="form-group">
          <label for="name" class="control-label">Select Location Warehouse</label>
            <select class="form-control" name="id_location">
            <?php if($all_location == null) { ?>
              <option value="">-</option>
            <?php } else { ?>
            <?php foreach($all_location as $row){ ?>
              <option value="<?php echo remove_junk($row['id_location']); ?>"><?php echo remove_junk(ucwords($row['unit'])); ?></option>
            <?php } ?>  
            <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label class="control-label">Safety Stock</label>
            <input type="number" min="0" class="form-control" required placeholder="Safety Stock" name="safety_stock">
          </div>

        <input type="hidden" value="<?php echo $a_location['id_po'];?>" name="id_po">
         <input type="hidden" value="<?php echo $a_location['id_item'];?>" name="id_item">
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