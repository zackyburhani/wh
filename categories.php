<?php
  $page_title = 'Categories';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(3);
  //get all categories
  $user = current_user();
  $id = $user['id_warehouse'];
  $all_categories    = find_all_categories('categories',$id);
  $all_subcategories = find_all_subcategories('sub_categories',$id);
  $join_categories   = find_allSubcategories($id);

?>

<!-- ADD NEW CATEGORY -->
<?php
  if(isset($_POST['add_cat'])){
    $req_field = array('categories-name');
    validate_fields($req_field_id);
    validate_fields($req_field);
    $id_cat       = autonumber('id_categories','categories');
    $cat_name     = remove_junk($db->escape($_POST['categories-name']));
    $id_warehouse = $user['id_warehouse'];

    // if(find_by_categoryName($_POST['categories-name']) === false ){
    //   $session->msg('d','<b>Sorry!</b> Entered Category Name Already In Database!');
    //   redirect('categories.php', false);
    // }

    if(empty($errors)){
      $sql  = "INSERT INTO categories (id_categories,nm_categories,id_warehouse)";
      $sql .= " VALUES ('{$id_cat}','{$cat_name}','{$id_warehouse}')";
      if($db->query($sql)){
        $session->msg("s", "Successfully Added Category");
        redirect('categories.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('categories.php',false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('categories.php',false);
    }
  }
?>
<!-- END NEW CATEGORY -->

<!-- UPDATE CATEGORY -->
<?php
  if(isset($_POST['update_category'])){
    $req_fields = array('nm_categories','id_categories');
    validate_fields($req_fields);
    if(empty($errors)){
      $nm_categories = remove_junk($db->escape($_POST['nm_categories']));
      $id_categories = remove_junk($db->escape($_POST['id_categories']));
      $id_warehouse = $user['id_warehouse'];
      $query  = "UPDATE categories SET ";
      $query .= "nm_categories='{$nm_categories}',id_categories='{$id_categories}',id_warehouse='{$id_warehouse}'";
      $query .= "WHERE id_categories='{$id_categories}'";
      $result = $db->query($query);
        if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"Category Has Been Updated! ");
          redirect('categories.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated Category!');
          redirect('categories.php', false);
        }
    } else {
      $session->msg("d", $errors);
      redirect('categories.php', false);
    }
  }
?>
<!-- END UPDATE CATEGORY -->

<!-- DELETE CATEGORY -->
<?php
  if(isset($_POST['delete_categories'])){
    $id_categories = remove_junk($db->escape($_POST['id_categories']));
    
    //validation connected foreign key
    $sub_category = find_all_id('sub_categories',$id_categories,'id_categories');
    foreach ($sub_category as $data) {
      $id_category2 = $data['id_categories'];  
    }
    if($id_categories == $id_category2){
      $session->msg("d","The Category Connected To Other Subcategory.");
      redirect('categories.php');
    }

    //delete function
    $delete_id   = delete('id_categories','categories',$id_categories);
    if($delete_id){
      $session->msg("s","Category Has Been Deleted.");
      redirect('categories.php');
    } else {
      $session->msg("d","Category Deletion Failed");
      redirect('categories.php');
    }  
  }
?>
<!-- END DELETE CATEGORY -->

