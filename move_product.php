<?php
error_reporting(0);
  $page_title = 'Move Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
   if (isset($_GET['id_location'])) {
    $products = join_product_table1();
   } else {
    $products = join_product_table();
   }
  
  $all_warehouse = find_all1('location');
  
// if (isset($_POST['submit'])) {
//   $id_prod = $_POST[''];
//   $name = $_POST['name'];
//   $qty = $_POST['quantity'];
//   $ware = $_POST['warehouse'];
//   $id_ware = $_POST['id_ware'];

//   for($x = 0; $x < count($id_ware); $x++) {
//     if ($ware[$x] != "" || $ware[$x] != "0") {
//       $query = "SELECT * FROM warehouse WHERE nm_warehouse ='$name[$x]' AND id_warehouse=$ware[$x]";
//       $result = $db->query($query);
//       if ($result->num_rows > 0) {
//         /*while($row = $result->fetch_assoc()) {
//           $quantity = $row['quantity'] + $qty[$x];
//         }*/
//         $query1="UPDATE item SET stock=stock+$qty[$x] WHERE nm_item='$name[$x]' AND id_warehouse=$ware[$x]";
//         $db->query($query1);
//         $query1="UPDATE item SET stock=stock-$qty[$x] WHERE id_item'$id_prod[$x]' AND id_warehouse=$id_ware";
//         $db->query($query1);
//       } else {
//         $query1 = "SELECT * FROM item WHERE id_item=$id_prod[$x] LIMIT 1";
//         $result1 = $db->query($query1);
//         if ($result1->num_rows > 0) {
//           while($row = $result1->fetch_assoc()) {
//             $date = make_date();
//             //$query2="INSERT INTO products (name, quantity, buy_price, sale_price, categorie_id, date, warehouse_id) VALUES('$row[name]', $qty[$x], $row[buy_price], $row[sale_price], $row[categorie_id], '$date', $ware[$x])";
//             //$db->query($query2);
//           }
//         }
//       }
//     }
//   }
// }
  
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
            if(isset($_GET['id_location'])){
              $id = $_GET["id_location"];
            }else{
              $id = 0;
            }
            ?>
              <select class="form-control" name="id">
                    <option value=""> Select Warehouse</option>
                   <?php  foreach ($all_warehouse as $ware): ?>
                     <option value="<?php echo (int)$ware['id']; ?>" <?php if($_GET['id'] === $ware['id']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($ware['name_warehouse']); ?></option>
                   <?php endforeach; ?>
                 </select>
                 <button type="submit" class="btn btn-danger">Sort</button>
         </div>
       </form>
       </div>
     </div>
    </div>
  </div>
  <?php
 if(isset($_GET['show_product'])){
  $warehouse_id = $_GET["product_warehouse"];
  $prod_warehouse = find_prod_warehouse('location');
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
          <input type="text" name="id_ware" value="<?php echo (int)$warehouse_id; ?>">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Id Location</th>
                    <th class="text-center" style="width: 100px;">Unit</th>
                    <th class="text-center" colspan="2">Actions</th>
                </tr>
            </thead>
           <tbody>
              <?php foreach ($prod_warehouse as $cat):?>
                <tr>
                    <td class="text-center"><input type="hidden" name="id_location[]" value="<?php echo (int)$cat['id_location']; ?>"><input type="hidden" name="Unit[]" value="<?php echo $cat['unit']; ?>"><?php echo count_id();?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                      <select class="form-control" name="warehouse[]">
                        <option value="0"> Select a Warehouse</option>
                        <?php  foreach ($all_warehouse as $ware): 
                          if ((int)$ware['id_warehouse'] != $warehouse_id) {?>
                            <option value="<?php echo (int)$ware['id_warehouse']; ?>">
                            <?php echo remove_junk($ware['nm_warehouse']); ?></option>
                        <?php
                          }
                          
                        endforeach; ?>
                    </select>
                    </td>

                </tr>
              <?php endforeach; ?>
              <tr>
                      <td colspan="5"><button type="submit" name="submit" class="btn btn-danger"><span class="glyphicon glyphicon-transfer"></span>&nbsp;&nbsp;&nbsp;Move</button></td>
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
