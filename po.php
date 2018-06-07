<?php
  $page_title = 'Purchase Order Transaction';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  error_reporting(0);

  $user            = current_user();
  $all_categories  = find_all1('warehouse');
  $getWarehouse    = find_po_warehouse($user['id_warehouse']);
  $getAllWarehouse = find_warehouse_po($user['id_warehouse']);
  $all_warehouse_id    = find_warehouse_id($user['id_warehouse']);

  $getItem         = find_all1('item');
  
?>
<?php
 //
  if(isset($_POST['process'])){
  $req_field = array('send_date');
  validate_fields($req_field);

    //po
    $id_po         = remove_junk($db->escape($_POST['id_po']));
    $date_po       = remove_junk($db->escape($_POST['date_po']));
    $id_warehouse  = $user['id_warehouse'];
    
    //detil_po
    $id_item        = remove_junk($db->escape($_POST['id_itemDP']));
    $qty            = remove_junk($db->escape($_POST['qtyDP']));
    $send_date      = remove_junk($db->escape($_POST['send_date']));
    $total_weight   = remove_junk($db->escape($_POST['total_weightDP']));
    $from_warehouse = remove_junk($db->escape($_POST['from_id_warehouse']));
    $total_sum      = remove_junk($db->escape($_POST['total_sum']));
    
    if($from_warehouse == '0001'){
      $status         = "Approved";
    } else {
      $status         = "On Process";
    }

    if($send_date < $date_po){
      $session->msg("d", "Send Date Invalid");
        redirect('po.php',false);
    }

     $heavy_max    = $all_warehouse_id['heavy_max'];
    // if($heavy_max < $total_sum){
    //   $session->msg('d',"You Do Not Have Enough Storage Space !");
    //   redirect('po.php',false);
    // } 

    if(empty($errors)){
      $sql  = "INSERT INTO po (id_po,date_po,id_warehouse)";
      $sql .= " VALUES ('{$id_po}','{$date_po}','{$id_warehouse}')";

      $getAllPO = "SELECT id_po FROM po where id_po = '$id_po'";
      $ada=$db->query($getAllPO) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Purchase Order Is Exist");
        redirect('po.php',false);
      } else {
          if($db->query($sql)){

            $cart = unserialize(serialize($_SESSION['cart']));
            for($i=0; $i<count($cart);$i++) {

              $cart[$i]->total_weight = $cart[$i]->weight * $cart[$i]->qty;

              $sql2  = "INSERT INTO detil_po (id_po, date_po, qty, status, id_warehouse ,total_weight ,id_item)";
              $sql2 .= " VALUES ('{$id_po}','{$send_date}','{$cart[$i]->qty}','{$status}','{$from_warehouse}','{$cart[$i]->total_weight}', '{$cart[$i]->id_item}')";
                $ada=$db->query($sql2);
            }
            unset($_SESSION['cart']);

            $session->msg("s", "Successfully Added Purchase Order");
            redirect('po.php',false);
          } else {
            $session->msg("d", "Sorry Failed To Add Purchase Order.");
            redirect('po.php',false);
          }
        }
    } else {
      $session->msg("d", $errors);
      redirect('po.php',false);
    }
  }
?>



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
 var $total_weight;

}

