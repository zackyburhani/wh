<?php
  $page_title = 'Edit product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$item = find_by_id_pro('item',(int)$_GET['id']);
$all_categories = find_all1('categories');
//$all_warehouse = find_all1('warehouse');
if(!$item){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('id_item','name_item','color','widht','height', 'lenght','weight','stock','id_package','id_subcategories','id_location' );
    validate_fields($req_fields);

   if(empty($errors)){
	   $p_id  = remove_junk($db->escape($_POST['id_item']));
       $p_name  = remove_junk($db->escape($_POST['name_item']));
	   $p_color  = remove_junk($db->escape($_POST['color']));
       $p_widht  = remove_junk($db->escape($_POST['widht']));
	   $p_height  = remove_junk($db->escape($_POST['height']));
       $p_lenght  = remove_junk($db->escape($_POST['lenght']));
	   $p_weight  = remove_junk($db->escape($_POST['weight']));
	   $p_stock  = remove_junk($db->escape($_POST['stock']));	   
       $p_package   = (int)$_POST['id_package'];
	   $p_cat   = (int)$_POST['id_subcategories'];
	   $p_loc   = (int)$_POST['id_location'];
      
       $query   = "UPDATE item SET";
       $query  .="nm_item ='{$p_name}', colour ='{$p_qcolor}', widht='{$p_widht}', height='{$p_height}', lenght='{$p_lenght}', weight='{$p_weight}, stock='{$p_stock}',";
       $query  .=" id_package ='{$p_package}', id_subcategories ='{$p_cat}', id_location ='{$p_loc}'";
       $query  .=" WHERE id_item ='{$item['id_item']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$item['id_item'], false);
               }

   } else{
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
         <div class="col-md-14">
           <form method="post" action="edit_product.php?id=<?php echo (int)$item['id_item'] ?>">
              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="id_item" value="<?php echo remove_junk($item['id_item']);?>">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-paperclip"></i>
                  </span>
                  <input type="text" class="form-control" name="name_item" onkeypress="return hanyaHuruf(event)" value="<?php echo remove_junk($item['nm_item']);?>">
                  </div>
                 </div>
                  <div class="col-md-4">
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
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="widht_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['width']);?>">
                  </div>
                 </div>
                 <div class="col-md-3">
                   <div class="input-group">
                     <span class="input-group-addon">
						<i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="height_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['height']);?>">
                  </div>
                 </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="lenght_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['lenght']);?>">
                   </div>
                  </div>
				  <div class="col-md-3">
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="weight_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['weight']);?>">
                   </div>
                  </div>
               </div>
              </div>
			   <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-equalizer"></i>
                  </span>
                  <input type="text" class="form-control" name="stock_item"  onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['stock']);?>"><br>
               </div>
			   <br>
              </div>
			 <div class="form-group">
			  <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="id_package">
                      <option value="">Select Package Product</option>
						
                    </select>
                  </div>
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="id_subcategories">
                      <option value="">Select Category Product</option>
						<?php  foreach ($all_categories as $cat): ?>
                     <option value="<?php echo (int)$cat['id_categories']; ?>" <?php if($item['id_subcategories'] === $cat['id_categories']): echo "selected"; endif; ?> >
                       <?php echo remove_junk($cat['nm_categories']); ?></option>
                <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="id_location">
                      <option value="">Select Location Warehouse</option>
					  
                \
                    </select>
                  </div>
                </div>
              </div><br>
              <button type="submit" name="product" class="btn btn-danger"><span class="glyphicon glyphicon-pencil"></span>&nbsp;&nbsp;&nbsp;Update</button>
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

<?php include_once('layouts/footer.php'); ?>
