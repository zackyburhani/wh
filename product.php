<?php
error_reporting(0);
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
	$all_item = find_all_item('item')
  
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add New</a>
         </div>
        </div>
		 <div class="panel-heading clearfix">
     <form method="get" action="product.php">
         <div class="row">
           <div class="col-md-6">
              <select class="form-control" name="id">
              <option value=""> Select Location Warehouse</option>
                <?php  //foreach ($all_warehouse as $ware): ?>
                  <option value="<?php //echo (int)$ware['id']; ?>" <?php //if($_GET['id'] === $ware['id']): echo "selected"; endif; ?> >
                    <?php //echo remove_junk($ware['name_warehouse']); ?></option>
                <?php //endforeach; ?>
            </select>
            </div>
            <div class="col-md-6">
                 <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;&nbsp;Sort</button>
            </div>
         </div>
         </form>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="datatableProduct">
            <thead>
              <tr>
                <th class="text-center" style="width: 30px;">#</th>
                <th class="text-center" style="width: 10%;"> ID Product</th>
                <th class="text-center" style="width: 10%;"> Name Product</th>
                <th class="text-center" style="width: 10%;"> Color Product </th>
                <th class="text-center" style="width: 10%;"> Stock </th>
				<th class="text-center" style="width: 10%;"> Package </th>
                <th class="text-center" style="width: 10%;"> Categori </th>
                <th class="text-center" style="width: 10%;"> Warehouse </th>
                <th class="text-center" style="width: 30px;"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_item as $items):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td class="text-center"> <?php echo remove_junk($items['id_item']); ?></td>
                <td class="text-center"> <?php echo remove_junk($items['nm_item']); ?></td>
                <td class="text-center"> <?php echo remove_junk($items['colour']); ?></td>
                <td class="text-center"> <?php echo remove_junk($items['stock']); ?></td>
				<td class="text-center"> <?php echo remove_junk($items['id_package']); ?></td>
				<td class="text-center"> <?php echo remove_junk($items['id_subcategories']); ?></td>
				<td class="text-center"> <?php echo remove_junk($items['id_location']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$items['id_item'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$items['id_item'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip" onclick="javascript: return confirm('Anda yakin hapus ?')">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(".hapus").click(function () {
        var jawab = confirm("Press a button!");
        if (jawab === true) {
            var hapus = false;
            if (!hapus) {
                hapus = true;
                $.post('hapus.php', {id: $(this).attr('data-id')},
                function (data) {
                    alert(data);
                });
                hapus = false;
            }
        } else {
            return false;
        }
    });
</script>

  <?php include_once('layouts/footer.php'); ?>
