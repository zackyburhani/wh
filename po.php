<?php
  $page_title = 'Purchase Order Transaction';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);

  $user            = current_user();
  $all_categories  = find_all1('warehouse');
  $getWarehouse    = find_po_warehouse($user['id_warehouse']);
  $getAllWarehouse = find_all1('warehouse');
  $getItem         = find_all_item_po($user['id_warehouse']);
  
?>
<?php
 if(isset($_POST['add_warehouse'])){
   $req_field = array('warehousename');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['warehousename']));
   $country = remove_junk($db->escape($_POST['country']));
   $address = remove_junk($db->escape($_POST['address']));
   $status = remove_junk($db->escape($_POST['status']));
   $heavymax = remove_junk($db->escape($_POST['heavymax']));
   $consumed = remove_junk($db->escape($_POST['consumed']));
   if(empty($errors)){
      $sql  = "INSERT INTO warehouse (nm_warehouse,country,address,status,heavy_max,heavy_consumed)";
      $sql .= " VALUES ('{$cat_name}','{$country}','{$address}','{$status}','{$heavymax}','{$consumed}')";

      $getAllWarehouseName = "SELECT nm_warehouse FROM warehouse where nm_warehouse = '$cat_name'";
      $ada=$db->query($getAllWarehouseName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Warehouse Is Exist");
        redirect('add_warehouse.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Warehouse");
          redirect('add_warehouse.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_warehouse.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_warehouse.php',false);
   }
 }
?>

<!-- UPDATE WAREHOUSE -->
<?php
if(isset($_POST['update_warehouse'])){
  $req_field = array('warehousename','country','address','status','heavymax','consumed','idwarehouse');
  validate_fields($req_field);
  $cat_name = remove_junk($db->escape($_POST['warehousename']));
  $country = remove_junk($db->escape($_POST['country']));
  $address = remove_junk($db->escape($_POST['address']));
  $status = remove_junk($db->escape($_POST['status']));
  $heavymax = remove_junk($db->escape($_POST['heavymax']));
  $consumed = remove_junk($db->escape($_POST['consumed']));
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));
  if(empty($errors)){
        $sql = "UPDATE warehouse SET nm_warehouse='{$cat_name}',country='{$country}',address='{$address}',status='{$status}',heavy_max='{$heavymax}',heavy_consumed='{$consumed}'";
       $sql .= " WHERE id_warehouse='{$idwarehouse}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Warehouse");
       redirect('add_warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_warehouse.php',false);
  }
}
?>
<!-- END UPDATE WAREHOUSE -->

<?php
  if(isset($_POST['delete_warehouse'])){
  $req_field = array('idwarehouse');
  validate_fields($req_field);
  $idwarehouse = remove_junk($db->escape($_POST['idwarehouse']));
  if(empty($errors)){
        $sql = "DELETE FROM warehouse WHERE id_warehouse='{$idwarehouse}'";
     $result = $db->query($sql);
     if($result && $db->affected_rows() === 1) {
       $session->msg("s", "Successfully updated Warehouse");
       redirect('add_warehouse.php',false);
     } else {
       $session->msg("d", "Sorry! Failed to Update");
       redirect('add_warehouse.php',false);
     }
  } else {
    $session->msg("d", $errors);
    redirect('add_warehouse.php',false);
  }
}
?>


<!-- SESSION ADD PO-->
<?php 

class POrder{
 var $id_item;
 var $nm_item;
 var $colour;
 var $width;
 var $length;
 var $weight;
 var $stock;
 var $id_package;
 var $id_subcategories;
 var $id_location;
 var $qty;

}

if(isset($_GET['add_item']) && !isset($_POST['update']))  { 
  $id =$_GET['id_item'];
  $sql     = "SELECT * FROM item WHERE id_item = '$id'";
  $result  = $db->query($sql); 
  $product = mysqli_fetch_object($result);

  $po    = new POrder();
  $po->id_item          = $product->id_item;
  $po->nm_item          = $product->nm_item;
  $po->colour           = $product->colour;
  $po->width            = $product->width;
  $po->height           = $product->height;
  $po->length           = $product->length;
  $po->weight           = $product->weight;
  $po->stock            = $product->stock;
  $po->id_package       = $product->id_package;
  $po->id_subcategories = $product->id_subcategories;
  $po->id_location      = $product->id_location;
  $iteminstock            = $product->stock;
  $po->qty = 1;
  // Check product is existing in cart
  $index = -1;
  $cart = unserialize(serialize($_SESSION['cart']));// set $cart as an array, unserialize() converts a string into array

  // session_destroy();

  for($i=0; $i<count($cart);$i++)
    if ($cart[$i]->id_item == $_GET['id_item']){
      $index = $i;
      break;
    }
    if($index == -1) 
      $_SESSION['cart'][] = $po; // $_SESSION['cart']: set $cart as session variable
    else {
      
      if (($cart[$index]->qty) < $iteminstock)
           $_SESSION['cart'] = $cart;
    }
}

