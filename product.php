<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  // $all_categories = find_all1('categories');
  $user = current_user();
  $id = $user['id_warehouse'];

  $join_subcategories  = find_allSubcategories($id);
  $all_package         = find_all_Position('package'); 
  $all_warehouse_id    = find_warehouse_id($user['id_warehouse']);

  $all_product         = find_all_product($id);
  $get_product         = get_product('item',$id);
  $all_categories      = find_all_categories('categories',$id);
  $all_subcategories   = find_all_subcategories('sub_categories',$id);
  $all_package         = find_all_package('package',$id);
  $all_location        = find_all_location('location',$id);

  $warehouse    = find_by_id_warehouse('warehouse',$user['id_warehouse']);
 
  // if($get_product == null){
  //   $query2  = "UPDATE warehouse SET ";
  //   $query2 .= "heavy_consumed = 0.00";
  //   $query2 .= " WHERE id_warehouse = '{$id}'";
  //   $db->query($query2);
  // } 

?> 

<!-- ADD PRODUCT -->
<?php
  if(isset($_POST['add_Product'])){

    $req_fields = array('nm_item','colour','stock','id_package','id_subcategories', 'id_location');
    validate_fields($req_fields);
    if(empty($errors)){
      $id_warehouse = $user['id_warehouse'];
      $id_item          = autonumber('id_item','item');
      $nm_item          = remove_junk($db->escape($_POST['nm_item']));
      $colour           = remove_junk($db->escape($_POST['colour']));
      $width            = remove_junk($db->escape($_POST['width']));
      $height           = remove_junk($db->escape($_POST['height']));
      $length           = remove_junk($db->escape($_POST['length']));
      $weight           = remove_junk($db->escape($_POST['weight']));
      $stock            = remove_junk($db->escape($_POST['stock']));  
      $id_package       = remove_junk($db->escape($_POST['id_package']));
      $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
      $id_location      = remove_junk($db->escape($_POST['id_location']));

      //convert
      $convert_weight   = remove_junk($db->escape($_POST['convert_weight']));
      $convert_stock    = remove_junk($db->escape($_POST['convert_stock']));

      //convert weight
      if($convert_weight == "weight_kilograms"){
        $weight = $weight;
      } else if($convert_weight == "weight_pounds") {
        $weight = $weight/2.2046;
      } else if($convert_weight == "weight_ons"){
        $weight = $weight/35.274;
      } else if($convert_weight == "weight_grams"){
        $weight = $weight/1000;
      }

      //reduce area consumed 
      $consumed     = $all_warehouse_id['heavy_consumed'];
      $heavy_max    = $all_warehouse_id['heavy_max'];
      $id_warehouse = $all_warehouse_id['id_warehouse']; 
      $reduced      = ($weight*$stock)+$consumed;

      if($reduced > $heavy_max){
        $session->msg('d',"You Do Not Have Enough Storage Space !");
        redirect('product.php', false);
      }

        $query  = "UPDATE warehouse SET ";
        $query .= "heavy_consumed='{$reduced}' ";
        $query .= "WHERE id_warehouse = '{$id_warehouse}'";

      if($db->query($query)){
        
        //insert item
        $query2  = "INSERT INTO item (";
        $query2 .=" id_item,nm_item,colour,width,height,length,weight,stock,id_package,id_subcategories,id_location";
        $query2 .=") VALUES (";
        $query2 .=" '{$id_item}', '{$nm_item}', '{$colour}', '{$width}', '{$height}', '{$length}', '{$weight}', '{$stock}', '{$id_package}', '{$id_subcategories}', '{$id_location}'";
        $query2 .=")";

        if($db->query($query2)) {
          $session->msg('s',"Product added ");
          redirect('product.php', false);  
        } 
      } else {
        $session->msg('d',' Sorry failed to added!');
        redirect('product.php', false);
      }

    } else{
       $session->msg("d", $errors);
       redirect('product.php',false);
     }

  }
?>
<!-- END ADD PRODUCT -->

