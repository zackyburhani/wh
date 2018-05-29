<?php
error_reporting(0);
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
	$all_item = find_all_item();
  // $all_categories = find_all1('categories');
  $join_subcategories  = find_allSubcategories();
  $all_subcategories  = find_all_order('sub_categories','nm_subcategories');
  $all_categories  = find_all_order('categories','nm_categories');
?> 


<!-- ADD PRODUCT -->
<?php
  if(isset($_POST['add_Product'])){

    $req_fields = array('nm_item','colour','stock','id_package','id_subcategories', 'id_location' );
    validate_fields($req_fields);
    if(empty($errors)){
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
    
      $query  = "INSERT INTO item (";
      $query .=" id_item,nm_item,colour,width,height,length,weight,stock,id_package,id_subcategories,id_location";
      $query .=") VALUES (";
      $query .=" '{$id_item}', '{$nm_item}', '{$colour}', '{$width}', '{$height}', '{$length}', '{$weight}', '{$stock}', '{$id_package}', '{$id_subcategories}', '{$id_location}'";
      $query .=")";

      if($db->query($query)){
        $session->msg('s',"Product added ");
        redirect('product.php', false);
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


        $query  = "UPDATE item SET ";
        $query .= "nm_item = '{$nm_item}',colour = '{$colour}',id_subcategories = '{$id_subcategories}',width = '{$width}',height = '{$height}',length = '{$length}',weight = '{$weight}',stock = '{$stock}',id_package = '{$id_package}',id_location = '{$id_location}', id_item = '{$id_item}' ";
        $query .= "WHERE id_item = '{$id_item}'";

        $result = $db->query($query);
         if($result && $db->affected_rows() === 1){
          //sucess
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

<!-- DELETE POSITION -->
<?php
  if(isset($_POST['delete_item'])){
    $id_item = remove_junk($db->escape($_POST['id_item']));

    //delete function
    $delete_id   = delete('id_item','item',$id_item);
    if($delete_id){
      $session->msg("s","Product Has Been Deleted.");
      redirect('product.php');
    } else {
      $session->msg("d","Product Deletion Failed");
      redirect('product.php');
    }  
  }
?>
<!-- END DELETE POSITION -->


<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
  </div>
  <div class="col-md-13">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <div class="col-md-6">
          <form method="get" action="product.php">
            <select class="form-control" name="id">
              <option value=""> Select Location Warehouse</option>
                <?php  //foreach ($all_warehouse as $ware): ?>
                  <option value="<?php //echo (int)$ware['id']; ?>" <?php //if($_GET['id'] === $ware['id']): echo "selected"; endif; ?> >
                  <?php //echo remove_junk($ware['name_warehouse']); ?></option>
                <?php //endforeach; ?>
            </select>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-search"></span>&nbsp;&nbsp;&nbsp;Sort</button>
          </div>
        </form>
         <div class="pull-right">
           <button data-target="#add_product" class="btn btn-md btn-info" data-toggle="modal" title="Remove"><i class="glyphicon glyphicon-plus"></i> Add New
            </button>
         </div>
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
                <th class="text-center"> Warehouse </th>
                <th class="text-center"> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_item as $items):?>
              <tr>
                <td class="text-center"><?php echo count_id().".";?></td>
                <td class="text-center"><a href="#detilItem<?php echo $items['id_item'];?>" data-toggle="modal" title="Detail"> <?php echo remove_junk($items['nm_item']); ?></a></td>
                <td class="text-center"> <?php echo remove_junk($items['colour']); ?></td>
                <td class="text-center"> <?php echo remove_junk($items['stock']); ?></td>
        				<td class="text-center"> <?php echo remove_junk($items['id_package']); ?></td>
        				<td class="text-center"> <?php echo remove_junk($items['nm_subcategories']); ?></td>
        				<td class="text-center"> <?php echo remove_junk($items['id_location']); ?></td>
                <td class="text-center">
                  <button data-target="#updateItem<?php echo $items['id_item'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Update">
                    <i class="glyphicon glyphicon-edit"></i>
                  </button>
                  <button data-target="#deleteItem<?php echo $items['id_item'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Remove">
                    <i class="glyphicon glyphicon-trash"></i>
                  </button>
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
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-th"></span>  Add New Product</h4>
        
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
                        <option value="">Select Package Product</option>
                        <option value="1">A</option>
                        <option value="2">B</option>
                        <option value="3">C</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label for="name" class="control-label">Location Warehouse</label>
                      <select class="form-control" name="id_location">
                        <option value="">Select Location Warehouse</option>
                        <option value="2">A</option>
                        <option value="3">B</option>
                        <option value="4">C</option>
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
        
              <div class="form-group">
                <div class="row">
                  <div class="col-md-3">
                    <label for="name" class="control-label">Width</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="text" class="form-control" name="width" onkeypress="return hanyaAngka(event)" placeholder="Widht Product">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Height</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                     <input type="text" class="form-control" name="height" onkeypress="return hanyaAngka(event)" placeholder="Height Product">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Length</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="length" onkeypress="return hanyaAngka(event)" placeholder="Length Product">
                   </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Weight</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="weight" onkeypress="return hanyaAngka(event)" placeholder="Weight Product">
                   </div>
                  </div>
                </div>
              </div>

              <hr>

             <label for="name" class="control-label">Stock</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-equalizer"></i>
                </span>
                <input type="text" class="form-control" name="stock" onkeypress="return hanyaAngka(event)" placeholder="Stock Product"><br>
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
<?php foreach($all_item as $item) { ?>
<div class="modal fade" id="updateItem<?php echo $item['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><span class="glyphicon glyphicon-th"></span>  Update Product</h4>
        
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
                          <option value="<?php echo $cat['id_categories']; ?>" <?php if($item['id_subcategories'] === $cat['id_categories']): echo "selected"; endif; ?> ><?php echo remove_junk($cat['nm_categories']); ?></option>
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
                            <option value="<?php echo $subcat['id_subcategories']; ?>" <?php if($item['id_subcategories'] === $subcat['id_categories']): echo "selected"; endif; ?> class="<?php echo $item['id_categories']; ?>"><?php echo remove_junk($subcat['nm_subcategories']); ?></option>
                          <?php endforeach; ?>
                      <?php } ?>  
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
                      <label for="name" class="control-label">Location Warehouse</label>
                      <select class="form-control" name="id_location">
                        <option value="">Select Location Warehouse</option>
                        <option value="2">A</option>
                        <option value="3">B</option>
                        <option value="4">C</option>
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
                  <div class="col-md-3">
                    <label for="name" class="control-label">Width</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                      <input type="text" class="form-control" value="<?php echo remove_junk($item['width']);?>" name="width" onkeypress="return hanyaAngka(event)" placeholder="Widht Product">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Height</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-tasks"></i>
                      </span>
                     <input type="text" class="form-control" name="height" value="<?php echo remove_junk($item['height']);?>" onkeypress="return hanyaAngka(event)" placeholder="Height Product">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Length</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="length" onkeypress="return hanyaAngka(event)" value="<?php echo remove_junk($item['length']);?>" placeholder="Length Product">
                   </div>
                  </div>
                  <div class="col-md-3">
                    <label for="name" class="control-label">Weight</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                         <i class="glyphicon glyphicon-tasks"></i>
                     </span>
                     <input type="text" class="form-control" name="weight" value="<?php echo remove_junk($item['weight']);?>" onkeypress="return hanyaAngka(event)" placeholder="Weight Product">
                   </div>
                  </div>
                </div>
              </div>

              <hr>

             <label for="name" class="control-label">Stock</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-equalizer"></i>
                </span>
                <input type="text" value="<?php echo remove_junk($item['stock']);?>" class="form-control" name="stock" onkeypress="return hanyaAngka(event)" placeholder="Stock Product"><br>
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
<?php foreach($all_item as $item) : ?>
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
<?php foreach($all_item as $item) : ?>
  <div class="modal fade" id="detilItem<?php echo $item['id_item'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-th"></span> Detail Product</h4>
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