// Delete product in cart
if(isset($_GET['index']) && !isset($_POST['update'])) {
  $cart = unserialize(serialize($_SESSION['cart']));
  unset($cart[$_GET['index']]);
  $cart = array_values($cart);
  $_SESSION['cart'] = $cart;
}

// Update quantity in cart
if(isset($_POST['update'])) {
  $arrQuantity = $_POST['qty'];
  $cart = unserialize(serialize($_SESSION['cart']));
  for($i=0; $i<count($cart);$i++) {
     $cart[$i]->qty = $arrQuantity[$i];
  }
  $_SESSION['cart'] = $cart;
}

?>
<!-- END SESSION ADD PO -->

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>PURCHASE ORDER</span>
     </strong>
    </div>
     <div class="panel-body">
      <form method="post" action="">
        <div class="form-group">
          <label class="control-label">No. PO</label>
          <input type="text" value="<?php echo autonumber('id_po','po'); ?>" class="form-control" name="warehousename" readonly>
        </div>
        <div class="form-group">
          <label class="control-label">PO Date</label>
          <input type="date" value="<?php echo date("Y-m-d");?>" class="form-control" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">From Warehouse</label>
          <select class="form-control" id="warehouse" name="id_warehouse">
            <?php foreach($getAllWarehouse as $wh) { ?>
               <option value="<?php echo remove_junk($wh['id_warehouse']); ?>"><?php echo remove_junk(ucwords($wh['nm_warehouse'])); ?></option>
            <?php } ?>
        </select>
        </div> 
      </form>
     </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
       <strong>
         <span class="glyphicon glyphicon-th"></span>
        <span>DETAIL ORDER</span>
       </strong>
    </div>
     <div class="panel-body">
      <form method="post" action="">
        <div class="form-group">
          <label class="control-label">Send Date</label>
          <input type="date" class="form-control" name="country">
        </div>
        <div class="form-group">
          <label class="control-label">For Warehouse</label>
          <input type="text" value="<?php echo $getWarehouse['nm_warehouse'] ?>" class="form-control" name="country" readonly>
        </div>
        <hr>
        <div class="form-group pull-right">
          <button type="button" data-target="#add_product" class="btn btn-md btn-info" data-toggle="modal" title="Add Item"><i class="glyphicon glyphicon-plus"></i> Choose Item
            </button>
        </div>   
      </form>
     </div>
    </div>
  </div>
  <div class="col-md-12">
    <form method="POST" action="po.php">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>WAREHOUSE</span>
       </strong>
       <div class="pull-right">
         <button type="submit" class="btn btn-danger" name="update">Simpan</button>
       </div>
      </div>
       <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
            <th>id_item</th>
            <th>nm_item</th>
            <th>colour</th>
            <th>weight</th>
            <th>stock</th>
            <th>id_package</th>
            <th>id_location</th>
            <th>QTY</th>
            <th>Sub Total (CAD)</th>
            <th>Action</th>
          </tr>  
          </thead>
          <tbody>
            <?php 
              $cart = unserialize(serialize($_SESSION['cart']));
              $s = 0;
              $index = 0;
              for($i=0; $i<count($cart); $i++){
              $s += $cart[$i]->weight * $cart[$i]->qty;

            ?> 
             <tr>
                <td> <?php echo $cart[$i]->id_item; ?> </td>
                <td> <?php echo $cart[$i]->nm_item; ?> </td>
                <td> <?php echo $cart[$i]->colour; ?> </td>
                <td> <?php echo $cart[$i]->weight; ?> </td>
                <td> <?php echo $cart[$i]->stock; ?> </td>
                <td> <?php echo $cart[$i]->id_package; ?> </td>
                <td> <?php echo $cart[$i]->id_location; ?> </td>
                <td align="center">
                  <input type="number" min="0" value="<?php echo $cart[$i]->qty; ?>" name="qty[]" style="width: 80px;">
                </td>
                <td> <?php echo $cart[$i]->weight * $cart[$i]->qty; ?> </td> 
                <td><a class="btn btn-danger" href="po.php?index=<?php echo $index; ?>" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-trash"></span></a> </td>
             </tr>
              <?php 
                $index++;
                } ?>  
          </tbody>
        </table>
       </div>
     </form>
    </div>
  </div>
</div>

