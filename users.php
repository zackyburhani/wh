<?php
  $page_title = 'All User';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_users = find_all_employee();
?>


<!-- ADD NEW USER -->
<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('nm_employer','username','password','password2','id_position' );
   validate_fields($req_fields);

   if(empty($errors)){
       $id_employer   = autonumber('id_employer','employer');
       $nm_employer   = remove_junk($db->escape($_POST['nm_employer']));
       $username      = remove_junk($db->escape($_POST['username']));
       $password      = remove_junk($db->escape($_POST['password']));
       $id_position   = remove_junk($db->escape($_POST['id_position']));
       $status        = 1;
       $password = sha1($password);
        $query = "INSERT INTO employer (";
        $query .="id_employer,nm_employer,username,password,id_position,status";
        $query .=") VALUES (";
        $query .=" '{$id_employer}','{$nm_employer}', '{$username}', '{$password}', '{$id_position}','{$status}'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"User account has been creted! ");
          redirect('users.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create account!');
          redirect('users.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('users.php',false);
   }
 }
 $all_position = find_all_position('position');
?>
<!-- END NEW USER -->

<!-- UPDATE DATA USER -->
<?php
  if(isset($_POST['update_user'])){

    $req_fields = array('nm_employer','username','id_position' );
    validate_fields($req_fields);
    if(empty($errors)){

      $id_employer = remove_junk($db->escape($_POST['id_employer']));
      $nm_employer = remove_junk($db->escape($_POST['nm_employer']));
      $username    = remove_junk($db->escape($_POST['username']));
      $password    = remove_junk($db->escape($_POST['password']));
      $id_position = remove_junk($db->escape($_POST['id_position']));
      $status      = remove_junk($db->escape($_POST['status']));

      if($password == null){      
        $query  = "UPDATE employer SET id_employer='{$id_employer}',nm_employer='{$nm_employer}',username='{$username}',id_position='{$id_position}',status='{$status}' WHERE id_employer='{$id_employer}'";
        $result = $db->query($query);
      } else {
        $password = sha1($password);
        $queryPass  = "UPDATE employer SET id_employer='{$id_employer}',nm_employer='{$nm_employer}',username='{$username}',password='{$password}',id_position='{$id_position}',status='{$status}' WHERE id_employer='{$id_employer}'";

        $result = $db->query($queryPass);
      }

      if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"User Has Been Updated! ");
          redirect('users.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated User!');
          redirect('users.php', false);
        }
   } else {
     $session->msg("d", $errors);
    redirect('users.php', false);
   }
 }
?>
<!-- END UPDATE DATA USER -->

<!-- DELETE DATA USER -->
<?php
  if(isset($_POST['delete_user'])){
    $id_employer = remove_junk($db->escape($_POST['id_employer']));
    $delete_id   = delete('id_employer','employer',$id_employer);
    if($delete_id){
      $session->msg("s","User has been deleted.");
      redirect('users.php');
    } else {
      $session->msg("d","User deletion failed");
      redirect('users.php');
    }  
  }
?>
<!-- END DELETE DATA USER -->

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
          <span>Users</span>
       </strong>
         <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addUser"><span class="glyphicon glyphicon-plus"></span> Add New User
        </button>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped" id="tableUser">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No.</th>
            <th>Name </th>
            <th>Username</th>
            <th class="text-center" style="width: 15%;">User Role</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th style="width: 20%;">Last Login</th>
            <th class="text-center" style="width: 13  0px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
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
           <td class="text-center"> 
              <button data-target="#updateUser<?php echo (int)$a_user['id_employer'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
              </button>
              <button data-target="#deleteUser<?php echo (int)$a_user['id_employer'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Edit">
                  <i class="glyphicon glyphicon-trash"></i>
              </button>
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

<!-- Entry Data User -->
<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Add New User</h4>
        
      </div>
      <div class="modal-body">
        <form method="post" action="users.php">
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
                  <?php foreach ($all_position as $group ):?>
                   <option value="<?php echo ucwords($group['id_position']);?>"><?php echo ucwords($group['nm_position']);?></option>
                <?php endforeach;?>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="add_user" class="btn btn-primary">Save</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- END Entry Data User -->

<!-- Update Entry Data User -->
<?php foreach($all_users as $a_user): ?>
  <div class="modal fade" id="updateUser<?php echo (int)$a_user['id_employer'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Update Data User</h4>
        
      </div>
      <div class="modal-body">
        <form method="post" action="users.php">
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
                <select class="form-control" name="id_position">
                  <?php foreach ($all_position as $group ):?>
                   <option value="<?php echo ucwords($group['id_position']);?>"><?php echo ucwords($group['nm_position']);?></option>
                <?php endforeach;?>
                </select>
            </div>
            <div class="form-group">
              <label for="level">Status</label>
                <select class="form-control" name="status">
                   <option <?php if( $a_user['status']=='1'){echo "selected"; } ?> value="1">Active</option>
                   <option <?php if( $a_user['status']=='2'){echo "selected"; } ?> value="2">Deactive</option>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="update_user" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- END Update Entry Data User -->

<!-- Delete Modal -->
<?php foreach($all_users as $a_user): ?> 
  <div class="modal fade" id="deleteUser<?php echo (int)$a_user['id_employer'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <form method="post" action="users.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_user['id_employer'])); ?>" name="id_employer">
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
