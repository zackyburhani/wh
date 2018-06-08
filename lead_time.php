<?php
  $page_title = 'Lead Time Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $all_warehouse = find_all1('warehouse');
   $user = current_user();
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
        <i class="fa fa-clock-o"></i>
        <span>Lead Time</span>
     </strong>
    </div>
     <div class="panel-body">
      <table class="table table-bordered" id="tablePosition">
        <thead>
          <tr>
            <th class="text-center" style="width: 50px;">No. </th>
            <th class="text-center">ID Purchase Order</th>
            <th class="text-center">Date Purchase Order</th>
            <th class="text-center" style="width: 150px;">Details</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
     </div>
    </div>
  </div>
</div>


  </div>
</div>

 
<?php include_once('layouts/footer.php'); ?>