<!-- Entry Data -->
<div class="modal fade" id="add_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-th"></span>  Add Item To Purchase</h4>
        
      </div>
      <div class="modal-body">
        <form method="GET" action="po.php">
            <div class="form-group">
              <label for="level">Name Item</label>
                <select class="form-control" name="id_item">
                  <?php if($getItem == null) { ?>
                   <option value="">-</option> 
                  <?php } ?>
                  <?php foreach ($getItem as $item ):?>
                   <option value="<?php echo $item['id_item'];?>"><?php echo ucwords($item['nm_item']);?></option>
                  <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
              <label class="control-label">QTY</label>
              <input type="number" class="form-control" name="qty">
            </div> 
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
            <button type="submit" name="add_item" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>  Add</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- END Entry Data  -->

  </div>
</div>

<!-- Update Modal -->
<?php foreach($all_categories as $a_warehouse): ?> 
<div class="modal fade" id="updateWarehouse<?php echo (int)$a_warehouse['id_warehouse'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Data Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <div class="form-group">
          <label class="control-label">Warehouse</label>
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['nm_warehouse'])); ?>" name="warehousename">
        </div>
        <div class="form-group">
          <label class="control-label">Country</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['country'])); ?>" name="country">
        </div>  
        <div class="form-group">
          <label class="control-label">Address</label>
          <textarea type="name" class="form-control" name="address"><?php echo remove_junk(ucwords($a_warehouse['address'])); ?>"</textarea>
        </div>  
        <div class="form-group">
          <label for="status">Status</label>
            <select class="form-control" name="status">
                <option <?php if($a_warehouse['status'] === '1') echo 'selected="selected"';?> value="1"> Produce </option>
                <option <?php if($a_warehouse['status'] === '0') echo 'selected="selected"';?> value="0"> Not Produce</option>
              </select>
        </div>
        <div class="form-group">
          <label class="control-label">Heavy Max</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['heavy_max'])); ?>" name="heavymax">
        </div>  
        <div class="form-group">
          <label class="control-label">Area Consumed</label>
          <input type="name" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['heavy_consumed'])); ?>" name="consumed">
        </div>    
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="update_warehouse" class="btn btn-primary">Update</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>

<!-- Modal -->
<?php foreach($all_categories as $a_warehouse): ?> 
<div class="modal fade" id="deleteWarehouse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Delete Warehouse</h4>
      </div>
      <div class="modal-body">
      <form method="post" action="add_warehouse.php" class="clearfix">
        <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_warehouse['id_warehouse'])); ?>" name="idwarehouse">
        <p>Are you sure to delete this warehouse?</p>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="delete_warehouse" class="btn btn-danger">Delete</button>
      </div>
    </form>
    </div>
  </div>
</div>
</div>
<?php endforeach;?>





<!-- <form method="POST" action="po.php">
<table border="1">
<tr>
  <th>DELETE</th>
  <th>id_item</th>
  <th>nm_item</th>
  <th>colour</th>
  <th>width</th>
  <th>length</th>
  <th>weight</th>
  <th>stock</th>
  <th>id_package</th>
  <th>id_subcategories</th>
  <th>id_location</th>
   <th>QTY</th>
  <th>Sub Total (CAD)</th>
</tr>
<?php 
     $cart = unserialize(serialize($_SESSION['cart']));
   $s = 0;
   $index = 0;
  for($i=0; $i<count($cart); $i++){
    $s += $cart[$i]->weight * $cart[$i]->qty;

 ?> 
   <tr>
      <td><a href="po.php?index=<?php echo $index; ?>" onclick="return confirm('Are you sure?')" >Delete</a> </td>
      <td> <?php echo $cart[$i]->id_item; ?> </td>
      <td> <?php echo $cart[$i]->nm_item; ?> </td>
      <td> <?php echo $cart[$i]->colour; ?> </td>
      <td> <?php echo $cart[$i]->width; ?> </td>
      <td> <?php echo $cart[$i]->length; ?> </td>
      <td> <?php echo $cart[$i]->weight; ?> </td>
      <td> <?php echo $cart[$i]->stock; ?> </td>
      <td> <?php echo $cart[$i]->id_package; ?> </td>
      <td> <?php echo $cart[$i]->id_subcategories; ?> </td>
      <td> <?php echo $cart[$i]->id_location; ?> </td>

        <td> <input type="number" min="0" value="<?php echo $cart[$i]->qty; ?>" name="qty[]"> </td>  
        <td> <?php echo $cart[$i]->weight * $cart[$i]->qty; ?> </td> 
   </tr>
  <?php 
    $index++;
  } ?>
  <tr>
    <td colspan="5" style="text-align:right; font-weight:bold">Sum 
         <input id="saveimg" type="image" src="images/save.png" name="update" alt="Save Button">
         <button type="submit" name="update">Simpan</button>
    </td>
    <td> <?php echo $s; ?> </td>
  </tr>
</table>
</form> -->
<?php include_once('layouts/footer.php'); ?>
