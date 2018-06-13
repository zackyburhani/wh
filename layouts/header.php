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
    <link rel="icon" type="image/png" href="img/ikea-icon.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
    <link rel="stylesheet" href="libs/css/main.css" />
    <link rel="stylesheet" href="libs/css/map.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Sweet Alert -->
    <link rel="stylesheet" href="libs/css/sweetalert.css">

    <!-- DATATABLES -->
    <link rel="stylesheet" href="libs/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="libs/datatables/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="libs/datatables/jquery.dataTables.css">
    <link rel="stylesheet" href="libs/datatables/jquery.dataTables.min.css">
    <!-- END DATATABLES -->

     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjdeO9J1CF_PRTS9aOjZ9-Scg8dIlhxGg&libraries=places&callback=initMap"
         async defer></script>

  </head>
  <body>
  <?php  if ($session->isUserLoggedIn(true)): ?>
    <header id="header">
      <div class="logo pull-left" style="color: #ffda1a"> IKEA WAREHOUSE </div>
      <div class="header-content">
      <div class="header-date pull-left">
        <strong><?php echo date("F j, Y, g:i a");?></strong>
      </div>
      <div class="pull-right clearfix">
        <ul class="info-menu list-inline list-unstyled">

          <?php $offer_admin   = find_all_PO_destination_admin_notif($user['id_warehouse']); ?>
          <?php $approve_admin = find_all_PO_admin_notif($user['id_warehouse']); ?>
          <?php $approve2      = find_all_PO_destination_notif($user['id_warehouse']);  ?>
          <?php $warehouse     = find_by_id_warehouse('warehouse',$user['id_warehouse']); ?>
          <?php $shipment      = find_all_PO_shipment_notif($user['id_warehouse']); ?>
          <?php $under_stock   = find_all_item_under_stock($user['id_warehouse']); ?>
          <?php $package       = find_all_package_under_stock($user['id_warehouse'])?>
          <?php $canceled      = find_all_canceled_notif($user['id_warehouse']) ?>

          <li class="profile">
            <span class="label label-danger"><?php if($under_stock != null) { echo $total = $under_stock+$package;}?></span>
            <a href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-balance-scale"><i class="caret"></i></span> 
            </a>
          
            <ul class="dropdown-menu">
              <li>
                <a href="warehouse.php">
                  <i class="fa fa-balance-scale"></i>Item Stock Out
                  <span class="label label-danger"><?php if($under_stock != null) { echo $under_stock; }  ?></span>
                </a>
              </li>
           </ul>
          </li>


          <?php if($warehouse['status'] != 0) { ?>

          <li class="profile">
            <span class="label label-danger"><?php  if ($user['level_user'] =='0') {$total = $offer_admin+$approve_admin; if($total != null) {echo $total;} } else { if($approve2 != null) { echo $total = $approve2;}}?></span>
            <a href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-envelope-o"><i class="caret"></i></span> 
            </a>
          
            <ul class="dropdown-menu">
              <li>
                  <?php if ($user['level_user'] =='0'){ ?>
                    
                    <a href="offer_po.php">
                      <i class="fa fa-envelope-o"></i> Offer PO
                      <span class="label label-danger"><?php if($offer_admin != null) { echo $offer_admin; }  ?></span>
                    </a>

                    <a href="approve1_po.php">
                      <i class="fa fa-envelope-o"></i> Approve PO
                      <span class="label label-danger"><?php if($approve_admin != null) {echo $approve_admin;} ?></span>
                    </a>
                  <?php } else { ?>   
                    <a href="approve2_po.php">
                      <i class="fa fa-envelope-o"></i> Offer PO
                          <span class="label label-danger"><?php if($approve2 != null)  {echo $approve2;} ?></span>
                      <?php } ?>
              </li>
           </ul>
          </li>

        <?php } ?>


          <li class="profile">
            <span class="label label-danger"><?php if($shipment != null || $canceled != null) { echo $total = $shipment+$canceled;}?></span>
            <a href="#" data-toggle="dropdown" aria-expanded="false">
              <span class="fa fa-truck"><i class="caret"></i></span> 
            </a>
          
            <ul class="dropdown-menu">
              <?php if($canceled != null) { ?>
              <li>
                <a href="canceled_po.php">
                  <i class="fa fa-warning"></i> Canceled PO
                  <span class="label label-danger"><?php if($canceled != null) { echo $canceled; }  ?></span>
                </a>
              </li>
              <?php } ?>
              <li>
                <a href="move_product.php">
                  <i class="fa fa-truck"></i> Shipment
                  <span class="label label-danger"><?php if($shipment != null) { echo $shipment; }  ?></span>
                </a>
              </li>
           </ul>
          </li>

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

      <?php elseif($user['level_user'] === '1' || $user['level_user'] === '2' || $user['level_user'] === '3'): ?>
        <!-- Special user -->
      <?php include_once('special_menu.php');?>

      <?php endif;?>

   </div>
<?php endif;?>

<div class="page">
  <div class="container-fluid">
 
    