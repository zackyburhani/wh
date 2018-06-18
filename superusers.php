<?php
  $page_title = 'Administrator Inter IKEA';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_users = find_all_admin();
 $user = current_user();
?>

<!-- ADD NEW ADMINISTRATOR -->
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('nm_employer','username','password','password2','id_position','id_warehouse');
   validate_fields($req_fields);

   if(empty($errors)){
       $id_employer   = autonumber('id_employer','employer');
       $nm_employer   = remove_junk($db->escape($_POST['nm_employer']));
       $username      = remove_junk($db->escape($_POST['username']));
       $password      = remove_junk($db->escape($_POST['password']));
       $id_position   = remove_junk($db->escape($_POST['id_position']));
       $id_warehouse  = remove_junk($db->escape($_POST['id_warehouse']));
       $status        = 1;

       if(find_by_UsernameInter($_POST['username'],$user['id_warehouse']) === false ){
         $session->msg('d','<b>Sorry!</b> Username Already In Database!');
         redirect('superusers.php', false);
       }

       $password = sha1($password);
        $query = "INSERT INTO employer (";
        $query .="id_employer,nm_employer,username,password,id_position,status,id_warehouse";
        $query .=") VALUES (";
        $query .=" '{$id_employer}','{$nm_employer}', '{$username}', '{$password}', '{$id_position}','{$status}','{$id_warehouse}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"User account has been creted! ");
          redirect('superusers.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create account!');
          redirect('superusers.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('superusers.php',false);
   }
 }
 $all_warehouse = find_all_position('warehouse');
 $all_position  =  find_all_Position_admin();
 $find_adminName = find_adminName($user['id_warehouse']);
?>
<!-- END NEW ADMINISTRATOR -->

<!-- UPDATE DATA ADMINISTRATOR -->
<?php
  if(isset($_POST['update_user'])){

    $req_fields = array('nm_employer','username','id_position' );
    validate_fields($req_fields);
    if(empty($errors)){

      $id_employer  = remove_junk($db->escape($_POST['id_employer']));
      $nm_employer  = remove_junk($db->escape($_POST['nm_employer']));
      $username     = remove_junk($db->escape($_POST['username']));
      $password     = remove_junk($db->escape($_POST['password']));
      $id_position  = remove_junk($db->escape($_POST['id_position']));
      $status       = remove_junk($db->escape($_POST['status']));
      $id_warehouse = remove_junk($db->escape($_POST['id_warehouse']));

      if(find_by_UsernameInter($_POST['username'],$user['id_warehouse']) === false ){
         $session->msg('d','<b>Sorry!</b> Username Already In Database!');
         redirect('superusers.php', false);
       }

      if($password == null){
        $query  = "UPDATE employer SET id_employer='{$id_employer}',nm_employer='{$nm_employer}',username='{$username}',id_position='{$id_position}',status='{$status}',id_warehouse='{$id_warehouse}' WHERE id_employer='{$id_employer}'";
        $result = $db->query($query);
      } else {
        $password = sha1($password);
        $queryPass  = "UPDATE employer SET id_employer='{$id_employer}',nm_employer='{$nm_employer}',username='{$username}',password='{$password}',id_position='{$id_position}',status='{$status}',id_warehouse='{$id_warehouse}' WHERE id_employer='{$id_employer}'";

        $result = $db->query($queryPass);
      }

      if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"User Has Been Updated! ");
          redirect('superusers.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated User!');
          redirect('superusers.php', false);
        }
   } else {
     $session->msg("d", $errors);
    redirect('superusers.php', false);
   }
 }
?>
<!-- END UPDATE DATA ADMINISTRATOR -->

<!-- DELETE DATA ADMINISTRATOR -->
<?php
  if(isset($_POST['delete_user'])){
    $id_employer = remove_junk($db->escape($_POST['id_employer']));
    $image       = remove_junk($db->escape($_POST['image']));
    
    $directory = "uploads/users/$image";
    unlink($directory);

    //validation connected foreign key
    $employer = find_all_idEmployee($id_employer);
    foreach ($employer as $data) {
      $id_employer2 = $data['id_employer'];
    }
    if($id_employer == $id_employer2){
      $session->msg("d","The Field Connected To Other Key.");
      redirect('superusers.php');
    }
    
    $delete_id   = delete('id_employer','employer',$id_employer);
    if($delete_id){
      $session->msg("s","User has been deleted.");
      redirect('superusers.php');
    } else {
      $session->msg("d","User deletion failed");
      redirect('superusers.php');
    }
  }
