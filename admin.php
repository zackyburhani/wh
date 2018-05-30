<?php
  $page_title = 'Admin Home Page';
	require_once('includes/load.php');
  //Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
 $c_categorie     = count_by_id_cat('categories');
 $c_product       = count_by_id_pro('products');
 $c_user          = count_by_id('employer');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-6">
     <?php echo display_msg($msg); ?>
   </div>
</div>
  <div class="row">
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
          <p class="text-muted">Users</p>
        </div>
       </div>
    </div>
    <div class="col-md-3">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-list"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted"><a href="categories.php">Categories</a></p>
        </div>
       </div>
    </div>
    
    <div class="col-md-3" >
       <div class="panel panel-box clearfix" >
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-usd"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo "0" ?></h2>
          <p class="text-muted">Sales</p>
        </div>
       </div>
    </div>
</div>
  <div class="row">
   <div class="col-md-12">
      <div class="panel">
        <div class="jumbotron text-center">
           <h1>Welcome to Warehouse....!</h1>
           <p> <strong>Warehouse-Budi Luhur v2</strong> 
           </br>Happy Browser in Our Website.</p>

        </div>
      </div>
   </div>
  </div>
  
  <div class="row">

  </div>

<?php include_once('layouts/footer.php'); ?>