<!-- ADD SUB CATEGORIES -->
<?php
   if(isset($_POST['add_subCategory'])){
   $req_field = array('nm_subcategories','id_categories');
   validate_fields($req_field);
   $id_subcategories = autonumber('id_subcategories','sub_categories');
   $id_categories = remove_junk($db->escape($_POST['id_categories']));
   $nm_subcategories = remove_junk($db->escape($_POST['nm_subcategories']));
   if(empty($errors)){
      $sql  = "INSERT INTO sub_categories (id_subcategories,nm_subcategories,id_categories)";
      $sql .= " VALUES ('{$id_subcategories}','{$nm_subcategories}','{$id_categories}')";

      $id_wh = $user['id_warehouse'];

      $getAllSubcategoryName = "SELECT nm_subcategories FROM sub_categories,categories where categories.id_categories = sub_categories.id_categories and nm_subcategories = '$nm_subcategories' and categories.id_warehouse = '$id_wh'";
      $exist=$db->query($getAllSubcategoryName) or die(mysql_error());
      if(mysqli_num_rows($exist)>0)
      { 
        $session->msg("d", "Subcategory Is Exist");
        redirect('categories.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Subcategory");
          redirect('categories.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('categories.php',false);
        }
      }      
   } else {
     $session->msg("d", $errors);
     redirect('categories.php',false);
   }
 }
?>
<!-- END SUB CATEGORIES -->

<!-- UPDATE SUBCATEGORY -->
<?php
  if(isset($_POST['update_Subcategory'])){
    $req_fields = array('nm_subcategories','id_categories');
    validate_fields($req_fields);
    if(empty($errors)){
      $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
      $nm_subcategories = remove_junk($db->escape($_POST['nm_subcategories']));
      $id_categories = remove_junk($db->escape($_POST['id_categories']));
      $query  = "UPDATE sub_categories SET ";
      $query .= "nm_subcategories='{$nm_subcategories}',id_categories='{$id_categories}'";
      $query .= "WHERE id_subcategories='{$id_subcategories}'";
      $result = $db->query($query);
        if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"Subcategory Has Been Updated! ");
          redirect('categories.php', false);
        } else {
          //failed
          $session->msg('d',' Sorry Failed To Updated Subcategory!');
          redirect('categories.php', false);
        }
    } else {
      $session->msg("d", $errors);
      redirect('categories.php', false);
    }
  }
?>
<!-- END UPDATE SUBCATEGORY -->

<!-- DELETE CATEGORY -->
<?php
  if(isset($_POST['delete_Subcategories'])){
    $id_subcategories = remove_junk($db->escape($_POST['id_subcategories']));
    
    //validation connected foreign key
    $item = find_all_id('item',$id_subcategories,'id_subcategories');
    foreach ($item as $data) {
      $id_subcategories2 = $data['id_subcategories'];  
    }
    if($id_subcategories == $id_subcategories2){
      $session->msg("d","The Subcategory Connected To Other Item.");
      redirect('categories.php');
    }

    //delete function
    $delete_id   = delete('id_subcategories','sub_categories',$id_subcategories);
    if($delete_id){
      $session->msg("s","Subcategory Has Been Deleted.");
      redirect('categories.php');
    } else {
      $session->msg("d","Subcategory Deletion Failed");
      redirect('categories.php');
    }  
  }
?>
<!-- END DELETE CATEGORY -->