?>
<!-- END DELETE DATA ADMINISTRATOR -->

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Administrators</span>
       </strong>
       <?php
        if ($user['level_user']==0 || $user['level_user']==1) { ?>
         <button type="button" title="Add New Administrator" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addUser"><span class="glyphicon glyphicon-plus"></span> Add New Administrator
        </button>
       <?php } ?>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped" id="tableUser">
        <thead>
          <tr>
            <th class="text-center" style="width: 5%;">No.</th>
            <th class="text-center" style="width: 10%;">Name </th>
            <th class="text-center" style="width: 10%;">Username</th>
            <th class="text-center" style="width: 10%;">User Role</th>
            <th class="text-center" style="width: 5%;">Status</th>
            <th class="text-center" style="width: 10%;">Last Login</th>
            <th class="text-center" style="width: 9%;">Warehouse</th>
            <th class="text-center" style="width: 7%;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; ?>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo $no++.".";?></td>
           <td><?php echo remove_junk(ucwords($a_user['nm_employer']))?></td>
           <td><?php echo remove_junk($a_user['username'])?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['nm_position']))?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Active"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Deactive"; ?></span>
          <?php endif;?>
           </td>
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td><?php echo remove_junk($a_user['nm_warehouse'])?></td>
           <td class="text-center">
              <button data-target="#updateUser<?php echo $a_user['id_employer'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
              </button>
            <?php
                if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) { ?>
              <button data-target="#deleteUser<?php echo $a_user['id_employer'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete">
                  <i class="glyphicon glyphicon-trash"></i>
              </button>
            <?php } ?>
              </a>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>

<!-- Entry Data ADMINISTRATOR -->
<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-user"></span>  Add New Administrator For Inter IKEA</h4>

      </div>
      <div class="modal-body">
        <form method="post" action="superusers.php">
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" name="nm_employer" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name ="password"  placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="password">Confirm Password</label>
              <input type="password" class="form-control" name ="password2"  placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
              <label for="level">User Role</label>
                <select class="form-control" name="id_position">
                  <?php foreach($find_adminName as $name) : ?>
                    <option value="<?php echo ucwords($name['id_position']);?>"><?php echo ucwords($name['nm_position']);?></option>
                  <?php endforeach;?>
                  </select>
            </div>
            <div class="form-group">
              <label for="level">Warehouse</label>
                <select class="form-control" name="id_warehouse">
                  <?php foreach ($all_warehouse as $group ):?>
                   <option value="<?php echo ucwords($group['id_warehouse']);?>"><?php echo ucwords($group['nm_warehouse']);?></option>
                <?php endforeach;?>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
            <button type="submit" name="add_user" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- END Entry Data ADMINISTRATOR -->

<!-- Update Entry Data ADMINISTRATOR -->
<?php foreach($all_users as $a_user): ?>
  <div class="modal fade" id="updateUser<?php echo $a_user['id_employer'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-user"></span> Update Data Administrator</h4>

      </div>
      <div class="modal-body">
        <form method="post" action="superusers.php">
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_user['id_employer'])); ?>" name="id_employer">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" value="<?php echo remove_junk(ucwords($a_user['nm_employer'])); ?>" name="nm_employer" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username" value="<?php echo remove_junk($a_user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name ="password"  placeholder="Let It Empty If Do Not Want To Update The Password">
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" class="form-control" name ="password2"  placeholder="Let It Empty If Do Not Want To Update The Password">
            </div>

            <div class="form-group">
              <label for="level">User Role</label>
                <?php foreach($find_adminName as $name) : ?>
                  <input type="hidden" class="form-control" name="id_position" value="<?php echo remove_junk($name['id_position']); ?>">
                  <input type="text" class="form-control" readonly value="<?php echo remove_junk($name['nm_position']); ?>">
                <?php endforeach;?>
            </div>

            <div class="form-group">
              <label for="level">Status</label>
                <select class="form-control" name="status">
                   <option <?php if( $a_user['status']=='1'){echo "selected"; } ?> value="1">Active</option>
                   <option <?php if( $a_user['status']=='2'){echo "selected"; } ?> value="2">Deactive</option>
                </select>
            </div>
            <div class="form-group">
              <label for="level">Warehouse</label>
                <select class="form-control" name="id_warehouse">
                  <?php foreach ($all_warehouse as $group ):?>
                   <option <?php if( $group['id_warehouse']==$a_user['id_warehouse']){echo "selected"; } ?> value="<?php echo ucwords($group['id_warehouse']);?>"><?php echo ucwords($group['nm_warehouse']);?></option>
                <?php endforeach;?>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
            <button type="submit" name="update_user" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span>  Update</button>
          </div>
        </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- END Update Entry Data ADMINISTRATOR -->

<!-- Delete Modal -->
<?php foreach($all_users as $a_user): ?>
  <div class="modal fade" id="deleteUser<?php echo $a_user['id_employer'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Confirm Delete</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete <b><u><?php echo remove_junk(ucwords($a_user['nm_employer'])); ?></u></b> ?
        <form method="post" action="superusers.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_user['id_employer'])); ?>" name="id_employer">
            <input type="hidden" class="form-control" value="<?php echo $a_user['image']; ?>" name="image">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_user" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>


<?php include_once('layouts/footer.php'); ?>
