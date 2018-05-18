<?php
  $page_title = 'Add Warehouse';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all1('warehouse')
?>
<?php
 if(isset($_POST['add_warehouse'])){
   $req_field = array('warehouse-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['warehouse-name']));
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
<?php include_once('layouts/header.php'); ?>
<!-- MESSAGE -->
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<!-- END MESSAGE -->

<div class="row">
    <!-- NEW DATA WAREHOUSE -->
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Warehouse</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="add_warehouse.php">
            <div class="form-group">
                <label for="warehouse-name">Warehouse Name</label>
                <input type="text" class="form-control" name="warehouse-name" placeholder="Warehouse Name">
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" class="form-control" name="country" placeholder="Country of Origin">
            </div>
            <div class="form-group">
                <label for="email">Address</label>
                <textarea type="text" class="form-control" name="address" placeholder="Address"></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status">
                  <option>Production</option>
                  <option>Not Production</option>
                </select>
            </div>
            <div class="form-group">
                <label for="heavymax">Heavy Max</label>
                <input type="number" class="form-control" name="heavymax" placeholder="Heavy Max">
            </div>
            <div class="form-group">
                <label for="consumed">Consumed</label>
                <input type="number" class="form-control" name="consumed" placeholder="Consumed">
            </div>
            <button type="submit" name="add_warehouse" class="btn btn-primary">Add Warehouse</button>
          </form>
        </div>
      </div>
    </div>
    <!-- END NEW DATA WAREHOUSE -->

    <!-- ALL DATA WAREHOUSE -->
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>All Warehouse</span>
          </strong>
        </div><!-- PANEL HEADING -->
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th class="text-center" style="width: 100px;">Warehouse</th>
                    <th class="text-center" style="width: 100px;">Country</th>
                    <th class="text-center" style="width: 100px;">Address</th>
                    <th class="text-center" style="width: 50px;">Status</th>
                    <th class="text-center" style="width: 50px;">Heavy Max</th>
                    <th class="text-center" style="width: 50px;">Consumed</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['nm_warehouse'])); ?></a></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['country'])); ?></a></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['address'])); ?></a></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['status'])); ?></a></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['heavy_max'])); ?></a></td>
                    <td class="text-center"><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['heavy_consumed'])); ?></a></td>
                    <td class="text-center">
                      
                    <button class="btn btn-md btn-warning" data-toggle="modal" href="#" data-target="#myModal"><span class="glyphicon glyphicon-wrench"></span></button>
                        
                    <button class="btn btn-md btn-danger" data-toggle="modal" href="#" data-target="#myModal"><span class="glyphicon glyphicon-trash"></span></button> 
                    </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div><!-- PANEL BODY -->
    </div><!-- PANEL DEFAULT -->
  </div><!-- END ALL DATA WAREHOUSE -->
</div><!-- END ROW -->

<!-- MODAL BEGINNING -->   
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- MODAL CONTENT-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Warehouse</h4>
      </div>
      <div class="modal-body">
        <form method="post" action="edit_warehouse.php?id=<?php echo (int)$categorie['id'];?>">
           <div class="form-group">
               <label for="warehouse-name">Warehouse Name</label>
               <input type="text" class="form-control" name="warehouse-name" value="<?php echo remove_junk(ucfirst($cat['nm_warehouse']));?>">
           </div>
           <div class="form-group">
               <label for="country">Country</label>
               <input type="text" class="form-control" name="country" value="<?php echo remove_junk(ucfirst($cat['country']));?>">
           </div>
           <div class="form-group">
                <label for="address">Address</label>
                <textarea type="text" class="form-control" name="address" ><?php echo remove_junk(ucfirst($cat['address']));?></textarea>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" name="status" value="<?php echo remove_junk(ucfirst($cat['status']));?>">
            </div>
            <div class="form-group">
                <label for="heavymax">Heavy Max</label>
                <input type="number" class="form-control" name="heavymax" value="<?php echo remove_junk(ucfirst($cat['heavy_max']));?>">
            </div>
            <div class="form-group">
                <label for="consumed">Consumed</label>
                <input type="number" class="form-control" name="consumed" value="<?php echo remove_junk(ucfirst($cat['heavy_consumed']));?>">
            </div>
           <button type="submit" name="edit_warehouse" class="btn btn-primary">Update Warehouse</button>
       </form>
      </div><!-- MODAL BODY -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- MODAL CONTENT -->
  </div><!-- MODAL DIALOG -->
</div><!-- END MODAL BEGINNING -->
<?php include_once('layouts/footer.php'); ?>
