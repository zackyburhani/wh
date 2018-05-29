<?php
  $page_title = 'Add Location';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all1('location')
?>
<?php
 if(isset($_POST['add_location'])){
   $req_field = array('location-name');
   validate_fields($req_field);
   $cat_name = remove_junk($db->escape($_POST['location-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO location (name_location)";
      $sql .= " VALUES ('{$cat_name}')";

      $getAllLocationName = "SELECT name_location FROM location where name_location = '$cat_name'";
      $ada=$db->query($getAllLocationName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Location Is Exist");
        redirect('add_lokasi.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Location");
          redirect('add_lokasi.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_lokasi.php',false);
        }
      }
   } else {
     $session->msg("d", $errors);
     redirect('add_lokasi.php',false);
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
            <span>Add Location</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="add_warehouse.php">
            <div class="form-group">
                <input type="text" class="form-control" name="warehouse-name" placeholder="Location name">
            </div>
            <button type="submit" name="add_warehouse" class="btn btn-primary">Add Location</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Location</span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Location</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
           <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><a href="location.php?id=<?php echo (int)$cat['id'];?>"><?php echo remove_junk(ucfirst($cat['name_location'])); ?></a></td>
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
x`