<?php
  $page_title = 'Edit Account';
  require_once('includes/load.php');
   page_require_level(3);
?>
<?php

  if(isset($_POST['submit'])) {
    $id[]        = $_SESSION['id_employer'];
    $id_employer = $id[0]['id_employer'];
    $Allow       = array('png','jpg');
    $name        = $_FILES['file_upload']['name'];
    $x           = explode('.', $name);
    $ekstension  = strtolower(end($x));
    $size        = $_FILES['file_upload']['size'];
    $file_tmp    = $_FILES['file_upload']['tmp_name'];
    $directory   = "uploads/users/$name";

    if(in_array($ekstension, $Allow) === true) {
      move_uploaded_file($file_tmp,$directory); 
      if($ukuran < 1044070) {       
        $sql = "UPDATE employer SET image ='{$name}' WHERE id_employer='{$id_employer}'";
        $result = $db->query($sql);
        if($result) {
          $session->msg('s',"Photo Updated");
            redirect('edit_account.php', false);
        } else {
          $session->msg('d',"Sorry Failed To Updated ");
          redirect('edit_account.php', false);
        }
      } else {
          $session->msg('d',"The Size Is Too Big");
          redirect('edit_account.php', false);
        }
    } else {
      $session->msg('d',"The Extension Not Allowed");
      redirect('edit_account.php', false);
      }
  }








//update user image
  if(isset($_POST['submit'])) {
  $photo = new Media();
  $user_id = $_POST['id_employer'];
  $photo->upload($_FILES['file_upload']);
  if($photo->process_user($user_id)){
    $session->msg('s','photo has been uploaded.');
    redirect('edit_account.php');
    } else{
      $session->msg('d',join($photo->errors));
      redirect('edit_account.php');
    }
  }
?>
<?php
 //update user other info
  if(isset($_POST['update'])){
    $req_fields = array('name','username' );
    validate_fields($req_fields);
    if(empty($errors)){
           $id[] = $_SESSION['id_employer'];
    $id_employer = $id[0]['id_employer'];
           $name = remove_junk($db->escape($_POST['name']));
       $username = remove_junk($db->escape($_POST['username']));

            $sql = "UPDATE employer SET nm_employer ='{$name}', username ='{$username}' WHERE id_employer='{$id_employer}'";
            $result = $db->query($sql);
          if($result && $db->affected_rows() === 1){
            $session->msg('s',"Acount updated ");
            redirect('edit_account.php', false);
          } else {
            $session->msg('d',' Sorry failed to updated!');
            redirect('edit_account.php', false);
          }
    } else {
      $session->msg("d", $errors);
      redirect('edit_account.php',false);
    }
  }
?>

<!-- UPDATE PASSWORD -->
<?php
  if(isset($_POST['updatePass'])){

    $req_fields = array('new-password','old-password','id' );
    validate_fields($req_fields);

    if(empty($errors)){

             if(sha1($_POST['old-password']) !== current_user()['password'] ){
               $session->msg('d', "Your old password not match");
               redirect('edit_account.php',false);
             }

            $id  = $_POST['id'];
            $new = remove_junk($db->escape(sha1($_POST['new-password'])));
            $sql = "UPDATE employer SET password ='{$new}' WHERE id_employer='{$db->escape($id)}'";
            $result = $db->query($sql);
                if($result && $db->affected_rows() === 1):
                  $session->logout();
                  $session->msg('s',"Login with your new password.");
                  redirect('index.php', false);
                else:
                  $session->msg('d',' Sorry failed to updated!');
                  redirect('edit_password.php', false);
                endif;
    } else {
      $session->msg("d", $errors);
      redirect('edit_password.php',false);
    }
  }
?>
<!-- END UPDATE PASSWORD -->

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
  <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-heading clearfix">
            <span class="glyphicon glyphicon-camera"></span>
            <span>Change My photo</span>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-4">
                <img class="img-circle img-size-2" src="uploads/users/<?php echo $user['image'];?>" alt="">
            </div>
            <div class="col-md-8">
              <form class="form" action="edit_account.php" method="POST" enctype="multipart/form-data">
              <div class="form-group">
                <input type="file" name="file_upload" multiple="multiple" class="btn btn-default btn-file"/>
              </div>
              <div class="form-group">
                <input type="hidden" name="id_employer" value="<?php echo $user['id_employer'];?>">
                 <button type="submit" name="submit" class="btn btn-warning">Change</button>
              </div>
             </form>
            </div>
          </div>
        </div>
      </div>
  </div>
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <span class="glyphicon glyphicon-edit"></span>
        <span>Edit My Account</span>
      </div>
      <div class="panel-body">
          <form method="post" action="edit_account.php?id=<?php echo $user['id_employer'];?>" class="clearfix">
            <div class="form-group">
                  <label for="name" class="control-label">Name</label>
                  <input type="name" class="form-control" name="name" value="<?php echo remove_junk(ucwords($user['nm_employer'])); ?>">
            </div>
            <div class="form-group">
                  <label for="username" class="control-label">Username</label>
                  <input type="text" class="form-control" name="username" value="<?php echo remove_junk(ucwords($user['username'])); ?>">
            </div>
            <div class="form-group clearfix">
              <button type="button" data-target="#changePassword" data-toggle="modal" title="change password" class="btn btn-danger pull-right">Change Password</button>
              <button type="submit" name="update" class="btn btn-info">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Change Password -->
<div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Change Password</h4>
        </div>
        <div class="modal-body">
          <form method="post" action="edit_account.php" class="clearfix">
            <div class="form-group">
                  <label for="newPassword" class="control-label">New password</label>
                  <input type="password" class="form-control" name="new-password" placeholder="New password">
            </div>
            <div class="form-group">
                  <label for="oldPassword" class="control-label">Old password</label>
                  <input type="password" class="form-control" name="old-password" placeholder="Old password">
            </div>
            <div class="form-group clearfix">
              <input type="hidden" name="id" value="<?php echo $user['id_employer'];?>">      
            </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="updatePass" class="btn btn-info">Change</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php include_once('layouts/footer.php'); ?>
