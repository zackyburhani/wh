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
   if(empty($errors)){
      $sql  = "INSERT INTO warehouse (name_warehouse)";
      $sql .= " VALUES ('{$cat_name}')";

      $getAllWarehouseName = "SELECT name_warehouse FROM warehouse where name_warehouse = '$cat_name'";
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
            <span>Add New Warehouse</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="add_warehouse.php">
            <div class="form-group">
                <input type="text" class="form-control" name="warehouse-name" placeholder="Warehouse Name">
            </div>
            <button type="submit" name="add_warehouse" class="btn btn-primary">Add Warehouse</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Warehouse</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Warehouse</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
           <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><a href="warehouse.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['name_warehouse'])); ?></a></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_warehouse.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_warehouse.php?id=<?php echo (int)$cat['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