if(isset($_GET['add_item']) && !isset($_POST['update']))  { 
  $id =$_GET['id_item'];
  $qty = remove_junk($db->escape($_GET['qty']));
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
  $po->total_weight     = $product->total_weight;
  $iteminstock          = $product->stock;
  $po->qty = $qty;

  // Check product is existing in cart
  $index = -1;
  $cart = unserialize(serialize($_SESSION['cart']));// set $cart as an array, unserialize() converts a string into array

  // session_destroy();

  if($cart==null){
    header("Refresh:0");
  }
  
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

     $consumed     = $all_warehouse_id['heavy_consumed'];
     $heavy_max    = $all_warehouse_id['heavy_max'];
     $id_warehouse = $all_warehouse_id['id_warehouse']; 
     $reduced      = ($cart[$i]->weight*$cart[$i]->qty);

     // if($reduced > $heavy_max) {
     //  $session->msg("d", "Total Weight Greater Than Area Warehouse");
     //  redirect('po.php',false);
    // }

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
<form method="post" action="po.php">
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
      <div class="form-group">
        <label class="control-label">No. PO</label>
        <input type="text" value="<?php echo autonumber('id_po','po'); ?>" class="form-control" name="id_po" readonly>
      </div>
      <div class="form-group">
        <label class="control-label">PO Date</label>
        <input type="date" value="<?php echo date("Y-m-d");?>" class="form-control" name="date_po" readonly>
      </div>
      <div class="form-group">
        <label class="control-label">For Warehouse</label>
        <input type="text" value="<?php echo $getWarehouse['nm_warehouse'] ?>" class="form-control" name="id_warehouse" readonly>
      </div>
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
      <?php if($_SESSION['cart'] != null){ ?>
        <div class="form-group">
          <label class="control-label">Send Date</label>
          <input type="date" class="form-control" name="send_date">
        </div>
        <div class="form-group">
          <label class="control-label">From Warehouse</label>
          <select class="form-control" id="warehouse" name="from_id_warehouse">
            <?php foreach($getAllWarehouse as $wh) { ?>
            <option value="<?php echo remove_junk($wh['id_warehouse']); ?>"><?php echo remove_junk(ucwords($wh['nm_warehouse'])); ?></option>
              <?php } ?>
          </select>
        </div> 
      <?php } ?>
        <hr>
        <div class="form-group pull-right">
          <?php if($_SESSION['cart'] != null){ ?>
            <button type="submit" class="btn btn-success" name="process"><i class="fa fa-money"></i> Process Purchase Order</button>
          <?php } ?>
          <button type="button" data-target="#add_product" class="btn btn-md btn-info" data-toggle="modal" title="Add Item"><i class="glyphicon glyphicon-plus"></i> Choose Item
            </button>
        </div>   
       </div>
      </div>
    </div>
  
  <?php if($_SESSION['cart'] != null) { ?>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>WAREHOUSE</span>
         </strong>
         <div class="pull-right">
           <button type="submit" class="btn btn-danger" name="update"><i class="  fa fa-pencil-square"></i> Update QTY</button>
         </div>
        </div>
         <div class="panel-body">
          
          <table class="table table-bordered">
            <thead>
              <tr>
                <th><center>ID Item </center></th>
                <th><center>Item Name</center></th>
                <th><center>Colour</center></th>
                <th><center>Weight</center></th>
                <th><center>Stock</center></th>
                <th><center>ID package</center></th>
                <th><center>ID location</center></th>
                <th><center>QTY</center></th>
                <th><center>Total Weight</center></th>
                <th><center>Action</center></th>
              </tr>  
            </thead>
            <tbody>
              <?php 
                $cart = unserialize(serialize($_SESSION['cart']));
                $s = 0;
                $index = 0;
                for($i=0; $i<count($cart); $i++){
                $s += $cart[$i]->weight * $cart[$i]->qty;
                $cart[$i]->total_weight = $cart[$i]->weight * $cart[$i]->qty

              ?> 
               <tr>
                  <td align="center"> <?php echo $cart[$i]->id_item; ?> </td>
                  <td align="center"> <?php echo $cart[$i]->nm_item; ?> </td>
                  <td align="center"> <?php echo $cart[$i]->colour; ?> </td>
                  <td align="center"> <?php echo $cart[$i]->weight; ?> </td>
                  <td align="center"> <?php echo $cart[$i]->stock ?> </td>
                  <td align="center"> <?php echo $cart[$i]->id_package; ?> </td>
                  <td align="center"> <?php echo $cart[$i]->id_location; ?> </td>
                  <td align="center">
                    <input type="number" min="0" value="<?php echo $cart[$i]->qty; ?>" name="qty[]" style="width: 80px;">
                  </td>
                  <td align="center"> <?php echo $cart[$i]->weight * $cart[$i]->qty; ?> </td> 
                  <td align="center"><a class="btn btn-danger" href="po.php?index=<?php echo $index; ?>" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-trash"></span></a> </td>

                  <!-- hidden input to detil_po -->
                  <input type="hidden" value="<?php echo $cart[$i]->id_item; ?>" name="id_itemDP">
                  <input type="hidden" value="<?php echo $cart[$i]->qty; ?>" name="qtyDP">
                  <input type="hidden" value="<?php echo $cart[$i]->total_weight; ?>" name="total_weightDP">
                  <!-- hidden input to detil_po -->
               </tr>
                <?php 
                  $index++;
                  } ?>
                <tr>
                  <td colspan="8" style="text-align:right; font-weight:bold"> 
                       <input type="hidden" name="update">
                       <span>SUM WEIGHT</span>
                  </td>
                  <td style="border-right-style:hidden;" align="center"><b><?php echo $s; ?></b> </td>
                  <input type="hidden" name="total_sum" value="<?php echo $s; ?>">
                  <td></td>
                </tr>  
            </tbody>
          </table>
         </div>
      </div>
    </div>
  </div>
<?php } ?>
</form>

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
              <input type="number" min="1" class="form-control" name="qty">
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


<?php include_once('layouts/footer.php'); ?>

