<?php
  $page_title = 'All categories';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(1);
  
  $all_categories = find_all1('categories')
?>
<?php
 if(isset($_POST['add_cat'])){
   $req_field_id = array('id-categorie');
   $req_field = array('categorie-name');
   validate_fields($req_field_id);
   validate_fields($req_field);
   $id_cat = remove_junk($db->escape($_POST['id-categorie']));
   $cat_name = remove_junk($db->escape($_POST['categorie-name']));
   if(empty($errors)){
      $sql  = "INSERT INTO categories (id_categories,nm_categories)";
	  $sql .= " VALUES ('{$id_cat}','{$cat_name}')";
	if($db->query($sql)){
        $session->msg("s", "Successfully Added Categorie");
        redirect('categorie.php',false);
      } else {
        $session->msg("d", "Sorry Failed to insert.");
        redirect('categorie.php',false);
      }
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
   }
     /* $getAllCategoriesName = "SELECT id_categories,nm_categories FROM categories";
      $ada=$db->query($getAllCategoriesName) or die(mysql_error());
      if(mysqli_num_rows($ada)>0)
      { 
        $session->msg("d", "Category Is Exist");
        redirect('categorie.php',false);
      } else {
          if($db->query($sql)){
          $session->msg("s", "Successfully Added Categorie");
          redirect('categorie.php',false);
        } else {
          $session->msg("d", "Sorry Failed to insert.");
          redirect('categorie.php',false);
        }
      }      
   } else {
     $session->msg("d", $errors);
     redirect('categorie.php',false);
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
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Categorie</span>
         </strong>
        </div>
        <div class="panel-body">
          <form method="post" action="categorie.php">
		  <div class="form-group">
                <input type="text" class="form-control" name="id-categorie" placeholder="ID Categorie">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="categorie-name" onkeypress="return hanyaHuruf(event)" placeholder="Categorie Name">
            </div>
            <button type="submit" name="add_cat" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></span>&nbsp;&nbsp;&nbsp;Add categorie</button>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>All Categories</span>
       </strong>
      </div>
        <div class="panel-body">
          <table id="CategoriesTable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th>ID Categorie</th>
					<th>Name Categorie</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
              <?php foreach ($all_categories as $cat):?>
                <tr>
                    <td class="text-center"><?php echo count_id();?></td>
					<td><?php echo remove_junk(ucfirst($cat['id_categories'])); ?></td>
                    <td><?php echo remove_junk(ucfirst($cat['nm_categories'])); ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <a href="edit_categorie.php?id=<?php echo (int)$cat['id_categories'];?>"  class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
							<span class="glyphicon glyphicon-edit"></span>
                        </a>
                        <a href="delete_categorie.php?id=<?php echo (int)$cat['id_categories'];?>"  class="btn btn-xs btn-danger" data-toggle="tooltip" title="Remove">
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