<!-- UPDATE PRODUCT -->
<?php
  if(isset($_POST['update_Product'])){

    $req_fields = array('nm_item','colour','id_package','stock','id_subcategories','id_location' );
    validate_fields($req_fields);

   if(empty($errors)){

    $id_item          = remove_junk($db->escape($_POST['id_item']));
    $nm_item          = remove_junk($db->escape($_POST['nm_item']));
    $colour           = remove_junk($db->escape($_POST['colour']));
    $width            = remove_junk($db->escape($_POST['width']));
    $height           = remove_junk($db->escape($_POST['height']));
    $length           = remove_junk($db->escape($_POST['length']));
    $weight           = remove_junk($db->escape($_POST['weight']));
    $stock            = remove_junk($db->escape($_POST['stock']));     
    $id_package       = remove_junk($db->escape($_POST['id_package']));
    $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
    $id_location      = remove_junk($db->escape($_POST['id_location']));
    $id_categories    = remove_junk($db->escape($_POST['id_categories']));

    //convert
    $convert_weight   = remove_junk($db->escape($_POST['convert_weight']));
    $convert_stock    = remove_junk($db->escape($_POST['convert_stock']));

    //convert weight
    if($convert_weight == "weight_kilograms"){
      $weight = $weight;
    } else if($convert_weight == "weight_pounds") {
      $weight = $weight/2.2046;
    } else if($convert_weight == "weight_ons"){
      $weight = $weight/35.274;
    } else if($convert_weight == "weight_grams"){
      $weight = $weight/1000;
    }

    $product_fetch    = find_product_fetch($id_item);
    //reduce area consumed
    $stock_fetch      = $product_fetch['stock'];
    $weight_fetch     = $product_fetch['weight'];
    $consumed         = $all_warehouse_id['heavy_consumed']; 
    $heavy_max        = $all_warehouse_id['heavy_max'];
    $id_warehouse     = $all_warehouse_id['id_warehouse']; 
    $count            = $consumed-($stock_fetch*$weight_fetch);
    $reduced          = $count+($weight*$stock);
 
    if($reduced > $heavy_max){
      $session->msg('d',"You Do Not Have Enough Storage Space !");
      redirect('product.php', false);
    }

        $id_warehouse = $user['id_warehouse'];
        $query  = "UPDATE item SET ";
        $query .= "nm_item = '{$nm_item}',colour = '{$colour}',id_subcategories = '{$id_subcategories}',width = '{$width}',height = '{$height}',length = '{$length}',weight = '{$weight}',stock = '{$stock}',id_package = '{$id_package}',id_location = '{$id_location}', id_item = '{$id_item}' ";
        $query .= "WHERE id_item = '{$id_item}'";

        $query2  = "UPDATE warehouse SET ";
        $query2 .= "heavy_consumed='{$reduced}' ";
        $query2 .= "WHERE id_warehouse = '{$id_warehouse}'";

        $result = $db->query($query);
         if($result && $db->affected_rows() === 1){
          //sucess
          $db->query($query2);
          $session->msg('s',"Product Has Been Updated! ");
          redirect('product.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated Product!');
          redirect('product.php', false);
        }
   } else {
     $session->msg("d", $errors);
     redirect('product.php', false);
   }
 }
?>
<!-- END PRODUCT -->

<!-- DELETE PRODUCT -->
<?php
  if(isset($_POST['delete_item'])){
    
    $weight  = remove_junk($db->escape($_POST['weight']));
    $stock   = remove_junk($db->escape($_POST['stock'])); 
    $id_item = remove_junk($db->escape($_POST['id_item']));

    //validation connected foreign key
    $item = find_all_idItem($id_item);
    foreach ($item as $data) {
      $id_item2 = $data['id_item'];  
    }

    $itemP = find_all_idItemPackage($id_item);
    foreach ($itemP as $data) {
      $id_itemPackage = $data['id_item'];  
    }

    if($id_item == $id_item2 || $id_item == $id_itemPackage){
      $session->msg("d","The Field Connected To Other Key.");
      redirect('product.php');
    }

    //reduce area consumed
    $consumed     = $all_warehouse_id['heavy_consumed']; 
    $heavy_max    = $all_warehouse_id['heavy_max'];
    $id_warehouse = $all_warehouse_id['id_warehouse']; 
    $reduced      = $consumed-($weight*$stock);

    $query  = "UPDATE warehouse SET ";
    $query .= "heavy_consumed='{$reduced}'";
    $query .= " WHERE id_warehouse = '{$id_warehouse}'";

    //delete function
    $delete_id   = delete('id_item','item',$id_item);

    $query2  = "UPDATE warehouse SET ";
    $query2 .= "heavy_consumed = 0.00";
    $query2 .= " WHERE id_warehouse = '{$id_warehouse}'";      

    if($delete_id){
      $db->query($query);
        if($reduced < 0){
          $db->query($query2);
        }
      $session->msg("s","Product Has Been Deleted.");
      redirect('product.php');
    } else {
      $session->msg("d","Product Deletion Failed");
      redirect('product.php');
    }  
  }
?>
<!-- END DELETE PRODUCT -->


