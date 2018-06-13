<?php
  ob_start();
  require_once('includes/load.php');
  if($session->isUserLoggedIn(true)) { redirect('home.php', false);}
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="icon" type="image/png" href="img/ikea-icon.png"/>
<link rel="stylesheet" type="text/css" href="libs/css/login.css">
<title>Log In Administrator</title>
<!------ Include the above in your HEAD tag ---------->

<div class="wrapper fadeInDown">
  <div id="formContent">
    <?php echo display_msg($msg); ?>
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div class="fadeIn first">
      <img src="img/logo.png" id="icon" alt="User Icon"/ style="padding-top: 50px; padding-bottom: 20px">
    </div>
    <!-- Login Form -->
    <form method="post" action="auth.php" class="clearfix">
      <input type="text" class="fadeIn second" name="username" placeholder="Username" required="">
      <input type="password" class="fadeIn third" name="password" placeholder="Password" required="">
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>
  </div>
</div>


<script type="text/javascript" src="8bit.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="libs/js/functions.js"></script>