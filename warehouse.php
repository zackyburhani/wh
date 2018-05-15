<?php
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $warehouse = find_all('warehouse');
  $product = find_all2('products');

  $page_title = "Warehouse " . $warehouse[0]["name_warehouse"];
?>
<?php include_once('layouts/header.php'); ?>

  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
   <div class="row">
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span><?php echo $warehouse[0]["name_warehouse"]; ?></span>
       </strong>
      </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>Product</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($product as $pro):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
                    <td><?php echo remove_junk(ucfirst($pro['name'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_categorie.php?id=<?php echo (int)$pro['id'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                          <span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_categorie.php?id=<?php echo (int)$pro['id'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a>
                      </div>
                    </td>

                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
       </div>
    </div>
    </div>
   </div>
  </div>

  <?php include_once('layouts/footer.php'); ?>
