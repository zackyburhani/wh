<?php
error_reporting(0);
  $page_title = 'Move Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_warehouse = find_all1('warehouse');
  
if (isset($_POST['submit'])) {
  $id_prod = $_POST['id'];
  $name = $_POST['name'];
  $qty = $_POST['quantity'];
  $ware = $_POST['warehouse'];
  $id_ware = $_POST['id_ware'];

  for($x = 0; $x < count($id_ware); $x++) {
    if ($ware[$x] != "" || $ware[$x] != "0") {
      $query = "SELECT * FROM products WHERE name='$name[$x]' AND warehouse_id=$ware[$x]";
      $result = $db->query($query);
      if ($result->num_rows > 0) {
        /*while($row = $result->fetch_assoc()) {
          $quantity = $row['quantity'] + $qty[$x];
        }*/
        $query1="UPDATE products SET quantity=quantity+$qty[$x] WHERE name='$name[$x]' AND warehouse_id=$ware[$x]";
        $db->query($query1);
        $query1="UPDATE products SET quantity=quantity-$qty[$x] WHERE id='$id_prod[$x]' AND warehouse_id=$id_ware";
        $db->query($query1);
      } else {
        $query1 = "SELECT * FROM products WHERE id=$id_prod[$x] LIMIT 1";
        $result1 = $db->query($query1);
        if ($result1->num_rows > 0) {
          while($row = $result1->fetch_assoc()) {
            $date = make_date();
            $query2="INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, date, warehouse_id) VALUES('$row[name]', $qty[$x], $row[buy_price], $row[sale_price], $row[categorie_id], '$date', $ware[$x])";
            $db->query($query2);
          }
        }
      }
    }
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
    <div class="col-md-5">
     <div class="panel panel-default">
       <div class="panel-heading">
         <strong>
           <span class="glyphicon glyphicon-th"></span>
           <span>Pilih Warehouse</span>
        </strong>
       </div>
       <div class="panel-body">
         <form method="get" action="move_product.php">
           <div class="form-group">
           <?php
            if(isset($_GET['show_product'])){
              $id = $_GET["product_warehouse"];
            }else{
              $id = 0;
            }
            ?>
              <select class="form-control" name="product_warehouse">
                    <option value=""> Select a Warehouse</option>
                   <?php  foreach ($all_warehouse as $ware): ?>
                     <option value="<?php echo (int)$ware['id']; ?>" <?php if($id === $ware['id']): echo "selected"; endif; ?>>
                       <?php echo remove_junk($ware['name_warehouse']); ?></option>
                   <?php endforeach; ?>
                 </select>
           </div>
           <button type="submit" name="show_product" class="btn btn-primary" value="Tampil">Tampil Product</button>
       </form>
       </div>
     </div>
    </div>
  </div>
  <?php
 if(isset($_GET['show_product'])){
  $warehouse_id = $_GET["product_warehouse"];
  $prod_warehouse = find_prod_warehouse('products');
?>
   <div class="row">
   <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Warehouse</span>
       </strong>
      </div>
        <div class="panel-body">
        <form method="post" action="move_product.php">
          <input type="hidden" name="id_ware" value="<?php echo (int)$warehouse_id; ?>">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Warehouse</th>
                    <th class="text-center" style="width: 100px;">Stok</th>
                    <th class="text-center" colspan="2">Actions</th>
                </tr>
            </thead>
           <tbody>
              <?php foreach ($prod_warehouse as $cat):?>
                <tr>
                    <td class="text-center"><input type="hidden" name="id[]" value="<?php echo (int)$cat['id']; ?>"><input type="hidden" name="name[]" value="<?php echo $cat['name']; ?>"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($cat['name'])); ?></td>
                    <td class="text-center"><?php echo remove_junk(ucfirst($cat['quantity'])); ?></td>
                    <td class="text-center">
                      <input type="number" class="form-control" name="quantity[]" min="0" max="<?php echo remove_junk(ucfirst($cat['quantity'])); ?>" style="width: 100px;" value="0">
                    </td>
                    <td>
                      <select class="form-control" name="warehouse[]">
                        <option value="0"> Select a Warehouse</option>
                        <?php  foreach ($all_warehouse as $ware): 
                          if ((int)$ware['id'] != $warehouse_id) {?>
                            <option value="<?php echo (int)$ware['id']; ?>">
                            <?php echo remove_junk($ware['name_warehouse']); ?></option>
                        <?php
                          }
                          
                        endforeach; ?>
                    </select>
                    </td>

                </tr>
              <?php endforeach; ?>
              <tr>
                      <td colspan="5"><button type="submit" name="submit" class="btn btn-danger">Move</button></td>
                </tr>
            </tbody>
          </table>
          </form>
       </div>
    </div>
    </div>
<?php
     }
?>
    
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
