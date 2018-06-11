<?php
  $page_title = 'Home Page';
  require_once('includes/load.php');
  if (!$session->isUserLoggedIn(true)) { redirect('index.php', false);}
  //Checkin What level user has permission to view this page
  page_require_level(2);
  $user = current_user();

  $get_product = get_item_condition($user['id_warehouse']);

  //only super user
  $c_warehouse    = count_by_warehouse('warehouse');
  $c_admin_wh     = count_all_admin();
  $c_employer     = count_by_all_employer($user['id_warehouse']);
  $c_item         = count_all_item($user['id_warehouse']);

  $c_categories    = count_by_all('id_categories','categories',$user['id_warehouse']);
  $c_subcategories = count_all_subcategories($user['id_warehouse']);
  $c_package       = count_by_all('id_package','package',$user['id_warehouse']);
  $c_bpack         = count_all_bpack($user['id_warehouse']);

?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
  <div class="row">
    <?php if($user['level_user'] == 0){ ?>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-primary">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_admin_wh['total']; ?> </h2>
          <p class="text-muted"><a href="superusers.php">Administrator Warehouses</a></p>
        </div>
       </div>
    </div>
    <?php } ?>

    <?php if($user['level_user'] == 0){ ?>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-primary">
          <i class="fa fa-users"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_employer['total']; ?> </h2>
          <p class="text-muted"><a href="users.php">Employees</a></p>
        </div>
       </div>
    </div>
    <?php } else { ?>
      <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-primary">
          <i class="fa fa-users"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_employer['total']; ?> </h2>
          <p class="text-muted"><a href="users.php">Employees</a></p>
        </div>
       </div>
    </div>
    <?php } ?>

    <?php if($user['level_user'] == 0){ ?>
    <div class="col-md-3">
      <div class="panel panel-box clearfix">
        <div class="panel-icon pull-left bg-primary">
          <i class="fa fa-university"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_warehouse['total']; ?> </h2>
          <p class="text-muted"><a href="add_warehouse.php">Warehouses</a></p>
        </div>
       </div>
    </div>
    <?php } ?>

    <?php if($user['level_user'] == 0){ ?>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-primary">
          <i class="fa fa-cubes"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_item['total']; ?> </h2>
          <p class="text-muted"><a href="product.php">Products</a></p>
        </div>
       </div>
    </div>
    <?php } else { ?>
    <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-primary">
          <i class="fa fa-cubes"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_item['total']; ?> </h2>
          <p class="text-muted"><a href="product.php">Products</a></p>
        </div>
       </div>
    </div>
    <?php } ?>
   
</div>

  <div class="row">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="fa fa-tag"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categories['total']; ?> </h2>
          <p class="text-muted"><a href="categories.php">Categories</a></p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="fa fa-tags"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_subcategories['total']; ?> </h2>
          <p class="text-muted">Subcategories</p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="fa fa-archive"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_package['total']; ?> </h2>
          <p class="text-muted"><a href="add_package.php">Package</a></p>
        </div>
       </div>
    </div>

    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="fa fa-window-restore"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_bpack['total']; ?> </h2>
          <p class="text-muted"><a href="add_bpack.php">Combine Package</a></p>
        </div>
       </div>
    </div>

  </div>

<!-- Alert if Stock out -->
<?php 

$user = current_user();
$get_product = get_item_condition($user['id_warehouse']);
  
     $array = array();
     foreach($get_product as $product) {
        if($product['stock'] <= $product['safety_stock']){  
          echo '<script> setTimeout(function() {
                  swal({
                          title: "Your Items Almost Stock Out!",
                          type: "warning"
                        }, function() {
                        window.location = "warehouse.php";
                      });
                  }, 100);
                </script>';
        } 
      }
 ?>

<?php include_once('layouts/footer.php'); ?>