<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
  <div class="col-md-13">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <!-- <div class="col-md-6">
          <form method="get" action="product.php">
            <select class="form-control" name="id">
              <option value=""> Select Location Warehouse</option>
                <?php  foreach ($all_warehouse as $ware): ?>
                  <option value="<?php echo (int)$ware['id']; ?>" <?php if($_GET['id'] === $ware['id']): echo "selected"; endif; ?> >
                  <?php echo remove_junk($ware['name_warehouse']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;&nbsp;Sort</button>
          </div>
        </form>
         <div class="pull-right">
           <button data-target="#add_product" class="btn btn-md btn-info" data-toggle="modal" title="Remove"><i class="glyphicon glyphicon-plus"></i> Add New
            </button>
         </div> -->
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Products</span>
          </strong>
          <?php if($warehouse['status'] != 0) { ?>
        <?php
         if ($user['level_user']==0 || $user['level_user']==1) { ?>
          <button data-target="#add_product" class="btn btn-md btn-info pull-right" data-toggle="modal" title="Remove"><i class="glyphicon glyphicon-plus"></i> Add New
            </button>
        <?php } ?>
          <?php } ?>
        </div>
        
        <div class="panel-body">
          <table class="table table-bordered" id="datatableProduct">
            <thead>
              <tr>
                <th class="text-center" style="width: 1px;">No.</th>
                <th class="text-center"> Name Product</th>
                <th class="text-center"> Color Product </th>
                <th class="text-center" style="width: 5px;"> Stock </th>
                <th class="text-center"> Package </th>
                <th class="text-center"> Category </th>
                <th class="text-center"> Unit </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($get_product as $items):?>
              <tr>
                <td class="text-center"><?php echo count_id().".";?></td>
                <td class="text-center"><a href="#detilItem<?php echo $items['id_item'];?>" data-toggle="modal" title="Detail"> <?php echo remove_junk(ucfirst($items['nm_item'])); ?></a></td>
                <td class="text-center"> <?php echo remove_junk(ucfirst($items['colour'])); ?></td>
                <td class="text-center"> <?php echo remove_junk(ucfirst($items['stock'])); ?></td>
                <td class="text-center"> <?php echo remove_junk(ucfirst($items['nm_package'])); ?></td>
                <td class="text-center"> <?php echo remove_junk(ucfirst($items['nm_subcategories'])); ?></td>
                <td class="text-center"> <?php echo remove_junk(ucfirst($items['unit'])); ?></td>
                <td class="text-center">
                  <button data-target="#updateItem<?php echo $items['id_item'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Update">
                    <i class="glyphicon glyphicon-edit"></i>
                  </button>
                <?php
                 if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) { ?>
                  <button data-target="#deleteItem<?php echo $items['id_item'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Remove">
                    <i class="glyphicon glyphicon-trash"></i>
                  </button>
                <?php } ?>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
</table>


<!-- ADD NEW PRODUCT -->
<div class="modal fade" id="add_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-cubes"></i>  Add New Product</h4>
        
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="row">
            <div class="col-md-3">
              <label for="name" class="control-label">Category</label>
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
                  <label for="name" class="control-label">Subcategory</label>
                  <form method="post" action="product.php" class="clearfix">
                    <input type="hidden" name="id_item" value="<?php echo remove_junk($item['id_item']);?>">
                    <select class="form-control" id="sub_category" name="id_subcategories">
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
                      <label for="name" class="control-label">Package</label>
                      <select class="form-control" name="id_package">
                        <?php if($all_package == null) { ?>
                          <option value="">-</option>
                        <?php } else { ?>
                        <?php foreach($all_package as $row3){ ?>
                            <option value="<?php echo remove_junk($row3['id_package']); ?>"><?php echo remove_junk(ucwords($row3['nm_package'])); ?></option>
                          <?php } ?> 
                        <?php } ?> 
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="name" class="control-label">Location Warehouse</label>
                      <select class="form-control" name="id_location">
                        <?php if($all_location == null) { ?>
                          <option value="">-</option>
                          <?php } else { ?>
                            <?php foreach($all_location as $row){ ?>
                              <option value="<?php echo remove_junk($row['id_location']); ?>"><?php echo remove_junk(ucwords($row['unit'])); ?></option>
                          <?php } ?>  
                        <?php } ?>
                      </select>
                    </div>
                </div>
              </div>

              <hr>

              <div class="form-group">
                 <div class="row">
                   <div class="col-md-6">
                    <label for="name" class="control-label">Name Product</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                          <i class="glyphicon glyphicon-paperclip"></i>
                        </span>
                        <input type="text" class="form-control" name="nm_item" onkeypress="return hanyaHuruf(event)" placeholder="Name Product">
                    </div>
                   </div>
                    <div class="col-md-6">
                      <label for="name" class="control-label">Colour</label>
                      <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-equalizer"></i>
                        </span>
                        <input type="text" class="form-control" name="colour"  onkeypress="return hanyaHuruf(event)" placeholder="Color Product"><br>
                     </div>
                    </div>
                 </div>
                </div>

              <hr>
        
              <div class="form-group">
                <div class="row">

                  <div class="col-md-4">
                    <label for="name" class="control-label">Height / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                     <input type="number" min="0" class="form-control" name="height" onkeypress="return hanyaAngka(event)" placeholder="Height Product">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <label for="name" class="control-label">Width / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="number" min="0" class="form-control" name="width" onkeypress="return hanyaAngka(event)" placeholder="Widht Product">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <label for="name" class="control-label">Length / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="number" min="0" class="form-control" name="length" onkeypress="return hanyaAngka(event)" placeholder="Length Product">
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="name" class="control-label">Weight</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="number" min="0" class="form-control" name="weight" onkeypress="return hanyaAngka(event)"placeholder="Weight Product">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="name" class="control-label">Convert Weight</label>
                      <select class="form-control" name="convert_weight">
                        <option value="weight_kilograms">Kilograms</option>
                        <option value="weight_pounds">Pounds</option>
                        <option value="weight_ons">Ons</option>
                        <option value="weight_grams">Grams</option>
                      </select>
                  </div>
                </div>
              </div>
              <hr>

             <div class="row">
               <div class="col-md-12">
                 <label for="name" class="control-label">Stock</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-equalizer"></i>
                    </span>
                    <input type="number" min="0" class="form-control" name="stock" onkeypress="return hanyaAngka(event)" placeholder="Stock Product"><br>
                  </div>
               </div>
             </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
            <button type="submit" name="add_Product" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span>  Save</button>
          </div>
        </form>
    </div>
  </div>