<?php include_once('layouts/header.php'); ?>
<div class="body">
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <?php if ($user['level_user']==0 || $user['level_user']==1) { ?>
  <div class="col-lg-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>
              <i class="fa fa-tag"></i>
              <span>Add New Category</span>
            </strong>
          </div>
          
        <div class="panel-body">
          <form method="post" action="categories.php">
            <div class="form-group">
              <input type="text" class="form-control" required name="categories-name" placeholder="Category Name">
            </div>
            <button type="submit" title="Add Category" name="add_cat" title="Add Category" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add category</button> 
          </form>
        </div>
       </div>
      </div>
    </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>
                <i class="fa fa-tags"></i>
                <span>Add New Subcategory</span>
             </strong>
            </div>
            <div class="panel-body">
              <form method="post" action="categories.php">
                <div class="form-group">
                  <div class="custom-select">
                    <select class="form-control" name="id_categories">
                      <?php if($all_categories == null) { ?>
                        <option value="">-</option>
                          <?php } else { ?>
                            <?php foreach($all_categories as $row){ ?>
                              <option value="<?php echo remove_junk($row['id_categories']); ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                            <?php } ?>  
                          <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" required name="nm_subcategories" placeholder="Subcategory Name">
                </div>
                <button type="submit" title="Add Subcategory" name="add_subCategory" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;Add Subcategory</button>
            </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>

    <?php if ($user['level_user']==0 || $user['level_user']==1) { ?>
    <div class="col-lg-8">
    <?php } else { ?>
    <div class="col-lg-12">
    <?php } ?>
      <div class="form-component-container">
        <div class="panel panel-default form component main">
          <div class="panel-heading"> 
            <div class="tab-content">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li class="active col-md-6"><a href="#category" role="tab" data-toggle="tab">
                  <strong>
                    <center>
                      <i class="fa fa-tag"></i>
                      <span>All Categories</span>
                    </center>
                  </strong>
                </a></li>
                <li class="col-md-6"><a href="#sub_category" role="tab" data-toggle="tab">
                  <strong>
                    <center>
                      <i class="fa fa-tags"></i>
                      <span>All Subcategories</span>
                    </center>
                  </strong>
                </a></li>
              </ul>
            </div>
          </div>
              
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="category">
              <div class="panel panel-default">
              </div>
             
                <div class="panel-body">
                  <table id="CategoriesTable" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th class="text-center" style="width: 10px;">No.</th>
                        <th>Name Category</th>
                        <th class="text-center" style="width: 130px;">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $no=1; ?>
                    <?php foreach ($all_categories as $cat):?>
                      <tr>
                        <td class="text-center"><?php echo $no++.".";?></td>
                        <td><?php echo remove_junk(ucfirst($cat['nm_categories'])); ?></td>
                        <td class="text-center">
                          <button data-target="#updateCategory<?php echo $cat['id_categories'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit"><i class="glyphicon glyphicon-edit"></i>
                          </button>
                        <?php
                         if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) { ?>
                          <button data-target="#deleteCategory<?php echo $cat['id_categories'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete"><i class="glyphicon glyphicon-trash"></i>
                          </button>
                        <?php } ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
                
              <div class="tab-pane" id="sub_category">
                <div class="panel panel-default">
                </div>
                  <div class="panel-body">
                    <table id="SubcategoriesTable" class="table table-bordered table-striped table-hover">
                      <thead>
                        <tr>
                          <th class="text-center" style="width: 10px;">No.</th>
                          <th>Name Subcategory</th>
                          <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php $no=1; ?>
                      <?php foreach ($all_subcategories as $subcat):?>
                        <tr>
                          <td class="text-center"><?php echo $no++."."; ?></td>
                          <td><a href="#detailSubcategory<?php echo $subcat['id_subcategories'];?>" data-toggle="modal" title="Detail"><?php echo remove_junk(ucfirst($subcat['nm_subcategories'])); ?></a></td>
                          <td class="text-center">
                            <button data-target="#updateSubcategory<?php echo $subcat['id_subcategories'];?>" class="btn btn-md btn-warning" data-toggle="modal" title="Edit"><i class="glyphicon glyphicon-edit"></i>
                            </button>
                          <?php
                           if ($user['level_user']==0 || $user['level_user']==1 || $user['level_user']== 2) { ?>
                            <button data-target="#deleteSubategory<?php echo $subcat['id_subcategories'];?>" class="btn btn-md btn-danger" data-toggle="modal" title="Delete"><i class="glyphicon glyphicon-trash"></i>
                            </button>
                          <?php } ?>
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
        </div>
      </div>
    </div>
  </div>

<!-- Update Entry Data Categories -->
<?php foreach($all_categories as $a_category): ?>
<div class="modal fade" id="updateCategory<?php echo $a_category['id_categories'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-tag"></i> Update Data Category</h4>
        
      </div>
      <div class="modal-body">
        <form method="post" action="categories.php">
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_category['id_categories'])); ?>" name="id_categories">
            <div class="form-group">
              <label for="name">Category Name</label>
              <input type="text" class="form-control" value="<?php echo remove_junk(ucwords($a_category['nm_categories'])); ?>" name="nm_categories" placeholder="Full Name" required>
            </div>
      </div>
        <div class="modal-footer">
          <button type="button" title="Close" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="update_category" title="Update" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span>  Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- END Update Entry Data Category -->

