, 'package-categorie', 'product-categorie', 'product-warehouse' 
=====================================================================
$p_package  = remove_junk($db->escape($_POST['package-categorie']));
     $p_categori  = remove_junk($db->escape($_POST['product-categorie']));
	 $p_warehouse  = remove_junk($db->escape($_POST['product-warehouse']));
=====================================================================
,id_package,id_subcategories,id_location
=====================================================================
,'{$p_package}','{$p_categori}','{$p_warehouse}'
=====================================================================


             <!-- <div class="form-group">
			  <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="package-categorie">
                      <option value="">Select Package Product</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Category Product</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" name="product-warehouse">
                      <option value="">Select Location Warehouse</option>
                 <?php  foreach ($all_warehouse as $ware): ?>
                      <option value="<?php echo (int)$ware['id'] ?>">
                        <?php echo $ware['name_warehouse'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div><br>-->
========================================================
  if(empty($errors)){
     $p_id  = remove_junk($db->escape($_POST['id_item']));
     $p_name   = remove_junk($db->escape($_POST['name_item']));
	 $p_color   = remove_junk($db->escape($_POST['color_item']));
     $p_widht  = remove_junk($db->escape($_POST['widht_item']));
     $p_height  = remove_junk($db->escape($_POST['height_item']));
     $p_lenght  = remove_junk($db->escape($_POST['lenght_item']));
	 $p_weight  = remove_junk($db->escape($_POST['weight_item']));
	 $p_stock  = remove_junk($db->escape($_POST['stock_item']));
     $p_package  = remove_junk($db->escape($_POST['package-categorie']));
     $p_categori  = remove_junk($db->escape($_POST['product-categorie']));
	 $p_warehouse  = remove_junk($db->escape($_POST['product-warehouse']));
    
     $query  = "INSERT INTO item (";
     $query .=" id_item,nm_item,colour,widht,height,lenght,weight,stock,id_package,id_subcategories,id_location";
     $query .=") VALUES (";
     $query .=" '{$p_id}','{$p_name}','{$p_color}','{$p_widht}','{$p_height}','{$p_lenght}','{$p_weight}','{$p_stock}','{$p_package}','{$p_categori}','{$p_warehouse}'";
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

 }			  