</div>
<!-- END ADD NEW PRODUCT -->

<!-- Update Data Product -->
<?php foreach($all_product as $item) { ?>
<div class="modal fade" id="updateItem<?php echo $item['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-cubes"></i>  Update Product</h4>
        
      </div>

      <div class="modal-body">

        <div class="form-group">
          <div class="row">
            <div class="col-md-3">
              <label for="name" class="control-label">Category</label>
                <select class="form-control" id="category" name="id_categories">
                  <?php if($all_categories == null) { ?>
                    <option value="">-</option>
                      <?php } else { ?>
                        <?php foreach ($all_categories as $cat) : ?>
                          <option value="<?php echo $cat['id_categories']; ?>" <?php if($item['id_categories'] === $cat['id_categories']): echo "selected"; endif; ?> ><?php echo remove_junk($cat['nm_categories']); ?></option>
                        <?php endforeach; ?> 
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="name" class="control-label">Subcategory</label>
                  <form method="post" action="product.php" class="clearfix">
                    <input type="hidden" name="id_item" value="<?php echo remove_junk($item['id_item']);?>">
                    <select class="form-control" id="sub_category" name="id_subcategories">
                      <option value="">-</option>
                        <?php if($join_subcategories== null) { ?>
                          <option value="">-</option>
                        <?php } else { ?>
                          <?php foreach ($join_subcategories as $subcat): ?>
                            <option value="<?php echo $subcat['id_subcategories']; ?>" <?php if($item['id_subcategories'] === $subcat['id_subcategories']): echo "selected"; endif; ?> class="<?php echo $item['id_categories']; ?>"><?php echo remove_junk($subcat['nm_subcategories']); ?></option>
                          <?php endforeach; ?>
                      <?php } ?>  
                    </select>
                    </div>
                    <div class="col-md-3">
                      <label for="name" class="control-label">Package</label>
                      <select class="form-control" name="id_package">
                        <?php if($all_package == null) { ?>
                          <option value="">-</option>
                            <?php } else { ?>
                              <?php foreach ($all_package as $row5) : ?>
                                <option value="<?php echo $row5['id_package']; ?>" <?php if($item['id_package'] === $row5['id_package']): echo "selected"; endif; ?> ><?php echo remove_junk($row5['nm_package']); ?></option>
                              <?php endforeach; ?> 
                          <?php } ?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="name" class="control-label">Location Warehouse</label>
                      <select class="form-control" name="id_location">
                        <?php if($all_location == null) { ?>
                          <option value="">-</option>
                          <?php } else { ?>
                            <?php foreach($all_location as $row){ ?>
                              <option value="<?php echo remove_junk($row['id_location']); ?>" <?php if($item['id_location'] === $row['id_location']): echo "selected"; endif; ?>><?php echo remove_junk(ucwords($row['unit'])); ?></option>
                          <?php } ?>  
                        <?php } ?>
                      </select>
                    </div>
                </div>
              </div>

              <hr>

              <div class="form-group">
                 <div class="row">
                   <div class="col-md-6">
                    <label for="name" class="control-label">Name Product</label>
                     <div class="input-group">
                       <span class="input-group-addon">
                          <i class="glyphicon glyphicon-paperclip"></i>
                        </span>
                        <input type="text" value="<?php echo remove_junk($item['nm_item']);?>" class="form-control" name="nm_item" onkeypress="return hanyaHuruf(event)" placeholder="Name Product">
                    </div>
                   </div>
                    <div class="col-md-6">
                      <label for="name" class="control-label">Colour</label>
                      <div class="input-group">
                        <span class="input-group-addon">
                           <i class="glyphicon glyphicon-equalizer"></i>
                        </span>
                        <input type="text" class="form-control" name="colour" value="<?php echo remove_junk($item['colour']);?>" onkeypress="return hanyaHuruf(event)" placeholder="Color Product"><br>
                     </div>
                    </div>
                 </div>
                </div>
        

              <div class="form-group">
                <div class="row">

                  <div class="col-md-4">
                    <label for="name" class="control-label">Height / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                     <input type="number" min="0" class="form-control" name="height" value="<?php echo remove_junk($item['height']);?>" onkeypress="return hanyaAngka(event)" placeholder="Height Product">
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <label for="name" class="control-label">Width / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="number" min="0" class="form-control" value="<?php echo remove_junk($item['width']);?>" name="width" onkeypress="return hanyaAngka(event)" placeholder="Widht Product">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <label for="name" class="control-label">Length / Centimeters</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="number" min="0" class="form-control" name="length" onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['length']);?>" placeholder="Length Product">
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <label for="name" class="control-label">Weight</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="number" min="0" class="form-control" min="0" name="weight" value="<?php echo remove_junk(round($item['weight']));?>" placeholder="Weight Product">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="name" class="control-label">Convert Weight</label>
                      <select class="form-control" name="convert_weight">
                        <option value="weight_kilograms">Kilograms</option>
                        <option value="weight_pounds">Pounds</option>
                        <option value="weight_ons">Ons</option>
                        <option value="weight_grams">Grams</option>
                      </select>
                  </div>
                </div>
              </div>
              <hr>

             <div class="row">
               <div class="col-md-12">
                 <label for="name" class="control-label">Stock</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-equalizer"></i>
                    </span>
                    <input type="number" min="0" value="<?php echo remove_junk($item['stock']);?>" class="form-control" name="stock" onkeypress="return hanyaAngka(event)" placeholder="Stock Product"><br>
                  </div>
               </div>
             </div>
            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
            <button type="submit" name="update_Product" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span>  Update</button>
          </div>
        </form>
    </div>
  </div>
</div>
<?php } ?>
<!-- END Update Data Product -->