<!-- Delete Modal Category -->
<?php foreach($all_categories as $a_category): ?> 
  <div class="modal fade" id="deleteCategory<?php echo $a_category['id_categories'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Confirm Delete</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete <b><u><?php echo remove_junk(ucwords($a_category['nm_categories'])); ?></u></b> ?
        <form method="post" action="categories.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_category['id_categories'])); ?>" name="id_categories">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_categories" title="Delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- DELETE MODAL CATEGORY -->

<!-- Update Data SubCategories -->
<?php foreach($all_subcategories as $a_subcategory): ?>
<div class="modal fade" id="updateSubcategory<?php echo $a_subcategory['id_subcategories'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-tags"></i> Update Data Subcategory</h4>
        
      </div>
      <div class="modal-body">
        <form method="post" action="categories.php">
          <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_subcategory['id_subcategories'])); ?>" name="id_subcategories">
            <div class="form-group">
              <div class="custom-select">
                <select class="form-control" name="id_categories">
                  <?php if($all_categories == null) { ?>
                    <option value="">-</option>
                  <?php } else { ?>
                    <?php foreach($all_categories as $row){ ?>
                      <option <?php if( $row['id_categories']==$a_subcategory['id_categories']){echo "selected"; } ?>  value="<?php echo remove_junk($row['id_categories']); ?>"><?php echo remove_junk(ucwords($row['nm_categories'])); ?></option>
                    <?php } ?>  
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="name">Subcategory Name</label>
              <input type="text" class="form-control" value="<?php echo remove_junk(ucwords($a_subcategory['nm_subcategories'])); ?>" name="nm_subcategories" placeholder="Subcategory" required>
            </div>
      </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="update_Subcategory" title="Update" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- END Update Entry Data SubCategory -->

<!-- DELETE MODAL SUBCATEGORR -->
<?php foreach($all_subcategories as $a_subcategory): ?>
  <div class="modal fade" id="deleteSubategory<?php echo $a_subcategory['id_subcategories'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><span class="glyphicon glyphicon-trash"></span> Confirm Delete</h4>
        </div>
        <div class="modal-body">
          Are You Sure Want To Delete <b><u><?php echo remove_junk(ucwords($a_subcategory['nm_subcategories'])); ?></u></b> ?
        <form method="post" action="categories.php" class="clearfix">
          <div class="form-group">
            <input type="hidden" class="form-control" value="<?php echo remove_junk(ucwords($a_subcategory['id_subcategories'])); ?>" name="id_subcategories">
          </div>    
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" title="Close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Close</button>
          <button type="submit" name="delete_Subcategories" title="Delete" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Delete</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- DELETE MODAL SUBCATEGORY -->

<!-- DETAIL MODAL SUBCATEGORY -->
<?php foreach($join_categories as $a_subcategory): ?>
  <div class="modal fade" id="detailSubcategory<?php echo $a_subcategory['id_subcategories'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><i class="fa fa-tags"></i> Detail Category</h4>
        </div>
        <div class="modal-body">
          <table border="0">
              <tbody>
                <tr>
                  <td width="90px">Category</td>
                  <td width="20px">:</td>
                  <td><b><?php echo ucwords($a_subcategory['nm_categories']);?></b></td>
                </tr>
              </tbody>
            </table>
        </div>
        <div class="modal-footer">
        </div>
      </form>
    </div>
  </div>
</div>
<?php endforeach;?>
<!-- DETAIL MODAL SUBCATEGORY -->

<?php include_once('layouts/footer.php'); ?>