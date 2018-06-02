<?php $user = current_user(); ?>
<!DOCTYPE html>
  <html lang="en">
    <head>
    <meta charset="UTF-8">
    <title><?php if (!empty($page_title))
           echo remove_junk($page_title);
            elseif(!empty($user))
           echo ucfirst($user['name']);
            else echo "Simple inventory System";?>
    </title>
    <style type="text/css">
      .btn-circle.btn-lg {
          width: 40px;
          height: 40px;
          padding: 5px 10px;
          font-size: 18px;
          line-height: 1.0;
          border-radius: 25px;
        }
    </style>
    <!-- <link rel="icon" href="img/icon.png" type="image/ico"> -->
    <link rel="icon" type="image/ico" href="img/icon.ico" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- DATATABLES -->
    <link rel="stylesheet" href="libs/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="libs/datatables/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="libs/datatables/jquery.dataTables.css">
    <link rel="stylesheet" href="libs/datatables/jquery.dataTables.min.css">
    <!-- END DATATABLES -->
  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left"> DATA WAREHOUSE </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("F j, Y, g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">
          <?php if ($user['id_warehouse'] =='0001'){ ?>
            <a href="approve1_po.php">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-danger"><?php echo $notif = find_all_PO_admin_notif($user['id_warehouse']); ?></span>
            </a>
          <?php } else { ?>
            <?php   $warehouse = find_by_id_warehouse('warehouse',$user['id_warehouse']); ?>
              <?php if($warehouse['status'] != 0) { ?>
                <a href="approve2_po.php">
                  <?php $notif = find_all_PO_destination_notif($user['id_warehouse']);  ?>
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-danger"><?php echo $notif ?></span>
              <?php } ?>
          <?php } ?>
            </a>

          <li class="profile">
            <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
              <img src="uploads/users/<?php echo $user['image'];?>" alt=" " class="img-circle img-inline">
              <span><?php echo remove_junk(ucfirst($user['nm_employer'])); ?> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                  <a href="profile.php?id=<?php echo $user['id_employer'];?>">
                      <i class="glyphicon glyphicon-user"></i>
                      Profile
                  </a>
              </li>
             <li>
                 <a href="edit_account.php" title="edit account">
                     <i class="glyphicon glyphicon-cog"></i>
                     Settings
                 </a>
             </li>
             <li class="last">
                 <a href="logout.php">
                     <i class="glyphicon glyphicon-off"></i>
                     Logout
                 </a>
             </li>
           </ul>
          </li>
        </ul>
      </div>
     </div>
    </header>
    <div class="sidebar">
      <?php if($user['level_user'] === '0'): ?>
        <!-- admin menu -->
      <?php include_once('admin_menu.php');?>

      <?php elseif($user['level_user'] === '1'): ?>
        <!-- Special user -->
      <?php include_once('special_menu.php');?>

      <?php elseif($user['level_user'] === '2'): ?>
        <!-- User menu -->
      <?php include_once('user_menu.php');?>

      <?php elseif($user['level_user'] === '3'): ?>

      <?php include_once('user_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">
