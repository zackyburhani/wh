<?php
  $page_title = 'All User';
  require_once('includes/load.php');
?>
<?php
// Checkin What level user has permission to view this page
 page_require_level(1);
//pull out all user form database
 $all_users = find_all_user();
?>
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
         <a href="add_user.php" class="btn btn-info pull-right"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add New User</a>
         <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#addUser"><span class="glyphicon glyphicon-plus"></span> Add New User
        </button>
      </div>
     <div class="panel-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">#</th>
            <th>Name </th>
            <th>Username</th>
            <th class="text-center" style="width: 15%;">User Role</th>
            <th class="text-center" style="width: 10%;">Status</th>
            <th style="width: 20%;">Last Login</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_users as $a_user): ?>
          <tr>
           <td class="text-center"><?php echo count_id();?></td>
           <td><?php echo remove_junk(ucwords($a_user['nm_employer']))?></td>
           <td><?php echo remove_junk(ucwords($a_user['username']))?></td>
           <td class="text-center"><?php echo remove_junk(ucwords($a_user['id_position']))?></td>
           <td class="text-center">
           <?php if($a_user['status'] === '1'): ?>
            <span class="label label-success"><?php echo "Active"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Deactive"; ?></span>
          <?php endif;?>
           </td>
           <td><?php echo read_date($a_user['last_login'])?></td>
           <td class="text-center"> 
             <div class="btn-group">
                <a href="edit_user.php?id=<?php echo (int)$a_user['id_employer'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>
                <a href="delete_user.php?id=<?php echo (int)$a_user['id_employer'];?>" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                  <i class="glyphicon glyphicon-remove"></i>
                </a>
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel">Modal title</h4>
        
      </div>
      <div class="modal-body">
        <form method="post" action="users.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="nm_employer" placeholder="Full Name">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name ="password"  placeholder="Password">
            </div>
            <div class="form-group">
              <label for="level">User Role</label>
                <select class="form-control" name="id_position">
                  <?php 

                  $getGroups = "SELECT * FROM user_groups where";
                  $groups=$db->query($getAllMenusName);

                  ?>
                  <?php foreach ($groups as $group ):?>
                   <option value="2"><?php echo ucwords($group['nm_position']);?></option>
                <?php endforeach;?>
                </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="add_user" class="btn btn-primary">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>

<?php
  if(isset($_POST['add_user'])){

   $req_fields = array('nm_employer','username','password','id_position' );
   validate_fields($req_fields);

   if(empty($errors)){
       $nm_employer   = remove_junk($db->escape($_POST['nm_employer']));
       $username      = remove_junk($db->escape($_POST['username']));
       $password      = remove_junk($db->escape($_POST['password']));
       $id_position   = (int)$db->escape($_POST['id_position']);
       $password = sha1($password);
        $query = "INSERT INTO employer (";
        $query .="nm_employer,username,password,id_position,status";
        $query .=") VALUES (";
        $query .=" '{$nm_employer}', '{$username}', '{$password}', '{$id_position}','1'";
        $query .=")";
        if($db->query($query)){
          //sucess
          $session->msg('s',"User account has been creted! ");
          redirect('user.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create account!');
          redirect('user.php', false);
        }
   } else {
     $session->msg("d", $errors);
      redirect('user.php',false);
   }
 }
?>



<?php include_once('layouts/footer.php'); ?>
