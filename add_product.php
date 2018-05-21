<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
   $all_categories = find_all1('categories');

?>
<?php
 if(isset($_POST['add_product'])){
	// include('connect.php');

 $req_fields = array('id_item','name_item','color_item','widht_item','height_item', 'lenght_item','weight_item','stock_item','package-categorie','product-categorie', 'product-warehouse' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_id  = remove_junk($db->escape($_POST['id_item']));
     $p_name  = remove_junk($db->escape($_POST['name_item']));
     $p_color   = remove_junk($db->escape($_POST['color_item']));
	 $p_widht  = remove_junk($db->escape($_POST['widht_item']));
     $p_height   = remove_junk($db->escape($_POST['height_item']));
     $p_lenght   = remove_junk($db->escape($_POST['lenght_item']));
     $p_weight  = remove_junk($db->escape($_POST['weight_item']));
     $p_stock   = remove_junk($db->escape($_POST['stock_item']));
     $p_package   = remove_junk($db->escape($_POST['package-categorie']));
     $p_categori = remove_junk($db->escape($_POST['product-categorie']));
     $p_location  = remove_junk($db->escape($_POST['product-warehouse']));
	
	 $query  = "INSERT INTO item (";
     $query .=" id_item,nm_item,colour,width,height,lenght,weight,stock,id_package,id_subcategories,id_location";
     $query .=") VALUES (";
     $query .=" '{$p_id}', '{$p_name}', '{$p_color}', '{$p_widht}', '{$p_height}', '{$p_lenght}', '{$p_weight}', '{$p_stock}', '{$p_package}', '{$p_categori}', '{$p_location}'";
     $query .=")";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }

 
    /* $p_id  = $_POST['id_item'];
     $p_name   = $_POST['name_item'];
	 $p_color   = $_POST['color_item'];
     $p_widht  = $_POST['widht_item'];
     $p_height  = $_POST['height_item'];
     $p_lenght  = $_POST['lenght_item'];
	 $p_weight  = $_POST['weight_item'];
	 $p_stock  = $_POST['stock_item'];
     $p_package  = $_POST['package-categorie'];
     $p_categori  = $_POST['product-categorie'];
	 $p_warehouse  = $_POST['product-warehouse'];
	 
	 $query = mysql_query("INSERT INTO item(id_item,nm_item,colour,width,height,lenght,weight,stock,id_package,id_subcategories,id_location) VALUES('$p_id', '$p_name', '$p_color','$p_widht', '$p_height', '$p_lenght','$p_weight','$p_stock', '$p_package', '$p_categori','$p_warehouse')") or die(mysql_error());
	if($query){
	echo "<script>alert('Product added')</script>";	
	echo "<script>document.location.href='add_product.php'</script>";
	}else{
	echo "<script>alert('Data Gagal Disimpan')</script>";	
    echo "<script>document.location.href='add_product.php'</script>";
	}*/
	
 }
	

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-17">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-14">
          <form method="post" action="add_product.php" class="clearfix">
			   <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-th-large"></i>
                     </span>
                     <input type="text" class="form-control" name="id_item" placeholder="Id Product">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                        <i class="glyphicon glyphicon-paperclip"></i>
                  </span>
                  <input type="text" class="form-control" name="name_item" onkeypress="return hanyaHuruf(event)" placeholder="Name Product">
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-equalizer"></i>
                  </span>
                  <input type="text" class="form-control" name="color_item" onkeypress="return hanyaHuruf(event)" placeholder="Color Product"><br>
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
                     <input type="text" class="form-control" name="widht_item" onkeypress="return hanyaAngka(event)" placeholder="Widht Product">
                  </div>
                 </div>
                 <div class="col-md-3">
                   <div class="input-group">
                     <span class="input-group-addon">
						<i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="height_item" onkeypress="return hanyaAngka(event)" placeholder="Height Product">
                  </div>
                 </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="lenght_item" onkeypress="return hanyaAngka(event)" placeholder="Lenght Product">
                   </div>
                  </div>
				  <div class="col-md-3">
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="weight_item" onkeypress="return hanyaAngka(event)" placeholder="Weight Product">
                   </div>
                  </div>
               </div>
              </div>
			   <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-equalizer"></i>
                  </span>
                  <input type="text" class="form-control" name="stock_item" onkeypress="return hanyaAngka(event)" placeholder="Stock Product"><br>
               </div>
			   <br>
              </div>
			 <div class="form-group">
			  <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="package-categorie">
                      <option value="">Select Package Product</option>
						<option value="1">A</option>
					  <option value="2">B</option>
					  <option value="3">C</option>
                    </select>
                  </div>
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Category Product</option>
						<?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id_catagories']?>"><?php echo $cat['nm_categories'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="product-warehouse">
                      <option value="">Select Location Warehouse</option>
					  <option value="2">A</option>
					  <option value="3">B</option>
					  <option value="4">C</option>
                \
                    </select>
                  </div>
                </div>
              </div><br>
              <button type="submit" name="add_product" class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add product</button>
          </form>
         </div>
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
