<?php
  $page_title = 'Input Menu';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_menu = find_all1('sidebar');
?>

<?php
   //add menu
   if(isset($_POST['add_menu'])){
   $req_field = array('menu-name');
   validate_fields($req_field);
   $menu_name = remove_junk($db->escape($_POST['menu-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO sidebar (name)";
      $sql .= " VALUES ('{$menu_name}')";

      $getAllMenusName = "SELECT name FROM sidebar where name = '$menu_name'";
      $exist=$db->query($getAllMenusName) or die(mysql_error());
      if(mysqli_num_rows($exist)>0)
      { 
        $session->msg("d", "Menu Is Exist");
        redirect('add_menu.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Menu");
          redirect('add_menu.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_menu.php',false);
        }
      }      
   } else {
     $session->msg("d", $errors);
     redirect('add_menu.php',false);
   }
 }
?>

<?php
   //add submenu
   if(isset($_POST['add_submenu'])){
   $req_field = array('submenu-name','id_menu-name');
   validate_fields($req_field);
   $id_menu = remove_junk($db->escape($_POST['id_menu-name']));
   $submenu_name = remove_junk($db->escape($_POST['submenu-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO sub_sidebar (name_sub,id_menu)";
      $sql .= " VALUES ('{$submenu_name}','{$id_menu}')";

      $getAllSubMenusName = "SELECT name_sub FROM sub_sidebar where name_sub = '$submenu_name'";
      $exist=$db->query($getAllSubMenusName) or die(mysql_error());
      if(mysqli_num_rows($exist)>0)
      { 
        $session->msg("d", "Sub Menu Is Exist");
        redirect('add_menu.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Sub Menu");
          redirect('add_menu.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('add_menu.php',false);
        }
      }      
   } else {
     $session->msg("d", $errors);
     redirect('add_menu.php',false);
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
    <div class="col-lg-6">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Add New Menu</span>
             </strong>
            </div>
            <div class="panel-body">
              <form method="post" action="add_menu.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="menu-name" placeholder="Menu Name">
                </div>
                <button type="submit" name="add_menu" class="btn btn-primary">Add Menu</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Add New Sub menu</span>
             </strong>
            </div>
            <div class="panel-body">
              <form method="post" action="add_menu.php">
                <div class="form-group">
                  <div class="custom-select">
                    <select class="form-control" name="id_menu-name">
                      <?php if($all_menu == null) { ?>
                        <option value="">-</option>
                          <?php } else { ?>
                            <?php foreach($all_menu as $row){ ?>
                              <option value="<?php echo remove_junk($row['id']); ?>"><?php echo remove_junk($row['name']); ?></option>
                            <?php } ?>  
                          <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="submenu-name" placeholder="Menu Name">
                </div>

                <button type="submit" name="add_submenu" class="btn btn-primary">Add Sub Menu</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Menus</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="CategoriesTable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th>Menu</th>
                  <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_menu as $menu):?>
                <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td><?php echo remove_junk(ucfirst($menu['name'])); ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="edit_categorie.php?id=<?php echo (int)$menu['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_categorie.php?id=<?php echo (int)$menu['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