<!-- Delete Modal -->
<?php foreach($all_product as $item) : ?>
  <div class="modal fade" id="deleteItem<?php echo $item['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Confirm Delete</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete <b><u><?php echo remove_junk(ucwords($item['nm_item'])); ?></u></b> ?
        <form method="post" action="product.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['id_item'])); ?>" name="id_item">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['stock'])); ?>" name="stock">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($item['weight'])); ?>" name="weight">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_item" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- DELETE MODAL -->

<!-- DETIL PRODUCT -->
<?php foreach($all_product as $item) : ?>
  <div class="modal fade" id="detilItem<?php echo $item['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-cubes"></i> Detail Product</h4>
        </div>
        <div class="modal-body">
          <table class="table table-bordered" border="0">
              <tbody>
                <tr>
                  <td width="90px">Width</td>
                  <td width="20px">:</td>
                  <td><b><?php echo $item['width'];?></b></td>
                </tr>
                <tr>
                  <td width="90px">Height</td>
                  <td>:</td>
                  <td><b><?php echo $item['height'];?></b></td>
                </tr>
                <tr>
                  <td width="90px">Length</td>
                  <td>:</td>
                  <td><b><?php echo $item['length'];?></b></td>
                </tr>
                <tr>
                  <td width="90px">Weight</td>
                  <td>:</td>
                  <td><b><?php echo $item['weight'];?></b></td>
                </tr>
              </tbody>
            </table>
        </div>       
      </div>
    </div>
  </div>
<?php endforeach;?>
<!-- END DETIL PRODUCT -->


<script src="jquery-1.10.2.min.js"></script>
<script src="jquery.chained.min.js"></script>
<script>
  $("#sub_category").chained("#category");
</script>


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
