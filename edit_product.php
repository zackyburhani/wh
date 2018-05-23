<?php
  $page_title = 'Edit product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$item = find_by_id_pro('item',$_GET['id']);
$all_categories = find_all1('categories');
$join_subcategories  = find_allSubcategories();
//$all_warehouse = find_all1('warehouse');
if(!$item){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>


<?php
  if(isset($_POST['product'])){

    $req_fields = array('name_item','color_item','id_package','stock_item','id_subcategories','id_location' );
    validate_fields($req_fields);

   if(empty($errors)){

    $p_id      = remove_junk($db->escape($_POST['id_item']));
    $p_name    = remove_junk($db->escape($_POST['name_item']));
    $p_color   = remove_junk($db->escape($_POST['color_item']));
    $p_width   = remove_junk($db->escape($_POST['width_item']));
    $p_height  = remove_junk($db->escape($_POST['height_item']));
    $p_length  = remove_junk($db->escape($_POST['length_item']));
    $p_weight  = remove_junk($db->escape($_POST['weight_item']));
    $p_stock   = remove_junk($db->escape($_POST['stock_item']));     
    $p_package = remove_junk($db->escape($_POST['id_package']));
    $p_cat     = remove_junk($db->escape($_POST['id_subcategories']));
    $p_loc     = remove_junk($db->escape($_POST['id_location']));


        $query  = "UPDATE item SET ";
        $query .= "nm_item = '{$p_name}',colour = '{$p_color}',width = '{$p_width}',height = '{$p_height}',length = '{$p_length}',weight = '{$p_weight}',stock = '{$p_stock}',id_package = '{$p_package}',id_subcategories = '{$p_cat}',id_location = '{$p_loc}', id_item = '{$p_id}' ";
        $query .= "WHERE id_item = '{$p_id}'";
        $result = $db->query($query);
         if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"Product Has Been Updated! ");
          redirect('product.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated Product!');
          redirect('edit_product.php?id='.$item['id_item'], false);
        }
   } else {
     $session->msg("d", $errors);
     redirect('edit_product.php?id='.$item['id_item'], false);
   }
 }
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-17">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Edit Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-13">
           <form method="post" action="edit_product.php?id=<?php echo $item['id_item'] ?>">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="name" class="control-label">Category</label>
                    <input type="hidden" name="id_item" value="<?php echo $item['id_item']; ?>">
                    <select class="form-control" id="category" name="id_categories">
                      <option value="">Select Category Product</option>
                        <?php foreach ($all_categories as $cat): ?>
                          <option value="<?php echo $cat['id_categories']; ?>" <?php if($item['id_subcategories'] === $cat['id_categories']): echo "selected"; endif; ?> ><?php echo remove_junk($cat['nm_categories']); ?></option>
                        <?php endforeach; ?>
                      </select>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Subcategory</label>
                    <select class="form-control" id="sub_category" name="id_subcategories">
                      <option value="">Select Subcategory Product</option>
                      <?php foreach ($join_subcategories as $subcat): ?>
                        <option value="<?php echo $cat['id_categories']; ?>" <?php if($item['id_subcategories'] === $subcat['id_categories']): echo "selected"; endif; ?> class="<?php echo $subcat['id_categories']; ?>"><?php echo remove_junk($subcat['nm_subcategories']); ?></option></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Package</label>
                    <select class="form-control" name="id_package">
                      <option value="">Select Package Product</option>
                      <option value="1">A</option>
                      <option value="2">B</option>
                      <option value="3">C</option>
                    </select>
                  </div>
                    
                  <div class="col-md-3">
                    <label for="name" class="control-label">Warehouse</label>
                    <select class="form-control" name="id_location">
                      <option value="">Select Location Warehouse</option>
                      <option value="1">A</option>
                      <option value="2">B</option>
                      <option value="3">C</option>
                    </select>
                  </div>
              </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="name" class="control-label">Item Name</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-paperclip"></i>
                      </span>
                      <input type="text" class="form-control" name="name_item" onkeypress="return hanyaHuruf(event)" value="<?php echo remove_junk($item['nm_item']);?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="name" class="control-label">Colour</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-equalizer"></i>
                      </span>
                      <input type="text" class="form-control" name="color_item" onkeypress="return hanyaHuruf(event)" value="<?php echo remove_junk($item['colour']);?>"><br>
                   </div>
                  </div>
               </div>
              </div>
		          <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="name" class="control-label">Width</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="text" class="form-control" name="width_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['width']);?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <label for="name" class="control-label">Height</label>
                  <div class="input-group">
                    <span class="input-group-addon">
						          <i class="glyphicon glyphicon-tasks"></i>
                    </span>
                     <input type="text" class="form-control" name="height_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['height']);?>">
                  </div>
                </div>
                <div class="col-md-3">
                  <label for="name" class="control-label">Length</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-tasks"></i>
                    </span>
                    <input type="text" class="form-control" name="length_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['length']);?>">
                  </div>
                </div>
				        <div class="col-md-3">
                  <label for="name" class="control-label">Weight</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-tasks"></i>
                    </span>
                    <input type="text" class="form-control" name="weight_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['weight']);?>">
                  </div>
                </div>
               </div>
              </div>
              <label for="name" class="control-label">Stock</label>
			        <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-equalizer"></i>
                </span>
                <input type="text" class="form-control" name="stock_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['stock']);?>"><br>
              </div>
			   <hr>
            </div>

              <button type="submit" name="product" class="btn btn-danger"><span class="glyphicon glyphicon-save"></span>&nbsp;&nbsp;&nbsp;Update Product</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <script>
		function hanyaAngka(evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode
		  if (charCode > 31 && (charCode < 48 || charCode > 57))
		    return false;
		    return true;
		}
		
		function hanyaHuruf(evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode
		    if (charCode > 31 && (charCode < 48 || charCode > 57))
		      return true;
		      return false;
		}
	</script>

	<script language="JavaScript">
    function showDetails(input){
		  window.open(input,"RataRata","width=800,height=200");
	  }
  </script>

  <script src="jquery-1.10.2.min.js"></script>
  <script src="jquery.chained.min.js"></script>
  <script>
    $("#sub_category").chained("#category");
  </script>

<?php include_once('layouts/footer.php'); ?>
