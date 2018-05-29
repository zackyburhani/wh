<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
   $all_subcategories  = find_all_order('sub_categories','nm_subcategories');
   $all_categories  = find_all_order('categories','nm_categories');
   $join_subcategories  = find_allSubcategories();
 
?>
<?php
  if(isset($_POST['add_product'])){

    $req_fields = array('name_item','color_item','stock_item','package-categorie','id_subcategories', 'product-warehouse' );
    validate_fields($req_fields);
    if(empty($errors)){
      $p_id       = autonumber('id_item','item');
      $p_name     = remove_junk($db->escape($_POST['name_item']));
      $p_color    = remove_junk($db->escape($_POST['color_item']));
      $p_widht    = remove_junk($db->escape($_POST['widht_item']));
      $p_height   = remove_junk($db->escape($_POST['height_item']));
      $p_length   = remove_junk($db->escape($_POST['length_item']));
      $p_weight   = remove_junk($db->escape($_POST['weight_item']));
      $p_stock    = remove_junk($db->escape($_POST['stock_item']));
      $p_package  = remove_junk($db->escape($_POST['package-categorie']));
      $p_categori = remove_junk($db->escape($_POST['id_subcategories']));
      $p_location = remove_junk($db->escape($_POST['product-warehouse']));
    
      $query  = "INSERT INTO item (";
      $query .=" id_item,nm_item,colour,width,height,length,weight,stock,id_package,id_subcategories,id_location";
      $query .=") VALUES (";
      $query .=" '{$p_id}', '{$p_name}', '{$p_color}', '{$p_widht}', '{$p_height}', '{$p_length}', '{$p_weight}', '{$p_stock}', '{$p_package}', '{$p_categori}', '{$p_location}'";
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
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-13">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-13">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong> 
        </div>
        <div class="panel-body">
          <div class="col-md-14">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" id="category" name="id_categories">
                      <?php if($all_categories == null) { ?>
                        <option value="">-</option>
                          <?php } else { ?>
                            <?php foreach($all_categories as $row){ ?>
                              <option value="<?php echo remove_junk($row['id_categories']); ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                            <?php } ?>  
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <form method="post" action="add_product.php" class="clearfix">
                        <select class="form-control" id="sub_category" name="id_subcategories">
                          <option value="">-</option>
                            <?php if($join_subcategories== null) { ?>
                              <option value="">-</option>
                            <?php } else { ?>
                              <?php foreach($join_subcategories as $row2){ ?>
                                <option class="<?php echo $row2['id_categories']; ?>" value="<?php echo remove_junk($row2['id_subcategories']); ?>"><?php echo remove_junk(ucwords($row2['nm_subcategories'])); ?>
                                </option>
                              <?php } ?>
                            <?php } ?>  
                        </select>
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" name="package-categorie">
                        <option value="">Select Package Product</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" name="product-warehouse">
                        <option value="">Select Location Warehouse</option>
                        <option value="2">A</option>
                        <option value="3">B</option>
                        <option value="4">C</option>
                      </select>
                    </div>
                </div>
              </div>

               <div class="form-group">
                 <div class="row">
                   <div class="col-md-6">
                     <div class="input-group">
                       <span class="input-group-addon">
                          <i class="glyphicon glyphicon-paperclip"></i>
                        </span>
                        <input type="text" class="form-control" name="name_item" onkeypress="return hanyaHuruf(event)" placeholder="Name Product">
                    </div>
                   </div>
                    <div class="col-md-6">
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
                     <input type="text" class="form-control" name="length_item" onkeypress="return hanyaAngka(event)" placeholder="Length Product">
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
            </div>
              <hr>
              <button type="submit" name="add_product" class="btn btn-danger"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add Product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>

<!-- SEMENTARA DIKOMEN KARENA DILAPTOP ZACKY KODINGAN INI GA BISA -->
<!-- <script>
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
</script> -->

<script src="jquery-1.10.2.min.js"></script>
<script src="jquery.chained.min.js"></script>
<script>
  $("#sub_category").chained("#category");
</script>

<?php include_once('layouts/footer.php'); ?>