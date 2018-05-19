<?php include_once('includes/load.php'); ?>
<?php
$req_fields = array('username','password' );
validate_fields($req_fields);
$username = remove_junk($_POST['username']);
$password = remove_junk($_POST['password']);

if(empty($errors)){
  $user_id = authenticate($username, $password);
  
  if($user_id){
    $userStatus = $user_id['status'];
    if($userStatus == 1){
      //create session with id
      $session->login($user_id);
      //Update Sign in time
      $userKey = $user_id['id_employer'];
      updateLastLogIn($userKey);
      $session->msg("s", "Welcome to OSWA-INV.");
      redirect('home.php',false);
    } else { 
      $session->msg("d", "Your Account Is Deactive");
      redirect('index.php',false);
    }  
  } else {
    $session->msg("d", "Sorry Username/Password incorrect.");
      redirect('index.php',false);
  } 
    

} else {
   $session->msg("d", $errors);
   redirect('index.php',false);
}

?>
